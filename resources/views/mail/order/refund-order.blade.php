<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Refunded</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap"
        rel="stylesheet">
</head>

<body style="font-family: Roboto Serif, serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        <table width="100%" cellspacing="0">
            <tr style="background-color: #000; padding: 20px; text-align: center; color: #ffffff;background:black;">
                <td style="padding: 10px;">
                    <a href="{{env('SITE_DOMAIN')}}" target="_blank"> <img src="{{ env('SITE_DOMAIN') }}assets/images/logo.png" alt="Shadows Photo Printing"
                            style="max-width: 300px; width:100%;">
                    </a>
                </td>
            </tr>
            <tr style="background-color: #16a085; color: #ffffff;">
                <td
                    style="padding:36px 48px;display:block;text-align:center;padding-top:15px;padding-bottom:15px;padding-left:48px;padding-right:48px">
                    <h1 style="margin: 0;">Your order refunded successfully.</h1>
                </td>
            </tr>
            <tr>

                @php
                    use Carbon\Carbon;
                    $dateString = '2024-07-11 05:15:58';
                    $date = Carbon::parse($order->created_at ?? '');
                    $order_date = $date->format('F j, Y');
                @endphp

                <td style="padding: 0 30px;">
                    <p>Your order has been successfully refunded. Please find the details below for your reference:</p>
                    <p>We will invoice your company</p>

                    <h2 style="color: #16a085;">Order #{{$order->order_number ?? ''}} ({{$order_date ?? ''}})</h2>
                </td>
            </tr>
            <table style="padding: 0 30px;" cellspacing="0" width="100%">
                <tr>
                    <th
                        style="padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; color: #636363; text-align: left;">
                        Product</th>
                    <th
                        style="padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; color: #636363; text-align: left;">
                        Quantity</th>
                    <th
                        style="padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; color: #636363; text-align: left;">
                        Price</th>
                </tr>

                @php
                    $CartService = app(App\Services\CartService::class);
                @endphp
                @foreach($order->orderDetails as $item)
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

                    <td style="padding: 10px; border: 1px solid #ddd; color: #636363;">{{$product_detail->product_title ?? ''}}<br><strong style="color:#636363;">Size</strong>: {{$photo_product_desc->photo_for_sale_size  ?? ''}}<br><strong
                            style="color: #636363;">Type</strong>: {{$photo_product_desc->photo_for_sale_type ?? ''}}</td>

                    @elseif($item->product_type == 'gift_card')

                    <td style="padding: 10px; border: 1px solid #ddd; color: #636363;">{{ $product_detail->product_title ?? '' }}<br><strong style="color:#636363;">To</strong>: {{$giftcard_product_desc->reciept_email ?? ''}}<br><strong
                        style="color: #636363;">From</strong>: {{$giftcard_product_desc->from ?? ''}}<br>
                        <strong
                        style="color: #636363;">Message</strong>: {{$giftcard_product_desc->giftcard_msg ?? ''}}
                    </td>

                    @elseif($item->product_type == 'hand_craft')
                      <td style="padding: 10px; border: 1px solid #ddd; color: #636363;">{{ $product_detail->product_title ?? '' }}</td>
                    @else

                    <td style="padding: 10px; border: 1px solid #ddd; color: #636363;">{{ $item->product->product_title ?? '' }}</td>

                    @endif

                   
                    <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">{{$item->quantity}}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">
                        
                        @if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft")
                        {{ number_format($item->product_price, 2) ?? 0}}
                        @else
                            {{ isset($product_sale_price) && !empty($product_sale_price) ? number_format($product_sale_price, 2) : number_format($item->product->product_price, 2) }}
                        @endif
                    </td>
                </tr>
                    
                @endforeach


                <tr>
                    <th colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: left; color: #636363;">
                        Subtotal:</th>
                    <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">${{number_format($order->sub_total,2)}}</td>
                </tr>
                <tr>
                    <th colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: left; color: #636363;">
                        Shipping:</th>
                    <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">${{number_format($order->shipping_charge,2)}} via Flat rate</td>
                </tr>

                @if(isset($order->coupon_code) && !empty($order->coupon_code))

                    <tr>
                        <th colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: left; color: #636363;">
                            Discount:</th>
                        <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">-${{number_format($order->discount,2)}}</td>
                    </tr>

                @endif

                <tr>
                    <th colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: left; color: #636363;">
                        Payment method:</th>
                    <td style="padding: 10px; border: 1px solid #ddd;  color: #636363;">{{ucfirst($order->payment_method) ?? ''}}</td>
                </tr>
                <tr>
                    <th colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: left; color: #636363;">
                        Total:</th>
                    <td style="padding: 10px; border: 1px solid #ddd; color: #636363;">${{number_format($order->total,2)}}</td>
                </tr>
            </table>

            <table style="padding: 0 30px;" cellspacing="0" width="100%">
                <tr>
                    <td style="padding: 12px"> <h2 style="color: #16a085; margin:0;"> Billing address </h2></td>
                    <td style="padding: 12px"><h2 style="color: #16a085; margin:0;">Shipping address </h2></td>
                </tr>
            </table>

            <table style="padding: 0 30px;" cellspacing="12" width="100%">
                <tr>
                    <td  valign="top" style="width: 50%;  border: 1px solid #e5e5e5;">
                       
                        <table cellspacing="" width="100%" style="width: 100%; ">
                            <tr>
                                <td  valign="top" id="billing-cell"
                                    style=" padding: 12px; font-style: italic;  color: #8f8f8f; text-align: left; line-height: 26px;">
                                    {{$order->orderBillingShippingDetails->fname ?? ''}} <br>
                                    {{$order->orderBillingShippingDetails->lname ?? ''}} <br> {{$order->orderBillingShippingDetails->street1 ?? ''}} <br>{{$order->orderBillingShippingDetails->street2 ?? ''}}
                                    <br>{{$order->orderBillingShippingDetails->postcode ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->phone ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->suburb ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->state ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->company_name ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->country_region ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->order_comments ?? ''}}<br>
                                    <a href="mailto:{{$order->orderBillingShippingDetails->email ?? ''}}"
                                        style="color: #16a085; text-decoration: underline; font-weight: normal;">{{$order->orderBillingShippingDetails->email ?? ''}}</a>

                                </td>
                            </tr>

                        </table>
                    </td>
                    <td valign="top" style="width: 50%; border: 1px solid #e5e5e5;">
                        {{-- <h2 style="color: #16a085;">Shipping address </h2> --}}
                        <table cellspacing="0" width="100%" style="width: 100%; ">
                            <tr>
                                <td  valign="top" id="shipping-cell"
                                    style=" padding: 12px; font-style: italic;    color: #8f8f8f; text-align: left; line-height: 26px;">
                                    {{$order->orderBillingShippingDetails->ship_fname ?? ''}} <br>
                                    {{$order->orderBillingShippingDetails->ship_lname ?? ''}} <br> {{$order->orderBillingShippingDetails->ship_company ?? ''}} <br>{{$order->orderBillingShippingDetails->ship_street1 ?? ''}}
                                    <br>{{$order->orderBillingShippingDetails->ship_street2 ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->ship_suburb ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->ship_state ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->ship_postcode ?? ''}}<br>
                                    {{$order->orderBillingShippingDetails->ship_country_region ?? ''}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p style="font-size: 14px; color: #636363; margin-top: 40px; margin-bottom: 16px;">Thanks for
                            using <a style="color: #15c;"
                                href="https://shadowsphotoprinting.com.au/">shadowsphotoprinting.com.au!</a></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="color: #555; font-size: 12px; text-align: center; padding-bottom: 48px; padding-left: 48px; padding-top: 20px; padding-right: 48px;">
                        Shadows Photo Printing </td>
                </tr>
            </table>

        </table>
    </div>


    <script>
        // JavaScript to ensure both cells have the same height
        window.onload = function () {
            var billingCell = document.getElementById('billing-cell');
            var shippingCell = document.getElementById('shipping-cell');
    
            var billingHeight = billingCell.offsetHeight;
            var shippingHeight = shippingCell.offsetHeight;
    
            var maxHeight = Math.max(billingHeight, shippingHeight);
    
            billingCell.style.height = maxHeight + 'px';
            shippingCell.style.height = maxHeight + 'px';
        };
    </script>

</body>

</html>

