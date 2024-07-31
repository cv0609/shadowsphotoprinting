@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                <div class="col-md-3">
                    <div class="kad-account">
                        <div class="kad-max">
                            <img src="images/max.png" alt="">
                            <a href="#" class="kt-link-to-gravatar">
                                <i
                                    class="fa-solid fa-cloud-arrow-up"></i>
                                <span
                                    class="kt-profile-photo-text">Update
                                    Profile Photo </span>
                            </a>
                        </div>
                    </div>
                    <div class="MyAccount-navigation">
                        @include('front-end.component.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Addresses
                            </h2>
                            <p>The following addresses will be used
                                on the checkout page by default.
                            </p>
                            <div class="set-addresses">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="woocommerce-address">
                                            <div
                                                class="address-billi">
                                                <h3>Billing
                                                    address</h3>
                                                <a
                                                    href="billing.html"
                                                    class="edit">Edit</a>
                                            </div>
                                            <address
                                                class="billing-address">
                                                <span> developer
                                                    dev</span>
                                                <span> test</span>
                                                <span> 7 Edward
                                                    Bennett
                                                    Drive</span>
                                                <span> gg</span>
                                                <span> Pendle Hill
                                                    New South Wales
                                                    2145 </span>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="woocommerce-address">
                                            <div
                                                class="address-billi">
                                                <h3>Shipping
                                                    address</h3>
                                                <a
                                                    href="shipping.html"
                                                    class="edit">Edit</a>
                                            </div>
                                            <address
                                                class="billing-address">
                                                <span> developer
                                                    dev</span>
                                                <span> test</span>
                                                <span> 7 Edward
                                                    Bennett
                                                    Drive</span>
                                                <span> gg</span>
                                                <span> Pendle Hill
                                                    New South Wales
                                                    2145 </span>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
    <script>
        $('.fade-slider').slick({
            autoplay: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    </script>
@endsection
