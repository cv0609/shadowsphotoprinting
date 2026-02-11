@php
$PageDataService = app(App\Services\PageDataService::class);
$productCategories = $PageDataService->getProductCategories();
$cartModel = app(App\Models\Cart::class);
$CartCount = $cartModel::getCartCount();

@endphp
<header class="header">
    <!-- August Promotion Banner - Show for all users, but popup only for logged-in users -->
   <div class="august-promotion-banner" style="background: #16a085;color: white;text-align: center;padding: 10px 0;cursor: pointer;/* border-bottom: 1px solid #ffd700; */" onclick="openAugustPromotion()">
        <span style="font-size: 16px;font-weight: 600;">
            New Here? Get 10% OFF Your First Order! – 
            <span style="color: #ffd700; text-decoration: underline; font-weight: bold;">Click here</span> 🎁
        </span>
    </div>
    
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
                                                     <li class=""> <a href="{{ route('ambassador') }}">Ambassador <i class="fa-solid fa-users"></i></a> </li>
                                                     <li class=""> <a href="{{ route('ambassador.blog') }}">Blog <i class="fa-solid fa-pencil-square-o"></i></a> </li>
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
                            {{-- <li><a href="{{ url('faq') }}">FAQ</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER BOTTOM -->

    <!-- August Promotion Popup - Only show for logged-in users who haven't received coupon -->
    @auth
        @if(auth()->user()->is_august_coupon != 1)
            <div id="augustPromotionPopup" class="modal fade" role="dialog">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #000; color: white; border-bottom: 2px solid #ffd700; padding: 15px 20px;">
                            <h5 class="modal-title mb-0">
                                <i class="fas fa-gift"></i> {{date('M Y')}} Special Promotion!
                            </h5>
                            <button type="button" class="close close-august-popup" data-dismiss="modal" aria-label="Close" style="color: white;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding: 20px;">
                            <div class="text-center mb-3">
                                <h4 style="color: #ffd700; margin-bottom: 10px; font-size: 18px;">🎉 Welcome to Shadows Photo Printing! 🎉</h4>
                                <p style="font-size: 14px; color: #fff; margin-bottom: 15px;">
                                    Get <strong>10% OFF</strong> your first order!
                                </p>
                            </div>
                            
                            <!-- Promotion Image -->
                            <div class="text-center mb-3">
                                <img src="{{ asset('august-new-order.jpg') }}" alt="August Promotion" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.2);">
                            </div>

                            <div class="email-form">
                                <h6 style="color: #fff; margin-bottom: 10px;">
                                    <i class="fas fa-envelope"></i> Get Your Coupon Code
                                </h6>
                                <p style="color: #fff; margin-bottom: 15px; font-size: 13px;">
                                    Enter your email address below and we'll send you your exclusive coupon code instantly!
                                </p>
                                
                                <form id="augustPromotionForm">
                                    <div class="form-group mb-3">
                                        <input type="email" 
                                               class="form-control" 
                                               id="promotionEmail" 
                                               placeholder="Enter your email address"
                                               required
                                               style="padding: 10px; border-radius: 6px; border: 2px solid #e9ecef; font-size: 14px;">
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" 
                                                class="btn btn-primary" 
                                                style="background: #20c997; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; color: white; font-size: 14px;">
                                            <i class="fas fa-paper-plane"></i> Send Me My Coupon!
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div id="successMessage" style="display: none; background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-top: 15px; font-size: 13px;">
                                <i class="fas fa-check-circle"></i> 
                                <strong>Success!</strong> Your coupon code has been sent to your email address.
                            </div>

                            <div id="errorMessage" style="display: none; background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-top: 15px; font-size: 13px;">
                                <i class="fas fa-exclamation-circle"></i> 
                                <strong>Error!</strong> <span id="errorText"></span>
                            </div>
                        </div>
                        
                        <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e9ecef; padding: 10px 20px;">
                            <small style="color: #6c757d; font-size: 11px;">
                                <i class="fas fa-info-circle"></i> 
                                Valid for first-time customers only.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <style>
    #augustPromotionPopup .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    #augustPromotionPopup .modal-header {
        border-radius: 15px 15px 0 0;
    }

    #augustPromotionPopup .close {
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    #augustPromotionPopup .close:hover {
        opacity: 1;
    }

    #augustPromotionPopup .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    #augustPromotionPopup .form-control:focus {
        border-color: #20c997;
        box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
    }

    .promotion-details ul li {
        padding: 5px 0;
    }

    .promotion-details ul li i {
        margin-right: 10px;
        width: 20px;
    }

    .august-promotion-banner:hover {
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    </style>

    <script>
    function openAugustPromotion() {
        // Check if user is logged in
        @auth
            // Check if user already received the coupon
            @if(auth()->user()->is_august_coupon != 1)
                $('#augustPromotionPopup').modal('show');
            @else
                const now = new Date();
                const month = now.toLocaleString('default', { month: 'long' });
                alert(`You have already received your ${month} promotion coupon!`);
            @endif
        @else
            // User is not logged in, show login prompt
            alert('Please Login/SignUp to receive your 10% discount coupon!');
        @endauth
    }

    // Close August promotion popup
    $('.close-august-popup').on('click', function() {
        $('#augustPromotionPopup').modal('hide');
    });
    </script>

</header>


<div id="afterpay-modal" class="modal fade" role="dialog">
    <style>
#afterpay-modal .modal-body.model-body-after .model-svg-flex {
display: flex;
justify-content: center;
align-content: center;
gap: 10px;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex img {
	width: 64px;
	height: 61px;
}
#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center {
width: calc(100% / 3);
text-align: center;
}
#afterpay-modal h3.afterpay-modal-headline {
color: #00193a;
font-size: 50px;
font-weight: 800;
padding-top: 40px;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center h4 {
color: #00193a;
font-size: 19px;
font-weight: 600;
padding-top: 9px;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center p {
padding: 0;
color: #00193a;
font-weight: 500;
font-size: 15px;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex {
margin-top: 29px;
}

#afterpay-modal .disclaimer {
color: #00193a!important;
}
#afterpay-modal .modal-content {
    width: calc(100% - 20px);
    margin: 0 auto;
}
@media (max-width: 991.98px) {

#afterpay-modal .modal-body.model-body-after .model-svg-flex {
flex-direction: column;
}


