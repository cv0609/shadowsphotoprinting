@extends('front-end.layout.main')
@section('styles')
<style>
    .shortly {
        background: #e8ede0;
        padding: 30px;
        margin-top: 30px;
        border-radius: 6px;
    }
    .shortly h1 {
        font-size: 26px;
        color: #111111;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .shortly span {
        font-size: 18px;
        margin-bottom: 10px;
        display: block;
        color: #212529;
        font-weight: 600;
    }
    .shortly p {
        font-size: 16px;
        color: #333333;
        margin-bottom: 0;
        line-height: 24px;
    }
</style>
@endsection
@section('content')
<section class="sans-ser">
    <div class="container">
        <div class="been-orders">
          @if(!empty($couponSuccessMessage))
              <div class="alert alert-success text-center" role="alert" style="margin-bottom: 20px;">
                  {{ $couponSuccessMessage }}
              </div>
          @endif
          <h5>Thank you for your order with Afterpay!</h5>
            <div class="shortly">
                <h1>ORDER CONFIRMATION</h1>
                <span>Your order has been successful!</span>
                <p>Thank you for choosing Shadows Photo Printing.</p>
            </div>
            <a class="thank-you-cls" href="{{ url('shop') }}">Back to shop</a>
        </div>
    </div>
</section>
@endsection
