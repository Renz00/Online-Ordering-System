<nav style="background-color: #0b4141;" class="shadow navbar navbar-expand-lg navbar-dark">
    <div class="container px-4 px-lg-5">

        <a class="navbar-brand" href="{{url('/')}}">Hangout Cafe</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav justify-content-center me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() == 'products.menu' ? ' active' : '' }}" aria-current="page" href="{{ route('products.menu')}}"><i class="bi bi-house-door-fill"></i> Menu</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::url() == url('/contact-us') ? 'active' : '' }}" href="{{ route('contact')}}"><i class="bi bi-telephone-fill"></i> Contact Us</a></li>
            </ul>
            <div class="d-flex">
                @include('layouts/partials/checkout')
            </div>
            <ul class="navbar-nav">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                    {{-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif --}}
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img style="object-fit: cover; width: 35; height: 35px;" class="img-thumbnail" width="40" height="40" src="{{ URL::to('/') }}/storage/profile_images/{{Auth::user()->image}}" alt="..." />
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="{{ route('user.edit', [Auth::user()->slug, 'account']) }}"><i class="bi bi-person-badge"></i> My Account</a>
                            <a class="dropdown-item" href="{{ route('products.favorites', Auth::user()->slug) }}"><i class="bi bi-suit-heart-fill"></i> My Favorites</a>
                            <a class="dropdown-item" href="{{ route('orders.user-orders', Auth::user()->slug) }}"><i class="bi bi-bag-fill"></i> My Orders</a>
                        
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
