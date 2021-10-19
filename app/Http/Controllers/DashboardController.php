<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Orders;
use App\Models\Reviews;
use App\Models\order_group;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(){

        $startmonth = 1;
        $endmonth = 12;
        $curyear = Carbon::now()->format('Y');

        $startdate = Carbon::parse($curyear."-".$startmonth."-01");
        $enddate = Carbon::parse($curyear."-".$endmonth."-01");
        $start = $startdate->startOfMonth()->format('Y-m-d H:i:s');
        $end = $enddate->endOfMonth()->format('Y-m-d H:i:s');
        //Data for the current year
        $totalusers  = User::where('role', 'Customer')->whereBetween('created_at', [$start, $end])->get();
        $totalsales = order_group::where('status', 'Delivered')->whereBetween('created_at', [$start, $end])->get();
        $totalorders = order_group::where('status', '<>', 'Cancelled')->whereBetween('created_at', [$start, $end])->get(); 
        
        $orders = Orders::select('id', 'total', 'created_at')
        ->where('status', 'Delivered')
        ->whereBetween('created_at', [$start, $end])
        ->get();

        $groupedorders = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('F');
        });

        $products = DB::table('products') //get best products
        ->join('orders', 'products.id', '=', 'orders.product_id')
        ->select('products.id', 'products.name', 'products.type')
        ->whereBetween('orders.created_at', [$start, $end])
        ->get();

        $groupedproducts = $products->groupBy(function($d) {
            return $d->name;
        })->take(8); //limit group by 8 records

        $recents = $this->getNotifications();

        $response = [
            'totalusers' => count($totalusers),
            'totalsales' => count($totalsales),
            'totalorders' => count($totalorders),
            'orders' => $groupedorders,
            'products' => $groupedproducts,
            'recentorders' => $recents['response']['recentorders'],
            'recentreviews' => $recents['response']['recentreviews']
        ];

        return response($response, 201);

    }

    public function getOrderReports(Request $request){
        $start = $request->start;
        $end = $request->end;
        $orders = Orders::select('id', 'total', 'created_at')
        ->where('status', 'Delivered')
        ->whereBetween('created_at', [$start, $end])
        ->get();

        $groupedorders = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('F');
        });

        $years = DB::table('orders')->select(DB::raw('DISTINCT YEAR(created_at) as date'))->get(); //getting unique year from created_at

        $response = [
            'groupedorders' => $groupedorders,
            'years' => $years
        ];

        return response($response, 201);
    }

    public function getProductReports(Request $request){
        $start = $request->start;
        $end = $request->end;
        $products = DB::table('products')
        ->join('bests', 'bests.product_id', '=', 'products.id')
        ->select('products.id', 'products.name','bests.count', 'bests.created_at')
        ->whereBetween('bests.created_at', [$start, $end])
        ->get();

        //grouping the months by year
        $dates = DB::table('bests')->select(DB::raw('DISTINCT MONTH(created_at) as month, created_at'))->orderBy('created_at', 'ASC')->get()->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('Y');
        });

        $response = [
            'products' => $products,
            'dates' => $dates,
        ];

        return response($response, 201);
    }

    public function getNotifications(){
        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        //Recent orders and reviews within 24 hours
        $recentorders = DB::table('users')
        ->join('order_groups', 'users.id', '=', 'order_groups.user_id')
        ->join('notifications', 'notifications.order_group_id', '=', 'order_groups.id')
        ->select('order_groups.id', 'order_groups.status', 'order_groups.itemcount', 'users.first_name', 'users.last_name', 'users.image', 'notifications.is_viewed', 'notifications.type', 'order_groups.created_at')
        ->where('notifications.is_viewed', '<>', true)
        ->whereBetween('order_groups.created_at', [$start, $end])
        ->get();

        $recentreviews = DB::table('users')
        ->join('reviews', 'users.id', '=', 'reviews.user_id')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->join('notifications', 'notifications.review_id', '=', 'reviews.id')
        ->select('reviews.id', 'reviews.rating','users.first_name', 'users.last_name', 'users.image', 'products.name', 'notifications.is_viewed', 'notifications.type', 'reviews.created_at')
        ->where('notifications.is_viewed', '<>', true)
        ->whereBetween('reviews.created_at', [$start, $end])
        ->get();

        $count  = count($recentorders) + count($recentreviews);

        $response = [
            'recentorders' => $recentorders,
            'recentreviews' => $recentreviews,
            'count' => $count
        ];
        return ['response' => $response];
    }

    public function updateNotifications(Request $request){
        if ($request->type == 'order'){
            $result = DB::table('notifications')
            ->where('notifications.order_group_id', $request->id)
            ->update(['is_viewed' => true]);
        }
        else if ($request->type == 'review'){
            $result = DB::table('notifications')
            ->where('notifications.review_id', $request->id)
            ->update(['is_viewed' => true]);
        }
        NotificationEvent::dispatch();
        return $result;
    }

    //Edits a excel template inside public/assets and download it
    public function print(Request $request){ 
        $start = $request->start;
        $end = $request->end;

        $orders = Orders::select('id', 'total', 'created_at')
        ->where('status', 'Delivered')
        ->whereBetween('created_at', [$start, $end])
        ->get();

        $groupedorders = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('F');
        });

        $year = Carbon::parse($orders->first()->created_at)->format('Y');
        $ss = IOFactory::load('assets/report_temp.xlsx'); //retrieves the excel template
        $ss->getActiveSheet()->setCellValue('A1', 'Sales Report for the Year '.$year);

        $ctr = 3;
        $gtotal =0;

        foreach ($groupedorders as $index => $item){
            $total = 0;
            $ss->getActiveSheet()->setCellValue('A'.$ctr, $index);
            foreach ($groupedorders[$index] as $order){
                $total += $order->total;
            }
            $ss->getActiveSheet()->setCellValue('B'.$ctr, $total);
            $gtotal += $total;
            $ctr++;
        }
        $ss->getActiveSheet()->setCellValue('A'.$ctr, 'GRAND TOTAL:');
        $ss->getActiveSheet()->setCellValue('B'.$ctr, $gtotal);
        
        $filename = $year.'.xlsx';
        $writer = new Xlsx($ss);
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Cache-Control','max-age=0');
        return $response;   
    }
}
