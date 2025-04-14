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
                                                    <li class=""> <a href="{{ route('dashboard') }}" class="active">Dashboard <i class="fa-solid fa-gauge"></i></a> </li>
                                                     @if(Auth::user()->role == 'affiliate')
                                                      <li class=""> <a href="{{ route('ambassador') }}">Ambassador <i class="fa-solid fa-gauge"></i></a> </li>
                                                     @endif 
                                                    
                                                    <li class=""> <a href="{{ route('orders') }}">Orders<i class="fa-solid fa-bag-shopping"></i></a></li>
                                                    {{-- <li class=""> <a href="{{ route('downloads') }}">Downloads <i class="fa-solid fa-download"></i></a></li> --}}
                                                    <li class=""> <a href="{{ route('address') }}">Addresses <i class="fa-solid fa-house"></i></a></li>
                                                    {{-- <li class=""> <a href="{{ route('payment-method') }}">Payment methods <i class="fa-solid fa-credit-card"></i></a></li> --}}
                                                    <li class=""> <a href="{{ route('account-details') }}">Account details <i class="fa-solid fa-user"></i></a></li>
                                                    {{-- <li class=""> <a href="{{ route('my-coupons') }}">My Coupons <i class="fa-solid fa-credit-card"></i></a></li> --}}
                                                    <li class=""><a href="{{ route('user-logout') }}">Log out <i class="fa-solid fa-arrow-right"></i></a></li>
                                                </ul>
                                           </div>
                    </div>
                    </li>
                    </ul>
                    </li>
                    @else
                    <li class="update-menu" data-bs-toggle="modal" data-bs-target="#login-form"><a class=""><span> Login/Signup</span> </a></li>
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
                    <li class="social-media"><a href="https://www.facebook.com/PhotoPrintingShadowsCommunityHub" target="_blank"><i class="fa-brands fa-facebook-f"></i> </a></li>
                    <li class="social-media"><a href="https://www.instagram.com/shadowsphotoprinting/" target="_blank"> <i class="fa-brands fa-instagram"></i> </a></li>



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
                                        <input type="search" class="search-field no-cancel-button" placeholder="Search …" value="" name="s">
                                        <span class="magnifying"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul>
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('shop') }}">Shop</a></li>
                            <li><a href="{{ url('blogs') }}">Blog</a></li>
                            <li><a href="{{ url('promotions') }}">Promotions</a></li>
                            <li><a href="{{ url('fun-facts') }}">Fun Facts</a></li>
                            <li class="dropdown"><a href="{{ url('our-products') }}">Our Products <i class="fa-solid fa-caret-down dropdown_icon"></i></a>

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


