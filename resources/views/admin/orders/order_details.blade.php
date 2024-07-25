@extends('admin.layout.main')
@section('page-content')
@php
   $CartService = app(App\Services\CartService::class);
@endphp
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orders-list') }}">Orders</a></li>
          <li class="breadcrumb-item"><a href="#">Order Detail</a></li>
        </ol>
      </nav>
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="order-address">
            <div class="top_row_order_address">
                  <div class="row">
                    <div class="col-12">
                      <div class="upper_header">
                        <h3>Order #15780 details</h3>

                        <div class="print_btn">
                          <a href="">Print</a>
                        </div>
                      </div>
                        <div class="lower_header">
                          <p>Payment via Credit Card (Stripe) ( <a href="{{ env('STRIPE_URL').$orderDetail->payment_id }}" target="_blank">{{ $orderDetail->payment_id }}</a>). Paid on {{ date('d F Y H:s A',strtotime($orderDetail->created_at)) }}</p>
                        </div>
                    </div>
                  </div>
            </div>
            <div class="row g-5">
              <div class="col-md-4">
                <div class="gerneral_billing_details">
                  <h4 class="mb-3">General</h4>

                  <div class="main_input_div">
                <label for="">Date created:</label>
                <p>{{ date('Y-m-d',strtotime($orderDetail->created_at)) }}</p>
                  </div>


                  <div class="main_input_div">
                    <label for="">Status : </label>
                    <select class="form-select form-control" aria-label="Default select example">
                      <option value="0" selected>Processing</option>
                      <option value="1">Completed</option>
                      <option value="2">Cancelled</option>
                      <option value="3">Refunded</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="order-address-details">
                    <h4 class="mb-3">Billing details</h4>
                    <div class="address">
                      <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['fname']) }} {{ Str::ucfirst($orderDetail->OrderBillingDetail['lname']) }}<br>{{ $orderDetail->OrderBillingDetail['street1'] }}<br>{{ $orderDetail->OrderBillingDetail['street2'] }} {{ Str::ucfirst($orderDetail->OrderBillingDetail['suburb']) }} {{ Str::ucfirst($orderDetail->OrderBillingDetail['state']) }} {{ $orderDetail->OrderBillingDetail['postcode'] }}</p>
                      <p><strong>Email address:</strong> <br><a href="mailto:{{ $orderDetail->OrderBillingDetail['email'] }}">{{ $orderDetail->OrderBillingDetail['email'] }}</a></p>
                      <p><strong>Phone:</strong><br> <a href="tel:{{ $orderDetail->OrderBillingDetail['phone'] }}">{{ $orderDetail->OrderBillingDetail['phone'] }}</a></p>
                    </div>
                </div>
              </div>
              <div class="col-md-4">
                @if($orderDetail->isShippingAddress)
                    <div class="order-address-details" id="Shipping-address">
                        <h4 class="mb-3">Shipping details</h4>
                        <div class="address">
                            <p>{{ $orderDetail->OrderBillingDetail['ship_fname'] }}  {{Str::ucfirst($orderDetail->OrderBillingDetail['ship_lname'])}} <br>{{$orderDetail->OrderBillingDetail['ship_street1']}}<br>{{$orderDetail->OrderBillingDetail['ship_street2']}} {{Str::ucfirst($orderDetail->OrderBillingDetail['ship_suburb'])}} {{Str::ucfirst($orderDetail->OrderBillingDetail['ship_state'])}} {{$orderDetail->OrderBillingDetail['ship_postcode']}}</p><p class="order_note"><strong>Customer provided note:<br></strong> {{$orderDetail->OrderBillingDetail['order_comments']}}</p></div>
                    </div>
                @else
                <div class="diffrent-address">
                  <h4 class="mb-3">Shipping details</h4>
                   <p>The shipping address is the same as the billing address.</p>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
          <div class="x_panel">
      <div class="x_title">
        <h2>Order Details</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

      <div class="table-responsive-sm order-invoice-main">
        <table class="table">
          <thead class="table_head">
            <tr>
              <th class="center">Item</th>
              <th></th>
              <th class="right">Cost</th>
              <th class="center">Qty</th>
              <th class="right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orderDetail->orderDetails as $key => $item)
            <?php $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);?>
            <tr>
              <td class="center order-img" data-title="image"><img src="{{ asset($item->selected_images) }}" alt=""></td>
              <td class="strong order_page_td">
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
                        {{ $product_detail->name }}
                        <p class="giftcard-message"><span class="gift-desc-heading">To: </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span class="gift-desc-heading"> From: </span><span> {{$giftcard_product_desc->from ?? ''}}</span><span class="gift-desc-heading"> Message: </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span></p>
                    @elseif($item->product_type == "photo_for_sale")
                        {{ $product_detail->product_title ?? '' }} - {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}
                    @else
                        {{ $item->product->product_title ?? ''}}
                    @endif
                </a>
                <div class="wc-order-item-sku"><strong>SKU:</strong> {{ $product_detail->slug }}</div>
            </td>


            <td class="right order_page_td" data-title="price">
                <span class="">
                    <bdi>
                        <span>$</span>
                        @if($item->product_type == "gift_card")
                        {{ number_format($item->product_price, 2) }}
                    @elseif($item->product_type == "photo_for_sale")
                        {{ number_format($item->product_price, 2) }}
                    @else
                        {{ number_format($product_detail->product_price, 2) }}
                    @endif
                    </bdi>
                </span>
            </td>

              <td class="center order_page_td" data-title="qty">{{ $item->quantity }}</td>
              <td class="right order_page_td" data-title="total">$  @if($item->product_type == "gift_card")
                {{ number_format($item->quantity * $item->product_price, 2) }}
            @elseif($item->product_type == "photo_for_sale")
                {{ number_format($item->quantity * $item->product_price, 2) }}
            @else
                {{ number_format($item->quantity * $item->product->product_price, 2) }}
            @endif</td>
            </tr>

            <tr>

              <td >&nbsp;</td>

              <td colspan="10" style="line-height:25px;" class="">

                  <p style="display: block;margin: 0 0 5px;color: #888;"><strong>Filename:</strong> {{ basename($item->selected_images) }} </p>

                <a href="https://fotovenderau.s3-ap-southeast-2.amazonaws.com/shadowsphotoprinting/july-23-2024-2024-07-23-14-33-51/original/img-3771-2-.jpg" target="_blank">Download image</a>


              </td>

            </tr>
            @endforeach
            <tr>
              <td>&nbsp;</td>
              <td colspan="10">
               <a href="{{ route('download-order-zip', ['order_id' => $orderDetail->id]) }}" class="download_zip_btn">download zip</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-lg-4 col-sm-5">
        </div>
        <div class="col-lg-4 col-sm-5"></div>
        <div class="col-lg-4 col-sm-5 ml-auto">
          <table class="table table-clear">
            <tbody>
              <tr>
                <td>
                  <strong>Subtotal</strong>
                </td>
                <td class="right">${{ number_format($OrderTotal['subtotal'],2) }}</td>
              </tr>
              @if(isset($OrderTotal['coupon_code']) && !empty($OrderTotal['coupon_code']) && $OrderTotal['coupon_code'] != null)
              <tr>
                <td>
                  <strong>Coupon ({{ $OrderTotal['coupon_code']['code'] }})</strong>
                </td>
                <td class="right">${{ number_format($OrderTotal['coupon_code']['discount_amount'],2) }}</td>
              </tr>
              @endif

              <tr>
                <td>
                  <strong>Shipping Charges</strong>
                </td>
                <td class="right">${{ number_format($OrderTotal['shippingCharge'],2) }}</td>
              </tr>
              <tr>
                <td>
                  <strong>Total</strong>
                </td>
                <td class="right">
                  <strong>${{ number_format($OrderTotal['total'],2) }}</strong>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      </div>

    </div>
    <div class="bottom_refund_btn_class">
      <div class="fefund_btn">
                <a href="">refund</a>
      </div>

      <div class="refund_paragraph">
        <p>This order is no longer editable.</p>

      </div>

     </div>
    </div>
    </div>

  </div>
@endsection





