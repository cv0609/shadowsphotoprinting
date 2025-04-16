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
                            <h2>Ambassador Details</h2>

                            <p>
                                Hello <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong> (not <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>?
                                <a href="{{ route('user-logout') }}">Log out</a>)
                            </p>
                            <!-- <p> From your account dashboard you can view your <a href="{{ route('orders') }}">recent
                                    orders</a>, manage your <a href="{{ route('address') }}">shipping and billing addresses</a>, and
                                <a href="{{ route('account-details') }}">edit your password and account details</a>.
                            </p> -->

                                <!-- Affiliate Link -->
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5>Your Affiliate Link</h5>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" id="affiliateLink" readonly
                                                value="{{ url('/?ref=' . auth()->user()?->affiliate?->referral_code) }}">
                                            <button class="btn btn-outline-primary" onclick="copyLink()">Copy</button>
                                        </div>
                                    </div>
                                </div>

                                    <!-- Summary Cards -->
                                    <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h6 class="text-muted">Referral Code</h6>
                                                <h4 class="text-primary">{{ auth()->user()?->affiliate?->referral_code }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h6 class="text-muted">Total Referred Users</h6>
                                                <h4>{{ auth()->user()?->affiliate?->referral_count }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h6 class="text-muted">Total Commissions Earned</h6>
                                                <h4 class="text-success">${{ number_format(auth()->user()?->affiliate?->commission, 2) }}</h4>
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
<script>
    function copyLink() {
        const linkInput = document.getElementById('affiliateLink');
        linkInput.select();
        document.execCommand('copy');
        alert('Affiliate link copied!');
    }
</script>
@endsection
