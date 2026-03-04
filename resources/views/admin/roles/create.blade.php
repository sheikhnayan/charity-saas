@extends('admin.main')

@section('content')
    <div class="container">
        <h1>Create Role</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Role Name</label>
                <input id="name" name="name" class="form-control" required maxlength="100" />
            </div>
            <button class="btn btn-primary mt-2">Create</button>
        </form>
    </div>
@endsection
