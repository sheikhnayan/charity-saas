@extends('admin.main')

@section('content')
<div class="container py-4">
    <h2>Create User</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Roles</label>
            <div>
                @if(isset($roles) && $roles->count())
                    @foreach($roles as $role)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->name }}"
                                {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->label ?? $role->name }}</label>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No roles defined. Create roles first.</p>
                @endif
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Website (optional)</label>
            <select name="website_id" class="form-select">
                <option value="">-- Global --</option>
                @foreach($websites as $w)
                    <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->domain }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Create User</button>
    </form>
</div>
@endsection
