@extends('front-end.layout.main')
@section('content')
@php
    $CartService = app(App\Services\CartService::class);
    $order_type = 0;
    if(Session::has('order_type')){
        $order_type = Session::get('order_type');
        if($order_type != 0){
            $CartTotal['shippingCharge'] = 0;
        }
    }
@endphp

<section class="coupon-main">
    <div class="container">
        <div class="coupon-inner">
            <div class="billing-row">
                <div class="row">
                    @if(Session::has('error'))
                      <p class="alert alert-danger text-center w-100">{{ Session::get('error') }}</p>
                    @endif

                    <div class="col-lg-6">
                        <div class="woocommerce-billing-fields">
                            <h3>Billing details</h3>
                            <div class="fields__field-wrapper">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="form-row">
                                            <label>First name * </label>

                                            <input type="text" name="name" value="{{ isset($user_address) && !empty($user_address->fname) ? $user_address->fname : '' }}" id="fname" placeholder="John">

                                        </p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p class="form-row">
                                            <label>Last name *
                                            </label>
                                            <input type="text" name="lname" id="lname" value="{{ isset($user_address) && !empty($user_address->lname) ? $user_address->lname : '' }}" placeholder="Smith">
                                        </p>
                                    </div>
                                </div>
                                <p class="form-row">
                                    <label>Company name (optional) </label>
                                    <input type="text" name="company_name" id="company_name" value="{{ isset($user_address) && !empty($user_address->company_name) ? $user_address->company_name : '' }}" placeholder="Example">
                                </p>
                                <p class="form-row">
                                    <label>Country / Region *
                                    </label>
                                    <span> <strong>{{ config('constant.default_country') }} </strong></span>
                                </p>
                                <p class="form-row">
                                    <label> Street address *
                                    </label>
                                    <input type="text" name="street1" id="street1"
                                        placeholder="House number and street name" value="{{ isset($user_address) && !empty($user_address->street1) ? $user_address->street1 : '' }}">
                                    <input type="text" name="street2" id="street2"
                                        placeholder="Apartment, suite, unit, etc. (optional)" value="{{ isset($user_address) && !empty($user_address->street2) ? $user_address->street2 : '' }}">
                                </p>
                                <p class="form-row">
                                    <label> Suburb * </label>
                                    <input type="text" name="suburb" id="suburb" placeholder="" value="{{ isset($user_address) && !empty($user_address->suburb) ? $user_address->suburb : '' }}">
                                </p>

                                <p class="form-row">
                                    @php
                                        $address_state = '';
                                        $ship_address_state = '';
                                        if (isset($user_address) && !empty($user_address->state)) {
                                            $address_state = $user_address->state;
                                        }

                                        if (isset($user_address) && !empty($user_address->ship_state)) {
                                            $ship_address_state = $user_address->ship_state;
                                        }


                                    @endphp
                                
                                    <select class="form-control" id="state" name="state">
                                        <option value="">State</option>
                                        @foreach ($countries->states as $state)
                                            <option value="{{ $state->id }}" {{ $address_state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </p>
                                
                                <p class="form-row">
                                    <label> Postcode *
                                    </label>
                                    <input type="number" name="postcode" id="postcode" value="{{ isset($user_address) && !empty($user_address->postcode) ? $user_address->postcode : '' }}">
                                </p>
                                <p class="form-row">
                                    <label> Phone (optional)
                                    </label>
                                    <input type="number" name="phone" id="phone" value="{{ isset($user_address) && !empty($user_address->phone) ? $user_address->phone : '' }}">
                                </p>
                                <p class="form-row">
                                    <label> Email address *
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ isset($user_address) && !empty($user_address->email) ? $user_address->email : '' }}">
                                </p>
                                @if(!Auth::check())
                                    <p class="form-row">
                                        <label> Account username * </label>
                                        <input type="text" name="username" id="username" placeholder="Username" value="{{ isset($user_address) && !empty($user_address->username) ? $user_address->username : '' }}">
                                    </p>
                                    <p class="form-row">
                                        <label> Create account password *
                                        </label>
                                        <input type="password" name="password" id="password" placeholder="Password" value="{{ isset($user_address) && !empty($user_address->password) ? $user_address->password : '' }}">
                                    </p>
                                @endif

                            </div>
                            <div class="Ship-field">
                                <div class="ship-to-different">
                                    <h3 class="ship-to-different-address">
                                        <label for="shipcheckbox">
                                            <input type="checkbox" id="shipcheckbox"> <span> Ship to a different
                                                address?</span>
                                        </label>
                                    </h3>
                                    <div class="fields__field-_gangast" id="dvPassport">
                                        <div class="fields__field-wrapper">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <p class="form-row">
                                                        <label>First name * </label>
                                                        <input type="text" name="ship_fname" id="ship_fname" placeholder="john" value="{{ isset($user_address) && !empty($user_address->ship_fname) ? $user_address->ship_fname : '' }}">
                                                    </p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p class="form-row">
                                                        <label>Last name *
                                                        </label>
                                                        <input type="text" name="ship_lname" id="ship_lname" placeholder="Smith" value="{{ isset($user_address) && !empty($user_address->ship_lname) ? $user_address->ship_lname : '' }}">
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="form-row">
                                                <label>Company name (optional) </label>
                                                <input type="text" name="ship_company" id="ship_company" placeholder="Example" value="{{ isset($user_address) && !empty($user_address->ship_company) ? $user_address->ship_company : '' }}">
                                            </p>
                                            <p class="form-row">
                                                <label>Country / Region *
                                                </label>
                                                <span> <strong>{{ config('constant.default_country') }}</strong></span>
                                            </p>
                                            <p class="form-row">
                                                <label> Street address *
                                                </label>
                                                <input type="text" name="ship_street1"
                                                    placeholder="House number and street name " id="ship_street1" value="{{ isset($user_address) && !empty($user_address->ship_street1) ? $user_address->ship_street1 : '' }}">
                                                <input type="text" name="ship_street2"
                                                    placeholder="Apartment, suite, unit, etc. (optional) " id="ship_street2" value="{{ isset($user_address) && !empty($user_address->ship_street2) ? $user_address->ship_street2 : '' }}">
                                            </p>
                                            <p class="form-row">
                                                <label> Suburb * </label>
                                                <input type="text" name="ship_suburb" placeholder="" id="ship_suburb" value="{{ isset($user_address) && !empty($user_address->ship_suburb) ? $user_address->ship_suburb : '' }}">
                                            </p>


                                            <p class="form-row">
                                                <select class="form-control" id="ship_state" name="ship_state" >
                                                    <option value="">State</option>
                                                    @foreach ($countries->states as $state)
                                                      <option value="{{ $state->id }}" {{ $ship_address_state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                                    @endforeach

                                                </select>

                                            </p>
                                            <p class="form-row">
                                                <label> Postcode *
                                                </label>
                                                <input type="number" name="ship_postcode" id="ship_postcode" value="{{ isset($user_address) && !empty($user_address->ship_postcode) ? $user_address->ship_postcode : '' }}">
                                            </p>


                                        </div>
                                    </div>
                                    <div class="order-notes">
                                        <p class="form-row">
                                            <label>Order notes (optional) </label>
                                            <textarea name="order_comments" id="order_comments" class="input-text "
                                                placeholder="Notes about your order, e.g. special notes for delivery."
                                                rows="2" cols=""></textarea>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="woocommerce-billing-fields">
                            <h3>Your order</h3>
                            <div class="order_review">
                                <table class="shop_table ">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->items as $item)
                                        <?php 
                                        
                                        $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);
                                        $productSalePrice =  $CartService->getProductSalePrice($item->product_id);
                                        
                                        ?>

                                        <tr>

                                            <td>
                                                @if($item->product_type == 'gift_card' || $item->product_type == 'photo_for_sale' || $item->product_type == 'hand_craft')
                                                    {{ $product_detail->product_title }}
                                                @else
                                                    {{ $item->product->product_title }}
                                                @endif
                                                &nbsp; <strong>×&nbsp;{{ $item->quantity }}</strong>
                                            </td>

                                            <td>
                                                <span>
                                                    
                                                    <bdi>
                                                        <span>$</span>
                                                        @if($item->product_type == 'gift_card' || $item->product_type == 'photo_for_sale' || $item->product_type == 'hand_craft')
                                                            {{ number_format($item->quantity * $item->product_price, 2) }}
                                                        @else
                                                            @if(isset($item->is_test_print) && ($item->is_test_print == '1'))
                                                                {{ number_format($item->test_print_qty * $item->test_print_price, 2) }}
                                                            @else
                                                                {{ isset($productSalePrice) && !empty($productSalePrice) 
                                                                    ? number_format($item->quantity * $productSalePrice, 2) 
                                                                    : number_format($item->quantity * $item->product->product_price, 2)
                                                                }}
                                                            @endif
                                                        @endif
                                                    </bdi>
                                                </span>
                                            </td>

                                            <input type="hidden" value="@if($item->product_type == 'gift_card')
                                                {{ number_format($item->quantity * $product_detail->product_price, 2) }}
                                            @elseif($item->product_type == 'photo_for_sale')
                                                {{ number_format($item->quantity * $product_detail->product_price, 2) }}
                                            @else
                                                {{ isset($productSalePrice) && !empty($productSalePrice) ? number_format($item->quantity * $productSalePrice, 2) : number_format($item->quantity * $item->product->product_price, 2) }}
                                            @endif">

                                        </tr>
                                       @endforeach
                                    </tbody>
                                    <tfoot>

                                        <tr>
                                            <th>Subtotal</th>
                                            <td><span><bdi><span>$</span>{{ number_format($CartTotal['subtotal'],2) }}</bdi></span> </td>
                                        </tr>
                                        @if(Session::has('coupon'))
                                        <tr>
                                            <th>Coupon: {{ $CartTotal['coupon_code']['code'] }}</th>
                                            <td>-<span><span>$</span>{{ number_format($CartTotal['coupon_discount'],2) }}</span> </td>
                                        </tr>
                                        @endif

                                        @if($cart->shutter_point == '1')
                                        <tr>
                                            <th>Shutter Point:</th>
                                            <td>-<span><span></span>{{ $affiliate_sales->total_shutter_points }} (${{ $affiliate_sales->total_commission }})</span> </td>
                                        </tr>
                                        @endif

                                        @if($shipping->status == "1" && $order_type != 1)
                                        <tr>
                                            <th>Shipping</th>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <input type="hidden" data-index="0">
                                                        <label>Flat rate:
                                                            <span><bdi><span>$</span>{{ number_format($CartTotal['shippingCharge'],2) }}</bdi></span>
                                                        </label>
                                                       
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endif

                                        <input type="hidden" name="shipping_charge" id="shipping_charge" value="{{ number_format($CartTotal['shippingCharge'] ?? 0, 2) }}">

                                        <input type="hidden" name="customer_order_type" id="customer_order_type" value="{{$order_type}}">

                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td><strong><span><bdi><span>$</span>{{ number_format($CartTotal['total'],2) }}</bdi></span></strong>
                                                {{-- <small class="includes_tax">(includes
                                                    <span><span>$</span>1.12</span>
                                                    GST)</small> --}}
                                            </td>
                                            <input type="hidden" id="total_amount" value="{{ number_format($CartTotal['total'],2) }}">
                                        </tr>

                                        @if($CartTotal['total'] > 0)

                                        <tr>
                                            <td>
                                                <div class="afterpay-4-payment">
                                                    <span>or 4 payments as low as ${{ number_format(($CartTotal['total'])/4,2) }} with </span>
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
                                            </td>
                                        </tr>

                                        @endif

                                    </tfoot>
                                </table>

                                @if($CartTotal['total'] > 0)

                                    <div class="mt-5">
                                    <h3 class="mb-4">Select Payment Method</h3>
                                        <div class="row">
                                            @if(env('STRIPE') == true)
                                            <div class="col-md-6">
                                                <label class="payment-option active-payment" for="stripeId">
                                                    <input type="radio" name="payment" id="stripeId" value="stripe" checked>
                                                    <!-- <i class="fab fa-cc-stripe"></i> -->
                                                    <i class="fas fa-credit-card"></i>
                                                    <span>Stripe</span>
                                                </label>
                                            </div>
                                            @endif

                                            @if(env('AFFTERPAY') == true)
                                            <div class="col-md-6">
                                                <label class="payment-option active-payment" for="afterpayId">
                                                    <input type="radio" name="payment" id="afterpayId" value="afterpay">
                                                    <img src="{{asset('assets/images/favicon.ico')}}" alt="" class="afterPayIcon">
                                                    <span>Afterpay</span>
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                @endif

                                <div id="payment">
                                    <form id="payment-form">
                                        @if($CartTotal['total'] > 0)
                                        <div class="payment_methods stripe-details">
                                            <ul>
                                            {{-- @if(env('STRIPE') == true)      --}}
                                                <li>
                                                    <label for=""> Credit Card (Stripe) </label>
                                                    <p>Pay with your credit card via Stripe.</p><br>
                                                    <span id="stripe-error"></span>
                                                    <div class="payment_form-wrap">
                                                        <div class="form-group">
                                                            <label>Card Number</label>
                                                            <div id="card-number-element"></div>
                                                            <div id="card-number-errors" role="alert"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Expiration Date</label>
                                                            <div id="card-expiry-element"></div>
                                                            <div id="card-expiry-errors" role="alert"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>CVC Number</label>
                                                            <div id="card-cvc-element"></div>
                                                            <div id="card-cvc-errors" role="alert"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                {{-- @endif --}}
                                            </ul>
                                        </div>
                                        @endif
                                    <div class="experience-throughout">
                                        <p id="afterPayError"></p>
                                        {{-- <p>Your personal data will be used to process your order, support your
                                            experience throughout this website, and for other purposes described
                                            in our privacy policy.</p> --}}
                                        <div class="place-order" id="place-order-btn">
                                            <button id="submit"> Place order </button>
                                        </div>
                                        <div class="loader-order d-none" id="loader-order-btn">
                                            <button type="button" disabled="disabled"> <img src="{{asset('assets/images/loader.gif')}}"> </button>
                                        </div>
                                    </div>
                                </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 {{-- </div> --}}
