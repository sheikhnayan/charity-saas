@extends('user.main')

@section('content')

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
            <div class="card">
            <div class="d-flex align-items-start row">
                <div class="col-sm-7">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">{{ Auth::user()->website->name }} </h5>
                    {{-- <p class="mb-6">
                        Peer to Peer (Premium)
                    </p> --}}

                    <a href="http://{{ Auth::user()->website->domain }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ Auth::user()->website->domain }}</a>
                </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                <div class="card-body pb-0 px-0 px-md-6">
                    <img
                    src="{{ asset('uploads/'.Auth::user()->website->setting->logo) }}"
                    height="175"
                    alt="View Badge User" />
                </div>
                </div>
            </div>
            </div>

            <div class="main-card mb-4 card mt-4">
                <div class="card-body steps-to-success-container">
                    <div class="jumbotron mb-0">
                        <h1 class="display-4">
                            Hello, {{ Auth::user()->name }} {{ Auth::user()->last_name }} !
                        </h1>

                        <p class="lead">
                            This is your dashboard, where you can manage your profile and view reports on your fundraising progress.
                        </p>

                                        <hr class="my-4">

                            <p class="mb-0">
                                Your personal fundraising page is: <a href="http://{{ Auth::user()->website->domain }}/profile/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">{{ Auth::user()->website->domain }}/profile/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}</a>.
                            </p>


                            <p class="mt-3">
                                Copy and paste your fundraising link above and text it to your family and friends
                            </p>
                                </div>

                    <div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-info bg-info">
                                        <i class="fa-solid fa-id-card-clip text-white" role="img" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <h4 class="timeline-title">
                                        <a href="/users/profile" class="link-info">
                                            Setup your profile
                                        </a>
                                    </h4>
                                    <p>
                                        Review your profile page and make sure all your details are correct
                                    </p>
                                </div>
                            </div>
                        </div>

                                </div>
                </div>
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

    <!-- Add Participant Processing Loader -->
    @if(Auth::user()->role == 'parents')
    <div id="participant-loader" style="display: none;">
        <div class="payment-loader-overlay"></div>
        <div class="payment-loader-container">
            <div class="payment-loader-content">
                <div class="spinner-border text-primary mb-4" role="status">
                    <span class="visually-hidden">Processing...</span>
                </div>
                <h3 class="mb-3">Adding Participant</h3>
                <p class="loader-message">Please wait while we save the participant...</p>
                <div class="loader-warnings mt-4">
                    <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not refresh the page</p>
                    <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not close this window</p>
                    <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not navigate away</p>
                </div>
                <p class="loader-subtext mt-4">This may take a few moments...</p>
            </div>
        </div>
    </div>
    @endif

    <style>
        .page-locked {
            pointer-events: none;
            user-select: none;
        }
        .page-locked #participant-loader,
        #participant-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #participant-loader .payment-loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
        }
        #participant-loader .payment-loader-container {
            position: relative;
            z-index: 1;
            background: #fff;
            padding: 32px;
            border-radius: 12px;
            max-width: 520px;
            width: calc(100% - 40px);
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>

    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Wait for jQuery and Select2 to be fully loaded
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
            // Check if jQuery and Select2 are loaded
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                // Initialize Select2 for teacher select with search
                jQuery('.teacher-select').select2({
                    placeholder: 'Search and select a teacher',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Photo upload validation
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
            });
            
            // Photo upload validation
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


