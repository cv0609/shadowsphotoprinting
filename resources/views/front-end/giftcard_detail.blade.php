@extends('front-end.layout.main')
@section('content')

<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="index.html">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="order-prints.html">Order prints</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="order-prints.html">images</a></span>
                <span class="bc-delimiter">»</span>
                <span> GIFT CARD</span>
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
                        <img src="{{ asset($blog_detail->image) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="summary">
                        <div class="title-cat">
                            <p>Gift Card</p>
                            <h2>Gift Card</h2>
                            <form id="submitForm" method="post">
                                <div class="quantity">
                                    <input type="hidden" id="giftcard_id" value="{{$blog_detail->id}}">
                                    <input type="hidden" id="giftcard_image" value="{{$blog_detail->image}}">
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
                                            <a id="">PREVIEW</a>
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
                                    Category: <a href="giftcard.html">Gift Card</a>
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
                                        <img src="{{ asset($related_product->image) }}" alt="">
                                    </div>
                                </div>
                            </a>
                            <div class="details-product-item">
                                <div class="product_details-card">
                                    <a href="{{ route('gift-card-detail',['slug'=>$related_product->slug]) }}">
                                        <h3>{{ $related_product->name }}</h3>
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