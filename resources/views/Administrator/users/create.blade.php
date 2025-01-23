@extends('Administrator.layout.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create new User</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a class="btn btn-primary" href="{{ route('users') }}">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form id="UsersForm" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="card">
                    <div class="card-body">							
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                                    <p id="error-name" class="error-message text-danger"></p>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="example@domain.com" >
                                    <p id="error-email" class="error-message text-danger"></p>
                                </div>
                            </div>	
                        </div>
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                                    <p id="error-password" class="error-message text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation">Confirm Password:</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" required>
                                    <p id="error-password_confirmation" class="error-message text-danger"></p>
                                </div>
                            </div>
                        </div>  
                                
                        
                        <div class="row">
                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone Number" >
                                    <p id="error-phone" class="error-message text-danger"></p>
                                </div>
                            </div>
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-control" >
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Blocked</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <p id="error-status" class="error-message text-danger"></p>
                                </div>
                            </div>
                        </div>	
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="{{ route('users') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
                
        
        </div>
    </section>
</div>
@endsection
@section('customScript')
<script src="{{ asset('admin/plugins/dropzone/min/dropzone.min.js') }}"></script>
  
<script>
    
   $("#UsersForm").submit(function(event) {
    event.preventDefault(); // Prevent the default form submission
    const $submitButton = $('button[type=submit]');
    $submitButton.prop('disabled', true);

    $.ajax({
        url: '{{ route("users.store") }}',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json', // Ensure the response is in JSON format
        success: function(response) {
            // Clear previous error messages
            $(".error-message").text("");

            if (response.status) {
                // Successful submission
                $("#UsersForm")[0].reset();
                window.location.href = "{{ route('users') }}";
            } else {
                // Handle validation errors
                handleErrors(response.errors);
            }

            $submitButton.prop('disabled', false);
        },
        error: function(xhr) {
            // Handle AJAX errors
            $submitButton.prop('disabled', false);
            handleErrors(xhr.responseJSON?.errors || {});
        }
    });
});

// Helper function to handle error display
function handleErrors(errors) {
    $(".error-message").text(""); // Clear previous error messages
    $.each(errors, function(field, message) {
        const $errorElement = $("#error-" + field);
        if ($errorElement.length) {
            $errorElement.text(message[0]); // Display error message for the specific field
        }
    });
}

       
        
</script>
@endsection