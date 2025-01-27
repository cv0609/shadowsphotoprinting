@php
    $PageDataService = app(App\Services\PageDataService::class);
    $productCategories = $PageDataService->getProductCategories();
    $cartModel = app(App\Models\Cart::class);
    $CartCount = $cartModel::getCartCount();

@endphp
<header class="header">
    <!-- main header -->
    <div class="navigation page-header">
        <div class="container">
            <div class="navigation-inner">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="{{ url('/') }}">
                            <img src="{{asset('assets/images/logo.png')}}" alt="logo">
                        </a>
                    </div>
                    <div class="sidena mycel" id="mySidenav">
                        <div class="magnifying">
                            <ul class="desk-trt">
                                @if(Auth::check())
                                <li class="dropdown"><a href="{{ route('dashboard') }}" class="signup">MY ACCOUNT </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <div class="kt-woo-account-nav">
                                                <div class="kad-account-avatar">
                                                    <div class="kad-customer-image">
                                                        {{-- <img src="{{asset('assets/images/profile-img.png')}}" alt="user"> --}}
                                                        <img src="{{(!empty(Auth::user()->image)) ? asset(Auth::user()->image) : asset('assets/images/profile-img.png') }}" alt="user_img">
                                                    </div>
                                                    <div class="kad-customer-name">
                                                        <h5> {{ Auth::user()->username ?? ''}} </h5>
                                                    </div>
                                                </div>
                                                <div class="MyAccount-navigation">
                                                    <ul class="ashboard">
                                                        <li class=""> <a href="{{ route('dashboard') }}"
                                                                class="active">Dashboard <i
                                                                    class="fa-solid fa-gauge"></i></a> </li>
                                                        <li class=""> <a href="{{ route('orders') }}">Orders<i
                                                                    class="fa-solid fa-bag-shopping"></i></a>
                                                        </li>
                                                        {{-- <li class=""> <a href="{{ route('downloads') }}">Downloads <i
                                                                    class="fa-solid fa-download"></i></a> </li> --}}
                                                        <li class=""> <a href="{{ route('address') }}">Addresses <i
                                                                    class="fa-solid fa-house"></i></a></li>
                                                        {{-- <li class=""> <a href="{{ route('payment-method') }}">Payment
                                                                methods <i
                                                                    class="fa-solid fa-credit-card"></i></a>
                                                        </li> --}}
                                                        <li class=""> <a href="{{ route('account-details') }}">Account
                                                                details <i class="fa-solid fa-user"></i></a>
                                                        </li>
                                                        {{-- <li class=""> <a href="{{ route('my-coupons') }}">My Coupons
                                                                <i class="fa-solid fa-credit-card"></i></a>
                                                        </li> --}}
                                                        <li class=""><a href="{{ route('user-logout') }}">Log out <i
                                                                    class="fa-solid fa-arrow-right"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                @else
                                <li class="update-menu" data-bs-toggle="modal" data-bs-target="#login-form"><a
                                    class=""><span> Login/Signup</span> </a></li>
                                @endif
                                <li><a href="{{ route('cart') }}">
                                        <span class="kt-extras-label">
                                            <span class="cart-extras-title">Cart</span>
                                        </span>
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>

                                        @if($CartCount > 99)
                                         <span class="kt-cart-total">99+</span>
                                        @else
                                         <span class="kt-cart-total">{{ $CartCount  }}</span>
                                        @endif


                                    </a>
                                </li>
                                <li class="social-media"><a href="https://www.facebook.com/PhotoPrintingShadowsCommunityHub"><i class="fa-brands fa-facebook-f"></i> </a></li>
                                <li class="social-media"><a href="https://www.instagram.com/shadowsphotoprinting/"> <i class="fa-brands fa-instagram"></i> </a></li>



                            </ul>
                        </div>
                    </div>
                    <div class="toggle-bar">
                        <span class="toggle_menu" onclick="openNav()"><i class="fa fa-bars"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER BOTTOM -->
    <div class="outside-second">
        <div class="second-navclass">
            <div class="container">
                <div class="second-clearfix">
                    <div class="sidenavs" id="mySidenavs">
                        <span class="closebtn" onclick="closeNav()"><i class="fa-solid fa-xmark"></i></span>
                        <div class="screen-reader">
                            <div class="">
                                <form action="">
                                    <div class="search-box">
                                        <input type="search" class="search-field no-cancel-button"
                                            placeholder="Search …" value="" name="s">
                                        <span class="magnifying"><i
                                                class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul>
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('shop') }}">Shop</a></li>
                            <li><a href="{{ url('blogs') }}">Blog</a></li>
                            <li><a href="{{ url('fun-facts') }}">Fun Facts</a></li>
                            <li class="dropdown"><a href="{{ url('our-products') }}">Our Products  <i class="fa-solid fa-caret-down dropdown_icon"></i></a>

                                <ul class="sub-menu">
                                   @foreach ($productCategories as $productCategory)
                                    <li><a href="{{ url('our-products/'.str_replace(' ', '-', strtolower($productCategory->name)))}}">{{ ucwords($productCategory->name) }}</a></li>
                                   @endforeach
                                </ul>
                            </li>
                            <li><a href="{{ url('get-a-quote') }}">Get a Quote</a></li>
                            <li><a href="{{ url('contact-us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER BOTTOM -->

</header>
