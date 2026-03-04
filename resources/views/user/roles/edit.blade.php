@extends('user.main')

@section('page-title', 'Edit Role')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Role: {{ $role->label ?? $role->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Role Name (slug format) *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name', $role->name) }}" required>
                        <small class="text-muted">Use lowercase with underscores</small>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Label</label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" 
                            value="{{ old('label', $role->label) }}">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        @foreach($permissionGroups as $group => $groupPermissions)
                            <div class="card mb-2">
                                <div class="card-header">
                                    <strong>{{ ucwords(str_replace('_', ' ', $group)) }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                        value="{{ $permission->id }}" id="perm{{ $permission->id }}"
                                                        {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm{{ $permission->id }}">
                                                        {{ $permission->label }}
                                                        <small class="text-muted d-block">{{ $permission->description }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Role</button>
                        <a href="{{ route('users.roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
