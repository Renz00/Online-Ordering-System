<div>
    <div class="container mt-2">
        <div class="row">
            <div style="background-color: #fcf9f2;" class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row justify-content-between">
                            <div class="col-4 mt-2">
                                <h5><b>Product Reviews</b></h5>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0" style="background-color:#fcf9f2">Sort By: </span>
                                    </div>
                                    <select wire:model="sort" id="sort" class="form-select">
                                        <option value='latest'>Latest</option>
                                        <option value='oldest'>Oldest</option>
                                        <option value='5'>5 stars</option>
                                        <option value='4'>4 stars</option>
                                        <option value='3'>3 stars</option>
                                        <option value='2'>2 stars</option>
                                        <option value='1'>1 star</option>
                                        <option value='mine'>My Review</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="container">
                        @if ($count == 0 && Auth::check())
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="input-group">
                                            {{ Form::textarea('review', '', ['class' => 'form-control', 'rows' => '2', 'placeholder' => 'Review Product...', 'wire:model' => 'content']) }}
                                            <div class="input-group-append">
                                            &nbsp;
                                            <button class="btn btn-primary" wire:click="storeReview()"><b>Submit <i class="bi bi-chevron-double-right"></i></b></button>
                                            </div>
                                        </div>
                                        <div class="alert-danger"><b>{{ $errors->first('content') }}</b></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0" style="background-color:#fcf9f2">Rating: </span>
                                        </div>
                                        <div class="col">
                                            <select wire:model="rating" id="stars" class="form-select" onchange="changeStars()">
                                                <option value='5'>5</option>
                                                <option value='4'>4</option>
                                                <option value='3'>3</option>
                                                <option value='2'>2</option>
                                                <option value='1'>1</option>
                                            </select>
                                            <div class="alert-danger"><b>{{ $errors->first('rating') }}</b></div>
                                        </div>
                                        &nbsp;
                                        <div class="col mt-1">
                                            <div class="text-center">
                                                <span id="show_stars"><i style="color:orange;" class="bi bi-star-fill"></i> 
                                                    <i style="color:orange;" class="bi bi-star-fill"></i> 
                                                    <i style="color:orange;" class="bi bi-star-fill"></i> 
                                                    <i style="color:orange;" class="bi bi-star-fill"></i> 
                                                    <i style="color:orange;" class="bi bi-star-fill"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @forelse($reviews as $review)
                        <div class="row my-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6>
                                                <img style="object-fit: cover; width: 35; height: 35px;" class="img-thumbnail" width="40" height="40" src="{{ URL::to('/') }}/storage/profile_images/{{$review->image}}" alt="..." />
                                                &nbsp;
                                                <b>{{ $review->first_name.' '.$review->last_name }}</b>
                                                &nbsp;
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                <span><i style="color:orange;" class="bi bi-star-fill"></i> </span>
                                                @endfor
                                            </h6>
                                        </div>
                                        <div class="col">
                                            <small>Posted on: {{ date('F j, Y | g:i A', strtotime($review->created_at)) }}</small>
                                        </div>
                                        <div class="col">
                                            @if(Auth::id() == $review->user_id)
                                                <div class="float-end">
                                                    <a wire:click="editReview({{ $review->id }})" class="btn btn-sm btn-outline-dark">Edit</a>
                                                    &nbsp;
                                                    <a wire:click="deleteReview({{ $review->id }})" class="btn btn-sm btn-outline-dark">Delete</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <p style="text-indent: 50px;" class="text-justify">{{ $review->content }}</p>
                                    </div>
                                </div>
                              </div>
                        </div>
                        @empty
                            <br>
                            <h5 class="text-center">No Reviews Found.</h5>
                        @endforelse
                    </div>
                </div>
              </div>
        </div>
    </div>
</div>
