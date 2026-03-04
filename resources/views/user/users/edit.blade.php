@extends('user.main')

@section('page-title', 'Edit User')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.manage-users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        @if($user->photo)
                            <div class="mb-2">
                                <img src="{{ asset($user->photo) }}" width="120" alt="Profile photo" style="border-radius: 8px;">
                            </div>
                        @endif
                        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo-image-file" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                    </div>

                    

                    @if (Auth::user()->role != 'user')
                        <div class="mb-3">
                            <label class="form-label">Roles *</label>
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                        id="role{{ $role->id }}" {{ in_array($role->name, $assignedNames) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role{{ $role->id }}">
                                        {{ $role->label ?? $role->name }}
                                    </label>
                                </div>
                            @endforeach
                            @error('roles')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    @if($user->role === 'individual')
                    <div id="student-fields" class="mb-3">
                        <div class="mb-3">
                            <label class="form-label">Select Teacher <span class="text-danger">*</span></label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" name="teacher_id">
                                <option value="">Choose a teacher</option>
                                @if(isset($teachers))
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ (string) old('teacher_id', $user->teacher_id) === (string) $teacher->id ? 'selected' : '' }}>
                                            {{ trim($teacher->name . ' ' . ($teacher->last_name ?? '')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('teacher_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fundraising Goal</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('goal') is-invalid @enderror" name="goal" min="0" step="0.01" value="{{ old('goal', $user->goal) }}">
                                <span class="input-group-text">.00 USD</span>
                                @error('goal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">T-Shirt Size</label>
                            <select class="form-select @error('tshirt_size') is-invalid @enderror" name="tshirt_size">
                                <option value="">Select a size</option>
                                <option value="Youth XS" {{ old('tshirt_size', $user->tshirt_size) == 'Youth XS' ? 'selected' : '' }}>Youth XS</option>
                                <option value="Youth Small" {{ old('tshirt_size', $user->tshirt_size) == 'Youth Small' ? 'selected' : '' }}>Youth Small</option>
                                <option value="Youth Medium" {{ old('tshirt_size', $user->tshirt_size) == 'Youth Medium' ? 'selected' : '' }}>Youth Medium</option>
                                <option value="Youth Large" {{ old('tshirt_size', $user->tshirt_size) == 'Youth Large' ? 'selected' : '' }}>Youth Large</option>
                                <option value="Adult Small" {{ old('tshirt_size', $user->tshirt_size) == 'Adult Small' ? 'selected' : '' }}>Adult Small</option>
                                <option value="Adult Medium" {{ old('tshirt_size', $user->tshirt_size) == 'Adult Medium' ? 'selected' : '' }}>Adult Medium</option>
                                <option value="Adult Large" {{ old('tshirt_size', $user->tshirt_size) == 'Adult Large' ? 'selected' : '' }}>Adult Large</option>
                                <option value="Adult XL" {{ old('tshirt_size', $user->tshirt_size) == 'Adult XL' ? 'selected' : '' }}>Adult XL</option>
                                <option value="Adult XXL" {{ old('tshirt_size', $user->tshirt_size) == 'Adult XXL' ? 'selected' : '' }}>Adult XXL</option>
                            </select>
                            @error('tshirt_size')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endif


                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('users.manage-users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Profile photo validation (matches parents portal behavior)
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('photo-image-file');
        const profileForm = profilePhotoInput ? profilePhotoInput.closest('form') : null;
        const submitBtn = profileForm ? profileForm.querySelector('button[type="submit"]') : null;
        let removeFileBtn = document.getElementById('removeProfileFileBtn');

        if (!removeFileBtn && profilePhotoInput) {
            removeFileBtn = document.createElement('button');
            removeFileBtn.id = 'removeProfileFileBtn';
            removeFileBtn.type = 'button';
            removeFileBtn.className = 'btn btn-sm btn-danger ms-2';
            removeFileBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove File';
            removeFileBtn.style.display = 'none';
            removeFileBtn.addEventListener('click', function() {
                profilePhotoInput.value = '';
                profilePhotoInput.classList.remove('is-invalid');
                const errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');
                if (errorDiv && !errorDiv.classList.contains('permanent-error')) {
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                }
                removeFileBtn.style.display = 'none';
                if (submitBtn) submitBtn.disabled = false;
            });
            profilePhotoInput.parentNode.appendChild(removeFileBtn);
        }

        if (profilePhotoInput && profileForm) {
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                let errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');

                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.style.display = 'none';
                    profilePhotoInput.parentNode.appendChild(errorDiv);
                }

                if (file) {
                    profilePhotoInput.classList.remove('is-invalid');
                    if (!errorDiv.classList.contains('permanent-error')) {
                        errorDiv.style.display = 'none';
                        errorDiv.textContent = '';
                    }

                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        profilePhotoInput.classList.add('is-invalid');
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                        e.target.value = '';
                        if (submitBtn) submitBtn.disabled = true;
                        if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                        return;
                    }

                    const fileName = file.name.toLowerCase();
                    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    const fileExtension = fileName.split('.').pop();

                    if (!allowedExtensions.includes(fileExtension)) {
                        profilePhotoInput.classList.add('is-invalid');
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                        e.target.value = '';
                        if (submitBtn) submitBtn.disabled = true;
                        if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                        return;
                    }

                    const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                    if (!allowedTypes.includes(file.type)) {
                        profilePhotoInput.classList.add('is-invalid');
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                        e.target.value = '';
                        if (submitBtn) submitBtn.disabled = true;
                        if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                        return;
                    }

                    if (submitBtn) submitBtn.disabled = false;
                    if (removeFileBtn) removeFileBtn.style.display = 'none';
                } else {
                    if (submitBtn) submitBtn.disabled = false;
                    if (removeFileBtn) removeFileBtn.style.display = 'none';
                }
            });

            profileForm.addEventListener('submit', function(e) {
                const file = profilePhotoInput.files[0];
                let hasError = false;
                let errorMessage = '';

                if (file) {
                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        hasError = true;
                        errorMessage = 'File size exceeds 5MB. Please choose a smaller image.';
                    }

                    const fileName = file.name.toLowerCase();
                    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    const fileExtension = fileName.split('.').pop();
                    if (!hasError && !allowedExtensions.includes(fileExtension)) {
                        hasError = true;
                        errorMessage = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                    }

                    if (!hasError) {
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            hasError = true;
                            errorMessage = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                        }
                    }
                }

                if (!hasError && profilePhotoInput.classList.contains('is-invalid')) {
                    hasError = true;
                    errorMessage = 'Please fix the file upload error before submitting.';
                }

                if (hasError) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    profilePhotoInput.classList.add('is-invalid');
                    let errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block';
                        profilePhotoInput.parentNode.appendChild(errorDiv);
                    }
                    if (!errorDiv.classList.contains('permanent-error')) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = errorMessage;
                    }
                    profilePhotoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    return false;
                }
            });
        }
    });
</script>

@endsection
