@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Comment Management</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Comments Table -->
    <div class="card">
        <h5 class="card-header">All Comments</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Website</th>
                        <th>Page</th>
                        <th>Author</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($comments as $comment)
                        <tr>
                            <td>
                                @if($comment->website)
                                    <strong>{{ $comment->website->name ?? $comment->website->domain }}</strong>
                                    <br><small class="text-muted">{{ $comment->website->domain }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $comment->page_identifier }}</td>
                            <td>
                                <span class="fw-bold">{{ $comment->author_display_name }}</span>
                                @if($comment->author_email)
                                    <br><small class="text-muted">{{ $comment->author_email }}</small>
                                @endif
                            </td>
                            <td>
                                <div style="max-width: 300px; overflow: hidden;">
                                    {{ Str::limit($comment->comment, 100) }}
                                </div>
                            </td>
                            <td>{{ $comment->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($comment->is_approved)
                                    <span class="badge bg-label-success me-1">Approved</span>
                                @else
                                    <span class="badge bg-label-warning me-1">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewModal{{ $comment->id }}">
                                            <i class="bx bx-show me-1"></i> View Full
                                        </button>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#replyModal{{ $comment->id }}">
                                            <i class="bx bx-reply me-1"></i> Reply
                                        </button>
                                        <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this comment and all its replies?')">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- View Comment Modal -->
                        <div class="modal fade" id="viewModal{{ $comment->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Comment Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Website:</strong></div>
                                            <div class="col-sm-9">
                                                @if($comment->website)
                                                    <strong>{{ $comment->website->name ?? $comment->website->domain }}</strong>
                                                    <br><small class="text-muted">{{ $comment->website->domain }}</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Page:</strong></div>
                                            <div class="col-sm-9">{{ $comment->page_identifier }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Author:</strong></div>
                                            <div class="col-sm-9">{{ $comment->author_display_name }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Email:</strong></div>
                                            <div class="col-sm-9">{{ $comment->author_email ?: 'Not provided' }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Component:</strong></div>
                                            <div class="col-sm-9">{{ $comment->component_id }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Date:</strong></div>
                                            <div class="col-sm-9">{{ $comment->created_at->format('F d, Y \a\t H:i') }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Comment:</strong></div>
                                            <div class="col-sm-9">
                                                <div class="p-3 bg-light rounded">
                                                    {{ $comment->comment }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($comment->replies->count() > 0)
                                            <hr>
                                            <h6>Replies ({{ $comment->replies->count() }})</h6>
                                            @foreach($comment->replies as $reply)
                                                <div class="border-start border-3 ps-3 mb-3">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ $reply->author_display_name }}</strong>
                                                        @if($reply->is_admin_reply ?? false)
                                                            <span class="badge bg-label-primary">Admin</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                                                    <div class="mt-2">{{ $reply->comment }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Modal -->
                        <div class="modal fade" id="replyModal{{ $comment->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reply to Comment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Original Comment:</label>
                                                <div class="p-3 bg-light rounded">
                                                    <strong>{{ $comment->author_display_name }}:</strong><br>
                                                    {{ Str::limit($comment->comment, 200) }}
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Your Reply (as Site Administrator):</label>
                                                <textarea class="form-control" id="comment" name="comment" rows="4" required placeholder="Enter your reply..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Post Reply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bx bx-comment-x display-4 text-muted"></i>
                                <p class="text-muted mt-2">No comments found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($comments->hasPages())
            <div class="card-footer">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide success alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection