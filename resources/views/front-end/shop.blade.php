@extends('front-end.layout.main')
@section('content')
 <!-- HERO SECTION -->
        {{-- @php dd($page_content); @endphp --}}

 <div class="banner-slider fade-slider">

    @foreach ($page_content['shop_banner'] as $image)
    <div>
        <div class="image">


            <div class="slider-wrapper">
                <img src="{{ asset($image) }}" alt="{{ pathinfo($image, PATHINFO_FILENAME) }}">
                {{-- <img src="assets/images/Wp2print-starter-2.jpg" alt=""> --}}
            </div>
        </div>
    </div>
    @endforeach

    {{-- <div>
        <div class="image">
            <div class="slider-wrapper">
                <img src="assets/images/Wp2print-starter-3.jpg" alt="">
            </div>
        </div>
    </div> --}}
</div>
<!-- HERO SECTION -->

<section class="instructions">
    <div class="container">
        <div class="instructions-inner">
            <div class="so-widget">
                <h3>{{ $page_content['page_instruction_title'] }}</h3>
                <div class="tinymce ">
                    <ol>
                        {!! $page_content['page_instruction_description'] !!}
                    </ol>
                </div>

            </div>
            <div class="fcsg-wrap">
                <form action="{{ route('shop-upload-image') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="uploading">
                        <div class="uploading-img">
                            <p>{{ $page_content['select_images_for_upload_title'] }}</p>
                            <div id="selectedFiles"></div>
                            <a id="selectfiles" href="javascript:;" class="button"
                                style="position: relative; z-index: 1;">Select images</a>
                            <input type="file" id="fileInput" name="image[]" multiple style="display: none;">
                            <button type="submit" id="uploadfiles" class="button button-primary" style="display: none;">Upload images</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<section class="kad_product_wrapper">
    <div class="container">
        <div class="kad_product">
            <div class="row">
                {{-- <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="javascript:void(0)">
                            <div class="cat-intrinsic">
                                <img src="assets/images/cart-page.jpg " alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>product-cat-title-area</h3>
                            </div>
                        </a>
                    </div>
                </div> --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="javascript:void(0)">
                            <div class="cat-intrinsic">
                                <img src="assets/images/gift-card-scaled.jpeg" alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>Gift Card <small class="count">(3)</small></h3>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="javascript:void(0)">
                            <div class="cat-intrinsic">
                                <img src="assets/images/The-Dish-Parkes.jpg" alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>Image <small class="count">(63)</small></h3>
                            </div>
                        </a>
                    </div>
                </div>
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
