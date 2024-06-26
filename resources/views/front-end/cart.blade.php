@extends('front-end.layout.main')
@section('content')
{{-- @php
echo"<pre>";
    print_r(Session::get('billing_details'));
    die;
@endphp --}}
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
                            @foreach ($cart->items as $item)
                                <tr>
                                <td class="product-remove">
                                    <a href="{{ route('remove-from-cart',['product_id'=>$item->product_id]) }}" onclick="return confirm('Are you sure!')">×</a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="#"><img src="{{ asset($item->selected_images) }}" alt=""></a>
                                </td>
                                <td class="product-name">
                                    <a href="">{{ $item->product->product_title }}</a>
                                </td>
                                <td class="product-price">
                                    <span class=""><bdi><span class="">$</span>{{ number_format($item->product->product_price,2) }}</bdi></span>
                                </td>
                                <td class="product-quantity">
                                    <input type="number" name="" id="" placeholder="0" value="{{ $item->quantity }}">
                                </td>
                                <td class="product-subtotal">
                                    <span><bdi><span>$</span>{{ number_format($item->quantity * $item->product->product_price,2) }}</bdi></span>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if(!Session::has('coupon'))
                            <tr>
                                <td colspan="6" class="actions">
                                    <div class="coupon-icons">
                                        <input type="text" name="coupon_code" class="input-text"
                                            id="coupon_code" value="" placeholder="Coupon code">
                                        <button type="button" class="button" id="apply_coupon">Apply coupon</button>
                                        <span class="text-danger coupon-errors"></span>
                                    </div>
                                    <button type="submit " class="button satay" name="update_cart"
                                        value="Update cart">Update cart</button>
                                </td>
                            </tr>
                            @endif
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
                                <td data-title="Subtotal"><span><bdi><span>$</span>{{ number_format($total,2) }}</bdi></span>
                                </td>
                            </tr>
                            @if(Session::has('coupon'))
                            <tr class="cart-discount coupon-eofy-discount">
                                <th>Coupon: {{ Session::get('coupon')['code'] }} discount</th>
                                <td data-title="Coupon: {{ Session::get('coupon')['code'] }} discount">-<span
                                        class="woocommerce-Price-amount amount"><span
                                            class="woocommerce-Price-currencySymbol">$</span>
                                            {{ number_format(Session::get('coupon')['discount_amount'],2) }}</span>
                                </td>
                            </tr>
                            @endif
                            @if($shipping->status == "1")
                            <tr>
                                <th>Shipping</th>
                                <td>

                                    @if(Session::has('billing_details'))
                                        <span class="flat-rate"> Flat rate: ${{ number_format($shipping->amount,2) }}</span>
                                        <p>
                                        <p class="">    
                                            Shipping to <strong>{{ Session::get('billing_details')['city'].' '. Session::get('billing_details')['state'].' '.Session::get('billing_details')['postcode']}}</strong>. </p>
                                        </p>

                                        <form action="{{ route('billing-details') }}" class="change-calculate-form" method="POST">
                                            @csrf
                                            <a class="change-address calculat-shipping" id="change-address">Change
                                                address</a>
                                                <div class="calculate-shipping">
                                                    <select class="form-control" id="country" name="country" >
                                                        <option  selected >{{ $countries->name }}</option>
                                                                                        
                                                    </select>	
                                                    <select class="form-control" id="state" name="state" >
                                                        <option value="">State</option>
                                                        @foreach ($countries->states as $state)
                                                          <option value="{{ $state->id }} "  <?= ($state->id ==  Session::get('billing_details')['state_id'] ) ? 'selected' : '' ?>>{{ $state->name }}</option>
                                                        @endforeach
                                                                                  
                                                    </select>	
                                                    <p class="form-row">
                                                        <input type="text" name="city" placeholder="city" value="{{ Session::get('billing_details')['city'] }}">
                                                    </p>
                                                    <p class="form-row">
                                                        <input type="text" name="postcode"
                                                            placeholder="postcode/ ZIP" value="{{ Session::get('billing_details')['postcode'] }}">
                                                    </p>
                                                    <p class="form-row">
                                                        <button type="submit"
                                                            class="update-btn">Update</button>
                                                    </p>
                                                </div>
                                           
                                        </form>
                                        @endif

                                        @if(!Session::has('billing_details'))
                                        <p class="woocommerce-shipping-destination">
                                            Shipping options will be updated during checkout. </p>

                                        <form action="{{ route('billing-details') }}" class="change-calculate-form" method="POST">
                                            @csrf
                                            <a class="calculat-shipping"
                                                id="calculat-shipping">Calculate shipping</a>
                                            <div class="calculate-shipping">
                                                <select class="form-control" id="country" name="country" >
                                                    <option value="saab" selected>{{ $countries->name }}</option>
                                                                                    
                                                </select>	
                                                <select class="form-control" id="state" name="state" >
                                                    <option value="volvo">State</option>
                                                        @foreach ($countries->states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            
                                                        @endforeach
                                                option>                               
                                                </select>	
                                                <p class="form-row">
                                                    <input type="text" name="city" placeholder="city">
                                                </p>
                                                <p class="form-row">
                                                    <input type="text" name="postcode"
                                                        placeholder="postcode/ ZIP">
                                                </p>
                                                <p class="form-row">
                                                    <button type="submit"
                                                        class="update-btn">Update</button>
                                                </p>
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endif
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
                        <div class="shopping_btn_cstm"> <a href="{{ url('shop') }}" class="shop_cont_button">Continue
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

@section('scripts')
  <script>
     $("#apply_coupon").on('click',function(){
        $("#coupon_code").removeClass('validator');

        if(!$("#coupon_code").val())
          {
            $("#coupon_code").addClass('validator');
          }
          else
          {
             var couponCode = $("#coupon_code").val();
             $.post("{{ route('apply-coupon') }}",
                {
                    coupon_code: couponCode,
                    "_token": "{{ csrf_token() }}"
                },
                function(res){
                    if(res.success === false)
                      {
                        $("#coupon_code").addClass('validator');
                        $(".coupon-errors").html(res.message);
                      }
                      else
                      {
                        location.reload();
                      }
                });
           }
     })
  </script>

<script>

    $(document).ready(function () {
        $(".calculat-shipping").click(function () {
            $(".calculate-shipping").slideToggle();
        });
    });

</script>
<script>    
    $(document).ready(function() {
        $('#country').select2({
            placeholder: 'Select products',
            allowClear: true
        });
		   $('#state').select2({
            placeholder: 'Select products',
            allowClear: true
        });

    });
</script>

@endsection

