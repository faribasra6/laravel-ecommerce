@extends('Administrator.layout.app')
@section('links')
    <link href="{{ asset('admin/plugins/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
    <!-- jQuery -->
@endsection
@section('content')

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <a class="btn btn-primary" href="{{ route('categories') }}">Back</a>
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
            <form id="categoryForm" enctype="multipart/form-data" >
                @csrf
                @method('post')
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" name="name" id="category_name" placeholder="Name">
                                    <p id="error-name" class="error-message text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug:</label>
                                    <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug"  readonly>
                                    <p id="error-slug" class="error-message text-danger"></p>
                                </div>
                            </div>									
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Blocked</option>
                                    </select>
                                    <p id="error-status" class="error-message text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Show on Home:</label>
                                    <select name="showHome" id="showHome" class="form-control" required>
                                        <option value="Yes">Yes</option>
                                        <option value="No" selected>No</option>
                                    </select>
                                    <p id="error-showHome" class="error-message text-danger"></p>
                                </div>
                            </div>
                        </div>	
                        <div class="row">    
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3 ">Media</h2>								
                                        <div id="image" class="dropby dropzone dz-clickable ">
                                            <div class="dz-message needsclick ">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                        <input type="hidden" name="image" id="image_path">
                                        <p id="error-image" class="error-message text-danger"></p>
                                    </div>	                                                                      
                                </div>                               
                            </div>	
                            <div class="col-md-6">
                                <div id="category-image"></div>
                            </div>
     						
                        </div>
                        
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="{{ route('categories') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
@section('customScript')
<script src="{{ asset('admin/plugins/dropzone/min/dropzone.min.js') }}"></script>
  
<script>
    $(document).ready(function () {
    $('#category_name').on('input', function () {
        let titleValue = $(this).val();
        let slugValue = titleValue.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, ''); // Remove invalid characters
        $('#slug').val(slugValue); // Set the slug value
    });
});


Dropzone.autoDiscover = false;
        const dropzone =  $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if(this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('image.store') }}",
            maxFiles: 1,  // Allow up to 10 files
            paramName: 'image',    
            addRemoveLinks: true,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
            acceptedFiles: "image/jpeg, image/png, image/gif",
            success: function(file, response) {
                console.log(response.data.file_path); 
                $('#image_path').val(response.data.file_path);
            },
           
        });
        
        $("#categoryForm").submit(function(event) {
        event.preventDefault(); // Prevents the default form submission
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("categories.store") }}',
            type: 'POST',
            data: $(this).serializeArray(),
         
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
                    $("#categoryForm")[0].reset();
                    window.location.href = "{{ route('categories')}}";
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