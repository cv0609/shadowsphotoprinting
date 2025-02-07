@extends('front-end.layout.main')
@section('content')

<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{url('home')}}">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="{{url('shop')}}">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span>Gift Card</span>
            </div>
        </div>
    </div>
</div>


<section class="recipients">
    <div class="container">
        <div class="recipient-box">
            <div class="row">
                <div class="col-lg-6">
                    <div class="gift-img">
                        <img src="{{ asset($blog_detail->product_image) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="summary">
                        <div class="title-cat">
                            <p>Gift Card</p>
                            <h2>{{$blog_detail->product_title}}</h2>

                            <div class="afterpay-4-payment">
                                <span>or 4 payments as low as $25 with </span>
                                <div class="after-pay-modal afterpayButton">
                                <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" width="98" height="36" class="compact-badge-logo" viewBox="0 0 100 21">
                                    <path class="afterpay-logo-badge-background" fill="#b2fce4" d="M89.85 20.92h-78.9a10.42 10.42 0 110-20.82h78.89a10.42 10.42 0 010 20.83v-.01z"></path>
                                    <g class="afterpay-logo-badge-lockup">
                                      <path d="M85.05 6.37L82.88 5.1l-2.2-1.27a2.2 2.2 0 00-3.3 1.9v.29c0 .16.08.3.22.38l1.03.58c.28.16.63-.04.63-.37v-.67c0-.34.36-.54.65-.38l2.02 1.16 2 1.15c.3.16.3.58 0 .75l-2 1.15-2.02 1.16a.43.43 0 01-.65-.38v-.33a2.2 2.2 0 00-3.28-1.9l-2.2 1.26-2.19 1.25a2.2 2.2 0 000 3.8l2.18 1.25 2.2 1.27a2.2 2.2 0 003.3-1.9v-.3c0-.15-.09-.3-.23-.37L78.02 14a.43.43 0 00-.64.37v.67c0 .34-.36.54-.65.38l-2-1.16-2-1.15a.43.43 0 010-.75l2-1.15 2-1.16c.3-.16.65.05.65.38v.33a2.2 2.2 0 003.3 1.9l2.2-1.26 2.17-1.25a2.2 2.2 0 000-3.8z"></path>
                                      <path d="M70.77 6.78l-5.1 10.53h-2.12l1.91-3.93-3-6.6h2.17l1.93 4.42 2.1-4.42h2.11z"></path>
                                      <path d="M19.8 10.5c0-1.24-.92-2.12-2.04-2.12s-2.03.9-2.03 2.14c0 1.23.91 2.14 2.03 2.14s2.03-.88 2.03-2.14m.02 3.74v-.97a3 3 0 01-2.36 1.09c-2.05 0-3.6-1.65-3.6-3.86 0-2.2 1.61-3.87 3.65-3.87.95 0 1.76.42 2.31 1.08v-.95h1.84v7.48h-1.84z"></path>
                                      <path d="M30.6 12.6c-.65 0-.84-.24-.84-.87V8.4h1.2V6.78h-1.2V4.96h-1.88v1.82h-2.43v-.74c0-.63.24-.87.9-.87h.42V3.72h-.9c-1.56 0-2.3.5-2.3 2.07v1h-1.04V8.4h1.04v5.85h1.88V8.4h2.43v3.66c0 1.53.6 2.19 2.11 2.19h.97V12.6h-.37z"></path>
                                      <path d="M37.35 9.85c-.13-.97-.93-1.56-1.86-1.56-.92 0-1.7.57-1.88 1.56h3.74zM33.6 11c.13 1.1.93 1.74 1.93 1.74.8 0 1.4-.37 1.76-.97h1.94c-.45 1.58-1.87 2.6-3.74 2.6a3.68 3.68 0 01-3.85-3.85 3.78 3.78 0 013.9-3.9 3.74 3.74 0 013.8 4.38H33.6z"></path>
                                      <path d="M51.35 10.5c0-1.2-.9-2.12-2.03-2.12-1.12 0-2.03.9-2.03 2.14 0 1.23.9 2.14 2.03 2.14 1.12 0 2.03-.93 2.03-2.14m-5.92 6.79V6.78h1.84v.97a2.97 2.97 0 012.36-1.1c2.02 0 3.6 1.65 3.6 3.85s-1.6 3.87-3.65 3.87a2.9 2.9 0 01-2.26-1v3.93h-1.9.01z"></path>
                                      <path d="M59.86 10.5c0-1.24-.9-2.12-2.03-2.12-1.12 0-2.04.9-2.04 2.14 0 1.23.92 2.14 2.04 2.14s2.03-.88 2.03-2.14m.02 3.74v-.97a3 3 0 01-2.36 1.09c-2.05 0-3.6-1.65-3.6-3.86 0-2.2 1.61-3.87 3.64-3.87.96 0 1.76.42 2.32 1.08v-.95h1.84v7.48h-1.84z"></path>
                                      <path d="M42.11 7.5s.47-.86 1.62-.86c.5 0 .8.17.8.17v1.9s-.69-.42-1.32-.33c-.64.09-1.04.67-1.04 1.45v4.42h-1.9V6.78h1.84v.73z"></path>
                                    </g>
                                </svg>
                                </div>
                            </div>
                        

                            <form id="submitForm" method="post">
                                <div class="quantity">
                                    <input type="hidden" id="giftcard_id" value="{{$blog_detail->id}}">
                                    <input type="hidden" id="giftcard_image" value="{{$blog_detail->product_image}}">
                                    <div class="mehala">
                                        <label class="wglabel">Enter Gift Card Price </label>
                                        <input type="number" id="card_price" value="100" class="input-text">
                                        <span class="error-message" id="card_price_error"></span>
                                    </div>
                                    <div class="mehala">
                                        <label class="wglabel">FROM </label>
                                        <input type="text" id="from" placeholder="Enter the sender name" class="input-text">
                                        <span class="error-message" id="from_error"></span>
                                    </div>
                                    <div class="mehala">
                                        <label class="wglabel">Gift Message </label>
                                        <textarea id="giftcard_msg" cols="30" rows="2" class="input-text"></textarea>
                                        <span class="error-message" id="giftcard_msg_error"></span>
                                        <p><span class="message-length">Characters: ( <span
                                                class="wbox-char">1</span>/300)</span></p>
                                    </div>
                                    <div class="delivery-method">
                                        <label class="">Delivery Method</label>
                                        <div class="recipient">
                                            <input type="radio" name="radio" checked="checked" id="">
                                            <span class="">Mail To Recipient</span>
                                            <div class="delivery-email">
                                                <input type="text" name="mail" id="reciept_email"
                                                    placeholder="Enter the Recipient Email" class="input-text">
                                                <span class="error-message" id="reciept_email_error"></span>
                                                <p><span class="msg-info">We will send it to the
                                                    recipient's email address</span></p>
                                            </div>
                                        </div>
                                        <span class="preview-email">
                                            <a id="giftcard-preview">PREVIEW</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="quanti">
                                    <input type="number" name="quantity" id="quantity" value="1" class="input-text">
                                    <button type="button" id="addToCartBtn">Add to cart</button>
                                </div>
                                <h6><span class="error-message" id="quantity_error"></span></h6>
                            </form>

                            <div class="product_meta">
                                <span class="posted_in">
                                    Category: <a href="{{route('gift-card')}}">Gift Card</a>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="related-product">
    <div class="container">
        <div class="related-wrapper">
            <h3>Related Products</h3>
            <div class="margin-small">
                <ul class="isotope-intrinsic">
                    @foreach ($related_products as $related_product)
                    <li class="type-product">
                        <div class="clearfix ">
                            <a href="{{ route('gift-card-detail',['slug'=>$related_product->slug]) }}">
                                <div class="noflipper">
                                    <div class="product-animations">
                                        <img src="{{ asset($related_product->product_image) }}" alt="">
                                    </div>
                                </div>
                            </a>
                            <div class="details-product-item">
                                <div class="product_details-card">
                                    <a href="{{ route('gift-card-detail',['slug'=>$related_product->slug]) }}">
                                        <h3>{{ $related_product->product_title }}</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>
