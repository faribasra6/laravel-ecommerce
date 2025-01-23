@extends('Administrator.layout.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pages</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a class="btn btn-primary" href="{{ route('pages.create') }}">New</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
 

    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="{{ route('pages') }}" method="GET" class="d-flex" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-default ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('pages') }}" class="btn btn-danger">Reset</a> <!-- Reset Button -->
                    </div>
                </div>
                
                
                @if($pages->isNotEmpty())
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Title</th>
                                <th>slug</th>
                                <th width="100">Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr id="product-row-{{$page->id}}">
                                <td>{{ $page->id }}</td>
                                
                                <td>{{ $page->title }}</td>
                                <td>{{ $page->slug}}</td>
                                <td>
                                    <span class="badge {{ $page->status == 'active' ? 'bg-success' : 'bg-danger' }}" 
                                        style="cursor: pointer;" 
                                        onclick="changeStatus({{ $page->id }}, '{{ $page->status }}')">
                                      {{ $page->status }}
                                  </span>
                                  
                                </td>
                                <td>
                                    <a href="{{ route('pages.edit', $page->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    <a href="javascript:void(0);" class="text-danger w-4 h-4 mr-1" onclick="deletePage({{ $page->id}})">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>	
                </div>
                
                <div class="card-footer clearfix">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $pages->links() }}
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="card-body">
                    <h3 class="text-center">
                        No Record Found
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
    function deleteProduct(catID) {
    if (confirm('Are You Sure to Delete This?')) {
        $.ajax({
            url: "{{ route('products.delete', '') }}/" + catID, // The route for the delete action
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token
            },
            success: function(response) {
                if (response.success) {  // Check the 'success' property from the response
                    window.location.reload();
                } else {
                    alert(response.message || 'Something went wrong, please try again.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    }
}

function changeStatus(pageId, currentStatus) {
        let newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        $.ajax({
            url: '/pages/change-status', // Update with your actual route
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: pageId,
                status: newStatus
            },
            success: function(response) {
                if (response.status) {
                    // Update the badge UI dynamically
                    const badge = $(`span[onclick="changeStatus(${pageId}, '${currentStatus}')"]`);
                    badge.text(response.new_status);
                    badge.removeClass(currentStatus === 'active' ? 'bg-success' : 'bg-danger')
                         .addClass(response.new_status === 'active' ? 'bg-success' : 'bg-danger');
                    badge.attr('onclick', `changeStatus(${pageId}, '${response.new_status}')`);
                } else {
                    alert(response.message || 'Failed to change status.');
                }
            },
            error: function(xhr) {
                alert('An error occurred while changing status.');
                console.error(xhr.responseText);
            }
        });
    }

    
    // Delete page function
    function deletePage(pageId) {
        // Confirm deletion
        if (confirm('Are you sure you want to delete this page?')) {
            $.ajax({
                url: '/pages/' + pageId, // URL to send the delete request
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for protection
                },
                success: function (response) {
                    if (response.status === 'success') {
                        // Successfully deleted, remove the page from the DOM
                        alert(response.message);
                        location.reload(); // Reload the page to update the list
                    } else {
                        // Deletion failed
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    // Handle AJAX error
                    alert('An error occurred while deleting the page.');
                }
            });
        }
    }

    // Trigger deletePage function when the delete button is clicked
    $('.delete-btn').on('click', function () {
        var pageId = $(this).data('id'); // Get the page ID from the data-id attribute
        deletePage(pageId); // Call the deletePage function
    });

</script>

@endsection