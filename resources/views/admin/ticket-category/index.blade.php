{{-- filepath: resources/views/admin/ticket-category/index.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Ticket Categories</h4>
                        <a href="{{ route('admin.ticket-category.create') }}" class="btn btn-primary">Add Category</a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Website</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Tickets Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} fa-2x"></i>
                                            @else
                                                <i class="fas fa-ticket-alt fa-2x text-muted"></i>
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->website->name }}</td>
                                        <td>{{ Str::limit($category->description, 50) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $category->sort_order }}</td>
                                        <td>{{ $category->tickets_count ?? $category->tickets()->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket-category.edit', $category->id) }}" 
                                               class="btn btn-sm btn-outline-primary">Edit</a>
                                            
                                            @if($category->tickets()->count() == 0)
                                                <a href="{{ route('admin.ticket-category.delete', $category->id) }}" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete category with tickets">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No ticket categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection