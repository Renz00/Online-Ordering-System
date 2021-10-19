@extends('layouts/app')
@section('content')
    <!-- Section-->
    <section class="py-4">
        <div class="container px-4 px-lg-5">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{route('orders.user-orders', Auth::user()->slug)}}">My Orders</a></li>
              <li style="color:#db423d;" class="breadcrumb-item" aria-current="page">Details</li>
            </ol>
          </nav>
        </div>
        <div class="container mt-2 px-5">
        {{-- Progress Bar --}} 
            @if ($orders->first()->status == 'Delivered')
                <div class="text-center">
                    <h5>Order has been Delivered!</h5>
                </div>
            @endif
            <div class="progress" style="height: 25px;">
                @if ($orders->first()->status == 'Processing')
                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"><h6 class="text mt-2"><b>PROCESSING</b></h6></div>
                @elseif($orders->first()->status == 'Outgoing')
                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"><h6 class="text mt-2"><b>OUTGOING</b></h6></div>
                @elseif($orders->first()->status == 'Delivered')
                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                        style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"><h6 class="text mt-2"><b>DELIVERED</b></h6></div>
                @elseif($orders->first()->status == 'Cancelled')
                    <div class="progress-bar progress-bar-striped bg-dark" role="progressbar"
                        style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                        aria-valuemax="100"><h6 class="text mt-2"><b>CANCELLED</b></h6></div>
                @endif
            </div>
        </div>
        <div class="container px-lg-5 mt-5">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2><i class="bi bi-card-text"></i> Order Ref: {{ $orders->first()->order_group_id }}</h2>
                </div>
                <div class="col-4 mt-1">
                    <div class="float-end">
                        @if ($orders->first()->status == 'Delivered' || $orders->first()->status == 'Cancelled')
                        <a href="{{ route('orders.again', $orders->first()->order_group_id) }}" class="btn btn-warning"><b><i class="bi bi-arrow-repeat"></i> Order Again</b></a>
                        @endif
                        @if ($orders->first()->status != 'Cancelled')
                        <a href="{{ route('orders.invoice', Crypt::encrypt($orders->first()->order_group_id)) }}" class="btn btn-primary"><b><i class="bi bi-receipt-cutoff"></i> Print Invoice</b></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col mb-5">
                <div style="background-color: #fcf9f2;" class="row border">
                    @if (count($orders) > 0)
                        <div class="col-sm">
                            <div class="p-4">
                                <div class="form-group">
                                    {{ Form::label('', 'Name of Recipient:') }}
                                    {{ Form::text('', $address->first()->recipient, ['class' => 'form-control', 'style' => 'background-color:white;', 'disabled', 'readonly']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('', 'Contact Number:') }}
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+63</span>
                                        </div>
                                        {{ Form::text('', Auth::user()->phone, ['class' => 'form-control', 'style' => 'background-color:white;', 'disabled', 'readonly']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('', 'Address:') }}} <small> <em>(Only supports areas within
                                        Sorsogon City)</em></small>:
                                    {{ Form::textarea('',  $address->first()->description, ['class' => 'form-control', 'rows' => '2', 'style' => 'background-color:white;', 'disabled', 'readonly']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('', 'Other Notes:') }}
                                    {{ Form::textarea('', $address->first()->notes, ['class' => 'form-control', 'rows' => '2', 'style' => 'background-color:white;', 'disabled', 'readonly']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm border border-top-0 border-right-0 border-bottom-0 py-3">
                        <div class="row">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:55%;">Item</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="row">
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>
                                                    @if ($order->discount > 0)
                                                        {{ $order->name }} 
                                                        <small><em class="text-info">{{ $order->discount }}% Off</em></small>
                                                    @else
                                                        {{ $order->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $order->amount }}</td>
                                                <td>{{ $order->total }}</td>
                                            </tr>
                                        @empty
                                            <h4 class="text-center py-5">No orders found.</h4>
                                        @endforelse
                                </tbody>
                            </table>
                            <small>{{ count($orders) }} {{ Str::plural('item', count($orders)) }}</small>
                            <div class="text-right">
                                <br>
                                <h5>Grand Total: ₱ <b>{{ $order_total }}</b></h5>
                            </div>
                        </div>
                        <div class="row h-25"></div>
                        <div class="row">
                            @if ($orders->first()->status == 'Processing')
                            <div class="col">
                                {!! Form::open(['route' => ['orders.cancel', Crypt::encrypt($orders->first()->order_group_id)], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                                    <button type="submit" class="btn btn-outline-warning"><b><i class="bi bi-x-octagon"></i> Cancel Order</b></button>
                                    <em><small> *Order can only be cancelled while processing.</small></em>
                                {!! Form::close() !!}
                            </div>
                            @elseif ($orders->first()->status == 'Cancelled' || $orders->first()->status == 'Delivered')
                                <div class="col">
                                    <a href="{{ route('orders.destroy-group', Crypt::encrypt($orders->first()->order_group_id)) }}"
                                        class="btn btn-outline-danger"><b><i class="bi bi-trash"></i> Delete Order</b></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            <!-- Footer-->
            @include('layouts/inc/footer')
@endsection
