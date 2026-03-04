{{-- filepath: resources/views/admin/ticket/index.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h4>Sponsor</h4>
                    <a href="{{ route('admin.sponsor.create') }}" class="btn btn-primary">Add Sponsor</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Website</th>
                            <th>Name</th>
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
                                <td>
                                    @if ($item->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                <td>
                                    <a href="{{ route('admin.sponsor.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="{{ route('admin.sponsor.delete', $item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete this sponsor?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No Sponsor found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
