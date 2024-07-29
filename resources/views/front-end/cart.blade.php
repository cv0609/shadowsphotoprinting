@extends('front-end.layout.main')
@section('content')
@php
   $CartService = app(App\Services\CartService::class);

@endphp
<section class="coupon-main">
<div class="container">
<div class="coupon-inner">
    @if(Session::has('success'))
        <div class="coupon-wrapper">
            <p class="text-center">{{Session::get('success')}}</p>
        </div>
    @endif

    <div class="coupon-wrapper d-none" id="qty-validation">
        <p class="text-center"></p>
    </div>

    <div class="entry-content">
        <div class="kt-woo-cart-form-wrap">
            <div class="row">
                <div class="col-lg-8">

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

                            <?php
                             $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);
                             $product_sale_price =  $CartService->getProductSalePrice($item->product_id);
                            ?>
                                <tr>
                                <td class="product-remove">
                                    <a href="{{ route('remove-from-cart',['product_id'=>$item->id]) }}" onclick="return confirm('Are you sure!')">×</a>
                                </td>
                                <td class="product-thumbnail">

                                    @php
                                        $image1 = '';
                                        $image2 = '';
                                        if(isset($product_detail->product_image)){
                                            $imageArray = explode(',', $product_detail->product_image);
                                            $image1 = $imageArray[0] ?? '';
                                            $image2 = $imageArray[1] ?? '';
                                        }
                                   @endphp


                                    <a href="#">
                                        <img src="
                                            @if($item->product_type == 'gift_card')
                                                {{ asset($product_detail->product_image) }}
                                            @elseif($item->product_type == 'photo_for_sale')
                                               
                                                {{ asset($image1) }}

                                            @elseif($item->product_type == 'hand_craft')

                                                 {{ asset($image1) }}

                                            @else
                                                {{ asset($item->selected_images) }}
                                            @endif
                                        " alt="">
                                    </a>
                                </td>
                                <td class="product-name">
                                    @php
                                        $photo_product_desc = '';
                                        $giftcard_product_desc = '';

                                        if($item->product_type == "photo_for_sale"){
                                            $photo_product_desc = json_decode($item->product_desc);
                                        }
                                        if($item->product_type == "gift_card"){
                                            $giftcard_product_desc = json_decode($item->product_desc);
                                        }

                                    @endphp
                                    <a href="#">
                                        @if($item->product_type == "gift_card")
                                            {{ $product_detail->product_title }}
                                            <p class="giftcard-message"><span class="gift-desc-heading">To: </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span class="gift-desc-heading"> From: </span><span> {{$giftcard_product_desc->from ?? ''}}</span><span class="gift-desc-heading"> Message: </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span></p>
                                        @elseif($item->product_type == "photo_for_sale")
                                            {{ $product_detail->product_title ?? '' }} - {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}

                                        @elseif($item->product_type == "hand_craft")

                                          {{ $product_detail->product_title ?? '' }} 

                                        @else
                                            {{ $item->product->product_title ?? ''}}
                                        @endif
                                    </a>

                                </td>
                                <td class="product-price">
                                    <span class="">
                                        <bdi>
                                            <span>$</span>
                                            @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                                                {{ number_format($item->product_price, 2) }}
                                            @else
                                                {{ isset($product_sale_price) && !empty($product_sale_price) ? number_format($product_sale_price, 2) : number_format($product_detail->product_price, 2)  }}
                                            @endif
                                        </bdi>
                                    </span>
                                </td>
                                <td class="product-quantity">
                                    <input type="number" name="product_quantity[]" id="product_quantity" placeholder="0" value="{{ $item->quantity }}" data-row="{{ $item->id }}">
                                </td>
                                <td class="product-subtotal">
                                    <span>
                                        <bdi>
                                            <span>$</span>
                                            @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                                                {{ number_format($item->quantity * $item->product_price, 2) }}
                                            @else
                                                {{ isset($product_sale_price) && !empty($product_sale_price) ? number_format($item->quantity * $product_sale_price, 2) : number_format($item->quantity * $product_detail->product_price, 2) }}
                                            @endif
                                        </bdi>
                                    </span>
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <td colspan="6" class="actions">
                                    
                                    @if(!Session::has('coupon'))
                                        <div class="coupon-icons">
                                            <input type="text" name="coupon_code" class="input-text"
                                                id="coupon_code" value="" placeholder="Coupon code">
                                            <button type="button" class="button" id="apply_coupon">Apply coupon</button>
                                            <span class="text-danger coupon-errors"></span>
                                        </div>
                                    @endif

                                    <button type="button" class="button satay3" name="update_cart"
                                        value="Update cart" id="update_cart">Update cart</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

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
                                <td data-title="Subtotal"><span><bdi><span>$</span>{{ number_format($CartTotal['subtotal'],2) }}</bdi></span>
                                </td>
                            </tr>
                            @if(Session::has('coupon'))
                            <tr class="cart-discount coupon-eofy-discount">
                                <th>Coupon: {{ $CartTotal['coupon_code']['code'] }} discount</th>
                                <td data-title="Coupon: {{ $CartTotal['coupon_code']['code'] }} discount">-<span
                                        class="woocommerce-Price-amount amount"><span
                                            class="woocommerce-Price-currencySymbol">$</span>
                                            {{ number_format($CartTotal['coupon_code']['discount_amount'],2) }}<a class="reset-coupon" href="{{ route('reset-coupon') }}" onclick="return confirm('Are you sure!')">×</a></span>
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
                                            Shipping to <strong>{{ Session::get('billing_details')['city'].' '. Session::get('billing_details')['state']['name'].' '.Session::get('billing_details')['postcode']}}</strong>. </p>
                                        </p>

                                        <form action="{{ route('billing-details') }}" class="change-calculate-form" method="POST">
                                            @csrf
                                            <a class="change-address calculat-shipping" id="change-address">Change
                                                address</a>
                                                <div class="calculate-shipping">
                                                    <select class="form-control" id="country" name="country" >
                                                        <option  selected >{{ $countries->name }}</option>

                                                    </select>

                                                    <select class="form-control" id="state" name="state" required>
                                                        <option value="">State</option>
                                                        @foreach ($countries->states as $state)
                                                          <option value="{{ $state->id }} "  <?= ($state->id ==  Session::get('billing_details')['state_id'] ) ? 'selected' : '' ?>>{{ $state->name }}</option>
                                                        @endforeach

                                                    </select>
                                                    <p class="form-row">
                                                        <input type="text" name="city" placeholder="city" value="{{ Session::get('billing_details')['city'] }}" required>
                                                    </p>
                                                    <p class="form-row">
                                                        <input type="text" name="postcode"
                                                            placeholder="postcode/ ZIP" value="{{ Session::get('billing_details')['postcode'] }}" required>
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
                                                    <option value="{{ $countries->id }}" selected>{{ $countries->name }}</option>

                                                </select>
                                                <select class="form-control" id="state" name="state" required>
                                                    <option value="">State</option>
                                                        @foreach ($countries->states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>

                                                        @endforeach
                                               
                                                </select>
                                                <p class="form-row">
                                                    <input type="text" name="city" placeholder="city" required>
                                                </p>
                                                <p class="form-row">
                                                    <input type="text" name="postcode"
                                                        placeholder="postcode/ ZIP" required>
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
                                        <strong><span><bdi><span>$</span>{{ number_format($CartTotal['total'],2) }}</bdi></span></strong>
                                        {{-- <small class="includes_tax">(includes
                                            <span><span>$</span>0.03</span>
                                            GST)</small> --}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="wc-proceed-to-checkout">
                            <a href="{{ route('checkout') }}" class="checkout-button button alt wc-forward">
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
        $(".coupon-errors").html('');
        if(!$("#coupon_code").val())
          {
            $("#coupon_code").addClass('validator');
          }
          else
          {
             var couponCode = $("#coupon_code").val();
             console.log(couponCode);
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

<script>
   $("#update_cart").on('click',function(){
    $('#qty-validation').addClass('d-none');
      var data = [];
        $("input[name='product_quantity[]']").each(function(i,v) {
            if($(v).val() > 0)
             {
                data.push({'quantity':$(v).val(),'rowId':$(v).data('row')})

             }
             else
              {
                 $(this).addClass('validator');
                 $('#qty-validation').removeClass('d-none');
                 $('#qty-validation p').text('Please enter quantity equal to or more then 1.');
                 return false;
              }

        });
        $.post("{{ route('update-cart') }}",
        {
            data: data,
            "_token": "{{ csrf_token() }}"
        },
        function(data, status){
            location.reload();
        });
   })
</script>

@endsection

