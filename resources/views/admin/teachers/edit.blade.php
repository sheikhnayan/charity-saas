@extends('admin.main')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8 offset-md-2 mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Teacher</h5>
                    <small>Website: {{ $website->name }} ({{ $website->domain }})</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            @if($teacher->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    <p class="text-muted small mb-0">Current photo</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to keep current photo. Maximum file size: 2MB.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $teacher->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (participants can select this teacher)
                            </label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.teachers.index', $website->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Update Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
