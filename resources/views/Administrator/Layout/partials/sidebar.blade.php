<aside class="sb-sidenav accordion sb-sidenav-dark elevation-4" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link" href="{{ url('administrator')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </a>
            <a class="nav-link" href="{{ route('categories')}}">
                <div class="sb-nav-link-icon">
                    <i class="nav-icon fas fa-file-alt"></i>
                </div>
                Category
            </a>
            <a class="nav-link" href="{{ route('subcategories')}}">
                <div class="sb-nav-link-icon">
                    <i class="nav-icon fas fa-file-alt"></i>
                </div>
                Sub-Category
            </a>
            
           
            
            
            <a class="nav-link" href="{{ route('brands')}}">
                <svg class="sb-nav-link-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" style="width: 16px; height: 16px;"> <!-- SVG Icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Brands
            </a>

            <a class="nav-link" href="{{ route('products')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-tags"></i>
                </div>
                Products
            </a>

            <a class="nav-link" href="{{ route('products.rating')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-star"></i>
                </div>
                Ratings
            </a>
         
            
            <a class="nav-link" href="{{ url('shipping')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-truck"></i>
                </div>
                Shipping
            </a>
            
            <a class="nav-link" href="{{ url('orders')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                Orders
            </a>
            
            <a class="nav-link" href="{{ url('discount')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-percent"></i>
                </div>
                Discount
            </a>
            
            <a class="nav-link" href="{{ url('users')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-users"></i>
                </div>
                Users
            </a>
            
            <a class="nav-link" href="{{ url('pages')}}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                Pages
            </a>
            
        </div>
    </div>
    
</aside>
