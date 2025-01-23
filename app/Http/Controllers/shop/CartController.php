<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Mail\OrderMails;
use App\Models\admin\Product;
use App\Models\shop\CouponDiscount;
use App\Models\shop\CouponUserUsage;
use App\Models\shop\CustomerAddress;
use App\Models\shop\Order;
use App\Models\shop\Shipping;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    //------------------- Creatin Cart Object for session --------------------------
    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);
        
        $product = Product::with('images')->findOrFail($request->id);
        $cartContent = Cart::content();
        $existingItem = $cartContent->firstWhere('id', $product->id);
        $status = false;
        $message = '';
        
        if ($existingItem) {
            $message = $product->title . ' is already added to the cart.';
        } else {
            if ($product->track_qty == 1 && $product->qty < 1) {
                $status = false;
                $message = 'Product is out of stock!';
                session()->flash('error', $message); 
                
            } else {
                Cart::add([
                    'id' => $product->id,
                    'name' => $product->title,
                    'qty' => 1,
                    'price' => $product->price,
                    'weight' => 550,
                    'options' => [
                        'image' => $product->images->isNotEmpty() 
                        ? asset('storage/'.$product->images->first()->path) 
                        : asset('storage/image.png'),
                    ],          

                ]);
                Cart::setGlobalDiscount(0);
                $status = true;
                $message = '<strong>' . $product->title . '</strong> added to cart.';
                session()->flash('success', $message); 
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    
    // ---------------- Return Cart Data for Cart Page----------------//
    public function cart()
    {
        $cartItems = Cart::content();
        $count = Cart::count();
        $subtotal = Cart::subtotal();
        return view('shop.cart', compact('cartItems', 'count', 'subtotal')); // Pass cart items to the view
    }

    // ----------------------- Update Cart - + -------------------------//
    public function update(Request $request)
    {
        $itemInfo = Cart::get($request->rowID);
        if (!$itemInfo) {
            $message = 'Item not found in cart.';
            session()->flash('error', $message); // Flash error message
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }
        $product = Product::findOrFail($itemInfo->id);
        if (!$product) {
            $message = 'Product is not available now.';
            session()->flash('error', $message); // Flash error message
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }
        if ($product->track_qty == 1 && $request->qty > $product->qty) {
            $message = 'Quantity not available in stock!';
            session()->flash('error', $message); // Flash error message
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }
        Cart::update($request->rowID, $request->qty);
        $message = 'Cart updated successfully!';
        session()->flash('success', $message); // Flash success message
        
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    //==================== Delete the Cart------------------//
    public function destroy(Request $request)
    {
        $item = Cart::get($request->rowID);
        if($item == NULL){
            $message = 'Item not found in Cart!';
            session()->flash('error', $message); // Flash success message       
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);

        }else{
            Cart::remove($request->rowID);
            $message = 'Product deleted successfully!';
            session()->flash('success', $message); // Flash success message
            
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
    }

    // ============== Checkou Page==========================//
    public function checkout(Request $request) {
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        if (Cart::count() == 0) {
            return redirect()->route('cart');
        }
        $cartItems = Cart::content();
        $count = Cart::count();
        $subtotal = Cart::subtotal();
        $discount = cART::discount();    
        $countries = Shipping::with('country')  // Load the related country model
        ->join('countries', 'shipping_charges.country_id', '=', 'countries.id')  // Join the countries table
        ->orderBy('countries.name', 'ASC')->get();
        return view('shop.checkout', compact(
            'countries',  'customerAddress',
            'cartItems', 'count',
            'subtotal','discount'
        ));
    }
    
    //=========================== Process Checkou Save the Order=================//
    public function processCheckout(Request $request)
    {
        // 1: Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:15',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);
        
        $validator->sometimes('coupon_code', 'string|exists:coupon_discount,code', function ($input) {
            return !empty($input->coupon_code);
        });
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fill all the required fields',
                'errors' => $validator->errors(),
            ], 400);
        }
        $couponCode = $request->coupon_code;
        if ($validator->passes() && $couponCode !== null) {
            $code = CouponDiscount::where('code', $couponCode)->first();
            $code->increment('usage_count');
    
            // Mark coupon as used by this user
            CouponUserUsage::create([
                'coupon_discount_id' => $code->id,
                'user_id' => $request->user()->id,
            ]);
        }
        
       
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'first_name', 'last_name', 'email', 'mobile', 'address', 'apartment', 'city', 'state', 'zip'
            ]) + [
                    'user_id' => $user->id,
                    'country_id' => $request->country,
        ]);
        if ($request->payment_method === 'cod') {
            $subtotal = Cart::subtotal(2, '.', '');
            $shipping = $request->shipping_amount;
            $discount = $request->discount_amount;  
            $coupon_code = $request->coupon_code ?? null;
            
            if ($request->discount_code) {
                $coupon_code = $request->discount_code;
                $coupon = CouponDiscount::where('code', $coupon_code)->first();
                if ($coupon) {
                    $discount = $coupon->discount_amount;
                }
            }
            
            $grandtotal = ($subtotal + $shipping) - $discount;
            $order = new Order();
            $order->user_id = $user->id;
            $order->subtotal = $subtotal;
            
            $order->shipping = $shipping;
            $order->coupon_code = $coupon_code;
            $order->discount = $discount;
            $order->grand_total = $grandtotal;
            $order->status = 'Pending';
            
            $order->fill($request->only([
                'first_name', 'last_name', 'email', 'mobile', 'address', 'apartment',
                'state', 'city', 'zip'
                ]) + [
                    'country_id' => $request->country,
                    'notes' => $request->order_notes,
            ]);
           
            $order->save();

            $orderItems = Cart::content()->map(function ($item) {
                return [
                    'product_id' => $item->id,
                    'name' => $item->name,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->qty * $item->price,
                ];
            });
            
            foreach ($orderItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->track_qty) {
                    if ($product->qty >= $item['qty']) {
                        $product->decrement('qty', $item['qty']);
                    } else {
                        throw new \Exception('Insufficient stock for product: ' . $product->name);
                    }
                }
            }
            
            $order->orderItems()->createMany($orderItems);
            Cart::destroy();

            return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Order placed successfully',
            ], 200);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Unsupported payment method',
        ], 400);
    }

    //===================== Thank You Page=====================//
    public function thankyou($id)
    {
        return view('shop.thanks', ['orderId' => $id]);
    }   
    
    //==================== Apply Coupon Code==============================//
    public function applyDiscount(Request $request)
    {
        // Validate the coupon code in the request
        $validated = $request->validate([
            'code' => 'required|string|exists:coupon_discount,code',
        ]);
    
        // Fetch the coupon code from the database
        $code = CouponDiscount::where('code', $validated['code'])->first();

        // Get current time
        $now = Carbon::now();
        $start_date = $code->start_date ? Carbon::createFromFormat('Y-m-d H:i:s', $code->start_date) : null;
        $end_date = $code->end_date ? Carbon::createFromFormat('Y-m-d H:i:s', $code->end_date) : null;
    
        // Check if the coupon is active (start date in the future)
        if ($start_date && $now->lt($start_date)) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon is not yet valid.',
            ], 400);
        }
    
        // Check if the coupon has expired (end date in the past)
        if ($end_date && $now->gt($end_date)) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon has expired.',
            ], 400);
        }
    
        // Check if the coupon is active
        if ($code->status === false) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon is not active.',
            ], 400);
        }
    
        // Check if the coupon has exceeded the global usage limit
        if ($code->usage_count >= $code->max_use) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon usage limit has been reached.',
            ], 400);
        }
    
        // Check if the coupon has been used by the current user more than allowed
        $userUsageCount = CouponUserUsage::where('coupon_discount_id', $code->id)
                                         ->where('user_id', $request->user()->id)
                                         ->count();
    
        if ($userUsageCount >= $code->max_user_use) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon usage limit per user has been reached.',
            ], 400);
        }
    
        // Check for minimum purchase requirement
        $subtotal = Cart::subtotal(2, '.', ''); // Cart subtotal in required format
        if ($code->minimum_order_amount && $subtotal < $code->minimum_order_amount) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon is not valid for orders below the minimum purchase amount.',
            ], 400);
        }
    
        // Apply the discount based on the coupon type
        $discountAmount = 0;
        if ($code->discount_type == 'percentage') {
            $discountAmount = ($code->discount_value / 100) * $subtotal; // Percentage discount
        } else {
            $discountAmount = $code->discount_value; // Fixed amount discount
        }
    
       
        // Mark coupon as used globally
       
        session(['coupon_used' => true]);
    
        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discountAmount,
            'subtotal' => Cart::subtotal(),
            'total' => Cart::subtotal() + $request->shipping - $discountAmount,
        ], 200);
    }
                
}
