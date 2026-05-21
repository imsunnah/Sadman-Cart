<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #1a202c; }
        .wrapper { max-width: 620px; margin: 30px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #003366 0%, #0055a5 100%); padding: 40px 40px 30px; text-align: center; }
        .header .badge { display: inline-block; background: #FF6600; color: #fff; font-size: 10px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; padding: 6px 14px; border-radius: 20px; margin-bottom: 16px; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: rgba(255,255,255,0.7); font-size: 13px; margin-top: 6px; }
        .order-id-banner { background: #FF6600; color: #fff; text-align: center; padding: 14px; font-size: 18px; font-weight: 800; letter-spacing: 2px; }
        .body { padding: 36px 40px; }
        .section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: #9b9fa8; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 2px solid #f0f4f8; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px; }
        .info-card { background: #f8fafc; border: 1px solid #e8edf2; border-radius: 12px; padding: 14px 16px; }
        .info-card .label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #9b9fa8; margin-bottom: 4px; }
        .info-card .value { font-size: 13px; font-weight: 700; color: #1a202c; }
        .full-width { grid-column: 1 / -1; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
        .items-table thead tr { background: #003366; }
        .items-table thead th { color: #fff; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 10px 14px; text-align: left; }
        .items-table tbody tr { border-bottom: 1px solid #f0f4f8; }
        .items-table tbody tr:last-child { border-bottom: none; }
        .items-table tbody td { padding: 12px 14px; font-size: 13px; font-weight: 600; color: #374151; }
        .items-table tbody td.qty { font-weight: 800; color: #FF6600; }
        .items-table tbody td.price { font-weight: 800; color: #003366; }
        .totals { background: #f8fafc; border-radius: 12px; border: 1px solid #e8edf2; padding: 18px 20px; margin-bottom: 28px; }
        .totals-row { display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 8px; }
        .totals-row.grand { font-size: 16px; font-weight: 800; color: #003366; border-top: 2px solid #e8edf2; padding-top: 10px; margin-top: 4px; }
        .cta { text-align: center; margin-bottom: 28px; }
        .cta a { display: inline-block; background: linear-gradient(135deg, #003366, #0055a5); color: #fff; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; padding: 14px 32px; border-radius: 10px; text-decoration: none; }
        .footer { background: #f8fafc; border-top: 1px solid #e8edf2; padding: 20px 40px; text-align: center; }
        .footer p { font-size: 11px; color: #9b9fa8; line-height: 1.8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="badge">🔔 New Order Alert</div>
            <h1>New Order Received!</h1>
            <p>A customer just placed an order on your store.</p>
        </div>

        <div class="order-id-banner">
            ORDER #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
        </div>

        <div class="body">
            <!-- Customer Info -->
            <div class="section-title">👤 Customer Information</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="label">Customer Name</div>
                    <div class="value">{{ $order->customer_name }}</div>
                </div>
                <div class="info-card">
                    <div class="label">Phone</div>
                    <div class="value">{{ $order->customer_phone }}</div>
                </div>
                @if($order->customer_email)
                <div class="info-card full-width">
                    <div class="label">Email</div>
                    <div class="value">{{ $order->customer_email }}</div>
                </div>
                @endif
            </div>

            <!-- Location Info -->
            <div class="section-title">📍 Delivery Location</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="label">District</div>
                    <div class="value">{{ $order->district }}</div>
                </div>
                <div class="info-card">
                    <div class="label">Upazila</div>
                    <div class="value">{{ $order->upazila }}</div>
                </div>
                @if($order->village)
                <div class="info-card">
                    <div class="label">Village</div>
                    <div class="value">{{ $order->village }}</div>
                </div>
                @endif
                <div class="info-card {{ $order->village ? '' : 'full-width' }}">
                    <div class="label">Zone</div>
                    <div class="value">{{ $order->delivery_location }}</div>
                </div>
                <div class="info-card full-width">
                    <div class="label">Full Address</div>
                    <div class="value">{{ $order->shipping_address }}</div>
                </div>
            </div>

            <!-- Products -->
            <div class="section-title">📦 Ordered Products</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="price">৳{{ number_format($item->price, 0) }}</td>
                        <td class="qty">×{{ $item->quantity }}</td>
                        <td class="price">৳{{ number_format($item->price * $item->quantity, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals">
                <div class="totals-row">
                    <span>Items Subtotal:</span>
                    <span>৳{{ number_format($order->total_amount - $order->delivery_charge, 0) }}</span>
                </div>
                <div class="totals-row">
                    <span>Delivery Charge ({{ $order->delivery_location }}):</span>
                    <span>৳{{ number_format($order->delivery_charge, 0) }}</span>
                </div>
                <div class="totals-row grand">
                    <span>Total Payable:</span>
                    <span>৳{{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            @if($order->customer_remarks)
            <div class="section-title">💬 Customer Remarks</div>
            <div class="info-card full-width" style="margin-bottom:28px">
                <div class="value">{{ $order->customer_remarks }}</div>
            </div>
            @endif

            <!-- CTA -->
            <div class="cta">
                <a href="{{ url('/admin/orders/' . $order->id) }}">View Order in Admin Panel →</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from <strong>SadmanCart</strong>.<br>
            Order placed at: {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>
</html>
