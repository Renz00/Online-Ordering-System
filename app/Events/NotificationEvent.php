<?php

namespace App\Events;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function broadcastWith(){ //return value must be in array format
        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        //Recent orders and reviews within 24 hours
        $recentorders = DB::table('users')
        ->join('order_groups', 'users.id', '=', 'order_groups.user_id')
        ->join('notifications', 'notifications.order_group_id', '=', 'order_groups.id')
        ->select('order_groups.id', 'order_groups.status', 'order_groups.itemcount', 'users.first_name', 'users.last_name', 'users.image', 'notifications.type', 'notifications.is_viewed', 'order_groups.created_at')
        ->where('notifications.is_viewed', '<>', true)
        ->whereBetween('order_groups.created_at', [$start, $end])
        ->get();

        $recentreviews = DB::table('users')
        ->join('reviews', 'users.id', '=', 'reviews.user_id')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->join('notifications', 'notifications.review_id', '=', 'reviews.id')
        ->select('reviews.id', 'reviews.rating','users.first_name', 'users.last_name', 'users.image', 'products.name', 'notifications.type', 'notifications.is_viewed', 'reviews.created_at')
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

    public function broadcastAs()
    {
        return 'notification-event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('NOTIFICATION.EMPb2LGWfXTtNOjTEef3');
    }
}
