<?php

namespace App\Events;

use App\Models\User;
use App\Models\Orders;
use App\Models\order_group;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DashboardEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function broadcastWith(){ //return value must be in array format
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

        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        //Recent orders and reviews within 24 hours
        $recentorders = DB::table('users')
        ->join('order_groups', 'users.id', '=', 'order_groups.user_id')
        ->select('order_groups.id', 'order_groups.status', 'order_groups.itemcount', 'users.first_name', 'users.last_name', 'users.image', 'order_groups.created_at')
        ->whereBetween('order_groups.created_at', [$start, $end])
        ->get();

        $recentreviews = DB::table('users')
        ->join('reviews', 'users.id', '=', 'reviews.user_id')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->select('reviews.id', 'reviews.rating','users.first_name', 'users.last_name', 'users.image', 'products.name','reviews.created_at')
        ->whereBetween('reviews.created_at', [$start, $end])
        ->get();

        $response = [
            'totalusers' => count($totalusers),
            'totalsales' => count($totalsales),
            'totalorders' => count($totalorders),
            'orders' => $groupedorders,
            'products' => $groupedproducts,
            'recentorders' => $recentorders,
            'recentreviews' => $recentreviews
        ];
        return ['response' => $response];
    }

    public function broadcastAs()
    {
        return 'dashboard-event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('DASHBOARD.YzuwyaG5MaT77AGtYrjq');
    }
}
