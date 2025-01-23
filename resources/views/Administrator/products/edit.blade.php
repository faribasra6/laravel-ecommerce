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
                    <h1>Change Product</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a href="{{ route('products') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <form  id="productForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Title"
                                 value="{{ $product->title}}" >                     
                            </div>
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug"
                                 value="{{ $product->slug}}"  readonly>
                               
                            </div>
                            <div class="mb-3">
                                <label for="short_description">Short Description</label>
                                <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">
                                    {{ $product->short_description }}
                                    </textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">
                                    {{ $product->description }}
                                </textarea>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_returns">Shipping and Returns</label>
                                <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Shipping and returns">
                                    {{ $product->shipping_returns }}
                                </textarea>
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
                    <div class="row" id="product-gallery">
                        @foreach($product->images as $image)
                            <div class="col-md-3" id="image-row-{{ $image->id }}">
                                <div class="card">
                                 
                                    <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" alt="Product Image">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" onclick="deleteImage({{ $product->id }}, {{ $image->id }})" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                                        
                    
                 
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Price"
                                 value="{{ $product->price }}" >	
                            </div>
                            <div class="mb-3">
                                <label for="compare_price">Compare at Price</label>
                                <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price"
                                 value="{{ $product->compare_price }}">
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
                                <select multiple class="related_products w-100" name="related_products[]" id="related_products">
                                    @if (!empty($relatedProducts))
                                    @foreach ($relatedProducts as $relProduct)
                                    <option value="{{$relProduct->id}}" selected>{{$relProduct->title}}</option>  
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="mb-3">
                                <label for="sku">SKU (Stock Keeping Unit)</label>
                                <input type="text" name="sku" id="sku" class="form-control" placeholder="SKU"
                                 value="{{ $product->sku }}" >	
                            </div>
                            <div class="mb-3">
                                <label for="barcode">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode"
                                 value="{{ $product->barcode }}" >	
                            </div>
                            <div class="mb-3 d-flex align-items-center">
                                <input type="hidden" name="track_qty" id="track_qty" value="0">
                                <input class="form-check-input" type="checkbox" id="track_qty" name="track_qty" value="1" checked>
                                <label for="track_qty" class="form-check-label ms-2">Track Quantity</label>
                            </div>        
                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty"
                             value="{{ $product->qty }}" >	
                        </div>
                    </div>
                    
                    
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Status</h2>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }} >Active</option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }} >Blocked</option>
                            </select>
                        </div>
                    </div> 
                    
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Category</h2>
                            <select name="category_id" id="category" class="form-control" >
                                <option value=""  {{ $product->category_id == NULL ? 'selected' : '' }} >select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Sub-Category</h2>
                            <select name="subcategory_id" id="subcategory" class="form-control">
                                <option value=""  {{ $product->subcategory_id == NULL ? 'selected' : '' }} >Select a sub-category</option>
                                @if ($subcategories->isNotEmpty())
                                    @foreach ($subcategories as $subcategory)
                                            <option value="{{$subcategory->id}}"{{ ($subcategory->id == $product->subcategory_id) ? 'selected': '' }} >{{ $subcategory->name }}</option>
                                        
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div> 
                    
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product Brand</h2>
                            <select name="brand_id" id="brand" class="form-control">
                                <option value="" {{ $product->brand_id == NULL ? 'selected' : '' }}>select a brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    
                    <!-- Featured Product Card -->
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured Product</h2>
                            <select name="is_featured" id="featured" class="form-control">
                                <option value="0" {{ $product->is_featured == 0 ? 'selected' : '' }} >No</option>
                                <option value="1" {{ $product->is_featured == 1 ? 'selected' : '' }} >Yes</option>                                                	
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
        
        
        Dropzone.autoDiscover = false; // Disable auto-discovery to manually configure Dropzone
        
        // Get the product ID from the Blade template
        const productId = {{ $product->id }};  // Replace this with the actual product ID variable
        
        // Initialize Dropzone
        const dropzone = new Dropzone("#image", {
            url: "/products/" + productId + "/images/create",  // Dynamically append the product ID to the URL
            maxFiles: 10,  // Allow up to 10 files
            paramName: 'image', // The parameter name for the file
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // CSRF token
            addRemoveLinks: true, // Add the remove link for each file
            params: {   product_id: productId },
            acceptedFiles: "image/jpeg, image/png, image/gif", // Accept only these file types
            success: function(file, response) {
                // Handle success response
                console.log(response); // Log the image path
                
                // Append the new image to the product gallery
                var html = `
                <div class="col-md-3" id="image-row-${response.image_id}">
                    <div class="card">
                        <img src="${response.image_path}" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>`;            
                $("#product-gallery").append(html); // Add the new image to the gallery
            },
            complete: function(file) {
                this.removeFile(file); // Remove the file from the Dropzone queue after upload
            }
        });
        


        function deleteImage(productId, imageId) {
            // Confirm before deleting
            if (confirm("Are you sure you want to delete this image?")) {
                $.ajax({
                    url: '{{ route('product_images.destroy', ['product' => ':product', 'image' => ':image']) }}'
                    .replace(':product', productId)
                    .replace(':image', imageId), // Replacing placeholders with the actual IDs
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token for security
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $("#image-row-" + imageId).remove();
                            alert('Image deleted successfully!');
                        } else {
                            alert('Error deleting image: ' + response.message);
                        }
                    },
                    error: function(error) {
                        console.error("Error deleting image:", error);
                        alert('An error occurred while deleting the image.');
                    }
                });
            }
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
                url: "{{ route('products.update', $product->id) }}",
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
