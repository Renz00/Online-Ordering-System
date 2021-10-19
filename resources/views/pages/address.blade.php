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
                                <a class="nav-link text-secondary" aria-current="page"
                                    href="{{ route('user.edit', [Auth::user()->slug, 'account']) }}">Account Details</a>
                            </li>
                            <li class="nav-item">
                                <a style="background-color: #fcf9f2;" class="nav-link active text-dark"
                                    href="{{ route('user.edit', [Auth::user()->slug, 'address']) }}">Address Details</a>
                            </li>
                        </ul>
                        <div class="container">
                            <div class="col-sm">
                                {!! Form::open(['route' => 'address.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                <div class="p-4">                                   
                                    @if ($address->first())
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-2">
                                                    {{Form::label('recipient', 'Recipient:', ['class' => 'pb-2'])}}
                                                    {{Form::text('recipient', $address->first()->recipient, ['class' => 'form-control', 'required' => true])}}
                                                    <div class="alert-danger"><b>{{ $errors->first('recipient') }}</b></div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-2">
                                                    {{Form::label('address', 'Address: ', ['class' => 'pb-2'])}}
                                                    {{Form::textarea('address', $address->first()->description, ['class' => 'form-control', 'rows' => '2', 'required' => true])}}
                                                    <div class="alert-danger"><b>{{ $errors->first('address') }}</b></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    {{Form::label('notes', 'Notes:', ['class' => 'pb-2'])}}
                                                    {{Form::textarea('notes', $address->first()->notes, ['class' => 'form-control', 'rows' => '2'])}}
                                                    <div class="alert-danger"><b>{{ $errors->first('notes') }}</b></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('recipient', 'Recipient:', ['class' => 'pb-2'])}}
                                                {{Form::text('recipient', '', ['class' => 'form-control', 'required' => true])}}
                                                <div class="alert-danger"><b>{{ $errors->first('recipient') }}</b></div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-2">
                                                {{Form::label('address', 'Address: ', ['class' => 'pb-2'])}}
                                                {{Form::textarea('address', '', ['class' => 'form-control', 'rows' => '2', 'required' => true])}}
                                                <div class="alert-danger"><b>{{ $errors->first('address') }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                {{Form::label('notes', 'Notes:', ['class' => 'pb-2'])}}
                                                {{Form::textarea('notes', '', ['class' => 'form-control', 'rows' => '2'])}}
                                                <div class="alert-danger"><b>{{ $errors->first('notes') }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row bottom-0 start-0 mt-4">
                                        <div class="col">
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success form-control"><b><i class="bi bi-save2"></i> Save Changes</b></button>
                                            </div>
                                        </div>
                                        <div class="col">
                                        </div>
                                        {{-- <a class="btn btn-danger" href="{{ url()->previous() }}">Cancel</a> --}}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                </div> 
            </div>
        </section>
                <!-- Footer-->
                @include('layouts/inc/footer')
@endsection