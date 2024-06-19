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
                <span>CHILDREN’S PHOTOS</span>
            </div>
        </div>
    </div>
</div>


{{-- <section class="subcategories">
    <div class="container">
        <div class="subcate">
            <ul class="cstmlist">
                <li class="central"> <a href="central-west-n-s-w" target="_blank" >Central West N.S.W</a> </li>
                <li class="central"> <a href="childrens-photos" class="active">Children’s Photos</a> </li>
                <li class="central"> <a href="countryside-victoria" target="_blank">Countrysideeeeeeeeeeeeee Victoria</a> </li>
                <li class="central"> <a href="outback-n-s-w" target="_blank">Outback N.S.W</a> </li>
                <li class="central"> <a href="poems" target="_blank">Pomes and Quotes Photos</a> </li>
                <li class="central"> <a href="southern-queensland-country" target="_blank">Southern Queensland Country</a> </li>
            </ul>
        </div>
    </div>
</section> --}}


<section class="shadow-taxlist">
    <div class="container">
        <div class="notices-wrapper">
            <div class="kad-shop-top">
                <div class="results-count">
                    <p>Showing 1–12 of 29 results</p>
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
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Blinky-Bill.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Blinky-2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>Blinky Bill</h3>
                                    <span>$2.75 - $127.17 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Canola-Fields-Parkes.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Canol-2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>Canola Fields Parkes N.S.W</h3>
                                    <span>$30.95 - $164.30 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Dont.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Dont2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>Don’t let your yearnings get ahead of your earnings!</h3>
                                    <span>$4.15 - $124.80 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
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
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Blinky-Bill.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Blinky-2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>Blinky Bill</h3>
                                    <span>$2.75 - $127.17 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="type-product">
                    <div class="clearfix kold">
                        <a href="#">
                            <div class="noflipper ">
                                <div class="product-animation">
                                    <img src="assets/images/Canola-Fields-Parkes.jpg" alt="">
                                </div>
                                <div class="image_flip_back">
                                    <img src="assets/images/Canol-2.jpg" alt="">
                                </div>
                            </div>
                        </a>
                        <div class="details-product-item">
                            <div class="product_details-card">
                                <a href="#">
                                    <h3>Canola Fields Parkes N.S.W</h3>
                                    <span>$30.95 - $164.30 incl. GST</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="paginations">
                <ul class="page-numbers">
                    <li> <span> <a class="current">1</a></span>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#"><i class="fa-solid fa-arrow-right"></i></a></li>
                </ul>
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
