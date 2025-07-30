@extends('front-end.layout.main')
@php
    $CartService = app(App\Services\CartService::class);       
    
    $status = '';
    if($orders->order_status == "0") {
        $status = "Processing";
    }elseif($orders->order_status == "1"){
        $status = "Completed";
    }elseif($orders->order_status == "2"){
        $status = "Cancelled";
    }elseif($orders->order_status == "3"){
        $status = "Refunded";
    }elseif($orders->order_status == "4"){
        $status = "On Hold";
    }

    $statusClass = match(strtolower($status)) {
        'processing' => 'badge-processing',
        'completed' => 'badge-completed',
        'cancelled' => 'badge-cancelled',
        default => 'badge-default',
    };
                        
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                @include('front-end.profile.component.account-sidebar')
                <div class="col-md-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Order #{{$orders->order_number ?? ''}} </h2>
                            <div class="notices-wrap">
                                <p>Order #{{$orders->order_number ?? ''}} was placed on {{date('F j, Y',strtotime($orders->created_at ?? ''))}} and is currently
                                    Processing.</p>
                                <p>
                                    Order status:
                                    <span class="order-status-badge {{ $statusClass }}">{{ $status }}</span>
                                </p>
                            </div>
                            <div class="order-details">
                                <div class="order-box">
                                    <h2>Order details </h2>
                                </div>

                                <table class="shop-details">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders->orderDetails as $item)

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
                                                @if($item->product_type == 'photo_for_sale')

                                                    <td>
                                                        <a
                                                            href="mothers-day-gift-card.html">{{ $product_detail->product_title ?? '' }}
                                                            <strong>×
                                                                {{ $item->quantity ?? '' }}</strong>
                                                        </a>
                                                        <ul class="top-real">
                                                            <li><strong>Size:</strong>
                                                                <p>{{$photo_product_desc->photo_for_sale_size  ?? ''}}</p></li>
                                                            <li><strong>Type:</strong>
                                                                <p>{{$photo_product_desc->photo_for_sale_type ?? ''}}</p></li>
                                                        </ul>
                                                    </td>


                                                @elseif($item->product_type == 'gift_card')

                                                <td>
                                                    <a
                                                        href="mothers-day-gift-card.html">{{ $product_detail->product_title ?? '' }}
                                                        <strong>×
                                                            {{ $item->quantity ?? '' }}</strong>
                                                    </a>
                                                    <ul
                                                        class="top-real">
                                                        <li>
                                                            <strong>To: 
                                                            </strong>
                                                            <p><a
                                                                    href="mailto:{{$giftcard_product_desc->reciept_email ?? ''}}">{{$giftcard_product_desc->reciept_email ?? ''}}</a></p>
                                                        </li>
                                                        <li><strong>From:</strong>
                                                            <p>{{$giftcard_product_desc->from ?? ''}}</p></li>
                                                        <li><strong>Message:</strong>
                                                            <p>{{$giftcard_product_desc->giftcard_msg ?? ''}}</p></li>
                                                        {{-- <li><strong>Delivery
                                                                Method:</strong>
                                                            <p>Mail to
                                                                recipient</p></li> --}}
                                                    </ul>
                                                </td>
                                            
                                                @elseif($item->product_type == 'hand_craft')

                                                <td><a href="#">{{ $product_detail->product_title ?? '' }} <strong>×
                                                    {{ $item->quantity ?? '' }}</strong></a></td>
                                                
                                                @else
                            
                                                <td><a href="#">{{ $item->product->product_title ?? '' }} <strong>×
                                                    {{ $item->quantity ?? '' }}</strong></a></td>
                                            
                                                @endif
                                           
                                                <td>
                                                    <span>${{$item->price ?? ''}}</span>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td> <span>${{ $orders->sub_total ?? 0 }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>${{ $orders->total ?? 0 }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="customer-details">
                                        <h2>Billing address</h2>

                                        <address>
                                            <span>  {{$orders->orderBillingShippingDetails->fname ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->lname ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->postcode ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->street1 ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->street2 ?? ''}}</span>

                                            <span> {{$orders->orderBillingShippingDetails->phone ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->suburb ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->state ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->company_name ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->country_region ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->order_comments ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->order_comments ?? ''}}</span>


                                            <p>{{$orders->orderBillingShippingDetails->email ?? ''}}</p>
                                        </address>
                                    </div>
                                </div>

                                @if($orders->orderBillingShippingDetails->isShippingAddress == 1)

                                <div class="col-lg-6">
                                    <div class="customer-details">
                                        <h2>Shipping address
                                        </h2>
                                        <address>
                                            <span> {{$orders->orderBillingShippingDetails->ship_fname ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_lname ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_company ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_street1 ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_street2 ?? ''}}</span>
                                            
                                            <span> {{$orders->orderBillingShippingDetails->ship_suburb ?? ''}}</span>
                                            <span>  {{$orders->orderBillingShippingDetails->ship_state ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_postcode ?? ''}}</span>
                                            <span> {{$orders->orderBillingShippingDetails->ship_country_region ?? ''}}</span>
                                        </address>
                                    </div>
                                </div>

                                @endif
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
        $('.fade-slider').slick({
            autoplay: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    </script>
@endsection
