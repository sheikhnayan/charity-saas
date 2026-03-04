@extends('user.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<!-- Intro.js for Tutorial -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

<style>
    .forms-wizard li.done em::before, .lnr-checkmark-circle::before {
  content: "\e87f";
}

.forms-wizard li.done em::before {
  display: block;
  font-size: 1.2rem;
  height: 42px;
  line-height: 40px;
  text-align: center;
  width: 42px;
}

.forms-wizard li.done em {
  font-family: Linearicons-Free;
}

.dt-buttons button span {
  color: #000 !important;
}

.paginate_buttons a {
  color: #000 !important;
}

.dataTables_filter {
  margin-bottom: 15px;
}

.dataTables_filter input {
  margin-left: 10px;
  padding: 5px 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

#DataTables_Table_0_filter label {
  color: #000 !important;
}
</style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            @if(Auth::user()->role == 'parents')
                                                My Participants
                                            @else
                                                Participants
                                            @endif
                                        </span>
                                        <div class="page-title-subheading">
                                            @if(Auth::user()->role == 'parents')
                                                Manage your Participants.
                                            @else
                                                View all Participants.
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                    @if(Auth::user()->role == 'parents')
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                            <i class="fas fa-plus me-2"></i>Add Participants
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="startParentTutorial()" id="tutorialBtn">
                                            <i class="fas fa-graduation-cap me-2"></i>View Tutorial
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="#">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Reports
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            users
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary p-4 card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <div class="table-responsive" style="overflow-x: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Teacher</th>
                                                <th>Parent/Guardian</th>
                                                <th>Goal</th>
                                                @if(Auth::user()->role == 'parents')
                                                <th>Raised</th>
                                                @endif
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="{{ Auth::user()->role == 'parents' ? '10' : '9' }}" class="text-center">No student found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>
                                                            <a href="/users/student/profile/{{ $item->id }}" class="text-decoration-none fw-bold text-primary">
                                                                {{ $item->name }} {{ $item->last_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($item->role) }}</span>
                                                        </td>
                                                        <td>{{ $item->teacher->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($item->parent)
                                                                <a href="/users/profile" class="text-decoration-none text-primary fw-semibold">
                                                                    {{ $item->parent->name }} {{ $item->parent->last_name }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            ${{ number_format($item->goal ?? 0, 2) }}
                                                        </td>
                                                        @if(Auth::user()->role == 'parents')
                                                        <td>
                                                            @php
                                                                // Calculate total raised from approved donations for this student
                                                                $totalRaised = \App\Models\Donation::where('user_id', $item->id)
                                                                    ->where('status', 1)
                                                                    ->sum('amount');
                                                            @endphp
                                                            ${{ number_format($totalRaised, 2) }}
                                                        </td>
                                                        @endif
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/users/student/profile/{{ $item->id }}" class="btn btn-sm btn-primary me-1" title="Edit Profile">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="/profile/{{ $item->id }}-{{ str_replace(' ', '-', $item->name) }}-{{ str_replace(' ', '-', $item->last_name) }}" class="btn btn-sm btn-info me-1" title="View Frontend Profile" target="_blank">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                            @if(Auth::user()->role == 'parents' && $item->status != 1)
                                                                <a href="/admins/student/approve/{{ $item->id }}" class="btn btn-sm btn-success" title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Include DataTables and jQuery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Only initialize DataTable if there are rows with data
                    @if (!$data->isEmpty())
                    let table = new DataTable('.table', {
                        dom: 'Bfrtip',
                        pageLength: 25,
                        language: {
                            search: 'Search',
                            searchPlaceholder: ''
                        },
                        buttons: [
                            {
                                extend: 'csv',
                                text: 'Export CSV',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true; // export all if none checked
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)' // Exclude checkbox and action columns
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Export Excel',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'pdf',
                                text: 'Export PDF',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            }
                        ]
                    });
                    @endif
                });
                
                // Initialize Select2 when modal is shown
                $('#addStudentModal').on('shown.bs.modal', function () {
                    if (!$('.teacher-select').hasClass('select2-hidden-accessible')) {
                        $('.teacher-select').select2({
                            placeholder: 'Search and select a teacher',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#addStudentModal')
                        });
                    }
                });
                
                // Destroy Select2 when modal is hidden to prevent duplicates
                $('#addStudentModal').on('hidden.bs.modal', function () {
                    if ($('.teacher-select').hasClass('select2-hidden-accessible')) {
                        $('.teacher-select').select2('destroy');
                    }
                });
            </script>
            
            <!-- Add Student Modal -->
            @if(Auth::user()->role == 'parents')
            <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true" style="margin-top:70px;">
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
                                            @foreach($teachers->sort(function($a, $b) {
                                                $nameA = preg_replace('/^(Mr|Ms|Mrs|Dr)\\.?\\s*/i', '', $a->name);
                                                $nameB = preg_replace('/^(Mr|Ms|Mrs|Dr)\\.?\\s*/i', '', $b->name);
                                                return strcasecmp($nameA, $nameB);
                                            }) as $teacher)
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
                            <div class="modal-footer pt-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

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
            </script>

            <!-- Parent Tutorial Script -->
            @if(Auth::user()->role == 'parents')
            <script>
                let isFirstVisit = @if(isset($showTutorial) && $showTutorial) true @else false @endif;
                
                function startParentTutorial() {
                    const intro = introJs();
                    
                    // Add class to body to hide skip button via CSS
                    if (isFirstVisit) {
                        document.body.classList.add('tutorial-first-visit');
                    }
                    
                    // Detect if user is on mobile
                    const isMobile = window.innerWidth < 768;
                    
                    // Build steps based on device type
                    let tutorialSteps = [];
                    
                    // Welcome step (both mobile and desktop)
                    tutorialSteps.push({
                        title: 'Welcome! 👋',
                        intro: 'Welcome to your dashboard! Let me show you how to add and manage participants under your profile.'
                    });
                    
                    if (isMobile) {
                        // Mobile-specific tutorial steps (skip sidebar references)
                        tutorialSteps.push({
                            title: 'Navigation Menu 📱',
                            intro: 'On mobile, tap the menu icon (☰) at the top to access all sections like participants, Profile, and Payments.',
                            tooltipClass: 'introjs-floating'
                        });
                        
                        tutorialSteps.push({
                            title: 'Adding Participants 🎓',
                            intro: 'To add a new participant:<br><br>1. Tap the menu icon (☰) at the top<br>2. Select "Participant"<br>3. Tap "Add Participant" button<br>4. Fill in their information<br>5. Tap "Save" to add them',
                            tooltipClass: 'introjs-floating'
                        });
                        
                        tutorialSteps.push({
                            title: 'Managing Participants',
                            intro: 'Once you\'ve added participants, you can:<br><br>• View their fundraising progress<br>• Edit their profile information<br>• Track donations received<br>• Share their fundraising page',
                            tooltipClass: 'introjs-floating'
                        });
                        
                        tutorialSteps.push({
                            element: document.querySelector('#tutorialBtn'),
                            title: 'Need Help Later?',
                            intro: 'You can always replay this tutorial by tapping this button anytime!',
                            position: 'bottom'
                        });
                        
                        tutorialSteps.push({
                            title: 'You\'re All Set! 🎉',
                            intro: 'That\'s it! You\'re ready to start managing your participants. Tap the menu icon (☰) and select "Participant" to get started!',
                            tooltipClass: 'introjs-floating'
                        });
                    } else {
                        // Desktop tutorial steps (original with sidebar references)
                        tutorialSteps.push({
                            element: document.querySelector('#students-menu-item'),
                            title: 'Participants',
                            intro: 'Click here to view and manage all your participants. This is where you\'ll spend most of your time!',
                            position: 'right'
                        });
                        
                        tutorialSteps.push({
                            element: document.querySelector('#profile-menu-item'),
                            title: 'Your Profile',
                            intro: 'Update your personal information and profile settings here.',
                            position: 'right'
                        });
                        
                        tutorialSteps.push({
                            title: 'Adding Participants 🎓',
                            intro: 'To add a new participant:<br><br>1. Click on "Participant" in the sidebar<br>2. Click the "Add Participant" button<br>3. Fill in their information<br>4. Click "Save" to add them to your account',
                            tooltipClass: 'introjs-floating'
                        });
                        
                        tutorialSteps.push({
                            title: 'Managing Participants',
                            intro: 'Once you\'ve added participants, you can:<br><br>• View their fundraising progress<br>• Edit their profile information<br>• Track donations received<br>• Share their fundraising page',
                            tooltipClass: 'introjs-floating'
                        });
                        
                        tutorialSteps.push({
                            element: document.querySelector('#tutorialBtn'),
                            title: 'Need Help Later?',
                            intro: 'You can always replay this tutorial by clicking this button anytime!',
                            position: 'left'
                        });
                        
                        tutorialSteps.push({
                            title: 'You\'re All Set! 🎉',
                            intro: 'That\'s it! You\'re ready to start managing your participants. Click "Participant" in the sidebar to get started!',
                            tooltipClass: 'introjs-floating'
                        });
                    }
                    
                    intro.setOptions({
                        steps: tutorialSteps,
                        showProgress: true,
                        showBullets: false,
                        exitOnOverlayClick: isFirstVisit ? false : true,
                        exitOnEsc: isFirstVisit ? false : true,
                        nextLabel: 'Next →',
                        prevLabel: '← Back',
                        doneLabel: 'Finish',
                        scrollToElement: true,
                        scrollPadding: 30,
                        disableInteraction: true,
                        overlayOpacity: 0.7
                    });
                    
                    // Prevent exit on first visit via any method
                    intro.onbeforeexit(function() {
                        if (isFirstVisit) {
                            console.log('Blocking exit on first visit');
                            return false;
                        }
                        return true;
                    });
                    
                    intro.oncomplete(function() {
                        isFirstVisit = false;
                        document.body.classList.remove('tutorial-first-visit');
                        markTutorialAsSeen();
                    });
                    
                    intro.onexit(function() {
                        if (!isFirstVisit) {
                            document.body.classList.remove('tutorial-first-visit');
                            markTutorialAsSeen();
                        }
                    });
                    
                    intro.start();
                }
                
                function markTutorialAsSeen() {
                    fetch('{{ route("parent.tutorial.seen") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => response.json())
                      .then(data => console.log('Tutorial marked as seen'))
                      .catch(error => console.error('Error:', error));
                }
                
                // Auto-start tutorial on first visit for parents
                @if(isset($showTutorial) && $showTutorial)
                document.addEventListener('DOMContentLoaded', function() {
                    // Small delay to ensure page is fully loaded
                    setTimeout(function() {
                        startParentTutorial();
                    }, 500);
                });
                @endif
            </script>
            <style>
                /* Intro.js Custom Styling */
                .introjs-overlay {
                    background: rgba(0, 0, 0, 0.5);
                }

                .introjs-tooltip {
                    max-width: 450px;
                    border-radius: 8px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                    background: white;
                }

                .introjs-tooltip-title {
                    font-size: 18px;
                    font-weight: 700;
                    padding: 15px 20px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border-radius: 8px 8px 0 0;
                }

                .introjs-tooltiptext {
                    font-size: 14px;
                    line-height: 1.6;
                    padding: 15px 20px;
                    color: #333;
                }

                .introjs-tooltipbuttons {
                    padding: 0 20px 15px;
                    display: flex;
                    gap: 8px;
                    justify-content: flex-end;
                }

                .introjs-button {
                    border-radius: 5px;
                    padding: 8px 16px;
                    font-weight: 600;
                    text-shadow: none;
                    cursor: pointer;
                    font-size: 12px;
                    border: none;
                    transition: all 0.2s ease;
                }

                .introjs-button:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                .introjs-nextbutton {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }

                .introjs-prevbutton {
                    background: #e2e8f0;
                    color: #2d3748;
                }

                .introjs-donebutton {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                }

                .introjs-skipbutton {
                    color: #475569;
                    background: #f8fafc;
                    padding: 0;
                    width: 15px;
                    height: 15px;
                    border: 1px solid #e2e8f0;
                    border-radius: 999px;
                    font-size: 16px;
                    font-weight: 600;
                    line-height: 28px;
                    text-align: center;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
                    margin-top: 21px;
                    margin-right: 4px;
                }

                .introjs-skipbutton:hover {
                    background: #f1f5f9;
                    color: #0f172a;
                }

                .introjs-skipbutton:disabled,
                .introjs-skipbutton.disabled {
                    display: none !important;
                }

                body.tutorial-first-visit .introjs-skipbutton {
                    display: none !important;
                }

                .introjs-progressbar {
                    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
                }

                /* Mobile responsive */
                @media(max-width: 768px) {
                    .introjs-tooltip {
                        max-width: 90vw;
                    }
                    
                    .introjs-tooltipbuttons {
                        flex-wrap: wrap;
                    }
                    
                    .introjs-button {
                        font-size: 11px;
                        padding: 6px 12px;
                        flex: 1;
                    }
                }

                /* Safari-specific fixes for Intro.js */
                @supports (-webkit-appearance:none) {
                    .introjs-tooltip {
                        -webkit-transform: translateZ(0);
                        transform: translateZ(0);
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                        will-change: transform, opacity;
                    }
                    
                    .introjs-helperLayer {
                        -webkit-transform: translateZ(0);
                        transform: translateZ(0);
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                    }
                    
                    .introjs-overlay {
                        -webkit-transform: translateZ(0);
                        transform: translateZ(0);
                    }
                    
                    /* Ensure tooltips are always visible in Safari */
                    .introjs-tooltipReferenceLayer {
                        visibility: visible !important;
                        -webkit-transform: translate3d(0, 0, 0);
                        transform: translate3d(0, 0, 0);
                    }
                    
                    /* Fix for centered tooltips without elements in Safari */
                    .introjs-tooltip.introjs-floating {
                        position: fixed !important;
                        left: 50% !important;
                        top: 50% !important;
                        -webkit-transform: translate(-50%, -50%) translateZ(0) !important;
                        transform: translate(-50%, -50%) translateZ(0) !important;
                        margin: 0 !important;
                    }
                }
            </style>
            @endif
        @endsection