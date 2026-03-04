@extends('admin.main')

@section('content')
    <div class="container">
        <h1>Roles</h1>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <p>
            <a class="btn btn-primary" href="{{ route('admin.roles.create') }}">Create Role</a>
        </p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete role?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
