@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account')}}">My Account</a></li>
                    <li class="breadcrumb-item">Change Password</li>
                </ol>
            </div>
        </div>
    </section>

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
                            <form id="passwordForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                    <p id="error-current_password" class="error-message text-danger"></p>
                                </div>
    
                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control">
                                    <p id="error-new_password" class="error-message text-danger"></p>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="new_password_confirmation">Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                                    <p id="error-new_password_confirmation" class="error-message text-danger"></p>
                                </div>
                                
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-dark">Update Password</button>
                                                            
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
        $('button[type=submit]').prop('disabled', true); // Disable submit button initially
        
        $('#new_password, #new_password_confirmation').on('input', function() {
            let newPassword = $('#new_password').val();
            let confirmPassword = $('#new_password_confirmation').val();
            
            if (newPassword !== confirmPassword) {
                $('#error-new_password_confirmation').text('Passwords do not match.');
                $('button[type=submit]').prop('disabled', true);
            } else {
                $('#error-new_password_confirmation').text('');
                $('button[type=submit]').prop('disabled', false);
            }
        });
        
        $("#passwordForm").submit(function(event) {
            event.preventDefault();
            
            let newPassword = $('#new_password').val();
            let confirmPassword = $('#new_password_confirmation').val();
            
            if (newPassword !== confirmPassword) {
                $('#error-new_password_confirmation').text('Passwords do not match.');
                return;
            }
            
            $('button[type=submit]').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("update_password") }}',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) { 
                        window.location.href = "{{ route('account') }}";
                    } else {
                        if (response.errors) {
                            for (let field in response.errors) {
                                $(`#error-${field}`).text(response.errors[field][0]);
                            }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'An error occurred while updating your password.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                },
                complete: function() {
                    $('button[type=submit]').prop('disabled', false);
                }
            });
        });
    });
    
</script>

@endsection
