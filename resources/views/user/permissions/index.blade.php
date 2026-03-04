@extends('user.main')

@section('page-title', 'Permissions')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Permissions Management</h5>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="mb-3">Available Permissions</h6>
                        
                        @foreach($permissionGroups as $group => $groupPermissions)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ ucwords(str_replace('_', ' ', $group)) }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Permission</th>
                                                    <th>Description</th>
                                                    <th>Assigned To</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupPermissions as $permission)
                                                    <tr>
                                                        <td><code>{{ $permission->name }}</code><br><small>{{ $permission->label }}</small></td>
                                                        <td><small>{{ $permission->description }}</small></td>
                                                        <td>
                                                            @foreach($roles as $role)
                                                                @if($role->permissions->contains($permission->id))
                                                                    <span class="badge bg-primary me-1">{{ $role->label ?? $role->name }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h6 class="mb-0">Assign Permissions to Role</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.permissions.assign') }}">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Select Role *</label>
                                        <select name="role_id" class="form-select" required id="roleSelect">
                                            <option value="">Choose a role...</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->label ?? $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Select Permissions *</label>
                                        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                                            @foreach($permissionGroups as $group => $groupPermissions)
                                                <strong class="d-block mt-2">{{ ucwords(str_replace('_', ' ', $group)) }}</strong>
                                                @foreach($groupPermissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                            value="{{ $permission->id }}" id="assign_perm{{ $permission->id }}">
                                                        <label class="form-check-label small" for="assign_perm{{ $permission->id }}">
                                                            {{ $permission->label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-save me-1"></i> Assign Permissions
                                    </button>
                                </form>

                                <div class="alert alert-info mt-3">
                                    <small>
                                        <i class="bx bx-info-circle me-1"></i>
                                        Select a role and the permissions you want to assign to it. This will replace any existing permissions for that role.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const permissions = @json($roles->mapWithKeys(function($role) {
        return [$role->id => $role->permissions->pluck('id')->toArray()];
    }));

    roleSelect.addEventListener('change', function() {
        const roleId = this.value;
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        if (roleId && permissions[roleId]) {
            permissions[roleId].forEach(permId => {
                const checkbox = document.getElementById('assign_perm' + permId);
                if (checkbox) checkbox.checked = true;
            });
        }
    });
});
</script>
@endsection
