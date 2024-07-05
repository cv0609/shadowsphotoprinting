@extends('front-end.layout.main')
@section('content')
<section class="coupon-main">
    <div class="container">
        <div class="coupon-inner">
            {{-- <div class="coupon-wrapper">
                <p> Returning customer? <a href="#">Click here to login</a> </p>
                <p> Have a coupon? <a href="#">Click here to enter your code</a> </p>
            </div> --}}
            <div class="billing-row">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="woocommerce-billing-fields">
                            <h3>Billing details</h3>
                            <div class="fields__field-wrapper">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="form-row">
                                            <label>First name * </label>
                                            <input type="text" name="name" id="fname" placeholder="developer ">
                                        </p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p class="form-row">
                                            <label>Last name *
                                            </label>
                                            <input type="text" name="lname" id="lname" placeholder="dev ">
                                        </p>
                                    </div>
                                </div>
                                <p class="form-row">
                                    <label>Company name (optional) </label>
                                    <input type="text" name="company_name" id="company_name" placeholder="test ">
                                </p>
                                <p class="form-row">
                                    <label>Country / Region *
                                    </label>
                                    <span> <strong>Australia </strong></span>
                                </p>
                                <p class="form-row">
                                    <label> Street address *
                                    </label>
                                    <input type="text" name="street1" id="street1"
                                        placeholder="House number and street name ">
                                    <input type="text" name="street2" id="street2"
                                        placeholder="Apartment, suite, unit, etc. (optional) ">
                                </p>
                                <p class="form-row">
                                    <label> Suburb * </label>
                                    <input type="text" name="suburb" id="suburb" placeholder="">
                                </p>
                                <p class="form-row">
                                    <select class="form-control" id="state" name="state" >
                                        <option value="">State</option>
                                        @foreach ($countries->states as $state)
                                          <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach

                                    </select>
                                </p>
                                <p class="form-row">
                                    <label> Postcode *
                                    </label>
                                    <input type="text" name="postcode" id="postcode">
                                </p>
                                <p class="form-row">
                                    <label> Phone (optional)
                                    </label>
                                    <input type="number" name="phone" id="phone">
                                </p>
                                <p class="form-row">
                                    <label> Email address *
                                    </label>
                                    <input type="email" name="email" id="email">
                                </p>
                                <p class="form-row">
                                    <label> Account username * </label>
                                    <input type="text" name="username" id="username" placeholder="Username">
                                </p>
                                <p class="form-row">
                                    <label> Create account password *
                                    </label>
                                    <input type="password" name="password" id="password" placeholder="Password">
                                </p>

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
                                                        <input type="text" name="ship_fname" id="ship_fname" placeholder="developer ">
                                                    </p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p class="form-row">
                                                        <label>Last name *
                                                        </label>
                                                        <input type="text" name="ship_lname" id="ship_lname" placeholder="dev ">
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="form-row">
                                                <label>Company name (optional) </label>
                                                <input type="text" name="ship_company" id="ship_company" placeholder="test ">
                                            </p>
                                            <p class="form-row">
                                                <label>Country / Region *
                                                </label>
                                                <span> <strong>Australia </strong></span>
                                            </p>
                                            <p class="form-row">
                                                <label> Street address *
                                                </label>
                                                <input type="text" name="ship_street1"
                                                    placeholder="House number and street name " id="ship_street1">
                                                <input type="text" name="ship_street2"
                                                    placeholder="Apartment, suite, unit, etc. (optional) " id="ship_street2">
                                            </p>
                                            <p class="form-row">
                                                <label> Suburb * </label>
                                                <input type="text" name="ship_suburb" placeholder="" id="ship_suburb">
                                            </p>
                                            <p class="form-row">
                                                <select class="form-control" id="ship_state" name="ship_state" >
                                                    <option value="">State</option>
                                                    @foreach ($countries->states as $state)
                                                      <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach

                                                </select>

                                            </p>
                                            <p class="form-row">
                                                <label> Postcode *
                                                </label>
                                                <input type="text" name="ship_postcode" id="ship_postcode">
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
                                        <tr>
                                            <td> {{ $item->product->product_title }}&nbsp; <strong>×&nbsp;{{ $item->quantity }}</strong> </td>
                                            <td> <span><bdi><span>$</span>{{ number_format($item->quantity * $item->product->product_price,2) }}</bdi></span> </td>
                                            <input type="hidden" value="{{ number_format($item->quantity * $item->product->product_price,2) }}">
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
                                            <th>Coupon: {{ $CartTotal['coupon_code'] }}</th>
                                            <td>-<span><span>$</span>{{ number_format($CartTotal['coupon_discount'],2) }}</span> </td>
                                        </tr>
                                        @endif
                                        @if($shipping->status == "1")
                                        <tr>
                                            <th>Shipping</th>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <input type="hidden" data-index="0">
                                                        <label>Flat rate:
                                                            <span><bdi><span>$</span>{{ number_format($shipping->amount,2) }}</bdi></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td><strong><span><bdi><span>$</span>{{ number_format($CartTotal['total'],2) }}</bdi></span></strong>
                                                <small class="includes_tax">(includes
                                                    <span><span>$</span>1.12</span>
                                                    GST)</small>
                                            </td>
                                        </tr>


                                    </tfoot>
                                </table>
                                <div id="payment">
                                    <form id="payment-form">
                                    <div class="payment_methods ">
                                        <ul>
                                            <li>
                                                <label for=""> Credit Card (Stripe) </label>
                                                <p>Pay with your credit card via Stripe.</p>
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
                                                <div class="save-info">
                                                    <input type="checkbox" id="save_card">
                                                    <label for="save_card">
                                                        Save payment information to my account for future purchases.</label>
                                                </div>
                                                  {{-- <div id="card-element">

                                                  </div> --}}
                                                    {{-- <button id="submit">Submit Payment</button> --}}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="experience-throughout">
                                        <p>Your personal data will be used to process your order, support your
                                            experience throughout this website, and for other purposes described
                                            in our <a href="#" target="_blank">privacy policy</a>.</p>
                                        <div class="place-order">
                                            <button id="submit"> Place order </button>
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
 </div>
