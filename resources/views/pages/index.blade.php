@extends('layouts/app')

@section('content')
    <!-- Header-->
    @include('layouts/inc/header')
    <!-- Section-->
    <section class="py-4 pt-0">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="d-block p-2"><i class="bi bi-check2-square"></i> Our Best Sellers</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @forelse ($products as $product)
                    <div class="col mb-5">
                        <div class="shadow-sm card h-100">
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
                    <h2 class="d-block p-2">No Products Available.</h2>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Footer-->
    @include('layouts/inc/footer')
@endsection
