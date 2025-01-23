@extends('shop.layouts.app');

@section('content');
<!-- Header Section -->
<header class="homepage-header bg-dark d-flex align-items-center justify-content-center" style="height: 400px;">
    <!-- Container for Content -->
    <div class="container">
        <!-- Centered Search Bar -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('shop') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="query" placeholder="Search For Products" class="form-control form-control-lg border-0 shadow-sm" aria-label="Search">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<main>
  
    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                    </div>                    
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Categories</h2>
            </div>  
            <div class="row pb-3">
                @if (getCategories()->isNotEmpty())
                @foreach (getCategories() as $category)
                <div class="col-lg-3">
                    <div class="cat-card">
                        <div class="left">
                            @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name}}" class="img-fluid">
                        @else
                            <img src="{{ asset('storage/image.png') }}" alt="No Image" class="img-fluid">
                        @endif
                        
                        </div>
                        <div class="right">
                            <div class="cat-data">
                                <h2>{{ $category->name }}</h2>
                                <p>100 Products</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @endif         
            </div>
        </div>  
    </section>
    
    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>    
            <div class="row pb-3">
                @if ($featuredProducts->isNotEmpty())
                @foreach ($featuredProducts as $product)
                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ route('shop.product', $product->slug)}}" class="product-img">
                                @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/'.$product->images->first()->path) }}" alt="{{ $product->slug }}" class="img-thumbnail" width="50">
                            @else
                                <img src="{{ asset('storage/image.png') }}" alt="No Image" class="img-thumbnail" width="50">
                            @endif
                            </a>
                            <a class="whishlist" href="javascript:void(0);" onclick="addToWishlist({{ $product->id }});">
                                <i class="far fa-heart"></i>
                            </a>                            

                            <div class="product-action">
                                @if ($product->track_qty)
                                @if ($product->qty > 0)
                                <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @else
                                <a href="javascript:void(0);" class="btn btn-dark disabled">
                                    <i class="fa fa-ban"></i> Out of Stock
                                </a>
                                @endif
                                @else
                                <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @endif                       
                                
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{ route('shop.product', $product->slug)}}">{{ $product->title}}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{ $product->price}}</strong></span>
                                @if ($product->compare_price > 0)
                                <span class="h6 text-underline"><del>${{ $product->compare_price}}</del></span>
                                @endif 
                            </div>
                        </div>                        
                    </div>                                               
                </div>  
                @endforeach
                @endif
               
                
                            
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest Produsts</h2>
            </div>    
            <div class="row pb-3">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                    @php
                        $productImage = $product->images->first();
                    @endphp
                    <div class="col-md-3">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('shop.product', $product->slug)}}" class="product-img">
                                    @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/'.$product->images->first()->path) }}" alt="{{ $product->slug }}" class="card-img-top" >
                                @else
                                    <img src="{{ asset('/images/image.png') }}" alt="No Image" class="card-img-top">
                                @endif
                                </a>
                                <a class="whishlist" href="javascript:void(0);" onclick="addToWishlist({{ $product->id }});">
                                    <i class="far fa-heart"></i>
                                </a>      
                                <div class="product-action">
                                    @if ($product->track_qty)
                                    @if ($product->qty > 0)
                                    <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    @else
                                    <a href="javascript:void(0);" class="btn btn-dark disabled">
                                        <i class="fa fa-ban"></i> Out of Stock
                                    </a>
                                    @endif
                                    @else
                                    <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    @endif                       
                                    
                                </div>
                                
                            </div>                        
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{ route('shop.product', $product->slug)}}">{{ $product->title}}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>${{ $product->price}}</strong></span>
                                    @if ($product->compare_price > 0)
                                    <span class="h6 text-underline"><del>${{ $product->compare_price}}</del></span>
                                    @endif 
                                </div>
                            </div>                        
                        </div>                                               
                    </div>  
                    @endforeach                        
                   
                @endif
               
                           
            </div>
        </div>
    </section>
</main>
@endsection