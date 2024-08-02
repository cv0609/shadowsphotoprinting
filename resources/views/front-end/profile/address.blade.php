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
                    @if(Session::has('success'))
                     <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
                    @endif
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
                                                <h3>Billing address</h3>
                                                @if(isset($details_check) && !empty($details_check))    
                                                 <a href="{{ route('edit-address',['slug' => 'billing']) }}" class="edit">Edit</a>
                                                @else    
                                                 <a href="{{ route('add-address',['slug' => 'billing']) }}" class="edit">Add</a>
                                                 <p><em>You have not set up this type of address yet.</em></p>
                                                @endif 
                                            </div>
                                            <address
                                                class="billing-address">
                                                <span>  {{$user_details->fname ?? ''}}</span>
                                                <span> {{$user_details->lname ?? ''}}</span>
                                                <span> {{$user_details->postcode ?? ''}}</span>
                                                <span> {{$user_details->street1 ?? ''}}</span>
                                                <span> {{$user_details->street2 ?? ''}}</span>

                                                <span> {{$user_details->phone ?? ''}}</span>
                                                <span> {{$user_details->suburb ?? ''}}</span>
                                                <span> {{$user_details->state ?? ''}}</span>
                                                <span> {{$user_details->company_name ?? ''}}</span>
                                                <span> {{$user_details->country_region ?? ''}}</span>
                                                <span> {{$user_details->order_comments ?? ''}}</span>
                                                <span> {{$user_details->order_comments ?? ''}}</span>

                                                <p>{{$user_details->email ?? ''}}</p>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="woocommerce-address">
                                            <div
                                                class="address-billi">
                                                    <h3>Shipping address</h3>
                                                    @if(isset($details_check) && !empty($details_check))    
                                                        <a href="{{ route('edit-address',['slug' => 'shipping']) }}" class="edit">Edit</a>
                                                    @else    
                                                        <a href="{{ route('add-address',['slug' => 'shipping']) }}" class="edit">Add</a>
                                                        <p><em>You have not set up this type of address yet.</em></p>
                                                    @endif
                                            </div>
                                            <address
                                                class="billing-address">
                                                <span> {{$user_details->ship_fname ?? ''}}</span>
                                                <span> {{$user_details->ship_lname ?? ''}}</span>
                                                <span> {{$user_details->ship_company ?? ''}}</span>
                                                <span> {{$user_details->ship_street1 ?? ''}}</span>
                                                <span> {{$user_details->ship_street2 ?? ''}}</span>
                                                
                                                <span> {{$user_details->ship_suburb ?? ''}}</span>
                                                <span>  {{$user_details->ship_state ?? ''}}</span>
                                                <span> {{$user_details->ship_postcode ?? ''}}</span>
                                                <span> {{$user_details->ship_country_region ?? ''}}</span>
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
