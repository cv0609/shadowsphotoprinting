@extends('front-end.layout.main')
@section('content')
@php
   $PageDataService = app(App\Services\PageDataService::class);
   $CartService = app(App\Services\CartService::class);
   $products = $PageDataService->getProductBySlug($page_content['slug']);
@endphp

<section class="scrapbook-banner common-banner">
    <div class="banner-img">
        @foreach ($page_content['scrapbook_banner'] as $scrapbook_image)
          <img src="{{ asset($scrapbook_image) }}" alt="{{ pathinfo($scrapbook_image, PATHINFO_FILENAME) }}">
        @endforeach
    </div>
    <div class="container">
        <div class="contact-bnr-text">
            {{-- <h2>SCRAPBOOK PRINTS </h2> --}}
            <h2>{{ $page_content['scrapbook_banner_title'] }} </h2>
        </div>
    </div>
</section>

<section class="products_list">
    <div class="container">
        <div class="Product_box">
            <ul class="product-list">
                @foreach ($products as $product )
                @php
                    $product_sale_price =  $CartService->getProductSalePrice($product['id']);
                @endphp
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="{{ asset($product['product_image']) }}" alt="Image">
                            @if(isset($product_sale_price) && !empty($product_sale_price))
                              <p class="sale_product">sale</p>
                            @endif
                        </div>
                        <div class="Product_info">
                            <h3>{{ $product['product_title'] }}</h3>
                            <div class="cart_price">

                                @if(isset($product_sale_price) && !empty($product_sale_price))
                                    <span class="price">Price: ${{ $product_sale_price }}</span>
                                    <p class="discounted_price">Price : <span>${{$product['product_price']}}</span></p>

                                @else
                                    <span class="price">Price: ${{ $product['product_price'] }}</span>
                                    {{-- <p class="discounted_price">Price : <span>$50</span></p> --}}
                                @endif

                            </div>
                            <div class="print_paper_type">Type of Paper Use:
                                <select>
                                    <option>{{ $product['type_of_paper_use'] }}</option>
                                </select></div>
                                {!! html_entity_decode($product['product_description']) !!}
                               
                        </div>
                    </div>
                </li>
                @endforeach
                {{-- <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap6.jpg" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>12″x 12″ prints – with 4mm white border</h3>
                            <div class="cart_price">
                                <span class="price">Price: $2.50</span>
                            </div>
                            <div class="print_paper_type">Type of Paper Use: <select>
                                    <option>Luster</option>
                                </select></div>
                            <p>
                                At Shadows Photo Printing we offer professional photo printing by professional
                                Photographers who take the time to check the quality of your image before we
                                print, as we understand how important your beautiful memories are.
                                Once we have checked the quality of your wonderful image and there are no issues
                                we will go ahead and carefully print your beautiful memories and dispatch them
                                as quickly as possible.
                                12×12″ (30.48×30.48cm) – with 4mm white border Scrapbook Print.
                                Dimensions: Width: 30.48cm, Height: 30.48cm</p>
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
