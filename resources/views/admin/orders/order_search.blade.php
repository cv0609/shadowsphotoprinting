@foreach ($orders as $key => $order)

<tr>
  <td data-title="s_no">{{ $key + 1 }}</td>
  <td data-title="order-number"> <a
          href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
  </td>
  <td>{{ $order->orderBillingShippingDetails->fname ?? ''}}</td>
  <td data-title="created-at">{{ date('M d, Y',strtotime($order->created_at)) }}</td>

  <td class="status_td" data-title="status"> 
      <p class="@if($order->order_status == 0) alert alert-primary @elseif($order->order_status == 1) alert alert-info @elseif($order->order_status == 2) alert alert-danger @elseif($order->order_status == 3) alert alert-warning @endif"> @if($order->order_status == 0) Processing @elseif($order->order_status == 1) Completed @elseif($order->order_status == 2) Cancelled @elseif($order->order_status == 3) Refunded @endif</p>
  </td>

  <td class="billing_address column-billing_address" data-colname="Billing" data-title="billing-add">
      
      @php
      $addressParts = [];

      if (isset($order->orderBillingShippingDetails->street1)) {
          $addressParts[] = $order->orderBillingShippingDetails->street1;
      }

      if (isset($order->orderBillingShippingDetails->street2)) {
          $addressParts[] = $order->orderBillingShippingDetails->street2;
      }

      if (isset($order->orderBillingShippingDetails->postcode)) {
          $addressParts[] = $order->orderBillingShippingDetails->postcode;
      }

      if (isset($order->orderBillingShippingDetails->suburb)) {
          $addressParts[] = $order->orderBillingShippingDetails->suburb;
      }

      $addressLine1 = implode(', ', $addressParts);
      $addressParts = [];

      if (isset($order->orderBillingShippingDetails->state)) {
          $addressParts[] = $order->orderBillingShippingDetails->state;
      }

      if (isset($order->orderBillingShippingDetails->country_region)) {
          $addressParts[] = $order->orderBillingShippingDetails->country_region;
      }

      $addressLine2 = implode(', ', $addressParts);
  @endphp

  {{ $addressLine1 }}<br>
  {{ $addressLine2 }}

  </td>


  <td class="shipping_address column-shipping_address" data-colname="Ship to" data-title="shipp-add">
      <a target="_blank" href="https://maps.google.com/maps?&amp;q=1145%20Eyre%20Street%2C%20%2C%20Newington%2C%20VIC%2C%203350%2C%20AU&amp;z=16">
  
          @if($order->orderBillingShippingDetails->isShippingAddress == 1)
              @php
                  $shippingAddressParts1 = [];
                  $shippingAddressParts2 = [];
  
                  if (isset($order->orderBillingShippingDetails->ship_street1)) {
                      $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_street1;
                  }
  
                  if (isset($order->orderBillingShippingDetails->ship_street2)) {
                      $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_street2;
                  }
  
                  if (isset($order->orderBillingShippingDetails->ship_suburb)) {
                      $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_suburb;
                  }
  
                  if (isset($order->orderBillingShippingDetails->ship_state)) {
                      $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_state;
                  }
  
                  if (isset($order->orderBillingShippingDetails->ship_postcode)) {
                      $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_postcode;
                  }
  
                  if (isset($order->orderBillingShippingDetails->ship_country_region)) {
                      $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_country_region;
                  }
  
                  $shippingAddressLine1 = implode(', ', $shippingAddressParts1);
                  $shippingAddressLine2 = implode(', ', $shippingAddressParts2);
              @endphp
  
              {{ $shippingAddressLine1 }}<br>
              {{ $shippingAddressLine2 }}
          @else
              -
          @endif
  
      </a>
  </td>
  

  <td data-title="total">{{ $order->total }}</td>
  
  <td data-title="zip">
      <a href="{{ route('download-order-zip', ['order_id' => $order->id]) }}" data-id="{{$order->id}}" class="order-zip">order_{{$order->order_number}}.zip</a>
  </td>
</tr>
@endforeach