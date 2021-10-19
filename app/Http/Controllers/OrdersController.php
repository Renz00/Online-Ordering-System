<?php

namespace App\Http\Controllers;
use Exception;
use Carbon\Carbon;
use App\Models\Best;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Orders;
use App\Models\Products;
use App\Models\order_group;
use Illuminate\Support\Str;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Http\Response;
use App\Events\DashboardEvent;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Illuminate\Contracts\Encryption\DecryptException;

class OrdersController extends OrderAddressController
{
    //Calls auth middleware to prevent unauthorized access
    public function __construct(){

        $this->middleware('auth');
        

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('users')
        ->join('order_groups', 'users.id', '=', 'order_groups.user_id')
        ->select('order_groups.id', 'order_groups.status','order_groups.itemcount', 'order_groups.created_at', 'users.first_name', 'users.last_name')
        ->orderBy('order_groups.id', 'DESC')
        ->get();
    }
        
    public function getAddInfo(){
        
        $users = DB::table('users')
        ->join('addresses', 'users.id', '=', 'addresses.user_id')
        ->select('users.id', 'users.first_name','users.last_name', 'users.phone', 'addresses.description', 'addresses.notes')
        ->where('users.role', 'Customer')
        ->get();

        $products = Products::select('id', 'name','type', 'discount', 'price', 'discount_price')->where('available', '<>', '0')->get();
        $response = [
            'users' => $users,
            'products' => $products
        ];

        return response($response, 201);
    }

    public function createOrder(Request $request){
        //$request->user will return an object
        //$request->products will return objects within an array

        $itemcount = count($request->products);
        $group_id = $this->storeToOrderGroups('Processing', $itemcount, $request->user['id']);
        $result = 1;

        foreach ($request->products as $product){
            $order = new Orders;

            $order->order_group_id = $group_id;
            $order->total = $product['price'];
            $order->amount = $product['amount'];
            $order->status='Processing';
            $order->user_id = $request->user['id'];
            $order->product_id = $product['id'];

            $res = $order->save();
            if ($res != 1){
                $result = "Error";
            }

        }

        //Storing the order address
        $order_add = new OrderAddress;

        $order_add->order_group_id = $group_id;
        $order_add->user_id = $request->user['id'];
        $order_add->recipient = $request->user['first_name'].' '.$request->user['last_name'];
        $order_add->phone = $request->phone;
        $order_add->description = $request->address;
        $order_add->notes = $request->notes;

        $res2 = $order_add->save();

        $res3 = $this->createNotification($group_id,'order');

        if ($result == 1 && $res2 == 1 && $res3 == 1){
            NotificationEvent::dispatch();
            DashboardEvent::dispatch(); //Refresh admin dashboard
            return 'stored';
        }
        else {
            return 'Error';
        }

    }

    public function createNotification($id, $type){
        if ($type == 'order'){
            //Storing the order notification
            $notif = new Notifications;
            $notif->order_group_id = $id;
            $notif->is_viewed = false;
            $notif->type = $type;
            return $notif->save();
        }
    }

    public function showOrders($order_group){
        $orders = DB::table('order_groups')
        ->join('orders', 'order_groups.id', '=', 'orders.order_group_id')
        ->join('products', 'products.id', '=', 'orders.product_id')
        ->select('orders.user_id', 'orders.order_group_id','orders.status', 'orders.total','orders.amount', 'orders.status',
        'products.id', 'products.name', 'products.type', 'products.price', 'products.discount', 'products.discount_price', 
        'products.available')
        ->where('order_groups.id', $order_group)
        ->get();

        $user = User::select('id', 'first_name','last_name', 'phone')->where('id', $orders->first()->user_id)->get();

        $order_address = OrderAddress::where('order_group_id', $order_group)->get();

        $response = [
            'orders' => $orders,
            'order_address' => $order_address,
            'user' => $user
        ];

        return response($response, 201);
        
    }

