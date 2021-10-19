@extends('layouts/app')
@section('content')
    <!-- Section-->
    <section class="py-3">
        <div class="container px-4 px-lg-5 mt-2">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{url('/')}}">Home</a></li>
                  <li style="color:#db423d;" class="breadcrumb-item" aria-current="page">Checkout</li>
                </ol>
              </nav>
        </div>
        <div class="container px-lg-5 mt-5">
            @if (count($orders) > 0)
            <h2><i class="bi bi-card-text"></i> Fill in your address details</h2>
            @endif
            <div class="col mb-5">
                <div style="background-color: #fcf9f2;" class="row border">
                    @if (count($orders) > 0)
                        <div class="col-sm">
                            {!! Form::open(['route' => ['orders.update', Auth::id()], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                            <div class="p-4">
                                @if (!$address->first())
                                    <div class="form-group">
                                        {{ Form::label('recipient', 'Name of Recipient:') }}
                                        {{ Form::text('recipient', Auth::user()->first_name . ' ' . Auth::user()->last_name, ['class' => 'form-control']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('recipient') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('phone', 'Contact Number:') }}
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+63</span>
                                            </div>
                                            {{ Form::text('phone', Auth::user()->phone, ['class' => 'form-control']) }}
                                        </div>
                                        <div class="alert-danger"><b>{{ $errors->first('phone') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('address', 'Address') }} <small> <em>(Only supports areas within
                                                Sorsogon City)</em></small>:
                                        {{ Form::textarea('address', '', ['class' => 'form-control', 'rows' => '2']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('address') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('notes', 'Other Notes:') }}
                                        {{ Form::textarea('notes', '', ['class' => 'form-control', 'rows' => '2']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('notes') }}</b></div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        {{ Form::label('recipient', 'Name of Recipient:') }}
                                        {{ Form::text('recipient', $address->first()->recipient, ['class' => 'form-control']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('recipient') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('phone', 'Contact Number:') }}
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+63</span>
                                            </div>
                                            {{ Form::text('phone', Auth::user()->phone, ['class' => 'form-control']) }}
                                        </div>
                                        <div class="alert-danger"><b>{{ $errors->first('phone') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('address', 'Address:') }}} <small> <em>(Only supports areas within
                                            Sorsogon City)</em></small>:
                                        {{ Form::textarea('address',  $address->first()->description, ['class' => 'form-control', 'rows' => '2']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('address') }}</b></div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('notes', 'Other Notes:') }}
                                        {{ Form::textarea('notes', $address->first()->notes, ['class' => 'form-control', 'rows' => '2']) }}
                                        <div class="alert-danger"><b>{{ $errors->first('notes') }}</b></div>
                                    </div>
                                @endif
                                <div class="form-group mt-4">
                                    <button class="btn btn-success form-control"
                                        type="submit"><b><i class="bi bi-arrow-right-square"></i> Checkout</b></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm border border-top-0 border-right-0 border-bottom-0 py-3">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col" style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="row">
                                    @if (Session::has('order_ids'))
                                        {{ Session::forget('order_ids') }}
                                    @endif
                                    @forelse($orders as $order)
                                        {{ Session::push('order_ids', $order->id) }}
                                        <tr>
                                            <td>
                                                @if ($order->discount > 0)
                                                    {{ $order->name }} <br>
                                                    <small><em class="text-info">{{ $order->discount }}% Off</em></small>
                                                @else
                                                    {{ $order->name }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-group w-50">
                                                    @if ($order->discount > 0)
                                                        <input type="number" name="amount_{{ $order->id }}"
                                                            id="#amt-{{ $order->id }}" class="form-control"
                                                            value="{{ $order->amount }}" min="1" max="10"
                                                            onchange="changePricewTotal({{ $order->id }}, {{ $order->discount_price }})">
                                                    @else
                                                        <input type="number" name="amount_{{ $order->id }}"
                                                            id="#amt-{{ $order->id }}" class="form-control"
                                                            value="{{ $order->amount }}" min="1" max="10"
                                                            onchange="changePricewTotal({{ $order->id }}, {{ $order->price }})">
                                                    @endif
                                                    <div class="alert-danger">
                                                        <b>{{ $errors->first('amount_'.$order->id) }}</b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="#price-{{ $order->id }}">{{ $order->total }}</td>
                                            <td>
                                                <a href="{{ route('orders.destroy', Crypt::encrypt($order->id)) }}"
                                                    class="btn btn-outline-danger"><i class="bi bi-trash"></i></a>
                                            </td>
                                            {{-- @include('layouts/inc/checkout_modal') --}}
                                        </tr>
                                    @empty
                                    <div class="text-center py-5">
                                        <h4 class="">No orders found.</h4>
                                        <br>
                                        <a href="{{ route('products.menu') }}" class="btn btn-lg btn-warning"><b><i class="bi bi-menu-button-wide"></i> See Full Menu</b></a>
                                    </div>
                                        
                                    @endforelse
                            </tbody>
                        </table>
                        <small>{{ count($orders) }} {{ Str::plural('item', count($orders)) }}</small>
                        <div class="text-right">
                            <br>
                            <h5>Grand Total: ₱ <b id="#total">{{ $order_total }}</b></h5>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
            <!-- Footer-->
            @include('layouts/inc/footer')
@endsection
