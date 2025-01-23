<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ecommerce') }}</title>
    <link rel="icon" href="{{ asset('images/app/1.svg') }}" type="image/svg+xml">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/video-js.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ion.rangeSlider.min.css')}}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
	<script src="{{ asset('assets/js/jquery-3.6.0.min.js')}}"></script>
	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />
</head>
<body data-instant-intensity="mousedown">

<div class="bg-light top-header">        
	<div class="container">
		<div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
			<div class="col-lg-4 logo">
				<a href="{{ route('home')}}" class="text-decoration-none">
					<span class="h1 text-uppercase text-primary bg-dark px-2">ecommerce</span>
					<span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Store</span>
				</a>
			</div>
			<div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
				
				
				@auth
				<a href="{{ route('account') }}" class="nav-link text-dark">My account</a>

				@else
				<a href="{{ route('login') }}" class="nav-link text-dark">Log in</a>
				@endauth			

				
				<form action="{{ route('shop', ['categorySlug' => $categorySlug ?? null, 'subCategorySlug' => $subCategorySlug ?? null]) }}" method="GET" class="d-flex align-items-center">
					<div class="input-group">
						<input type="text" name="query" placeholder="Search For Products" class="form-control" value="{{ $filters['searchQuery'] ?? null }}" aria-label="Search">
						<button type="submit" class="input-group-text">
							<i class="fa fa-search"></i>
						</button>
						<!-- Reset Button -->
						@if($filters['searchQuery'] ?? null)
							<a href="{{ route('shop', ['categorySlug' => $categorySlug ?? null, 'subCategorySlug' => $subCategorySlug ?? null]) }}" class="input-group-text text-decoration-none" title="Reset Search">
								<i class="fa fa-times"></i>
							</a>
						@endif
					</div>
				</form>
			</div>		
		</div>
	</div>
</div>

<header class="bg-dark">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="index.php" class="text-decoration-none mobile-logo">
				<span class="h2 text-uppercase text-primary bg-dark">ecommerce</span>
				<span class="h2 text-uppercase text-white px-2">store</span>
			</a>
			<button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      			<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				  <i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">
      			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
        			<!-- <li class="nav-item">
          				<a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
        			</li> -->

                    @if(getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        
					<li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $category->name }}
						</button>
                        @if ($category->subcategories->isNotEmpty())  
						<ul class="dropdown-menu dropdown-menu-dark">
                            @foreach ($category->subcategories as $subcategory)
                            <li><a class="dropdown-item nav-link" href="{{ route('shop', [$category->slug, $subcategory->slug])}}">{{ $subcategory->name}}</a></li>
                            @endforeach
						
						</ul>
                        @endif
					</li>
                    @endforeach
                    @endif
					
					
      			</ul>      			
      		</div>   
			<div class="right-nav py-0">
				<a href="{{ route('cart')}}" class="ml-3 d-flex pt-2">
					<i class="fas fa-shopping-cart text-primary"></i>					
				</a>
			</div> 		
      	</nav>
  	</div>
</header>

<main>
   @yield('content')
</main>

<footer class="bg-dark mt-5">
	<div class="container pb-5 pt-3">
		<div class="row">
			<div class="col-md-4">
				<div class="footer-card">
					<h3>Get In Touch</h3>
					<p>No dolore ipsum accusam no lorem. <br>
					123 Street, New York, USA <br>
					exampl@example.com <br>
					000 000 0000</p>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Important Links</h3>
					<ul>
						@if( staticPages()->isNotEmpty())
						@foreach (staticPages() as $page)
						<li><a href="{{ route('shop.page', $page->slug)}}" title="{{ $page->title }}">{{ $page->title }}</a></li>
						@endforeach
						@endif
						
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>My Account</h3>
					<ul>
						<li><a href="#" title="Sell">Login</a></li>
						<li><a href="#" title="Advertise">Register</a></li>
						<li><a href="#" title="Contact Us">My Orders</a></li>						
					</ul>
				</div>
			</div>			
		</div>
	</div>
	<div class="copyright-area">
		<div class="container">
			<div class="row">
				<div class="col-12 mt-3">
					<div class="copy-right text-center">
						<p>© Copyright 2022 Amazing Shop. All Rights Reserved</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js')}}"></script>
<script src="{{ asset('assets/js/instantpages.5.1.0.min.js')}}"></script>
<script src="{{ asset('assets/js/lazyload.17.6.0.min.js')}}"></script>
<script src="{{ asset('assets/js/slick.min.js')}}"></script>
<script src="{{ asset('assets/js/custom.js')}}"></script>
<script src="{{ asset('assets/js/ion.rangeSlider.min.js')}}"></script>
<script>
	window.onscroll = function() {myFunction()};

	var navbar = document.getElementById("navbar");
	var sticky = navbar.offsetTop;

	function myFunction() {
		if (window.pageYOffset >= sticky) {
			navbar.classList.add("sticky")
		} else {
			navbar.classList.remove("sticky");
		}
	}
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	
	function addToCart(id) {
		$.ajax({
			url: '{{ route("cart.add") }}', // Route to handle adding product to cart
			type: 'POST',
			data: { 
				id: id, // Product ID
				_token: '{{ csrf_token() }}' // CSRF token for security
			},
			dataType: 'json',
			success: function(response) {
				if (response.status === true) {
					// Redirect to the cart page if the product is successfully added
					window.location.href = "{{ route('cart') }}";
				} else {
					// Display the message if there's an issue
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				// Handle any errors that occur
				console.error(error);
				alert('An error occurred while adding the product to the cart. Please try again.');
			}
		});
	}
	
	
		function addToWishlist(productId) {
			$.ajax({
				url: '/wishlist/' + productId,  // API endpoint to handle the request
				type: 'POST',
				data: {
					product_id: productId,
					"_token": "{{ csrf_token() }}"  // CSRF Token for security
				},
				success: function(response) {
					// Handle success response
					if(response.success) {
						alert(response.message);  // Show success message
					}
				},
				error: function(xhr, status, error) {
					// Handle error response
					alert('There was an error adding the product to your wishlist.');
					console.log(error);
				}
			});
		}
	
	
</script>

</script>
@yield('customScript')
</body>
</html>