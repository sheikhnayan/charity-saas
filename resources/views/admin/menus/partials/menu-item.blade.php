<li class="dd-item dd3-item" data-id="{{ $item->id }}" data-page-id="{{ $item->page_id ?? '' }}" data-url="{{ $item->url }}" data-title="{{ $item->title }}" data-target="{{ $item->target }}" data-css-classes="{{ $item->css_classes ?? '' }}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content d-flex align-items-center justify-content-between">
        <span class="menu-item-info">
            <span class="menu-item-title">{{ $item->title }}</span>
            <span class="menu-item-url">
                @if($item->page_id && $item->page)
                    /page/{{ str_replace(' ', '-', strtolower($item->page->name)) }}
                @else
                    {{ $item->url }}
                @endif
            </span>
        </span>
        <span class="menu-item-actions ms-auto">
            <button class="btn btn-sm btn-warning edit-item" type="button" title="Edit">
                <i class="fas fa-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-item" type="button" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </span>
    </div>
    @if($item->children->count() > 0)
        <ol class="dd-list">
            @foreach($item->children as $child)
                @include('admin.menus.partials.menu-item', ['item' => $child])
            @endforeach
        </ol>
    @endif
</li>
