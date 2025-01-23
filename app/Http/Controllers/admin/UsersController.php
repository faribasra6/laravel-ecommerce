<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        $users = $users->paginate(10);
        return view('Administrator.users.list', compact('users'));

    }

    public function create(Request $request){
       
        return view('Administrator.users.create');

    }

    public function store(Request $request)
    {
        // Normalize the phone number before validating it
        $normalizedPhone = $this->normalizePhoneNumber($request->phone);
        
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $validator = Validator::make(
            ['phone' => $normalizedPhone],
            ['phone' => 'required|string|max:15|unique:users,phone',  ]);
            
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        else{
            try {
                
                // Create a new user instance and store the data
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $normalizedPhone;  // Store the normalized phone number
                $user->status = $request->status;
                $user->password = Hash::make($validated['password']);
                $user->save();
                
                // Return a success response in JSON format
                return response()->json([
                    'status' => true,
                    'message' => 'User created successfully!',
                    'data' => $user
                ], 201); // HTTP Status Code 201 for created
            } catch (\Exception $e) {
                // Return a failure response in JSON format
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, please try again.',
                    'error' => $e->getMessage()
                ], 500); // HTTP Status Code 500 for internal server error
            }
        }
    }

    
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

    public function edit(Request $request, $userid)
    {
        // Fetch the user data
        $userData = User::findOrFail($userid);
        
        // Pass the user data to the view using compact
        return view('Administrator.users.edit', compact('userData'));
    }
    
    public function update(Request $request, $userId)
    {
        // Normalize the phone number before validating it
        $normalizedPhone = $this->normalizePhoneNumber($request->phone);
        
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|string|min:8|confirmed', // Password is optional for update
        ]);
        
        // Validate the phone number uniqueness for update
        $validator = Validator::make(
            ['phone' => $normalizedPhone],
            ['phone' => 'nullable|string|max:15|unique:users,phone,' . $userId]
        );
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        
        try {
            // Find the user to be updated
            $user = User::findOrFail($userId);
            
            // Update the user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $normalizedPhone; // Store the normalized phone number
            $user->status = $request->status;
            
            // If password is provided, hash it and update it
            if ($request->has('password') && $validated['password']) {
                $user->password = Hash::make($validated['password']);
            }
            
            // Save the changes to the user
            $user->save();
            
            // Return a success response in JSON format
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully!',
                'data' => $user
            ], 200); // HTTP Status Code 200 for OK
        } catch (\Exception $e) {
            // Return a failure response in JSON format
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again.',
                'error' => $e->getMessage()
            ], 500); // HTTP Status Code 500 for internal server error
        }
    }
    
    public function destroy(Request $request, $uid)
    {
        try {
            // Find the user by UID
            $user = User::findOrFail($uid);
            
            // Delete the user
            $user->delete();
            
            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully!'
            ], 200); // HTTP Status Code 200 for OK
        } catch (\Exception $e) {
            // Return a failure response if the user is not found or something goes wrong
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again.',
                'error' => $e->getMessage()
            ], 500); // HTTP Status Code 500 for internal server error
        }
    }
    

}
