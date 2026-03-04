@extends('user.main')

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Auth::user()->role == 'parents')
        <div class="row mb-4">
            <div class="col-xxl-12">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus me-2"></i>Add Participants
                    </button>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <form action="/admins/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Logo<span class="text-danger">*</span>
                    </label>
                    <br>

                    <img src="{{ asset('uploads/'.$data->logo) ?? null}}" alt="" width="200px">

                    <br>

                    <input type="file" class="form-control" id="last_name" name="logo">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Banner<span class="text-danger">*</span>
                    </label>
                    <br>
                    <img src="{{ asset('uploads/'.$data->banner) ?? null}}" alt="" width="200px">
                    <br>
                    <input type="file" class="form-control" id="last_name" name="banner">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title" value="{{ $data->title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title 2
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title2" value="{{ $data->title2 ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Sub Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="sub_title" value="{{ $data->sub_title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Date
                    </label>

                    <input type="date" class="form-control" id="last_name" name="date" value="{{ $data->date ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Goal
                    </label>

                    <input type="number" class="form-control" id="last_name" name="goal" value="{{ $data->goal ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payout Method
                    </label>

                    <select class="form-select" name="payout_method">
                        <option value="direct_deposits" {{ ($data->payout_method ?? null) == 'direct_deposits' ? 'selected' : '' }}>Direct Deposits</option>
                        <option value="mailed_checks" {{ ($data->payout_method ?? null) == 'mailed_checks' ? 'selected' : '' }}>Mailed Checks</option>
                        <option value="wire_transfers" {{ ($data->payout_method ?? null) == 'wire_transfers' ? 'selected' : '' }}>Wire Transfers</option>
                    </select>
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Location
                    </label>

                    <input type="text" class="form-control" id="last_name" name="location" value="{{ $data->location ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Time
                    </label>

                    <input type="time" class="form-control" id="last_name" name="time" value="{{ $data->time ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Description
                    </label>

                    <textarea name="description" id="description" cols="30" rows="10" class="form-control">
                        {{ $data->description ?? null}}
                    </textarea>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Participant Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="participant_name" value="{{ $data->participant_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Team Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="team_name" value="{{ $data->team_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Organization Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="organization" value="{{ $data->organization ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Phone
                    </label>

                    <input type="text" class="form-control" id="last_name" name="phone" value="{{ $data->phone ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Charitable ID
                    </label>

                    <input type="text" class="form-control" id="last_name" name="charitable_id" value="{{ $data->charitable_id ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Address
                    </label>

                    <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        ZIP / Postal Code
                    </label>

                    <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        City
                    </label>

                    <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Country
                    </label>

                    <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        State / Province
                    </label>

                    <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Site Status
                    </label>

                    <select class="form-select" name="site_status">
                        <option value="1" {{ ($data->site_status ?? null) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($data->site_status ?? null) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payment Method
                    </label>

                    <select class="form-select" name="payment_method">
                        <option value="authorize" {{ ($data->payment_method ?? null) == 'authorize' ? 'selected' : '' }}>Authorize.net</option>
                        <option value="stripe" {{ ($data->payment_method ?? null) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api key
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_key" value="{{ $data->api_key ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api secret
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_secret" value="{{ $data->api_secret ?? null}}">
                </div>

                @php
                    $pages = \App\Models\Page::where('website_id',$data->user->website_id)->where('status',1)->get();
                @endphp

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Privacy Policy Page
                    </label>

                    <select class="form-select" name="privacy">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->privacy == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Terms & Condition Page
                    </label>

                    <select class="form-select" name="terms">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->terms == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Refund Policy
                    </label>

                    <select class="form-select" name="refund">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->refund == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- / Content -->
    
    <!-- Add Student Modal -->
    @if(Auth::user()->role == 'parents')
    <div class="modal fade" style="margin-top: 70px;" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 20px !important">
            <div class="modal-content">
                <form id="addStudentForm" action="{{ route('parent.add-student') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="form-text">Credentials are automatically generated for system use only and are not shared or tracked outside the fundraiser.</div>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                            <select class="form-select teacher-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Choose a teacher</option>
                                @if(isset($teachers))
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_goal" class="form-label">Fundraising Goal</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="modal_goal" name="goal" min="0" step="0.01">
                                <span class="input-group-text">.00 USD</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_tshirt_size" class="form-label">T-Shirt Size</label>
                            <select class="form-select" id="modal_tshirt_size" name="tshirt_size">
                                <option value="">Select a size</option>
                                <option value="Youth XS">Youth XS</option>
                                <option value="Youth Small">Youth Small</option>
                                <option value="Youth Medium">Youth Medium</option>
                                <option value="Youth Large">Youth Large</option>
                                <option value="Adult Small">Adult Small</option>
                                <option value="Adult Medium">Adult Medium</option>
                                <option value="Adult Large">Adult Large</option>
                                <option value="Adult XL">Adult XL</option>
                                <option value="Adult XXL">Adult XXL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_description" class="form-label">Profile Description</label>
                            <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="modal_photo" class="form-label">Upload Photo</label>
                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="modal_photo" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                            <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                            <div class="invalid-feedback" id="modal_photo_error" style="@error('photo') display: block; @else display: none; @enderror">@error('photo'){{ $message }}@enderror</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Wait for jQuery and Select2 to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Check if jQuery and Select2 are loaded
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                // Initialize Select2 for teacher select with search
                jQuery('.teacher-select').select2({
                    placeholder: 'Search and select a teacher',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>
    
    <script>
        ClassicEditor
          .create(document.querySelector('#description'))
          .catch(error => {
              console.error(error);
          });
      </script>

    <script>
    $(document).ready(function() {
        // Hide loader if there are backend validation errors
        @if($errors->any())
            const participantLoader = document.getElementById('participant-loader');
            if (participantLoader) {
                participantLoader.style.display = 'none';
            }
            document.body.classList.remove('page-locked');
            window.onbeforeunload = null;
        @endif
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Reopen modal if there are backend validation errors
        @if($errors->has('photo') || $errors->has('first_name') || $errors->has('last_name') || $errors->has('teacher_id'))
            var addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));
            addStudentModal.show();
            
            // Restore form values
            @if(old('first_name'))
                document.getElementById('first_name').value = "{{ old('first_name') }}";
            @endif
            @if(old('last_name'))
                document.getElementById('last_name').value = "{{ old('last_name') }}";
            @endif
            @if(old('teacher_id'))
                document.getElementById('teacher_id').value = "{{ old('teacher_id') }}";
            @endif
            @if(old('goal'))
                document.getElementById('modal_goal').value = "{{ old('goal') }}";
            @endif
            @if(old('tshirt_size'))
                document.getElementById('modal_tshirt_size').value = "{{ old('tshirt_size') }}";
            @endif
            @if(old('description'))
                document.getElementById('modal_description').value = `{{ old('description') }}`;
            @endif
        @endif
        
        const photoInput = document.getElementById('modal_photo');
        const photoError = document.getElementById('modal_photo_error');
        const form = document.getElementById('addStudentForm');
            const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
            let removeFileBtn = document.getElementById('removeFileBtn');
            
            // Create remove file button if not exists
            if (!removeFileBtn && photoInput) {
                removeFileBtn = document.createElement('button');
                removeFileBtn.id = 'removeFileBtn';
                removeFileBtn.type = 'button';
                removeFileBtn.className = 'btn btn-sm btn-danger ms-2';
                removeFileBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove File';
                removeFileBtn.style.display = 'none';
                removeFileBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    photoInput.classList.remove('is-invalid');
                    photoError.style.display = 'none';
                    photoError.textContent = '';
                    removeFileBtn.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = false;
                });
                photoInput.parentNode.appendChild(removeFileBtn);
            }
            
            if (photoInput && form) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    
                    if (file) {
                        // Clear previous errors only when a new file is selected
                        photoInput.classList.remove('is-invalid');
                        photoError.style.display = 'none';
                        photoError.textContent = '';
                        
                        // Check file size (5MB max)
                        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                        if (file.size > maxSize) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Get file extension
                        const fileName = file.name.toLowerCase();
                        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        const fileExtension = fileName.split('.').pop();
                        
                        // Check file extension first (catches HEIC, WEBP, etc.)
                        if (!allowedExtensions.includes(fileExtension)) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Check file type
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = 'Invalid file type. Please upload an image file (PNG, JPG, GIF).';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // File is valid - enable submit button and hide remove button
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    } else {
                        // No file selected - enable submit button (photo is optional)
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    }
                });
            }
            
            // Prevent form submission if there's a validation error
            form.addEventListener('submit', function(e) {
                // Check if there's a file and validate it immediately
                const file = photoInput.files[0];
                let hasError = false;
                let errorMessage = '';
                
                if (file) {
                    // Check file size
                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        hasError = true;
                        errorMessage = 'File size exceeds 5MB. Please choose a smaller image.';
                    }
                    
                    // Check file extension
                    const fileName = file.name.toLowerCase();
                    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    const fileExtension = fileName.split('.').pop();
                    if (!hasError && !allowedExtensions.includes(fileExtension)) {
                        hasError = true;
                        errorMessage = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                    }
                    
                    // Check MIME type
                    if (!hasError) {
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            hasError = true;
                            errorMessage = 'Invalid file type. Please upload an image file (PNG, JPG, GIF).';
                        }
                    }
                }
                
                // Also check if input already has invalid class
                if (!hasError && photoInput.classList.contains('is-invalid')) {
                    hasError = true;
                    errorMessage = 'Please fix the file upload error before submitting.';
                }
                
                if (hasError) {
                    // PREVENT submission completely
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Show error message
                    photoInput.classList.add('is-invalid');
                    photoError.style.display = 'block';
                    photoError.textContent = errorMessage;
                    photoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // DO NOT show loader
                    return false;
                }
                
                // ONLY show loader if we reach here (no errors)
                const participantLoader = document.getElementById('participant-loader');
                if (participantLoader) {
                    participantLoader.style.display = 'flex';
                }
                document.body.classList.add('page-locked');
            });
        }
    });
    </script>

@endsection