<div id="afterpay-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header sail-modal-header">
                <button type="button" class="close" id="close-sail-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" focusable="false" width="20" height="20" viewBox="0 0 30 30" aria-hidden="true"><defs><style>.a{fill:none;stroke:#808284;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title>Afterpay modal close</title><line class="a" x1="1" y1="1" x2="29" y2="29"></line><line class="a" x1="1" y1="29" x2="29" y2="1"></line></svg>
                </button>
            </div>
            <div class="modal-body model-body-after">
               <div class="model-body-main">
                <div class="afterpay-modal-logo"><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 1150 222" class="afterpay-logo-black" aria-label="Afterpay logo"><path d="M1132 45.3l-34.6-19.8-35.1-20.1a34.9 34.9 0 00-52.2 30.2V40c0 2.5 1.3 4.8 3.5 6l16.3 9.3a6.8 6.8 0 0010.1-6V38.9c0-5.3 5.7-8.6 10.3-6l32 18.4 31.9 18.3a6.9 6.9 0 010 11.9l-31.9 18.3-32 18.4a6.9 6.9 0 01-10.3-6v-5.3c0-26.8-29-43.6-52.2-30.2l-35.1 20-34.6 19.9a35 35 0 000 60.5l34.6 19.8 35.1 20a34.9 34.9 0 0052.2-30.1v-4.5c0-2.5-1.3-4.8-3.5-6l-16.3-9.3a6.8 6.8 0 00-10.1 5.9v10.7c0 5.3-5.7 8.6-10.3 6l-32-18.4-31.9-18.3a6.9 6.9 0 010-12l31.9-18.2 32-18.4a6.9 6.9 0 0110.3 6v5.3c0 26.8 29 43.6 52.2 30.2l35.1-20.1 34.6-19.8a35 35 0 000-60.5zm-227 6.6l-81 167.3h-33.6l30.3-62.5L773 51.9h34.5l30.6 70.2 33.4-70.2H905zM95.1 111.3c0-20-14.5-34-32.3-34s-32.3 14.3-32.3 34c0 19.5 14.5 34 32.3 34s32.3-14 32.3-34m.3 59.4v-15.4a47.6 47.6 0 01-37.5 17.3C25.3 172.6.6 146.5.6 111.3c0-35 25.7-61.5 58-61.5 15.2 0 28 6.7 36.8 17v-15h29.2v118.9H95.4zm171.2-26.4c-10.2 0-13.1-3.8-13.1-13.8V77.8h18.8v-26h-18.8v-29h-29.9v29H185V40.2c0-10 3.8-13.8 14.3-13.8h6.6v-23h-14.4c-24.7 0-36.4 8-36.4 32.8V52h-16.6v25.8h16.6v92.9H185v-93h38.6V136c0 24.2 9.3 34.7 33.5 34.7h15.4v-26.4h-5.9zM374 100.6c-2.1-15.4-14.7-24.7-29.5-24.7-14.7 0-26.9 9-29.9 24.7H374zM314.3 119a29.6 29.6 0 0030.7 27.6c12.6 0 22.3-6 28-15.4h30.7c-7.1 25.2-29.7 41.3-59.4 41.3a58.5 58.5 0 01-61.1-61.1A60 60 0 01345 49.7a59.5 59.5 0 0160.4 69.4h-91.1zm282.2-7.8a33 33 0 00-32.3-34c-17.8 0-32.3 14.3-32.3 34 0 19.5 14.5 34 32.3 34a32.9 32.9 0 0032.3-34m-94.1 107.9V51.9h29.2v15.4a47.2 47.2 0 0137.5-17.6c32.1 0 57.3 26.4 57.3 61.3s-25.7 61.5-58 61.5a46 46 0 01-35.9-16v62.6h-30.1zm229.3-108c0-20-14.5-34-32.3-34-17.8 0-32.3 14.4-32.3 34s14.5 34 32.3 34c17.8 0 32.3-14 32.3-34m.3 59.5v-15.4a47.6 47.6 0 01-37.5 17.3c-32.6 0-57.3-26.1-57.3-61.3 0-35 25.7-61.5 58-61.5 15.2 0 28 6.7 36.8 17v-15h29.2v118.9H732zM449.7 63.5s7.4-13.8 25.7-13.8c7.8 0 12.8 2.7 12.8 2.7v30.3s-11-6.8-21.1-5.4c-10.1 1.4-16.5 10.6-16.5 23v70.3h-30.2V51.9h29.2v11.6h.1z"></path></svg></div>
               </div>
               <h3 class="afterpay-modal-headline">Shop now. <span>Pay later.</span><div>Always interest-free, when you pay it in 4.</div></h3>
               <div class="model-svg-flex">

                  <div class="model-svg-center">
                     <span><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 52 72" class="process-icon shopping-cart" aria-label="Step 1"><path class="c1" d="M26 55.47a27.28 27.28 0 01-4-3c-2.13-1.87-4.52-4.49-4.44-7.36a5 5 0 01.37-1.75 4.27 4.27 0 018.13.25 4.27 4.27 0 018.13-.25 5 5 0 01.37 1.75c.08 2.87-2.31 5.49-4.44 7.36a27.28 27.28 0 01-4 3"></path><path class="c1" fill="#000" d="M10.5 35V17a15.5 15.5 0 0131 0v18"></path><path class="c1" transform="rotate(90 85.5 -4.5)" d="M110.5 30.5h45.79a4.21 4.21 0 014.21 4.21v40.58a4.21 4.21 0 01-4.21 4.21H110.5v-49z"></path></svg></span>
                     <p>Add your favourites to cart</p>
                  </div>

                  <div class="model-svg-center">
                    <span><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 82 56" class="process-icon afterpay-desktop" aria-label="Step 2"><path class="c1" d="M36.5 52.49h9"></path><path class="c1" d="M10.43 1.5h61.14a3.93 3.93 0 013.93 3.93V50.5h-69V5.43a3.93 3.93 0 013.93-3.93z"></path><path d="M54.75 17.52L50.3 15l-4.51-2.59a4.48 4.48 0 00-6.71 3.89v.58a.89.89 0 00.44.77l2.1 1.2a.86.86 0 001.3-.75v-1.41a.89.89 0 011.33-.77l4.12 2.37 4.11 2.36a.88.88 0 010 1.53l-4.11 2.36-4.12 2.36a.88.88 0 01-1.33-.76v-.69a4.48 4.48 0 00-6.71-3.89l-4.51 2.59-4.45 2.55a4.49 4.49 0 000 7.78L31.7 37l4.51 2.59a4.48 4.48 0 006.71-3.89v-.58a.88.88 0 00-.44-.77l-2.1-1.2a.86.86 0 00-1.3.75v1.38a.89.89 0 01-1.33.77l-4.12-2.37-4.11-2.36a.88.88 0 010-1.53l4.11-2.36 4.12-2.36a.88.88 0 011.33.76v.69a4.48 4.48 0 006.71 3.89l4.51-2.59 4.45-2.55a4.49 4.49 0 000-7.75z"></path><rect class="c1" width="79" height="4" x="1.5" y="50.49" rx="2"></rect></svg></span>
                    <p>Select Afterpay at checkout</p>
                 </div>

                 <div class="model-svg-center">
                    <span><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 82 56" class="process-icon sign-up-desktop" aria-label="Step 3"><path class="c1" d="M52.51 28.12l4.43 4.45L65.5 24"></path><circle class="c1" cx="31" cy="26" r="14.5"></circle><circle class="c1" cx="31" cy="21.8" r="6.09"></circle><path class="c1" d="M20.92 36.42a8.43 8.43 0 018-5.8h4.2a8.45 8.45 0 018 5.8M36.5 52.49h9"></path><path class="c1" d="M10.43 1.5h61.14a3.93 3.93 0 013.93 3.94V50.5h-69V5.45a3.93 3.93 0 013.93-3.93z"></path><rect class="c1" width="79" height="4" x="1.5" y="50.49" rx="2"></rect></svg></span>
                    <p>Log into or create your Afterpay account. Free and simple to join</p>
                 </div>

                 <div class="model-svg-center">
                    <span><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="14 7 79 79" class="process-icon four-payments" aria-label="Step 4"><path class="c1" d="M50,15.5A34.5,34.5,0,0,0,15.5,50H50Z"></path><path class="c1" d="M15.5,50A34.5,34.5,0,0,0,50,84.5V50Z"></path><path class="c1" d="M50,84.5h0A34.5,34.5,0,0,0,84.5,50H50Z"></path><path class="c1" d="M57,8.5h0V43h34.5A34.5,34.5,0,0,0,57,8.5Z"></path></svg></span>
                    <p>Your purchase will be split into 4 payments, payable every 2 weeks</p>
                 </div>

               </div>

               <div class="disclaimer">All you need to apply is to have a debit or credit card, to be over 18 years of age, and to be a resident of Australia.<br><br>Late fees and additional eligibility criteria apply. The first payment may be due at the time of purchase.<br>For complete terms visit <a href="https://www.afterpay.com/en-AU/terms-of-service" aria-label="Afterpay Terms (New Window)" target="_blank" rel="noopener" class="disclaimer-link" tabindex="1">afterpay.com/en-AU/terms-of-service</a></div>
            </div>
        </div>
    </div>
</div>
<h1 class="h1-missing">.</h1>
