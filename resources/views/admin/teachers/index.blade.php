@extends('admin.main')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teachers Management</h5>
                        @if(isset($website))
                            <small>Website: {{ $website->name }} ({{ $website->domain }})</small>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('admin.teachers.websites') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i>Back to Websites
                        </a>
                        @if(isset($website))
                            <a href="{{ route('admin.teachers.create', $website->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>Add New Teacher
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($teachers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Participants</th>
                                        @if(!isset($website))
                                            <th>Website</th>
                                        @endif
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $teacher)
                                    <tr>
                                        <td>
                                            @if($teacher->photo)
                                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ Str::limit($teacher->description, 50) }}</td>
                                        <td>
                                            @if($teacher->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $teacher->students_count ?? 0 }}</span>
                                        </td>
                                        @if(!isset($website))
                                            <td>{{ $teacher->website->name ?? 'N/A' }}</td>
                                        @endif
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.teachers.delete', $teacher->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this teacher? This will remove teacher assignments from all students.');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No teachers found. Click "Add New Teacher" to create one.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
