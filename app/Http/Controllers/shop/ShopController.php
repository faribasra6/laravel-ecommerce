<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\admin\Brands;
use App\Models\admin\Category;
use App\Models\admin\Page;
use App\Models\admin\Product;
use App\Models\admin\SubCategory;
use App\Models\shop\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        // Initialize selected filters with default values
        $filters = [
            'categorySelected' => null,
            'subCategorySelected' => null,
            'brandsArray' => $request->has('brand') ? explode(',', $request->get('brand')) : [],
            'priceRange' => [
                'price_min' => $request->get('price_min'),
                'price_max' => $request->get('price_max'),
            ],
            'searchQuery' => $request->get('query', ''),
            'sort' => $request->get('sort', 'latest'),
        ];
        
        // Fetch categories and brands
        $categories = Category::with('subcategories')->where('status', 1)->orderBy('name', 'ASC')->get();
        
        $brands = Brands::where('status', 1)->orderBy('name', 'ASC')->get();
        // Initialize the products query
        $productsQuery = Product::where('status', 1);
        
        // Filter by category and subcategory slugs
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $productsQuery->where('category_id', $category->id);
            $filters['categorySelected'] = $category->id;
        }
        
        if ($subCategorySlug) {
            $subcategory = SubCategory::where('slug', $subCategorySlug)->firstOrFail();
            $productsQuery->where('subcategory_id', $subcategory->id);
            $filters['subCategorySelected'] = $subcategory->id;
        }
        
        // Filter by selected brands
        if (!empty($filters['brandsArray'])) {
            $productsQuery->whereIn('brand_id', $filters['brandsArray']);
        }
        
        // Apply price filters (only if both min and max are provided)
        if (!empty($filters['priceRange']['price_min']) && !empty($filters['priceRange']['price_max'])) {
            $productsQuery->whereBetween('price', [
                $filters['priceRange']['price_min'],
                $filters['priceRange']['price_max']
            ]);
        }
        
        // Apply search filter
        if (!empty($filters['searchQuery'])) {
            $productsQuery->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['searchQuery']}%")
                ->orWhere('description', 'like', "%{$filters['searchQuery']}%");
            });
        }
        
        // Apply sorting
        switch ($filters['sort']) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'ASC');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'DESC');
                break;
            default:
                $productsQuery->orderBy('created_at', 'DESC');
                break;
        }
        
        // Get the min and max prices dynamically
        $priceRange = Product::selectRaw('MIN(price) as minPrice, MAX(price) as maxPrice')->first();
        
        // Paginate the product results
        $products = $productsQuery->paginate(9);
        
        return view('shop.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'filters' => $filters,
            'priceRange' => $priceRange,
        ]);
    }
    
    
    
    public function product($slug)  {
        $product = Product::where('slug', $slug)->with(['images', 'ratings']) ->first();

        $productRatingSum = $product->ratings->sum('rating');
        $productRatingCount = $product->ratings->count();
        $averageRating = 0.00;
        $averageRating = number_format(($productRatingCount > 0 ? $productRatingSum / $productRatingCount : 0), 2);
        $ratingPercentage = $productRatingCount > 0 ? ($averageRating / 5) * 100 : 0;
        $relatedProducts = [];
        if ($product->related_products != 0) {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('images')->get();
        }
        
        if($product == NULL) {
            abort(404);
        }else{
            $data['product'] = $product;
            $data['relatedProducts'] = $relatedProducts;
            $data['productRatingSum'] = $productRatingSum;
            $data['productRatingCount'] = $productRatingCount;
            $data['averageRating'] = $averageRating;
            $data['ratingPercentage'] = $ratingPercentage;
            return view('shop.product', $data);
        }
    }
    
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if (!$page) {
            abort(404, 'Page not found.');
        }
        return view('shop.page', compact('page'));
    }
    
    public function contactmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false, 
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $mailData = [
            'subject' => $request->subject,
            'name' => $request->name,
            'email' => $request->email, // Include the user
            'message' => $request->message,
        ];

        
        Mail::to('faribasra6@gmail.com')->send(new ContactMail($mailData));

        return response()->json(['status' => true, 'message' => 'Message sent successfully!']);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false, 
            'message' => 'Failed to send the message. Please try again.'. $e,
        ], 500);
    }
}


    public function submitRating(Request $request, $slug)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Return a JSON response for unauthenticated users with a message and a redirect URL to the sign-in page
            return response()->json([
                
                'status' => false,
                'message' => 'You need to sign in first.',
                'redirect' => route('login')  // Adjust route name if necessary
            ]);
        }
        
        // Find the product by slug
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Validate the rating and review input
        $validated = $request->validate([
            'username' => 'required|string|min:3',
            'email' => 'required|email',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        
        // Check if the user has already rated this product using their email
        $existingRating = ProductRating::where('product_id', $product->id)
        ->where('email', $validated['email'])
        ->first();
        
        // If the user already submitted a rating, update it
        if ($existingRating) {
            $existingRating->update([
                'username' => $validated['username'],
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'status' => 'pending',
            ]);
            
            $message = 'Your rating has been updated successfully!';
        } else {
            // If the user hasn't rated the product, create a new rating
            ProductRating::create([
                'product_id' => $product->id,
                'user_id' => Auth::user()->id, // Assuming the user is logged in
                'username' => $validated['username'],
                'email' => $validated['email'],
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'status' => 'pending', // Or any other status you want to set
            ]);
            
            $message = 'Thank you for your rating!';
        }
        
        // Flash a success message into the session
        session()->flash('success', $message);
        
        // Return a JSON success response
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
    
     
}
