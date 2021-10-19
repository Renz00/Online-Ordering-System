@extends('layouts/app')
@section('content')

<!-- Section-->
        <section class="py-4">
            <div class="container px-4 px-lg-5 mt-2">       
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none text-dark" href="{{url('/')}}">Home</a></li>
                  <li style="color:#db423d;" class="breadcrumb-item" aria-current="page">My Account</li>
                </ol>
              </nav>
            </div>
            <div class="container px-4 px-lg-5 mt-4">
                <h2><i class="bi bi-person-badge"></i> Manage Your Account</h2>
                <div class="col mb-5">
                    <div style="background-color: #fcf9f2;" class="card h-100">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a style="background-color: #fcf9f2;" class="nav-link active text-dark" aria-current="page"
                                    href="{{ route('user.edit', [Auth::user()->slug, 'account']) }}">Account Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary"
                                    href="{{ route('user.edit', [Auth::user()->slug, 'address']) }}">Address Details</a>
                            </li>
                        </ul>
                        {!! Form::open(['route' => ['user.update', Auth::user()->slug], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                        <div class="row">
                            
                            <div class="col-sm">
                                <div class="text-center py-3 px-3">
                                    <img style="object-fit: cover; width: 300px; height: 300px;" class="img-thumbnail" width="300" height="300" src="{{ URL::to('/') }}/storage/profile_images/{{$user->first()->image}}" alt="..." />                                 
                                    <hr>
                                        {{-- Creating a custom file input design --}}
                                        <label class="btn btn-outline-primary" style="outline-color: purple;" >
                                            <input type="file" name="profile_image" id="profile_image"/>
                                            <b><i class="bi bi-upload"></i> Change Profile</b>
                                        </label>
                                    <br>
                                    <span id="file_path"></span>
                                    <div class="alert-danger"><b>{{ $errors->first('profile_image') }}</b></div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="p-4">                                   
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('first_name', 'First Name:', ['class' => 'pb-2'])}}
                                                {{Form::text('first_name', $user->first()->first_name, ['class' => 'form-control', 'required' => true])}}
                                                <div class="alert-danger"><b>{{ $errors->first('first_name') }}</b></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('last_name', 'Last Name:', ['class' => 'pb-2', 'required' => true])}}
                                                {{Form::text('last_name', $user->first()->last_name, ['class' => 'form-control'])}}
                                                <div class="alert-danger"><b>{{ $errors->first('last_name') }}</b></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('email', 'Email:', ['class' => 'pb-2', 'required' => true])}}
                                                {{Form::text('email', $user->first()->email, ['class' => 'form-control'])}}
                                                <div class="alert-danger"><b>{{ $errors->first('email') }}</b></div>
                                            </div>
                                            
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('phone', 'Phone:', ['class' => 'pb-2'])}}
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+63</span>
                                                    </div>
                                                {{Form::text('phone',$user->first()->phone, ['class' => 'form-control'])}}
                                                <div class="alert-danger"><b>{{ $errors->first('phone') }}</b></div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mt-3">
                                                <a class="btn btn-outline-primary" data-toggle="modal" data-target="#passwordModal"><b><i class="bi bi-key"></i> Change Password?</b></a>
                                                <div class="alert-danger"><b>{{ $errors->first('current_password') }}</b></div>
                                                <div class="alert-danger"><b>{{ $errors->first('password') }}</b></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mt-3">
                                                <a data-toggle="modal" data-target="#userDeleteModal" class="btn btn-outline-danger"><b><i class="bi bi-person-x"></i> Delete Account</b></a>
                                                <div class="alert-danger"><b>{{ $errors->first('user_password') }}</b></div>
                                                <div class="alert-danger"><b>{{ $errors->first('user_password_confirmation') }}</b></div>
                                                {{-- <a href="{{ route('user.destroy', Crypt::encrypt(Auth::id())) }}" class="btn btn-outline-danger"><b><i class="bi bi-person-x"></i> Delete Account</b></a> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bottom-0 start-0 mt-5">
                                        <div class="form-group">
                                            <button type="submit" name="submit_button" class="btn btn-success form-control" value="save"><b><i class="bi bi-save2"></i> Save Changes</b></button>
                                        </div>
                                        {{-- <a class="btn btn-danger" href="{{ url()->previous() }}">Cancel</a> --}}
                                    </div>
                                </div>
                                
                            </div>
                            {!! Form::close() !!}
                        </div>
                </div> 
            </div>
        </section>
                <!-- Footer-->
                @include('layouts/inc/footer')
@endsection