@extends('admin.layout.main')
@section('page-content')
@php
   $CartService = app(App\Services\CartService::class);

  // function getS3Img($str, $size){
  //   $str = str_replace('original', $size, $str);
  //   return $str;
  // }
@endphp
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orders-list') }}">Orders</a></li>
          <li class="breadcrumb-item"><a href="#">Order Detail</a></li>
        </ol>
      </nav> 

    @if(Session::has('success'))
      <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif

    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="order-address">
            <div class="top_row_order_address">
                  <div class="row">
                    <div class="col-12">
                      <div class="upper_header">
                        <div class="order_detail_heading">
                          @php
                          $status = '';
                            if($orderDetail->order_status == "0") {
                              $status = "Processing";
                            }elseif($orderDetail->order_status == "1"){
                              $status = "Completed";
                            }elseif($orderDetail->order_status == "2"){
                              $status = "Cancelled";
                            }elseif($orderDetail->order_status == "3"){
                              $status = "Refunded";
                            }elseif($orderDetail->order_status == "4"){
                              $status = "On Hold";
                            }
                          @endphp
                        <h3>Order #{{$orderDetail->order_number}} details</h3>
                        <span class="status_paragraph completed_clr p-1">{{ $status }}</span>
                      </div>
                        <div class="print_btn">
                          <a href="javascript:void()" id="print">Print</a>
                        </div>


                      </div>
                      @if($orderDetail->payment_method != 'afterPay')
                        <div class="lower_header">
                          <p>Payment via Credit Card (Stripe) ( <a href="{{ env('STRIPE_URL').$orderDetail->payment_id }}" target="_blank">{{ $orderDetail->payment_id }}</a>). Paid on {{ date('d F Y H:s A',strtotime($orderDetail->created_at)) }}</p>
                        </div>
                      @else
                        <div class="lower_header">
                          <p>Payment via AfterPay ( <a href="#">{{ $orderDetail->payment_id }}</a>). Paid on {{ date('d F Y H:s A',strtotime($orderDetail->created_at)) }}</p>
                        </div>
                      @endif
                    </div>
                  </div>
            </div>
            <div class="billing_adress_row g-5">
              <div class="col-md-4">
                <div class="gerneral_billing_details">
                  <h4 class="mb-3">General</h4>

                  <div class="main_input_div">
                <label for="">Date created:</label>
                <p>{{ date('Y-m-d',strtotime($orderDetail->created_at)) }}</p>
                  </div>


                  <div class="main_input_div main_div_status">
                    <label for="">Status : </label>
                    <select class="form-select form-control" aria-label="Default select example" id="order-status">
                      <option value="0" {{ ($orderDetail->order_status == "0") ? 'selected' : ''}}>Processing</option>
                      <option value="1" {{ ($orderDetail->order_status == "1") ? 'selected' : ''}}>Completed</option>
                      <option value="2"{{ ($orderDetail->order_status == "2") ? 'selected' : ''}}>Cancelled</option>
                      <option value="3"{{ ($orderDetail->order_status == "3") ? 'selected' : ''}}>Refunded</option>
                      <option value="4"{{ ($orderDetail->order_status == "4") ? 'selected' : ''}}>On Hold</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="order-address-details">
                    <h4 class="mb-3">Billing details</h4>
                    <div class="address">
                      <p>{{ Str::ucfirst($orderDetail->orderBillingShippingDetails['fname'] ?? '') }} {{ Str::ucfirst($orderDetail->orderBillingShippingDetails['lname'] ?? '') }}<br>{{ $orderDetail->orderBillingShippingDetails['street1'] ?? ''}}<br>{{ $orderDetail->orderBillingShippingDetails['street2'] ?? ''}} {{ Str::ucfirst($orderDetail->orderBillingShippingDetails['suburb'] ?? '') }} {{ Str::ucfirst($orderDetail->orderBillingShippingDetails['state'] ?? '') }} {{ $orderDetail->orderBillingShippingDetails['postcode'] ?? ''}}</p>
                      <p><strong>Email address:</strong> <br><a href="mailto:{{ $orderDetail->orderBillingShippingDetails['email'] ?? ''}}">{{ $orderDetail->orderBillingShippingDetails['email'] ?? ''}}</a></p>
                      <p><strong>Phone:</strong><br> <a href="tel:{{ $orderDetail->orderBillingShippingDetails['phone'] ?? ''}}">{{ $orderDetail->orderBillingShippingDetails['phone'] ?? ''}}</a></p>
                    </div>
                </div>
              </div>
              <div class="col-md-4">
                @if($orderDetail->isShippingAddress)
                    <div class="order-address-details" id="Shipping-address">
                        <h4 class="mb-3">Shipping details</h4>
                        <div class="address">
                            <p>{{ $orderDetail->orderBillingShippingDetails['ship_fname'] }}  {{Str::ucfirst($orderDetail->orderBillingShippingDetails['ship_lname'])}} <br>{{$orderDetail->orderBillingShippingDetails['ship_street1']}}<br>{{$orderDetail->orderBillingShippingDetails['ship_street2']}} {{Str::ucfirst($orderDetail->orderBillingShippingDetails['ship_suburb'])}} {{Str::ucfirst($orderDetail->orderBillingShippingDetails['ship_state'])}} {{$orderDetail->orderBillingShippingDetails['ship_postcode']}}</p><p class="order_note"><strong>Customer provided note:<br></strong> {{$orderDetail->orderBillingShippingDetails['order_comments']}}</p></div>
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

      @php
        // Group order details by packages
        $package_groups = [];
        $normal_items = [];
        $has_package_items = false;
        $has_non_package_items = false;
        
        // Load wedding packages JSON to get package names
        $weddingPackages = null;
        try {
            $weddingPackagesPath = resource_path('pages_json/wedding_packages.json');
            if (file_exists($weddingPackagesPath)) {
                $weddingPackages = json_decode(file_get_contents($weddingPackagesPath), true);
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        foreach ($orderDetail->orderDetails as $item) {
            $is_package = isset($item->is_package) && !empty($item->is_package) && ($item->is_package == 1) ? 1 : 0;
            
            if($is_package == 1) {
                $has_package_items = true;
                $package_product_id = $item->package_product_id;
                if(!isset($package_groups[$package_product_id])) {
                    // Try to get package name from wedding packages JSON
                    $package_name = 'Package #' . $package_product_id;
                    if ($weddingPackages && isset($weddingPackages['packages'])) {
                        foreach ($weddingPackages['packages'] as $package) {
                            if (isset($package['id']) && $package['id'] == $package_product_id) {
                                $package_name = $package['name'];
                                break;
                            }
                        }
                    }
                    
                    $package_groups[$package_product_id] = [
                        'package_name' => $package_name,
                        'package_price' => $item->package_price ?? 0,
                        'items' => []
                    ];
                }
                $package_groups[$package_product_id]['items'][] = $item;
            } else {
                $has_non_package_items = true;
                $normal_items[] = $item;
            }
        }
      @endphp

      <div class="table-responsive-sm order-invoice-main">
        <table class="table">
          <thead class="table_head">
            <tr>
              <th class="center">Item</th>
              <th></th>
              @if($has_package_items && !$has_non_package_items)
                <th class="right">Items</th>
              @elseif($has_package_items && $has_non_package_items)
                <th class="right">Cost</th>
              @else
                <th class="right">Cost</th>
              @endif
              <th class="right">Sale</th>
              <th class="right">Sale Cost</th>
              <th class="center">Qty</th>
              <th class="right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($package_groups as $package_product_id => $package_group)
              <!-- Package Header -->
              <tr style="background-color: #e3f2fd; font-weight: bold; border-top: 3px solid #2196f3;">
                <td colspan="2" style="padding: 15px 10px; border-bottom: 2px solid #bbdefb;">
                  <i class="fas fa-box" style="color: #1976d2;"></i> 
                  <span style="color: #1976d2; font-size: 16px;">{{ $package_group['package_name'] }}</span>
                  <span style="color: #666; font-size: 14px; margin-left: 10px;">- ${{ number_format($package_group['package_price'], 2) }}</span>
                </td>
                <td colspan="5" style="padding: 15px 10px; border-bottom: 2px solid #bbdefb; text-align: right; color: #1976d2;">
                  <i class="fas fa-list"></i> Package Items
                </td>
              </tr>
              
              @foreach ($package_group['items'] as $key => $item)
            <?php 
              $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);
              $product_sale_price =  $CartService->getProductSalePrice($item->product_id);
              
              $photo_product_desc = '';
              $giftcard_product_desc = '';

              if($item->product_type == "photo_for_sale"){
                  $photo_product_desc = json_decode($item->product_desc);
              }
              if($item->product_type == "gift_card"){
                  $giftcard_product_desc = json_decode($item->product_desc);
              }
            ?>
            <tr>
                <td class="center order-img" data-title="image">
                                
                  @php
                      $image1 = '';
                      $image2 = '';
                      if(isset($product_detail->product_image)){
                          $imageArray = explode(',', $product_detail->product_image);
                          $image1 = $imageArray[0] ?? '';
                          $image2 = $imageArray[1] ?? '';
                      }
                  @endphp

                  <img src="
                      @if($item->product_type == 'gift_card')
                          {{ asset($product_detail->product_image) }}
                      @elseif($item->product_type == 'photo_for_sale')

                          {{ asset($image1) }}

                      @elseif($item->product_type == 'hand_craft')

                          {{ asset($image1) }}

                      @else
                          {{-- {{ getS3Img(asset($item->selected_images), 'medium') }} --}}
                          {{ getS3Img(asset($item->selected_images), 'raw') }}
                      @endif
                  " alt="">
                
                </td>
                <td class="strong order_page_td">
                  
                      @if($item->product_type == "gift_card")
                      <a href="{{ route('gift-card-show', ['category_id' => $product_detail->id]) }}">
                          {{ $product_detail->product_title }}
                          <p class="giftcard-message"><span class="gift-desc-heading">To: </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span class="gift-desc-heading"> From: </span><span> {{$giftcard_product_desc->from ?? ''}}</span><span class="gift-desc-heading"> Message: </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span></p>
                      </a>    
                      @elseif($item->product_type == "photo_for_sale")
                      <a href="{{ route('photos-for-sale-product-show', ['slug' => $product_detail->slug]) }}">
                          {{ $product_detail->product_title ?? '' }} - {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}
                      </a>    
                      @elseif($item->product_type == "hand_craft")  
                      <a href="{{ route('hand-craft-product-show', ['slug' => $product_detail->slug]) }}">
                        {{ $product_detail->product_title ?? '' }}  
                      </a>
                      @else
                      <a href="{{ route('product-show', ['slug' => $item->product->slug]) }}">
                        {{ $item->product->product_title ?? ''}}
                      </a>
                      @endif
                  
                  <div class="wc-order-item-sku"><strong>SKU:</strong> {{ $product_detail->slug ?? ''}}</div>
                  <p style="display: block;margin: 0 0 5px;color: #888;"><strong>Filename:</strong> {{ basename($item->selected_images) }} </p>

                  <a href="{{ str_contains($item->selected_images, 'amazonaws.com') ? $item->selected_images : asset($item->selected_images) }}" download>Download image</a>
              </td>


              <td class="right order_page_td" data-title="price">
                  <span class="">
                    @if(!$has_package_items)
                      <bdi>
                          <span>$</span>
                      {{-- @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                          {{ number_format($item->product_price, 2) }}
                      @else
                          {{ number_format($product_detail->product_price, 2) }}
                      @endif --}}
                        {{ number_format($item->product_price, 2) }}
                      </bdi>
                    @else
                          package item
                    @endif
                  </span>
              </td>
              <td>
                  @php
                  $sale_status = "";
                  $sale_price = "";
                    if(isset($item->sale_on) && !empty($item->sale_on)){
                      $sale_status = 'On';
                      $sale_price = $item->sale_price;
                    }else{
                      $sale_status = 'Off';
                      $sale_price = '-';
                    }
                  @endphp

                  {{$sale_status}}

              </td>
              <td>{{$sale_price}}</td>

              <td class="center order_page_td" data-title="qty">{{ $item->quantity }}</td>
              <td class="right order_page_td" data-title="total">
                @if(!$has_package_items)
                $ 
                  @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                      {{ number_format($item->quantity * $item->product_price, 2) }}
                  @else

                      {{ isset($product_sale_price) && !empty($product_sale_price) ? number_format($item->quantity * $product_sale_price, 2) : number_format($item->quantity * $item->product_price, 2) }}
                      
                  @endif

                @else
                
                @endif
              </td>
            </tr>

              @endforeach
              
              <!-- Package Separator -->
              @if(!$loop->last)
                <tr style="height: 20px; background-color: #f8f9fa;">
                  <td colspan="7" style="border: none; padding: 0;"></td>
                </tr>
              @endif
            @endforeach
            
            @if($has_package_items && $has_non_package_items)
              <!-- Separator between packages and normal items -->
              <tr style="height: 30px; background-color: #f8f9fa;">
                <td colspan="7" style="border: none; padding: 0;"></td>
              </tr>
            @endif
            
            @foreach ($normal_items as $key => $item)
            <?php 
              $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type);
              $product_sale_price =  $CartService->getProductSalePrice($item->product_id);
              
              $photo_product_desc = '';
              $giftcard_product_desc = '';

              if($item->product_type == "photo_for_sale"){
                  $photo_product_desc = json_decode($item->product_desc);
              }
              if($item->product_type == "gift_card"){
                  $giftcard_product_desc = json_decode($item->product_desc);
              }
            ?>
            <tr>
                <td class="center order-img" data-title="image">
                                
                  @php
                      $image1 = '';
                      $image2 = '';
                      if(isset($product_detail->product_image)){
                          $imageArray = explode(',', $product_detail->product_image);
                          $image1 = $imageArray[0] ?? '';
                          $image2 = $imageArray[1] ?? '';
                      }
                  @endphp

                  <img src="
                      @if($item->product_type == 'gift_card')
                          {{ asset($product_detail->product_image) }}
                      @elseif($item->product_type == 'photo_for_sale')

                          {{ asset($image1) }}

                      @elseif($item->product_type == 'hand_craft')

                          {{ asset($image1) }}

                      @else
                          {{-- {{ getS3Img(asset($item->selected_images), 'medium') }} --}}
                          {{ getS3Img(asset($item->selected_images), 'raw') }}
                      @endif
                  " alt="">
                
                </td>
                <td class="strong order_page_td">
                  
                      @if($item->product_type == "gift_card")
                      <a href="{{ route('gift-card-show', ['category_id' => $product_detail->id]) }}">
                          {{ $product_detail->product_title }}
                          <p class="giftcard-message"><span class="gift-desc-heading">To: </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span class="gift-desc-heading"> From: </span><span> {{$giftcard_product_desc->from ?? ''}}</span><span class="gift-desc-heading"> Message: </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span></p>
                      </a>    
                      @elseif($item->product_type == "photo_for_sale")
                      <a href="{{ route('photos-for-sale-product-show', ['slug' => $product_detail->slug]) }}">
                          {{ $product_detail->product_title ?? '' }} - {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}
                      </a>    
                      @elseif($item->product_type == "hand_craft")  
                      <a href="{{ route('hand-craft-product-show', ['slug' => $product_detail->slug]) }}">
                        {{ $product_detail->product_title ?? '' }}  
                      </a>
                      @else
                      <a href="{{ route('product-show', ['slug' => $item->product->slug]) }}">
                        {{ $item->product->product_title ?? ''}}
                      </a>
                      @endif
                  
                  <div class="wc-order-item-sku"><strong>SKU:</strong> {{ $product_detail->slug ?? ''}}</div>
                  <p style="display: block;margin: 0 0 5px;color: #888;"><strong>Filename:</strong> {{ basename($item->selected_images) }} </p>

                  <a href="{{ str_contains($item->selected_images, 'amazonaws.com') ? $item->selected_images : asset($item->selected_images) }}" download>Download image</a>
              </td>


              <td class="right order_page_td" data-title="price">
                  <span class="">
                      <bdi>
                          <span>$</span>
                      {{-- @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                          {{ number_format($item->product_price, 2) }}
                      @else
                          {{ number_format($product_detail->product_price, 2) }}
                      @endif --}}
                        {{ number_format($item->product_price, 2) }}
                      </bdi>
                  </span>
              </td>
              <td>
                  @php
                  $sale_status = "";
                  $sale_price = "";
                    if(isset($item->sale_on) && !empty($item->sale_on)){
                      $sale_status = 'On';
                      $sale_price = $item->sale_price;
                    }else{
                      $sale_status = 'Off';
                      $sale_price = '-';
                    }
                  @endphp

                  {{$sale_status}}

              </td>
              <td>{{$sale_price}}</td>

              <td class="center order_page_td" data-title="qty">{{ $item->quantity }}</td>
              <td class="right order_page_td" data-title="total">$ 
                  @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                      {{ number_format($item->quantity * $item->product_price, 2) }}
                  @else

                      {{ isset($product_sale_price) && !empty($product_sale_price) ? number_format($item->quantity * $product_sale_price, 2) : number_format($item->quantity * $item->product_price, 2) }}
                      
                  @endif
              </td>
            </tr>
            @endforeach
            
            <tr class="download_zip_tr">
              <td>
                  @if(isset($orderDetail->orderBillingShippingDetails['order_notes']) && !empty($orderDetail->orderBillingShippingDetails['order_notes']))
                    <h2>Order Note</h2>
                    <p>{{$orderDetail->orderBillingShippingDetails['order_notes'] ?? ''}}</p>
                  @endif
                    <a href="#addNotes" data-toggle="modal" class="btn btn-primary add-notes">Add note</a>
              </td>
              <td colspan="10" class="download-zip-cls">
               <a href="{{ route('download-order-zip', ['order_id' => $orderDetail->id]) }}" class="download_zip_btn">download zip</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="zaya">
        <table style=" border-collapse: collapse;  padding: 20px; width: 100%; max-width: 387px;">
           <table class="wc-order-totals">
            <tr>
                <td style="padding: 5px;">Items Subtotal:</td>
                <td style="text-align: right; padding: 5px;"><strong>${{ number_format($orderDetail->sub_total,2) }}</strong></td>
            </tr>

            @if(isset($OrderTotal['coupon_code']) && !empty($OrderTotal['coupon_code']) && $OrderTotal['coupon_code'] != null)

              <tr>
                <td style="padding: 5px;">Coupon ({{ $OrderTotal['coupon_code']}}):</td>
                <td style="text-align: right; padding: 5px;"><strong>${{ number_format($OrderTotal['coupon_discount'],2)}}</strong></td>
              </tr>

            @endif 

            @if($orderDetail->order_type != '1')

            <tr>
                <td style="padding: 5px;">Shipping:</td>
                <td style="text-align: right; padding: 5px;"><strong>${{ number_format($OrderTotal['shippingCharge'],2) }}</strong></td>
            </tr>

            @if($orderDetail->shipping_service)
                <tr>
                    <td style="padding: 5px; padding-left: 20px; font-size: 12px; color: #666;">
                        Service: {{ ucwords(str_replace('_', ' ', $orderDetail->shipping_service)) }}
                    </td>
                    <td style="text-align: right; padding: 5px; font-size: 12px; color: #666;">
                        <strong>{{ ucwords($orderDetail->shipping_carrier ?? 'Australia Post') }}</strong>
                    </td>
                </tr>
            @endif

            @else

            <tr>
              <td style="padding: 5px;">Pickup (Free Shipping):</td>
              <td style="text-align: right; padding: 5px;"><strong>-</strong></td>
            </tr>

            @endif
            <tr>
                <td style="padding: 5px;">Order Total:</td>
                <td style="text-align: right; padding: 5px;"><strong>${{ number_format($orderDetail->total,2) }}</strong></td>
            </tr>
            
            <!-- Shipping Service Information -->
            @if($orderDetail->shipping_service)
                <tr>
                    <td colspan="2" style="padding: 5px; padding-left: 20px; font-size: 12px; color: #666; border-top: 1px solid #eee;">
                        <strong>Shipping Service:</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px; padding-left: 30px; font-size: 12px; color: #666;">
                        @if($orderDetail->hasExpressShipping())
                            🚀 <strong>Express Shipping</strong>
                        @elseif($orderDetail->hasSnailMailShipping())
                            🐌 <strong>Snail Mail</strong>
                        @else
                            📦 <strong>{{ ucwords(str_replace('_', ' ', $orderDetail->shipping_service)) }}</strong>
                        @endif
                    </td>
                    <td style="text-align: right; padding: 5px; font-size: 12px; color: #666;">
                        @if($orderDetail->shipping_carrier)
                            <strong>{{ $orderDetail->shipping_carrier }}</strong>
                        @else
                            <strong>Australia Post</strong>
                        @endif
                    </td>
                </tr>
                
                @if($orderDetail->shipping_breakdown && is_array($orderDetail->shipping_breakdown))
                    @php
                        $shippingInfo = $orderDetail->getShippingServiceInfo();
                    @endphp
                    
                    @if(isset($shippingInfo['breakdown']) && !empty($shippingInfo['breakdown']))
                        <tr>
                            <td colspan="2" style="padding: 5px; padding-left: 20px; font-size: 12px; color: #666; border-top: 1px solid #eee;">
                                <strong>Shipping Breakdown:</strong>
                            </td>
                        </tr>
                        @foreach($shippingInfo['breakdown'] as $breakdown)
                            <tr>
                                <td style="padding: 5px; padding-left: 30px; font-size: 11px; color: #888;">
                                    • {{ $breakdown['category'] }} - {{ $breakdown['service'] }}
                                </td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; color: #888;">
                                    <strong>${{ $breakdown['price'] }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            @endif
            
            <tr>
                <td colspan="2" style="border-bottom: 1px solid #ccc; padding: 5px;"></td>
            </tr>
           </table>
           <div class="clear"></div>
            <table class="wc-order-totals">
                <tr>
                    <td style="padding: 5px;"><strong>Paid:</strong></td>
                    <td style="text-align: right; padding: 5px;"><strong>${{ number_format($orderDetail->total,2) }}</strong></td>
                </tr>
                <tr>
                      <td colspan="" style="padding: 5px;">{{ date('F j, Y', strtotime($orderDetail->created_at ?? '')) }}
                      @if($orderDetail->payment_method != 'afterPay')  via Credit Card (Stripe) @else via AfterPay @endif
                      </td>
                    <td></td>
                </tr>
            </table>
            <div class="clear"></div>
            <table class="wc-order-totals">
              @if($orderDetail->payment_method != 'afterPay')
                <tr>
                    <td style="padding: 5px;"> <i class="fa fa-question-circle" aria-hidden="true"></i>
                        Stripe Fee:</td>
                    <td style="text-align: right; padding: 5px;"><strong>-${{$stripe_fee}}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><i class="fa fa-question-circle" aria-hidden="true"></i> Stripe Payout:
                    </td>
                    <td style="text-align: right; padding: 5px;"><strong>${{(float)$orderDetail->total-$stripe_fee}}</strong></td>
                </tr>
              @endif  
                <tr>
                    <td style="padding: 5px;">Total cart count:</td>
                    <td style="text-align: right; padding: 5px;"><strong>{{$orderDetail->order_details_sum_quantity ?? 0}}</strong></td>
                </tr>
            </table>
            <div class="clear"></div>
        </table>
    </div>

      </div>

    </div>

    @if($orderDetail->total > 0)

    <div class="bottom_refund_btn_class">
       <div class="fefund_btn">
          <a href="{{ ($orderDetail->order_status != "3") ? route('refund-order',['order_id'=>$orderDetail->id]) : '#' }}" class="{{ (($orderDetail->order_status == "3")) ? 'not-allowed' : '' }}"  
            @if($orderDetail->order_status != "3") 
            onclick="return confirm('Are you sure!')"
            @endif
            
            >@if($orderDetail->order_status == "3") refunded @else refund @endif</a>
        </div>
        <div class="refund_paragraph">
          <p>This order is no longer editable.</p>
        </div>
     </div>

     @endif
    </div>
    </div>

  </div>

  <div id="addNotes" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add a note</h4>
                <button type="button" class="close" id="modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form id="addNotesForm" action="{{ route('add-note') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{$orderDetail->id}}">
                <div class="modal-body">
                    <textarea id="order-notes" name="order_notes" class="form-control" rows="4" placeholder="Enter your notes here...">{{ $orderDetail->orderBillingShippingDetails['order_notes']?? null }}</textarea>
                    <p id="notes-error"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit-note">Save</button>
                </div>
            </form>
        </div>
    </div>
  </div>


@endsection

@section('custom-script')
  <script>
     $("#order-status").on('change',function(){
        $.post("{{ route('update-order') }}",
        {
           order_id : "{{ $orderDetail->id }}",
           order_status: $(this).val(),
           "_token": "{{ csrf_token() }}"
        },
        function(data){
           console.log(data);
           location.reload();
        });
        // location.reload();
     })

     $("#print").on('click',function(){
        window.print();
     })

     $('#submit-note').on('click',function(){
       var order_notes = $('#order-notes').val();     
       if(order_notes==''){
          $('#notes-error').text('Please fill requird field.').css('color','red');
          return false;
       }else{
          $('#addNotesForm').submit();
       }   
     })
  </script>


@endsection


