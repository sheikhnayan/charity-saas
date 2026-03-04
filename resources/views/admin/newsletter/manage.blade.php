@extends('admin.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Newsletter Subscriptions</h3>
                        <p class="card-text mb-0">
                            <small class="text-muted">
                                {{ $website->name }} ({{ $website->domain }})
                            </small>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.newsletter') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Newsletter Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['total'] }}</h4>
                                            <p class="mb-0">Total Subscriptions</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['active'] }}</h4>
                                            <p class="mb-0">Active Subscribers</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['inactive'] }}</h4>
                                            <p class="mb-0">Inactive Subscribers</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-times fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            @if($stats['active'] > 0)
                                <button type="button" 
                                        class="btn btn-success"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#sendEmailModal">
                                    <i class="fas fa-envelope"></i> Send Newsletter
                                </button>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            @if($stats['total'] > 0)
                                <a href="{{ route('admin.newsletter.export', $website->id) }}" 
                                   class="btn btn-info">
                                    <i class="fas fa-download"></i> Export CSV
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Subscriptions Table -->
                    @if($subscriptions->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <h5>No newsletter subscriptions yet</h5>
                            <p>Once users subscribe to your newsletter using the newsletter component on your website, they will appear here.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Subscribed Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $subscription)
                                        <tr>
                                            <td>
                                                @if($subscription->first_name || $subscription->last_name)
                                                    {{ trim($subscription->first_name . ' ' . $subscription->last_name) }}
                                                @else
                                                    <em class="text-muted">Not provided</em>
                                                @endif
                                            </td>
                                            <td>{{ $subscription->email }}</td>
                                            <td>
                                                @if($subscription->phone)
                                                    {{ $subscription->country_code ?? '+1' }} {{ $subscription->phone }}
                                                @else
                                                    <em class="text-muted">Not provided</em>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $subscription->subscribed_at ? $subscription->subscribed_at->format('M d, Y H:i') : 'N/A' }}
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $subscription->id }}, '{{ $subscription->email }}')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $subscriptions->links() }}
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
                    <input type="hidden" name="website_id" value="{{ $website->id }}">
                    
                    <div class="alert alert-info" role="alert">
                        <strong>Website:</strong> {{ $website->name }}<br>
                        <strong>Recipients:</strong> {{ $stats['active'] }} active subscribers
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject" id="subject" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="recipient_type" class="form-label">Send to <span class="text-danger">*</span></label>
                        <select class="form-control" name="recipient_type" id="recipient_type" required>
                            <option value="active">Active subscribers only ({{ $stats['active'] }})</option>
                            <option value="all">All subscribers ({{ $stats['total'] }})</option>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the subscription for <strong id="delete-email"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(subscriptionId, email) {
    document.getElementById('delete-email').textContent = email;
    document.getElementById('delete-form').action = `/users/newsletter/subscription/${subscriptionId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection