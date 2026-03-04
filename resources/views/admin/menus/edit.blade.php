@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">

<style>
    .dd { position: relative; display: block; margin: 0; padding: 0; max-width: 100%; list-style: none; font-size: 14px; line-height: 20px; }
    .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
    .dd-list .dd-list { padding-left: 30px; }
    .dd-collapsed .dd-list { display: none; }
    .dd-item, .dd-empty, .dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 14px; line-height: 20px; }
    .dd-handle { display: block; height: auto; margin: 5px 0; padding: 10px 15px; color: #333; text-decoration: none; font-weight: normal; border: 1px solid #ccc; background: #fafafa; border-radius: 3px; box-sizing: border-box; cursor: move; }
    .dd-handle:hover { color: #2ea8e5; background: #fff; }
    .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 10px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
    .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
    .dd-item > button[data-action="collapse"]:before { content: '-'; }
    .dd-placeholder, .dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; }
    .dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5; background-size: 60px 60px; background-position: 0 0, 30px 30px; }
    .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
    .dd-dragel > .dd-item .dd-handle { margin-top: 0; }
    .dd-dragel .dd-handle { box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1); }
    .dd3-content { display: block; height: auto; margin: 5px 0; padding: 10px 15px 10px 45px; color: #333; text-decoration: none; font-weight: normal; border: 1px solid #ccc; background: #fafafa; border-radius: 3px; box-sizing: border-box; }
    .dd3-content:hover { color: #2ea8e5; background: #fff; }
    .dd-dragel > .dd3-item > .dd3-content { margin: 0; }
    .dd3-item > button { margin-left: 35px; }
    .dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 35px; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 1px solid #aaa; background: #ddd; border-top-right-radius: 0; border-bottom-right-radius: 0; height: 100%; }
    .dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 8px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
    .dd3-handle:hover { background: #ccc; }
    
    .menu-item-actions { float: right; }
    .menu-item-actions .btn { padding: 2px 8px; font-size: 12px; margin-left: 5px; }
    .menu-item-info { display: block; }
    .menu-item-title { font-weight: bold; color: #333; }
    .menu-item-url { font-size: 12px; color: #666; margin-left: 10px; }
    .add-menu-item-card { border: 2px dashed #ddd; background: #f9f9f9; }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-edit icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">Edit Menu: {{ $menu->name }}</span>
                                    <div class="page-title-subheading">Build and organize your menu structure</div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                <a href="{{ route('admin.menus.list', $website->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Menus
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

                    <div class="row">
                        <!-- Menu Settings -->
                        <div class="col-lg-4">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    <i class="header-icon fas fa-cog me-2"></i>Menu Settings
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.menus.update', [$website->id, $menu->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Menu Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $menu->name }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="location" class="form-label">Menu Location</label>
                                            <select class="form-select" id="location" name="location" required>
                                                <option value="primary" {{ $menu->location == 'primary' ? 'selected' : '' }}>Primary Navigation</option>
                                                <option value="footer" {{ $menu->location == 'footer' ? 'selected' : '' }}>Footer Menu</option>
                                                <option value="mobile" {{ $menu->location == 'mobile' ? 'selected' : '' }}>Mobile Menu</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $menu->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">Active</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save me-2"></i>Update Settings
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Add Menu Item -->
                            <div class="main-card mb-3 card add-menu-item-card">
                                <div class="card-header bg-primary text-white">
                                    <i class="header-icon fas fa-plus me-2"></i>Add Menu Item
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Select Page</label>
                                        <select class="form-select" id="page-select">
                                            <option value="">-- Select a Page --</option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}" data-url="/page/{{ str_replace(' ', '-', strtolower($page->name)) }}">
                                                    {{ $page->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Or create a custom link below</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="custom-title" class="form-label">Custom Title</label>
                                        <input type="text" class="form-control" id="custom-title" placeholder="e.g., About Us">
                                    </div>

                                    <div class="mb-3">
                                        <label for="custom-url" class="form-label">Custom URL</label>
                                        <input type="text" class="form-control" id="custom-url" placeholder="e.g., https://example.com">
                                    </div>

                                    <div class="mb-3">
                                        <label for="target" class="form-label">Open In</label>
                                        <select class="form-select" id="target">
                                            <option value="_self">Same Window</option>
                                            <option value="_blank">New Window</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="css-classes" class="form-label">CSS Classes</label>
                                        <input type="text" class="form-control" id="css-classes" placeholder="e.g., btn-primary highlight">
                                        <small class="form-text text-muted">Optional styling classes</small>
                                    </div>

                                    <button type="button" class="btn btn-success w-100" id="add-menu-item">
                                        <i class="fas fa-plus me-2"></i>Add to Menu
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Structure -->
                        <div class="col-lg-8">
                            <div class="main-card mb-3 card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span><i class="header-icon fas fa-sitemap me-2"></i>Menu Structure</span>
                                    <button type="button" class="btn btn-sm btn-primary" id="save-menu-structure">
                                        <i class="fas fa-save me-2"></i>Save Menu Structure
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Tips:</strong> Drag items to reorder. Drag items to the right to create dropdown menus. Click the pencil icon to edit, trash icon to delete.
                                    </div>

                                    <div class="dd" id="menu-nestable">
                                        <ol class="dd-list">
                                            @foreach($menu->items as $item)
                                                @include('admin.menus.partials.menu-item', ['item' => $item])
                                            @endforeach
                                        </ol>
                                    </div>

                                    @if($menu->items->count() == 0)
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-bars fa-3x mb-3"></i>
                                            <p>No menu items yet. Add items using the form on the left.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Nestable
    $('#menu-nestable').nestable({
        maxDepth: 3,
        group: 1
    });

    // Add menu item
    $('#add-menu-item').click(function() {
        var pageId = $('#page-select').val();
        var pageText = $('#page-select option:selected').text();
        var pageUrl = $('#page-select option:selected').data('url');
        var customTitle = $('#custom-title').val();
        var customUrl = $('#custom-url').val();
        var target = $('#target').val();
        var cssClasses = $('#css-classes').val();

        var title, url;

        if (pageId) {
            title = pageText;
            url = pageUrl;
        } else if (customTitle && customUrl) {
            title = customTitle;
            url = customUrl;
            pageId = '';
        } else {
            alert('Please select a page or enter both custom title and URL');
            return;
        }

        var newId = 'new-' + Date.now();
        var newItem = `
            <li class="dd-item dd3-item" data-id="${newId}" data-page-id="${pageId || ''}" data-url="${url}" data-title="${title}" data-target="${target}" data-css-classes="${cssClasses}">
                <div class="dd-handle dd3-handle"></div>
                <div class="dd3-content">
                    <span class="menu-item-info">
                        <span class="menu-item-title">${title}</span>
                        <span class="menu-item-url">${url}</span>
                    </span>
                    <span class="menu-item-actions">
                        <button class="btn btn-sm btn-danger delete-item" type="button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </span>
                </div>
            </li>
        `;

        $('#menu-nestable > .dd-list').append(newItem);
        $('#menu-nestable').nestable('init');

        // Clear form
        $('#page-select').val('');
        $('#custom-title').val('');
        $('#custom-url').val('');
        $('#target').val('_self');
        $('#css-classes').val('');
    });

    // Delete menu item
    $(document).on('click', '.delete-item', function() {
        if (confirm('Are you sure you want to delete this menu item?')) {
            $(this).closest('.dd-item').remove();
        }
    });

    // Save menu structure
    $('#save-menu-structure').click(function() {
        var structure = $('#menu-nestable').nestable('serialize');
        
        $.ajax({
            url: '{{ route("admin.menus.update-items", [$website->id, $menu->id]) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: structure
            },
            success: function(response) {
                alert('Menu structure saved successfully!');
                location.reload();
            },
            error: function(xhr) {
                alert('Error saving menu structure. Please try again.');
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
@endsection
