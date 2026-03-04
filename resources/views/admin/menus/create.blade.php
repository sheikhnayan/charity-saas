@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-plus icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">Create New Menu</span>
                                    <div class="page-title-subheading">Add a new menu to your website</div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Menus
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            <i class="header-icon fas fa-edit me-2"></i>Menu Details
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.menus.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., Main Menu, Footer Menu" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">This is for your reference only.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Menu Location <span class="text-danger">*</span></label>
                                    <select class="form-select @error('location') is-invalid @enderror" 
                                            id="location" name="location" required>
                                        <option value="primary" {{ old('location') == 'primary' ? 'selected' : '' }}>Primary Navigation</option>
                                        <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>Footer Menu</option>
                                        <option value="mobile" {{ old('location') == 'mobile' ? 'selected' : '' }}>Mobile Menu</option>
                                    </select>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Where this menu will appear on your website.</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" 
                                               name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Only active menus will be displayed on your website.</small>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Menu
                                    </button>
                                    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Next Steps:</strong> After creating the menu, you'll be able to add menu items, organize them with drag-and-drop, and create dropdown menus.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
