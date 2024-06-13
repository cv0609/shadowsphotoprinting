@extends('layout.main')
@section('content')
@php
$PageDataService = app(App\Services\PageDataService::class);
$ProductCategories = $PageDataService->getProductCategories();
@endphp

        <div class="banner-slider fade-slider">
            @foreach ($page_content['main_banner'] as $image)
                <div>
                    <div class="image">
                        <div class="slider-wrapper">
                            <img src="{{ asset($image) }}" alt="{{ pathinfo($image, PATHINFO_FILENAME) }}">
                            {{-- <img src="assets/images/Wp2print-starter-1.jpg" alt=""> --}}
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- <div>
                <div class="image">
                    <div class="slider-wrapper">
                        <img src="assets/images/Wp2print-starter-9.jpg" alt="">
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- HERO SECTION -->

        <!-- Welcome to Shadows Photo Printing -->

        <section class="entry-content">
            <div class="container">
                <div class="entry-content-wrapper"> <!-- Corrected class name -->
                    <div class="row"> <!-- Add row here -->
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
                                        {{-- <h3>Welcome to Shadows Photo Printing</h3> --}}
                                    <div class="textwidget">
                                        {!! $page_content['description'] !!}
                                        {{-- <p>
                                            At Shadows Photo Printing we offer professional photo printing by professional Photographers who take the time to check the quality of your image before we print, as we understand how important your beautiful memories are.
                                        </p>
                                        <p>
                                            Once we have checked the quality of your wonderful image and there are no issues we will go ahead and carefully print your beautiful memories and dispatch them as quickly as possible.
                                        </p> --}}
                                    </div>
                                </div>
                                <div class="so-widget-sow-button" data-aos="fade-left">
                                    <a href="shop">{{ $page_content['shop_now'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End of row -->
                </div>
            </div>
        </section>
        <!-- Welcome to Shadows Photo Printing -->

        <!--  -->

        <section class="custom-size">
            <div class="container">
                <div class="custom-wrapper">
                    <div class="custom-size-content" >
                        <h2 data-aos="fade-right">{!! $page_content['quote_description'] !!}</h2>
                        {{-- <h2 data-aos="fade-right">If you have a custom size to be printed, please fill out the form and We will get back to
                            you with the price.</h2> --}}
                        <div class="ow-button-base" data-aos="fade-left">
                            <a href="{{ url('get-a-quote') }}"> {{ $page_content['get_a_quote'] }} </a>
                            {{-- <a href="quote"> Get a Quote </a> --}}
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
                    {{-- <h3> Shop ByCategories</h3> --}}
                </div>
                <div class="categories-wrapper">
                    <div class="row">
                       @foreach ($ProductCategories as $ProductCategory)
                        <div class="col-lg-3 col-md-6">
                            <div class="product-categories">
                                <a href="{{ url('our-products/'.$ProductCategory->slug) }}">
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

        <!-- Photo Restoration Service -->

        <section class="restoration">
            <div class="container">
                <div class="restoration-box">
                    <div class="restoration-wrapper kash">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="restoration-content">
                                    <h2>{{ $page_content['photo_restoration_service_description'] }}</h2>
                                    {{-- <h2>Photo Restoration Service</h2> --}}
                                    <div class="restoration-btn">
                                        <a href="{{ url('contact-us') }}">{{ $page_content['contact_us'] }}</a>
                                        {{-- <a href="contact">Contact Us</a> --}}
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
                                    <h2>{{ $page_content['accept_bulk_order_description'] }}</h2>
                                    {{-- <h2>We accept bulk orders for</h2> --}}
                                    <p>
                                        @foreach ($ProductCategories as $ProductCategory)
                                          <a href="{{ url('our-products/'.$ProductCategory->slug) }}">{{ ucfirst($ProductCategory->name) }}  {{ (!$loop->last) ? ',' : '' }} </a>
                                        @endforeach
                                    </p>
                                    <div class="restoration-btn">
                                        <a href="shop">{{ $page_content['order_now'] }}</a>
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
            duration: 1200,
        })

    </script>
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
