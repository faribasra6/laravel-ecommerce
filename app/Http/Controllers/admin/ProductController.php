<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Brands;
use App\Models\admin\Category;
use App\Models\admin\Product;
use App\Models\admin\ProductImage;
use App\Models\admin\SubCategory;
use App\Models\admin\TempImage;
use App\Models\shop\ProductRating;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'images'])->paginate(10);
        return view('Administrator.products.list', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brands::all();
        return view('Administrator.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateProduct($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product. ' . implode(", ", $validator->errors()->all())
            ]);
        }

        try {
            $product = Product::create($request->only([
                'title', 'slug', 'description','short_description','shipping_returns', 
                'price', 'compare_price','sku', 'barcode', 'track_qty', 'qty', 'status',
                'category_id', 'brand_id', 'is_featured', 'subcategory_id'
            ]));

            if (is_array($request->related_products)) {
                $product->related_products = implode(',', $request->related_products);
            }
            $product->save();

            // Handle uploaded images
            $this->handleImages($request->image_array ?? [], $product);

            session()->flash('success', 'Product created successfully!');
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product. ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $brands = Brands::orderBy('name', 'ASC')->get();
        
        $relatedProducts = [];
        if ($product->related_products != 0) {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->get();
        }
        
        $data = [];
        $data['product'] = $product;
        $data['subcategories'] = $subcategories;
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['relatedProducts'] = $relatedProducts; 
        
        
        return view('Administrator.products.edit', $data);
    }
    

    public function update(Request $request, $id)
    {
        $validator = $this->validateProduct($request, $id);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product. ' . implode(", ", $validator->errors()->all())
            ]);
        }
        try {
            $product = Product::findOrFail($id);

            $product->update($request->only([
                'title', 'slug', 'description', 'short_description', 'shipping_returns',
                'price', 'compare_price', 'sku', 'barcode', 'track_qty',
                'qty', 'status', 'category_id', 'brand_id',
                'is_featured', 'subcategory_id'
            ]));

            if (isset($request->related_products) && is_array($request->related_products)) {
                $product->related_products = implode(',', $request->related_products);
            } else {
                $product->related_products = ''; // Clear related products if not set
            }
        
            $product->save();
          
           
        
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully!'
            ]);
        
       
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product. ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id)
    {
        try {
            $product = Product::with('images')->findOrFail($id);
            
            // Delete associated images
            foreach ($product->images as $image) {
                if (Storage::exists($image->path)) {
                    Storage::delete($image->path);
                }
                $image->delete();
            }
            
            // Delete the product
            $product->delete();
            
            // Flash a success message to the session
            session()->flash('success', 'Product and its images deleted successfully!');
            
            // For JSON response, return success message
            return response()->json(['success' => true, 'message' => 'Product and its images deleted successfully!']);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete product. ' . $e->getMessage());
            
            // For JSON response, return error message
            return response()->json(['success' => false, 'message' => 'Failed to delete product.']);
        }
    }
    

    private function validateProduct($request, $id = null)
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'shipping_returns' => 'nullable|string',
            'related_products' => 'nullable|array',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric|gte:price',
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'barcode' => 'nullable|string|max:255',
            'qty' => 'nullable|integer|min:0',
            'track_qty' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'subcategory_id' => 'nullable|exists:sub_categories,id'
        ]);
    }

    private function handleImages(array $imageIds, Product $product)
    {
        foreach ($imageIds as $tempImageId) {
            $tempImage = TempImage::find($tempImageId);
            if ($tempImage) {
                $imageTitle = "{$product->id}-" . time() . ".{$tempImage->file_extension}";
    
                // Read the image from storage
                $imagePath = storage_path("app/public/{$tempImage->file_path}");
                $image = Image::read($imagePath);
    
                // Process and save the large image
                $largeImage = $image->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                Storage::disk('public')->put("products/large/{$imageTitle}", (string) $largeImage->encode());
    
                // Process and save the small image
                $smallImage = $image->resize(300, 300);
                Storage::disk('public')->put("products/small/{$imageTitle}", (string) $smallImage->encode());
    
                // Save image data to the database
                ProductImage::create([
                    'product_id' => $product->id,
                    'title' => $imageTitle,
                    'path' => "products/small/{$imageTitle}",
                    'type' => 'image/' . $tempImage->file_extension,
                ]);
    
                // Optionally, delete the temporary image
                Storage::disk('public')->delete($tempImage->file_path);
                $tempImage->delete();
            }
        }
    }
    public function getSubcategories(Request $request)
    {
        if ($request->id) {
            $subcategories = SubCategory::where('category_id', $request->id)
            ->orderBy('name', 'ASC')
            ->get();
            
            return response()->json([
                'status' => 'success',
                'subcategories' => $subcategories
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Category ID is required.'
        ]);
    }

    public function getProducts(Request $request) {
        $products = [];
        
    
        if ($request->has('term') && $request->term !== "") {
            // Use Eloquent to filter products based on the search term
            $products = Product::where('title', 'like', '%' . $request->term . '%')
                ->get(['id', 'title']); // Only select the needed columns for performance
    
            // Map the results to the expected format
            $products = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->title,
                ];
            });
        }
    
        return response()->json([
            'tags' => $products,
            'status' => true,
        ]);
    }

    public function product_rating(Request $request) {
        // Fetch ratings with their associated products
        $ratings = ProductRating::with('product') // Assuming 'product' is the relationship method in ProductRating model
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    
        // Pass both ratings and products to the view
        return view('Administrator.products.rating', compact('ratings'));
    }
    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        // Find the rating
        $rating = ProductRating::findOrFail($id);
        
        // Update the status
        $rating->status = $request->status;
        $rating->save();
        
        // Return a response (optional)
        return response()->json(['message' => 'Status updated successfully']);
    }
    
    
}

