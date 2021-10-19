@extends('layouts/app')
@section('content')
<!-- Section-->
        <section class="py-2">   
            <div class="container px-4 px-lg-5 mt-2">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{url('/')}}">Home</a></li>
                      <li style="color:#db423d;" class="breadcrumb-item" aria-current="page">Product</li>
                    </ol>
                  </nav>
            </div>    
            <div class="container px-4 px-lg-5 mt-5">
                <h2><i class="bi bi-bag-check"></i> Add to Order</h2>
                <div class="col mb-5">
                    <div style="background-color: #fcf9f2;" class="card h-100">
                        <!-- Discount badge-->
                        <div class="row">
                            <div class="col-sm">
                                {!! Form::open(['action' => 'App\Http\Controllers\OrdersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                <div class="p-4">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">{{$product->name}}
                                    @if ($product->discount>0)  
                                        <em class="text-info">{{$product->discount}}% Off</em>

                                        </h5>
                                        <p><em>{{$product->description}}</em></p>
                                        <h5 id="#price">Price: ₱ <b>{{$product->discount_price}}</b></h5>
                                        <input name="price" id="#constprice" type="number" value = "{{$product->discount_price}}" hidden readonly>
                                    @else
                                        
                                        </h5>
                                        <p><em>{{$product->description}}</em></p>
                                        <h5 id="#price">Price: ₱ <b>{{$product->price}}</b></h5>
                                        <input name="price" id="#constprice" type="number" value = "{{$product->price}}" hidden readonly>
                                    @endif
                                    <hr>                               
                                    <div class="form-group">
                                        {{Form::label('amount', 'Quantity:', ['class' => 'pb-2'])}}
                                        @if ($product->discount>0)  
                                        {{Form::number('amount', '1', ['class' => 'form-control', 'min' =>'1', 'max' => '10', 'id' => '#amt', 'onchange'=>'changePrice('.$product->discount_price.')'])}}
                                            
                                        @else
                                        {{Form::number('amount', '1', ['class' => 'form-control', 'min' =>'1', 'max' => '10', 'id' => '#amt', 'onchange'=>'changePrice('.$product->price.')'])}}
                                            
                                        @endif
                                    </div>
                                    <div class="row bottom-0 start-0 mt-5">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success form-control"><b><i class="bi bi-arrow-right-square"></i> Proceed</b></button>
                                        </div>
                                        
                                        {{-- <a class="btn btn-danger" href="{{ url()->previous() }}">Cancel</a> --}}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="col-sm">
                                <div class="text-center">
                                    <img class="img-fluid" style="object-fit: fill; width: 600px; height: 400px;" width ='450' height= '300' src="https://picsum.photos/450/300" alt="Responsive image"  />
                                    <livewire:add-favorites :product='$product' />
                                </div>
                           
                            </div>
                        </div>
                    </div>
                    
                    <livewire:user-reviews :product='$product' />
                </div> 
            </div>
        </section>
        <!-- Footer-->
        @include('layouts/inc/footer')
@endsection