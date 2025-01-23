@extends('Administrator.layout.app')
@section('content')
	<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="text-center">Coupon / Discount</h1>
                    </div>
                    <div class="col-sm-6 text-end mt-2">
                        <a href="{{ route('discount.create') }}" class="btn btn-primary">Add new</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <section class="content">
            <!-- Default box -->
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {!! Session::get('success') !!}  <!-- Changed from 'message' to 'success' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ Session::get('error') }}  <!-- Changed from 'message' to 'error' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('discount') }}" method="GET" class="d-flex" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-default ml-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                            <a href="{{ route('discount') }}" class="btn btn-danger">Reset</a> <!-- Reset Button -->
                        </div>
                    </div>
            
                    <div class="card-body table-responsive p-0">								
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Starts at</th>
                                    <th>Expires at</th>
                                    <th>Use Count</th>
                                    <th>Max Limit</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($coupons->isNotEmpty())
                                @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td>{{ $coupon->name ?? 'N/A' }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>
                                        @if ($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                        @elseif ($coupon->discount_type === 'fixed')
                                        AED {{ $coupon->discount_value }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>{{ $coupon->start_date ? $coupon->start_date->format('d-m-Y H:i') : 'N/A' }}</td>
                                    <td>{{ $coupon->end_date ? $coupon->end_date->format('d-m-Y H:i') : 'N/A' }}</td>
                                    <td>{{ $coupon->usage_count }}</td>
                                    <td>{{ $coupon->max_user_use }}</td>
                                    <td>
                                        <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $coupon->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('discount.edit', $coupon->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('discount.delete', $coupon->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                             onclick="return confirm('Are You Sure to Delete This?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No coupons found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer clearfix">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $coupons->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
                    
        </section>
    </div>    

        
@endsection

@section('customScript')
<script>
     // Function to handle showing alerts
     function showAlert(type, message) {
        var alertId = type === 'success' ? 'success-alert' : 'error-alert';
        var alertDiv = $('#' + alertId);
        alertDiv.text(message).show(); // Update and show the alert
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alertDiv[0]);
            bsAlert.close(); // Dismiss alert after 3 seconds
        }, 3000);
    }
    
    // Auto dismiss alerts on page load
    var errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(errorAlert);
            bsAlert.close();
        }, 3000);
    }
    
    var successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 3000);
    }

</script>
@endsection