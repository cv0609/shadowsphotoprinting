@extends('layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="product-banner">
    <div class="banner-img">
        @foreach ($page_content['our_products_banner'] as $our_products_image)
          <img src="{{ asset($our_products_image) }}" alt="{{ pathinfo($our_products_image, PATHINFO_FILENAME) }}">
        @endforeach
    </div>
    <div class="container">
        <div class="contact-bnr-text">
            {{-- <h2>OUR PRODUCTS </h2> --}}
            <h2>{{ $page_content['our_products_banner_title'] }} </h2>
        </div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <div class="categories-wrapper custom-categories">
            <div class="row">
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/Categories1.png" alt="">
                            <span>SCRAPBOOK PRINTS</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/categories2.jpg" alt="">
                            <span>CANVAS PRINTS</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/categories3.jpg" alt="">
                            <span>POSTERS &amp; PANORAMICS</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/categories4.jpg" alt="">
                            <span>PRINTS &amp; ENLARGEMENTS </span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/gift-card-scaled.jpeg" alt="">
                            <span>Gift Cards</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/The-Dish-Parkes.jpg" alt="">
                            <span>Central West N.S.W</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/working-on-the-railroad.jpg" alt="">
                            <span>Children’s Photos</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/Menindee-Lakes-sunse.jpg" alt="">
                            <span>Pomes and Quotes Photos</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/Rochester.jpg" alt="">
                            <span>Countryside Victoria</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/Packsaddle.jpg" alt="">
                            <span>Outback N.S.W</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ">
                    <div class="product-categories">
                        <a href="#">
                            <img src="assets/images/Yelarbon-Silos.jpg" alt="">
                            <span>Southern Queensland Country</span>
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