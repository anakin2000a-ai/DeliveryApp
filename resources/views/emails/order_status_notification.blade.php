<p>Hi {{ $order->customer->first_name }},</p>

<p>Your order <strong>#{{ $order->order_number }}</strong> status has been updated to <strong>{{ ucfirst($status) }}</strong>.</p>

@if($note)
<p>Note from admin: {{ $note }}</p>
@endif

<p>Thank you for using our service!</p>