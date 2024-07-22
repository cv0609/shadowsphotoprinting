@foreach ($orders as $key => $order)
<tr>
    <td>{{ $key + 1 }}</td>
    <td> <a
            href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
    </td>
    <td>{{ $order->total }}</td>
    <td>{{ $order->status }}</td>
    <td>{{ date('d-m-Y h:i:d',strtotime($order->created_at)) }}</td>
    <td>
        <a href="{{ route('download-order-zip', ['order_id' => $order->id]) }}" data-id="{{$order->id}}" class="order-zip">order_{{$order->id}}.zip</a>
    </td>
</tr>
@endforeach
