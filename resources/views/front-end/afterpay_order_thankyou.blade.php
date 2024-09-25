@extends('front-end.layout.main')
@section('content')
<section class="sans-ser">
    <div class="container">
        <div class="been-orders">
          <h5>Thank you for your order with Afterpay!</h5>
            <div class="shortly">
                <h1>ORDER CONFIRMATION
                </h1>
                <span>Your order has been sucessful! </span>
                <p>Thank you for choosing Shadows Photo Printing.
                </p>
            </div>
            <a class="thank-you-cls" href="{{ url('shop') }}">Back to shop</a>
        </div>
    </div>
</section>
@endsection
