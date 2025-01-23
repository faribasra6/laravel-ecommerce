@extends('Administrator.layout.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sub Categories</h1>
                </div>
                <div class="col-sm-6 text-end mt-2">
                    <a class="btn btn-primary" href="{{ route('subcategories.create') }}">Add SubCategory</a>
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
                        <form action="{{ route('subcategories') }}" method="GET" class="d-flex" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-default ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('subcategories') }}" class="btn btn-danger">Reset</a> <!-- Reset Button -->
                    </div>
                </div>
                
                
                @if($subcategories->isNotEmpty())
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th width="100">Status</th>
                                <th width="100">SHow on Home</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subcategories as $subcategory)
                            <tr id="category-row-{{$subcategory->id}}">
                                <td>{{ $subcategory->id }}</td>
                                <td>{{ $subcategory->name }}</td>
                                <td>{{ $subcategory->slug }}</td>
                                <td>
                                    <span class="badge {{ $subcategory->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $subcategory->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $subcategory->showHome == 'Yes' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $subcategory->showHome == 'Yes' ? 'Yes' : 'No' }}
                                    </span>
                                   
                                </td>

                                <td>
                                    <a href="{{ route('subcategories.edit', $subcategory->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    <a href="javascript:void(0);" class="text-danger w-4 h-4 mr-1" onclick="deleteSubCategory({{ $subcategory->id}})">
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
                            {{ $subcategories->links() }}
                        </ul>
                    </nav>
                </div>
                @else
                <div class="card-body">
                    <h3 class="text-center">
                        No SubCategories Found
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
    function deleteSubCategory(catID) {
        if (confirm('Are You Sure to Delete This?')) {
            $.ajax({
                url: "{{ route('subcategories.delete', '') }}/" + catID, // The route for the delete action
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