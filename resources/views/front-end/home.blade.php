@extends('front-end.layout.main')
@section('content')
@php
$PageDataService = app(App\Services\PageDataService::class);
$ProductCategories = $PageDataService->getProductCategories();
$ProductCategoriesForBulk = $PageDataService->getProductCategoriesForBulk();
@endphp

<div class="banner-slider fade-slider">
    @foreach ($page_content['main_banner'] as $image)
    <div>
        <div class="image">
            <div class="slider-wrapper">
                <img src="{{ asset($image) }}" alt="{{ pathinfo($image, PATHINFO_FILENAME) }}">
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- HERO SECTION -->

<!-- Welcome to Shadows Photo Printing -->

<section class="entry-content">
    <div class="container">
        <div class="entry-content-wrapper">
            <!-- Corrected class name -->
            <div class="row">
                <!-- Add row here -->
                <div class="col-lg-6">
                    <div class="entry-img">
                        <figure data-aos="fade-right">
                            <img src="{{ $page_content['side_image'] }}" alt="Side Image">
                        </figure>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="entry-text">
                        <div class="widget-title" data-aos="fade-left">
                            <h3>{{ $page_content['title'] }}</h3>
                            <div class="textwidget">
                                {!! $page_content['description'] !!}
                            </div>
                        </div>
                        <div class="so-widget-sow-button" data-aos="fade-left">
                            <a href="{{ url('shop') }}">{{ $page_content['shop_now'] }}</a>
                        </div>
                        <div class="afterpay-4-payment">
                            <span>Always interest-free, when you pay it in 4 installment.</span>
                            <div class="after-pay-modal afterpayButton">
                                <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" width="98" height="36" class="compact-badge-logo" viewBox="0 0 100 21">
                                    <path class="afterpay-logo-badge-background" fill="#b2fce4" d="M89.85 20.92h-78.9a10.42 10.42 0 110-20.82h78.89a10.42 10.42 0 010 20.83v-.01z"></path>
                                    <g class="afterpay-logo-badge-lockup">
                                        <path d="M85.05 6.37L82.88 5.1l-2.2-1.27a2.2 2.2 0 00-3.3 1.9v.29c0 .16.08.3.22.38l1.03.58c.28.16.63-.04.63-.37v-.67c0-.34.36-.54.65-.38l2.02 1.16 2 1.15c.3.16.3.58 0 .75l-2 1.15-2.02 1.16a.43.43 0 01-.65-.38v-.33a2.2 2.2 0 00-3.28-1.9l-2.2 1.26-2.19 1.25a2.2 2.2 0 000 3.8l2.18 1.25 2.2 1.27a2.2 2.2 0 003.3-1.9v-.3c0-.15-.09-.3-.23-.37L78.02 14a.43.43 0 00-.64.37v.67c0 .34-.36.54-.65.38l-2-1.16-2-1.15a.43.43 0 010-.75l2-1.15 2-1.16c.3-.16.65.05.65.38v.33a2.2 2.2 0 003.3 1.9l2.2-1.26 2.17-1.25a2.2 2.2 0 000-3.8z"></path>
                                        <path d="M70.77 6.78l-5.1 10.53h-2.12l1.91-3.93-3-6.6h2.17l1.93 4.42 2.1-4.42h2.11z"></path>
                                        <path d="M19.8 10.5c0-1.24-.92-2.12-2.04-2.12s-2.03.9-2.03 2.14c0 1.23.91 2.14 2.03 2.14s2.03-.88 2.03-2.14m.02 3.74v-.97a3 3 0 01-2.36 1.09c-2.05 0-3.6-1.65-3.6-3.86 0-2.2 1.61-3.87 3.65-3.87.95 0 1.76.42 2.31 1.08v-.95h1.84v7.48h-1.84z"></path>
                                        <path d="M30.6 12.6c-.65 0-.84-.24-.84-.87V8.4h1.2V6.78h-1.2V4.96h-1.88v1.82h-2.43v-.74c0-.63.24-.87.9-.87h.42V3.72h-.9c-1.56 0-2.3.5-2.3 2.07v1h-1.04V8.4h1.04v5.85h1.88V8.4h2.43v3.66c0 1.53.6 2.19 2.11 2.19h.97V12.6h-.37z"></path>
                                        <path d="M37.35 9.85c-.13-.97-.93-1.56-1.86-1.56-.92 0-1.7.57-1.88 1.56h3.74zM33.6 11c.13 1.1.93 1.74 1.93 1.74.8 0 1.4-.37 1.76-.97h1.94c-.45 1.58-1.87 2.6-3.74 2.6a3.68 3.68 0 01-3.85-3.85 3.78 3.78 0 013.9-3.9 3.74 3.74 0 013.8 4.38H33.6z"></path>
                                        <path d="M51.35 10.5c0-1.2-.9-2.12-2.03-2.12-1.12 0-2.03.9-2.03 2.14 0 1.23.9 2.14 2.03 2.14 1.12 0 2.03-.93 2.03-2.14m-5.92 6.79V6.78h1.84v.97a2.97 2.97 0 012.36-1.1c2.02 0 3.6 1.65 3.6 3.85s-1.6 3.87-3.65 3.87a2.9 2.9 0 01-2.26-1v3.93h-1.9.01z"></path>
                                        <path d="M59.86 10.5c0-1.24-.9-2.12-2.03-2.12-1.12 0-2.04.9-2.04 2.14 0 1.23.92 2.14 2.04 2.14s2.03-.88 2.03-2.14m.02 3.74v-.97a3 3 0 01-2.36 1.09c-2.05 0-3.6-1.65-3.6-3.86 0-2.2 1.61-3.87 3.64-3.87.96 0 1.76.42 2.32 1.08v-.95h1.84v7.48h-1.84z"></path>
                                        <path d="M42.11 7.5s.47-.86 1.62-.86c.5 0 .8.17.8.17v1.9s-.69-.42-1.32-.33c-.64.09-1.04.67-1.04 1.45v4.42h-1.9V6.78h1.84v.73z"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- End of row -->
            <div class="row textwidget" style="padding-top: 31px;">
                <!-- Add row here -->
                {!! $page_content['about_description'] !!}
            </div>
        </div>
    </div>
