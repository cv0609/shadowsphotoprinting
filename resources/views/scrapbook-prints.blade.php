@extends('layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}

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
                <li class="product-sect">
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
                </li>
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap5.jpg
                            " alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>12″x 12″ prints </h3>
                            <div class="cart_price">
                                <span class="price">Price: $2.50</span>
                            </div>
                            <div class="print_paper_type">Type of Paper Use: <select>
                                    <option>Luster</option>
                                </select>
                            </div>
                            <p>At Shadows Photo Printing we offer professional photo printing by professional
                                Photographers who take the time to check the quality of your image before we
                                print, as we understand how important your beautiful memories are. <br>

                                Once we have checked the quality of your wonderful image and there are no issues
                                we will go ahead and carefully print your beautiful memories and dispatch them
                                as quickly as possible.<br>

                                12×12″ (30.48cmx30.48cm) Scrapbook Print.<br>

                                Dimensions: Width: 30.48cm, Height: 30.48cm</p>
                        </div>
                    </div>
                </li>
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap4.jpg" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>10″x 10″ prints – with 4mm white border </h3>
                            <div class="cart_price">
                                <span class="price">Price: $1.95 </span>
                            </div>
                            <div class="print_paper_type">Type of Paper Use: <select>
                                    <option>Luster</option>
                                </select></div>
                            <p>
                                At Shadows Photo Printing we offer professional photo printing by professional
                                Photographers who take the time to check the quality of your image before we
                                print, as we understand how important your beautiful memories are. <br>

                                Once we have checked the quality of your wonderful image and there are no issues
                                we will go ahead and carefully print your beautiful memories and dispatch them
                                as quickly as possible.<br>

                                10×10″ (25.4cmx25.4cm) – with 4mm white border Scrapbook Print.<br>

                                Dimensions: Width: 25.4cm, Height: 25.4cm</p>
                        </div>
                    </div>
                </li>
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap3.jpg" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>10″x 10″ prints </h3>
                            <div class="cart_price">
                                <span class="price">Price: $1.95 </span>
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

                                10×10″ (25.4cm x 25.4cm) Scrapbook Print.

                                Dimensions: Width: 25.4cm, Height: 25.4cm
                            </p>
                        </div>
                    </div>
                </li>
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap2.jpg" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>8″x 8″ prints – with 4mm white border
                            </h3>
                            <div class="cart_price">
                                <span class="price">Price: $1.25
                                </span>
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

                                An 8×8″ (20.32 x 20.32 cm) – Square Digital Print.

                                Dimensions: Width: 20.32cm, Height: 20.32cm.</p>
                        </div>
                    </div>
                </li>
                <li class="product-sect">
                    <div class="Product-box">
                        <div class="Product_image">
                            <img src="assets/images/scrap1.jpg" alt="scrap6">
                        </div>
                        <div class="Product_info">
                            <h3>8″x 8″ prints </h3>
                            <div class="cart_price">
                                <span class="price">Price: $1.25
                                </span>
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

                                An 8×8″ (20.32 x 20.32 cm) – Square Digital Print.

                                Dimensions: Width: 20.32cm, Height: 20.32cm.
                            </p>
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