@extends('Administrator.layout.app')
@section('links')
    <link href="{{ asset('admin/plugins/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- jQuery -->
@endsection

@section('content')

<div class="alert-container"></div>
    <div class="content-wrapper">
        <div class="container-fluid my-2">
            <div class="row mb-2 d-flex align-items-center">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a href="{{ route('products') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <form  id="productForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>                     
                            </div>
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug" required readonly>
                               
                            </div>
                            <div class="mb-3">
                                <label for="short_description">Short Description</label>
                                <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">
                                    </textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_returns">Shipping and Returns</label>
                                <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Shipping and returns"></textarea>
                            </div>
                        </div>
                    </div>
                
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3 ">Media</h2>								
                            <div id="image" class="dropby dropzone dz-clickable ">
                                <div class="dz-message needsclick ">    
                                    <br>Drop files here or click to upload.<br><br>                                            
                                </div>
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="row" id="product-gallery"></div>
                 
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Price" required>	
                            </div>
                            <div class="mb-3">
                                <label for="compare_price">Compare at Price</label>
                                <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                <p class="text-muted mt-3">
                                    To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                </p>	
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">    
                            <h2 class="h4 mb-3">Related Products</h2>
                            <div class="mb-3">
                                <select multiple class="related_products form-control" name="related_products[]" id="related_products">

                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="mb-3">
                                <label for="sku">SKU (Stock Keeping Unit)</label>
                                <input type="text" name="sku" id="sku" class="form-control" placeholder="SKU" required>	
                            </div>
                            <div class="mb-3">
                                <label for="barcode">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                            </div>
                            <div class="mb-3 d-flex align-items-center">
                                <input type="hidden" name="track_qty" id="track_qty" value="0">
                                <input class="form-check-input" type="checkbox" id="track_qty" name="track_qty" value="1" checked>
                                <label for="track_qty" class="form-check-label ms-2">Track Quantity</label>
                            </div>        
                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" required>	
                        </div>
                    </div>
                    
                    
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Status</h2>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Blocked</option>
                            </select>
                        </div>
                    </div> 
                    
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Category</h2>
                            <select name="category_id" id="category" class="form-control" required>
                                <option value="" selected>select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Sub-Category</h2>
                            <select name="subcategory_id" id="subcategory" class="form-control">
                                <option value="" selected>Select a sub-category</option>
                            </select>
                        </div>
                    </div> 
                    
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Brand</h2>
                            <select name="brand_id" id="brand" class="form-control">
                                <option value="" selected>select a brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    
                    <!-- Featured Product Card -->
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured Product</h2>
                            <select name="is_featured" id="featured" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>                                                	
                            </select>
                        </div>
                    </div> 
                  
                </div>
            </div> 
              
            <div class="pb-5 pt-3">
                <input type="submit" name="Create" id="create" class="btn btn-primary" value="Create">
                <a href="{{ route('products') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
@endsection
    
@section('customScript')  
    <script src="{{ asset('admin/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(function () {
            $('.summernote').summernote({    
                height: '300px'
            });
        });    

     
    $('.related_products').select2({
        ajax: {
            url: '{{ route('products.getProducts') }}',
            dataType: 'json',
            tags: true,
            multiple: true,
            minimumInputLength: 3,
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
            data: function(params) {
                return { term: params.term }; // Correctly send the search term
            },
            processResults: function(data) {
                return {
                    results: data.tags.map(function(item) {
                        return { id: item.id, text: item.text }; // Map the response
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select related products',
        allowClear: true
    });

        
        // Title to Slug Conversion
        $(document).ready(function () {
            $('#title').on('input', function () {
                let titleValue = $(this).val();
                let slugValue = titleValue.toLowerCase().replace(/\s+/g, '-'); // Replace spaces with hyphens
                $('#slug').val(slugValue); // Set the slug value
            });
        });
        
        Dropzone.autoDiscover = false;
        const dropzone =  $("#image").dropzone({
            url: "{{ route('image.store') }}",
            maxFiles: 10,  // Allow up to 10 files
            paramName: 'image',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},   
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, image/gif",
            success: function(file, response) {
                console.log(response.image_path); 
                var html = `
                <div class="col-md-3" id="image-row-${response.data.id}">
                    <div class="card"">
                        <input type="hidden"  name="image_array[]" value="${response.data.id}">
                        <img src="${response.image_path}" class="card-img-top" alt="">
                        <div class="card-body">
                            <a href="javascript:void(0)" 
                            onclick="deleteImage(${response.data.id})" class="btn btn-danger ">Delete</a>
                        </div>
                    </div>
                </div>`
                $("#product-gallery").append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });
        
        function deleteImage(id) {
            $.ajax({
                url:`   {{ route('image.delete', ':id') }}`.replace(':id', id),  
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
                success: function (response) {
                    console.log("Image deleted successfully");
                    $("#image-row-" + id).remove();
                },
                error: function (error) {
                    console.error("Error deleting image:", error);
                }
            });
        }
        
        $(document).ready(function () {
            $('#category').on('change', function () {
                let category_id = $(this).val();  
                if (category_id) {
                    $.ajax({
                        url: "{{ route('getSubcategories', ':id') }}".replace(':id', category_id), 
                        type: 'GET',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
                        dataType: 'json',
                        success: function (response) {
                            $("#subcategory").find("option").not(":first").remove(); 
                            $.each(response.subcategories, function (key, item) {
                                $("#subcategory").append(`<option value="${item.id}">${item.name}</option>`);
                            });
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching subcategories:', error);
                        }
                    });
                }
            });
        })
        
        
        $("#productForm").submit(function (event) {
            event.preventDefault(); 
            var formData = new FormData(this); 
            $.ajax({
                url: '{{ route('products.store') }}',
                type: 'POST',
                data: formData,
                processData: false,  
                contentType: false, 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
                success: function (response) {
                    if (response.status === 'success') { 
                        $("#productForm")[0].reset(); 
                        window.location.href = "{{ route('products') }}";
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorList = '<ul>';
                            $.each(errors, function (key, error) {
                                errorList += `<li>${error[0]}</li>`;
                            });
                            errorList += '</ul>';                        
                            $(".alert-container").html(`
                            <div class="alert alert-danger">${errorList}</div>`
                        );
                    } else {
                        console.log(response);
                        // Handle other errors
                        $(".alert-container").html(`
                        <div class="alert alert-danger">Failed to create product. Please try again.</div>
                        `);
                    }
                }
            });
        });    
    </script>
@endsection