    public function saveOrderUpdate(Request $request, $group_id){
        //in order to update the orders, i will delete the orders with the group_id and add the new orders
        $itemcount = count($request->products);
        $result1 = $this->updateOrderGroup($request->status, $itemcount, $group_id, $request->user['id']);
        if ($result1 == 1){
            Orders::where('order_group_id', $group_id)->delete();
        }
        $result2 = 1;
        
        foreach ($request->products as $product){
            $order = new Orders;

            $order->order_group_id = $group_id;

            if (array_key_exists('total', $product)){ //when new products are added to products list it will have price instead of total
                $order->total = $product['total'];
            }
            else {
                $order->total = $product['price'];
            }

            $order->amount = $product['amount'];
            $order->status=$request->status;
            $order->user_id = $request->user['id'];
            $order->product_id = $product['id'];
            
            $res = $order->save();

            if ($res == 1){
                //Incrementing count in Bests table
                if ($request->status == 'Delivered'){ //Only updating the bests table if an order has been recieved and paid
                    
                    $now = Carbon::now();
                    $date = Carbon::parse($now->year."-".$now->month."-01");
                    $start = $date->startOfMonth()->format('Y-m-d H:i:s'); //gets first day of current month
                    $end = $date->endOfMonth()->format('Y-m-d H:i:s'); // last day of month
                    $best = Best::where('product_id', $product['id'])->whereBetween('created_at', [$start, $end])->get();
                    if (count($best) > 0){ //if a record in Best table exists within the current month, update the record
                        Best::where('product_id', $product['id'])->increment('count', 1);
                    }
                    else { //else it will insert a new record
                        $bestnew = new Best;
                        $bestnew->count = 1;
                        $bestnew->product_id = $product['id'];
                        $bestnew->save();
                    }
                    
                }
            }
            else {
                $result2 = "Error";
            }

        }

        $order_add = OrderAddress::where('order_group_id', $group_id)->first();

        $order_add->order_group_id = $group_id;
        $order_add->user_id = $request->user['id'];
        $order_add->recipient = $request->user['first_name'].' '.$request->user['last_name'];
        $order_add->phone = $request->phone;
        $order_add->description = $request->address;
        $order_add->notes = $request->notes;


        $result3 = $order_add->save();
        $notifresult = $this->createNotification($group_id,'order');
        
        if ($result1 == 1 && $result2 == 1 && $result3 == 1 && $notifresult == 1){
            NotificationEvent::dispatch();
            DashboardEvent::dispatch(); //Refresh admin dashboard
            return 'updated';
        }
        else {
            return 'Error';
        }
    }

    public function updateOrderGroup($status, $itemcount, $group_id, $user_id){

        $order_group = order_group::find($group_id);
        $order_group->status = $status;
        $order_group->itemcount = $itemcount;
        $order_group->user_id = $user_id;
        
        return $order_group->save();


    }

    public function destroyOrder($id){
        return order_group::where('id', $id)->delete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Form input validation
        $this->validate($request, [
            'amount' => 'required|numeric|min:1|max:10'
        ]);

        // try{
            //Create order
            $order = new Orders;

            if (Session::has('product_id') && Session::has('product_price')){

                $order->product_id = Session::get('product_id');
                $order->total = Session::get('product_price') * $request->input('amount');

            }else{
                return route('error');
            }

            $order->amount = $request->input('amount');

            $order->user_id = Auth::id();

            $order->save();

            $data = $this->getOrders(Auth::id());

            //Deleting the session variables
            Session::forget('product_id');
            Session::forget('product_price');
            
            // $orders = Orders::where('user_id', Auth::id())->where('status', 'pending')->get();
            return redirect()->route('home')->with('orders', $data)->with('success', 'Order Created');
        // } catch(Exception $e) {
        //     return "Error!";
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = $this->explodeSlug($id);

        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }

        $data = $this->getOrders($id);
        $address = $this->getAddress($id);

        return view('pages/ordercheck')->with('orders', $data['orders'])->with('address', $address)->with('order_total', $data['order_total']);
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        //pulls the edit view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }

