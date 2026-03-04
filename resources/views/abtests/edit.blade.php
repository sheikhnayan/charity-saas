@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/ab-tests">A/B Tests</a></li>
                <li class="breadcrumb-item active">Edit Test</li>
            </ol>
        </nav>
        <h4 class="fw-bold">
            <i class="bx bx-edit text-primary me-2"></i>Edit A/B Test
        </h4>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Test Configuration</h5>
                </div>
                <div class="card-body">
                    @if($test->status === 'running')
                    <div class="alert alert-warning">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Warning:</strong> This test is currently running. You cannot edit it while it's active. 
                        Please pause or end the test first.
                    </div>
                    @endif

                    <form id="editTestForm" method="POST" action="/ab-tests/{{ $test->id }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Test Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $test->name }}" required {{ $test->status === 'running' ? 'disabled' : '' }}>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" {{ $test->status === 'running' ? 'disabled' : '' }}>{{ $test->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Test Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($test->test_type) }}" disabled>
                                <small class="text-muted">Cannot be changed after creation</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" value="{{ ucfirst($test->status) }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Goal Metric</label>
                                <select class="form-select" name="goal_metric" {{ $test->status === 'running' ? 'disabled' : '' }}>
                                    <option value="conversion_rate" {{ $test->goal_metric === 'conversion_rate' ? 'selected' : '' }}>Conversion Rate</option>
                                    <option value="click_through_rate" {{ $test->goal_metric === 'click_through_rate' ? 'selected' : '' }}>Click-Through Rate</option>
                                    <option value="donation_amount" {{ $test->goal_metric === 'donation_amount' ? 'selected' : '' }}>Donation Amount</option>
                                    <option value="time_on_page" {{ $test->goal_metric === 'time_on_page' ? 'selected' : '' }}>Time on Page</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Goal Value (optional)</label>
                                <input type="number" step="0.01" class="form-control" name="goal_value" value="{{ $test->goal_value }}" {{ $test->status === 'running' ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Min Sample Size</label>
                                <input type="number" class="form-control" value="{{ $test->min_sample_size }}" disabled>
                                <small class="text-muted">Minimum conversions needed per variant</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confidence Level (%)</label>
                                <input type="number" step="0.01" class="form-control" value="{{ $test->confidence_level }}" disabled>
                                <small class="text-muted">Required confidence for significance</small>
                            </div>
                        </div>

                        @if($test->status !== 'running')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Save Changes
                            </button>
                            <a href="/ab-tests" class="btn btn-secondary">Cancel</a>
                        </div>
                        @else
                        <a href="/ab-tests" class="btn btn-secondary">Back to Tests</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Test Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Test Status</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'draft' => 'secondary',
                            'running' => 'primary',
                            'paused' => 'warning',
                            'completed' => 'success',
                        ];
                        $color = $statusColors[$test->status] ?? 'secondary';
                    @endphp
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $color }} fs-6">{{ ucfirst($test->status) }}</span>
                    </div>

                    @if($test->started_at)
                    <div class="mb-2">
                        <small class="text-muted">Started:</small>
                        <div><strong>{{ $test->started_at->format('M d, Y H:i') }}</strong></div>
                    </div>
                    @endif

                    @if($test->ended_at)
                    <div class="mb-2">
                        <small class="text-muted">Ended:</small>
                        <div><strong>{{ $test->ended_at->format('M d, Y H:i') }}</strong></div>
                    </div>
                    @endif

                    @if($test->started_at && !$test->ended_at)
                    <div class="mb-2">
                        <small class="text-muted">Running for:</small>
                        <div><strong>{{ $test->started_at->diffInDays(now()) }} days</strong></div>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex flex-column gap-2">
                        @if($test->status === 'draft')
                        <button class="btn btn-primary btn-sm" onclick="startTest({{ $test->id }})">
                            <i class="bx bx-play me-1"></i>Start Test
                        </button>
                        @elseif($test->status === 'running')
                        <button class="btn btn-warning btn-sm" onclick="pauseTest({{ $test->id }})">
                            <i class="bx bx-pause me-1"></i>Pause Test
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="endTest({{ $test->id }})">
                            <i class="bx bx-stop-circle me-1"></i>End Test
                        </button>
                        @elseif($test->status === 'paused')
                        <button class="btn btn-primary btn-sm" onclick="startTest({{ $test->id }})">
                            <i class="bx bx-play me-1"></i>Resume Test
                        </button>
                        @endif
                        
                        <a href="/ab-tests/{{ $test->id }}/results" class="btn btn-info btn-sm">
                            <i class="bx bx-bar-chart me-1"></i>View Results
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variants Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Variants</h5>
                </div>
                <div class="card-body">
                    @foreach($test->testVariants as $variant)
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $variant->name }}</strong>
                                @if($variant->is_control)
                                <span class="badge bg-label-primary">Control</span>
                                @endif
                                @if($test->winning_variant_id == $variant->id)
                                <span class="badge bg-label-success">Winner</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $variant->traffic_percentage }}%</small>
                        </div>
                        @if($variant->configuration && count((array)$variant->configuration) > 0)
                        <small class="text-muted d-block mt-2">
                            Config: {{ json_encode($variant->configuration) }}
                        </small>
                        @endif
                    </div>
                    @endforeach

                    <small class="text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        Variants cannot be edited after test creation
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('editTestForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch(this.action, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            window.location.href = '/ab-tests';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update test');
    });
});

function startTest(testId) {
    if (!confirm('Start this test?')) return;
    fetch(`/ab-tests/${testId}/start`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload();
    });
}

function pauseTest(testId) {
    if (!confirm('Pause this test?')) return;
    fetch(`/ab-tests/${testId}/pause`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload();
    });
}

function endTest(testId) {
    if (!confirm('End this test? This cannot be undone.')) return;
    fetch(`/ab-tests/${testId}/end`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload();
    });
}
</script>
@endsection
