@extends('user.main')

@section('page-title', 'Roles')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Roles Management</h5>
                <a href="{{ route('users.roles.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Create Role
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Label</th>
                            <th>Permissions Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td><code>{{ $role->name }}</code></td>
                                <td>{{ $role->label ?? $role->name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                </td>
                                <td>
                                    <a href="{{ route('users.roles.edit', $role->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('users.roles.destroy', $role->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete role?')">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No roles found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
