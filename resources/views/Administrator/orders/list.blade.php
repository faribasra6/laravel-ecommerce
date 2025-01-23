@extends('Administrator.layout.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <form action="{{ route('orders') }}" method="GET" class="d-flex" style="width: 250px;">
                        <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}">
                        <button type="submit" class="btn btn-default ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('orders') }}" class="btn btn-danger">Reset</a> <!-- Reset Button -->
                </div>
            </div>

            @if($orders->isNotEmpty())
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Orders #</th>											
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{ route('orders.detail', $order->id)}}">{{ $order->id}}</a></td>
                            <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                            <td>{{ $order->email}}</td>
                            <td>{{$order->mobile}}</td>
                            <td>
                                <span class=" badge
                                @switch($order->status)
                                    @case('pending')
                                        bg-warning
                                        @break
                                    @case('delivered')
                                        bg-success
                                        @break
                                    @case('canceled')
                                        bg-danger
                                        @break
                                    @case('shipped')
                                        bg-info
                                        @break
                                    @default
                                        bg-secondary
                                @endswitch">
                                {{ $order->status }}
                            </span>
                             
                            </td>

                            <td>AED {{ $order->grand_total}}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y')}}</td>																				
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{ $orders->links()}}
            </div>
            @else
                <div class="card-body">
                    <h3 class="text-center">
                        No Orders Found
                    </h3>
                </div>
                @endif
        </div>
    </div>
    <!-- /.card -->
</section>
</div>
    
    
@endsection

@section('customScript')
<script>
    function deleteCategory(catID) {
        if (confirm('Are You Sure to Delete This?')) {
            $.ajax({
                url: "{{ route('categories.delete', '') }}/" + catID, // The route for the delete action
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',  // CSRF token
                },
                success: function(response) {
                    if (response.status === true) {
                        window.location.reload();
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        }
    }
</script>

@endsection