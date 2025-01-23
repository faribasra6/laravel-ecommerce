<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Mail\OrderMails;
use App\Models\shop\Order;
use App\Models\shop\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Start the query for orders and join with users table
        $query = Order::query()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.email as user_email'); // Select the necessary fields from both tables
    
        // If there's a status filter in the request, apply it
        $validStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ];
    
        // If there's a status filter in the request, apply it only if it's valid
        if ($request->has('status') && in_array($request->status, $validStatuses)) {
            $query->where('orders.status', $request->status);
        }
    
        // If there's a search query (e.g., order ID, customer name, or email), apply it
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($subQuery) use ($request) {
                // Search in multiple fields like order ID, first name, last name, or email
                $subQuery->where('orders.id', 'like', '%' . $request->search . '%')
                         ->orWhere('users.name', 'like', '%' . $request->search . '%')
                         ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }
    
        // Order by latest first (desc) by default, and apply pagination (e.g., 10 orders per page)
        $orders = $query->orderBy('orders.created_at', 'desc')->paginate(10);
    
        // Pass the orders to the view
        return view('Administrator.orders.list', compact('orders'));
    }
    

    public function show(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order = Order::with('user', 'country', 'shipping')  
        ->findOrFail($id);
        
        $orderitems = OrderItems::where('order_id', $id)->get();
        // You can pass any additional data to the view as needed
        return view('Administrator.orders.show', compact('order','orderitems'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'shipped_date' => 'nullable|date',
        ]);
        
        // Find the order by ID
        $order = Order::findOrFail($id);
        
        // Update the order status
        $order->status = $request->status;
        
        // If a shipped date is provided and the status is "shipped", update the shipped date
        if ($request->status == 'shipped' && $request->shipped_date) {
            $order->estimated_delivery = $request->shipped_date;
        }
        
        // Save the order changes
        $order->save();
        
        // Return a response to Ajax
        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully!',
            'updated_status' => $order->status,
            'shipped_date' => $order->estimated_delivery,
        ]);
    }
    
    public function sendInvoiceEmail(Request $request, $orderId)
    {
        // Fetch the order with user validation and eager load the items
        $order = Order::where('id', $orderId)
        ->where('user_id', Auth::id())
        ->with('items') // Eager load the items relationship
        ->firstOrFail();
        
        try {
            // Determine the recipient email based on the selected option
            $recipientEmail = $request->email == 1
            ? env('ADMIN_EMAIL') // Replace with the admin's email address
            : $order->email; // Customer's email
            
            // Prepare mail data
            $mailData = [
                'subject' => 'Your Invoice for Order #' . $order->id,
                'order' => $order,
                'user' => $request->email, // Include the user
            ];
            
            // Send the email
            Mail::to($recipientEmail)->send(new OrderMails($mailData));
            
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Email sent successfully',
            ]);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(), // Include the error message for debugging
            ], 500);
        }
    }
    
}
