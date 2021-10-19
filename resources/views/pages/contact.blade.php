@extends('layouts/app')

@section('content')
    <!-- Header-->
    @include('layouts/inc/header')
    <!-- Section-->
    <section class="py-4 pt-0">
        <div class="container mt-4">
            <h2 class="d-block text-center p-2">- Contact Us -</h2>
            <div style="background-color: #fcf9f2;" class="card mx-5">
                <div class="card-body">
                    <div class="text-center">
                    <h5>If you have any inquiries, please contact us using the following numbers:</h5>
                    <h5><b>SMART</b>: 09396106522</h5>
                    <h5><b>GLOBE</b>: 09396106522</h5>
                    </div>
                </div>
            </div>
            <div style="background-color: #fcf9f2;" class="card mx-5 mt-2">
                <div class="card-body">
                    <div class="text-center">
                    <h5>Or visit our Facebook Page:</h5>
                    <a class="btn btn-lg btn-link" href="https://www.facebook.com/TLCexplore.IT/?ref=pages_you_manage" target="_blank">https://www.facebook.com/TLCexplore.IT/?ref=pages_you_manage</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
            <!-- Footer-->
            @include('layouts/inc/footer')
@endsection
