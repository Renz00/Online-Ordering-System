@extends('layouts/app')
@section('content')
    <!-- Section-->
    <section class="py-4">
        <div class="container px-4 px-lg-5">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{url('/')}}">Home</a></li>
                  <li style="color:#db423d;" class="breadcrumb-item" aria-current="page">My Orders</li>
                </ol>
              </nav>
        </div>    
        <div class="container px-4 px-lg-5 mt-4">
            <livewire:orders />
            </div>
    </section>
            <!-- Footer-->
            @include('layouts/inc/footer')
@endsection


