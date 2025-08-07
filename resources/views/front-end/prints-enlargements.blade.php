@extends('front-end.layout.main')
@section('content')
@php
   $PageDataService = app(App\Services\PageDataService::class);
   $products = $PageDataService->getProductBySlug($page_content['slug']);
   $CartService = app(App\Services\CartService::class);
@endphp
{{-- @php dd($page_content); @endphp --}}
<section class="poster-bnr">
    <div class="banner-img">
        @foreach ($page_content['prints_enlargements_banner'] as $prints_posters_image)
          <img src="{{ asset($prints_posters_image) }}" alt="{{ pathinfo($prints_posters_image, PATHINFO_FILENAME) }}">
        @endforeach
    </div>
    <div class="container">
        <div class="contact-bnr-text">
            {{-- <h2>PRINTS & ENLARGEMENTS </h2> --}}
            <h2>{{ $page_content['prints_enlargements_banner_title'] }}</h2>
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
                            <img src="{{ asset($product['product_image']) }}" alt="Image">
                            @if(isset($product_sale_price) && !empty($product_sale_price))
                             <p class="sale_product">sale</p>
                            @endif
                        </div>
                        <div class="Product_info">
                            <h3>{{ $product['product_title'] }}
                            </h3>
                            {{-- <div class="cart_price">
                                <span class="price">Price: ${{ $product['product_price'] }}
                                </span>
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
                                {!! html_entity_decode($product['product_description']) !!}
                                <p><a href="{{ route('more-info') }}" class="more-info-link">More Info</a></p>
                        </div>
                    </div>
                </li>
                @endforeach
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
