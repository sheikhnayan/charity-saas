@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-bars icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">Menu Builder</span>
                                    <div class="page-title-subheading">Manage your website menus</div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                <a href="{{ route('admin.menus.create', $website->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create New Menu
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            <i class="header-icon fas fa-list me-2"></i>All Menus
                        </div>
                        <div class="card-body">
                            @if($menus->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Items Count</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($menus as $menu)
                                                <tr>
                                                    <td><strong>{{ $menu->name }}</strong></td>
                                                    <td><span class="badge bg-info">{{ ucfirst($menu->location) }}</span></td>
                                                    <td>{{ $menu->allItems->count() }} items</td>
                                                    <td>
                                                        @if($menu->status)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.menus.edit', [$website->id, $menu->id]) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.menus.destroy', [$website->id, $menu->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-bars fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No menus created yet. Click "Create New Menu" to get started.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
