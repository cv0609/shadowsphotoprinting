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
                @include('front-end.profile.component.account-sidebar')
                <div class="col-md-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Account details </h2>
                            <div class="notices-wrapper">
                                <form action="" class="account-details input-color">
                                    <div class="fields__field-wrapper">
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>First name * </label>
                                                        <input type="text" placeholder="Terri">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>Last name * </label>
                                                        <input type="text" placeholder="Pangas">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Display name * </label>
                                                        <input type="text" placeholder="Terri Pangas">
                                                        <span><em>This will be how your name will be displayed
                                                                in the account section and in
                                                                reviews</em></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Email address * </label>
                                                        <input type="text"
                                                            placeholder="shadowsphotoprinting@outlook.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset>
                                            <legend>Password change</legend>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>Current password (leave blank to leave
                                                                unchanged) </label>
                                                            <input type="password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>New password (leave blank to leave unchanged)
                                                            </label>
                                                            <input type="password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>New password (leave blank to leave unchanged)
                                                            </label>
                                                            <input type="password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="quanti">
                                                        <a href="#">Save changes</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
