<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\shop\Country;
use App\Models\shop\CustomerAddress;
use App\Models\shop\Order;
use App\Models\shop\OrderItems;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountManager extends Controller
{
    public function index(Request $request){

        $user = User::findOrFail(Auth::user()->id);
        $address = CustomerAddress::where('user_id', $user->id)->first();
        $countries = Country::all(); // Paginate countries (10 per page)
        return view('shop.profile.index', compact('user', 'countries', 'address'));
    }

    public function orders(Request $requets){
        $user = Auth::user();   
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();


        return view('shop.profile.order', compact('orders'));
    }

    public function orderDetail(Request $request, $id){
        try {
            $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

            $orderitems = OrderItems::where('order_id', $id)->get();
            
            return view('shop.profile.order-detail', compact('order', 'orderitems'));
        } catch (ModelNotFoundException $e) {
            abort(403, 'You do not have access to this order.'); // Custom error message
        }
        
    }

    public function updateUser(Request $request)
    {
        // Find the user by ID
        $user = User::findOrFail(Auth::user()->id);
        
        // Validate the input data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'regex:/^(05\d{8}|\+9715\d{8})$/', Rule::unique('users')->ignore($user->id)],
        ]);
        
        // Normalize the phone number before updating the user
        $validated['phone'] = $this->normalizePhoneNumber($validated['phone']);
        
        // Update the user with the validated data
        $user->update($validated);
        
        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
    
    /**
     * Normalize the phone number to standard format (+9715XXXXXXXX)
     */
    protected function normalizePhoneNumber($phone)
    {
        // Remove all non-numeric characters except the '+' sign
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // If the phone number starts with '05', replace it with '+9715'
        if (strpos($phone, '05') === 0) {
            $phone = '+971' . substr($phone, 1);
        }
        
        // If the phone number does not start with '+9715', add it
        if (strpos($phone, '+9715') !== 0) {
            $phone = '+9715' . ltrim($phone, '+971');
        }
        
        return $phone;
    }   

    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    
        $user = User::findOrFail(Auth::user()->id);
        
    
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }
    
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }
    
        if (Hash::check($validated['new_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'New password cannot be the same as the current password.',
            ], 400);
        }
    
        try {
            $user->password = Hash::make($validated['new_password']);
            
            $user->save();
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating password: ' . $e->getMessage(),
            ], 500);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ]);
    }    
    
    
    public function password(){
        return view('shop.profile.new-password');
    }

    public function updateOrCreate(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',  // Validate that user_id exists in the users table
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:customer_address,email,' . $request->user_id,
            'mobile'     => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'address'    => 'required|string|max:255',
            'apartment'  => 'nullable|string|max:255',
            'city'       => 'required|string|max:255',
            'state'      => 'nullable|string|max:255',
            'zip'        => 'nullable|string|max:255',
        ]);
        $validated['mobile'] = $this->normalizePhoneNumber($validated['mobile']);
    
        // Use updateOrCreate
        $customer = CustomerAddress::updateOrCreate(
            [
                'user_id' => $validated['user_id'],  // Ensure the user_id is also part of the condition
            ],
            [
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'mobile'     => $validated['mobile'],
                'country_id' => $validated['country_id'],
                'address'    => $validated['address'],
                'apartment'  => $validated['apartment'],
                'city'       => $validated['city'],
                'state'      => $validated['state'],
                'zip'        => $validated['zip'],
            ]
        );
    
        // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Customer address has been updated or created successfully!',
                'data' => $customer
            ], 200);
        }
}
