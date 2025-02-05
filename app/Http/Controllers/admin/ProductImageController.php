<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductImageController extends Controller
{
   public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file type and size
        'product_id' => 'required|exists:products,id', // Ensure valid product_id exists
    ]);

    // Get the uploaded image and the associated product_id
    $image = $request->file('image'); // Get the image from the request
    $product_id = $request->product_id; // Get the product_id from the request

    if ($image) {
        // Generate a unique image title using the product_id and timestamp
        $extension = $image->getClientOriginalExtension(); // Get the file extension
        $imageTitle = "{$product_id}-" . time() . ".{$extension}"; // Use product_id and timestamp to make the title unique

        // Define the storage paths for large and small images
        $largeDestPath = "products/large/{$imageTitle}"; // Path for large image
        $smallDestPath = "products/small/{$imageTitle}"; // Path for small image

        // Use Intervention Image to process the large image
        $thumb = Image::read($image); // Create an image instance
        $thumb->resize(1200, 800, function ($constraint) {
            $constraint->aspectRatio(); // Maintain aspect ratio for large image
        });

        // Store the large image in the public disk
        Storage::disk('public')->put($largeDestPath, (string) $thumb->encode());

        // Create the small version (300x300) for thumbnails
        $thumb->resize(300, 300); // Resize for small thumbnail

        // Store the small image in the public disk
        Storage::disk('public')->put($smallDestPath, (string) $thumb->encode());

        // Save the image details to the ProductImage model
        $productImage = ProductImage::create([
            'product_id' => $product_id,
            'title' => $imageTitle,
            'path' => $smallDestPath, // Store the path for the small image
            'type' => 'image/' . $extension, // Store the type (mime type)
        ]);

        // Return a success response with the image details
        return response()->json([
            'status' => 'success',
            'image_id' => $productImage->id, // The newly created image ID
            'image_path' => asset("storage/{$smallDestPath}"), // Path to the small image
            'message' => 'Image uploaded and processed successfully',
        ]);
    }

    // Return an error response if no image is uploaded
    return response()->json(['status' => 'error', 'message' => 'No image uploaded']);
}


    public function destroy($productId, $imageId)
{
    try {
        // Find the product and image
        $product = Product::findOrFail($productId);
        $productImage = $product->images()->findOrFail($imageId); // This assumes the image is related to the product

        // Delete the image from storage
        $largeImagePath = "products/large/{$productImage->title}";
        $smallImagePath = "products/small/{$productImage->title}";

        if (Storage::disk('public')->exists($largeImagePath)) {
            Storage::disk('public')->delete($largeImagePath);
        }

        if (Storage::disk('public')->exists($smallImagePath)) {
            Storage::disk('public')->delete($smallImagePath);
        }

        // Delete the image from the database
        $productImage->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Image deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error deleting image',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
