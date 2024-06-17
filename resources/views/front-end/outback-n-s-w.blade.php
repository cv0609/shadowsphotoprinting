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
                <span><a href="photos">images</a></span>
                <span class="bc-delimiter">»</span>
                <span> OUTBACK N.S.W</span>
            </div>
        </div>
    </div>
</div>

<section class="subcategories">
    <div class="container">
        <div class="subcate">
            <ul class="cstmlist">
                <li class="central"> <a href="central-west-n-s-w" target="_blank">Central West N.S.W</a> </li>
                <li class="central"> <a href="childrens-photos" target="_blank">Children’s Photos</a> </li>
                <li class="central"> <a href="countryside-victoria" target="_blank" >Countryside Victoria</a> </li>
                <li class="central"> <a href="outback-n-s-w" target="_blank" class="active">Outback N.S.W</a> </li>
                <li class="central"> <a href="poems" target="_blank">Pomes and Quotes Photos</a> </li>
                <li class="central"> <a href="southern-queensland-country" target="_blank">Southern Queensland Country</a> </li>
            </ul>
        </div>
    </div>
</section>


<section class="shadow-taxlist">
    <div class="container">
        <div class="notices-wrapper">
            <div class="kad-shop-top">
                <div class="results-count">
                    <p> Showing the single result </p>
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
                <!-- <div class="product-toggleouter">
                    <span class="grid-view active"><i class="fa fa-th" aria-hidden="true"></i> </span>
                    <span class="list-view"><i class="fa fa-bars" aria-hidden="true"></i> </span>
                </div> -->
            </div>
            <ul class="isotope-intrinsic">
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Platypus.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Platypus2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>A Platypus Down Under</h3>
                                    <span>$2.75 - $127.17 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                 </ul>
            <!-- <div class="paginations">
                <ul class="page-numbers">
                    <li> <span> <a class="current">1</a></span>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">6</a></li>
                    <li><a href="#"><i class="fa-solid fa-arrow-right"></i></a></li>
                </ul>
            </div> -->
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
