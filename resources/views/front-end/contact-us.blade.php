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

         <div class="panel-grid-cell contact-page-panel">
            <div class="panel-widget shipping-contact-info">
                <h2>🚚 Flat-Rate Shipping Across Australia</h2>
                <p class="subtitle">Wherever you are, we’re here to get your prints to you safely and simply. <br>
                Choose Standard or Express Post and let us do the rest.</p>

                <h3>Photo Prints & Scrapbook Pages</h3>
                <ul>
                    <li>1–60 prints — $15 / $20</li>
                    <li>61–100 prints — $18.40 / $22.65</li>
                    <li>101+ prints — $22.60 / $30.21</li>
                </ul>

                <h3>Canvas, Photo Enlargements & Posters </h3>
                <ul>
                    <li>Flat-rate — $22.60 Standard / $30.21 Express</li>
                </ul>
            </div>
        </div>


            <div class="panel-grid-cell contact-page-panel">
                <div class="panel-widget shipping-contact-info">
                    <h2>❤️{{ $page_content['shipping_policy_title'] }}</h2>
                    <!-- {!! $page_content['shipping_policy_description'] !!} -->
                     <p class="subtitle">We print your order within <b>1–4 days.</b><br>
                     Delivery may take <b>up to 14 days</b>, depending on your location.<br>
                     If anything comes up during printing or delivery, we’ll let you know straight away.<br>
                     You’ll receive tracking details once your parcel is on the way.</p>
                </div>
            </div>

            <div class="panel-grid-cell contact-page-panel">
                <div class="panel-widget shipping-contact-info">
                    <!-- <h2>{{ $page_content['policy_refund_title'] }} </h2> -->
                    <h2>🔄Refunds & Reprints</h2>
                    <!-- {!! $page_content['policy_refund_description'] !!} -->
                    <p class="subtitle">We don't offer refunds.<br>
                    But if something arrives damaged or faulty, just send us an email with a photo.<br> 
                    If the issue is visible, we’ll reprint your image <b>free of charge</b> — if we stuff up, we pay for it!</p>
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
                                <a href="https://www.facebook.com">
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
