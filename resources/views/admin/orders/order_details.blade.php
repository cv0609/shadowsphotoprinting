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
                          <p>Payment via Credit Card (Stripe) ( <a href="ch_3PfaajF2Zj7VtYCT00WsQekf">ch_3PfaajF2Zj7VtYCT00WsQekf</a>). Paid on July 23, 2024 @ 4:44 am. Customer IP: 175.38.83.75</p>
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
                <p>2024-7-23</p>
                  </div>


                  <div class="main_input_div">
                    <label for="">Status : </label>
                    <select class="form-select form-control" aria-label="Default select example">
                      <option selected>Processing</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>


               



                </div>
              </div>
              <div class="col-md-4">
                <div class="order-address-details">
                    <h4 class="mb-3">Billing details</h4>
                    <div class="address">
                      <p>Jenny Garley<br>1145 Eyre Street<br>Newington Victoria 3350</p>
                      <p><strong>Email address:</strong> <br><a href="mailto:jennygarley@hotmail.com">jennygarley@hotmail.com</a></p>
                      <p><strong>Phone:</strong><br> <a href="tel:0401731342">0401731342</a></p>		
                    </div>
                {{-- <ul class="m-0 list-unstyled">
                <li>
                  <h6>First name </h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['fname']) }}</p>
                </li>
                <li>
                  <h6>Last name</h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['lname']) }}</p>
                </li>
                <li>
                  <h6>Company name</h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['lname']) }}</p>
                </li>
                <li>
                  <h6>Country / Region</h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['lname']) }}</p>
                </li>
                <li>
                  <h6>Street address</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['street1'].','.$orderDetail->OrderBillingDetail['street2'] }}</p>
                </li>
                <li>
                  <h6>Suburb</h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['suburb']) }}</p>
                </li>
                <li>
                  <h6>State</h6>
                  <p>{{ Str::ucfirst($orderDetail->OrderBillingDetail['state']) }}</p>
                </li>
                <li>
                  <h6>Postcode</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['postcode'] }}</p>
                </li>
                <li>
                  <h6>Phone</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['phone'] }}</p>
                </li>
                <li>
                  <h6>Email address</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['email'] }}</p>
                </li>
                <li>
                  <h6>Account username</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['username'] }}</p>
                </li>
                <li>
                  <h6>Account password</h6>
                  <p>{{ $orderDetail->OrderBillingDetail['password'] }}</p>
                </li>
              </ul> --}}
                </div>

              </div>
              <div class="col-md-4">
                @if($orderDetail->isShippingAddress)
                    <div class="order-address-details" id="Shipping-address">
                        <h4 class="mb-3">Shipping details</h4>
                        <ul class="m-0 list-unstyled">
                            <li>
                            <h6>First name </h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_fname'])}} </p>
                            </li>
                            <li>
                            <h6>Last name</h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_lname'])}}</p>
                            </li>
                            <li>
                            <h6>Company name</h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_company'])}}</p>
                            </li>
                            <li>
                            <h6>Country / Region</h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_country_region'])}}</p>
                            </li>
                            <li>
                            <h6>Street address</h6>
                            <p>{{$orderDetail->OrderBillingDetail['ship_street1'].','.$orderDetail->OrderBillingDetail['ship_street2']}}</p>
                            </li>
                            <li>
                            <h6>Suburb</h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_suburb'])}}</p>
                            </li>
                            <li>
                            <h6>State</h6>
                            <p>{{Str::ucfirst($orderDetail->OrderBillingDetail['ship_state'])}}</p>
                            </li>
                            <li>
                            <h6>Postcode</h6>
                            <p>{{$orderDetail->OrderBillingDetail['ship_state']}}</p>
                            </li>
                            <li>
                            <h6>Order notes</h6>
                            <p>{{$orderDetail->OrderBillingDetail['order_comments']}}</p>
                            </li>
                        </ul>
                    </div>
                @else
                <div class="diffrent-address">
                  <h4 class="mb-3">Shipping details</h4>
                  <div class="address">
                    <p>Jenny Garley<br>1145 Eyre Street<br>Newington Victoria 3350</p><p class="order_note"><strong>Customer provided note:<br></strong> Leave on front verandah</p>						</div>
                  {{-- <p>The shipping address is the same as the billing address.</p> --}}
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
          <thead>
            <tr>
              <th class="center">#</th>
              <th>Product</th>
              <th class="right">Unit Cost</th>
              <th class="center">Qty</th>
              <th class="right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orderDetail->orderDetails as $key => $item)
            <?php $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);?>
            <tr>
              <td class="center">{{ $key + 1}}</td>
              <td class="strong">
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
                <div class="wc-order-item-sku"><strong>SKU:</strong> PE27</div> 
            </td>

      
            <td class="right">
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

              <td class="center">{{ $item->quantity }}</td>
              <td class="right">$  @if($item->product_type == "gift_card")
                {{ number_format($item->quantity * $item->product_price, 2) }}
            @elseif($item->product_type == "photo_for_sale")
                {{ number_format($item->quantity * $item->product_price, 2) }}
            @else
                {{ number_format($item->quantity * $item->product->product_price, 2) }}
            @endif</td>
            </tr>

            <tr>

              <td>&nbsp;</td>
        
              <td colspan="10" style="line-height:25px;">
        
                  <p style="display: block;margin: 0 0 5px;color: #888;"><strong>Filename:</strong> img-3771-2-.jpg</p>
        
                <a href="https://fotovenderau.s3-ap-southeast-2.amazonaws.com/shadowsphotoprinting/july-23-2024-2024-07-23-14-33-51/original/img-3771-2-.jpg" target="_blank">Download image</a>
        
                
              </td>
        
            </tr>
            @endforeach
            <tr>

              <td>&nbsp;</td>
      
              <td colspan="10">
      
                
               <a href="" class="download_zip_btn">download zip</a>
                
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





