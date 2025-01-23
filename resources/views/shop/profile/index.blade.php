@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account')}}">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>
    @include('shop.message')

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('shop.profile.account-panel')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <div class="card-body p-4">
                            <form id="profileForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                                    <p id="error-name" class="error-message text-danger"></p>
                                </div>
                            
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                                    <p id="error-email" class="error-message text-danger"></p>
                                </div>
                            
                                <!-- Phone -->
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                                    <p id="error-phone" class="error-message text-danger"></p>
                                </div>
                            
                                
                            
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-dark">Update</button>
                            </form>
                            
                            
                        </div>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Shipping Address</h2>
                        </div>
                        <div class="card-body p-4">
                            <form id="addressForm" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id}}">
                                <!-- First Name -->
                                <div class="mb-3 row">
                                    <div class="col-lg-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $address->first_name }}">
                                        <p id="error-first_name" class="error-message text-danger"></p>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $address->last_name }}">
                                        <p id="error-last_name" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                
                                
                                <div class="mb-3 row">
                                    <div class="col-lg-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ $address->email }}">
                                        <p id="error-email" class="error-message text-danger"></p>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ $address->mobile }}">
                                        <p id="error-mobile" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                
                    
                                <!-- Country -->
                                <div class="mb-3">
                                    <label for="country_id">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $address->country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p id="error-country_id" class="error-message text-danger"></p>
                                </div>
                                
                                <!-- Address -->
                                <div class="mb-3">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" class="form-control" rows="3">{{ $address->address }}</textarea>
                                    <p id="error-address" class="error-message text-danger"></p>
                                </div>
                                
                    
                                <div class="mb-3 row">
                                    <div class="col-lg-6">
                                        <label for="apartment">Apartment (Optional)</label>
                                        <input type="text" name="apartment" id="apartment" class="form-control" value="{{ $address->apartment }}">
                                        <p id="error-apartment" class="error-message text-danger"></p>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <label for="city">City</label>
                                        <input type="text" name="city" id="city" class="form-control" value="{{ $address->city }}">
                                        <p id="error-city" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                
                    
                                <div class="mb-3 row">
                                    <div class="col-lg-6">
                                        <label for="state">State (Optional)</label>
                                        <input type="text" name="state" id="state" class="form-control" value="{{ $address->state }}">
                                        <p id="error-state" class="error-message text-danger"></p>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <label for="zip">Zip Code (Optional)</label>
                                        <input type="text" name="zip" id="zip" class="form-control" value="{{ $address->zip }}">
                                        <p id="error-zip" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                
                                
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-dark">Update</button>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
   
</main>
@endsection
@section('customScript')
<script>
    
    $(document).ready(function() {
        // Phone number validation
        $('#phone').on('input', function() {
            let phoneNumber = $(this).val();
            let phonePattern = /^(05\d{8}|\+9715\d{8})$/; // Correct UAE phone number validation
            
            // Check if the phone number is valid or not
            if (!phonePattern.test(phoneNumber)) {
                // Invalid phone number
                $('#error-phone').text('Invalid phone number. Please enter a valid UAE phone number.');
                $('button[type=submit]').prop('disabled', true);
            } else {
                // Valid phone number, clear the error
                $('#error-phone').text('');
                $('button[type=submit]').prop('disabled', false);
            }
        });
        
        // Form submit handler with AJAX
        $("#profileForm").submit(function(event) {
            event.preventDefault(); // Prevents the default form submission
            
            // Basic check for phone validation
            let phoneNumber = $('#phone').val();
            let phonePattern = /^(05\d{8}|\+9715\d{8})$/; // Correct UAE phone number validation
            
            // If the phone number is invalid, display the error and stop form submission
            if (!phonePattern.test(phoneNumber)) {
                $('#error-phone').text('Invalid phone number. Please enter a valid UAE phone number.');
                return;
            }
            
            // Proceed with AJAX form submission if the phone number is valid
            $('button[type=submit]').prop('disabled', true); // Disable submit button
            $.ajax({
                url: '{{ route("update_user") }}',
                type: 'POST',
                data: new FormData(this),
                processData: false, 
                contentType: false,
                success: function(response) {
                    // Handle response from server
                    if (response.status) {
                        window.location.href = "{{ route('account') }}"; // Redirect to profile page
                    } else {
                        $.each(response.errors, function(field, messages) {
                        if ($("#error-" + field).length) {
                            $("#error-" + field).text(messages[0]);
                        }
                    });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    $.each(response.errors, function(field, messages) {
                        if ($("#error-" + field).length) {
                            $("#error-" + field).text(messages[0]);
                        }
                    });
                },
                complete: function() {
                    $('button[type=submit]').prop('disabled', false); // Enable submit button again
                }
            });
        });
    
    

    $('#mobile').on('input', function() {
            let phoneNumber = $(this).val();
            let phonePattern = /^(05\d{8}|\+9715\d{8})$/; // Correct UAE phone number validation
            
            // Check if the phone number is valid or not
            if (!phonePattern.test(phoneNumber)) {
                // Invalid phone number
                $('#error-mobile').text('Invalid mobile number. Please enter a valid UAE phone number.');
                $('button[type=submit]').prop('disabled', true);
            } else {
                // Valid phone number, clear the error
                $('#error-mobile').text('');
                $('button[type=submit]').prop('disabled', false);
            }
        });
        
        // Form submit handler with AJAX
        $("#addressForm").submit(function(event) {
            event.preventDefault(); // Prevents the default form submission
            
            // Basic check for phone validation
            let phoneNumber = $('#mobile').val();
            let phonePattern = /^(05\d{8}|\+9715\d{8})$/; // Correct UAE phone number validation
            
            // If the phone number is invalid, display the error and stop form submission
            if (!phonePattern.test(phoneNumber)) {
                $('#error-mobile').text('Invalid phone number. Please enter a valid UAE phone number.');
                return;
            }
            
            // Proceed with AJAX form submission if the phone number is valid
            $('button[type=submit]').prop('disabled', true); // Disable submit button
            $.ajax({
                url: '{{ route("customer-address.save") }}',
                type: 'POST',
                data: new FormData(this),
                processData: false, 
                contentType: false,
                success: function(response) {
                    // Handle response from server
                    if (response.status) {
                        window.location.href = "{{ route('account') }}"; // Redirect to profile page
                    } else {
                        $.each(response.errors, function(field, messages) {
                        if ($("#error-" + field).length) {
                            $("#error-" + field).text(messages[0]);
                        }
                    });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    $.each(response.errors, function(field, messages) {
                        if ($("#error-" + field).length) {
                            $("#error-" + field).text(messages[0]);
                        }
                    });
                },
                complete: function() {
                    $('button[type=submit]').prop('disabled', false); // Enable submit button again
                }
            });
        });
    });
    


</script>
    
@endsection
