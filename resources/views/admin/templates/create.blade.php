@extends('admin.main')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Create New Template</h2>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i>Back to Templates
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('admin.templates.store') }}" method="POST">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Template Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Template Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category">
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="landing" {{ old('category') == 'landing' ? 'selected' : '' }}>Landing Page</option>
                                    <option value="about" {{ old('category') == 'about' ? 'selected' : '' }}>About</option>
                                    <option value="contact" {{ old('category') == 'contact' ? 'selected' : '' }}>Contact</option>
                                    <option value="services" {{ old('category') == 'services' ? 'selected' : '' }}>Services</option>
                                    <option value="portfolio" {{ old('category') == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="blog" {{ old('category') == 'blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="e-commerce" {{ old('category') == 'e-commerce' ? 'selected' : '' }}>E-commerce</option>
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
                                      placeholder="Describe what this template is for...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                       value="{{ old('meta_title') }}" placeholder="SEO title for pages using this template">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="background_color" class="form-label">Background Color</label>
                                <input type="color" class="form-control form-control-color" 
                                       id="background_color" name="background_color" 
                                       value="{{ old('background_color', '#ffffff') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="2" placeholder="SEO description for pages using this template">{{ old('meta_description') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" 
                                       name="is_public" {{ old('is_public', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Make this template available to all users
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Page Content (JSON State)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="state" class="form-label">Page Builder State</label>
                            <textarea class="form-control @error('state') is-invalid @enderror" 
                                      id="state" name="state" rows="10" required
                                      placeholder='{"sections": [], "settings": {}}'>{{ old('state') }}</textarea>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Paste the JSON state from a page builder export or create manually. 
                                You can also save an existing page as a template from the page editor.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" onclick="validateJSON()">
                        <i class="bx bx-check me-1"></i>Validate JSON
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-save me-1"></i>Create Template
                    </button>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Template Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            <strong>Name:</strong> Use descriptive names like "Modern Landing Page" or "Contact Form Layout"
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            <strong>Category:</strong> Choose the most appropriate category for easy discovery
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            <strong>Description:</strong> Explain what the template includes and when to use it
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            <strong>JSON State:</strong> Must be valid JSON from the page builder
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.page.websites') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bx bx-file me-1"></i>Browse Existing Pages
                    </a>
                    <button type="button" class="btn btn-outline-info w-100" onclick="loadSampleJSON()">
                        <i class="bx bx-code-block me-1"></i>Load Sample JSON
                    </button>
                </div>
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

function loadSampleJSON() {
    const sampleJSON = {
        "sections": [
            {
                "type": "hero",
                "id": "hero-1",
                "settings": {
                    "title": "Welcome to Our Website",
                    "subtitle": "This is a sample template",
                    "background_color": "#f8f9fa"
                }
            },
            {
                "type": "content",
                "id": "content-1",
                "settings": {
                    "text": "This is sample content for the template.",
                    "alignment": "center"
                }
            }
        ],
        "settings": {
            "layout": "full-width",
            "theme": "default"
        }
    };
    
    document.getElementById('state').value = JSON.stringify(sampleJSON, null, 2);
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