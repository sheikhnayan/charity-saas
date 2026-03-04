@extends('admin.main')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Page Templates</h2>
        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>Create Template
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter by Category -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Filter Templates</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter" onchange="filterByCategory()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row">
        @forelse($templates as $template)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">{{ $template->name }}</h6>
                        <span class="badge bg-label-primary">{{ ucfirst($template->category ?? 'General') }}</span>
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text text-muted">
                            {{ Str::limit($template->description, 100) ?: 'No description available.' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="bx bx-download me-1"></i>Used {{ $template->usage_count }} times
                            </small>
                            <small class="text-muted">
                                {{ $template->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.templates.show', $template) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="bx bx-show me-1"></i>View
                            </a>
                            <a href="{{ route('admin.templates.edit', $template) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-edit me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-outline-success btn-sm" 
                                    onclick="showApplyModal({{ $template->id }}, '{{ $template->name }}')">
                                <i class="bx bx-copy me-1"></i>Apply
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="deleteTemplate({{ $template->id }})">
                                <i class="bx bx-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bx bx-file-blank display-1 text-muted"></i>
                        <h4 class="mt-3">No Templates Found</h4>
                        <p class="text-muted">Create your first template or select a different category.</p>
                        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>Create Template
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
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
                <p>Select a page to apply the template "<span id="templateName"></span>" to:</p>
                <select class="form-select" id="pageSelect">
                    <option value="">Select a page...</option>
                    @foreach(\App\Models\Page::all() as $page)
                        <option value="{{ $page->id }}">{{ $page->name ?: 'Page #' . $page->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="applyTemplate()">Apply Template</button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedTemplateId = null;

function filterByCategory() {
    const category = document.getElementById('categoryFilter').value;
    const url = new URL(window.location);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    window.location = url;
}

function showApplyModal(templateId, templateName) {
    selectedTemplateId = templateId;
    document.getElementById('templateName').textContent = templateName;
    const modal = new bootstrap.Modal(document.getElementById('applyTemplateModal'));
    modal.show();
}

function applyTemplate() {
    const pageId = document.getElementById('pageSelect').value;
    if (!pageId || !selectedTemplateId) {
        alert('Please select a page');
        return;
    }

    fetch(`/admins/templates/apply-to-page/${selectedTemplateId}/${pageId}`, {
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

function deleteTemplate(templateId) {
    if (confirm('Are you sure you want to delete this template?')) {
        fetch(`/admins/templates/destroy/${templateId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
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