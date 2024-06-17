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
                        @if(Request::segment('3') == 'gift-card')
                        <img src="{{ asset('assets/images/cardgift.jpg') }}" alt="">
                        @elseif (Request::segment('3') == 'birthday-gift-card')
                        <img src="{{ asset('assets/images/AdobeStock.jpeg') }}" alt="">
                        @elseif (Request::segment('3') == 'mothers-day-gift-card')
                        <img src="{{ asset('assets/images/Mothers-Day-Gfit.jpg') }}" alt="">
                        @endif

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="summary">
                        <div class="title-cat">
                            <p>Gift Card</p>
                            <h2>Gift Card</h2>
                            <form action="" method="post">
                                <div class="quantity">
                                    <div class="mehala">
                                        <label class="wglabel">Enter Gift Card Price </label>
                                        <input type="text" name="" id="" value="100">
                                    </div>
                                    <div class="mehala">
                                        <label class="wglabel">FROM </label>
                                        <input type="text" name="" id="" placeholder="Enter the sender name">
                                    </div>
                                    <div class="mehala">
                                        <label class="wglabel">Gift Message </label>
                                        <textarea name="" id="" cols="30" rows="2"></textarea>
                                        <span class="message-length">Characters: ( <span
                                                class="wbox-char">1</span>/300)</span>
                                    </div>
                                    <div class="delivery-method">
                                        <label class="">Delivery Method</label>
                                        <div class="recipient">
                                            <input type="radio" name="radio" checked="checked" id="">
                                            <span class="">Mail To Recipient</span>
                                            <div class="delivery-email">
                                                <input type="text" name="mail"
                                                    placeholder="Enter the Recipient Email">
                                                <span class="msg-info">We will send it to the
                                                    recipient's email address</span>
                                            </div>
                                        </div>
                                        <span class="preview-email">
                                            <a id="">PREVIEW</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="quanti">
                                    <input type="number" name="quantity" value="1">
                                    <button type="button">Add to cart</button>
                                </div>
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
                    <li class="type-product">
                        <div class="clearfix ">
                            <a href="{{ url('/our-products/gift-card/mothers-day-gift-card') }}">
                                <div class="noflipper">
                                    <div class="product-animations">
                                        <img src="{{ asset('assets/images/Mothers-Day-Gfit.jpg') }}" alt="">
                                    </div>
                                </div>
                            </a>
                            <div class="details-product-item">
                                <div class="product_details-card">
                                    <a href="{{ url('/our-products/gift-card/mothers-day-gift-card') }}">
                                        <h3>Mothers Day Gift Card</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="type-product">
                        <div class="clearfix ">
                            <a href="{{ url('/our-products/gift-card/birthday-gift-card') }}">
                                <div class="noflipper ">
                                    <div class="product-animations">
                                        <img src="{{ asset('assets/images/AdobeStock.jpeg') }}" alt="">
                                    </div>
                                </div>
                            </a>
                            <div class="details-product-item">
                                <div class="product_details-card">
                                    <a href="{{ url('/our-products/gift-card/birthday-gift-card') }}">
                                        <h3>Birthday Gift Card</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
