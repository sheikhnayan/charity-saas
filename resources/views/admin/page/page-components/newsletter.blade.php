@php
    // Newsletter component properties with defaults
    $title = $component['properties']['title'] ?? 'Newsletter';
    $subtitle = $component['properties']['subtitle'] ?? 'Subscribe to our newsletter';
    $placeholder = $component['properties']['placeholder'] ?? 'Enter your email address';
    $buttonText = $component['properties']['button_text'] ?? 'SIGN UP';
    
    // Styling properties
    $backgroundColor = $component['properties']['background_color'] ?? '#ffffff';
    $textColor = $component['properties']['text_color'] ?? '#000000';
    $buttonColor = $component['properties']['button_color'] ?? '#28a745';
    $buttonTextColor = $component['properties']['button_text_color'] ?? '#ffffff';
    $padding = $component['properties']['padding'] ?? '40';
    $borderRadius = $component['properties']['border_radius'] ?? '8';
    $textAlign = $component['properties']['text_align'] ?? 'center';
    $maxWidth = $component['properties']['max_width'] ?? '600';
    
    // Typography
    $titleFontSize = $component['properties']['title_font_size'] ?? '24';
    $titleFontWeight = $component['properties']['title_font_weight'] ?? '600';
    $subtitleFontSize = $component['properties']['subtitle_font_size'] ?? '16';
    $subtitleFontWeight = $component['properties']['subtitle_font_weight'] ?? '400';
    $buttonFontSize = $component['properties']['button_font_size'] ?? '16';
    $buttonFontWeight = $component['properties']['button_font_weight'] ?? '600';
    $buttonPadding = $component['properties']['button_padding'] ?? '12';
    
    // Input styling
    $inputBorderColor = $component['properties']['input_border_color'] ?? '#ddd';
    $inputPadding = $component['properties']['input_padding'] ?? '12';
    $inputFontSize = $component['properties']['input_font_size'] ?? '16';
@endphp

