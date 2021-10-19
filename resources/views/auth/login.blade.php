@extends('layouts.app')

@section('content')

@if (Session::get('error_auth'))

<div>
    <div class="alert alert-light" role="alert">
        <div class="text-center"><h3><i class="bi bi-exclamation-triangle"></h3></i> 
            <h4 class="alert-heading"><strong>- Please login to Continue -</strong></h4>
            <div>
                No account? <a href="{{ route('register') }}">{{ __('Register Now') }}</a> 
            </div>
        </div>
    </div>
</div>
@endif
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="background-color: #fcf9f2;" class="card">
                <div style="background-color: #db423d;" class="card-header shadow-sm text-light"><h5 class="mt-1"><i class="bi bi-input-cursor"></i> {{ __('Login') }}</h5></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row py-2">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}
                                        </strong>
                                    </span>   
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row py-2">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row py-2">
                            <div class="col offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>           
                            @if (Route::has('password.request'))
                                    <div class="col-md-5">
                                        <div class="container-md">
                                            <a href="{{ route('password.request') }}">
                                                <b>{{ __('Forgot Your Password?') }}</b>
                                            </a>
                                        </div>
                                    </div>
                                    
                            @endif             
                        </div>
                        <div class="form-group row">
                            <div class="col offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> <b>{{ __('Login') }}</b>
                                </button>                                
                            </div>
                            <div class="col-md-5">
                                <div class="container-md">
                                    <a class="btn btn-success" href="{{ route('register') }}"><b><i class="bi bi-person-badge"></i> {{ __('Create Account') }}</b></a>
                                </div>
                            </div>           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts/inc/footer')
@endsection
