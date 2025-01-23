@extends('shop.layouts.app')

@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id="orderForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                             value="{{ (!empty($customerAddress))? $customerAddress->first_name : ''}}" placeholder="First Name">
                                            <p id="error-first_name" class="error-message text-danger"></p>
                                        </div>            
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->last_name : ''}}" placeholder="Last Name">
                                            <p id="error-last_name" class="error-message text-danger"></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" 
                                            value="{{ (!empty($customerAddress))? $customerAddress->email : ''}}" placeholder="Email">
                                            <p id="error-email" class="error-message text-danger"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id)? 'selected' : ''}} value="{{ $country->id}}">{{ $country->country->name}}</option>
                                                    
                                                @endforeach
                                                    
                                                @endif
                                            </select>
                                            <p id="error-country" class="error-message text-danger"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">
                                                {{ (!empty($customerAddress))? $customerAddress->address : ''}}
                                            </textarea>
                                            <p id="error-address" class="error-message text-danger"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->apartment : ''}}" placeholder="Apartment, suite, unit, etc. (optional)">
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->city : ''}}" placeholder="City">
                                            <p id="error-city" class="error-message text-danger"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->state : ''}}" placeholder="State">
                                            <p id="error-state" class="error-message text-danger"></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->zip : ''}}" placeholder="Zip">
                                            <p id="error-zip" class="error-message text-danger"></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                            value="{{ (!empty($customerAddress))? $customerAddress->mobile : ''}}" placeholder="Mobile No.">
                                            <p id="error-mobile" class="error-message text-danger"></p>
                                        </div>            
                                    </div>
                                    

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                        </div>            
                                    </div>

                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>                    
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach ($cartItems as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{ $item->name}} * {{ $item->qty}}</div>
                                    <div class="h6">AED{{ $item->price * $item->qty}}</div>
                                </div>
                                @endforeach
                            
                            
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    {{-- <div class="h6"><strong>AED{{Cart::subtotal() + Cart::discount()}}</strong></div> --}}
                                    <div>AED {{ number_format(floatval(str_replace(',', '', $subtotal)), 2) }}</div>

                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
                                    <div id="discount_amount" class="h6" ><strong>-AED {{number_format(floatval(str_replace(',', '', $discount)), 2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <input type="hidden" name="shipping_amount" id="shipping-amount-input" value="0">
                                    <div  id="shipping-amount" class="h6" name="shipping"><strong>
                                        
                                        AED 0</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div id="total-amount" name="total_amount" class="h5"><strong>AED {{ number_format(floatval(str_replace(',', '', $subtotal)), 2)     }}</strong></div>
                                </div>                            
                            </div>
                        </div>
                        <form id="apply_discount">
                            <div class="input-group apply-coupon mt-4">
                                <input type="hidden" name="coupon_code" id="coupon_code" value="">
                                <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                                <button class="btn btn-dark" type="button" id="apply_coupon">Apply Coupon</button>
                            </div>
                            <div id="applied_coupon" class="mt-3 d-none">
                                <span class="text-success" id="coupon_text"></span>
                                <button type="button" class="btn btn-link text-danger p-0 ms-2" id="remove_coupon">×</button>
                            </div>
                            <p id="error-coupon" class="error-message text-danger"></p>
                        </form>
                        
                        
                        
                        

                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">Payment Details</h3>
                            <div class="form-check">
                                <input checked type="radio" name="payment_method" value="cod" id="payment_cod" class="form-check-input">
                                <label for="payment_cod" class="form-check-label">Cash on delivery</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="payment_method" value="card" id="payment_card" class="form-check-input">
                                <label for="payment_card" class="form-check-label">CARD</label>
                            </div>                    
                            <div class="card-body p-0" id="card_payment">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv_code" class="mb-2">CVV Code</label>
                                        <input type="text" name="cvv_code" id="cvv_code" placeholder="123" class="form-control">
                                    </div>
                                </div>
                               
                            </div> 
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>                       
                        </div>
                        
                            
                        <!-- CREDIT CARD FORM ENDS HERE -->
                        
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customScript')
<script>
var shippingCharges = @json($countries->pluck('amount', 'country_id'));
var cartSubtotal = parseFloat('{{ preg_replace("/[^0-9.]/", "", $subtotal) }}');
var preTotal = 0;

// Function to update the shipping and total amount
function updateShippingAndTotalAmount(selectedCountryId) {
    var shippingAmount = shippingCharges[selectedCountryId] || 0;

    // Update the shipping amount on the page
    $('#shipping-amount').html('<strong>AED ' + shippingAmount.toFixed(2) + '</strong>');
    $('#shipping-amount-input').val(shippingAmount);

    // Update the total amount
    var totalAmount = cartSubtotal + shippingAmount;
    preTotal = totalAmount;
    $('#total-amount').html('<strong>AED ' + totalAmount.toFixed(2) + '</strong>');
}

// Trigger the function when the page loads with the selected country
$(document).ready(function() {
    var selectedCountryId = $('#country').val();  // Get the currently selected country id
    updateShippingAndTotalAmount(selectedCountryId); // Update shipping and total amount

    // Attach the event listener for country change
    $(document).on('change', '#country', function() {
        var selectedCountryId = $(this).val();
        updateShippingAndTotalAmount(selectedCountryId); // Update on country change
    });
});

    // When the payment method is selected
    $("input[name='payment_method']").change(function() {
        if ($("#payment_cod").is(":checked")) {
            $("#card_payment").css('display', 'none'); 
        } else if ($("#payment_card").is(":checked")) {
            $("#card_payment").css('display', 'block'); 
        }
    });
    
    // Initial check (in case the page loads with payment_cod selected)
    if ($("#payment_cod").is(":checked")) {
        $("#card_payment").css('display', 'none'); 
    } else {
        $("#card_payment").css('display', 'block'); 
    }
    
    $("#orderForm").submit(function(event) {
        event.preventDefault(); // Prevents the default form submission
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("cart.processCheckout") }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                // Clear previous error messages
                $(".error-message").text("");
                
                if (!response.status) {
                    alert(response.message);
                    $('button[type=submit]').prop('disabled', false);
                    
                    $.each(response.errors, function(field, message) {
                        $("#error-" + field).text(message[0]);
                    });
                } else {
                    // successful submission
                    $("#orderForm")[0].reset();
                    window.location.href = "{{ url('/thanks/')}}/" +response.orderId;
                }
            },
            error: function(xhr) {
                console.log("An error occurred" );
                $('button[type=submit]').prop('disabled', false);
                if (xhr.status === 400) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                    $.each(response.errors, function(field, messages) {
                        if ($("#error-" + field).length) {
                            $("#error-" + field).text(messages[0]);
                        }
                    });
                } else {
                    alert('An unexpected error occurred. Please try again later.');
                }
            }
        });
    });

    $("#apply_coupon").click(function (event) {
    event.preventDefault(); // Prevent the default form submission
    let couponCode = $("#discount_code").val().trim();

    // Check if the coupon code is empty
    if (couponCode === "") {
        alert("Please enter a coupon code.");
        return;
    }

    // Get form data (coupon code and shipping)
    var formData = {
        code: couponCode,
        shipping: $("#shipping-amount-input").val(),
        _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token for Laravel
    };

    // AJAX request to apply the discount
    $.ajax({
        url: '{{ route("cart.discount") }}', // The route to apply the discount
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            // Handle the response (successful coupon application)
            if (response.status) {
                $('#error-coupon').removeClass('text-danger text-success').text('');
                $('#error-coupon').addClass('text-success').text(response.message);

                // Update discount and total amount
                $("#discount_amount").text("AED " + response.discount);
                $("#total-amount").text("AED " + response.total);
                $('#discount-amount-input').val(response.discount);

                // Display the applied coupon and enable cancel button
                $('#applied_coupon').removeClass('d-none'); // Show the applied coupon section
                $('#coupon_text').text("Coupon Applied: " + couponCode); // Display coupon code

                // Sync the hidden input for form submission
                $('#coupon_code').val(couponCode);

                // Disable input field
                $('#discount_code').val('').prop('disabled', true);
            } else {
                $('#error-coupon').removeClass('text-success').text('');
                $('#error-coupon').addClass('text-danger').text(response.message); // Display error message
            }
        },
        error: function (xhr, status, error) {
            $('#error-coupon').removeClass('text-success').text('');
            var errorMessage = xhr.responseJSON.message || 'An error occurred.';
            $('#error-coupon').addClass('text-danger').text(errorMessage);
            console.error('AJAX error:', error);
        }
    });
});

// Remove coupon handler
$("#remove_coupon").click(function () {
    $('#applied_coupon').addClass('d-none'); // Hide the applied coupon section
    $('#coupon_text').text(''); // Clear coupon text

    // Reset the hidden input for form submission
    $('#coupon_code').val('');

    // Enable input field
    $('#discount_code').val('').prop('disabled', false);

    // Reset discount and total amount
    $('#discount_amount').text("AED 0");
    $('#total-amount').text("AED " + $('#cart-total').val());
    $('#discount-amount-input').val(0);
});

    
</script>
@endsection