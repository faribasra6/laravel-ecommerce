@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account')}}">My Account</a></li>
                    <li class="breadcrumb-item">Wishlist</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('shop.profile.account-panel')
                </div>
                <div class="col-md-9">
                    <!-- Error message container -->
                    <div id="error-message" class="alert alert-danger" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: auto; max-width: 400px;">
                        <strong>Error!</strong> Something went wrong. Please try again.
                    </div>
                    

                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">My Wishlist</h2>
                        </div>
                        @if ($wishlist->isNotEmpty())
                        <div class="card-body p-4">
                            @foreach ($wishlist as $item)
                            <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom" id="wishlist-item-{{ $item->product->id }}">
                                <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{ route('shop.product', $item->product->slug)}}" style="width: 10rem;">
                                        @if($item->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->title }}">
                                        @else
                                        <img src="{{ asset('storage/image.png') }}" alt="{{ $item->product->title }}">
                                        @endif
                                    </a>
                                    
                                    <div class="pt-2">
                                        <h3 class="product-title fs-base mb-2">
                                            <a href="{{ route('shop.product', $item->product->slug)}}">{{ $item->product->title }}</a>
                                        </h3>
                                        <div class="fs-lg text-accent pt-2">
                                            ${{ number_format($item->product->price) }}.<small>{{ number_format($item->product->price - floor($item->product->price), 2) * 100 }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                    <button class="btn btn-outline-danger btn-sm remove-wishlist" type="button" data-product-id="{{ $item->product->id }}">
                                        <i class="fas fa-trash-alt me-2"></i> Remove
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="card-body p-4 text-center">
                            <!-- Bucket Icon (Font Awesome) -->
                            <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
                            <h2 class="text-center text-muted">Your wishlist is empty</h2>
                            <!-- Optional subtitle -->
                            <p class="text-center text-muted">Start adding your favorite products to the wishlist!</p>
                        </div>
                        @endif
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('customScript')
<script>
    // Attach click event to remove buttons
    document.querySelectorAll('.remove-wishlist').forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const wishlistItem = document.getElementById('wishlist-item-' + productId);

            // Perform the AJAX request to remove the item from the wishlist
            fetch(`/wishlist/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the item from the DOM if successfully deleted
                    wishlistItem.remove();

                    // Check if wishlist is empty and update the page
                    if (document.querySelectorAll('.wishlist-item').length === 0) {
                        document.querySelector('.card-body').innerHTML = `
                            <div class="card-body p-4 text-center">
                                <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
                                <h2 class="text-center text-muted">Your wishlist is empty</h2>
                                <p class="text-center text-muted">Start adding your favorite products to the wishlist!</p>
                            </div>
                        `;
                    }

                    // Optionally show a success message
                    alert('Product removed from wishlist!');
                } else {
                    showErrorMessage('Failed to remove product!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('There was an error removing the item!');
            });
        });
    });

    // Function to show error message and fade out after 3 seconds
    function showErrorMessage(message) {
        const errorMessageDiv = document.getElementById('error-message');
        errorMessageDiv.textContent = message;
        errorMessageDiv.style.display = 'block'; // Show the message

        // Fade out the message after 3 seconds
        setTimeout(function() {
            errorMessageDiv.style.display = 'none';
        }, 3000); // 3 seconds
    }
</script>

@endsection