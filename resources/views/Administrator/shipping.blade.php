@extends('Administrator.layout.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2 pb-3">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="text-center">Shipping Charges</h1>
                </div>
            </div>
        </div>
    </section>
<section class="content">
        <div class="container-fluid">
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


        <form id="countryForm" enctype="multipart/form-data" >
            @csrf
            @method('post')
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select a Country</option>
                                    @if ($countries->isNotEmpty())
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id}}">{{ $country->name}}</option>
                                    @endforeach
                                    <option value="others">Rest of the Wrold</option>
                                    @endif
                                </select>
                                <p id="error-country_id" class="error-message text-danger"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="amount" id="amount" class="form-control">
                            <p id="error-amount" class="error-message text-danger"></p>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary form-control " type="submit">Add/Update</button>
                        </div>					
                    </div>
                </div>							
            </div>
        </form>
    </div>

    <div class="container-fluid pt-3">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Amount</td>
                                <td>Action</td>
                            </tr>
                            @if ($shippingCharges->isNotEmpty())
                            @foreach ($shippingCharges as $charge)
                            <tr>
                                <td>{{ $charge->id}}</td>
                                <td>{{ $charge->country->name}}</td>
                                <td>{{$charge->amount}}</td>
                                <td>
                                    <a href="javascript:void(0);" 
                                    class="text-danger w-4 h-4 mr-1 delete-shipping" 
                                    data-id="{{ $charge->country_id }}" >
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $shippingCharges->links() }} 
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customScript')
<script>
    $("#countryForm").submit(function(event) {
        event.preventDefault(); // Prevents the default form submission
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("shipping.store") }}',
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
                    $("#countryForm")[0].reset();
                    window.location.href = "{{ route('shipping')}}"
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
    
    $(document).on('click', '.delete-shipping', function (e) {
        e.preventDefault();
        
        var shippingId = $(this).data('id');  // Get the shipping charge ID
        
        // Confirm before deleting
        var confirmDelete = confirm("Are you sure you want to delete this shipping charge?");
        
        if (confirmDelete) {
            // Send DELETE request via AJAX
            $.ajax({
                url: '{{ route("shipping.delete", ":id") }}'.replace(':id', shippingId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                },
                success: function(response) {
                 window.location.href = "{{ route('shipping')}}"
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('Something went wrong. Please try again.');
                }
            });
        }
    });
    
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
