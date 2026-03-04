@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .font-preview {
        padding: 20px;
        margin: 10px 0;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: #f9f9f9;
    }
    
    .font-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .font-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .font-preview-text {
        font-size: 24px;
        margin: 10px 0;
    }
    
    .font-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .font-meta {
        color: #666;
        font-size: 14px;
    }
    
    .badge-active {
        background: #28a745;
    }
    
    .badge-inactive {
        background: #6c757d;
    }
    
    .upload-area {
        border: 2px dashed #007bff;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .upload-area:hover {
        background: #e9ecef;
        border-color: #0056b3;
    }
    
    .upload-area i {
        font-size: 48px;
        color: #007bff;
        margin-bottom: 15px;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-font icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">Font Management</span>
                                    <div class="page-title-subheading">
                                        Upload and manage custom fonts for your text editors
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload New Font</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fonts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="font_name" class="form-label">Font Name *</label>
                                        <input type="text" class="form-control" id="font_name" name="font_name" 
                                               placeholder="e.g., My Custom Font" required>
                                        <small class="text-muted">This will be displayed in the font selector</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="font_file" class="form-label">Font File *</label>
                                        <input type="file" class="form-control" id="font_file" name="font_file" 
                                               accept=".ttf,.otf,.woff,.woff2" required>
                                        <small class="text-muted">Supported formats: TTF, OTF, WOFF, WOFF2 (Max 10MB)</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Font
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Fonts List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Uploaded Fonts ({{ $fonts->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if($fonts->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-font" style="font-size: 64px; color: #ccc;"></i>
                                    <p class="text-muted mt-3">No fonts uploaded yet. Upload your first font above!</p>
                                </div>
                            @else
                                @foreach($fonts as $font)
                                    <div class="font-item">
                                        <div class="font-info">
                                            <div>
                                                <h5 class="mb-1">{{ $font->font_name }}</h5>
                                                <div class="font-meta">
                                                    <span class="badge {{ $font->is_active ? 'badge-active' : 'badge-inactive' }}">
                                                        {{ $font->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <span class="ms-2">
                                                        <i class="fas fa-code me-1"></i>{{ strtoupper($font->file_format) }}
                                                    </span>
                                                    <span class="ms-2">
                                                        <i class="fas fa-file me-1"></i>{{ number_format($font->file_size / 1024, 2) }} KB
                                                    </span>
                                                    <span class="ms-2 text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $font->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <form action="{{ route('admin.fonts.toggle', $font->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $font->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                            title="{{ $font->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $font->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.fonts.destroy', $font->id) }}" method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this font?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <div class="font-preview">
                                            <style>
                                                @font-face {
                                                    font-family: '{{ $font->font_family }}-preview';
                                                    src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
                                                }
                                            </style>
                                            <div class="font-preview-text" style="font-family: '{{ $font->font_family }}-preview', sans-serif;">
                                                The quick brown fox jumps over the lazy dog
                                            </div>
                                            <div class="font-preview-text" style="font-family: '{{ $font->font_family }}-preview', sans-serif;">
                                                ABCDEFGHIJKLMNOPQRSTUVWXYZ
                                            </div>
                                            <div class="font-preview-text" style="font-family: '{{ $font->font_family }}-preview', sans-serif;">
                                                abcdefghijklmnopqrstuvwxyz 0123456789
                                            </div>
                                            <small class="text-muted">CSS Font Family: <code>{{ $font->font_family }}</code></small>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>How to Use Custom Fonts</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-2"><strong>Upload Your Font:</strong> Use the form above to upload TTF, OTF, WOFF, or WOFF2 font files.</li>
                                <li class="mb-2"><strong>Activate the Font:</strong> Ensure the font is marked as "Active" (green badge).</li>
                                <li class="mb-2"><strong>Use in Text Editors:</strong> Active fonts automatically appear in all TinyMCE editors throughout the site.</li>
                                <li class="mb-2"><strong>CSS Font Family:</strong> The generated CSS font-family name is shown under each font preview.</li>
                                <li class="mb-2"><strong>Best Practices:</strong>
                                    <ul>
                                        <li>Use WOFF2 format when possible (smallest file size, best performance)</li>
                                        <li>Keep font files under 500KB for optimal loading speed</li>
                                        <li>Use descriptive font names for easy identification</li>
                                    </ul>
                                </li>
                            </ol>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> After uploading or activating fonts, they will be immediately available in all text editors. No page refresh needed!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
