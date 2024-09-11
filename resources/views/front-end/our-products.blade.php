@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('content')
    <section class="product-banner">
        <div class="banner-img">
            @foreach ($page_content['our_products_banner'] as $our_products_image)
            <img src="{{ asset($our_products_image) }}" alt="{{ pathinfo($our_products_image, PATHINFO_FILENAME) }}">
            @endforeach
        </div>
        <div class="container">
            <div class="contact-bnr-text">
                <h2>{{ $page_content['our_products_banner_title'] }} </h2>
            </div>
        </div>
    </section>

    <section class="categories">
        <div class="container">
            <div class="categories-wrapper custom-categories">
                <div class="row">
                    @foreach ($ProductCategories as $productCategory)
                        <div class="col-lg-3 col-md-6 ">
                            <div class="product-categories">
                                <a href="{{ url('our-products/'.str_replace(' ', '-', strtolower($productCategory->name)))}}">
                                    <img src="{{(isset($productCategory['image']) && !empty($productCategory['image'])) ? asset($productCategory['image'])  : asset('assets/admin/images/dummy-image.jpg') }}" alt="Image">
                                    <span>{{ ucfirst($productCategory['name']) }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
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
