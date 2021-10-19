@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="background-color: #fcf9f2;" class="card">
                <div style="background-color: #db423d;" class="card-header shadow-sm text-light"><h5 class="mt-1"><i class="bi bi-person-badge"></i> {{ __('Create an Account') }}</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group row py-2">
                            <div class="col">
                                <div class="input-group">
                                    <input id="first_name" type="text" placeholder = "{{ __('First Name') }}" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input id="last_name" type="text" placeholder = "{{ __('Last Name') }}" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row py-2">
                                <div class="col">
                                    <div class="input-group">
                                        <input id="email" type="email" placeholder = "{{ __('E-Mail Address') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+63</span>
                                        </div>
                                        <div class="col">
                                            <input id="phone" type="text" placeholder = "{{ __('Phone Number') }}" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror  
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group row py-2">
                            <div class="col">
                                <div class="input-group">
                                    <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="input-group">
                                    <div class="col">
                                        <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row p-2">
                            <button type="submit" class="btn btn-success">
                                    <b><i class="bi bi-save"></i> {{ __('Register') }}</b>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts/inc/footer')
@endsection