        //returns an error message rejected
        $request->validate([
            'recipient' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'nullable|numeric|regex:/(09)[0-9]{9}/',
            'notes' => 'nullable|max:500'
        ]);
            //updates the table
        if (Session::has('order_ids')){
            $itemcount = count(Session::get('order_ids'));
            $group_id = $this->storeToOrderGroups('Processing', $itemcount, $id);
            $message = '';

            foreach (Session::get('order_ids') as $order_id){

                $request->validate([
                    'amount_'.$order_id => 'required|numeric|min:1|max:10'
                ]);

                $order = Orders::find($order_id);

                $product = Products::find($order->product_id);
                
                //Setting status to Processing
                $order->status='Processing';
                
                //Recomputing the order total
                $amount = $request->input('amount_'.$order_id);
                $order->amount = $amount;

                if ($product->discount != null && $product->discount != 0){
                    $order->discount = $product->discount;
                    $price = $product->discount_price;
                }
                else {
                    $price = $product->price;
                }
                $order->total = $price * $amount;

                $order->order_group_id = $group_id;
                $order->save();
                
            }
            //Saving the order address in other controller
            $message = $this->saveOrderAddress($request, $group_id, $id);
            Session::forget('order_ids');

            $notifresult = $this->createNotification($group_id,'order');
            NotificationEvent::dispatch();
            DashboardEvent::dispatch(); //Refresh admin dashboard

            return redirect()->route('orders.user-orders', Auth::user()->slug)->with('order_group', $group_id)->with('order_message', $message);
        }
        else {
            return route('error');
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $id = Crypt::decrypt($id);

            $order = Orders::find($id);

            if (Auth::id() == $order->user_id){
                $order->delete();
            }
            else {
                return redirect()->back();
            }

        } catch (Exception $e){
            return redirect()->back();
        }
        return redirect()->back();
}

    public function destroyGroup($order_group){

        try{
            $order_group = Crypt::decrypt($order_group);
            order_group::where('id', $order_group)->where('user_id', Auth::id())->delete();
            NotificationEvent::dispatch();
            DashboardEvent::dispatch(); //Refresh admin dashboard

        } catch (Exception $e){
            return redirect()->back();
        }

        return redirect()->route('orders.user-orders', Auth::user()->slug)->with('deleted', 'Item Removed');
    }

    public function getOrders($id)
    {
        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }

        //Inner join orders and products table to send to modal
        $data['orders'] = DB::table('products')
        ->join('orders', 'products.id', '=', 'orders.product_id')
        ->select('orders.id', 'orders.user_id', 'products.name', 'products.price', 'products.discount', 'products.discount_price', 'orders.amount', 'orders.total')
        ->where('orders.user_id', $id)
        ->where('orders.status', null)
        ->get();

        $order_total = 0;

        foreach ($data['orders'] as $order){
            $order_total += $order->total;
        }

        $data['order_total'] = number_format($order_total, 2, '.', ',');

        //Will return a collection (orders) and a single value (order_total)
        return $data;

    }

    public function userOrders($id){

        $id = $this->explodeSlug($id);

        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }
    
        return view('pages/orders');
    
    }

    public function storeToOrderGroups($status, $itemcount, $id){

         //Create order
         $order_groups = new order_group;

         $order_groups->status = $status;
         $order_groups->itemcount = $itemcount;
         $order_groups->user_id = $id;
         $order_groups->save();

         return $order_groups->id;

    }

    public function orderAgain($order_group){

            $orders_again =  DB::table('orders')
            ->join('order_groups', 'order_groups.id', '=', 'orders.order_group_id')
            ->where('order_groups.id', $order_group)
            ->where('order_groups.user_id', Auth::id())
            ->get();

            foreach ($orders_again as $order_again){
                //Create order
                $order = new Orders;
                $order->product_id = $order_again->product_id;
                $order->total = $order_again->total;
                $order->amount = $order_again->amount;
                $order->user_id = Auth::id();
                $order->save();

                //Incrementing count in Bests table
                Best::where('product_id', $order_again->product_id)->increment('count');
            }
            DashboardEvent::dispatch(); //Refresh admin dashboard
            return $this->show(Auth::user()->slug);

            // //User id as parameter
            // $data = $this->getOrders($id);
            // $address = $this->getAddress($id);

            // return view('pages/ordercheck')->with('orders', $data['orders'])->with('address', $address)->with('order_total', $data['order_total']);
    }

    public function getOrderDetails($order_group)
    {
        //Inner join orders and products table to send to modal
        $data['orders'] = DB::table('products')
        ->join('orders', 'products.id', '=', 'orders.product_id')
        ->select('orders.id', 'orders.order_group_id', 'orders.user_id', 'orders.status', 'products.name', 'products.price', 'products.discount', 'products.discount_price', 'orders.amount', 'orders.total')
        ->where('orders.order_group_id', $order_group)
        ->where('orders.user_id', Auth::id())
        ->get();

        if (!$data['orders']->first()){
            //if this is true then a user tried to access another user's order via url
            return redirect()->back();
        }

        $order_total = 0;

        foreach ($data['orders'] as $order){
            $order_total += $order->total;
        }

        $data['order_total'] = number_format($order_total, 2, '.', ',');

        $address = $this->getOrderAddress($order_group);

        return view('pages/orderdetails')->with('orders', $data['orders'])->with('address', $address)->with('order_total', $data['order_total']);
    }

    public function cancelOrder($order_group){

        try {
            //updating values of multiple rows with condition
            $order_group = Crypt::decrypt($order_group);

            Orders::where('order_group_id', '=' ,$order_group)->update(['status' => 'Cancelled']);
            order_group::where('id', $order_group)->update(['status' => 'Cancelled']);
            DashboardEvent::dispatch(); //Refresh admin dashboard
            return redirect()->route('orders.user-orders', Auth::user()->slug);
        }
        catch (Exception $e){
            return redirect()->back();
        }
    }

    public function generateInvoice($order_group){

        try {
            $order_group = Crypt::decrypt($order_group);
        }
        catch (Exception $e){
            return redirect()->back();
        }

        $user_data['orders'] = DB::table('products')
        ->join('orders', 'products.id', '=', 'orders.product_id')
        ->select('orders.id', 'orders.order_group_id', 'orders.user_id', 'orders.status', 'products.name', 'products.price', 'products.discount', 'products.discount_price', 'orders.amount', 'orders.total')
        ->where('orders.order_group_id', $order_group)
        ->where('orders.user_id', Auth::id())
        ->get();

        $user_data['user'] = User::find(Auth::id())->get();

        $user_data['address'] = $this->getOrderAddress($order_group);

        $invoice = $this->invoice($user_data);

        return $invoice->stream();
        

    }

    public function generateInvoiceAPI($order_group){

        $user_data['orders'] = DB::table('products')
        ->join('orders', 'products.id', '=', 'orders.product_id')
        ->select('orders.id', 'orders.order_group_id', 'orders.user_id', 'orders.status', 'products.name', 'products.price', 'products.discount', 'products.discount_price', 'orders.amount', 'orders.total')
        ->where('orders.order_group_id', $order_group)
        ->get();

        $user_data['user'] = User::find($user_data['orders']->first()->user_id)->get();

        $user_data['address'] = $this->getOrderAddress($order_group);

        $invoice = $this->invoice($user_data);

        return $invoice->stream();
    }

    public function invoice($user_data){

        $current_time = Carbon::now()->timestamp; 

        $customer = new Buyer([
            'name'          => $user_data['user']->first()->first_name.' '.$user_data['user']->first()->last_name,
            'custom_fields' => [
                'email' => $user_data['user']->first()->email,
                'phone' => $user_data['user']->first()->phone
            ],
        ]);

        $grand_total = 0;

        foreach($user_data['orders'] as $order){

            if ($order->discount > 0){
                $items[] = (new InvoiceItem())->title($order->name)
                    ->quantity($order->amount)
                    ->pricePerUnit($order->discount_price)
                    ->discountByPercent(10);
            }
            else {
                $items[] = (new InvoiceItem())->title($order->name)
                ->quantity($order->amount)
                ->pricePerUnit($order->price);
            }

            $grand_total += $order->total;
        }
        
        $notes = [
            'Recipient: '.$user_data['address']->first()->recipient,
            'Address: '.$user_data['address']->first()->description,
            'Notes: '.$user_data['address']->first()->notes,
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make()
            ->buyer($customer)
            ->sequence($user_data['orders']->first()->order_group_id)
            ->serialNumberFormat('{SEQUENCE}')
            ->date($user_data['address']->first()->created_at)
            ->currencySymbol('₱')
            ->addItems($items)
            ->notes($notes)
            ->filename($user_data['user']->first()->first_name.' '.$user_data['user']->first()->last_name.'_'.$user_data['orders']->first()->order_group_id.'_'.$current_time);

        return $invoice;
    }

}
