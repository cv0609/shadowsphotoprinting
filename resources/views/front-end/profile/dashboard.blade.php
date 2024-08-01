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
                            <h2>My Account</h2>
                            <p>
                                Hello <strong>{{ Auth::user()->name }}</strong> (not <strong>{{ Auth::user()->name }}</strong>?
                                <a href="{{ route('user-logout') }}">Log out</a>)
                            </p>
                            <p> From your account dashboard you can view your <a href="{{ route('orders') }}">recent
                                    orders</a>, manage your <a href="{{ route('address') }}">shipping and billing addresses</a>, and
                                <a href="{{ route('account-details') }}">edit your password and account details</a>.
                            </p>
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
