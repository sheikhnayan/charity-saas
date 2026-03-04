@extends('layouts.admin')

@section('admin-content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Manage Individuals</h5>
                    <a href="{{ route('users.children.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New Individual
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($children->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Teacher</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($children as $child)
                                    <tr>
                                        <td>{{ $child->name }} {{ $child->last_name }}</td>
                                        <td>{{ $child->email }}</td>
                                        <td>
                                            @if($child->teacher)
                                                <span class="badge bg-primary">{{ $child->teacher->name }}</span>
                                            @else
                                                <span class="text-muted">No teacher assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $child->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.children.edit', $child->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('users.children.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this individual?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>You haven't added any individuals yet. Click "Add New Individual" to get started.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
