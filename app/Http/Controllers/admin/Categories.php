<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

    


class Categories extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $categories = Category::
        when($search, function ($query, $search) {
            return $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(10);
        
        return view('Administrator.categories.list', compact('categories', 'search'))
        ->with('search', $search); // Passing the search term to preserve it in pagination links
    }

    public function create(Request $request){
        return view('Administrator.categories.add');
    }


    
    public function store(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|string|max:255', // Validate image as a string path
            'status' => 'required|boolean',
            'showHome' => 'required|in:Yes,No',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        $imagePath = null;
    
        if ($request->filled('image')) {
            $tempImagePath = $request->image;
    
            // Check if the file exists in the temporary storage
            if (Storage::disk('public')->exists($tempImagePath)) {
                $image = Storage::disk('public')->get($tempImagePath); // Get the image content

                
                
                // Create a new styled image
                $styledImage = Image::read($image)
                    ->resize(300, 300); // Resize to 300x300
                
                    $title = uniqid();
                
                $newImageName = 'categories/' . $title . '.jpg'; // Unique name for the styled image
                $styledImage->save(public_path("storage/".$newImageName));
              
                
                // Delete the temporary image
                Storage::disk('public')->delete($tempImagePath);
                
                // Save the new image path
                $imagePath = $newImageName;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found in the specified path.'
                ]);
            }
        }
    
        // Create the new category
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath,
            'status' => $request->status,
            'showHome' => $request->showHome,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ]);
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('Administrator.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'image' => 'nullable|string|max:255', // Validate image as a string path
            'status' => 'required|boolean',
            'showHome' => 'required|in:Yes,No',
        ]);
        
        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
        // Find the existing category by ID
        $category = Category::findOrFail($id);
        $imagePath = $category->image; // Keep the existing image path if no new image is uploaded
        
        if ($request->filled('image')) {
            $tempImagePath = $request->image;
            
            // Check if the file exists in the temporary storage
            if (Storage::disk('public')->exists($tempImagePath)) {
                $image = Storage::disk('public')->get($tempImagePath); // Get the image content
                
                // Create a new styled image
                $styledImage = Image::read($image)
                ->resize(300, 300); // Resize to 300x300
                
                $title = uniqid();
                
                $newImageName = 'categories/' . $title . '.jpg'; // Unique name for the styled image
                $styledImage->save(public_path("storage/" . $newImageName));
                
                // Delete the temporary image
                Storage::disk('public')->delete($tempImagePath);
                
                // Set the new image path
                $imagePath = $newImageName;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found in the specified path.'
                ]);
            }
        }
        
        // Update the category with new values
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath,
            'status' => $request->status,
            'showHome' => $request->showHome,
        ]);
        
        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully!',
            'category' => $category
        ]);
    }
    
    public function destroy($id)
{
    // Find the category by ID
    $category = Category::findOrFail($id);

    // Check if the category has an image and delete it from the storage
    if ($category->image && Storage::disk('public')->exists($category->image)) {
        // Delete the image from the public storage
        Storage::disk('public')->delete($category->image);
    }

    // Delete the category from the database
    $category->delete();

    // Return success response
    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully!'
    ]);
}


                
}
