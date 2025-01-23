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
				<!-- Logo -->
				<div class="col-lg-4 logo">
					<a href="{{ route('home') }}" class="text-decoration-none">
						<span class="h1 text-uppercase text-primary bg-dark px-2">laravel</span>
						<span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">ecommerce</span>
					</a>
				</div>
	
				<!-- Search Bar and User Actions -->
				<div class="col-lg-8 d-flex justify-content-end align-items-center">
					<!-- User Account/Login Link -->
					<div class="me-4">
						@auth
							<a href="{{ route('account') }}" class="nav-link text-dark">My Account</a>
						@else
							<a href="{{ route('login') }}" class="nav-link text-dark">Log In</a>
						@endauth
					</div>
	
					<!-- Search Form -->
					<form action="{{ route('shop', ['categorySlug' => $categorySlug ?? null, 'subCategorySlug' => $subCategorySlug ?? null]) }}" method="GET" class="d-flex align-items-center me-4">
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
	
					<!-- Shopping Cart Icon -->
					<div class="right-nav">
						<a href="{{ route('cart') }}" class="d-flex align-items-center text-decoration-none">
							<i class="fas fa-shopping-cart text-primary fa-lg"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

   @yield('content')

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
						@auth
							<!-- Links for logged-in users -->
							<li><a href="{{ route('account') }}" title="My Account">My Account</a></li>
							<li><a href="{{ route('my-orders') }}" title="My Orders">My Orders</a></li>
							<li>
								<form action="{{ route('logout') }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn p-0 text-white" title="Logout">Logout</button>
								</form>
							</li>
						@else
							<!-- Links for guests (not logged in) -->
							<li><a href="{{ route('login') }}" title="Login">Login</a></li>
							<li><a href="{{ route('register') }}" title="Register">Register</a></li>
						@endauth
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
						<p>© Copyright 2025 ecommerce. All Rights Reserved</p>
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