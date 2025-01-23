<ul id="account-panel" class="nav nav-pills flex-column">
    <li class="nav-item {{ Request::routeIs('account') ? 'active' : '' }}">
        <a href="{{ route('account') }}" class="nav-link font-weight-bold " role="tab" aria-controls="tab-login" aria-expanded="false">
            <i class="fas fa-user-alt"></i> My Profile
        </a>
    </li>
    <li class="nav-item {{ Request::routeIs('my-orders') ? 'active' : '' }}">
        <a href="{{ route('my-orders') }}" class="nav-link font-weight-bold " role="tab" aria-controls="tab-register" aria-expanded="false">
            <i class="fas fa-shopping-bag"></i> My Orders
        </a>
    </li>
    <li class="nav-item {{ Request::routeIs('wishlist') ? 'active' : '' }}">
        <a href="{{ route('wishlist') }}" class="nav-link font-weight-bold " role="tab" aria-controls="tab-register" aria-expanded="false">
            <i class="fas fa-heart"></i> Wishlist
        </a>
    </li>
    <li class="nav-item {{ Request::routeIs('change-password') ? 'active' : '' }}">
        <a href="{{ route('change-password') }}" class="nav-link font-weight-bold " role="tab" aria-controls="tab-register" aria-expanded="false">
            <i class="fas fa-lock"></i> Change Password
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('logout') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="nav-link font-weight-bold " role="tab" aria-controls="tab-register" aria-expanded="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </li>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
