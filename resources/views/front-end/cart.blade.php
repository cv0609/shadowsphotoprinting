@extends('front-end.layout.main')
@section('content')
<section class="coupon-main">
    <div class="container">
        <div class="coupon-inner">
            <div class="coupon-wrapper">
                <p> Coupon code applied successfully </p>
            </div>
            <div class="entry-content">
                <div class="kt-woo-cart-form-wrap">
                    <div class="row">
                        <div class="col-lg-8">
                            <form action="#" class="intero">
                                <div class="cart-summary">
                                    <h2>Cart Summary</h2>
                                </div>
                                <table cellspacing="0">
                                    <thead>
                                        <th>
                                            <tr>

                                                <th colspan="3" class="product-name">Product</th>
                                                <th class="product-price">Price</th>
                                                <th class="product-quantity">Quantity</th>
                                                <th class="product-subtotal">Subtotal</th>
                                            </tr>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="product-remove">
                                                <a href="">×</a>
                                            </td>
                                            <td class="product-thumbnail">
                                                <a href="#"><img src="images/cart-img.jfif" alt=""></a>
                                            </td>
                                            <td class="product-name">
                                                <a href="">5”x5” Prints</a>
                                            </td>
                                            <td class="product-price">
                                                <span class=""><bdi><span class="">$</span>0.55</bdi></span>
                                            </td>
                                            <td class="product-quantity">
                                                <input type="number" name="" id="" placeholder="0">
                                            </td>
                                            <td class="product-subtotal">
                                                <span><bdi><span>$</span>0.55</bdi></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="actions">
                                                <div class="coupon-icons">
                                                    <input type="text" name="coupon_code" class="input-text"
                                                        id="coupon_code" value="" placeholder="Coupon code">
                                                    <button type="submit" class="button" name="apply_coupon"
                                                        value="Apply coupon">Apply coupon</button>
                                                </div>
                                                <button type="submit " class="button satay" name="update_cart"
                                                    value="Update cart">Update cart</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart-collaterals">
                                <div class="cart_totals ">
                                    <h2>Cart totals</h2>
                                </div>
                                <table cellspacing="0">
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Subtotal</th>
                                            <td data-title="Subtotal"><span><bdi><span>$</span>0.55</bdi></span>
                                            </td>
                                        </tr>
                                        <tr class="cart-discount coupon-eofy-discount">
                                            <th>Coupon: eofy discount</th>
                                            <td data-title="Coupon: eofy discount">-<span
                                                    class="woocommerce-Price-amount amount"><span
                                                        class="woocommerce-Price-currencySymbol">$</span>0.27</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>
                                                <p class="woocommerce-shipping-destination">
                                                    Shipping options will be updated during checkout. </p>
                                                <a href="#" class="calculat-shipping">Calculate shipping</a>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td data-title="Total">
                                                <strong><span><bdi><span>$</span>0.28</bdi></span></strong>
                                                <small class="includes_tax">(includes
                                                    <span><span>$</span>0.03</span>
                                                    GST)</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="wc-proceed-to-checkout">
                                    <a href="" class="checkout-button button alt wc-forward">
                                        Proceed to checkout</a>
                                </div>
                                <div class="shopping_btn_cstm"> <a href="#" class="shop_cont_button">Continue
                                        Shopping →</a></div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
@endsection

