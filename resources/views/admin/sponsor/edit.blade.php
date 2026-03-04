{{-- filepath: resources/views/admin/ticket/edit.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <h4>Edit Sponsor</h4>
                <form action="{{ route('admin.sponsor.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- ...inside your <form> --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Sponsor Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $data->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ old('price', $data->price ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">hide Until</label>
                        <input type="date" name="hide_until" class="form-control" id="valid_from" value="{{ old('valid_from', $data->hide_until ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Hide After</label>
                        <input type="date" name="hide_after" class="form-control" id="valid_to" value="{{ old('valid_to', $data->hide_after ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" id="link" value="{{ old('link', $data->link ?? '') }}">
                    </div>
                    {{-- Replace the is_active checkbox with this select in your create/edit forms --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ (old('status', $data->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (old('status', $data->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.sponsor.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
