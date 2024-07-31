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
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span class="kt-profile-photo-text">Update Profile Photo </span>
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
                            <h2>My Account</h2>
                            <div class="wt-mycoupons">
                                <h4>Available Coupons</h4>
                                <div class="available-coupon">
                                    <form action="#">
                                        <span>Sort by </span>
                                        <select name="wt_sc_available_coupons_orderby">
                                            <option value="created_date:desc">Latest first</option>
                                            <option value="created_date:asc" selected="selected">Latest last
                                            </option>
                                            <option value="amount:desc">Price high to low</option>
                                            <option value="amount:asc">Price low to high</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="clear"></div>
                                <div class="wt-coupon-wrapper">
                                    <p>Sorry, you don't have any available coupons</p>
                                </div>
                            </div>
                            <div class="cssa-soupons">
                                <h4>Used Coupons</h4>
                                <div class="wt-used-coupons">
                                    <div class="single-coupon">
                                        <div class="coupon-content">
                                            <div class="coupon-amount">
                                                <span class="coupon-amount-amount">$12.50</span>
                                                <span class="coupon-type">Cart discount</span>
                                            </div>
                                            <div class="coupon-code">
                                                <span>10freecameraprints</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="expired-coupons">
                                <div class="expired-wrapper">
                                    <h4>Expired Coupons</h4>
                                    <div class="myaccount-no">
                                        <p>Sorry, you don't have any expired coupons</p>
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