</section>
@endsection
@section('scripts')
<script>
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

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        $('.error-message').remove();

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
            var ship_state = $('#ship_state').val();
            var ship_postcode = $('#ship_postcode').val();
            var order_comments = $('#order_comments').val();
        }


        var isValid = true;

        // Primary address validation
        var requiredFields = ['#fname', '#lname', '#street1','#street2', '#postcode', '#email', '#username', '#password','#suburb'];
        requiredFields.forEach(function(field) {
            var $field = $(field);
            if ($field.val().trim() === '') {
                isValid = false;
                $field.after('<span class="error-message" style="color: red;">This field is required.</span>');
            }
        });

        // Shipping address validation if checkbox is checked
        if ($('#shipcheckbox').is(':checked')) {
            var shipRequiredFields = ['#ship_fname', '#ship_lname', '#ship_street1','#ship_street2', '#ship_postcode','#ship_suburb'];
            shipRequiredFields.forEach(function(field) {
                var $field = $(field);
                if ($field.val().trim() === '') {
                    isValid = false;
                    $field.after('<span class="error-message" style="color: red;">This field is required.</span>');
                }
            });
        }

        if (!isValid) {
            return; // Stop form submission
        }


        stripe.createToken(cardNumber).then(function(result) {
            if (result.error) {
                // Display error.message in your UI
            } else {
                // Send the token to your server
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
                    stripeToken: result.token.id
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
                }


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
                            amount: 1000 // amount in cents
                        })
                    })
                    .then(response => response.json())
                    .then(charge => {
                         if(charge.error == false){
                            window.location.href = "{{ route('thankyou') }}";
                         }else{
                            console.log('something went wrong.');
                         }
                    });
                });
            }
        });
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
