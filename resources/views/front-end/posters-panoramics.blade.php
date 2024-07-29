@extends('front-end.layout.main')
@section('content')
@php
   $PageDataService = app(App\Services\PageDataService::class);
   $products = $PageDataService->getProductBySlug($page_content['slug']);
   $CartService = app(App\Services\CartService::class);
@endphp
<section class="poster-bnr">
    <div class="banner-img">
        @foreach ($page_content['posters_panoramic_banner'] as $poster_image)
          <img src="{{ asset($poster_image) }}" alt="{{ pathinfo($poster_image, PATHINFO_FILENAME) }}">
        @endforeach
    </div>
    <div class="container">
        <div class="contact-bnr-text">
            {{-- <h2>POSTERS & PANORAMICS</h2> --}}
            <h2>{{ $page_content['posters_panoramic_banner_title'] }}</h2>
        </div>
    </div>
</section>

<section class="products_list">
    <div class="container">
        <div class="Product_box">
            <ul class="product-list">
                @foreach ($products as $product)
                @php
                  $product_sale_price =  $CartService->getProductSalePrice($product['id']);
                @endphp
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="{{ (isset($product['product_image']) && !empty($product['product_image'])) ? asset($product['product_image']) : asset('assets/admin/images/dummy-image.jpg') }}" alt="Image">
                        </div>
                        <div class="Product_info">
                            <h3>{{ $product['product_title'] }} </h3>
                            {{-- <div class="cart_price">
                                <span class="price">Price: ${{ $product['product_price'] }} </span>
                            </div> --}}

                            <div class="cart_price">

                                @if(isset($product_sale_price) && !empty($product_sale_price))
                                    <span class="price">Price: ${{ $product_sale_price }}</span>
                                    <p class="discounted_price">Price : <span>${{$product['product_price']}}</span></p>

                                @else
                                    <span class="price">Price: ${{ $product['product_price'] }}</span>
                                @endif

                            </div>


                            <div class="print_paper_type">Type of Paper Use: <select>
                                    <option>{{ $product['type_of_paper_use'] }}</option>
                                </select></div>
                            <p>{!! html_entity_decode($product['product_description']) !!} </p>
                        </div>
                    </div>
                </li>
                @endforeach
                {{-- <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/dup17.png" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>30″x40” Prints </h3>
                            <div class="cart_price">
                                <span class="price">Price: $74.80 </span>
                            </div>
                            <div class="print_paper_type">Type of Paper Use: <select>
                                    <option>Luster</option>
                                </select></div>
                            <p>At Shadows Photo Printing we offer professional photo printing by professional
                                Photographers who take the time to check the quality of your image before we
                                print, as we understand how important your beautiful memories are. Once we have
                                checked the quality of your wonderful image and there are no issues we will go
                                ahead and carefully print your beautiful memories and dispatch them as quickly
                                as possible. 30″x40” Prints (76.2×101.6cm) Posters & Panoramics. Dimensions:
                                Width: 76.2cm, Height: 101.6cm
                            </p>
                        </div>
                    </div>
                </li> --}}
            </ul>
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
