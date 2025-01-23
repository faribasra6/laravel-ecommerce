<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\shop\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request, $productId)
    {
        // Validate the request (ensure the product exists)
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = Auth::user();

        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

            if ($existingWishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is already in your wishlist.',
                ]);
            }

            $wishlist = Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist.',
                'wishlist' => $wishlist,
            ]);
    }

    public function getWishlist()
    {
        $userID = Auth::id();  // Using Auth::id() for cleaner code
    
        // Fetch the wishlist with related products, only selecting necessary columns from the product
        $wishlist = Wishlist::where('user_id', $userID)
                         ->with(['product.images']) // Eager load the product images
                            ->paginate(10);  // Paginate the results to 10 items per page
    
        // Return view with the wishlist data
        return view('shop.profile.wishlist', compact('wishlist'));
    }
    
    // In WishlistController.php
public function removeFromWishlist($productId)
{
    // Get the authenticated user
    $userId = Auth::id();

    // Find the wishlist entry for the user and the product
    $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

    // If found, delete it
    if ($wishlist) {
        $wishlist->delete();
        return response()->json(['success' => true, 'message' => 'Item removed from wishlist']);
    }

    return response()->json(['success' => false, 'message' => 'Item not found']);
}

}
