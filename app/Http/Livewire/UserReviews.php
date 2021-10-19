<?php

namespace App\Http\Livewire;

use App\Models\Reviews;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notifications;
use App\Events\DashboardEvent;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserReviews extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $sort = 'latest';
    public $product_id = '';
    public $content = '';
    public $rating = '5';
    public $hasreview = '';

    protected $rules = [
        'content' => 'required|min:1',
        'rating' => 'required|numeric|min:1|max:5'
    ];

    public function mount($product)
    {
        $this->product_id = $product->id;
    }

    public function render()
    {
        $reviews = $this->sortBy();
        $count = $this->hasReview();
    
        return view('livewire.user-reviews', ['reviews' => $reviews, 'count' => $count]);
    }

    public function hasReview(){
        $user_review = Reviews::where('user_id', '=', Auth::id())
            ->where('product_id', '=', $this->product_id)->get();

        return count($user_review);
        
    }

    public function sortBy(){

        if ($this->sort == 'latest'){
            $reviews = DB::table('users')
                ->join('reviews', 'users.id', '=', 'reviews.user_id')
                ->where('reviews.product_id', $this->product_id)
                ->latest('reviews.id')
                ->paginate(5);
        }
        elseif($this->sort == 'oldest'){
            $reviews = DB::table('users')
                ->join('reviews', 'users.id', '=', 'reviews.user_id')
                ->where('reviews.product_id', $this->product_id)
                ->oldest('reviews.id')
                ->paginate(5);
        }
        elseif($this->sort == 'mine'){
            $reviews = DB::table('users')
                ->join('reviews', 'users.id', '=', 'reviews.user_id')
                ->where('users.id', Auth::id())
                ->where('reviews.product_id', $this->product_id)
                ->paginate(5);
        }
        else {
            $reviews = DB::table('users')
                ->join('reviews', 'users.id', '=', 'reviews.user_id')
                ->where('reviews.product_id', $this->product_id)
                ->where('reviews.rating', $this->sort)
                ->latest('reviews.id')
                ->paginate(5);
        }

        return $reviews;
    }

    public function storeReview(){
        //returns an error message rejected
        $this->validate();

        $reviews = new Reviews;

        $reviews->content = $this->content;
        $reviews->rating = $this->rating;
        $reviews->product_id = $this->product_id;
        $reviews->user_id = Auth::id();

        $result = $reviews->save();
        $this->createNotification($reviews->id, 'review', $result);
        $msg = $this->dispatchDashboard($result);
        return $result;

    }

    public function deleteReview($review_id){

        $review = Reviews::where('id', $review_id)
            ->where('user_id', Auth::id())
            ->where('product_id', $this->product_id);

        $result = $review->delete();

        $msg = $this->dispatchDashboard($result);

        return $result;

    }

    public function editReview($review_id){
        //this deletes the review and sets the value of content and rating with the previous values
        $review = Reviews::where('id', $review_id)
            ->where('user_id', Auth::id())
            ->where('product_id', $this->product_id);

        $this->content = $review->first()->content;
        $this->rating = $review->first()->rating;
        $result = $this->deleteReview($review_id);

        return $result;

    }

    public function dispatchDashboard($result){
        if ($result == 1){
            NotificationEvent::dispatch();
            DashboardEvent::dispatch(); //Refresh admin dashboard
        }
        return;
    }

    public function createNotification($id, $type, $result){
        if ($result == 1){
            if ($type == 'order'){
                //Storing the order notification
                $notif = new Notifications;
                $notif->order_group_id = $id;
                $notif->is_viewed = false;
                $notif->type = $type;
                $notif->save();
            }
            else if ($type == 'review'){
                 //Storing the order notification
                 $notif = new Notifications;
                 $notif->review_id = $id;
                 $notif->is_viewed = false;
                 $notif->type = $type;
                 $notif->save();
            }
        }
       return;
    }
}
