<h2>New Order Received!</h2>

<p>Order Number: <strong>{{ $order->order_number }}</strong></p>
<p>Customer: <strong>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</strong></p>
<p>Delivery Address: {{ $order->delivery_address }}</p>

<h4>Items:</h4>
<ul>
    @foreach($order->items as $item)
        <li>{{ $item->quantity }} x {{ $item->item_name }} ({{ $item->line_total }}$)</li>
    @endforeach
</ul>

<p><strong>Total: {{ $order->order_total }}$</strong></p>

<p>Please process this order as soon as possible.</p>