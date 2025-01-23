<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\TempImage;
use App\Models\shop\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Controller
{
    public function index() {
        $orders = Order::where('status', '!=', 'cancelled')->count();
        $products = Product::count();
        $lowStockProducts = Product::where('qty', '<', 10)->get(); // Fetch products with quantity < 10
        $hasLowStock = $lowStockProducts->isNotEmpty(); // Check if there are any low-stock products
        $customers = User::where('usertype', 0)->count();
        
        // Current month revenue
        $currentMonthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');
    
        // Last month revenue
        $lastMonthRevenue = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');
    
        // Last 30 days revenue
        $last30DaysRevenue = Order::where('created_at', '>=', now()->subDays(30))
        ->where('status', '!=', 'cancelled')->sum('grand_total');
    
        $oldImages = TempImage::where('created_at', '<', Carbon::now()->startOfDay())->get();
        foreach ($oldImages as $image) {
            $absolutePath = public_path('storage/' . $image->file_path);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
            $image->delete();
        }
        
        
        $nonCancelledOrders = Order::where('status', '!=', Order::STATUS_CANCELLED)
        ->with('items.product') // Eager load items and their associated products
        ->get();
        
        $totalRevenue = $nonCancelledOrders->sum('grand_total');
        $shippingCharges = $nonCancelledOrders->sum('shipping');
        
        // Initialize arrays for chart data
        $productSalesData = [];
        $barChartLabels = [];
        $barChartData = [];
       
        
        // Loop through non-cancelled orders and aggregate product sales
        foreach ($nonCancelledOrders as $order) {
            foreach ($order->items as $item) {
                $productId = $item->product_id;
                $productName = $item->product->title; // Assuming the product has a 'name' field
                $quantity = $item->qty;
                
                // Aggregate total quantity sold for each product
                if (!isset($productSalesData[$productId])) {
                    $productSalesData[$productId] = [
                        'name' => $productName,
                        'sales' => 0,
                    ];
                }
                $productSalesData[$productId]['sales'] += $quantity;
            }
        }
        
        // Prepare data for bar chart and line chart
        foreach ($productSalesData as $productId => $data) {
            $barChartLabels[] = $data['name']; // Product names for bar chart labels
            $barChartData[] = $data['sales']; // Total quantities for bar chart data
        }
        
       
        
        // Return the view with all data
        
        return view('Administrator.index', compact(
            'orders', 
            'products', 
            'customers', 
            'currentMonthRevenue', 
            'lastMonthRevenue', 
            'last30DaysRevenue',
            'totalRevenue',
            'barChartLabels',
            'barChartData',
            'shippingCharges',
            'lowStockProducts',
            'hasLowStock'
          
        ));
    }
}