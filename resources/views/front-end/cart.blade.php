@extends('front-end.layout.main')
@section('content')
@php
$CartService = app(App\Services\CartService::class);
// dd($CartTotal);
// function getS3Img($str, $size){
// $str = str_replace('original', $size, $str);
// return $str;
// }
@endphp

<!-- Package Restriction Modal -->
<div class="modal fade" id="packageRestrictionModal" tabindex="-1" role="dialog" aria-labelledby="packageRestrictionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 0;">
            <div class="modal-header" style="background-color: #f8f9fa; border-radius: 0;">
                <h5 class="modal-title text-dark" id="packageRestrictionModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Package Restriction Violation
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #dc3545; font-size: 1.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #ffffff; padding: 20px;">
                <div class="alert alert-info" style="border-radius: 0; border-left: 4px solid #17a2b8;">
                    <i class="fas fa-info-circle"></i> You cannot update these quantities because they exceed the package restrictions.
                </div>
                <div id="restriction-message"></div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-radius: 0;">
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius: 0; padding: 8px 20px;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

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
                            @php
                                // Check if there are any non-package items in the cart
                                $has_non_package_items = $cart->items->where('is_package', '!=', 1)->count() > 0;
                                $has_package_items = $cart->items->where('is_package', 1)->count() > 0;
                            @endphp
                            <table cellspacing="0">
                                <thead>
                                    <tr @if($has_package_items)style="text-align: center;" @endif>
                                        <th colspan="3" class="product-name" @if($has_package_items)style="text-align: left;" @endif>Product</th>
                                        <th class="product-price">Price @if($has_package_items) / Package Info @endif </th>
                                        <th class="product-quantity">Quantity</th>
                                        @if($has_non_package_items)
                                            <th class="product-subtotal">Subtotal</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $displayed_packages = [];
                                        $previous_package_id = null;
                                    @endphp
                                    @foreach ($cart->items as $item)

                                            <?php
                                    $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);
                                    $product_sale_price =  $CartService->getProductSalePrice($item->product_id);


                                    $is_package = isset($item->is_package) && !empty($item->is_package) && ($item->is_package == 1) ? 1 : 0;

                                    $package_product_price = 0;
                                    $package_name = '';
                                    $package_slug = '';
                                    $is_first_package_item = false;
                                    $show_package_separator = false;

                                    if($is_package == 1){
                                        $package_product_id = $item->package_product_id;
                                        $package = $CartService->getPackageProductDetails($package_product_id);

                                        $package_product_price = $package->product_price;
                                        $package_name = $package->product_title;
                                        $package_slug = $package->slug;
                                        
                                        // Check if this is the first item of this package
                                        if(!in_array($package_product_id, $displayed_packages)){
                                            $is_first_package_item = true;
                                            $displayed_packages[] = $package_product_id;
                                        }
                                        
                                        // Check if we need to show separator (different package than previous)
                                        if($previous_package_id && $previous_package_id != $package_product_id){
                                            $show_package_separator = true;
                                        }
                                        
                                        $previous_package_id = $package_product_id;
                                    } else {
                                        $previous_package_id = null; // Reset when we hit non-package item
                                    }

                                    ?>
                                    
                                    {{-- Package Separator --}}
                                    @if($show_package_separator)
                                        <tr class="package-separator">
                                            <td colspan="6" style="height: 20px; background: #f8f9fa; border-top: 2px solid #e74c3c;"></td>
                                        </tr>
                                    @endif
                                    
                                    <tr data-product-id="{{ $item->product_id }}" 
                                        data-product-type="{{ $item->product_type }}" 
                                        data-is-test-print="{{ $item->is_test_print ?? '0' }}"
                                        @if($is_package == 1) class="package-item" @endif>
                                        <td class="product-remove">
                                            <a href="{{ route('remove-from-cart',['product_id'=>$item->id]) }}"
                                                onclick="return confirm('Are you sure!')">×</a>
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


                                            <a href="javascript:void(0)" class="product-img">
                                                <img src="
                                            @if($item->product_type == 'gift_card')
                                                {{ asset($product_detail->product_image) }}
                                            @elseif($item->product_type == 'photo_for_sale')

                                                {{ asset($image1) }}

                                            @elseif($item->product_type == 'hand_craft')

                                                {{ asset($image1) }}

                                            @else
                                            {{-- @php
                                                dd(addWaterMark(asset($item->selected_images)));
                                            @endphp --}}

                                                 @if($item->is_test_print == '1')
                                                   {{-- {{ addWaterMark($item->selected_images) }} --}}
                                                  
                                                    {{ asset($item->watermark_image) }}
                                                   {{-- {{ asset($item->selected_images) }} --}}
                                                 @else
                                                 {{-- {{ asset($item->selected_images) }} --}}
                                                 {{ getS3Img($item->selected_images, 'raw') }}
                                                 @endif
                                            @endif
                                        " data-src="
                                            @if($item->product_type == 'gift_card')
                                                {{ asset($product_detail->product_image) }}
                                            @elseif($item->product_type == 'photo_for_sale')

                                                {{ asset($image1) }}

                                            @elseif($item->product_type == 'hand_craft')

                                                {{ asset($image1) }}

                                            @else
                                            {{-- @php
                                                dd(addWaterMark(asset($item->selected_images)));
                                            @endphp --}}

                                                 @if($item->is_test_print == '1')
                                                   {{-- {{ addWaterMark($item->selected_images) }} --}}
                                                    {{ asset($item->watermark_image) }}
                                                   {{-- {{ asset($item->selected_images) }} --}}
                                                 @else
                                                 {{-- {{ asset($item->selected_images) }} --}}
                                                 {{ $item->selected_images }}
                                                 @endif
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
                                                <p class="giftcard-message"><span class="gift-desc-heading">To:
                                                    </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span
                                                        class="gift-desc-heading"> From: </span><span>
                                                        {{$giftcard_product_desc->from ?? ''}}</span><span
                                                        class="gift-desc-heading"> Message:
                                                    </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span>
                                                </p>
                                                @elseif($item->product_type == "photo_for_sale")
                                                {{ $product_detail->product_title ?? '' }} -
                                                {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}

                                                @elseif($item->product_type == "hand_craft")

                                                {{ $product_detail->product_title ?? '' }}

                                                @else
                                                {{ $item->product->product_title ?? ''}}
                                                @endif
                                            </a>

                                        </td>
                                        <td class="product-price">
                                            <span class="">

                                            @if($is_package != 1)   

                                                <bdi>
                                                    <span>$</span>
                                                    @if($item->product_type == "gift_card" || $item->product_type ==
                                                    "photo_for_sale" || $item->product_type == "hand_craft")
                                                    {{ number_format($item->product_price, 2) }}
                                                    @else

                                                    @if(isset($item->is_test_print) && ($item->is_test_print == '1'))
                                                    {{ number_format($item->test_print_price, 2) }}
                                                    @else
                                                    {{ isset($product_sale_price) && !empty($product_sale_price) 
                                                    ? number_format($product_sale_price, 2) 
                                                    : number_format($product_detail->product_price, 2)
                                                }}
                                                    @endif

                                                    @endif
                                                </bdi>
                                            @else
                                                @if($is_first_package_item)
                                                    <div class="package-info">
                                                        <bdi>
                                                            <div class="package-name">{{$package_name}}</div>
                                                            <div class="package-price">${{number_format($package_product_price, 2)}}</div>
                                                        </bdi>
                                                    </div>
                                                @else
                                                    <div class="package-item-label">
                                                        <span class="package-item-text">Package Item</span>
                                                    </div>
                                                @endif
                                            @endif
                                            </span>
                                        </td>

                                        <td class="product-quantity">

                                            <input type="number" name="product_quantity[]" id="product_quantity"
                                            placeholder="0" value="{{ $item->quantity }}" data-row="{{ $item->id }}"
                                            data-product_type="{{ $item->product_type }}"
                                            data-product_id="{{ $item->product_id }}"
                                            data-is_test_print="{{ isset($item->is_test_print) && ($item->is_test_print == '1') ? $item->test_print_cat : '' }}"
                                            data-is_package="{{ $is_package }}"
                                            data-package_product_id="{{ $is_package == 1 ? $item->package_product_id : '' }}"
                                            data-category_id="{{ $product_detail->category_id ?? '' }}"
                                            data-package_slug="{{ $package_slug ?? '' }}"
                                            >
                                       
                                        </td>
                                       
                                        @if($has_non_package_items)

                                            <td class="product-subtotal">
                                                @if($is_package != 1)  
                                                    <span>
                                                        <bdi>
                                                            <span>$</span>
                                                            @if($item->product_type == "gift_card" || $item->product_type ==
                                                            "photo_for_sale" || $item->product_type == "hand_craft")
                                                            {{ number_format($item->quantity * $item->product_price, 2) }}
                                                            @else
                                                            @if(isset($item->is_test_print) && ($item->is_test_print == '1'))
                                                            {{ number_format($item->test_print_qty * $item->test_print_price, 2) }}
                                                            @else
                                                            {{ isset($product_sale_price) && !empty($product_sale_price) 
                                                                ? number_format($item->quantity * $product_sale_price, 2) 
                                                                : number_format($item->quantity * $product_detail->product_price, 2)
                                                            }}
                                                            @endif
                                                            @endif
                                                        </bdi>
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="{{ $has_non_package_items ? '6' : '4' }}" class="actions">

                                            @if(!Session::has('coupon'))
                                            <div class="coupon-icons">
                                                <input type="text" name="coupon_code" class="input-text"
                                                    id="coupon_code" value="" placeholder="Coupon code">
                                                <button type="button" class="button" id="apply_coupon">Apply
                                                    coupon</button>
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
                                            <td data-title="Subtotal">
                                                <span><bdi><span>$</span>{{ number_format($CartTotal['subtotal'],2) }}</bdi></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="rad-btns-box">
                                                    @php
                                                        $order_type = 0;
                                                        if(Session::has('order_type')){
                                                            $order_type = Session::get('order_type');
                                                        }
                                                    @endphp
                                                    <div class="rad-btns">
                                                        <input type="radio" id="cart-shipping" name="order_type" class="orderType" value="0" @if($order_type == 0) checked @endif>
                                                        <label for="c-shipping">Shipping</label>
                                                    </div>
                                                    <div class="rad-btns">
                                                       <input type="radio" id="cart-pickup" name="order_type" class="orderType" value="1" @if($order_type == 1) checked @endif>
                                                        <label for="c-pickup">Pickup</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        @if(Auth::check() && !empty(Auth::user()) && Auth::user()->role == 'affiliate' && $affiliate_sales->total_shutter_points >= 500)
                                        <tr class="cart-shutter-point">
                                            <th>Shutter Points</th>
                                            <td data-title="Shutter Points" >
                                                <span class="woocommerce-Price-amount amount"><span
                                                        class="woocommerce-Price-currencySymbol"></span>
                                                    {{ $affiliate_sales->total_shutter_points }} (${{ $affiliate_sales->total_commission }})                                             
                                                </span>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <td colspan="2">
                                                <div class="rad-btns-box">
                                                    @php
                                                        $shutter_point = '0';
                                                        if(Session::has('shutter_point')){
                                                            $shutter_point = Session::get('shutter_point');
                                                        }
                                                    @endphp
                                                    <div class="rad-btns">
                                                        <input type="radio" id="point-use-no" name="shutter_point" class="shutterPoint" value="0" @if($shutter_point == '0') checked @endif>
                                                        <label for="c-shipping">No Points</label>
                                                    </div>
                                                    <div class="rad-btns">
                                                       <input type="radio" id="point-use" name="shutter_point" class="shutterPoint" value="1" @if($shutter_point == '1') checked @endif>
                                                        <label for="c-pickup">Use Points</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif

                                        @if(Session::has('coupon'))
                                        <tr class="cart-discount coupon-eofy-discount">
                                            <th>Coupon: {{ $CartTotal['coupon_code']['code'] }} discount</th>
                                            <td data-title="Coupon: {{ $CartTotal['coupon_code']['code'] }} discount">
                                                -<span class="woocommerce-Price-amount amount"><span
                                                        class="woocommerce-Price-currencySymbol">$</span>
                                                    {{-- @php @endphp --}}

                                                    {{ number_format($CartTotal['coupon_discount'],2) }}
                                                    @if(!session()->has('referral_code'))
                                                        <a class="reset-coupon" href="{{ route('reset-coupon') }}" onclick="return confirm('Are you sure!')">×</a></span>
                                                    @endif                                                
                                                </span>
                                            </td>
                                        </tr>
                                        @endif


                                        @if($shipping->status == "1")
                                        <tr class="shipping-section">
                                            {{-- <th></th> --}}
                                            <td>
                                                <!-- Category-wise Shipping Calculator -->
                                                <div id="category-shipping-options">
                                                    <div class="shipping-loading">Calculating shipping options...</div>
                                                </div>
                                                
                                                <!-- Total Shipping Cost -->
                                                {{-- <div id="total-shipping-cost" style="display: none;">
                                                    <strong>Total Shipping: <span id="total-shipping-amount">${{ number_format($CartTotal['shippingCharge'],2) }}</span></strong>
                                                </div> --}}

                                                {{-- Commented out old flat rate shipping
                                                @if(Session::has('billing_details'))
                                                <span class="flat-rate"> Flat rate:
                                                    ${{ number_format($CartTotal['shippingCharge'],2) }}</span>
                                                <p>
                                                    <p class="">
                                                        Shipping to
                                                        <strong>{{ Session::get('billing_details')['city'].' '. Session::get('billing_details')['state']['name'].' '.Session::get('billing_details')['postcode']}}</strong>.
                                                    </p>
                                                </p>
                                                @endif
                                                --}}

                                                {{-- @if(Session::has('billing_details'))
                                                <p class="shipping-address">
                                                    Shipping to
                                                    <strong>{{ Session::get('billing_details')['city'].' '. Session::get('billing_details')['state']['name'].' '.Session::get('billing_details')['postcode']}}</strong>
                                                </p>

                                                <form action="{{ route('billing-details') }}"
                                                    class="change-calculate-form" method="POST">
                                                    @csrf
                                                    <a class="change-address calculat-shipping"
                                                        id="change-address">Change address</a>
                                                    <div class="calculate-shipping">
                                                        <select class="form-control" id="country" name="country">
                                                            <option selected>{{ $countries->name }}</option>
                                                        </select>

                                                        <select class="form-control" id="state" name="state" required>
                                                            <option value="">State</option>
                                                            @foreach ($countries->states as $state)
                                                            <option value="{{ $state->id }} "
                                                                <?= ($state->id ==  Session::get('billing_details')['state_id'] ) ? 'selected' : '' ?>>
                                                                {{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="form-row">
                                                            <input type="text" name="city" placeholder="city"
                                                                value="{{ Session::get('billing_details')['city'] }}"
                                                                required>
                                                        </p>
                                                        <p class="form-row">
                                                            <input type="text" name="postcode"
                                                                placeholder="postcode/ ZIP"
                                                                value="{{ Session::get('billing_details')['postcode'] }}"
                                                                required>
                                                        </p>
                                                        <p class="form-row">
                                                            <button type="submit" class="update-btn">Update</button>
                                                        </p>
                                                    </div>
                                                </form>
                                                @endif --}}

                                                {{-- @if(!Session::has('billing_details'))
                                                <p class="woocommerce-shipping-destination">
                                                    Shipping options will be updated during checkout. </p>

                                                <form action="{{ route('billing-details') }}"
                                                    class="change-calculate-form" method="POST">
                                                    @csrf
                                                    <a class="calculat-shipping" id="calculat-shipping">Calculate
                                                        shipping</a>
                                                    <div class="calculate-shipping">
                                                        <select class="form-control" id="country" name="country">
                                                            <option value="{{ $countries->id }}" selected>
                                                                {{ $countries->name }}</option>
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
                                                            <button type="submit" class="update-btn">Update</button>
                                                        </p>
                                                    </div>
                                                </form>
                                                @endif --}}
                                            </td>
                                        </tr>
                                        @endif
                                        
                                        <!-- Shipping Cost Row -->
                                        <tr class="shipping-cost-row" id="shipping-cost-row" style="display: none;">
                                            <th>Shipping</th>
                                            <td data-title="Shipping">
                                                <span id="shipping-cost">$0.00</span>
                                            </td>
                                        </tr>
                                        
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td data-title="Total">
                                                
                                                <strong><span class="cart-total" id="cart-total" data-subtotal="{{ $CartTotal['subtotal'] }}"><bdi><span>$</span>{{ number_format($CartTotal['total'],2) }}</bdi></span></strong>
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
                                <div class="shopping_btn_cstm"> <a href="{{ url('shop') }}"
                                        class="shop_cont_button">Continue
                                        Shopping →</a></div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>

<div id="ImgViewer" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal-close">&times;</button>
                {{-- <h4 class="modal-title">Modal Header</h4> --}}
            </div>
            <div class="modal-body">
                <img src="" alt="image" id="modal-img">
            </div>
            {{-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> --}}
        </div>

    </div>
</div>


@endsection

@section('scripts')
<!-- Include shipping calculator styles -->
<link rel="stylesheet" href="{{ asset('css/cart-shipping.css') }}">

<script>
    $("#apply_coupon").on('click', function () {
        $("#coupon_code").removeClass('validator');
        $(".coupon-errors").html('');
        if (!$("#coupon_code").val()) {
            $("#coupon_code").addClass('validator');
        } else {
            var couponCode = $("#coupon_code").val();
            console.log(couponCode);
            $.post("{{ route('apply-coupon') }}", {
                    coupon_code: couponCode,
                    "_token": "{{ csrf_token() }}"
                },
                function (res) {

                    if (res.success === false) {
                        $("#coupon_code").addClass('validator');
                        $(".coupon-errors").html(res.message);
                    } else {
                        location.reload();
                    }
                });
        }
    })
</script>

<!-- Include category-wise shipping calculator script -->
<script src="{{ asset('js/category-shipping.js') }}"></script>

<script>
    $(document).ready(function () {
    
    function updateShippingAndTotal(orderType) {
        var shutterPoint = $("input[name='shutter_point']:checked").val(); // Get selected value

        let baseSubtotal = parseFloat("{{ $CartTotal['subtotal'] }}"); // Base subtotal without shipping
        let currentShippingCost = parseFloat($('#shipping-cost').text().replace('$', '') || 0);

        console.log('=== UPDATE SHIPPING AND TOTAL ===');
        console.log('baseSubtotal', baseSubtotal);
        console.log('currentShippingCost', currentShippingCost);

        @if(Auth::check() && !empty(Auth::user()) &&  Auth::user()->role == 'affiliate')
          let commission = parseFloat("{{$affiliate_sales->total_commission}}");
        @else
          let commission = 0;
        @endif  

        @if(Auth::check() && !empty(Auth::user()) && Auth::user()->role == 'affiliate' && $affiliate_sales->total_shutter_points >= 500)
           if($("input[name='shutter_point']:checked").val() === '1')
           {
            baseSubtotal = baseSubtotal - commission;
           }
        @endif

        if (orderType == 1) { 
            $(".shipping-section").hide(); // Hide shipping section
            $(".cart-total").html(`<bdi><span>$</span>${(baseSubtotal).toFixed(2)}</bdi>`);
            console.log('Pickup selected - total without shipping:', baseSubtotal);
            
            // Sync category shipping calculator with the new total
            if (window.categoryShippingCalculator) {
                window.categoryShippingCalculator.syncWithExternalTotal();
            }
        } else {
            $(".shipping-section").show(); // Show shipping section
            // Use the category shipping calculator's total if available
            if (window.categoryShippingCalculator && window.categoryShippingCalculator.currentTotal) {
                $(".cart-total").html(`<bdi><span>$</span>${window.categoryShippingCalculator.currentTotal.toFixed(2)}</bdi>`);
                console.log('Shipping selected - using category shipping total:', window.categoryShippingCalculator.currentTotal);
            } else {
                $(".cart-total").html(`<bdi><span>$</span>${(baseSubtotal + currentShippingCost).toFixed(2)}</bdi>`);
                console.log('Shipping selected - fallback total with shipping:', baseSubtotal + currentShippingCost);
            }
            
            // Sync category shipping calculator with the new total
            if (window.categoryShippingCalculator) {
                window.categoryShippingCalculator.syncWithExternalTotal();
            }
        }
        console.log('=== END UPDATE SHIPPING AND TOTAL ===');
    }

    let orderType = {{$order_type}}; 
    updateShippingAndTotal(orderType);


    $(".orderType").change(function () {
        let orderType = $("input[name='order_type']:checked").val(); // Get selected value
        
        $.ajax({
            url: "{{route('order-type')}}",  // Your Laravel route
            type: "POST",
            data: {
                order_type: orderType,
                _token: "{{ csrf_token() }}", 
            },
            success: function (response) {
                // If pickup is selected, clear shipping selection
                if (orderType == 1) {
                    // Clear shipping selection from server
                    if (window.categoryShippingCalculator) {
                        // Clear shipping session
                        $.ajax({
                            url: '/cart-shipping/clear-shipping-selection',
                            method: 'POST',
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    }
                }
                
                updateShippingAndTotal(response.order_type);
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    @if(Auth::check() && !empty(Auth::user()) && Auth::user()->role == 'affiliate' && $affiliate_sales->total_shutter_points >= 500)
        $(".shutterPoint").change(function () {
            let shutterPoint = $("input[name='shutter_point']:checked").val(); // Get selected value
            
            $.ajax({
                url: "{{route('shutter-point')}}",  // Your Laravel route
                type: "POST",
                data: {
                    shutter_point: shutterPoint,
                    _token: "{{ csrf_token() }}", 
                },
                success: function (response) {
                    updateShippingAndTotal(response.order_type);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        });
    @endif





});



</script>

<script>
    $(document).ready(function () {
        $(".calculat-shipping").click(function () {
            $(".calculate-shipping").slideToggle();
        });
    });

</script>
<script>
    $(document).ready(function () {
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
function checkPackageValidationForCart(callback){
    // Use cart data that's already available on the page
    @php
        $cartItemsData = [];
        if($cart && $cart->items) {
            foreach($cart->items as $item) {
                $cartItemsData[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'is_package' => $item->is_package ?? 0,
                    'package_product_id' => $item->package_product_id ?? null,
                    'category_id' => $item->product->category_id ?? null,
                    'product_type' => $item->product_type ?? null
                ];
            }
        }
    @endphp
    var currentCartItems = @json($cartItemsData);
    
    // Load wedding packages JSON
    $.get("{{ route('wedding-packages-json') }}", function(packages) {
        // Get items being updated in cart
        let updatedCartItems = [];
        let hasPackageItems = false;
        let packageSlug = '';
        
        $("input[name='product_quantity[]']").each(function() {
            let quantity = $(this).val();
            if (quantity > 0) {
                let is_package = $(this).data('is_package') || 0;
                let package_product_id = $(this).data('package_product_id') || null;
                let category_id = $(this).data('category_id') || null;
                let package_slug = $(this).data('package_slug') || '';
                
                console.log('Input data:', {
                    product_id: $(this).data('product_id'),
                    quantity: quantity,
                    is_package: is_package,
                    package_product_id: package_product_id,
                    category_id: category_id,
                    package_slug: package_slug
                });
                
                if (is_package == 1) {
                    hasPackageItems = true;
                    packageSlug = package_slug;
                }
                
                updatedCartItems.push({
                    product_id: $(this).data('product_id'),
                    quantity: parseFloat(quantity),
                    is_package: is_package,
                    package_product_id: package_product_id,
                    category_id: category_id,
                    package_slug: package_slug
                });
            }
        });

        console.log('updatedCartItems',updatedCartItems);
        console.log('hasPackageItems',hasPackageItems);
        console.log('packageSlug',packageSlug);

        
        if (hasPackageItems) {
            // Group items by package_slug
            var packageGroups = {};
            
            updatedCartItems.forEach(function(item) {
                if (item.is_package == 1 && item.package_slug) {
                    if (!packageGroups[item.package_slug]) {
                        packageGroups[item.package_slug] = {
                            package_slug: item.package_slug,
                            typeCounts: {} // Dynamic type counting
                        };
                    }
                    
                    // Find the package to get type mapping
                    var package = packages.packages.find(p => p.slug === item.package_slug);
                    if (package && package.frames) {
                        // Find the frame type for this category_id
                        var frame = package.frames.find(f => f.category_id == item.category_id);
                        if (frame && frame.type) {
                            var typeKey = frame.type.toLowerCase().replace(/\s+/g, '_');
                            
                            if (!packageGroups[item.package_slug].typeCounts[typeKey]) {
                                packageGroups[item.package_slug].typeCounts[typeKey] = 0;
                            }
                            
                            packageGroups[item.package_slug].typeCounts[typeKey] += parseInt(item.quantity);
                        }
                    }
                }
            });
            
            console.log('packageGroups', packageGroups);
            
            // Validate each package separately
            var allViolations = [];
            
            for (var currentPackageSlug in packageGroups) {
                var packageGroup = packageGroups[currentPackageSlug];
                console.log('Validating package:', currentPackageSlug, packageGroup);
                
                // Find package by slug
                var package = packages.packages.find(p => p.slug === currentPackageSlug);
                console.log('Found package:', package);
                
                if (package && package.restrictions) {
                    var restrictions = package.restrictions;
                    var violations = [];
                    
                    console.log('Restrictions:', restrictions);
                    
                    // Dynamic validation based on JSON restrictions
                    for (var restrictionType in restrictions) {
                        var restriction = restrictions[restrictionType];
                        var typeKey = restrictionType; // e.g., "photo_prints", "canvas_prints"
                        var currentCount = packageGroup.typeCounts[typeKey] || 0;
                        var limit = restriction.total_limit;
                        
                        console.log(`${typeKey}: count=${currentCount}, limit=${limit}`);
                        
                        if (currentCount > limit) {
                            var typeDisplayName = typeKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            var violation = `${typeDisplayName} limit exceeded. You are trying to set ${currentCount} ${typeDisplayName.toLowerCase()}, but the limit is ${limit}.`;
                            violations.push(violation);
                        }
                    }
                    
                    if (violations.length > 0) {
                        allViolations.push({
                            packageName: package.name,
                            violations: violations
                        });
                    }
                }
            }
            
            if (allViolations.length > 0) {
                var message = '';
                
                allViolations.forEach(function(packageViolation) {
                    message += `
                        <div class="package-violation" style="background-color: #f8f9fa; padding: 15px; border-radius: 0; margin-bottom: 15px;">
                            <h6 class="text-danger mb-3" style="font-weight: bold;">
                                <i class="fas fa-gift"></i> ${packageViolation.packageName}
                            </h6>
                            <div class="violations-list">
                                <ul class="list-unstyled mb-0">
                    `;
                    
                    packageViolation.violations.forEach(function(violation) {
                        message += `<li class="mb-2" style="color: #6c757d; font-size: 14px;"><i class="fas fa-times-circle text-danger"></i> ${violation}</li>`;
                    });
                    
                    message += `
                                </ul>
                            </div>
                        </div>
                    `;
                });
                
                // Show modal with violation message
                $('#restriction-message').html(message);
                $('#packageRestrictionModal').modal('show');
                
                // Ensure close button works
                $('#packageRestrictionModal .close, #packageRestrictionModal [data-dismiss="modal"]').off('click').on('click', function() {
                    $('#packageRestrictionModal').modal('hide');
                });
                
                callback(false); // Prevent updating cart
                return;
            } else {
                callback(true); // Allow updating cart
                return;
            }
        }
        
        callback(true); // No package items or no restrictions, allow
    }).fail(function() {
        callback(true); // Allow if JSON fails to load
    });
}

    $(document).ready(function () {
        $("#update_cart").on('click', function () {
            $('#qty-validation').addClass('d-none');
            
            // Check package validation first
            checkPackageValidationForCart(function(isValid) {
                if (!isValid) {
                    return false; // Stop if validation fails
                }
                
                // Proceed with cart update
                var data = [];
                $("input[name='product_quantity[]']").each(function (i, v) {
                    if ($(v).val() > 0) {
                        data.push({
                            'quantity': $(v).val(),
                            'rowId': $(v).data('row'),
                            'product_type': $(v).data('product_type'),
                            'product_id': $(v).data('product_id'),
                            'is_test_print': $(v).data('is_test_print')
                        })

                    } else {
                        $(this).addClass('validator');
                        $('#qty-validation').removeClass('d-none');
                        $('#qty-validation p').text('Please enter quantity equal to or more then 1.');
                        return false;
                    }

                });
                $.post("{{ route('update-cart') }}", {
                        data: data,
                        "_token": "{{ csrf_token() }}"
                    },
                    function (data, status) {
                        if (data.error == true) {
                            console.log(data.message);
                            $('#qty-validation').removeClass('d-none');
                            $('#qty-validation p').text(data.message);
                            return false;
                        } else {
                            location.reload();
                        }
                    });
            });
        })

        $(".product-img").on('click', function () {
            $("#modal-img").attr('src', $(this).children('img').attr('data-src'));
            $("#ImgViewer").modal('show');
        });

        $("#modal-close").on('click', function () {
            $("#ImgViewer").modal('hide');
        })
         });
 </script>

<link rel="stylesheet" href="{{ asset('css/package-cart.css') }}">

@endsection