</section>
<!-- Welcome to Shadows Photo Printing -->

<!--  -->

<section class="custom-size">
    <div class="container">
        <div class="custom-wrapper">
            <div class="custom-size-content">
                <h2 data-aos="fade-right">{!! $page_content['quote_description'] !!}</h2>
                <div class="ow-button-base" data-aos="fade-left">
                    <a href="{{ url('get-a-quote') }}"> {{ $page_content['get_a_quote'] }} </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  -->


<section class="categories">
    <div class="container">
        <div class="categories-heading">
            <h3>{{ $page_content['shop_by_categories_title'] }}</h3>
        </div>
        <div class="categories-wrapper">
            <div class="row">
                @foreach ($ProductCategories as $ProductCategory)
                <div class="col-lg-3 col-md-6">
                    <div class="product-categories dfdf">
                        <a href="{{ url('our-products/'.str_replace(' ', '-', strtolower($ProductCategory->name)))}}">
                            <img src="{{ (isset($ProductCategory->image) && !empty($ProductCategory->image)) ? asset($ProductCategory->image) : asset('assets/admin/images/dummy-image.jpg')}}" alt="">
                            <span>{{ ucfirst($ProductCategory->name) }}</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Shop By Categories -->

@php
$sale_popup = getSalePopup();
@endphp

@if(isset($sale_popup) && !empty($sale_popup))
<div id="sailImagePopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="background-color:transparent">
           
            <div class="modal-body sail-modal-body">
                <div class="modal-header sail-modal-header">
                    <button type="button" class="close close-popup" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @php
                    // Get file extension to check if it's a video
                    $fileExtension = pathinfo($sale_popup->image, PATHINFO_EXTENSION);
                    $videoExtensions = ['mp4', 'webm', 'ogg'];
                @endphp

                @if(in_array($fileExtension, $videoExtensions))
                    <!-- Show video if the type is video -->
                    <video id="saleVideo" width="100%" height="auto" autoplay muted playsinline loop style="height: 500px;">
                        <source src="{{ asset($sale_popup->image) }}" type="video/{{ $fileExtension }}">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <!-- Show image if the type is not video -->
                    <img src="{{ asset($sale_popup->image) }}" alt="image" width="100%">
                @endif
            </div>
        </div>
    </div>
</div>
@endif




<!-- Photo Restoration Service -->

<section class="restoration">
    <div class="container">
        <div class="restoration-box">
            <div class="restoration-wrapper kash">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="restoration-content">
                            <h2>{{ $page_content['photo_restoration_service_description'] }}</h2>
                            <div class="restoration-btn">
                                <a href="{{ url('contact-us') }}">{{ $page_content['contact_us'] }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="restoration-img">
                            <figure>
                                <img src="{{ $page_content['photo_restoration_service_image'] }}" alt="Photo restoration image">
                                {{-- <img src="assets/images/cart-page.jpg" alt=""> --}}
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
            <div class="restoration-wrapper seconds">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="restoration-img">
                            <figure>
                                <img src="{{ $page_content['accept_bulk_order_image'] }}" alt="bulk image">
                                {{-- <img src="assets/images/canvasprint9.jpg" alt=""> --}}
                            </figure>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="restoration-content">
                            <h2>{{ ucwords($page_content['accept_bulk_order_description']) }}</h2>
                            {{-- <h2>We accept bulk orders for</h2> --}}
                            <p>
                                @foreach ($ProductCategoriesForBulk as $ProductCategory)
                                <a href="{{ url('our-products/'.str_replace(' ', '-', strtolower($ProductCategory->name)))}}">{{ ucwords($ProductCategory->name) }} {{ (!$loop->last) ? ',' : '' }} </a>
                                @endforeach
                            </p>
                            <div class="restoration-btn">
                                <a href="{{ url('shop') }}">{{ $page_content['order_now'] }}</a>
                                {{-- <a href="shop">Order Now</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Photo Restoration Service -->
@endsection
@section('scripts')
<script>
    AOS.init({
        duration: 1200
    , })

</script>
<script>
    var is_popup = @json($sale_popup);
    
    $('.fade-slider').slick({
        autoplay: true
        , dots: true
        , infinite: true
        , speed: 500
        , fade: true
        , cssEase: 'linear'
    });

    $(document).ready(function() {

        $('.textwidget p').css('margin-bottom', '10px')

        if(is_popup){

            if (!getCookie("sailpopupcokies")) {
                setTimeout(function() {
                    $('#sailImagePopup').modal('show');
                }, 2000); 
            }
    
            $('.close-popup').on('click', function() {
                $('#sailImagePopup').modal('hide');
                setCookie("sailpopupcokies", "closed", 1);
            });

        }
    })

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Adds 1 day in milliseconds
            expires = "; expires=" + date.toUTCString(); // Set expiration date
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/"; // Cookie applies to entire site
    }

    // Function to get a cookie by name
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
</script>


@endsection
