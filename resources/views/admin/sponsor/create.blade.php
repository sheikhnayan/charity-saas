{{-- filepath: resources/views/admin/ticket/create.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <h4>Add Sponsor</h4>
                <form action="{{ route('admin.sponsor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <select name="website_id" id="website" class="form-select">
                            @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Sponsor Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price">
                    </div>
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">hide Until</label>
                        <input type="date" name="hide_until" class="form-control" id="valid_from">
                    </div>
                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Hide After</label>
                        <input type="date" name="hide_after" class="form-control" id="valid_to">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" id="link">
                    </div>
                    {{-- Replace the is_active checkbox with this select in your create/edit forms --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.sponsor.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
