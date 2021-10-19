{{-- @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{$error}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endforeach
@endif --}}

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <div class="text-center">
            <h5><strong> {{session('error')}} </strong></h5>
        </div>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif

@if (session('order_message') == 'success')
    <div class="alert alert-light alert-dismissible fade show mt-2" role="alert">
        <div class="text-center">
            <h5><strong>- Your order is currently being processed -</strong></h5>
            <p>This order will arrive within 30-40 minutes. <small>Order Ref: {{session('order_group')}}</small>
            <br>Enjoy your meal!</p>
        </div>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif

@if (session('message'))
    <div class="alert alert-light alert-dismissible fade show mt-2" role="alert">
        <div class="text-center">
            <h5><strong>- Changes have been saved. -</strong></h5>
        </div>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
