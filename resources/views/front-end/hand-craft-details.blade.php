@extends('front-end.layout.main')
@section('content')

<div class="kt-bc-nomargin">
    <div class="adbreadcrumbs never">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="index.html">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="order-prints.html">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="order-prints.html">images</a></span>
                <span class="bc-delimiter">»</span>
                <span>A PLATYPUS DOWN UNDER</span>
            </div>
        </div>
    </div>
</div>

<section class="description">
    <div class="container">
        <div class="description-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <div class="on-sale">
                        <div class="dirty">
                            <div class="chirs-img">
                                <div class="slider slider-for">
                                @php
                                    if(isset($productDetails)){
                                        $imageArray = explode(",", $productDetails->product_image);
                                    }
                                @endphp

                                    @foreach($imageArray as $arrImg)
                                        <div>
                                            <div class="billboard">
                                                <img src="{{ asset($arrImg) ?? ''}}" alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="chirs">
                                <div class="slider slider-nav">
                                    @foreach($imageArray as $arrImg)
                                        <div>
                                            <div class="billboard">
                                                <img src="{{ asset($arrImg) ?? ''}}" alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="canvas-summary">
                        <p>IMAGE</p>
                        <h2>{{$productDetails->product_title ?? ''}}</h2>
                        <p class="incl"> ${{$productDetails->price}}</p>
                        <div class="print_paper">
                            <form id="submitForm" method="post">
                                @csrf
                                {{-- <input type="hidden" id="product_price"> --}}
                                <input type="hidden" id="product_price" value="{{$productDetails->price}}">
                                <input type="hidden" id="product_image" value="{{$image1 ?? ''}}">
                                <input type="hidden" id="product_id" value="{{$productDetails->id}}">

                                <div class="quanti add">
                                    <span><input type="number" id="product_qty" placeholder="0"></span>
                                    <button type="button" id="addToCartBtn">Add to cart</button>
                                </div>
                                <h6>
                                    <span class="error-message" id="product_qty_error"></span>
                                </h6>
                        </form>
                            <div class="product_meta">
                                <span>SKU: N/A</span>
                                <span class="posted_in">
                                    Category: <a href="{{ route('hand-craft') }}">IMAGE, POMES AND QUOTES PHOTOS</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wonderful">
    <div class="container">
        <div class="wonderful-box">
            <ul class="tab-title">
                <li><a> Description</a></li>
            </ul>
            <div class="woocommerce-tabs">
                <h2>Description</h2>
                <p>At Shadows Photo Printing we offer professional photo printing by professional Photographers
                    who take the time to check the quality of your image before we print, as we understand how
                    important your beautiful memories are.</p>
                <p>Once we have checked the quality of your wonderful image and there are no issues we will go
                    ahead and carefully print your beautiful memories and dispatch them as quickly as possible.
                </p>
                <p>12”x30” Canvas (30.5cm x 76cm) Canvas Print.</p>
                <p>Dimensions: Width: 30.5cm, Height: 76cm</p>
            </div>
        </div>
    </div>
</section>


<section class="related-products">
    <div class="container">
        <div class="dimensions">
            <h2>Related Products </h2>

            <div class="related-slider">
                <div class="slider responsive">
                    @foreach ($relatedProduct as $item)
                    @php
                        if(isset($item)){
                            $imageArray = explode(",", $item->product_image);
                        }
                    @endphp
                    <div>
                        <div class="sets">
                            <a href="{{ route('hand-craft-details',['slug'=>$item->slug]) }}">
                            <div class="products-img">
                                @foreach($imageArray as $arrImg)
                                    <img src="{{ asset($arrImg) ?? ''}}" alt="">
                                @endforeach
                                <div class="onsale">
                                    <span>Sale!</span>
                                </div>
                            </div>
                            <div class="text-slider">
                                <h3>{{$item->product_title}}</h3>
                                <p>${{$item->price}}</p>
                            </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>




@endsection
@section('scripts')

<script>

$(document).ready(function() {
    var productId = "{{$productDetails->id}}";

    $('#addToCartBtn').click(function() {
      
        var isValid = true;
        $('.error-message').css('color','red');
        $('.error-message').closest('input select').addClass('validator');
        $('.error-message').text(''); // Clear previous error messages
        $('.error-message').css('color', 'red').each(function() {
            $(this).siblings('input select').addClass('validator');
        });

        var product_price = $('#product_price').val();
        var quantity = $('#product_qty').val();
        var product_image = $('#product_image').val();
        var product_id = $('#product_id').val();
        
        
        if (quantity <= 0) {
            $('#product_qty_error').text('Please enter minimum 1 quantity');
            isValid = false;
        }
        
        if (isValid) {

        let cartItems = [];
        let total = 0;
        let selectedImages = [];

        if (quantity !== '' && quantity > 0) {
            let price = parseFloat(product_price);
            let productId = product_id; 
            let totalPrice = quantity * price;
            total += totalPrice;
            cartItems.push({
                product_id: productId,
                quantity: parseFloat(quantity),
                price: price
            });
        }

        selectedImages.push(product_image);

        if (cartItems.length > 0) {
            // Send cart items to the server
            $.ajax({
                url: "{{ route('add-to-cart') }}", // Replace with your route
                method: 'POST',
                data: {
                    cart_items: cartItems,
                    total: total,
                    selectedImages:selectedImages,
                    item_type:'hand_craft',
                    card_price:product_price,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    location.href = "{{ route('cart') }}";
                },
                error: function(xhr, status, error) {
                    console.error('Error adding items to cart:', error);
                }
            });
        } else {
            alert('No items to add to cart!');
        }
        }
    });
});



    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: true,
        centerMode: true,
        focusOnSelect: true
    });

</script>
 <script>
    $('.responsive').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    dots: false
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>

@endsection
