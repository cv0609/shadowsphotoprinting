@extends('front-end.layout.main')
@section('content')

<div class="kt-bc-nomargin">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="home">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="shop">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span>Gift Card</span>
            </div>
        </div>
    </div>
</div>

<section class="shadow-taxlist">
    <div class="container">
        <div class="notices-wrapper">
            <div class="kad-shop-top">
                <div class="results-count">
                    <p> Showing all 3 results</p>
                </div>
                <div class="kad-woo-ordering">
                    <form method="get">
                        <select name="orderby" class="orderby" aria-label="Shop order">
                            <option value="menu_order" selected="selected">Default sorting</option>
                            <option value="popularity">Sort by popularity</option>
                            <option value="date">Sort by latest</option>
                            <option value="price">Sort by price: low to high</option>
                            <option value="price-desc">Sort by price: high to low</option>
                        </select>
                    </form>
                </div>
                <div class="product-toggleouter">
                    <span class="grid-view active"><i class="fa fa-th" aria-hidden="true"></i> </span>
                    <span class="list-view"><i class="fa fa-bars" aria-hidden="true"></i> </span>
                </div>
            </div>
            <ul class="isotope-intrinsic">
                <li class="type-product">
                    <div class="clearfix ">
                        <a href="{{ url('/our-products/gift-card/gift-card') }}">
                            <div class="noflipper ">
                                <div class="product-animations">
                                    <img src="{{ asset('assets/images/cardgift.jpg') }}" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                {{-- <a href="{{ url('/our-products/gift-card/gift-card') }}"> --}}
                                    <h3>Gift Card</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="type-product">
                    <div class="clearfix ">
                        <a href="{{ url('/our-products/gift-card/birthday-gift-card') }}">
                            <div class="noflipper ">
                                <div class="product-animations">
                                    <img src="{{ asset('assets/images/AdobeStock.jpeg') }}" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="{{ url('/our-products/gift-card/birthday-gift-card') }}">
                                    <h3>Birthday Gift Card</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="type-product">
                    <div class="clearfix ">
                        <a href="{{ url('/our-products/gift-card/mothers-day-gift-card') }}">
                            <div class="noflipper ">
                                <div class="product-animations">
                                    <img src="{{ asset('assets/images/Mothers-Day-Gfit.jpg') }}" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="{{ url('/our-products/gift-card/mothers-day-gift-card') }}">
                                    <h3>Mothers Day Gift Card</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
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