</section>

@endsection


@section('scripts')

<script>

$(document).ready(function() {

    $('#quantity').on('input', function() {
        if ($(this).val().length > 4) {
            $(this).val($(this).val().slice(0, 4));
        }
    });

    $('#addToCartBtn').click(function() {
      
        var isValid = true;
        $('.error-message').css('color','red');
        $('.error-message').closest('input').addClass('validator');
        $('.error-message').text(''); // Clear previous error messages
        $('.error-message').css('color', 'red').each(function() {
            $(this).siblings('input').addClass('validator');
        });

        var card_price = $('#card_price').val();
        var from = $('#from').val();
        var giftcard_msg = $('#giftcard_msg').val();
        var reciept_email = $('#reciept_email').val();
        var quantity = $('#quantity').val();
        var giftcard_id = $('#giftcard_id').val();
        var giftcard_image = $('#giftcard_image').val();
        
        if (card_price == '') {
            $('#card_price_error').text('This field is required');
            isValid = false;
        }

        if (card_price <= 1) {
            $('#card_price_error').text('Please enter minimum card price 1');
            isValid = false;
        }

        if (from == '') {
            $('#from_error').text('This field is required');
            isValid = false;
        }
        if (giftcard_msg == '') {
            $('#giftcard_msg_error').text('This field is required');
            isValid = false;
        }

        if (giftcard_msg.length > 300) {
            $('#giftcard_msg_error').text('Message must be 300 characters or less');
            isValid = false;
        }

        if (reciept_email == '') {
            $('#reciept_email_error').text('This field is required');
            isValid = false;
        }
        if (quantity == '') {
            $('#quantity_error').text('This field is required');
            isValid = false;
        }

        if (isValid) {

        let cartItems = [];
        let total = 0;
        let selectedImages = [];

        if (quantity !== '' && quantity > 0) {
            let price = parseFloat(card_price);
            let productId = giftcard_id; 
            let totalPrice = quantity * price;
            total += totalPrice;
            cartItems.push({
                product_id: productId,
                quantity: parseFloat(quantity),
                price: price
            });
        }

        selectedImages.push(giftcard_image);

        if (cartItems.length > 0) {
            // Send cart items to the server
            $.ajax({
                url: "{{ route('add-to-cart') }}", // Replace with your route
                method: 'POST',
                data: {
                    cart_items: cartItems,
                    total: total,
                    selectedImages:selectedImages,
                    from:from,
                    giftcard_msg:giftcard_msg,
                    reciept_email:reciept_email,
                    item_type:'gift_card',
                    card_price:card_price,
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
    // });
        }
    });

});

</script>

@endsection