@extends('layouts/app')
@section('content')
<!-- Section-->
        <section class="py-5">       
            <div class="container px-4 px-lg-5 mt-5">
                <div class="text-center">
                    <h2 class="text-danger">Session Timed Out</h2>
                    <hr>
                    <a class = "btn btn-lg btn-primary" href="{{ url('/') }}">Go Back</a>
                    </div>
            </div>
        </section>
                <!-- Footer-->
                @include('layouts/inc/footer')
@endsection