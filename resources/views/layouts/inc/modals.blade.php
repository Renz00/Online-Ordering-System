@if (session('success'))
    <script>
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>
        <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalTitle">- Your Order -</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 ml-auto"><h5>Item</h5></div>
                    <div class="col-md-4 ml-auto"><h5>Quantity</h5></div>
                    <div class="col-md-4 ml-auto"><h5>Price</h5></div>
                </div>
                @if(count(session('orders')['orders'])>0)
                    @foreach (session('orders')['orders'] as $order)
                        <div class="row">
                            <div class="col-md-4 ml-auto"><b>{{$order->name}}</b></div>
                            <div class="col-md-4 ml-auto">{{$order->amount}}</div>
                            <div class="col-md-4 ml-auto">₱ {{$order->total}} @if($product->discount>0)<em>{{$order->discount}}</em>@endif</div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <br>
                        <h5>Grand Total: ₱ <b>{{session('orders')['order_total']}}</b></h5>
                    </div>
                @else
                    <h5>No orders found.</h3>
                @endif
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col">
                        <a class="btn btn-lg btn-success" href="/"><i class="bi bi-box-arrow-left"></i> Continue with Order</a>
                    </div>
                    <div class="col">
                        <a class="btn btn-lg btn-primary" href="@if (Auth::check()) {{ route('orders.show', Auth::user()->slug)}} @endif"><i class="bi bi-bag-check-fill"></i> Proceed to Checkout</a>
                    </div>
                    
                </div>
            </div>
        </div>
        </div>
    </div>
@endif

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="passwordModalLabel"><i class="bi bi-key"></i> Change your Password</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
            @if(Auth::check())
            {!! Form::open(['route' => ['user.update', Auth::user()->slug], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group mb-2">
                {{Form::label('current_password', 'Current Password:', ['class' => 'pb-2'])}}
                {{Form::password('current_password', ['class' => 'form-control', 'required' => true])}}
            </div>
            <div class="form-group mb-2">
                {{Form::label('password', 'New Password:', ['class' => 'pb-2'])}}
                {{Form::password('password', ['class' => 'form-control', 'required' => true])}}
            </div>
            <div class="form-group mb-2">
                {{Form::label('password_confirmation', 'Confirm Password:', ['class' => 'pb-2'])}}
                {{Form::password('password_confirmation', ['class' => 'form-control', 'required' => true])}}
            </div>
            
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-warning" data-dismiss="modal">Cancel</a>
          <button type="submit" name="submit_button" class="btn btn-primary" value="password">Save Changes</button>
        </div>
        {!! Form::close() !!}
        @endif
      </div>
    </div>
  </div>

  <div class="modal fade" id="userDeleteModal" tabindex="-1" role="dialog" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userDeleteModalLabel"><i class="bi bi-person-x"></i> Delete your Account</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
            <div class="text-center">
                <h4 class="text-danger">Warning!</h4>
                <p><b>A deleted account cannot be recovered.</b></p>
                <hr>
            </div>
            @if(Auth::check())
            {!! Form::open(['route' => ['user.update', Auth::user()->slug], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group mb-2">
                {{Form::label('user_password', 'Password:', ['class' => 'pb-2'])}}
                {{Form::password('user_password', ['class' => 'form-control', 'required' => true])}}
            </div>
            <div class="form-group mb-2">
                {{Form::label('user_password_confirmation', 'Confirm Password:', ['class' => 'pb-2'])}}
                {{Form::password('user_password_confirmation', ['class' => 'form-control', 'required' => true])}}
            </div>
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-warning" data-dismiss="modal">Cancel</a>
          <button type="submit" name="submit_button" class="btn btn-primary" value="delete">I understand.</button>
        </div>
        {!! Form::close() !!}
        @endif
      </div>
    </div>
  </div>