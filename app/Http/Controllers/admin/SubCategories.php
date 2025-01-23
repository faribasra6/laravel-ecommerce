<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Category;
use App\Models\admin\SubCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategories extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');

        $subcategories = SubCategory::
        when($search, function ($query, $search) {
            return $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(10);

        return view('Administrator.subcategories.list', compact('subcategories', 'search'))
        ->with('search', $search);
    }

    public function create(Request $request){
        $categories = Category::all();

        return view('Administrator.subcategories.add', compact('categories'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
            'name' => 'required|string|max:255|unique:sub_categories,name', // Ensure the name is unique for subcategories
            'slug' => 'required|string|max:255|unique:sub_categories,slug', // Ensure the slug is unique
            'status' => 'required|boolean',
            'showHome' => 'required|in:Yes,No',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $subcategory = SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
            'showHome' => $request->showHome,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'SubCategory created successfully!',
            'subcategory' => $subcategory
        ]);
    }

    public function edit(Request $request, $id){
        $categories = Category::all();
        $subcategory = SubCategory::findOrFail($id);
        return view('Administrator.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        
        // Find the existing subcategory by its ID
        $subcategory = SubCategory::findOrFail($id);
        
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subcategory->id, // Ensure the name is unique, except for the current subcategory
            'slug' => 'required|string|max:255|unique:sub_categories,slug,' . $subcategory->id, // Ensure the slug is unique, except for the current subcategory
            'status' => 'required|boolean',
            'showHome' => 'required|in:Yes,No',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
        // Update the subcategory with the new data
        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->status = $request->status;
        $subcategory->showHome = $request->showHome;
        $subcategory->save();
        
        // Return a response after successful update
        return response()->json([
            'status' => true,
            'message' => 'SubCategory updated successfully!',
            'subcategory' => $subcategory
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        try{

            $subcategory->delete();
            return response()->json([
                'status' => true,
                'message' => 'SubCategory deleted successfully'
            ]);
        }
        catch(Exception $e) {

            return response()->json([
                'status' => true,
                'message' => $e,
            ]);
        }    
    }
        
}
