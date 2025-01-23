@extends('Administrator.layout.app')
@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@endsection
@section('content')
	<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="text-center">Add Coupon Code</h1>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('discount') }}" class="btn btn-primary">back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        
        <section class="content">
            <!-- Default box -->
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {!! Session::get('success') !!}  <!-- Changed from 'message' to 'success' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ Session::get('error') }}  <!-- Changed from 'message' to 'error' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            <div class="container-fluid">
                <form id="discountFormUpdate" enctype="multipart/form-data" >
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code">Code</label>
                                        <input type="text" class="form-control" name="code" id="code" placeholder="Coupon code"
                                         value="{{ $coupon->code}}" >
                                        <p id="error-code" class="error-message text-danger"></p>
                                    </div>   
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Coupon code name"
                                         value="{{ $coupon->name}}" >
                                        <p id="error-name" class="error-message text-danger"></p>
                                    </div>
                                </div>									
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_use">Max Uses</label>
                                        <input type="number" class="form-control" name="max_use" id="max_use" placeholder="Max uses"
                                         value="{{ $coupon->max_use}}"  >
                                        <p id="error-max_use" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_user_use">Max Uses User</label>
                                        <input type="number" class="form-control" name="max_user_use" id="max_user_use" placeholder="Max uses User"
                                         value="{{ $coupon->max_user_use}}" >
                                        <p id="error-max_user_use" class="error-message text-danger"></p>
                                    </div>
                                </div>	
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_type">Discount Type:</label>
                                        <select name="discount_type" id="discount_type" class="form-control" >
                                            <option value="percentage" {{ ($coupon->discount_type == 'percentage') ?  'selected':''}}>Percentage</option>
                                            <option value="fixed" {{ ($coupon->discount_type == 'fixed') ? 'selected' : ''}}>Fixed Price</option>
                                        </select>
                                    </div>
                                    <p id="error-discount_type" class="error-message text-danger"></p>

                                </div>  								
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_value">Discount Value</label>
                                        <input type="number" class="form-control" name="discount_value" id="discount_value" placeholder="Discount amount"
                                         value="{{$coupon->discount_value}}" >
                                        <p id="error-discount_value" class="error-message text-danger"></p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="minimum_order_amount">Minimum order Amount</label>
                                        <input type="number" class="form-control" name="minimum_order_amount" id="minimum_order_amount"
                                         value="{{ $coupon->minimum_order_amount}}" placeholder="Min amount" >
                                        <p id="error-minimum_order_amount" class="error-message text-danger"></p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" >
                                            <option value="1" {{ ($coupon->status == '1')? 'selected' : ''}}>Active</option>
                                            <option value="0" {{ ($coupon->staus == '0')? 'selected': ''}}>Inactive</option>
                                        </select>
                                    </div>
                                    <p id="error-status" class="error-message text-danger"></p>

                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date">Starts At</label>
                                        <input type="text" class="form-control" name="start_date" id="start_date"
                                         value="{{ $coupon->start_date}}" placeholder="Starts at" >
                                        <p id="error-start_date" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date">Expires At</label>
                                        <input type="text" class="form-control" name="end_date" id="end_date" 
                                         value="{{ $coupon->end_date}}" placeholder="expires at" >
                                        <p id="error-end_date" class="error-message text-danger"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="10" >
                                            {{ $coupon->description}}
                                        </textarea>
                                        <p id="error-description" class="error-message text-danger"></p>

                                    </div>   
                                </div>
                                <div class="col-md-6">
                                    <div class="pb-5 pt-3">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                        <a href="{{ route('categories') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                                    </div>
                                </div>
                            </div>	
                        </div>							
                    </div>
                </form>
            </div>
        </section>
    </div>
    
   
@endsection
@section('customScript')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize start_date picker
    flatpickr("#start_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        altInput: true,
        altFormat: "F j, Y (h:i K)",
        minDate: "today",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Set minimum date of end_date based on selected start_date
            endDatePicker.set('minDate', dateStr);
        }
    });

    // Initialize end_date picker
    var endDatePicker = flatpickr("#end_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        altInput: true,
        altFormat: "F j, Y (h:i K)",
        minDate: "today",
        time_24hr: true
    });
});

$("#discountFormUpdate").submit(function(event) {
        event.preventDefault(); // Prevents the default form submission
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("discount.update", $coupon->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                // Clear previous error messages
                $(".error-message").text("");
                
                if (!response.status) {
                    $('button[type=submit]').prop('disabled', false);
                    
                    $.each(response.errors, function(field, message) {
                        $("#error-" + field).text(message[0]);
                    });
                } else {
                    // successful submission
                    $("#discountFormUpdate")[0].reset();
                    window.location.href = "{{ route('discount')}}";
                }
            },
            error: function(xhr) {
                console.log("An error occurred" );
                $('button[type=submit]').prop('disabled', false);
                if (xhr.status === 400) {
                    var response = JSON.parse(xhr.responseText);
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

    

</script>

@endsection