</section>
@endsection
@section('scripts')


<script>

    $(document).ready(function() {
        if ($('#afterpayId').is(':checked')) {
            $('.stripe-details').hide();
        }
        $('input[name="payment"]').on('change', function() {
            if ($('#stripeId').is(':checked')) {
                $('.stripe-details').show();
            } else if ($('#afterpayId').is(':checked')) {
                $('.stripe-details').hide();
            }
        });
    });

    var order_cart_total = {{$CartTotal['total']}};


    var authcheck = "{{Auth::check()}}";

    if(order_cart_total > 0){
        var stripe = Stripe("{{ env('STRIPE_KEY') }}");
        
        var elements = stripe.elements();
        var style =  {
            base: {
            iconColor: '#666EE8',
            color: '#000',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: 'Helvetica Neue',
            fontSize: '15px',
            padding:'10px',
            background:'red',
    
            '::placeholder': {
                color: '#CFD7E0',
            },
            },
        }
        var cardNumber = elements.create('cardNumber', {style: style});
        cardNumber.mount('#card-number-element');
    
        var cardExpiry = elements.create('cardExpiry', {style: style});
        cardExpiry.mount('#card-expiry-element');
    
        var cardCvc = elements.create('cardCvc', {style: style});
        cardCvc.mount('#card-cvc-element');
    }
    

    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        $('.error-message').remove();

        var isShippingAddress = false;

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var street1 = $('#street1').val();
        var street2 = $('#street2').val();
        var state = $('#state').val();
        var postcode = $('#postcode').val();
        var phone = $('#phone').val();
        var suburb = $('#suburb').val();
        var email = $('#email').val();
        var username = $('#username').val();
        var shipping_charge = $('#shipping_charge').val();
        var total_amount = $('#total_amount').val();
        var customer_order_type = $('#customer_order_type').val();
        
        
        
        var password = $('#password').val();
        var company_name = $('#company_name').val();

        if ($('#shipcheckbox').is(':checked')) {
            var ship_fname = $('#ship_fname').val();
            var ship_lname = $('#ship_lname').val();
            var ship_company = $('#ship_company').val();
            var ship_street1 = $('#ship_street1').val();
            var ship_street2 = $('#ship_street2').val();
            var ship_suburb = $('#ship_suburb').val();
            var ship_state = $('#ship_state').val();
            // var ship_state = $('#ship_state').val();
            var ship_postcode = $('#ship_postcode').val();
            var order_comments = $('#order_comments').val();
            isShippingAddress = true;
        }

        $('#stripe-error').text('');

        var isValid = true;

        var requiredFields = ['#fname', '#lname', '#street1', '#postcode', '#email', '#suburb'];

        if (!authcheck) {
            requiredFields = requiredFields.concat(['#username', '#password']);
        }

        requiredFields.forEach(function(field) {
            var $field = $(field);
            if ($field.val().trim() === '') {
                isValid = false;
                $field.after('<span class="error-message" style="color: red;">This field is required.</span>');
            }
        });

        // Shipping address validation if checkbox is checked
        if ($('#shipcheckbox').is(':checked')) {
            var shipRequiredFields = ['#ship_fname', '#ship_lname', '#ship_street1', '#ship_postcode','#ship_suburb'];
            shipRequiredFields.forEach(function(field) {
                var $field = $(field);
                if ($field.val().trim() === '') {
                    isValid = false;
                    $field.after('<span class="error-message" style="color: red;">This field is required.</span>');
                }
            });
        }

        var formData = {
            fname: fname,
            lname: lname,
            street1: street1,
            street2: street2,
            state: state,
            postcode: postcode,
            phone: phone,
            suburb: suburb,
            email: email,
            username: username,
            password: password,
            company_name:company_name,
            shipping_charge:shipping_charge,
            customer_order_type: customer_order_type
        };

        if ($('#shipcheckbox').is(':checked')) {
            formData.ship_fname = ship_fname;
            formData.ship_lname = ship_lname;
            formData.ship_company = ship_company;
            formData.ship_street1 = ship_street1;
            formData.ship_street2 = ship_street2;
            formData.ship_suburb = ship_suburb;
            formData.ship_state = ship_state;
            formData.ship_postcode = ship_postcode;
            formData.order_comments = order_comments;
            formData.isShippingAddress = isShippingAddress;
        }

        if(order_cart_total > 0){
            if ($('#stripeId').is(':checked')) {

                if (cardNumber._empty) {
                    isValid = false;
                    $('#card-number-element').after('<span class="error-message" style="color: red;">Card number is required.</span>');
                }
        
                if (cardExpiry._empty) {
                    isValid = false;
                    $('#card-expiry-element').after('<span class="error-message" style="color: red;">Expiry date is required.</span>');
                }
        
                if (cardCvc._empty) {
                    isValid = false;
                    $('#card-cvc-element').after('<span class="error-message" style="color: red;">CVC is required.</span>');
                }
        
                if (!isValid) {
                    return;
                }
                
                stripe.createToken(cardNumber).then(function (result) {
    if (result.error) {
        $('#stripe-error').text(result.error.message).css('color', 'red');
        $('#place-order-btn').removeClass('d-none');
        $('#loader-order-btn').addClass('d-none');
    } else {
        $('#place-order-btn').addClass('d-none');
        $('#loader-order-btn').removeClass('d-none');

        // Send the token to the server    
        formData.stripeToken = result.token.id;
        formData.payment_method = 'stripe';

        var cent_total_amount = total_amount * 100; // Convert to cents

        fetch('/create-customer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(response => {
            fetch('/charge-customer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_id: response.id,
                    amount: cent_total_amount, // amount in cents
                    stripeToken: formData.stripeToken // Fix: Correct token usage
                })
            })
            .then(response => response.json())
            .then(charge => {
                if (charge.error === false) {
                    let url = "{{ route('thankyou', ['order_id' => ':orderId']) }}";
                    url = url.replace(':orderId', charge.order_id);
                    window.location.href = url;
                } else {
                    $('#stripe-error').text(charge.data).css('color', 'red');
                    $('#place-order-btn').removeClass('d-none');
                    $('#loader-order-btn').addClass('d-none');
                    console.log(charge.data);
                }
            });
        });
    }
});

            }else{

                if (!isValid) {
                    $('#place-order-btn').removeClass('d-none');
                    $('#loader-order-btn').addClass('d-none');
                    return;
                }
                $('#place-order-btn').addClass('d-none');
                $('#loader-order-btn').removeClass('d-none');

                formData.payment_method = 'afterPay';

                $.ajax({
                    type: "POST",
                    url: "{{route('afterPay.checkout')}}", // Replace with your Laravel route
                    data: {
                        _token: "{{ csrf_token() }}", // Include CSRF token
                        data: formData // Include your data here
                    },
                    success: function(data) {
                        if (data.error == false) {
                            window.location = data.data; 
                        } else {
                            $('#afterPayError').text(data.data).css('color','red');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Error: " + error);
                    }
                });
            }
        }else{
            if (!isValid) {
                $('#place-order-btn').removeClass('d-none');
                $('#loader-order-btn').addClass('d-none');
                return;
            }
            $('#place-order-btn').addClass('d-none');
            $('#loader-order-btn').removeClass('d-none');

            formData.payment_method = 'free';

            $.ajax({
                type: "POST",
                url: "{{route('free_order.checkout')}}", // Replace with your Laravel route
                data: {
                    _token: "{{ csrf_token() }}", // Include CSRF token
                    data: formData // Include your data here
                },
                success: function(data) {
                    if (data.error == false) {
                        window.location = data.data; 
                    } else {
                        $('#afterPayError').text(data.data).css('color','red');
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error);
                }
            });
        }
    });
</script>

<script>
    $(function () {
        $("#shipcheckbox").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
                $("#AddPassport").hide();
            } else {
                $("#dvPassport").hide();
                $("#AddPassport").show();
            }
        });
    });

    $('#state').select2({
            placeholder: 'Select state',
            allowClear: false
        });

    $('#ship_state').select2({
        placeholder: 'Select state',
        allowClear: false
    });

</script>
@endsection
