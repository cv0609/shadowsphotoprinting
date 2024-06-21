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
