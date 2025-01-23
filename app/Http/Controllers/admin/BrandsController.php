<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Brands;
use Dotenv\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $brands = Brands::when($search, function ($query, $search) {
            return $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(10);

        return view('Administrator.brands.list', compact('brands', 'search'))
            ->with('search', $search);
    }
    public function create(Request $request)
    {
        return view('Administrator.brands.add');
    }

    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name', // Ensure the name is unique
            'slug' => 'required|string|max:255|unique:brands,slug', // Ensure the slug is unique
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $brand = Brands::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Brand created successfully!',
            'brand' => $brand
        ]);
    }

    public function edit(Request $request, $id)
    {
        $brand = Brands::findOrFail($id);
        return view('Administrator.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brands::findOrFail($id);

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id, // Ensure the name is unique, except for the current brand
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id, // Ensure the slug is unique, except for the current brand
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();

        return response()->json([
            'status' => true,
            'message' => 'Brand updated successfully!',
            'brand' => $brand
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $brand = Brands::findOrFail($id);
        try {
            $brand->delete();
            return response()->json([
                'status' => true,
                'message' => 'Brand deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
