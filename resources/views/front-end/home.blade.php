@extends('front-end.layout.main')
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
                        </div>
                    </div>
                </div>
            @endforeach
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
                                    <div class="textwidget">
                                        {!! $page_content['description'] !!}
                                    </div>
                                </div>
                                <div class="so-widget-sow-button" data-aos="fade-left">
                                    <a href="{{ url('shop') }}">{{ $page_content['shop_now'] }}</a>
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
                                    <h2>{{ $page_content['accept_bulk_order_description'] }}</h2>
                                    {{-- <h2>We accept bulk orders for</h2> --}}
                                    <p>
                                        @foreach ($ProductCategories as $ProductCategory)
                                          <a href="{{ url('our-products/'.$ProductCategory->slug) }}">{{ ucfirst($ProductCategory->name) }}  {{ (!$loop->last) ? ',' : '' }} </a>
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
