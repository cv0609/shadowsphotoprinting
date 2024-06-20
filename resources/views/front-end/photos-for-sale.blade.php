@extends('front-end.layout.main')
@section('content')

<div class="kt-bc-nomargin">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{ url('/') }}">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="{{ url('shop') }}">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span>images</span>
            </div>
        </div>
    </div>
</div>


<section class="subcategories">
    <div class="container">
        <div class="subcate">
            <ul class="cstmlist">
                @foreach ($productCategories as $productCategory)
                 <li class="central"> <a href="{{ route('photos-for-sale',['slug'=>$productCategory->slug]) }}">{{ ucfirst($productCategory->name) }}</a> </li>
               @endforeach
            </ul>
        </div>
    </div>
</section>


<section class="shadow-taxlist">
    <div class="container">
        <div class="notices-wrapper">
            <div class="kad-shop-top">
                <div class="results-count">
                    <p> Showing all {{count($products)}} results</p>
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
                @foreach ($products as $product)
                    <li class="type-product">
                        <div class="clearfix kold">

                            <a href="javascript:void(0)">
                                <div class="noflipper" id="image-div">
                                  <div class="product-animation">
                                     @foreach (explode(',',$product->product_images) as $key => $product_image)
                                       <img src="{{ asset($product_image) }}" alt="">
                                    @endforeach
                                 </div>
                               </div>
                            </a>

                            <div class="details-product-item">
                                <div class="product_details-card">
                                    <a href="#">
                                        <h3>{{ ucfirst($product->product_title) }}</h3>
                                        <span>${{ $product->min_price }} - ${{ $product->max_price }} incl. GST</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="paginations">
                {{ $products->links('pagination::bootstrap-4') }}
                {{-- <ul class="page-numbers">
                    <li> <a class="current">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">6</a></li>
                    <li><a href="#"><i class="fa-solid fa-arrow-right"></i></a></li>
                </ul> --}}
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
