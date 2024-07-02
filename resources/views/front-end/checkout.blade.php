@extends('front-end.layout.main')
@section('content')
<section class="coupon-main">
    <div class="container">
        <div class="coupon-inner">
            <div class="coupon-wrapper">
                <p> Returning customer? <a href="#">Click here to login</a> </p>
                <p> Have a coupon? <a href="#">Click here to enter your code</a> </p>
            </div>
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
                                            <input type="text" name="name" placeholder="developer ">
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
                                    <input type="text" name="company-name" placeholder="test ">
                                </p>
                                <p class="form-row">
                                    <label>Country / Region *
                                    </label>
                                    <span> <strong>Australia </strong></span>
                                </p>
                                <p class="form-row">
                                    <label> Street address *
                                    </label>
                                    <input type="text" name="street"
                                        placeholder="House number and street name ">
                                    <input type="text" name="suite"
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
                                    <input type="text" name="postcode">
                                </p>
                                <p class="form-row">
                                    <label> Phone (optional)
                                    </label>
                                    <input type="number" name="number1">
                                </p>
                                <p class="form-row">
                                    <label> Email address *
                                    </label>
                                    <input type="email" name="email12">
                                </p>
                                <p class="form-row">
                                    <label> Account username * </label>
                                    <input type="text" name="username" id="postcode" placeholder="Username">
                                </p>
                                <p class="form-row">
                                    <label> Create account password *
                                    </label>
                                    <input type="password" name="password" placeholder="Password">
                                </p>

                            </div>
                            <div class="Ship-field">
                                <div class="ship-to-different">
                                    <h3 class="ship-to-different-address">
                                        <label for="chkPassport">
                                            <input type="checkbox" id="chkPassport"> <span> Ship to a different
                                                address?</span>
                                        </label>
                                    </h3>
                                    <div class="fields__field-_gangast" id="dvPassport">
                                        <div class="fields__field-wrapper">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <p class="form-row">
                                                        <label>First name * </label>
                                                        <input type="text" name="name" placeholder="developer ">
                                                    </p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p class="form-row">
                                                        <label>Last name *
                                                        </label>
                                                        <input type="text" name="lname" placeholder="dev ">
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="form-row">
                                                <label>Company name (optional) </label>
                                                <input type="text" name="company-name" placeholder="test ">
                                            </p>
                                            <p class="form-row">
                                                <label>Country / Region *
                                                </label>
                                                <span> <strong>Australia </strong></span>
                                            </p>
                                            <p class="form-row">
                                                <label> Street address *
                                                </label>
                                                <input type="text" name="street1"
                                                    placeholder="House number and street name ">
                                                <input type="text" name="street2"
                                                    placeholder="Apartment, suite, unit, etc. (optional) ">
                                            </p>
                                            <p class="form-row">
                                                <label> Suburb * </label>
                                                <input type="text" name="suburb" placeholder="">
                                            </p>
                                            <p class="form-row">
                                                <select class="form-control" id="ship-state" name="state" >
                                                    <option value="">State</option>
                                                    @foreach ($countries->states as $state)
                                                      <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach

                                                </select>

                                            </p>
                                            <p class="form-row">
                                                <label> Postcode *
                                                </label>
                                                <input type="text" name="postcode0">
                                            </p>


                                        </div>
                                    </div>
                                    <div class="order-notes">
                                        <p class="form-row">
                                            <label>Order notes (optional) </label>
                                            <textarea name="order_comments" class="input-text "
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
                                                  <div id="card-element"></div>
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
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Display error.message in your UI
            } else {
                // Send the token to your server
                fetch('/create-customer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        source: result.token.id
                    })
                })
                .then(response => response.json())
                .then(customer => {
                    fetch('/charge-customer', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            customer_id: customer.id,
                            amount: 1000 // amount in cents
                        })
                    })
                    .then(response => response.json())
                    .then(charge => {
                         console.log(charge);
                    });
                });
            }
        });
    });
</script>

<script>
    $(function () {
        $("#chkPassport").click(function () {
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
            placeholder: 'Select products',
            allowClear: true
        });

    $('#ship-state').select2({
        placeholder: 'Select products',
        allowClear: true
    });

</script>
@endsection
