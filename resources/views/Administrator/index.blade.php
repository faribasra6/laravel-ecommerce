@extends('Administrator.Layout.app')
@section('links')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
 
</style>    
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>

    @if($hasLowStock)
    <div class="warning-row">
        <div class="warning-message">
            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i> <!-- Warning icon -->
            <span class="warning-text">Warning: products are low in stock!</span>
        </div>
        <div class="low-stock-products">
            <ul>
                @foreach($lowStockProducts as $product)
                    <li>
                        <span class="product-name">{{ $product->title }}</span>
                        <span class="product-quantity text-danger">(Quantity: {{ $product->qty }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
   
   
    <div class="revenue-shipping-row">
        <!-- Total Revenue -->
        <div class="revenue-box">
            <div class="label">
                <span>Total Revenue</span>
            </div>
            <div class="value text-success">
                AED {{ $totalRevenue }}
                <i class="fas fa-coins fa-2x text-success"></i> <!-- Stack of coins icon -->
            </div>
        </div>
    
        <!-- Shipping Charges -->
        <div class="shipping-box">
            <div class="label">
                <span>Shipping Charges</span>
            </div>
            <div class="value text-danger">
                AED {{ $shippingCharges }}
                <i class="fas fa-shipping-fast fa-2x text-danger"></i> <!-- Truck icon -->
            </div>
        </div>
    
        <!-- Net Revenue -->
        <div class="net-revenue-box">
            <div class="label">
                <span>Net Revenue</span>
            </div>
            <div class="value text-primary">
                AED {{ $totalRevenue - $shippingCharges }} <!-- Subtract shipping from revenue -->
                <i class="fas fa-chart-line fa-2x text-primary"></i> <!-- Line chart icon -->
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-info p-3 rounded-circle shadow-sm">
                            <i class="fas fa-dollar-sign text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">This Month Revenue</div>
                            <h4 class="fw-bold text-dark mb-0">AED {{ number_format($currentMonthRevenue, 2) }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-purple p-3 rounded-circle shadow-sm">
                            <i class="fas fa-dollar-sign text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">Last 30 Days</div>
                            <h4 class="fw-bold text-dark mb-0">AED {{ number_format($last30DaysRevenue, 2) }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
        
        <div class=" col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-danger p-3 rounded-circle shadow-sm">
                            <i class="fas fa-dollar-sign text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">Last Month Revenue</div>
                            <h4 class="fw-bold text-dark mb-0">AED {{ number_format($lastMonthRevenue, 2) }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
    </div>

   
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-success p-3 rounded-circle shadow-sm">
                            <i class="fas fa-shopping-bag text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">Total Orders</div>
                            <h4 class="fw-bold text-dark mb-0">{{ $orders }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
        
        <div class=" col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-primary p-3 rounded-circle shadow-sm">
                            <i class="fas fa-tag text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">Total Products</div>
                            <h4 class="fw-bold text-dark mb-0">{{ $products }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Icon and Label -->
                        <div class="bg-warning p-3 rounded-circle shadow-sm">
                            <i class="fas fa-user text-white fa-lg"></i> <!-- Larger Icon -->
                        </div>
                        <div>
                            <div class="text-muted mb-2">Total Customers</div>
                            <h4 class="fw-bold text-dark mb-0">{{ $customers }}</h4>
                        </div>
                    </div>
                    <!-- Optional extra info (like a small text or percentage) -->
                    
                </div>
            </div>
        </div>
    </div>

 

    <div class="p-5 row">

        <div class="chart-container">
            <!-- Chart Canvas -->
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    
    
</div>
@endsection
@section('customScript')
<script>
    $(document).ready(function () {
        // Get the data passed from the controller
        const barChartLabels = @json($barChartLabels);
        const barChartData = @json($barChartData);

      

        // Get the chart canvas context
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Initialize the Bar Chart
        const myChart = new Chart(ctx, {
            type: 'bar', // Chart type
            data: {
                labels: barChartLabels, // X-axis labels
                datasets: [{
                    label: 'Product Sales', // Dataset label
                    data: barChartData, // Y-axis data
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                    borderColor: 'rgba(75, 192, 192, 1)', // Border color
                    borderWidth: 1 // Border width
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
                scales: {
                    y: {
                        beginAtZero: true // Start Y-axis from zero
                    }
                }
            }
        });

        
    });
</script>

@endsection
