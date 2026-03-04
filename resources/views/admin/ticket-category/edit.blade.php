{{-- filepath: resources/views/admin/ticket-category/edit.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Edit Ticket Category</h4>
                    <a href="{{ route('admin.ticket-category.index') }}" class="btn btn-secondary">Back to Categories</a>
                </div>

                <form action="{{ route('admin.ticket-category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="website_id" class="form-label">Website</label>
                        <select name="website_id" id="website_id" class="form-select @error('website_id') is-invalid @enderror" required>
                            <option value="">Select Website</option>
                            @foreach ($websites as $website)
                                <option value="{{ $website->id }}" 
                                        {{ (old('website_id', $category->website_id) == $website->id) ? 'selected' : '' }}>
                                    {{ $website->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('website_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  id="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon</label>
                        <div class="input-group">
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon', $category->icon) }}" readonly placeholder="Select an icon">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                                <i class="fas fa-icons"></i> Choose Icon
                            </button>
                        </div>
                        <div id="icon-preview" class="mt-2" style="font-size: 2rem;"></div>
                        @error('icon')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                               id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Lower numbers appear first</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="alert alert-info">
                            <strong>Tickets in this category:</strong> {{ $category->tickets()->count() }}
                            @if($category->tickets()->count() > 0)
                                <br><small>Note: Changing the website will affect all tickets in this category.</small>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="{{ route('admin.ticket-category.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select an Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="iconSearch" class="form-control mb-3" placeholder="Search icons...">
                <div id="iconGrid" class="row g-2" style="max-height: 400px; overflow-y: auto;">
                    @php
                    $icons = [
                        'fas fa-ticket-alt', 'fas fa-calendar-alt', 'fas fa-music', 'fas fa-film', 'fas fa-theater-masks',
                        'fas fa-microphone', 'fas fa-guitar', 'fas fa-drum', 'fas fa-trophy', 'fas fa-medal',
                        'fas fa-star', 'fas fa-gift', 'fas fa-birthday-cake', 'fas fa-glass-cheers', 'fas fa-wine-glass',
                        'fas fa-utensils', 'fas fa-pizza-slice', 'fas fa-hamburger', 'fas fa-coffee', 'fas fa-cocktail',
                        'fas fa-beer', 'fas fa-heart', 'fas fa-graduation-cap', 'fas fa-book', 'fas fa-palette',
                        'fas fa-paint-brush', 'fas fa-camera', 'fas fa-video', 'fas fa-gamepad', 'fas fa-basketball-ball',
                        'fas fa-football-ball', 'fas fa-baseball-ball', 'fas fa-volleyball-ball', 'fas fa-running',
                        'fas fa-swimmer', 'fas fa-bicycle', 'fas fa-mountain', 'fas fa-tree', 'fas fa-umbrella-beach',
                        'fas fa-plane', 'fas fa-train', 'fas fa-bus', 'fas fa-car', 'fas fa-ship',
                        'fas fa-rocket', 'fas fa-hotel', 'fas fa-building', 'fas fa-home', 'fas fa-shopping-bag',
                        'fas fa-shopping-cart', 'fas fa-tag', 'fas fa-tags', 'fas fa-percent', 'fas fa-dollar-sign',
                        'fas fa-chart-line', 'fas fa-crown', 'fas fa-gem', 'fas fa-fire', 'fas fa-bolt',
                        'fas fa-moon', 'fas fa-sun', 'fas fa-cloud', 'fas fa-snowflake', 'fas fa-leaf',
                        'fas fa-seedling', 'fas fa-paw', 'fas fa-dog', 'fas fa-cat', 'fas fa-horse',
                        'fas fa-fish', 'fas fa-dove', 'fas fa-frog', 'fas fa-spider', 'fas fa-users',
                        'fas fa-user-friends', 'fas fa-user-tie', 'fas fa-child', 'fas fa-baby', 'fas fa-hands-helping',
                        'fas fa-handshake', 'fas fa-praying-hands', 'fas fa-hospital', 'fas fa-ambulance', 'fas fa-pills',
                        'fas fa-briefcase', 'fas fa-laptop', 'fas fa-mobile-alt', 'fas fa-tv', 'fas fa-headphones',
                        'fas fa-lightbulb', 'fas fa-cog', 'fas fa-tools', 'fas fa-hammer', 'fas fa-wrench',
                        'fas fa-bell', 'fas fa-flag', 'fas fa-map-marker-alt', 'fas fa-compass', 'fas fa-globe',
                        'fas fa-lock', 'fas fa-key', 'fas fa-envelope', 'fas fa-comment', 'fas fa-comments'
                    ];
                    @endphp
                    @foreach($icons as $icon)
                        <div class="col-2 text-center">
                            <button type="button" class="btn btn-outline-secondary icon-option w-100 p-3" data-icon="{{ $icon }}">
                                <i class="{{ $icon }} fa-2x"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon picker functionality
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconSearch = document.getElementById('iconSearch');
    const iconOptions = document.querySelectorAll('.icon-option');
    
    // Display current icon if set
    if (iconInput.value) {
        iconPreview.innerHTML = `<i class="${iconInput.value}"></i>`;
    }
    
    // Icon selection
    iconOptions.forEach(option => {
        option.addEventListener('click', function() {
            const iconClass = this.dataset.icon;
            iconInput.value = iconClass;
            iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
            modal.hide();
        });
    });
    
    // Icon search
    iconSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        iconOptions.forEach(option => {
            const iconClass = option.dataset.icon.toLowerCase();
            const parent = option.closest('.col-2');
            if (iconClass.includes(searchTerm)) {
                parent.style.display = 'block';
            } else {
                parent.style.display = 'none';
            }
        });
    });
});
</script>
@endsection