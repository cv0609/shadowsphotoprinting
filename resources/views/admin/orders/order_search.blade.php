@foreach ($orders as $key => $order)
<tr>
    <td>{{ $key + 1 }}</td>
    <td> <a
            href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
    </td>
    <td>{{ $order->total }}</td>
    <td>{{ $order->status }}</td>
    <td>{{ date('d-m-Y h:i:d',strtotime($order->created_at)) }}</td>
</tr>
@endforeach
