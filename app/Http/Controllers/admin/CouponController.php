<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\shop\CouponDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->get('search');
        
        // Fetch coupons based on the search query
        $coupons = CouponDiscount::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            });
        })
        ->paginate(10); // Paginate the results
        
        // Pass coupons and search query to the view
        return view('Administrator.discount.index', compact('coupons', 'search'));
    }
    
    public function create()
    {
        return view('Administrator.discount.create');
    }
    
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupon_discount,code',
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'max_use' => 'required|integer|min:1',
            'max_user_use' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            session()->flash('error', 'Invalid input');
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        // Create a new coupon
        CouponDiscount::create($request->all());
        
        session()->flash('success', 'Coupon created successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Coupon created successfully!',
        ], 201); 
        
    }
    
    
    public function edit($id)
    {
        $coupon = CouponDiscount::findOrFail($id);
        
        return view('Administrator.discount.edit', compact('coupon'));
    }
    
    public function update(Request $request, $id)
    {
        $coupon = CouponDiscount::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupon_discount,code,' . $id,
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'max_use' => 'required|integer|min:1',
            'max_user_use' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Invalid input');
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        // Update the coupon
        $coupon->update($request->all());
        
        session()->flash('success', 'Coupon updated successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Coupon updated successfully!',
        ], 201); 
    }
    
    
    public function destroy($id)
    {
        $coupon = CouponDiscount::findOrFail($id); // Find the coupon or throw a 404 error
        $coupon->delete(); // Delete the coupon
        
        // Flash a success message
        session()->flash('success', 'Coupon deleted successfully!');
        
        // Redirect to the coupon listing page
        return redirect()->route('discount');
    }
    
}
