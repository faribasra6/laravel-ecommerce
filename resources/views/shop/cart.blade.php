@extends('shop.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop')}}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>
<section class="section-9 pt-4">
    

    <div class="container">
        @include('shop.message')
        <div class="row">
            @if ($count > 0)
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table" id="cart">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($cartItems))
                            @foreach ($cartItems as $cartItem)  
                            <tr>
                                <td class="text-start">
                                    <div class="d-flex align-items-start">
                                        @if($cartItem->options->image)
                                        <img src="{{ asset($cartItem->options->image) }}" alt="{{ $cartItem->name }}" class="card-img-top">
                                        @else
                                        <img src="{{ asset('storage/image.png') }}" alt="No Image" class="card-img-top">
                                        @endif
                                        <h2>{{ $cartItem->name }}</h2>
                                    </div>
                                </td>
                                <td>AED{{ $cartItem->price }}</td>
                                <td>
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $cartItem->rowId }}">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm border-0 text-center" value="{{ $cartItem->qty }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $cartItem->rowId }}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>AED {{ $cartItem->price * $cartItem->qty }}</td>
                                <td>
                                    <button onclick="DeleteCartItem('{{ $cartItem->rowId }}');" class="btn btn-sm btn-danger"   ><i class="fa fa-times" ></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @endif                                                       
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">            
                <div class="card cart-summery"> 
                    <div class="card-body">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summary</h2>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <div>Subtotal</div>
                            <div>AED {{ number_format(floatval(str_replace(',', '', $subtotal )), 2) }}</div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('cart.checkout')}}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>     
            </div>
            @else
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h2 class="text-center text-muted">Your Cart is empty</h2>
                            <!-- Optional subtitle -->
                            <p class="text-center text-muted">Start adding your favorite products to the Cart!</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

@section('customScript')
<script>

    // Increment quantity
    $('.add').click(function() {
        var qtyElement = $(this).parent().prev(); // Qty Input
        var qtyValue = parseInt(qtyElement.val());
        if (qtyValue < 10) {
            var rowID = $(this).data('id');
            qtyElement.val(qtyValue + 1);
            var newQty = qtyElement.val();
            updateCartTotal(rowID, newQty);
        }            
    });
    
    // Decrement quantity
    $('.sub').click(function() {
        var qtyElement = $(this).parent().next(); 
        var qtyValue = parseInt(qtyElement.val());
        if (qtyValue > 1) {
            var rowID = $(this).data('id');
            qtyElement.val(qtyValue - 1);
            var newQty = qtyElement.val();
            updateCartTotal(rowID, newQty);
        }        
    });
    
    // Function to update the cart total
    function updateCartTotal(rowID, qty) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'put',
            data: {
                rowID: rowID, qty: qty, _token: '{{ csrf_token() }}' // Include CSRF token for security
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    showAlert('success', response.message); // Show success alert
                } else {
                    showAlert('error', response.message); // Show error alert
                }
                window.location.href = '{{ route("cart") }}';
            }
        });
    }
    
    
    // Function to handle showing alerts
    function showAlert(type, message) {
        var alertId = type === 'success' ? 'success-alert' : 'error-alert';
        var alertDiv = $('#' + alertId);
        alertDiv.text(message).show(); // Update and show the alert
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alertDiv[0]);
            bsAlert.close(); // Dismiss alert after 3 seconds
        }, 3000);
    }
    
    // Auto dismiss alerts on page load
    var errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(errorAlert);
            bsAlert.close();
        }, 3000);
    }
    
    var successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 3000);
    }

    //function to delete cart item
    function DeleteCartItem(rowID) {
        if (confirm("Are you sure you want to delete this item from your cart?")) { // Confirmation dialog
            $.ajax({
                url: '{{ route("cart.delete") }}',
                type: 'delete',
                data: {
                    rowID: rowID,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        showAlert('success', response.message); // Show success alert
                    } else {
                        showAlert('error', response.message);
                    }
                    // Optionally reload the cart page or remove the item from the DOM
                    window.location.href = '{{ route("cart") }}'; // Only if you want to refresh the cart page
                },
                error: function(xhr, status, error) {
                    showAlert('error', 'An error occurred while deleting the item. Please try again.'); // General error message
                }
            });
        }
    }


</script>
@endsection
