@extends('admin.layout.main')
@section('page-content')
@php
   $CartService = app(App\Services\CartService::class);

  // function getS3Img($str, $size){
  //   $str = str_replace('original', $size, $str);
  //   return $str;
  // }
@endphp
<style>
  .order-meta-panel {
    display: flex;
    width: 100%;
    margin-top: 8px;
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 12px;
    overflow: hidden;
  }

  .order-meta-col {
    flex: 1;
    min-width: 0;
    padding: 22px 24px;
  }

  .order-meta-col + .order-meta-col {
    border-left: 1px solid #e8edf2;
  }

  .order-meta-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 18px;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
  }

  .order-meta-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e8f1fb;
    color: #3b82f6;
    font-size: 14px;
    flex-shrink: 0;
  }

  .order-meta-field {
    margin-bottom: 16px;
  }

  .order-meta-field:last-child {
    margin-bottom: 0;
  }

  .order-meta-label {
    display: block;
    margin: 0 0 6px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.01em;
  }

  .order-meta-value {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    line-height: 1.45;
  }

  .order-meta-divider {
    height: 1px;
    margin: 16px 0;
    background: #e8edf2;
  }

  .shopping-country-card {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #dfe7ee;
    border-radius: 10px;
    background: #f8fafc;
  }

  .shopping-country-card.is-nz {
    border-color: #b7ddd5;
    background: #f3faf8;
  }

  .shopping-country-card.is-au {
    border-color: #c9d8e8;
    background: #f5f8fb;
  }

  .shopping-country-code-lg {
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
    color: #1f2937;
    letter-spacing: 0.02em;
  }

  .shopping-country-text {
    min-width: 0;
  }

  .shopping-country-name {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
  }

  .shopping-country-card.is-nz .shopping-country-name {
    color: #0f766e;
  }

  .shopping-country-card.is-au .shopping-country-name {
    color: #1d4f7c;
  }

  .shopping-country-pill {
    display: inline-block;
    margin-top: 5px;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.03em;
    color: #475569;
    background: #e2e8f0;
  }

  .shopping-country-card.is-nz .shopping-country-pill {
    color: #0f766e;
    background: #d1fae5;
  }

  .shopping-country-card.is-au .shopping-country-pill {
    color: #1d4f7c;
    background: #dbeafe;
  }

  .order-meta-status select#order-status {
    width: 100%;
    max-width: 100%;
    height: 38px;
    margin: 0;
    padding: 6px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px !important;
    background: #fff;
    color: #111827;
    font-size: 14px;
  }

  .order-meta-address {
    margin: 0 0 14px;
    font-size: 14px;
    color: #334155;
    line-height: 1.55;
  }

  .order-meta-contact-label {
    display: block;
    margin: 0 0 4px;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
  }

  .order-meta-contact-value {
    display: block;
    margin: 0 0 12px;
    font-size: 14px;
    color: #2563eb;
    word-break: break-word;
  }

  .order-meta-muted {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    line-height: 1.5;
  }

  .order-payment-line {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px 10px;
    margin: 10px 0 0;
    font-size: 13px;
    color: #64748b;
    line-height: 1.4;
  }

  .order-payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.02em;
    line-height: 1.2;
    border: 1px solid transparent;
  }

  .order-payment-badge i {
    font-size: 12px;
  }

  .order-payment-badge.is-stripe {
    color: #1d4ed8;
    background: #eff6ff;
    border-color: #bfdbfe;
  }

  .order-payment-badge.is-afterpay {
    color: #065f46;
    background: #ecfdf5;
    border-color: #a7f3d0;
  }

  .order-payment-badge.is-other {
    color: #475569;
    background: #f1f5f9;
    border-color: #cbd5e1;
  }

  .order-payment-id {
    color: #2563eb;
    font-weight: 600;
    word-break: break-all;
  }

  .order-payment-meta {
    color: #64748b;
  }

  @media (max-width: 991px) {
    .order-meta-panel {
      flex-direction: column;
    }

    .order-meta-col + .order-meta-col {
      border-left: 0;
      border-top: 1px solid #e8edf2;
    }
  }
