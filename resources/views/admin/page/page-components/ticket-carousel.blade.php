@php
    // Get properties with defaults
    $title = $component['properties']['title'] ?? 'Available Tickets';
    $cardBgColor = $component['properties']['card_background_color'] ?? '#ffffff';
    $priceTextColor = $component['properties']['price_text_color'] ?? '#2e7d3e';
    $descriptionTextColor = $component['properties']['description_text_color'] ?? '#666666';
    $buttonBgColor = $component['properties']['button_background_color'] ?? '#007bff';
    $buttonTextColor = $component['properties']['button_text_color'] ?? '#ffffff';
    $slidesToShow = $component['properties']['slides_to_show'] ?? 3;
    $autoplay = $component['properties']['autoplay'] ?? false;
    $autoplaySpeed = $component['properties']['autoplay_speed'] ?? 3000;
@endphp

<div class="component-preview">
    <div class="ticket-carousel">
        <h3 class="section-title">{{ $title }}</h3>
        <div class="owl-carousel">
            @foreach(range(1, 3) as $i)
            <div class="ticket-card" style="background-color: {{ $cardBgColor }};">
                <div class="ticket-image">
                    <img src="{{ asset('images/ticket-placeholder.jpg') }}" alt="Ticket {{ $i }}">
                </div>
                <div class="ticket-details">
                    <h4 class="ticket-name">Event Ticket {{ $i }}</h4>
                    <p class="ticket-price" style="color: {{ $priceTextColor }}">$99.99</p>
                    <p class="ticket-description" style="color: {{ $descriptionTextColor }}">Lorem ipsum dolor sit amet</p>
                    <button class="btn" style="background-color: {{ $buttonBgColor }}; color: {{ $buttonTextColor }}">
                        Buy Now
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
</div>

<style>
.ticket-carousel {
    padding: 20px 0;
}

.ticket-card {
    border-radius: 8px;
    overflow: hidden;
    margin: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.ticket-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.ticket-details {
    padding: 15px;
}

.ticket-name {
    margin-bottom: 10px;
    font-size: 18px;
}

.ticket-price {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.ticket-description {
    margin-bottom: 15px;
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