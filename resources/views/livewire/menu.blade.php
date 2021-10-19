<div class="container px-4 px-lg-5 mt-4">            
    {{-- @if (Session::has('message'))
            {{Session::get('message')}}
        @endif --}}
    {{-- <h2 class="d-block p-2">Our Best Sellers</h2> --}}
    
    <div class="row mb-3 justify-content-between">
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0" style="background-color: #faf0dc;"><b>Select Category</b></span>
                </div>
                <select wire:model="category" class="form-select" aria-label="Default select example">
                    <option value='All'>All</option>
                    <option value='Discount'>Discounts</option>
                    <option value='Combo'>Combo Meals</option>
                    <option value='Appetizer'>Appetizers</option>
                    <option value='Sandwich'>Sandwiches</option>
                    <option value='Pasta'>Pasta</option>
                    <option value='Drinks'>Drinks</option>
                </select>
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model='search' placeholder='Search...'>
            </div>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @forelse ($products as $product)
            <div class="col-md mb-5">
                <div class="shadow-sm card h-auto">
                    <!-- Discount badge-->
                    @if ($product->discount > 0)
                        <div class="badge bg-info text-white position-absolute" style="top: 0.5rem; right: 0.5rem">
                            {{ $product->discount }}% Off</div>
                    @endif
                    <!-- Product image-->
                    <img class="card-img-top" src="https://picsum.photos/450/300" alt="..." />
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="text-center">
                            <!-- Product name-->
                            <h5 class="fw-bolder">{{ $product->name }}</h5>
                            <hr>
                            <!-- Product price-->
                            @if ($product->discount > 0)
                                <h5><s>₱ {{ $product->price }}</s> ₱ {{ $product->discount_price }}</h5>
                            @else
                                <h5>₱ {{ $product->price }}</h5>
                            @endif
                        </div>
                    </div>
                    <!-- Product actions-->
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center">
                            <a class="btn btn-danger mt-auto"
                                href="{{ route('products.show', $product->slug) }}"><b><i class="bi bi-bag-plus"></i> Add to Order</b></a>
                        </div>
                    </div>
                    
                </div>
            </div>
        @empty
            <h2 class="text-center">No Products Available.</h2>
        @endforelse
    </div>
    <div class="d-flex justify-content-center">
        {!! $products->links() !!}
    </div>
</div>