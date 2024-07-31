@extends('front-end.layout.main')
@php
    $CartService = app(App\Services\CartService::class);           
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                <div class="col-md-3">
                    <div class="kad-account">
                        <div class="kad-max">
                            <img src="images/max.png" alt>
                            <a href="#" class="kt-link-to-gravatar">
                                <i
                                    class="fa-solid fa-cloud-arrow-up"></i>
                                <span
                                    class="kt-profile-photo-text">Update
                                    Profile Photo </span>
                            </a>
                        </div>
                    </div>
                    <div class="MyAccount-navigation">
                        @include('front-end.component.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Order #{{$orders->order_number ?? ''}} </h2>
                            <div class="notices-wrap">
                                <p>Order #{{$orders->order_number ?? ''}} was placed on {{date('F j, Y',strtotime($orders->created_at ?? ''))}} and is currently
                                    Processing.</p>
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
                                            <td> <span>$0.00</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>$0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>

                            <div class="customer-details">
                                <h2>Billing address</h2>
                                <address>
                                    <span> developer dev</span>
                                    <span> test</span>
                                    <span> 7 Edward Bennett
                                        Drive</span>
                                    <span> gg</span>
                                    <span> Pendle Hill New South
                                        Wales 2145
                                    </span>
                                    <p>devavology12@gmail.com</p>
                                </address>
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
