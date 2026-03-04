@extends('admin.main')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ $template->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back
            </a>
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i>Edit
            </a>
            <button type="button" class="btn btn-success" onclick="showApplyModal()">
                <i class="bx bx-copy me-1"></i>Apply to Page
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Template Info -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Template Information</h5>
                    <span class="badge bg-label-primary">{{ ucfirst($template->category ?? 'General') }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Name:</strong></div>
                        <div class="col-sm-9">{{ $template->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Description:</strong></div>
                        <div class="col-sm-9">{{ $template->description ?: 'No description provided.' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Category:</strong></div>
                        <div class="col-sm-9">{{ ucfirst($template->category ?? 'General') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Usage Count:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge bg-info">{{ $template->usage_count }} times used</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Created:</strong></div>
                        <div class="col-sm-9">{{ $template->created_at->format('F d, Y \a\t g:i A') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Public:</strong></div>
                        <div class="col-sm-9">
                            @if($template->is_public)
                                <span class="badge bg-success">Public</span>
                            @else
                                <span class="badge bg-warning">Private</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($template->meta_title)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Meta Title:</strong></div>
                        <div class="col-sm-9">{{ $template->meta_title }}</div>
                    </div>
                    @endif
                    
                    @if($template->meta_description)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Meta Description:</strong></div>
                        <div class="col-sm-9">{{ $template->meta_description }}</div>
                    </div>
                    @endif
                    
                    @if($template->background_color)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Background Color:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge" style="background-color: {{ $template->background_color }}; color: white;">
                                {{ $template->background_color }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Template Content -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Template Content (JSON State)</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
                        <i class="bx bx-copy me-1"></i>Copy JSON
                    </button>
                </div>
                <div class="card-body">
                    <pre id="jsonContent" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"><code>{{ json_encode($template->state, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">Quick Actions</h6>
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
                    
                    <button type="button" class="btn btn-outline-danger w-100" onclick="deleteTemplate()">
                        <i class="bx bx-trash me-1"></i>Delete Template
                    </button>
                </div>
            </div>
            
            <!-- Related Templates -->
            @if($relatedTemplates = \App\Models\PageTemplate::where('category', $template->category)->where('id', '!=', $template->id)->limit(3)->get())
                @if($relatedTemplates->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Related Templates</h6>
                    </div>
                    <div class="card-body">
                        @foreach($relatedTemplates as $related)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div>
                                    <strong>{{ $related->name }}</strong><br>
                                    <small class="text-muted">{{ $related->usage_count }} uses</small>
                                </div>
                                <a href="{{ route('admin.templates.show', $related) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif
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

function copyToClipboard() {
    const jsonContent = document.getElementById('jsonContent').textContent;
    navigator.clipboard.writeText(jsonContent).then(function() {
        alert('JSON copied to clipboard!');
    }).catch(function(err) {
        console.error('Error copying to clipboard:', err);
        alert('Failed to copy to clipboard');
    });
}

function duplicateTemplate() {
    const newName = prompt('Enter name for the duplicate template:', '{{ $template->name }} Copy');
    if (newName) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.templates.store") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'name';
        nameInput.value = newName;
        form.appendChild(nameInput);
        
        const descInput = document.createElement('input');
        descInput.type = 'hidden';
        descInput.name = 'description';
        descInput.value = 'Copy of {{ $template->description }}';
        form.appendChild(descInput);
        
        const stateInput = document.createElement('input');
        stateInput.type = 'hidden';
        stateInput.name = 'state';
        stateInput.value = JSON.stringify({!! json_encode($template->state) !!});
        form.appendChild(stateInput);
        
        const categoryInput = document.createElement('input');
        categoryInput.type = 'hidden';
        categoryInput.name = 'category';
        categoryInput.value = '{{ $template->category }}';
        form.appendChild(categoryInput);
        
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
</script>
@endsection