</style>
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
                      @php
                        $isAfterPay = $orderDetail->payment_method === 'afterPay';
                        $paidAt = date('j F Y, g:i A', strtotime($orderDetail->created_at));
                      @endphp
                      <div class="lower_header order-payment-line">
                        <span>Payment via</span>
                        @if($isAfterPay)
                          <span class="order-payment-badge is-afterpay">
                            <i class="fas fa-wallet" aria-hidden="true"></i>
                            AfterPay
                          </span>
                          @if(!empty($orderDetail->payment_id))
                            <span class="order-payment-meta">(<a class="order-payment-id" href="#">{{ $orderDetail->payment_id }}</a>)</span>
                          @endif
                        @elseif($orderDetail->payment_method === 'free')
                          <span class="order-payment-badge is-other">
                            <i class="fas fa-gift" aria-hidden="true"></i>
                            Free Order
                          </span>
                        @else
                          <span class="order-payment-badge is-stripe">
                            <i class="fas fa-credit-card" aria-hidden="true"></i>
                            Credit Card (Stripe)
                          </span>
                          @if(!empty($orderDetail->payment_id))
                            <span class="order-payment-meta">
                              (<a class="order-payment-id" href="{{ env('STRIPE_URL').$orderDetail->payment_id }}" target="_blank">{{ $orderDetail->payment_id }}</a>)
                            </span>
                          @endif
                        @endif
                        <span class="order-payment-meta">· Paid on {{ $paidAt }}</span>
                      </div>
                    </div>
                  </div>
            </div>
              @php
              $shoppingCountryName = optional($orderDetail->shoppingCountry)->name ?? 'Not recorded';
              $shoppingCountryCode = strtoupper((string) (optional($orderDetail->shoppingCountry)->code ?? ''));
              $isNzOrder = $shoppingCountryCode === 'NZ';
              $isAuOrder = $shoppingCountryCode === 'AU';
              $billing = $orderDetail->orderBillingShippingDetails;
            @endphp

            <div class="order-meta-panel billing_adress_row">
              <div class="order-meta-col">
                <h4 class="order-meta-heading">
                  <span class="order-meta-icon"><i class="fas fa-file-alt"></i></span>
                  General
                </h4>

                <div class="order-meta-field">
                  <span class="order-meta-label">Date Created</span>
                  <p class="order-meta-value">{{ date('j F Y, g:i A', strtotime($orderDetail->created_at)) }}</p>
                </div>

                <div class="order-meta-divider"></div>

                <div class="order-meta-field">
                  <span class="order-meta-label">Shopping Country</span>
                  <div class="shopping-country-card {{ $isNzOrder ? 'is-nz' : ($isAuOrder ? 'is-au' : '') }}">
                    <span class="shopping-country-code-lg">{{ $shoppingCountryCode !== '' ? $shoppingCountryCode : '—' }}</span>
                    <div class="shopping-country-text">
                      <span class="shopping-country-name">{{ $shoppingCountryName }}</span>
                      @if($shoppingCountryCode !== '')
                        <span class="shopping-country-pill">{{ $shoppingCountryCode }}</span>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="order-meta-field order-meta-status main_div_status">
                  <span class="order-meta-label">Status</span>
                  <select class="form-select form-control" aria-label="Order status" id="order-status">
                    <option value="0" {{ ($orderDetail->order_status == "0") ? 'selected' : ''}}>Processing</option>
                    <option value="1" {{ ($orderDetail->order_status == "1") ? 'selected' : ''}}>Completed</option>
                    <option value="2"{{ ($orderDetail->order_status == "2") ? 'selected' : ''}}>Cancelled</option>
                    <option value="3"{{ ($orderDetail->order_status == "3") ? 'selected' : ''}}>Refunded</option>
                    <option value="4"{{ ($orderDetail->order_status == "4") ? 'selected' : ''}}>On Hold</option>
                  </select>
                </div>
              </div>

              <div class="order-meta-col">
                <h4 class="order-meta-heading">
                  <span class="order-meta-icon"><i class="fas fa-user"></i></span>
                  Billing details
                </h4>
                <p class="order-meta-address">
                  {{ Str::ucfirst($billing['fname'] ?? '') }} {{ Str::ucfirst($billing['lname'] ?? '') }}<br>
                  {{ $billing['street1'] ?? '' }}<br>
                  {{ $billing['street2'] ?? '' }} {{ Str::ucfirst($billing['suburb'] ?? '') }} {{ Str::ucfirst($billing['state'] ?? '') }} {{ $billing['postcode'] ?? '' }}
                </p>
                <span class="order-meta-contact-label">Email address</span>
                <a class="order-meta-contact-value" href="mailto:{{ $billing['email'] ?? '' }}">{{ $billing['email'] ?? '' }}</a>
                <span class="order-meta-contact-label">Phone</span>
                <a class="order-meta-contact-value" href="tel:{{ $billing['phone'] ?? '' }}">{{ $billing['phone'] ?? '' }}</a>
              </div>

              <div class="order-meta-col">
                <h4 class="order-meta-heading">
                  <span class="order-meta-icon"><i class="fas fa-truck"></i></span>
                  Shipping details
                </h4>
                @if(!empty($billing['isShippingAddress']) && (int) $billing['isShippingAddress'] === 1)
                  <div id="Shipping-address">
                    <p class="order-meta-address">
                      {{ $billing['ship_fname'] ?? '' }} {{ Str::ucfirst($billing['ship_lname'] ?? '') }}<br>
                      {{ $billing['ship_street1'] ?? '' }}<br>
                      {{ $billing['ship_street2'] ?? '' }} {{ Str::ucfirst($billing['ship_suburb'] ?? '') }} {{ Str::ucfirst($billing['ship_state'] ?? '') }} {{ $billing['ship_postcode'] ?? '' }}
                    </p>
                    @if(!empty($billing['order_comments']))
                      <span class="order-meta-contact-label">Customer provided note</span>
                      <p class="order-meta-muted">{{ $billing['order_comments'] }}</p>
                    @endif
                  </div>
                @else
                  <p class="order-meta-muted">The shipping address is the same as the billing address.</p>
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

            @if(!empty($orderDetail->coupon_code))

              <tr>
                <td style="padding: 5px;">Coupon ({{ $orderDetail->coupon_code }}):</td>
                <td style="text-align: right; padding: 5px;"><strong>-${{ number_format($orderDetail->discount, 2) }}</strong></td>
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
                        <strong>{{ $orderDetail->getShippingCarrierDisplayName() }}</strong>
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
                        <strong>{{ $orderDetail->getShippingCarrierDisplayName() }}</strong>
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
                      <td colspan="" style="padding: 5px;">
                        {{ date('j F Y', strtotime($orderDetail->created_at ?? '')) }}
                        @if($orderDetail->payment_method === 'afterPay')
                          via <span class="order-payment-badge is-afterpay" style="vertical-align: middle;">AfterPay</span>
                        @elseif($orderDetail->payment_method === 'free')
                          via <span class="order-payment-badge is-other" style="vertical-align: middle;">Free Order</span>
                        @else
                          via <span class="order-payment-badge is-stripe" style="vertical-align: middle;">Credit Card (Stripe)</span>
                        @endif
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


