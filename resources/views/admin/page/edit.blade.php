@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

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

label{
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
                                            Page
                                        </span>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="float: left">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/admins">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Setting
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Page
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <form action="{{ route('admin.page.update',[$data->id]) }}" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="website" class="form-label">Website</label>
                                                        <select name="website" id="website" class="form-control">
                                                            @foreach ($website as $item)
                                                                <option {{ $data->website_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Page Name</label>
                                                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" id="name" placeholder="Page Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option {{ $data->status == 0 ? 'selected' : '' }} value="0">Deactive</option>
                                                            <option {{ $data->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="default" class="form-label">
                                                            <i class="fas fa-home me-1"></i>Make Homepage
                                                        </label>
                                                        <select name="default" id="homepage_select" class="form-control" required>
                                                            <option {{ $data->default == 0 ? 'selected' : '' }} value="0">No</option>
                                                            <option {{ $data->default == 1 ? 'selected' : '' }} value="1">Yes</option>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            If set to "Yes", this page will be accessible via the domain itself (e.g., domain.com) and displayed as "Home"
                                                        </small>
                                                        @if($data->is_homepage)
                                                            <div class="alert alert-success mt-2">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                <strong>Current Homepage:</strong> This page is currently set as the homepage for this website.
                                                            </div>
                                                        @endif
                                                        <div id="homepage_warning" class="alert alert-warning mt-2" style="display: none;">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            <strong>Note:</strong> Setting this as homepage will automatically remove homepage status from other pages of this website.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="show_in_menu" class="form-label">
                                                            <i class="fas fa-bars me-1"></i>Show in Menu
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" {{ $data->show_in_menu ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="show_in_menu">
                                                                Display this page in the website navigation menu
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Toggle this to control whether the page appears in the menu. Useful for hidden pages like thank you pages or private pages.
                                                        </small>
                                                    </div>
                                                </div>
                                            <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Meta Title</label>
                                                        <input type="text" name="meta_title" class="form-control" id="meta_title" value="{{ $data->meta_title }}" placeholder="Meta Title">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Meta Description</label>
                                                        <textarea name="meta_description" class="form-control" id="meta_description" placeholder="Meta Description">{{ $data->meta_description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="meta_image" class="form-label">Meta Image (Open Graph)</label>
                                                        <input type="file" name="meta_image" class="form-control" id="meta_image" accept="image/*">
                                                        @if($data->meta_image)
                                                            <div class="mt-2">
                                                                <small class="text-muted">Current image:</small><br>
                                                                <img src="{{ asset($data->meta_image) }}" alt="Meta Image" style="max-width: 200px; max-height: 100px; object-fit: cover;" class="mt-1">
                                                            </div>
                                                        @endif
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Upload an image for social media sharing (recommended: 1200x630px)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4" data-step="3" data-title="Header text color"
                                                data-intro="Choose a color for the header text." bis_skin_checked="1">
                                                <label for="text_color" class="form-label">
                                                    Page Background
                                                </label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="text_color_picker"
                                                        value="{{ $data->background_color ?? '#000000' }}" title="Choose your color"
                                                        style="max-width: 3rem;">
                                                    <input type="text" class="form-control" id="text_color" name="background_color"
                                                        value="{{ $data->background_color ?? '#000000' }}" placeholder="#000000 or color name">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const colorInput = document.getElementById('text_color_picker');
                                                        const textInput = document.getElementById('text_color');
                                                        // Sync color picker to text
                                                        colorInput.addEventListener('input', function() {
                                                            textInput.value = colorInput.value;
                                                        });
                                                        // Sync text to color picker if valid hex
                                                        textInput.addEventListener('input', function() {
                                                            const val = textInput.value.trim();
                                                            if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                                colorInput.value = val;
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <!-- Template Actions -->
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="card border">
                                                        <div class="card-header">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-file-alt me-2"></i>Template Actions
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="btn-group w-100 mb-3" role="group">
                                                                <button type="button" class="btn btn-outline-success" onclick="showSaveTemplateModal()">
                                                                    <i class="fas fa-save me-1"></i>Save as Template
                                                                </button>
                                                                <button type="button" class="btn btn-outline-info" onclick="showApplyTemplateModal()">
                                                                    <i class="fas fa-paste me-1"></i>Apply Template
                                                                </button>
                                                                <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-primary" target="_blank">
                                                                    <i class="fas fa-list me-1"></i>Browse Templates
                                                                </a>
                                                            </div>
                                                            
                                                            @if($data->template_id)
                                                                <div class="alert alert-info">
                                                                    <i class="fas fa-info-circle me-2"></i>
                                                                    This page was created from template: 
                                                                    <strong>{{ $data->template->name ?? 'Unknown Template' }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.page.websites') }}" class="btn btn-danger">Cancel</a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Save as Template Modal -->
<div class="modal fade" id="saveTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Save Page as Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="saveTemplateForm">
                    <div class="mb-3">
                        <label for="template_name" class="form-label">Template Name</label>
                        <input type="text" class="form-control" id="template_name" name="template_name" 
                               value="{{ $data->name }} Template" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="template_description" class="form-label">Description</label>
                        <textarea class="form-control" id="template_description" name="template_description" 
                                  rows="3" placeholder="Describe what this template is for..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="template_category" class="form-label">Category</label>
                        <select class="form-select" id="template_category" name="template_category">
                            <option value="general">General</option>
                            <option value="landing">Landing Page</option>
                            <option value="about">About</option>
                            <option value="contact">Contact</option>
                            <option value="services">Services</option>
                            <option value="portfolio">Portfolio</option>
                            <option value="blog">Blog</option>
                            <option value="e-commerce">E-commerce</option>
                        </select>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="template_is_public" 
                               name="is_public" checked>
                        <label class="form-check-label" for="template_is_public">
                            Make this template available to all users
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="saveAsTemplate()">Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Apply Template Modal -->
<div class="modal fade" id="applyTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply Template to Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="template_category_filter" class="form-label">Filter by Category</label>
                    <select class="form-select" id="template_category_filter" onchange="loadTemplates()">
                        <option value="">All Categories</option>
                        <option value="general">General</option>
                        <option value="landing">Landing Page</option>
                        <option value="about">About</option>
                        <option value="contact">Contact</option>
                        <option value="services">Services</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="blog">Blog</option>
                        <option value="e-commerce">E-commerce</option>
                    </select>
                </div>
                
                <div id="templatesContainer">
                    <p class="text-center">Loading templates...</p>
                </div>
                
                <div class="alert alert-warning mt-3" id="applyWarning" style="display: none;">
                    <strong>Warning:</strong> Applying a template will replace the current page content. 
                    Make sure to save any changes first.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="applyTemplateBtn" 
                        onclick="applySelectedTemplate()" disabled>Apply Template</button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedTemplateId = null;

function showSaveTemplateModal() {
    const modal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
    modal.show();
}

function showApplyTemplateModal() {
    const modal = new bootstrap.Modal(document.getElementById('applyTemplateModal'));
    modal.show();
    loadTemplates();
}

function saveAsTemplate() {
    const formData = new FormData(document.getElementById('saveTemplateForm'));
    
    fetch('{{ route("admin.templates.save-from-page", $data->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('saveTemplateModal')).hide();
            // Optionally redirect to templates page
            const viewTemplate = confirm('Template saved! Would you like to view the templates page?');
            if (viewTemplate) {
                window.open('{{ route("admin.templates.index") }}', '_blank');
            }
        } else {
            alert('Error saving template: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving template');
    });
}

function loadTemplates() {
    const category = document.getElementById('template_category_filter').value;
    const container = document.getElementById('templatesContainer');
    
    container.innerHTML = '<p class="text-center">Loading templates...</p>';
    
    fetch(`{{ route('admin.templates.get') }}?category=${category}`)
    .then(response => response.json())
    .then(templates => {
        if (templates.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">No templates found.</p>';
            return;
        }
        
        let html = '<div class="row">';
        templates.forEach(template => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card template-card" onclick="selectTemplate(${template.id}, '${template.name}')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title">${template.name}</h6>
                                    <p class="card-text text-muted small">
                                        ${template.description || 'No description'}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-download me-1"></i>Used ${template.usage_count} times
                                    </small>
                                </div>
                                <span class="badge bg-label-primary">${template.category || 'General'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        container.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<p class="text-center text-danger">Error loading templates.</p>';
    });
}

function selectTemplate(templateId, templateName) {
    // Remove previous selection
    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('border-success');
        card.style.borderWidth = '';
    });
    
    // Highlight selected template
    event.currentTarget.classList.add('border-success');
    event.currentTarget.style.borderWidth = '2px';
    
    selectedTemplateId = templateId;
    document.getElementById('applyTemplateBtn').disabled = false;
    document.getElementById('applyWarning').style.display = 'block';
}

function applySelectedTemplate() {
    if (!selectedTemplateId) {
        alert('Please select a template first');
        return;
    }
    
    if (!confirm('Are you sure you want to apply this template? This will replace the current page content.')) {
        return;
    }
    
    fetch(`{{ route('admin.templates.apply-to-page', ['template' => '__TEMPLATE_ID__', 'page' => $data->id]) }}`.replace('__TEMPLATE_ID__', selectedTemplateId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('applyTemplateModal')).hide();
            // Reload the page to show applied template content
            window.location.reload();
        } else {
            alert('Error applying template: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error applying template');
    });
}

// CSS for template selection
const style = document.createElement('style');
style.textContent = `
    .template-card {
        cursor: pointer;
        transition: all 0.2s;
    }
    .template-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
`;
document.head.appendChild(style);

// Handle homepage selection change
document.addEventListener('DOMContentLoaded', function() {
    const homepageSelect = document.getElementById('homepage_select');
    const homepageWarning = document.getElementById('homepage_warning');
    const currentIsHomepage = {{ $data->is_homepage ? 'true' : 'false' }};
    
    if (homepageSelect && homepageWarning) {
        homepageSelect.addEventListener('change', function() {
            // Show warning only if changing TO homepage (and not already homepage)
            if (this.value === '1' && !currentIsHomepage) {
                homepageWarning.style.display = 'block';
            } else {
                homepageWarning.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
