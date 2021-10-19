{{-- Do not forget to enclose all the html inside a single div --}}
<div>
<div class="row justify-content-between">
    <div class="col-md-4">
        <h2><i class="bi bi-bag-fill"></i> All Orders</h2>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" wire:model="search" class="form-control"  placeholder='Search...'>
        </div>
    </div>  
</div>
<div class="col mb-5">
    <div style="background-color: #fcf9f2;" class="card h-100">
        <!-- Discount badge-->
        <div class="row">
            <div class="col">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button style="background-color: #fcf9f2;" class="nav-link @if($status == 'All') active text-dark @endif" wire:click="changeStatus('All')">All</button>
                    </li>
                    <li class="nav-item">
                        <button style="background-color: #fcf9f2;" class="nav-link text-secondary @if($status == 'Processing') active text-dark  @endif" wire:click="changeStatus('Processing')" >Processing</button>
                    </li>
                    <li class="nav-item">
                        <button style="background-color: #fcf9f2;" class="nav-link text-secondary @if($status == 'Outgoing') active text-dark  @endif" wire:click="changeStatus('Outgoing')">Outgoing</button>
                    </li>
                    <li class="nav-item">
                        <button style="background-color: #fcf9f2;" class="nav-link text-secondary @if($status == 'Delivered') active text-dark  @endif" wire:click="changeStatus('Delivered')">Delivered</button>
                    </li>
                    <li class="nav-item">
                        <button style="background-color: #fcf9f2;" class="nav-link text-secondary @if($status == 'Cancelled') active text-dark  @endif" wire:click="changeStatus('Cancelled')">Cancelled</button>
                    </li>
                </ul> 
                @forelse ($orders as $order)
                    <div style="background-color: #fcf9f2;" class="card border-top-0">
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col mt-2">
                                        <h6><b>Order Ref: </b>{{ $order->id }}</h6>
                                    </div>
                                    <div class="col mt-2">
                                        <h5><span class="badge 
                                        @if ($order->status ==
                                                'Processing') bg-danger
                                            @elseif($order->status ==
                                                'Outgoing')
                                                bg-primary
                                            @elseif($order->status ==
                                                'Delivered')
                                                bg-success
                                            @elseif($order->status ==
                                                'Cancelled')
                                                bg-dark 
                                        @endif">{{ $order->status }}</span></h5>
                                    </div>
                                    <div class="col mt-2">
                                        <h6>{{ $order->itemcount.' '.Str::plural('item', $order->itemcount) }} </h6>
                                    </div>
                                    <div class="col mt-2">
                                        <h6><b>Created at: </b>{{ date('F j, Y | g:i A', strtotime($order->created_at)) }}</h6>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" wire:click="gotoOrderDetails({{ $order->id }})">View Details <i class="bi bi-caret-right-fill"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <h4 class="text-center py-5">No orders found.</h4>
                @endforelse

            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            @if ($orders->lastPage() > 1)
                {!! $orders->links() !!}
            @endif
        </div>
    </div>
</div>