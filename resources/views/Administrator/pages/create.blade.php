@extends('Administrator.layout.app')
@section('links')
    <link href="{{ asset('admin/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
    <!-- jQuery -->
@endsection

@section('content')

<div class="alert-container"></div>
    <div class="content-wrapper">
        <div class="container-fluid my-2">
            <div class="row mb-2 d-flex align-items-center">
                <div class="col-sm-6">
                    <h1>Create Page</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a href="{{ route('pages') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <form id="pageForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-sm-6">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                    <span class="text-danger error-message" id="error-title"></span>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                                    <span class="text-danger error-message" id="error-slug"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" cols="30" rows="10" class="summernote"></textarea>
                                <span class="text-danger error-message" id="error-content"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <input type="submit" name="Create" id="create" class="btn btn-primary" value="Create">
                <a href="{{ route('pages') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    
@endsection
    
@section('customScript')  
    <script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function () {
            // Initialize Summernote
            $('.summernote').summernote({
                height: '300px',
            });
    
            // Title to Slug Conversion
            $('#title').on('input', function () {
                let titleValue = $(this).val();
                let slugValue = titleValue
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/^-+|-+$/g, ''); // Trim hyphens
                $('#slug').val(slugValue);
            });
    
            // Handle Form Submission with AJAX
            $("#pageForm").submit(function (event) {
                event.preventDefault(); // Prevent default form submission
                const $submitButton = $('#create');
                $submitButton.prop('disabled', true);
    
                $.ajax({
                    url: '{{ route("pages.store") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            // Redirect on success
                            window.location.href = "{{ route('pages') }}";
                        } else {
                            // Display validation errors
                            handleErrors(response.errors);
                        }
                        $submitButton.prop('disabled', false);
                    },
                    error: function (xhr) {
                        handleErrors(xhr.responseJSON?.errors || {});
                        $submitButton.prop('disabled', false);
                    },
                });
            });
    
            // Handle Errors
            function handleErrors(errors) {
                $(".error-message").text(""); // Clear existing errors
                $.each(errors, function (field, message) {
                    $("#error-" + field).text(message[0]); // Display error near relevant field
                });
            }
        });
    </script>
    
@endsection
