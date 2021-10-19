<a class="btn btn-outline-light" href="@if (Auth::check()) {{ route('orders.show', Auth::user()->slug) }} @endif">
@if ($order_count>0)
<i class="bi bi-bag-fill"></i>
@else
<i class="bi bi-bag"></i>
@endif Orders
<span style="background-color:#db423d;" class="badge text-white ms-1">
    {{$order_count}}
</span>
</a>