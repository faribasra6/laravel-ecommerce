<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\TempImage;
use Illuminate\Support\Facades\Storage;

class TempImages extends Controller
{
    // Store a temporary image
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' .$image->getClientOriginalExtension(); // Generate unique file name
            $filePath = $image->storeAs('temp_images', $fileName, 'public'); // Store image in public directory

            // Save image data to the database
            $tempImage = TempImage::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_extension' => $image->getClientOriginalExtension(),
                'is_active' => true, // Assuming the image is active by default
            ]);

            return response()->json([
                'message' => 'Image uploaded successfully!',
                'data' => $tempImage,
                'image_path' => asset('storage/' . $tempImage->file_path),
            ], 201);
        }

        return response()->json(['message' => 'No image uploaded.'], 400);
    }

    // Show a temporary image
    public function show($id)
    {
        $tempImage = TempImage::findOrFail($id);

        // Return image response
        return response()->json([
            'file_name' => $tempImage->file_name,
            'file_path' => asset('storage/' . $tempImage->file_path),
            'file_extension' => $tempImage->file_extension,
        ]);
    }

    // Delete a temporary image
    public function destroy($id)
    {
        $tempImage = TempImage::findOrFail($id);
        $absolutePath = public_path('storage/temp_images/' . $tempImage->file_name);

        if (file_exists($absolutePath) && is_file($absolutePath)) {
            // Delete the file
            unlink($absolutePath);
            echo "File deleted successfully using PHP's unlink().\n";
            // Delete the record from the database
            $tempImage->delete();
        } else {
            echo "File does not exist: " . $absolutePath . "\n";
            return response()->json(['message' => 'File not found.'], 404);
        }

        // Delete the image record from the database
      

        return response()->json(['message' => 'Image deleted successfully.']);
    }
    
}
