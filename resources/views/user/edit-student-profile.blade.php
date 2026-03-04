@extends('user.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('user/extra.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        .forms-wizard li.done em::before,
        .lnr-checkmark-circle::before {
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
    </style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-site-information">
                            <div class="main-card card">
                                <div class="card-body">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left me-3 d-none d-md-block">
                                                    <div class="widget-content-left">
                                                        <img width="42" class="rounded" alt="{{ $currentWebsite->name }}"
                                                            src="{{ asset('uploads/' . $currentWebsite->setting->logo) }}">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ $currentWebsite->name }}
                                                    </div>
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard" role="button"
                                                            data-clipboard-text="http://{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}"></i>
                                                        <a href="http://{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}"
                                                            class="link-info"
                                                            target="_blank">{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}</a>
                                                    </div>
                                                </div>

                                                <div class="widget-content-right">
                                                    <div class="btn-group d-none d-md-inline-flex me-2" role="group">
                                                        <a href="/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}"
                                                            class="btn btn-info btn-hover-info" target="_blank">
                                                            <i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i>
                                                            <span>View</span>
                                                        </a>

                                                        <button type="button" class="btn btn-success btn-hover-info"
                                                            data-bs-toggle="modal" data-bs-target="#modal-share">
                                                            <i class="fa-solid fa-share-nodes fa-fw" aria-hidden="true"></i>
                                                            <span>Share</span>
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-primary btn-hover-info" onclick="copyProfileUrl()">
                                                            <i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i>
                                                            <span>Copy URL</span>
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-warning btn-hover-info" id="generateQRBtn" onclick="generateStudentQR()">
                                                            <i class="fa-solid fa-qrcode fa-fw" aria-hidden="true"></i>
                                                            <span>QR Code</span>
                                                        </button>
                                                    </div>
                                                    
                                                    <a href="/users/student" class="btn btn-secondary">
                                                        <i class="fa-solid fa-arrow-left fa-fw" aria-hidden="true"></i>
                                                        <span>Back to Particiipants</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            Student Profile
                                        </span>
                                        <div class="page-title-subheading">
                                            Edit profile information.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/users">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            <a href="/users/student">My Participants</a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Edit Profile
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <ul class="forms-wizard profile-progress-steps">
                            <li class="done">
                                <span>
                                    <em>1</em>
                                    <span>Profile</span>
                                </span>
                            </li>
                            <li class="done">
                                <span>
                                    <em>2</em>
                                    <span>Approved</span>
                                </span>
                            </li>
                        </ul>




                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-primary">
                                            <div>
                                                <h5 class="menu-header-title">
                                                    <a href="{{ $user->website->domain }}/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}"
                                                        class="link-light">
                                                        {{ $user->name }} {{ $user->last_name }}
                                                    </a>
                                                </h5>
                                                <h6 class="menu-header-subtitle text-capitalize">
                                                    {{ $user->role }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-center-fixed-width main-card mb-4 card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form id="editStudentForm" action="{{ route('parent.update-student', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-3">

                                    <div class="col-12">
                                        <label for="goal" class="form-label">Fundraising Goal</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="goal"
                                                name="goal" value="{{ $user->goal }}">
                                            <span class="input-group-text">.00 USD</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="tshirt_size" class="form-label">T-Shirt Size</label>
                                        <select class="form-select" id="tshirt_size" name="tshirt_size">
                                            <option value="">Select a size</option>
                                            <option value="Youth XS" {{ $user->tshirt_size == 'Youth XS' ? 'selected' : '' }}>Youth XS</option>
                                            <option value="Youth Small" {{ $user->tshirt_size == 'Youth Small' ? 'selected' : '' }}>Youth Small</option>
                                            <option value="Youth Medium" {{ $user->tshirt_size == 'Youth Medium' ? 'selected' : '' }}>Youth Medium</option>
                                            <option value="Youth Large" {{ $user->tshirt_size == 'Youth Large' ? 'selected' : '' }}>Youth Large</option>
                                            <option value="Adult Small" {{ $user->tshirt_size == 'Adult Small' ? 'selected' : '' }}>Adult Small</option>
                                            <option value="Adult Medium" {{ $user->tshirt_size == 'Adult Medium' ? 'selected' : '' }}>Adult Medium</option>
                                            <option value="Adult Large" {{ $user->tshirt_size == 'Adult Large' ? 'selected' : '' }}>Adult Large</option>
                                            <option value="Adult XL" {{ $user->tshirt_size == 'Adult XL' ? 'selected' : '' }}>Adult XL</option>
                                            <option value="Adult XXL" {{ $user->tshirt_size == 'Adult XXL' ? 'selected' : '' }}>Adult XXL</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label required">
                                            First Name
                                        </label>
                                        <input type="text" class="form-control" id="first_name" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label required">
                                            Last Name
                                        </label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="{{ $user->last_name }}" required>
                                    </div>

                                    {{-- <div class="col-12">
                                        <label for="email" class="form-label required">
                                            Email
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" readonly>
                                        <div class="form-text">Email cannot be changed</div>
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="description" class="form-label ">
                                            Profile Description
                                        </label>
                                        <textarea class="form-control text-editor" id="description" name="description"
                                            rows="3" style="visibility: hidden;">
                                            {!! $user->description !!}
                                        </textarea>
                                    </div>

                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            Profile Photo
                                        </h5>
                                        @if($user->photo)
                                            <img src="{{ asset($user->photo) }}" width="150px" class="mb-3">
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label for="photo" class="form-label ">
                                            Upload New Photo
                                        </label>
                                        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo-image-file" name="photo"
                                            accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                                        <div class="invalid-feedback" id="photo_error" style="display: none;"></div>
                                        @error('photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                                    </div>

                                </div>

                                <div class="sticky-save-button-container">
                                    <div class="sticky-save-button-inner">
                                        <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                            type="submit" id="">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
        
        <!-- QR Code Modal -->
        <!-- Share Modal -->
        <div class="modal fade" id="modal-share" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-share-nodes me-2"></i> Share {{ $user->name }}'s Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Share this profile to help {{ $user->name }} reach more supporters</p>
                        
                        <!-- Social Share Buttons -->
                        <div class="mb-4">
                            <p class="fw-semibold mb-3">Share on social media:</p>
                            <div class="d-flex gap-3">
                                <button class="share-btn-circle btn btn-whatsapp" id="whatsappShare" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                <button class="share-btn-circle btn btn-twitter" id="twitterShare" title="X">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </button>
                                <button class="share-btn-circle btn btn-facebook" id="facebookShare" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                                <button class="share-btn-circle btn btn-email" id="emailShare" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Copy URL Section -->
                        <div class="mt-4">
                            <p class="fw-semibold mb-3">Copy your profile URL:</p>
                            <div class="input-group">
                                <input type="text" class="form-control" id="profileUrl" placeholder="Profile URL" readonly>
                                <button class="btn btn-outline-primary" type="button" onclick="copyProfileUrlFromModal()">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .share-btn-circle {
                width: 60px !important;
                height: 60px !important;
                padding: 0 !important;
                border-radius: 50% !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 24px !important;
            }
            .share-btn-circle i {
                line-height: 1 !important;
            }
            .btn-whatsapp {
                background-color: #25D366 !important;
                color: white !important;
                border: none !important;
            }
            .btn-whatsapp:hover {
                background-color: #1ead50 !important;
                color: white !important;
            }
            .btn-twitter {
                background-color: #1DA1F2 !important;
                color: white !important;
                border: none !important;
            }
            .btn-twitter .fa-x-twitter {
                color: #fff !important;
            }
            .btn-twitter:hover {
                background-color: #1a8cd8 !important;
                color: white !important;
            }
            .btn-facebook {
                background-color: #1877F2 !important;
                color: white !important;
                border: none !important;
            }
            .btn-facebook:hover {
                background-color: #0a66c2 !important;
                color: white !important;
            }
            .btn-email {
                background-color: #6c757d !important;
                color: white !important;
                border: none !important;
            }
            .btn-email:hover {
                background-color: #5a6268 !important;
                color: white !important;
            }

            .btn-twitter{
                background: #000 !important;
            }
        </style>
        
        <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-qrcode me-2"></i> Student Donation QR Code
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-muted mb-3">Share this QR code with supporters to receive donations for {{ $user->name }}</p>
                        <div id="qrCodeContainer" style="display: none;">
                            <img id="qrCodeImage" src="" alt="Student QR Code" style="max-width: 400px; border: 3px solid #28a745; padding: 15px; border-radius: 10px;">
                            <div id="qrInfo" class="mt-3 text-start">
                                <small class="text-muted"><strong>Student:</strong> <span id="qrStudentName">-</span></small><br>
                                <small class="text-muted"><strong>URL:</strong> <code id="qrUrl" style="font-size: 0.75rem;">-</code></small>
                            </div>
                        </div>
                        <div id="qrLoading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Generating QR Code...</span>
                            </div>
                            <p class="text-muted mt-2">Generating QR Code...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="downloadStudentQR()">
                            <i class="fas fa-download me-1"></i> Download QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        let currentStudentQRData = null;
        
        async function generateStudentQR() {
            const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
            modal.show();
            
            // Show loading, hide QR
            document.getElementById('qrLoading').style.display = 'block';
            document.getElementById('qrCodeContainer').style.display = 'none';
            
            try {
                const response = await fetch('{{ route("users.student-qr.generate", $user->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        student_id: {{ $user->id }}
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    currentStudentQRData = result;
                    document.getElementById('qrCodeImage').src = result.qr_code_base64;
                    document.getElementById('qrStudentName').textContent = '{{ $user->name }} {{ $user->last_name }}';
                    document.getElementById('qrUrl').textContent = result.donation_url;
                    
                    document.getElementById('qrLoading').style.display = 'none';
                    document.getElementById('qrCodeContainer').style.display = 'block';
                } else {
                    alert('Error: ' + (result.message || 'Failed to generate QR code'));
                    modal.hide();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error generating QR code: ' + error.message);
                modal.hide();
            }
        }
        
        function downloadStudentQR() {
            if (!currentStudentQRData) {
                alert('Please generate QR code first');
                return;
            }
            
            const link = document.createElement('a');
            link.download = 'student-qr-{{ $user->id }}-' + Date.now() + '.png';
            link.href = currentStudentQRData.qr_code_base64;
            link.click();
        }
        </script>
        
        <script>
        function copyProfileUrl() {
            const profileUrl = window.location.origin + '/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}';
            
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = profileUrl;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                // Show success message
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check fa-fw"></i><span>Copied!</span>';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-primary');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL. Please copy manually: ' + profileUrl);
            }
            
            document.body.removeChild(textarea);
        }
        
        function copyProfileUrlFromModal() {
            const profileUrl = window.location.origin + '/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}';
            const urlInput = document.getElementById('profileUrl');
            
            // Select the text in the input
            urlInput.select();
            
            try {
                document.execCommand('copy');
                
                // Show success feedback
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL. Please copy manually: ' + profileUrl);
            }
        }
        
        // Initialize Share Modal
        document.getElementById('modal-share').addEventListener('show.bs.modal', function() {
            const profileUrl = window.location.origin + '/profile/{{ $user->id }}-{{ str_replace(' ', '-', $user->name) }}-{{ str_replace(' ', '-', $user->last_name) }}';
            const studentName = '{{ $user->name }} {{ $user->last_name }}';
            const shareMessage = 'Check out ' + studentName + '\'s fundraising profile!';
            
            // Set the profile URL in the input field
            document.getElementById('profileUrl').value = profileUrl;
            
            // WhatsApp Share
            document.getElementById('whatsappShare').onclick = function() {
                const whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(shareMessage + ' ' + profileUrl);
                window.open(whatsappUrl, '_blank', 'width=600,height=400');
            };
            
            // Twitter Share
            document.getElementById('twitterShare').onclick = function() {
                const twitterUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareMessage) + '&url=' + encodeURIComponent(profileUrl);
                window.open(twitterUrl, '_blank', 'width=600,height=400');
            };
            
            // Facebook Share
            document.getElementById('facebookShare').onclick = function() {
                const facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(profileUrl);
                window.open(facebookUrl, '_blank', 'width=600,height=400');
            };
            
            // Email Share
            document.getElementById('emailShare').onclick = function() {
                const subject = 'Check out ' + studentName + '\'s Fundraising Profile';
                const body = shareMessage + '%0A%0A' + profileUrl;
                window.location.href = 'mailto:?subject=' + encodeURIComponent(subject) + '&body=' + body;
            };
        });
        </script>

        <script>
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'undo', 'redo'
                        ]
                    },
                    image: {
                        toolbar: [
                            'imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline',
                            'imageStyle:block', 'imageStyle:side'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells'
                        ]
                    },
                    mediaEmbed: {
                        previewsInData: true
                    }
                })
                .then(editor => {
                    // Custom upload adapter for images
                    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                        return {
                            upload: () => {
                                return loader.file.then(file => {
                                    return new Promise((resolve, reject) => {
                                        const reader = new FileReader();
                                        reader.onload = () => {
                                            resolve({ default: reader.result });
                                        };
                                        reader.onerror = error => reject(error);
                                        reader.readAsDataURL(file);
                                    });
                                });
                            }
                        };
                    };
                })
                .catch(error => {
                    console.error(error);
                });
        </script>

        <!-- Photo Upload Validation -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo-image-file');
            const photoError = document.getElementById('photo_error');
            const form = document.getElementById('editStudentForm');
            const editSubmitBtn = form ? form.querySelector('button[type="submit"]') : null;
            let editRemoveFileBtn = document.getElementById('removeFileBtn');
            
            // Create remove file button if not exists
            if (!editRemoveFileBtn && photoInput) {
                editRemoveFileBtn = document.createElement('button');
                editRemoveFileBtn.id = 'removeFileBtn';
                editRemoveFileBtn.type = 'button';
                editRemoveFileBtn.className = 'btn btn-sm btn-danger ms-2';
                editRemoveFileBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove File';
                editRemoveFileBtn.style.display = 'none';
                editRemoveFileBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    photoInput.classList.remove('is-invalid');
                    if (photoError) {
                        photoError.style.display = 'none';
                        photoError.textContent = '';
                    }
                    editRemoveFileBtn.style.display = 'none';
                    if (editSubmitBtn) editSubmitBtn.disabled = false;
                });
                photoInput.parentNode.appendChild(editRemoveFileBtn);
            }
            
            if (photoInput && form) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    
                    if (file) {
                        // Clear previous errors only when a new file is selected
                        photoInput.classList.remove('is-invalid');
                        if (photoError) {
                            photoError.style.display = 'none';
                            photoError.textContent = '';
                        }
                        
                        // Check file size (5MB = 5 * 1024 * 1024 bytes)
                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            photoInput.classList.add('is-invalid');
                            if (photoError) {
                                photoError.style.display = 'block';
                                photoError.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                            }
                            e.target.value = '';
                            if (editSubmitBtn) editSubmitBtn.disabled = true;
                            if (editRemoveFileBtn) editRemoveFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Get file extension
                        const fileName = file.name.toLowerCase();
                        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        const fileExtension = fileName.split('.').pop();
                        
                        // Check file extension first (catches HEIC, WEBP, etc.)
                        if (!allowedExtensions.includes(fileExtension)) {
                            photoInput.classList.add('is-invalid');
                            if (photoError) {
                                photoError.style.display = 'block';
                                photoError.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                            }
                            e.target.value = '';
                            if (editSubmitBtn) editSubmitBtn.disabled = true;
                            if (editRemoveFileBtn) editRemoveFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Check file type
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            photoInput.classList.add('is-invalid');
                            if (photoError) {
                                photoError.style.display = 'block';
                                photoError.textContent = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                            }
                            e.target.value = '';
                            if (editSubmitBtn) editSubmitBtn.disabled = true;
                            if (editRemoveFileBtn) editRemoveFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // File is valid - enable submit button and hide remove button
                        if (editSubmitBtn) editSubmitBtn.disabled = false;
                        if (editRemoveFileBtn) editRemoveFileBtn.style.display = 'none';
                    } else {
                        // No file selected - enable submit button (photo is optional)
                        if (editSubmitBtn) editSubmitBtn.disabled = false;
                        if (editRemoveFileBtn) editRemoveFileBtn.style.display = 'none';
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
                                errorMessage = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
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
                        if (photoError) {
                            photoError.style.display = 'block';
                            photoError.textContent = errorMessage;
                        }
                        photoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // DO NOT show loader
                        return false;
                    }
                    
                    // ONLY show loader if we reach here (no errors)
                    const studentEditLoader = document.getElementById('student-edit-loader');
                    if (studentEditLoader) {
                        studentEditLoader.style.display = 'flex';
                    }
                    document.body.classList.add('page-locked');
                    window.onbeforeunload = function() {
                        return 'Please wait while the profile is being updated.';
                    };
                });
            }
        });
        </script>

        <!-- Student Edit Form Processing Loader -->
        <div id="student-edit-loader" style="display: none;">
            <div class="payment-loader-overlay"></div>
            <div class="payment-loader-container">
                <div class="payment-loader-content">
                    <div class="spinner-border text-primary mb-4" role="status">
                        <span class="visually-hidden">Processing...</span>
                    </div>
                    <h3 class="mb-3">Updating Profile</h3>
                    <p class="loader-message">Please wait while we save the changes...</p>
                    <div class="loader-warnings mt-4">
                        <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not refresh the page</p>
                        <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not close this window</p>
                        <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not navigate away</p>
                    </div>
                    <p class="loader-subtext mt-4">This may take a few moments...</p>
                </div>
            </div>
        </div>

        <style>
            .page-locked {
                pointer-events: none;
                user-select: none;
            }
            .page-locked #student-edit-loader,
            #student-edit-loader {
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
            #student-edit-loader .payment-loader-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
            }
            #student-edit-loader .payment-loader-container {
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

        <script>
            $(document).ready(function() {
                // Hide loader if there are backend validation errors
                @if($errors->any())
                    const studentEditLoader = document.getElementById('student-edit-loader');
                    if (studentEditLoader) {
                        studentEditLoader.style.display = 'none';
                    }
                    document.body.classList.remove('page-locked');
                    window.onbeforeunload = null;
                @endif
            });
        </script>
@endsection
