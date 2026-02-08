<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation - #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
        }

        .order-details {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order!</p>
        </div>

        <div class="order-info">
            <h2>Order #{{ $order->id }}</h2>
            <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Phone:</strong> {{ $order->number }}</p>
            <p><strong>Address:</strong> {{ $order->address }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment }}</p>
        </div>

        <div class="order-details">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                        <tr>
                            <td>{{ $item->article_name }}</td>
                            <td>
                                @if(!empty($item->size) && is_array(json_decode($item->size, true)))
                                    @foreach(json_decode($item->size, true) as $size)
                                        <span class="badge bg-secondary me-1">{{ strtoupper($size) }}</span>
                                    @endforeach
                                @else
                                    {{ is_string($item->size) ? $item->size : 'One Size' }}
                                @endif
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Subtotal:</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="total">Total:</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            <p>If you have any questions about your order, please contact our customer service.</p>
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>

</html>