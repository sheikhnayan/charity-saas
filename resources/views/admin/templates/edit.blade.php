@extends('admin.main')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Template: {{ $template->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-list-ul me-1"></i>All Templates
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('admin.templates.update', $template) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Template Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Template Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $template->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category">
                                    <option value="general" {{ old('category', $template->category) == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="landing" {{ old('category', $template->category) == 'landing' ? 'selected' : '' }}>Landing Page</option>
                                    <option value="about" {{ old('category', $template->category) == 'about' ? 'selected' : '' }}>About</option>
                                    <option value="contact" {{ old('category', $template->category) == 'contact' ? 'selected' : '' }}>Contact</option>
                                    <option value="services" {{ old('category', $template->category) == 'services' ? 'selected' : '' }}>Services</option>
                                    <option value="portfolio" {{ old('category', $template->category) == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="blog" {{ old('category', $template->category) == 'blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="e-commerce" {{ old('category', $template->category) == 'e-commerce' ? 'selected' : '' }}>E-commerce</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe what this template is for...">{{ old('description', $template->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                       value="{{ old('meta_title', $template->meta_title) }}" 
                                       placeholder="SEO title for pages using this template">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="background_color" class="form-label">Background Color</label>
                                <input type="color" class="form-control form-control-color" 
                                       id="background_color" name="background_color" 
                                       value="{{ old('background_color', $template->background_color ?? '#ffffff') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="2" placeholder="SEO description for pages using this template">{{ old('meta_description', $template->meta_description) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" 
                                       name="is_public" {{ old('is_public', $template->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Make this template available to all users
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Page Content (JSON State)</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="formatJSON()">
                                <i class="bx bx-code me-1"></i>Format
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="validateJSON()">
                                <i class="bx bx-check me-1"></i>Validate
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="state" class="form-label">Page Builder State</label>
                            <textarea class="form-control @error('state') is-invalid @enderror font-monospace" 
                                      id="state" name="state" rows="15" required
                                      style="font-size: 0.9em;">{{ old('state', json_encode($template->state, JSON_PRETTY_PRINT)) }}</textarea>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Modify the JSON state carefully. Use the Format button to clean up formatting,
                                and Validate to check for syntax errors before saving.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteTemplate()">
                            <i class="bx bx-trash me-1"></i>Delete Template
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-secondary me-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-save me-1"></i>Update Template
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Template Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1">{{ $template->usage_count }}</h4>
                                <small class="text-muted">Times Used</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1">{{ $template->created_at->diffForHumans() }}</h4>
                            <small class="text-muted">Created</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-info w-100 mb-2" onclick="previewTemplate()">
                        <i class="bx bx-show me-1"></i>Preview Template
                    </button>
                    
                    <button type="button" class="btn btn-outline-success w-100 mb-2" onclick="showApplyModal()">
                        <i class="bx bx-copy me-1"></i>Apply to Page
                    </button>
                    
                    <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="duplicateTemplate()">
                        <i class="bx bx-duplicate me-1"></i>Duplicate Template
                    </button>
                    
                    <a href="{{ route('admin.page.websites') }}" class="btn btn-outline-secondary w-100">
                        <i class="bx bx-file me-1"></i>Browse Pages
                    </a>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title">JSON Tools</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="minifyJSON()">
                            <i class="bx bx-compress me-1"></i>Minify JSON
                        </button>
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="expandJSON()">
                            <i class="bx bx-expand me-1"></i>Expand JSON
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="copyJSON()">
                            <i class="bx bx-copy me-1"></i>Copy JSON
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Template Modal -->
<div class="modal fade" id="applyTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select a page to apply this template to:</p>
                <select class="form-select" id="pageSelect">
                    <option value="">Select a page...</option>
                    @foreach(\App\Models\Page::all() as $page)
                        <option value="{{ $page->id }}">{{ $page->name ?: 'Page #' . $page->id }}</option>
                    @endforeach
                </select>
                <div class="alert alert-warning mt-3">
                    <strong>Warning:</strong> This will replace the current content of the selected page.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="applyTemplate()">Apply Template</button>
            </div>
        </div>
    </div>
</div>

<script>
function validateJSON() {
    const stateTextarea = document.getElementById('state');
    const jsonString = stateTextarea.value.trim();
    
    if (!jsonString) {
        alert('Please enter JSON content to validate');
        return;
    }
    
    try {
        JSON.parse(jsonString);
        alert('✅ Valid JSON format!');
        stateTextarea.classList.remove('is-invalid');
        stateTextarea.classList.add('is-valid');
    } catch (error) {
        alert('❌ Invalid JSON format: ' + error.message);
        stateTextarea.classList.remove('is-valid');
        stateTextarea.classList.add('is-invalid');
    }
}

function formatJSON() {
    const stateTextarea = document.getElementById('state');
    try {
        const parsed = JSON.parse(stateTextarea.value);
        stateTextarea.value = JSON.stringify(parsed, null, 2);
        stateTextarea.classList.remove('is-invalid');
        stateTextarea.classList.add('is-valid');
    } catch (error) {
        alert('Cannot format invalid JSON. Please fix syntax errors first.');
    }
}

function minifyJSON() {
    const stateTextarea = document.getElementById('state');
    try {
        const parsed = JSON.parse(stateTextarea.value);
        stateTextarea.value = JSON.stringify(parsed);
    } catch (error) {
        alert('Cannot minify invalid JSON. Please fix syntax errors first.');
    }
}

function expandJSON() {
    formatJSON(); // Same as format
}

function copyJSON() {
    const stateTextarea = document.getElementById('state');
    stateTextarea.select();
    document.execCommand('copy');
    alert('JSON copied to clipboard!');
}

function showApplyModal() {
    const modal = new bootstrap.Modal(document.getElementById('applyTemplateModal'));
    modal.show();
}

function applyTemplate() {
    const pageId = document.getElementById('pageSelect').value;
    if (!pageId) {
        alert('Please select a page');
        return;
    }

    fetch(`/admins/templates/apply-to-page/{{ $template->id }}/${pageId}`, {
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
            if (data.redirect) {
                window.open(data.redirect, '_blank');
            }
        } else {
            alert('Error applying template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error applying template');
    });
}

function previewTemplate() {
    window.open('{{ route("admin.templates.preview", $template) }}', '_blank');
}

function duplicateTemplate() {
    const newName = prompt('Enter name for the duplicate template:', '{{ $template->name }} Copy');
    if (newName) {
        // Create form to duplicate
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.templates.store") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add form fields
        const fields = {
            'name': newName,
            'description': document.getElementById('description').value + ' (Copy)',
            'state': document.getElementById('state').value,
            'category': document.getElementById('category').value,
            'meta_title': document.getElementById('meta_title').value,
            'meta_description': document.getElementById('meta_description').value,
            'background_color': document.getElementById('background_color').value
        };
        
        Object.keys(fields).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        });
        
        if (document.getElementById('is_public').checked) {
            const publicInput = document.createElement('input');
            publicInput.type = 'hidden';
            publicInput.name = 'is_public';
            publicInput.value = '1';
            form.appendChild(publicInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteTemplate() {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        fetch('{{ route("admin.templates.destroy", $template) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("admin.templates.index") }}';
            } else {
                alert('Error deleting template');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting template');
        });
    }
}

// Auto-format JSON on blur
document.getElementById('state').addEventListener('blur', function() {
    try {
        const parsed = JSON.parse(this.value);
        this.value = JSON.stringify(parsed, null, 2);
        this.classList.remove('is-invalid');
    } catch (error) {
        // Keep original value if invalid
    }
});
</script>
@endsection