<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            color: #2ecc71;
            text-align: center;
        }
        h2, h3 {
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }
        .bg-info {
            background-color: #17a2b8;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Thank You for Placing Your Order!</h1>
    <h2>Your Order ID is: #{{ $order->id }}</h2>
    
    <p>Dear {{ $order->first_name }},</p>
    
    <p>Your order has been successfully placed. Here are the details:</p>

    <h2>Order Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th width="100">Price</th>
                <th width="100">Qty</th>                                        
                <th width="100">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>AED {{ number_format($item->price, 2) }}</td>                                        
                <td>{{ $item->qty }}</td>
                <td>AED {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
           
            <tr>
                <th colspan="3" class="text-end">Discount: {!! !empty($order->coupon_code) 
                    ? '<span class="badge bg-info">' . $order->coupon_code . '</span>' 
                    : '' !!}</th>
                <td>AED {{ number_format($order->discount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Subtotal:</th>
                <td>AED {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Shipping:</th>
                <td>AED {{ number_format($order->shipping, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Grand Total:</th>
                <td>AED {{ number_format($order->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p>We will notify you when your order is shipped.</p>
    
    <p>Thank you for choosing us!</p>

    <div class="footer">
        <p>If you have any questions, please contact our support team.</p>
        <p>&copy; {{ date('Y') }} ecommerce. All rights reserved.</p>
    </div>
</body>
</html>