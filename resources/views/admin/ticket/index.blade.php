{{-- filepath: resources/views/admin/ticket/index.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h4>Tickets @if(isset($website)) - {{ $website->name }} @endif</h4>
                        @if(isset($website))
                            <p class="text-muted mb-0">{{ $website->domain }}</p>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('admin.ticket.websites') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left"></i> Back to Websites
                        </a>
                        <a href="{{ route('admin.ticket-category.index') }}" class="btn btn-outline-primary me-2">Manage Categories</a>
                        <a href="{{ route('admin.ticket.create') }}" class="btn btn-primary">Add Ticket</a>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Website</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->website->name }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category ? $item->category->name : 'No Category' }}</td>
                                <td>
                                    @if ($item->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.ticket.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="{{ route('admin.ticket.delete', $item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete this ticket?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
