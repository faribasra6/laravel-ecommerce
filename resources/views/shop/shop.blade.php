@extends('shop.layouts.app')
@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a class="white-text" href="{{ route('shop')}}">Shop</a></li>
          
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @if ($categories->isNotEmpty())
                            @foreach ($categories as $key =>  $category)
                            <div class="accordion-item">
                                @if ($category->subcategories->isNotEmpty())
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key}}" aria-expanded="false" aria-controls="collapseOne">
                                        {{$category->name}}
                                    </button>
                                </h2>
                                @else
                                <a href="{{ route('shop', $category->slug)}}" class="nav-item nav-link {{($filters['categorySelected'] == $category->id) ? 'text-primary': ''}}">{{$category->name}}</a>
                                @endif
                                @if ($category->subcategories->isNotEmpty())
                                <div id="collapseOne-{{ $key}}" class="accordion-collapse collapse {{($filters['categorySelected'] == $category->id) ? 'show': ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                            @foreach ($category->subcategories as $subcategory)
                                            <a href="{{ route('shop', [$category->slug, $subcategory->slug])}}" class="nav-item nav-link {{($filters['subCategorySelected'] == $subcategory->id) ? 'text-primary': ''}}">{{$subcategory->name}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div> 
                            @endforeach 
                            @endif            
                        </div>
                    </div>
                </div>
                
                <div class="sub-title mt-5">
                    <h2>
                        Brand
                    </h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($brands->isNotEmpty())
                        @foreach ($brands as $brand)
                        <div class="form-check mb-2">
                            <input {{ (in_array($brand->id, $filters['brandsArray']) ? 'checked' : '') }} 
                            class="form-check-input brand-label"
                            type="checkbox" 
                            name="brand[]" 
                            value="{{ $brand->id }}" 
                            id="brand-{{ $brand->id }}">
                            <label class="form-check-label" for="brand-{{$brand->id}}">
                                {{$brand->name}}
                            </label>
                        </div>
                        @endforeach
                        @endif        
                    </div>
                </div>
                
                <div class="sub-title mt-5">
                    <h2>Price</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value="" />            
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <select name="sort" id="sort" class="form-control">
                                    <option value="latest" {{ $filters['sort'] == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="price_asc" {{ $filters['sort'] == 'price_asc' ? 'selected' : '' }}>Price Low</option>
                                    <option value="price_desc" {{ $filters['sort'] == 'price_desc' ? 'selected' : '' }}>Price High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                    @php
                        $productImage = $product->images->first();
                    @endphp
                    <div class="col-md-4">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('shop.product', $product->slug)}}" class="product-img">
                                    @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/'.$productImage->path) }}" alt="{{ $product->slug }}" class="card-img-top">
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
                                <a class="h6 link" href="{{ route('shop.product', $product->slug)}}">{{$product->title}}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>${{ $product->price}}</strong></span>
                                    @if ($product->compare_price > 0)
                                    <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                                    @endif
                                </div>
                            </div>                        
                        </div>                                               
                    </div>  
                    @endforeach
                    
                    @endif
                    <div class="col-md-12 pt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $products->count() }} out of {{ $products->total() }} products
                            </div>
                            <div>
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                    
                    
                   
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('customScript')
<script>


    // Access priceRange data from the backend
    var minPrice = {{ $priceRange->minPrice }};
    var maxPrice = {{ $priceRange->maxPrice }};
    var selectedMin = {{ $filters['priceRange']['price_min'] ?? $priceRange->minPrice }};
    var selectedMax = {{ $filters['priceRange']['price_max'] ?? $priceRange->maxPrice }};

    

var rangeSlider = $(".js-range-slider").ionRangeSlider({
    type: "double",
    min: minPrice,
    max: maxPrice,
    from: selectedMin,
    to: selectedMax,
    step: 10,
    skin: "round",
    grid: true,
    max_postfix: "+",
    prefix: "AED",
    onFinish: function () {
        apply_filters();
    }
});

var slider = $(".js-range-slider").data("ionRangeSlider");

$(".brand-label").change(function () {
    apply_filters();
});

$("#sort").change(function() {
    apply_filters();
});

function apply_filters() {
    var brands = [];

    $(".brand-label:checked").each(function () {
        brands.push($(this).val());
    });

 

    var url = '{{ url()->current() }}?';

    url += 'price_min=' + slider.result.from + '&price_max=' + slider.result.to;

    if (brands.length > 0) {
        url += '&brand=' + brands.join(',');
    }
    //Sort filter
    url += '&sort='+ $('#sort').val();

    window.location.href = url;
}

</script>
@endsection