@extends('admin.main')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8 offset-md-2 mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Teacher</h5>
                    <small>Website: {{ $website->name }} ({{ $website->domain }})</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="website_id" value="{{ $website->id }}">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum file size: 2MB. Accepted formats: JPG, PNG, GIF</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active (participants can select this teacher)
                            </label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.teachers.index', $website->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
