@extends('front-end.layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="contact-banner">
    <div class="container">
        <div class="contact-bnr-text">
            <h2>{{ $page_content['contact_us_banner_title'] }} </h2>
            {{-- <h2>Contact Us </h2> --}}
        </div>
    </div>
</section>

<section class="shipping-policy">
    <div class="container">
        <div class="shipping-wrapper">
            <div class="panel-grid-cell">
                <div class="panel-widget">
                    <h2>{{ $page_content['shipping_policy_title'] }}</h2>
                    {!! $page_content['shipping_policy_description'] !!}
                </div>
            </div>
            <div class="panel-grid-cell refunds">
                <div class="panel-widget">
                    <h2>{{ $page_content['policy_refund_title'] }} </h2>
                    {!! $page_content['policy_refund_description'] !!}
                </div>
            </div>
            <div class="kadinfolink">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="kadence_infobox_widget">
                            <div class="kadence_infobox">
                                <a href="tel:0266029424">
                                    <div class="kad-info-box">
                                        <div class="kt-info-icon-case">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div class="kt-info-content-case">
                                            <h4>{{ $page_content['contact_us_today_title'] }}</h4>
                                            {{ $page_content['contact_us_number'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="kadence_infobox_widget">
                            <div class="kadence_infobox">
                                <a href="mailto:shadowsphotoprinting@outlook.com">
                                    <div class="kad-info-box">
                                        <div class="kt-info-icon-case">
                                            <i class="fa-regular fa-envelope"></i>
                                        </div>
                                        <div class="kt-info-content-case">
                                            <h4>{{ $page_content['email_for_query_title'] }}</h4>
                                            {{ $page_content['email'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="kadence_infobox_widget">
                            <div class="kadence_infobox">
                                <a href="#">
                                    <div class="kad-info-box">
                                        <div class="kt-info-icon-case">
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </div>
                                        <div class="kt-info-content-case">
                                            <h4>{{ $page_content['facebook_page_title'] }}</h4>
                                           </div>
                                    </div>
                                </a>
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
