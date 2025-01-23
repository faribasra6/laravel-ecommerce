<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\shop\Country;
use App\Models\shop\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function index()
    {
        // Fetch paginated shipping charges and countries
        $shippingCharges = Shipping::with('country')->paginate(7); // Paginate shipping charges (10 per page)
        $countries = Country::all(); // Paginate countries (10 per page)
    
        return view('Administrator.shipping', [
            'shippingCharges' => $shippingCharges,
            'countries' => $countries,
        ]);
    }
    
    
        public function store(Request $request)
        {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'country' => 'required|exists:countries,id', // Ensure country_id exists in countries table
                'amount' => 'required|numeric', // Ensure amount is numeric
            ]);
    
            // If validation fails, return a JSON error response
           
            if ($validator->fails()) {
                session()->flash('error', 'Invalid input');
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400); // 400 Bad Request for validation errors
            }
    
            // Use updateOrCreate to either update or create the shipping charge
            $shippingCharge = Shipping::updateOrCreate(
                ['country_id' => $request->country], // Check if record exists for the given country_id
                ['amount' => $request->amount] // Update or create with the provided amount
            );
    
            // Flash success message and return success response
            session()->flash('success', 'Shipping charge added/updated successfully!');
            return response()->json([
                'status' => true,
                'message' => 'Shipping charge added/updated successfully!',
            ], 201); // 201 Created status code
        }
    
        public function destroy($country_id)
        {
        
            $shippingCharge = Shipping::where('country_id', $country_id)->first();
    
            
            if (!$shippingCharge) {
                session()->flash('error', 'Shipping charge not found for this country.');
                return response()->json([
                    'status' => false,
                    'message' => 'Shipping charge not found for this country.',
                ], 404); // Return a 404 if not found
            }
    
            $shippingCharge->delete();
    
            // Return success response
            session()->flash('success', 'Shipping charge deleted successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Shipping charge deleted successfully.',
            ], 200); 
        }
}