<div class="component-settings-panel newsletter-settings">
    <div class="row">
        <!-- Content Settings -->
        <div class="col-md-6">
            <h5>Content Settings</h5>
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control component-property" 
                       data-property="title" value="{{ $title }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Subtitle</label>
                <input type="text" class="form-control component-property" 
                       data-property="subtitle" value="{{ $subtitle }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email Placeholder</label>
                <input type="text" class="form-control component-property" 
                       data-property="placeholder" value="{{ $placeholder }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Button Text</label>
                <input type="text" class="form-control component-property" 
                       data-property="button_text" value="{{ $buttonText }}">
            </div>
        </div>

        <!-- Layout Settings -->
        <div class="col-md-6">
            <h5>Layout Settings</h5>
            
            <div class="mb-3">
                <label class="form-label">Text Alignment</label>
                <select class="form-control component-property" data-property="text_align">
                    <option value="left" {{ $textAlign === 'left' ? 'selected' : '' }}>Left</option>
                    <option value="center" {{ $textAlign === 'center' ? 'selected' : '' }}>Center</option>
                    <option value="right" {{ $textAlign === 'right' ? 'selected' : '' }}>Right</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Max Width (px)</label>
                <input type="number" class="form-control component-property" 
                       data-property="max_width" value="{{ $maxWidth }}" min="200" max="1200">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Padding (px)</label>
                <input type="number" class="form-control component-property" 
                       data-property="padding" value="{{ $padding }}" min="0" max="100">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Border Radius (px)</label>
                <input type="number" class="form-control component-property" 
                       data-property="border_radius" value="{{ $borderRadius }}" min="0" max="50">
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Color Settings -->
        <div class="col-md-6">
            <h5>Color Settings</h5>
            
            <div class="mb-3">
                <label class="form-label">Background Color</label>
                <input type="color" class="form-control component-property" 
                       data-property="background_color" value="{{ $backgroundColor }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Text Color</label>
                <input type="color" class="form-control component-property" 
                       data-property="text_color" value="{{ $textColor }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Button Background Color</label>
                <input type="color" class="form-control component-property" 
                       data-property="button_color" value="{{ $buttonColor }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Button Text Color</label>
                <input type="color" class="form-control component-property" 
                       data-property="button_text_color" value="{{ $buttonTextColor }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Input Border Color</label>
                <input type="color" class="form-control component-property" 
                       data-property="input_border_color" value="{{ $inputBorderColor }}">
            </div>
        </div>

        <!-- Typography Settings -->
        <div class="col-md-6">
            <h5>Typography Settings</h5>
            
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Title Font Size (px)</label>
                        <input type="number" class="form-control component-property" 
                               data-property="title_font_size" value="{{ $titleFontSize }}" min="12" max="48">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Title Font Weight</label>
                        <select class="form-control component-property" data-property="title_font_weight">
                            <option value="300" {{ $titleFontWeight === '300' ? 'selected' : '' }}>Light</option>
                            <option value="400" {{ $titleFontWeight === '400' ? 'selected' : '' }}>Normal</option>
                            <option value="500" {{ $titleFontWeight === '500' ? 'selected' : '' }}>Medium</option>
                            <option value="600" {{ $titleFontWeight === '600' ? 'selected' : '' }}>Semi Bold</option>
                            <option value="700" {{ $titleFontWeight === '700' ? 'selected' : '' }}>Bold</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Subtitle Font Size (px)</label>
                        <input type="number" class="form-control component-property" 
                               data-property="subtitle_font_size" value="{{ $subtitleFontSize }}" min="12" max="24">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Subtitle Font Weight</label>
                        <select class="form-control component-property" data-property="subtitle_font_weight">
                            <option value="300" {{ $subtitleFontWeight === '300' ? 'selected' : '' }}>Light</option>
                            <option value="400" {{ $subtitleFontWeight === '400' ? 'selected' : '' }}>Normal</option>
                            <option value="500" {{ $subtitleFontWeight === '500' ? 'selected' : '' }}>Medium</option>
                            <option value="600" {{ $subtitleFontWeight === '600' ? 'selected' : '' }}>Semi Bold</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Button Font Size (px)</label>
                        <input type="number" class="form-control component-property" 
                               data-property="button_font_size" value="{{ $buttonFontSize }}" min="12" max="20">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Button Font Weight</label>
                        <select class="form-control component-property" data-property="button_font_weight">
                            <option value="400" {{ $buttonFontWeight === '400' ? 'selected' : '' }}>Normal</option>
                            <option value="500" {{ $buttonFontWeight === '500' ? 'selected' : '' }}>Medium</option>
                            <option value="600" {{ $buttonFontWeight === '600' ? 'selected' : '' }}>Semi Bold</option>
                            <option value="700" {{ $buttonFontWeight === '700' ? 'selected' : '' }}>Bold</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Button Padding (px)</label>
                        <input type="number" class="form-control component-property" 
                               data-property="button_padding" value="{{ $buttonPadding }}" min="8" max="20">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Input Padding (px)</label>
                        <input type="number" class="form-control component-property" 
                               data-property="input_padding" value="{{ $inputPadding }}" min="8" max="20">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Input Font Size (px)</label>
                <input type="number" class="form-control component-property" 
                       data-property="input_font_size" value="{{ $inputFontSize }}" min="12" max="20">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for property changes
    const propertyInputs = document.querySelectorAll('.newsletter-settings .component-property');
    propertyInputs.forEach(input => {
        input.addEventListener('input', function() {
            updateNewsletterPropertyFromPanel(this);
        });
        input.addEventListener('change', function() {
            updateNewsletterPropertyFromPanel(this);
        });
    });
});

function updateNewsletterPropertyFromPanel(input) {
    const property = input.dataset.property;
    const value = input.type === 'checkbox' ? input.checked : input.value;
    
    // Map property names from panel to newsletter data structure
    const propertyMap = {
        'title': 'title',
        'subtitle': 'subtitle', 
        'placeholder': 'placeholder',
        'button_text': 'buttonText',
        'background_color': 'backgroundColor',
        'text_color': 'textColor',
        'button_color': 'buttonColor',
        'button_text_color': 'buttonTextColor',
        'input_border_color': 'inputBorderColor',
        'border_radius': 'borderRadius',
        'text_align': 'textAlign',
        'max_width': 'maxWidth',
        'padding': 'padding',
        'title_font_size': 'titleFontSize',
        'title_font_weight': 'titleFontWeight',
        'subtitle_font_size': 'subtitleFontSize',
        'subtitle_font_weight': 'subtitleFontWeight',
        'button_font_size': 'buttonFontSize',
        'button_font_weight': 'buttonFontWeight',
        'button_padding': 'buttonPadding',
        'input_padding': 'inputPadding',
        'input_font_size': 'inputFontSize'
    };
    
    const newsletterDataField = propertyMap[property];
    if (newsletterDataField && typeof updateNewsletterField === 'function') {
        updateNewsletterField(value, newsletterDataField);
    }
}

function updateNewsletterComponent() {
    // Legacy function - no longer needed with proper property handling
    console.log('updateNewsletterComponent called (legacy)');
}
</script>