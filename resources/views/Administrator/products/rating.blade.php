@extends('Administrator.layout.app')

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Ratings</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <!-- Card Header with Search and Reset -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Search Form -->
                            <form action="{{ route('products.rating') }}" method="GET" class="d-flex" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-default ml-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                            <!-- Reset Button -->
                            <a href="{{ route('products.rating') }}" class="btn btn-danger">Reset</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    @if($ratings->isNotEmpty())
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th width="60">ID</th>
                                        <th>Product</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Rated By</th>
                                        <th width="100">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ratings as $rating)
                                        <tr id="product-row-{{ $rating->id }}">
                                            <td>{{ $rating->id }}</td>
                                            <td>{{ $rating->product->title }}</td>
                                            <td>{{ $rating->rating }}</td>
                                            <td>{{ $rating->review }}</td>
                                            <td>{{ $rating->username }}</td>
                                            <td>
                                                <!-- Status Dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm {{ $rating->status == 'Approved' ? 'btn-success' : ($rating->status == 'Rejected' ? 'btn-danger' : 'btn-warning') }} dropdown-toggle"
                                                            type="button"
                                                            id="statusDropdown{{ $rating->id }}"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        {{ ucfirst($rating->status) }}
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $rating->id }}">
                                                        <li>
                                                            <a class="dropdown-item change-status" href="#" data-id="{{ $rating->id }}" data-status="pending">Pending</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item change-status" href="#" data-id="{{ $rating->id }}" data-status="approved">Approved</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item change-status" href="#" data-id="{{ $rating->id }}" data-status="rejected">Rejected</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer clearfix">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    {{ $ratings->links() }}
                                </ul>
                            </nav>
                        </div>
                    @else
                        <!-- No Ratings Found -->
                        <div class="card-body">
                            <h3 class="text-center">No Ratings Listed</h3>
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
        $(document).ready(function () {
            // Handle status change
            $('.change-status').on('click', function (e) {
                e.preventDefault();

                const ratingId = $(this).data('id');
                const newStatus = $(this).data('status');

                // Confirm status change
                if (!confirm(`Are you sure you want to change the status to ${newStatus}?`)) {
                    return;
                }

                // Send AJAX request
                $.ajax({
                    url: `/ratings/${ratingId}/update-status`,
                    method: 'PUT',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}' // CSRF token for security
                    },
                    success: function (response) {
                        // Update the button dynamically
                        const button = $(`#statusDropdown${ratingId}`);
                        button.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1)); // Update text
                        button.removeClass('btn-warning btn-success btn-danger'); // Remove existing classes

                        // Add the appropriate class based on the new status
                        if (newStatus === 'approved') {
                            button.addClass('btn-success');
                        } else if (newStatus === 'rejected') {
                            button.addClass('btn-danger');
                        } else {
                            button.addClass('btn-warning');
                        }

                        // Optional: Show a success message
                        alert('Status updated successfully!');
                    },
                    error: function (xhr) {
                        alert('Failed to update status. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection