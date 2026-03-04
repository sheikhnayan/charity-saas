@extends('admin.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Newsletter Management</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($websites->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <h5>No websites found</h5>
                            <p>You need to create a website first before managing newsletter subscriptions.</p>
                            <a href="{{ route('admin.website.create') }}" class="btn btn-primary">Create Website</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($websites as $website)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $website->name }}</h5>
                                            <p class="card-text">
                                                <small class="text-muted">{{ $website->domain }}</small><br>
                                                <span class="badge bg-{{ $website->type === 'investment' ? 'success' : 'primary' }}">
                                                    {{ ucfirst($website->type) }}
                                                </span>
                                            </p>
                                            
                                            <div class="row text-center mb-3">
                                                <div class="col">
                                                    <h4 class="text-primary">{{ $website->activeNewsletterSubscriptions->count() }}</h4>
                                                    <small class="text-muted">Active Subscribers</small>
                                                </div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <a href="{{ route('admin.newsletter.manage', $website->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-cog"></i> Manage Subscriptions
                                                </a>
                                                
                                                @if($website->activeNewsletterSubscriptions->count() > 0)
                                                    <button type="button" 
                                                            class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#sendEmailModal"
                                                            data-website-id="{{ $website->id }}"
                                                            data-website-name="{{ $website->name }}"
                                                            data-subscriber-count="{{ $website->activeNewsletterSubscriptions->count() }}">
                                                        <i class="fas fa-envelope"></i> Send Newsletter
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Send Newsletter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.newsletter.send') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="website_id" id="modal-website-id">
                    
                    <div class="alert alert-info" role="alert">
                        <strong>Website:</strong> <span id="modal-website-name"></span><br>
                        <strong>Recipients:</strong> <span id="modal-subscriber-count"></span> active subscribers
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject" id="subject" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="recipient_type" class="form-label">Send to <span class="text-danger">*</span></label>
                        <select class="form-control" name="recipient_type" id="recipient_type" required>
                            <option value="active">Active subscribers only</option>
                            <option value="all">All subscribers (including inactive)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" id="message" rows="10" required 
                                  placeholder="Write your newsletter content here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Newsletter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sendEmailModal = document.getElementById('sendEmailModal');
    if (sendEmailModal) {
        sendEmailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const websiteId = button.getAttribute('data-website-id');
            const websiteName = button.getAttribute('data-website-name');
            const subscriberCount = button.getAttribute('data-subscriber-count');
            
            document.getElementById('modal-website-id').value = websiteId;
            document.getElementById('modal-website-name').textContent = websiteName;
            document.getElementById('modal-subscriber-count').textContent = subscriberCount;
        });
    }
});
</script>
@endsection