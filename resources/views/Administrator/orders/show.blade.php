@extends('Administrator.layout.app')
@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{$order->id}}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <address>
                                    <strong>{{$order->first_name.' '.$order->last_name}}</strong><br>
                                    {{ $order->address}}<br>
                                    {{ $order->city}}, {{ $order->zip}} {{ $order->country->name}}<br>
                                    Phone: {{ $order->mobile}}<br>
                                    Email: {{ $order->email}}
                                </address>
                                </div>
                                
                                
                                
                                <div class="col-sm-4 invoice-col">
                                    <b>Invoice #007612</b><br>
                                    <br>
                                    <b>Order ID:</b> {{$order->id}}<br>
                                    <b>Total:</b> AED {{$order->grand_total}}<br>
                                    <b>Status:</b>
                                    <span class=" 
                                    @switch($order->status)
                                    @case('pending')
                                    text-warning
                                    @break
                                    @case('delivered')
                                    text-success
                                    @break
                                    @case('canceled')
                                    text-danger
                                    @break
                                    @case('shipped')
                                    text-info
                                    @break
                                    @default
                                    text-secondary
                                    @endswitch">
                                    {{ $order->status }}
                                    </span>
                                    <br>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="card-body table-responsive p-3">								
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>                                        
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderitems as $item)
                                        
                                    <tr>
                                        <td>{{ $item->name}}</td>
                                        <td>AED {{ number_format($item->price, 2)}}</td>                                        
                                        <td>{{ $item->qty}}</td>
                                        <td>AED {{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                   
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <td>AED {{ number_format($order->subtotal, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Discount:{!! !empty($order->coupon_code) 
                                            ? '<span class="badge bg-info">' . $order->coupon_code . '</span>' 
                                            : '' !!} </th>
                                        <td>AED {{ number_format($order->discount, 2)}}</td>
                                    </tr>

                                    
                                    <tr>
                                        <th colspan="3" class="text-end">Shipping:</th>
                                        <td>AED {{ $order->shipping}}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Grand Total:</th>
                                        <td>AED {{ $order->grand_total}}</td>
                                    </tr>
                                </tbody>
                            </table>								
                        </div>                            
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ ($order->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        
                            <div class="mb-3">
                                <input type="text" class="form-control" name="shipped_date" id="shipped_date" placeholder="Shipped Date" 
                                    value="{{ $order->estimated_delivery ?? '' }}"> <!-- If shipped_date is available, show it here -->
                                <p id="error-start_date" class="error-message text-danger"></p>
                            </div>
                        
                            <div class="mb-3">
                                <button class="btn btn-primary" id="update-status-btn">Update</button>
                            </div>
                            
                            <div id="status-message"></div> <!-- Message container -->
                        </div>
                        
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Send Invoice Email</h2>
                            <div class="mb-3">
                                <select name="email" id="email" class="form-control">
                                    <option value="0">Customer</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="send-invoice-btn">Send</button>
                            </div>
                            <div id="email-status-message"></div> <!-- Status message -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('customScript')
<script>
$(document).ready(function () {
    // Initialize flatpickr for the shipped_date input field
    flatpickr("#shipped_date", {
        dateFormat: "Y-m-d H:i:s", // Date and time format
        enableTime: true,          // Enable time picker
        time_24hr: false,         // 12-hour format (set true for 24-hour)
        defaultDate: "{{ $order->shipped_date ?? '' }}", // Pre-fill the input with existing shipped date if available
    });

    // Update order status
    $('#update-status-btn').on('click', function (e) {
        e.preventDefault(); // Prevent form submission

        var orderId = {{ $order->id }}; // Get the order ID
        var status = $('#status').val(); // Get the selected status
        var shippedDate = $('#shipped_date').val(); // Get the shipped date value

        // Send the Ajax request
        $.ajax({
            url: '/orders/' + orderId + '/update-status', // URL for the route to update status
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status,
                shipped_date: shippedDate,
            },
            success: function (response) {
                // Reset visibility of the alert box
                $('#status-message').css('display', 'block').html(
                    response.status === 'success'
                        ? '<div class="alert alert-success">' + response.message + '</div>'
                        : '<div class="alert alert-danger">Error updating status. Please try again later.</div>'
                );

                // Optionally update the UI with the new values
                $('#status').val(response.updated_status);
                $('#shipped_date').val(response.shipped_date);

                // Hide the message after 3 seconds (3000 ms)
                setTimeout(function () {
                    $('#status-message').fadeOut();
                }, 2000);
            },
            error: function () {
                // Reset visibility of the alert box
                $('#status-message').css('display', 'block').html(
                    '<div class="alert alert-danger">Error updating status. Please try again later.</div>'
                );

                // Hide the message after 3 seconds (3000 ms)
                setTimeout(function () {
                    $('#status-message').fadeOut();
                }, 2000);
            }
        });
    });

    // Send invoice email
    $('#send-invoice-btn').on('click', function (e) {
        e.preventDefault();

        const email = $('#email').val(); // Get the selected email
        const orderId = {{ $order->id }}; // Get the order ID

        // Send Ajax request
        $.ajax({
            url: "{{ route('send.invoice.email', $order->id) }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: email,
                order_id: orderId
            },
            success: function (response) {
                console.log(response);

                // Reset visibility of the alert box
                $('#email-status-message').css('display', 'block').html(
                    response.status
                        ? '<div class="alert alert-success">' + response.message + '</div>'
                        : '<div class="alert alert-danger">' + response.message + '</div>'
                );

                // Hide the message after 3 seconds (3000 ms)
                setTimeout(function () {
                    $('#email-status-message').fadeOut();
                }, 2000);
            },
            error: function () {
                // Reset visibility of the alert box
                $('#email-status-message').css('display', 'block').html(
                    '<div class="alert alert-danger">Failed to send email.</div>'
                );

                // Hide the message after 3 seconds (3000 ms)
                setTimeout(function () {
                    $('#email-status-message').fadeOut();
                }, 2000);
            }
        });
    });
}); 
</script>
@endsection