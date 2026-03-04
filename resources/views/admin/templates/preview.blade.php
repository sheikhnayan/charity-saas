@extends('admin.main')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Preview Template: {{ $template->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back to Template
            </a>
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i>Edit Template
            </a>
        </div>
    </div>

    <!-- Template Info Banner -->
    <div class="alert alert-info">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="alert-heading mb-1">{{ $template->name }}</h6>
                <p class="mb-0">{{ $template->description ?: 'No description provided' }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary">{{ ucfirst($template->category ?? 'General') }}</span>
                <br>
                <small class="text-muted">Used {{ $template->usage_count }} times</small>
            </div>
        </div>
    </div>

    <!-- Template Preview -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Template Preview</h5>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary" onclick="toggleRawView()">
                    <i class="bx bx-code me-1"></i>View JSON
                </button>
                <button type="button" class="btn btn-outline-info" onclick="refreshPreview()">
                    <i class="bx bx-refresh me-1"></i>Refresh
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Visual Preview -->
            <div id="visualPreview" class="p-4" style="min-height: 400px; background: {{ $template->background_color ?? '#ffffff' }};">
                <div class="text-center">
                    <h3>Template Preview</h3>
                    <p class="text-muted">This is a visual representation of the template structure.</p>
                    
                    @if($template->state && isset($template->state['sections']))
                        @foreach($template->state['sections'] as $section)
                            <div class="border rounded p-3 mb-3" style="background: rgba(0,123,255,0.1);">
                                <h5 class="text-primary">
                                    <i class="bx bx-cube me-1"></i>
                                    {{ ucfirst($section['type'] ?? 'Section') }}
                                    @if(isset($section['id']))
                                        <small class="text-muted">({{ $section['id'] }})</small>
                                    @endif
                                </h5>
                                
                                @if(isset($section['settings']))
                                    <div class="row">
                                        @foreach($section['settings'] as $key => $value)
                                            @if(is_string($value) && strlen($value) < 100)
                                                <div class="col-md-6 mb-2">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    <span class="text-muted">{{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning">
                            <i class="bx bx-info-circle me-2"></i>
                            No sections found in template state.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Raw JSON View -->
            <div id="rawView" class="d-none">
                <pre class="p-4 m-0 bg-light" style="max-height: 600px; overflow-y: auto;"><code>{{ json_encode($template->state, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </div>
    </div>

    <!-- Template Metadata -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Template Metadata</h6>
                </div>
                <div class="card-body">
                    @if($template->meta_title)
                        <div class="mb-2">
                            <strong>Meta Title:</strong> {{ $template->meta_title }}
                        </div>
                    @endif
                    
                    @if($template->meta_description)
                        <div class="mb-2">
                            <strong>Meta Description:</strong> {{ $template->meta_description }}
                        </div>
                    @endif
                    
                    @if($template->background_color)
                        <div class="mb-2">
                            <strong>Background Color:</strong> 
                            <span class="badge" style="background-color: {{ $template->background_color }}; color: white;">
                                {{ $template->background_color }}
                            </span>
                        </div>
                    @endif
                    
                    <div class="mb-2">
                        <strong>Public:</strong> 
                        @if($template->is_public)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-warning">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Template Actions</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success w-100 mb-2" onclick="showApplyModal()">
                        <i class="bx bx-copy me-1"></i>Apply to Page
                    </button>
                    
                    <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bx bx-edit me-1"></i>Edit Template
                    </a>
                    
                    <button type="button" class="btn btn-info w-100 mb-2" onclick="duplicateTemplate()">
                        <i class="bx bx-duplicate me-1"></i>Duplicate Template
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="exportTemplate()">
                        <i class="bx bx-download me-1"></i>Export JSON
                    </button>
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
                <h5 class="modal-title">Apply Template: {{ $template->name }}</h5>
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
function toggleRawView() {
    const visual = document.getElementById('visualPreview');
    const raw = document.getElementById('rawView');
    const btn = event.target.closest('button');
    
    if (visual.classList.contains('d-none')) {
        // Currently showing raw, switch to visual
        visual.classList.remove('d-none');
        raw.classList.add('d-none');
        btn.innerHTML = '<i class="bx bx-code me-1"></i>View JSON';
    } else {
        // Currently showing visual, switch to raw
        visual.classList.add('d-none');
        raw.classList.remove('d-none');
        btn.innerHTML = '<i class="bx bx-eye me-1"></i>View Preview';
    }
}

function refreshPreview() {
    location.reload();
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

    fetch(`{{ route('admin.templates.apply-to-page', ['template' => $template->id, 'page' => '__PAGE_ID__']) }}`.replace('__PAGE_ID__', pageId), {
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

function duplicateTemplate() {
    const newName = prompt('Enter name for the duplicate template:', '{{ $template->name }} Copy');
    if (newName) {
        window.location.href = `{{ route('admin.templates.create') }}?duplicate={{ $template->id }}&name=${encodeURIComponent(newName)}`;
    }
}

function exportTemplate() {
    const templateData = {
        name: '{{ $template->name }}',
        description: '{{ $template->description }}',
        category: '{{ $template->category }}',
        state: {!! json_encode($template->state) !!},
        meta_title: '{{ $template->meta_title }}',
        meta_description: '{{ $template->meta_description }}',
        background_color: '{{ $template->background_color }}'
    };
    
    const dataStr = JSON.stringify(templateData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = '{{ Str::slug($template->name) }}-template.json';
    link.click();
}
</script>
@endsection