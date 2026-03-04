@php
    // Get properties with defaults
    $title = $component['properties']['title'] ?? 'Available Properties';
    $categoryId = $component['properties']['category_id'] ?? '';
    $cardBgColor = $component['properties']['card_background_color'] ?? '#ffffff';
    $priceTextColor = $component['properties']['price_text_color'] ?? '#2e7d3e';
    $descriptionTextColor = $component['properties']['description_text_color'] ?? '#666666';
    $buttonBgColor = $component['properties']['button_background_color'] ?? '#007bff';
    $buttonTextColor = $component['properties']['button_text_color'] ?? '#ffffff';
    $slidesToShow = $component['properties']['slides_to_show'] ?? 3;
    $autoplay = $component['properties']['autoplay'] ?? false;
    $autoplaySpeed = $component['properties']['autoplay_speed'] ?? 3000;
    $loop = $component['properties']['loop'] ?? true;
    
    // Get categories for current website (for dropdown)
    $currentWebsiteId = $currentWebsiteId ?? session('current_website_id', 1); // Use passed variable or fallback
    $categories = \App\Models\TicketCategory::where('website_id', $currentWebsiteId)
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
@endphp

<div class="component-preview">
    <div class="property-category-carousel">
        <h3 class="section-title">{{ $title }}</h3>
        <div class="owl-carousel">
            @foreach(range(1, 3) as $i)
            <div class="property-card" style="background-color: {{ $cardBgColor }};">
                <div class="property-image">
                    <img src="{{ asset('images/ticket-placeholder.jpg') }}" alt="Property {{ $i }}">
                </div>
                <div class="property-details">
                    <h4 class="property-name">Property {{ $i }}</h4>
                    <p class="property-price" style="color: {{ $priceTextColor }}">$100 per share</p>
                    <p class="property-shares" style="color: {{ $descriptionTextColor }}">500 / 1000 shares available</p>
                    <button class="btn" style="background-color: {{ $buttonBgColor }}; color: {{ $buttonTextColor }}">
                        Invest Now
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="component-properties">
    <div class="form-group">
        <label>Section Title</label>
        <input type="text" class="form-control component-property" data-property="title" value="{{ $title }}">
    </div>
    
    <div class="form-group">
        <label>Property Category</label>
        <select class="form-control component-property" data-property="category_id">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">Select a specific category to filter properties, or leave blank to show all categories.</small>
    </div>
    
    <div class="form-group">
        <label>Card Background Color</label>
        <input type="color" class="form-control component-property" data-property="card_background_color" value="{{ $cardBgColor }}">
    </div>
    
    <div class="form-group">
        <label>Price Text Color</label>
        <input type="color" class="form-control component-property" data-property="price_text_color" value="{{ $priceTextColor }}">
    </div>
    
    <div class="form-group">
        <label>Description Text Color</label>
        <input type="color" class="form-control component-property" data-property="description_text_color" value="{{ $descriptionTextColor }}">
    </div>
    
    <div class="form-group">
        <label>Button Background Color</label>
        <input type="color" class="form-control component-property" data-property="button_background_color" value="{{ $buttonBgColor }}">
    </div>
    
    <div class="form-group">
        <label>Button Text Color</label>
        <input type="color" class="form-control component-property" data-property="button_text_color" value="{{ $buttonTextColor }}">
    </div>
    
    <div class="form-group">
        <label>Slides to Show</label>
        <input type="number" class="form-control component-property" data-property="slides_to_show" value="{{ $slidesToShow }}" min="1" max="4">
    </div>
    
    <div class="form-group">
        <label>Autoplay</label>
        <select class="form-control component-property" data-property="autoplay">
            <option value="1" {{ $autoplay ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$autoplay ? 'selected' : '' }}>No</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Autoplay Speed (ms)</label>
        <input type="number" class="form-control component-property" data-property="autoplay_speed" value="{{ $autoplaySpeed }}" min="1000" step="500">
    </div>
    
    <div class="form-group">
        <label>Enable Loop</label>
        <select class="form-control component-property" data-property="loop">
            <option value="1" {{ $loop ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$loop ? 'selected' : '' }}>No</option>
        </select>
        <small class="form-text text-muted">Enable infinite loop. Will be automatically disabled if you have fewer items than slides to show to prevent duplication.</small>
    </div>
</div>

<style>
.property-category-carousel {
    padding: 20px 0;
}

.property-card {
    border-radius: 8px;
    overflow: hidden;
    margin: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.property-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.property-details {
    padding: 15px;
}

.property-name {
    margin-bottom: 10px;
    font-size: 18px;
}

.property-price {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 5px;
}

.property-shares {
    margin-bottom: 15px;
    font-size: 14px;
}

.btn {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn:hover {
    opacity: 0.9;
}
</style>
