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
                            <h2>Add payment method </h2>
                            <div class="notices-wrapper">
                                <form action="add-payment-method">
                                    <div class="payment">
                                        <ul>
                                            <li>
                                                <input type="radio" checked="checked" name="payment-methods">
                                                <label for="payment_method_stripe">Credit Card (Stripe) </label>
                                                <div class="Paymentbox">
                                                    <div class="stripe-payment">
                                                        <p>Pay with your credit card via Stripe.</p>
                                                        <fieldset class="credit-card-form">
                                                            <div class="form-row-wide">
                                                                <label for="">Card Number*</label>
                                                                <div class="stripe-card-group">
                                                                    <input type="text"
                                                                        placeholder="1234 1234 1234 1234">
                                                                    <span> <i
                                                                            class="fa-solid fa-credit-card"></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-row-first">
                                                                <div class="form-row-wide">
                                                                    <label for=""> Expiry Date *</label>
                                                                    <div class="stripe-card-group">
                                                                        <input type="text" placeholder="MM / YY">
                                                                        <span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row-wide">
                                                                    <label for="">Card Code (CVC) *</label>
                                                                    <div class="stripe-card-group">
                                                                        <input type="text" placeholder="CVC">
                                                                        <span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="quanti">
                                <button type="button">Add payment method</button>
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
