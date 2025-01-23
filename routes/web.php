<?php

use App\Http\Controllers\admin\BrandsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Dashboard;
use App\Http\Controllers\admin\Categories;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SubCategories;
use App\Http\Controllers\admin\TempImages;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\shop\AccountManager;
use App\Http\Controllers\shop\CartController;
use App\Http\Controllers\shop\ShopController;
use App\Http\Controllers\shop\WishlistController;
use App\Models\admin\Product;
use App\Models\shop\Order;
use Illuminate\Support\Facades\Auth;

 //------------------------ Guest Access Management---------------//
Route::get('/', function () {
    $featuredProducts = Product::where('is_featured', 1)
    ->orderBy('id', 'DESC')->where('status', 1)->take(8)->get();

    $latestProducts = Product::orderBy('id', 'DESC')
    ->where('status', 1)->take(8)->get();

    $data['featuredProducts'] = $featuredProducts;
    $data['latestProducts'] = $latestProducts;
    return view('shop.index', $data);
})->name('home');

Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])
    ->where([
        'categorySlug' => '[a-zA-Z0-9-_]+',
        'subCategorySlug' => '[a-zA-Z0-9-_]+'
    ])
    ->name('shop');

     

Route::get('/product/{slug}', [ShopController::class, 'product'] )->name('shop.product');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'cart'])->name('cart');
    Route::post('/add', [CartController::class, 'create'])->name('cart.add');
    Route::put('/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove', [CartController::class, 'destroy'])->name('cart.delete');
});
Route::get('/page/{slug}', [ShopController::class, 'page'])->name('shop.page');
Route::post('/contact/submit', [ShopController::class, 'contactmail'])->name('contact.submit');

Route::redirect('/dashboard', '/')->name('dashboard');


// -------------------- Authorized User Access Management----------------------//
Route::middleware([ 'auth:sanctum', config('jetstream.auth_session'), 'verified', ])->group(function () {
    
    // --------------- Invoice mail----------------------//
    Route::post('/send-invoice-email/{orderID}', [OrderController::class , 'sendInvoiceEmail'])->name('send.invoice.email');
    
    // ================ Account Manager=======================//
    Route::get('/account', [AccountManager::class, 'index'])->name('account');
    Route::put('/account', [AccountManager::class, 'updateUser'])->name('update_user');
    Route::post('/customer-address', [AccountManager::class, 'updateOrCreate'])->name('customer-address.save');
    Route::get('/my-orders', [AccountManager::class, 'orders'])->name('my-orders');
    Route::get('/my-orders/{order_id}', [AccountManager::class, 'orderDetail'])->name('order-detail');
    Route::get('/change-password', [AccountManager::class, 'password'])->name('change-password');
    Route::put('/change-password', [AccountManager::class, 'changePassword'])->name('update_password');
    
    //================== Order Manager Customer========================//
    Route::get('/checkout', [CartController::class, 'checkout'] )->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'] )->name('cart.processCheckout');
    Route::get('/thanks/{orderId}', [CartController::class, 'thankyou'] )->name('thanks');
    Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('cart.discount');
    
    //============================ WhishList Manager================//
    Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist');
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::post('/wishlist/{productId}', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');

    Route::post('/product/{slug}/rating', [ShopController::class, 'submitRating'])->name('shop.rating');

});

//----------------- Administarttor Access Management------------------//
Route::middleware(['AuthUser'])->group(function () {
    route::get('/administrator', [Dashboard::class, 'index'] )->name('administrator');

    // -----------Temp Images Handling -----------//
    Route::post('/temp-images', [TempImages::class, 'store'])->name('image.store');; // Store a temporary image
    Route::get('/temp-images/{id}', [TempImages::class, 'show'])->name('image.show');; // Show a temporary image
    Route::delete('/temp-images/{id}', [TempImages::class, 'destroy'])->name('image.delete');; // Delete a temporary image

    // -------------- Category Management------------//
    Route::get('/category', [Categories::class, 'index'])->name('categories');
    Route::get('/category/create', [Categories::class, 'create'])->name('categories.create');
    Route::post('/category', [Categories::class, 'store'])->name('categories.store');
    Route::get('/category/{id}/edit', [Categories::class, 'edit'])->name('categories.edit');
    Route::put('/category/{id}', [Categories::class, 'update'])->name('categories.update');
    Route::delete('/category/delete/{id}', [Categories::class, 'destroy'])->name('categories.delete');

    // -------------- Sub Category Management------------//
    Route::get('/subcategory', [SubCategories::class, 'index'])->name('subcategories');
    Route::get('/subcategory/create', [SubCategories::class, 'create'])->name('subcategories.create');
    Route::post('/subcategory', [SubCategories::class, 'store'])->name('subcategories.store');
    Route::get('/subcategory/{id}/edit', [SubCategories::class, 'edit'])->name('subcategories.edit');
    Route::put('/subcategory/{id}', [SubCategories::class, 'update'])->name('subcategories.update');
    Route::delete('/subcategory/delete/{id}', [SubCategories::class, 'destroy'])->name('subcategories.delete');

    // -------------- Brands Management------------//
    Route::get('/brands', [BrandsController::class, 'index'])->name('brands');
    Route::get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandsController::class, 'store'])->name('brands.store');
    Route::get('/brands/{id}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{id}', [BrandsController::class, 'update'])->name('brands.update');
    Route::delete('/brands/delete/{id}', [BrandsController::class, 'destroy'])->name('brands.delete');

    // -------------- Product Management------------//
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');
    Route::get('/getSubcategories/{id}', [ProductController::class, 'getSubcategories'])->name('getSubcategories');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');
    
    Route::get('/ratings', [ProductController::class, 'product_rating'])->name('products.rating');
    Route::put('/ratings/{id}/update-status', [ProductController::class, 'updateStatus'])->name('ratings.updateStatus');

    // -------------- ProductImage Management------------//
    
    Route::prefix('products/{product}/images')->group(function () {
        // Image CRUD operations
        Route::post('/create', [ProductImageController::class, 'store'])->name('product_images.store');
        Route::delete('/{image}', [ProductImageController::class, 'destroy'])->name('product_images.destroy');
    });
    // ----------------- Shipping Management ----------------//
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping');
    Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
    Route::delete('/shipping/delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');
    
    // ==============Discount Coupon=============//
    
    Route::prefix('discount')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('discount');
        Route::get('/create', [CouponController::class, 'create'])->name('discount.create');
        Route::post('/', [CouponController::class, 'store'])->name('discount.store');
        Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('discount.edit');
        Route::put('/{id}', [CouponController::class, 'update'])->name('discount.update');
        Route::delete('/{id}', [CouponController::class, 'destroy'])->name('discount.delete');
    });
    
    // ----------------- Shipping Management ----------------//
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.detail');
    Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/', [UsersController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.delete');
    });

    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('pages');
        Route::get('/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/', [PageController::class, 'store'])->name('pages.store');
        Route::get('/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/{id}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/{id}', [PageController::class, 'destroy'])->name('pages.delete');
        Route::post('/change-status', [PageController::class, 'changeStatus'])->name('pages.changeStatus');

    });

});