#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center {
width: 100%;
text-align: center;
display: flex;
text-align: center;
flex-direction: column;
}
#afterpay-modal .model-svg-center p {
	padding: 0 3% 0 3%;
	font-weight: 500;
	line-height: 1.25;
	text-align: center;
	color: #000;
}

 }
@media (max-width: 767px) {
#afterpay-modal h3.afterpay-modal-headline span {
display: block;
}
#afterpay-modal h3.afterpay-modal-headline {
    text-align: center!important;
    max-width: 415px;
    margin-left: auto;
    margin-right: auto;
}
#afterpay-modal .disclaimer {
text-align: center!important;
}
#afterpay-modal .afterpay-modal-logo {
margin: 0 auto!important;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center {
gap: 0;
}

#afterpay-modal .modal-body.model-body-after .model-svg-flex .model-svg-center p {
max-width: 100px;
}
}
@media (max-width: 575px) {
#afterpay-modal h3.afterpay-modal-headline {
    font-size: 40px;
    padding-top: 11px;
}
}
@media (max-width: 420px) {
#afterpay-modal h3.afterpay-modal-headline {
    font-size: 34px;
    padding-top: 22px;
}
}
    </style>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header sail-modal-header">
                <button type="button" class="close" id="close-sail-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" focusable="false" width="20" height="20" viewBox="0 0 30 30" aria-hidden="true"><defs><style>.a{fill:none;stroke:#808284;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title>Afterpay modal close</title><line class="a" x1="1" y1="1" x2="29" y2="29"></line><line class="a" x1="1" y1="29" x2="29" y2="1"></line></svg>
                </button>
            </div>
            <div class="modal-body model-body-after">
               <div class="model-body-main">
                <div class="afterpay-modal-logo"><svg width="162" height="57" viewBox="0 0 162 57" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<path d="M0 28.5C0 12.7599 12.7599 0 28.5 0H133.5C149.24 0 162 12.7599 162 28.5V28.5C162 44.2401 149.24 57 133.5 57H28.5C12.7599 57 0 44.2401 0 28.5V28.5Z" fill="url(#pattern0_2001_6)"/>
<defs>
<pattern id="pattern0_2001_6" patternContentUnits="objectBoundingBox" width="1" height="1">
<use xlink:href="#image0_2001_6" transform="matrix(0.00617284 0 0 0.0175439 -1.93827 -0.701754)"/>
</pattern>
<image id="image0_2001_6" width="788" height="580" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAxQAAAJECAIAAAAAGPWFAAAgAElEQVR4AeydvU8jy9bunZN7RHhISC6hR8gpkAIiM29CACkQew/Z9g2YAE4GewiRnF0kSwS8KZx0I0KiIXDMX+Dgalhnr732qqqnq/2F234khKq762PVr1ZVPV1d3a71Bn3+kQAJkAAJkAAJkAAJZBKoZcZjNBIgARIgARIgARIggd6gT/HEhTcSIAESIAESIAESKEGA4qkELMptEiABEiABEiABEqB4ongiARIgARIgARIggRIEKJ5KwKLWJgESIAESIAESIAGKJ4onEiABEiABEiABEihBgOKpBCxqbRIgARIgARIgARKgeKJ4IgESIAESIAESIIESBCieSsCi1iYBEiABEiABEiABiieKJxIgARIgARIgARIoQYDiqQQsam0SIAESIAESIAESoHiieCIBEiABEiABEiCBEgQonkrAotYmARIgARIgARIgAYoniicSIAESIAESIAESKEGA4qkELGptEiABEiABEiABEqB4ongiARIgARIgARIggRIEKJ5KwKLWJgESIAESIAESIAGKJ4onEiABEiABEiABEihBgOKpBCxqbRIgARIgARIgARKgeKJ4IgESIAESIAESIIESBCieSsCi1iYBEiABEiABEiABiieKJxIgARIgARIgARIoQYDiqQQsam0SIAESIAESIAESoHiieCIBEiABEiABEiCBEgQonkrAotYmARIgARIgARIgAYoniicSIAESIAESIAESKEGA4qkELGptEiABEiABEiABEqB4ongiARIgARIgARIggRIEKJ5KwKLWJgESIAESIAESIAGKJ4onEiABEiABEiABEihBgOKpBCxqbRIgARIgARIgARKgeKJ4IgESIAESIAESIIESBCieSsCi1iYBEiABEiABEiABiieKJxIgARIgARIgARIoQYDiqQQsam0SIAESIAESIAESoHiieCIBEiABEiABEiCBEgQonkrAotYmARIgARIgARIgAYoniicSIAESIAESIAESKEGA4qkELGptEiABEiABEiABEqB4ongigYUg8OPnnyfd2+2zzvrB6Zevu0urW7XlJv9GJ7C0urWyub+2d7R91jnp3l6+PHFeIQESmHsCFE8LMXHOvR+zgikCnceH7bMOpdLoIik/h3pjZ+O43Xl8SDUK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64DxPJpztU7OiXiaMvTO44P8SmL7/i5/pJuykXNZXPf9tfP4cHh1vX3W2Thuy9/2Wefw6rrz+MC2mMtGn7VKSfc/vLo+6d6WdbnLl6f2/V3r/EK9d+O43Tq/OOne/vj556zVtIr22NaZV6S2jmU9cFxtevP2LJ7shuKT7i3eaTouAz49H4qn/qe3AQ0gARIgARIgARKoEAGKJ4onEiABEiABEiABEihBgOKpBKwKiWKaSgIkQAIkQAIkMCECFE8UTyRAAiRAAiRAAiRQggDFUwlYExKwzJYESIAESIAESKBCBCieKJ5IgARIgARIgARIoAQBiqcSsCokimkqCZAACZAACZDAhAhQPFE8kQAJkAAJkAAJkEAJAhRPJWBNSMAyWxIgARIgARIggQoRoHiieCIBEiABEiABEiCBEgQonkrAqpAopqkkQAIkQAIkQAITIkDxRPFEAiRAAiRAAiRAAiUIUDyVgDUhActsSYAESIAESIAEKkSA4oniiQRIgARIgARIgARKEKB4KgGrQqKYppIACZAACZAACUyIAMUTxRMJkAAJkAAJkAAJlCBA8VQC1oQELLMlARIgARIgARKoEAGKJ4onEiABEiABEiABEihBgOKpBKwKiWKaSgIkQAIkQAIkMCECFE8UTyRAAiRAAiRAAiRQggDFUwlYExKwzJYESIAESIAESKBCBCieKJ5IgARIgARIgARIoAQBiqcSsCokimkqCZAACZAACZDAhAhQPFE8kQAJkAAJkAAJkEAJAhRPJWBNSMAyWxIgARIgARIggQoRoHiieCIBEiABEiABEiCBEgQonkrAqpAopqkkQAIkQAIkQAITIkDxRPFEAiRAAiRAAiRAAiUIUDyVgDUhActsSYAESIAESIAEKkSA4oniiQRIgARIgARIgARKEKB4KgGrQqKYppIACZAACZAACUyIAMUTxRMJkAAJkAAJkAAJlCBA8VQC1oQELLMlARIgARIgARKoEAGKJ4onEiABEiABEiABEihBgOKpBKwKiWKaSgIkQAIkQAIkMCECFE8UTyRAAiRAAiRAAiRQggDFUwlYExKwzJYESIAESIAESKBCBCieKJ5IgARIgARIgARIoAQBiqcSsCokimkqCZAACZAACZDAhAhQPFE8kQAJkAAJkAAJkEAJAhRPJWBNSMAyWxIgARIgARIggQoRoHiieCIB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wARMwARMwARMIAcBi6ccsN6n/rVVJmACJmACJmAC0yRg8WTxZAImYAImYAImYAI5CFg85YA1TVXruEzABEzABEzABN4nAYsniycTMAETMAETMAETyEHA4ikHrPepf22VCZiACZiACZjANAlYPFk8mYAJmIAJmIAJmEAOAhZPOWBNU9U6LhMwARMwARMwgfdJwOLJ4skETMAETMAETMAEchCweMoB633qX1tlAiZgAiZgAiYwTQIWTxZPJmACJmACJmACJpCDgMVTDljTVLWOywRMwARMwARM4H0SsHiyeDIBEzABEzABEzCBHAQsnnLAep/611aZgAmYgAmYgAlMk4DFk8WTCZiACZiACZiACeQgYPGUA9Y0Va3jMgETMAETMAETeJ8ELJ4snkzABEzABEzABEwgBwGLpxyw3qf+tVUmYAImYAImYALTJGDxZPFkAiZgAiZgAiZgAjkIWDzlgDVNVeu4TMAETMAETMAE3icBiyeLJxMwARMwARMwARPIQcDiKQes96l/bZUJmIAJmIAJmMA0CVg8WTyZgAmYgAmYgAmYQA4CFk85YE1T1TouEzABEzABEzCB90nA4sniyQRMwARMwARMwARyELB4ygHrfepfW2UCJmACJmACJjBNAhZPFk8mYAImYAImYAImkIOAxVMOWNNUtY7LBEzABEzABEzgfRKweLJ4MgETMAETMAETMIEcBCyecsB6n/rXVpmACZiACZiACUyTgMWTxZMJmIAJmIAJmIAJ5CBg8ZQD1jRVreMyARMwARMwARN4nwQsniyeTMAETMAETMAETCAHAYunHLDep/61VSZgAiZgAiZgAtMkYPFk8WQCJmACJmACJmACOQhYPOWANU1V67hMwARMwARMwATeJwGLJ4snEzABEzABEzABE8hBwOIpB6z3qX9tlQmYgAmYgAmYwDQJWDxZPJmACZiACZiACZhADgIWTzlgTVPVOi4TMAETMAETMIH3ScDiyeLJBEzABEzABEzABHIQsHjKAet96t8JVh3fDUu15kJ5a3WnNXi4rey3lza3+fvz5puOlza3o0Cqh53G4OTs8f7o5mp1p3X2eF/Zby+Ut6qHnbPH+9Wd1p833zjoj4YKp3V+GoXTvrzg19WdVn80PHu83z0/XdrcHjzcnj3e66B62Gml19uXF7vnp6Va8+jmSsFW9trtywvMaAxO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"/>
</defs>
</svg>
</div>
               </div>
               <h3 class="afterpay-modal-headline">BUY NOW.<span>PAY IT IN 4</span><div>INTEREST-FREE INSTALMENTS</div></h3>
               <div class="model-svg-flex">

                  <div class="model-svg-center">
                     <span>

                    <img src="{{ asset('afterpay-1.png') }}" alt="">

                    </span>
                    <h4>CHOOSE AFTERPAY </h4>
                     <p>At checkout.</p>
                  </div>

                  <div class="model-svg-center">
                    <span>

                   <img src="{{ asset('afterpay-2.png') }}" alt="">

                </span>
                <h4>LOGIN OR SIGN UP </h4>
                    <p>Free and simple to apply.</p>
                 </div>

                 <div class="model-svg-center">
                    <span>

                  <img src="{{ asset('afterpay.png') }}" alt="">

                </span>
                <h4> PAY IT IN 4</h4>
                    <p>Approx every 2 weeks.</p>
                 </div>



               </div>

               <div class="disclaimer">SEE YOUR PAYMENT SCHEDULE FOR FULL BREAKDOWN OF YOUR INSTALMENTS AND DUE DATES IN THE AFTERPAY APP OR WEB PORTAL AFTER YOU CHECK OUT. YOUR INSTALMENTS AND DUE DATES WILL TAKE INTO ACCOUNT YOUR PREFERRED PAYMENT DATE, AND WHETHER NO PAYMENT UPFRONT OR HIGHER UPFRONT PAYMENT APPLIES.<br><br>LATE FEES, ELIGIBILITY CRITERIA AND T&CS APPLY. AUSTRALIAN CREDIT LICENCE 527911.</div>
            </div>
        </div>
    </div>
</div>
<h1 class="h1-missing">.</h1>
