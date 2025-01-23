@extends('shop.layouts.app')
@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop')}}">Shop</a></li>
                <li class="breadcrumb-item">{{ $product->title}}</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-7 pt-3 mb-3">
    <div class="container">
        <div class="row ">
            <div class="col-md-5">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @if ($product->images)
                        @foreach ($product->images as $key => $productImage )
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/products/large/'.$productImage->title) }}" class="d-block w-100" alt="Image">
                        </div>
                        @endforeach
                        @else
                        <img src="{{ asset('storage/image.png') }}" class="d-block w-100" alt="Image">
                        @endif
                       
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="bg-light right">
                    <h1>{{ $product->title}}</h1>
                    <div class="d-flex mb-3">
                        <div class="small star-rating mr-2" title="">   
                            <div class="back-stars">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <div class="front-stars" style="width: {{$ratingPercentage}}%">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <small class="small pb-1">
                             ({{ $productRatingCount }} Review{{ $productRatingCount != 1 ? 's' : '' }})
                        </small>
                    </div>
                    @if ($product->compare_price > 0)
                        
                    <h2 class="price text-secondary"><del>${{$product->compare_price}}</del></h2>
                    @endif
                    <h2 class="price ">${{$product->price}}</h2>
                    <p>
                        {!! $product->short_description !!}
                    </p>
                    
                    <div class="product-action">
                        @if ($product->track_qty)
                        @if ($product->qty > 0)
                        <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                            <i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART
                        </a>
                        @else
                        <a href="javascript:void(0);" class="btn btn-dark disabled">
                            <i class="fas fa-ban"></i> &nbsp;Out of Stock
                        </a>
                        @endif
                        @else
                        <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                            <i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART
                        </a>
                        @endif
                    </div>
                    
                </div>
            </div>
            

            <div class="col-md-12 mt-5">
                <div class="bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <p>{!! $product->description !!}</p>
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            <p>{!! $product->shipping_returns !!} </p>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <div class="col-md-8">
                                <div class="row">
                                    <h3 class="h4 pb-3">Write a Review</h3>
                                    <form id="ratingForm" method="POST">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="username" id="name" placeholder="Name" >
                                            <!-- Error message for Name -->
                                            <p class="error-message" id="error-name" style="color: red; display: none;"></p>
                                        </div>
                                        
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                            <!-- Error message for Email -->
                                            <p class="error-message" id="error-email" style="color: red; display: none;"></p>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label>Rating</label>
                                            <br>
                                            <div  class="rating" style="width: 10rem">
                                                <input id="rating-5" type="radio" name="rating" value="5"  /><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-4" type="radio" name="rating" value="4" /><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-3" type="radio" name="rating" value="3" /><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-2" type="radio" name="rating" value="2" /><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-1" type="radio" name="rating" value="1" /><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                                            </div>
                                            <!-- Error message for Rating -->
                                            <p class="error-message" id="error-rating" style="color: red; display: none;"></p>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="review">How was your overall experience?</label>
                                            <textarea name="review" id="review" class="form-control" cols="30" rows="10" placeholder="How was your overall experience?" ></textarea>
                                            <!-- Error message for Review -->
                                            <p class="error-message" id="error-review" style="color: red; display: none;"></p>
                                        </div>
                                        
                                        <div>
                                            <button type="submit" class="btn btn-dark">Submit</button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-5">
                            <div class="overall-rating mb-3">
                                <div class="d-flex">
                                    <h1 class="h3 pe-3">{{ $averageRating }}</h1>
                                    <div class="star-rating mt-2" title="">   
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <div class="front-stars" style="width: {{$ratingPercentage}}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-2 ps-2">
                                        ({{ $productRatingCount }} Review{{ $productRatingCount != 1 ? 's' : '' }})
                                    </div>
                                </div>
                            </div>
                            @if ($product->ratings->isNotEmpty())
                                @foreach ($product->ratings as $rating)
                                @php
                                    $percentage = ($rating->rating*100)/5
                                @endphp
                                <div class="rating-group mb-4">
                                    <span><strong>{{ $rating->username}}</strong></span>
                                    <div class="star-rating mt-2" title="">
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <div class="front-stars" style="width: {{$percentage}}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-3">
                                        <p>{{ $rating->review }}</p>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                
                            @endif
                           
                        </div>
                        
                    </div>
                </div>
            </div> 
        </div>           
    </div>
</section>

@if ($relatedProducts->isNotEmpty())
<section class="pt-5 section-8">
    <div class="container">
        <div class="section-title">
            <h2>Related Products</h2>
        </div> 
        <div class="col-md-12">
            <div id="related-products" class="carousel">
              
                @foreach ($relatedProducts as $relProduct)
                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ route('shop.product', $relProduct->slug)}}" class="product-img">
                                @if($relProduct->images->isNotEmpty())
                                <img src="{{ asset($relProduct->images->first()->path) }}" alt="{{ $relProduct->slug }}" class="img-thumbnail" width="50">
                            @else
                                <img src="{{ asset('/images/image.png') }}" alt="No Image" class="img-thumbnail" width="50">
                            @endif
                            </a>
                            <a class="whishlist" href="javascript:void(0);" onclick="addToWishlist({{ $relProduct->id }});">
                                <i class="far fa-heart"></i>
                            </a>                        

                            <div class="product-action">
                                @if ($relProduct->track_qty)
                                @if ($relProduct->qty > 0)
                                <a href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});" class="btn btn-dark">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @else
                                <a href="javascript:void(0);" class="btn btn-dark disabled">
                                    <i class="fa fa-ban"></i> Out of Stock
                                </a>
                                @endif
                                @else
                                <a href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});" class="btn btn-dark">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @endif                       
                                
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="product.php">{{ $relProduct->title}}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{ $relProduct->price}}</strong></span>
                                @if ($relProduct->compare_price > 0)
                                <span class="h6 text-underline"><del>${{ $relProduct->compare_price}}</del></span>
                                @endif 
                            </div>
                        </div>                        
                    </div>                                               
                </div>  
                @endforeach     
            </div>
        </div>
    </div>
</section>
@endif
@endsection
@section('customScript')
<script>
    var isAuthenticated = @json(auth()->check());
    var loginUrl = "{{ route('login') }}"; // The route to the login page
    $(document).ready(function() {
        $('#ratingForm').on('submit', function(e) {
            e.preventDefault(); // Prevent form submission
    
            if (!isAuthenticated) {
                window.location.href = loginUrl; // Redirect to login page
                return; // Stop the form submission
            }
            // Serialize form data
            var formData = $(this).serialize();
           // var submitUrl =  '{{ route("shop.rating", ['slug' => $product->slug])}}',  // The correct URL for submitting the rating
    
            $.ajax({
                url: '{{ route("shop.rating", ['slug' => $product->slug])}}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status) {
                        alert(response.message);  // Show success message
                        $('#ratingForm')[0].reset();
                    } else {
                        alert(response.message);  // Show message for unauthenticated user or any other error
                        if (response.redirect) {
                            window.location.href = response.redirect;  // Redirect to login page
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Handle and display validation errors
                        $.each(errors, function(field, messages) {
                            // Add error message next to the respective input field
                            $('#'+field+'_error').text(messages[0]);
                        });
                    }
                }
            });
            
        });
    });
    
    </script>
    
@endsection