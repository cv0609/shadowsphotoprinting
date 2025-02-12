@extends('front-end.layout.main')
@section('content')

@php
   $CartService = app(App\Services\CartService::class);

//   function getS3Img($str, $size){
//     $str = str_replace('original', $size, $str);
//     return $str;
//   }
@endphp

<div class="kt-bc-nomargin">
    <div class="adbreadcrumbs never">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{ url('/') }}">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="{{ url('shop') }}">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="{{ url('our-products/hand-craft') }}">images</a></span>
                <span class="bc-delimiter">»</span>
                <span>{{$productDetails->slug}}</span>
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
                                    <div>
                                        <div class="billboard product-img">
                                            <img src="{{ asset($productDetails->product_image) ?? ''}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="canvas-summary">
                        {{-- <p>IMAGE</p> --}}
                        <h2>{{$productDetails->product_title ?? ''}}</h2>
                        <p class="incl">${{$productDetails->product_price}}</p>
                        <h6 style="color: #ffc205">Type of Paper Use:</h6>
                        {{-- <input type="text" name="" id="" value="{{$productDetails->type_of_paper_use}}"> --}}
                        <select name="" id="" class="form-control mb-2">
                            <option value="">{{$productDetails->type_of_paper_use}}</option>
                        </select>
                        <div class="print_paper">
                            <form id="submitForm" method="post">
                                @csrf
                                {{-- <input type="hidden" id="product_price"> --}}
                                <input type="hidden" id="product_price" value="{{$productDetails->product_price}}">
                                <input type="hidden" id="product_image" value="{{ $productDetails->product_image }}">
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
                                    Category: <a href="{{ route('bulkprints') }}">BULKPRINTS</a>
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
                {{-- <h2>Description</h2> --}}
               <p class="woocommerce-tabs-desc">{!! $productDetails->product_description ?? '' !!}</p>
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
                    @foreach ($related_products as $item)
                   
                    <div>
                        <div class="sets">
                            <a href="{{ route('bulkprints-product',['slug'=>$item->slug]) }}">
                            <div class="products-img">
                                    <img src="{{ asset($item->product_image) ?? ''}}" alt="">
                                {{-- <div class="onsale">
                                    <span>Sale!</span>
                                </div> --}}
                            </div>
                            <div class="text-slider">
                                <h3>{{$item->product_title}}</h3>
                                <p>${{$item->product_price}}</p>
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

    $(".product-img").on('click',function(){
        $("#modal-img").attr('src',$(this).children('img').attr('src'));
        $("#ImgViewer").modal('show');
    });

    $("#modal-close").on('click',function(){
        $("#ImgViewer").modal('hide');
    })

    $('#product_qty').on('input', function() {
        if ($(this).val().length > 4) {
            $(this).val($(this).val().slice(0, 4));
        }
    });
    
    var productId = "{{$productDetails->id}}";

    $('#addToCartBtn').click(function() {
        addToCartFn();
       
    });

    $('#product_qty').keypress(function(e) {
        if (e.which === 13) { // 13 is the Enter key code
            addToCartFn(); // Call the form submission function
            return false; // Prevent default behavior of the Enter key
        }
    });
});

function addToCartFn(){
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

        var cartItems = [];
        let total = 0;
        let selectedImages = [];

        if (quantity !== '' && quantity > 0) {
            var price = parseFloat(product_price);
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
                    card_price:product_price,
                    item_type:'shop',
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.error == true){
                       $('#product_qty_error').text(response.message);
                    }else {
                        location.href = "{{ route('cart') }}";
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error adding items to cart:', error);
                }
            });
        } else {
            alert('No items to add to cart!');
        }
        }
}



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
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
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
