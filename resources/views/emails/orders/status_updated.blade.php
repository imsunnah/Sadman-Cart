<x-mail::message>
# Order Status Updated

Hello {{ $order->customer_name }},

The status of your order **#{{ $order->id }}** has been updated to: **{{ ucfirst($order->status) }}**.

**Order Details:**
- Total Amount: ৳{{ number_format($order->total_amount, 2) }}
- Shipping Address: {{ $order->shipping_address }}

<x-mail::button :url="config('app.url')">
Visit Sadman Cart
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
