{{-- Universal Component Renderer for Inner-Section Architecture --}}
@php
    // Check if $component is an array of components (multiple components)
    if (is_array($component) && isset($component[0]) && is_array($component[0])) {
        // Handle multiple components - render each one in its own container
        foreach ($component as $index => $singleComponent) {
            echo '<div class="component-group-item" style="width: 100%;">';
            echo view('page-components.render-component', [
                'component' => $singleComponent,
                'componentId' => ($componentId ?? 'component') . '-' . $index,
                'isNested' => $isNested ?? false
            ])->render();
            echo '</div>';
        }
        return;
    }
    
    // Single component processing
    $componentType = $component['type'] ?? '';
    $componentData = $component['data'] ?? [];
    $style = $component['style'] ?? [];
    $wrapperStyle = $component['wrapperStyle'] ?? [];
    $responsiveStyles = $component['responsiveStyles'] ?? [];
    $visibility = $component['visibility'] ?? 'both';
    
    // Temporary debugging - remove after testing
    error_log("RENDER COMPONENT DEBUG: Type={$componentType}, IsNested=" . (isset($isNested) ? ($isNested ? 'true' : 'false') : 'undefined'));
    if ($componentType === 'feature-grid' || $componentType === 'numbered-timeline' || $componentType === 'investment-tier' || $componentType === 'ticket-carousel' || $componentType === 'ticket-category-carousel' || $componentType === 'property-category-carousel') {
        error_log("RENDER COMPONENT FOUND: {$componentType}");
    }
    $componentId = $componentId ?? ('component-' . uniqid());
    
    // Generate style strings
    $styleStr = '';
    foreach ($style as $key => $value) {
        if ($value) {
            $styleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
    
    $wrapperStyleStr = '';
    foreach ($wrapperStyle as $key => $value) {
        if ($value) {
            $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
    
    // Generate responsive CSS if available
    $responsiveCSS = '';
    if (!empty($responsiveStyles)) {
        $responsiveCSS = "/* Component {$componentId} responsive styles */\n";
        
        // Desktop styles (default - 992px and up)
        if (isset($responsiveStyles['desktop']) && is_array($responsiveStyles['desktop'])) {
            $desktopStyles = [];
            foreach ($responsiveStyles['desktop'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $desktopStyles[] = "{$prop}: {$value}";
                }
            }
            if (!empty($desktopStyles)) {
                $responsiveCSS .= "#{$componentId} { " . implode('; ', $desktopStyles) . "; }\n";
            }
        }
        
        // Tablet styles (768px to 991px)
        if (isset($responsiveStyles['tablet']) && is_array($responsiveStyles['tablet'])) {
            $tabletStyles = [];
            foreach ($responsiveStyles['tablet'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $tabletStyles[] = "{$prop}: {$value} !important";
                }
            }
            if (!empty($tabletStyles)) {
                $responsiveCSS .= "@media screen and (max-width: 991px) and (min-width: 768px) {\n";
                $responsiveCSS .= "  #{$componentId} { " . implode('; ', $tabletStyles) . "; }\n";
                $responsiveCSS .= "}\n";
            }
        }
        
        // Mobile styles (up to 767px)
        if (isset($responsiveStyles['mobile']) && is_array($responsiveStyles['mobile'])) {
            $mobileStyles = [];
            foreach ($responsiveStyles['mobile'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $mobileStyles[] = "{$prop}: {$value} !important";
                }
            }
            if (!empty($mobileStyles)) {
                $responsiveCSS .= "@media screen and (max-width: 767px) {\n";
                $responsiveCSS .= "  #{$componentId} { " . implode('; ', $mobileStyles) . "; }\n";
                $responsiveCSS .= "}\n";
            }
        }
    }

    // Device visibility CSS
    if ($visibility === 'desktop') {
        $responsiveCSS .= "@media screen and (max-width: 767px) { #{$componentId} { display: none !important; } }\n";
    } elseif ($visibility === 'mobile') {
        $responsiveCSS .= "@media screen and (min-width: 768px) { #{$componentId} { display: none !important; } }\n";
    }
@endphp

@if(!empty($responsiveCSS))
<style>
{!! $responsiveCSS !!}

.ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }

/* Quill.js Class-based Font Styles for Frontend */
.ql-size-6px { font-size: 6px !important; }
.ql-size-8px { font-size: 8px !important; }
.ql-size-9px { font-size: 9px !important; }
.ql-size-10px { font-size: 10px !important; }
.ql-size-12px { font-size: 12px !important; }
.ql-size-14px { font-size: 14px !important; }
.ql-size-16px { font-size: 16px !important; }
.ql-size-18px { font-size: 18px !important; }
.ql-size-20px { font-size: 20px !important; }
.ql-size-24px { font-size: 24px !important; }
.ql-size-28px { font-size: 28px !important; }
.ql-size-32px { font-size: 32px !important; }
.ql-size-36px { font-size: 36px !important; }
.ql-size-40px { font-size: 40px !important; }
.ql-size-48px { font-size: 48px !important; }

.ql-font-arial { font-family: Arial, sans-serif !important; }
.ql-font-helvetica { font-family: 'Helvetica Neue', Helvetica, sans-serif !important; }
.ql-font-times { font-family: 'Times New Roman', Times, serif !important; }
.ql-font-georgia { font-family: Georgia, serif !important; }
.ql-font-verdana { font-family: Verdana, sans-serif !important; }
.ql-font-courier { font-family: 'Courier New', Courier, monospace !important; }
.ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

.category-pill .label {
  background: transparent !important;
}

/* SEO-friendly semantic heading styles for frontend */
h1, .ql-header-1 {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    line-height: 1.2 !important;
    margin: 1rem 0 0.5rem 0 !important;
}
h2, .ql-header-2 {
    font-size: 2rem !important;
    font-weight: bold !important;
    line-height: 1.3 !important;
    margin: 0.8rem 0 0.4rem 0 !important;
}
h3, .ql-header-3 {
    font-size: 1.75rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.6rem 0 0.3rem 0 !important;
}
h4, .ql-header-4 {
    font-size: 1.5rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.5rem 0 0.25rem 0 !important;
}
h5, .ql-header-5 {
    font-size: 1.25rem !important;
    font-weight: bold !important;
    line-height: 1.5 !important;
    margin: 0.4rem 0 0.2rem 0 !important;
}

/* Investment CTA Component - Base Styles */
.invest-cta-wrapper {
    background-color: #f8f9fa;
    border-radius: 0px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    max-width: 500px;
    margin: 0px;
    box-sizing: border-box;
    width: 100%;
}

.invest-cta-wrapper .invest-cta-button-wrap {
    flex-shrink: 0;
}

.invest-cta-wrapper .invest-cta-button {
    display: inline-block;
    background-color: #2e7d3e;
    color: #ffffff;
    text-decoration: none;
    padding: 15px 30px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.invest-cta-wrapper .invest-cta-button:hover {
    background-color: #246630;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(46, 125, 62, 0.3);
    color: #ffffff;
    text-decoration: none;
}

.invest-cta-wrapper .investment-info-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex: 1;
}

.invest-cta-wrapper .investment-info-item {
    text-align: center;
    flex: 1;
}

.invest-cta-wrapper .investment-value {
    color: #333333;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 5px;
}

.invest-cta-wrapper .investment-label {
    color: #666666;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.2;
}

.invest-cta-wrapper .investment-divider {
    width: 1px;
    height: 40px;
    background-color: #e0e0e0;
    flex-shrink: 0;
}

/* Button components: allow side-by-side placement on desktop */
.nested-component .button-component,
.button-component {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    width: auto;
}

.nested-component .button-component .custom-button,
.button-component .custom-button {
    width: auto;
}



/* Global Mobile Fixes */
@media screen and (max-width: 767px) {
    /* Prevent horizontal overflow on mobile */
    body {
        overflow-x: hidden !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Enhanced Mobile Edge-to-Edge Experience */
    html {
        overflow-x: hidden !important;
    }
    
    /* Fix any page container margins on mobile */
    html, body, .page {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Bootstrap Container Mobile Overrides - Minimal margins for edge-to-edge feel */
    .container-fluid, .container {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Fix any component wrappers on mobile - bring content closer to edges */
    .component-wrapper, .component {
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Ensure all components fit within viewport with minimal side spacing */
    .row {
        margin-left: -2px !important;
        margin-right: -2px !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .row > [class*="col-"] {
        padding-left: 2px !important;
        padding-right: 2px !important;
        max-width: 100% !important;
    }
    
    /* Inner sections mobile edge optimization */
    .inner-section-frontend {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .inner-section-fullwidth {
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Component content mobile optimization */
    .component-content {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Text and content elements mobile spacing */
    .text-component, .heading-component {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Form elements mobile edge optimization */
    .form-component {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Button components mobile spacing */
    .button-component,
    .nested-component .button-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
        display: block !important;
        width: 100% !important;
    }
    .button-component .custom-button,
    .nested-component .button-component .custom-button {
        width: 100% !important;
        text-align: center;
    }
    
    /* Image components mobile edge behavior */
    .image-component img {
        max-width: calc(100% - 10px) !important;
        margin-left: 5px !important;
        margin-right: 5px !important;
    }
    
    /* Investment components mobile edge optimization */
    .investment-tier, .invest-cta-wrapper {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
    

    
    /* Video components mobile behavior */
    .video-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
    
    /* Gallery mobile edge behavior */
    .gallery-component {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Divider mobile spacing */
    .divider-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
    }

    /* Extra Small Mobile Devices - Investment CTA Optimization */
    @media screen and (max-width: 480px) {
        .invest-cta-wrapper {
            padding: 15px 10px !important;
        }
        
        .invest-cta-wrapper .investment-info-wrapper {
            gap: 10px !important;
        }
        
        .invest-cta-wrapper .investment-divider {
            height: 25px !important;
        }
        
        .invest-cta-wrapper .investment-value {
            font-size: 16px !important;
        }
        
        .invest-cta-wrapper .investment-label {
            font-size: 12px !important;
        }
        
        .invest-cta-wrapper .invest-cta-button {
            padding: 16px 15px !important;
            font-size: 14px !important;
        }

        .ticket-mask .row .col-md-10{
            text-align: center !important;
        }

        .inner-section-frontend {
                        padding: 0 !important;
                    }
    }
    
    /* Fix investment CTA overflow */
    [data-component-type="invest-cta"],
    .investment-tier-component,
    .perk-wrap,
    .investment-tier {
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }
    
    /* Ensure proper Bootstrap responsive behavior */
    .col-sm-12 {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Fix text-image component on mobile */
    .text-images-component .row {
        margin: 0 !important;
    }
    
    .text-images-component [class*="col-"] {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    /* Fix full-width sections on mobile */
    .inner-section-fullwidth {
        width: 100vw !important;
        margin-left: calc(-50vw + 50%) !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
    }
}
</style>
@else
{{-- Include Quill.js styles even when no responsive CSS --}}
<style>
/* Quill.js Class-based Font Styles for Frontend */
.ql-size-6px { font-size: 6px !important; }
.ql-size-8px { font-size: 8px !important; }
.ql-size-10px { font-size: 10px !important; }
.ql-size-12px { font-size: 12px !important; }
.ql-size-14px { font-size: 14px !important; }
.ql-size-16px { font-size: 16px !important; }
.ql-size-18px { font-size: 18px !important; }
.ql-size-20px { font-size: 20px !important; }
.ql-size-24px { font-size: 24px !important; }
.ql-size-28px { font-size: 28px !important; }
.ql-size-32px { font-size: 32px !important; }
.ql-size-36px { font-size: 36px !important; }
.ql-size-40px { font-size: 40px !important; }
.ql-size-48px { font-size: 48px !important; }

.ql-font-arial { font-family: Arial, sans-serif !important; }
.ql-font-helvetica { font-family: 'Helvetica Neue', Helvetica, sans-serif !important; }
.ql-font-times { font-family: 'Times New Roman', Times, serif !important; }
.ql-font-georgia { font-family: Georgia, serif !important; }
.ql-font-verdana { font-family: Verdana, sans-serif !important; }
.ql-font-courier { font-family: 'Courier New', Courier, monospace !important; }
.ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

/* SEO-friendly semantic heading styles for frontend */
h1, .ql-header-1 {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    line-height: 1.2 !important;
    margin: 1rem 0 0.5rem 0 !important;
}
h2, .ql-header-2 {
    font-size: 2rem !important;
    font-weight: bold !important;
    line-height: 1.3 !important;
    margin: 0.8rem 0 0.4rem 0 !important;
}
h3, .ql-header-3 {
    font-size: 1.75rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.6rem 0 0.3rem 0 !important;
}
h4, .ql-header-4 {
    font-size: 1.5rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.5rem 0 0.25rem 0 !important;
}
h5, .ql-header-5 {
    font-size: 1.25rem !important;
    font-weight: bold !important;
    line-height: 1.5 !important;
    margin: 0.4rem 0 0.2rem 0 !important;
}
</style>
@endif

<div class="component-wrapper" style="{{ $wrapperStyleStr }}" id="{{ $componentId }}">
    @switch($componentType)
        
        @case('inner-section')
            @php
                $innerSectionData = $component['innerSectionData'] ?? [];
                $nestedComponents = $component['nestedComponents'] ?? [];
                $columns = $innerSectionData['columns'] ?? 1;
                $gap = $innerSectionData['gap'] ?? '15px';
                $fullWidth = $innerSectionData['fullWidth'] ?? false;
                $contentWidth = $innerSectionData['contentWidth'] ?? 'full';
                $contentWidth = $innerSectionData['contentWidth'] ?? 'full'; // 'full' or 'boxed'
                
                // Animation settings
                $animationEnabled = $innerSectionData['animationEnabled'] ?? false;
                // Convert boolean strings to actual booleans
                if (is_string($animationEnabled)) {
                    $animationEnabled = ($animationEnabled === '1' || $animationEnabled === 'true' || $animationEnabled === true);
                }
                $animationType = $innerSectionData['animationType'] ?? 'fade';
                $animationDuration = $innerSectionData['animationDuration'] ?? '0.8';
                $animationDelay = $innerSectionData['animationDelay'] ?? '0';
                $columnStaggerDelay = $innerSectionData['columnStaggerDelay'] ?? '0.1';
                
                // Parallax settings
                $parallaxEnabled = $innerSectionData['parallaxEnabled'] ?? false;
                // Convert boolean strings to actual booleans
                if (is_string($parallaxEnabled)) {
                    $parallaxEnabled = ($parallaxEnabled === '1' || $parallaxEnabled === 'true' || $parallaxEnabled === true);
                }
                $parallaxSpeed = $innerSectionData['parallaxSpeed'] ?? '0.5';
                // Background video support
                $backgroundType = $innerSectionData['backgroundType'] ?? 'color';
                $backgroundVideoUrl = $innerSectionData['backgroundVideoUrl'] ?? '';
                $backgroundVideoType = $innerSectionData['backgroundVideoType'] ?? 'mp4';
                $backgroundVideoAutoplay = isset($innerSectionData['backgroundVideoAutoplay']) ? (bool)$innerSectionData['backgroundVideoAutoplay'] : true;
                $backgroundVideoLoop = isset($innerSectionData['backgroundVideoLoop']) ? (bool)$innerSectionData['backgroundVideoLoop'] : true;
                $backgroundVideoMuted = isset($innerSectionData['backgroundVideoMuted']) ? (bool)$innerSectionData['backgroundVideoMuted'] : true;
                $hasVideoBackground = ($backgroundType === 'video' && !empty($backgroundVideoUrl));
                $backgroundVideoEmbed = '';
                if ($hasVideoBackground && $backgroundVideoType === 'youtube') {
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $backgroundVideoUrl, $matches)) {
                        $vid = $matches[1];
                        // ALWAYS autoplay background videos with mute=1 (required for browser autoplay policies)
                        $backgroundVideoEmbed = "https://www.youtube.com/embed/{$vid}?autoplay=" . ($backgroundVideoAutoplay ? '1' : '0') . "&mute=1&loop=" . ($backgroundVideoLoop ? '1' : '0') . "&playlist={$vid}&controls=0&rel=0&modestbranding=1";
                    } else {
                        $backgroundVideoEmbed = $backgroundVideoUrl;
                    }
                }
                
                // Apply inner-section styling - ENABLE ALL STYLES for frontend
                $sectionStyle = '';
                
                // Check if any nested component is a slider
                $hasSlider = false;
                foreach ($nestedComponents as $columnComponents) {
                    if (is_array($columnComponents)) {
                        foreach ($columnComponents as $nestedComp) {
                            if (isset($nestedComp['type']) && $nestedComp['type'] === 'slider') {
                                $hasSlider = true;
                                break 2;
                            }
                        }
                    }
                }
                
                // Background color - apply when backgroundType is 'color' or not set (with a color value)
                if (($backgroundType === 'color' || !isset($innerSectionData['backgroundType'])) && 
                    isset($innerSectionData['backgroundColor']) && 
                    $innerSectionData['backgroundColor'] !== '' && 
                    $innerSectionData['backgroundColor'] !== 'transparent') {
                    $sectionStyle .= "background-color: {$innerSectionData['backgroundColor']} !important;";
                }

                if ($hasVideoBackground) {
                    $sectionStyle .= "position: relative; overflow: hidden;";
                }
                
                // Padding - apply with or without !important based on whether section has slider
                if (isset($innerSectionData['padding']) && $innerSectionData['padding'] !== '') {
                    if ($hasSlider) {
                        // For sliders, apply padding to top/bottom only, let CSS handle left/right
                        $paddingValue = $innerSectionData['padding'];
                        // Extract padding values
                        $paddingParts = explode(' ', trim($paddingValue));
                        if (count($paddingParts) == 1) {
                            // Single value: apply to top/bottom only
                            $sectionStyle .= "padding-top: {$paddingParts[0]}; padding-bottom: {$paddingParts[0]};";
                        } elseif (count($paddingParts) == 2) {
                            // Two values: top/bottom left/right
                            $sectionStyle .= "padding-top: {$paddingParts[0]}; padding-bottom: {$paddingParts[0]};";
                        } elseif (count($paddingParts) == 4) {
                            // Four values: top right bottom left
                            $sectionStyle .= "padding-top: {$paddingParts[0]}; padding-bottom: {$paddingParts[2]};";
                        } else {
                            // Default to all padding without !important
                            $sectionStyle .= "padding: {$innerSectionData['padding']};";
                        }
                    } else {
                        // For non-slider sections, apply padding normally with !important
                        $sectionStyle .= "padding: {$innerSectionData['padding']} !important;";
                    }
                }
                
                // Margin - always apply if set
                if (isset($innerSectionData['margin']) && $innerSectionData['margin'] !== '') {
                    $sectionStyle .= "margin: {$innerSectionData['margin']} !important;";
                }
                
                // Border - always apply if set
                if (isset($innerSectionData['border']) && $innerSectionData['border'] !== '') {
                    $sectionStyle .= "border: {$innerSectionData['border']} !important;";
                }
                
                // Border radius - always apply if set
                if (isset($innerSectionData['borderRadius']) && $innerSectionData['borderRadius'] !== '') {
                    $sectionStyle .= "border-radius: {$innerSectionData['borderRadius']} !important;";
                }
                
                // Handle background image with parallax support
                if (isset($innerSectionData['backgroundType']) && $innerSectionData['backgroundType'] === 'image' && !empty($innerSectionData['backgroundImage'])) {
                    $imageUrl = trim($innerSectionData['backgroundImage']);
                    if (!empty($imageUrl)) {
                        // Get background attachment setting (scroll, fixed, local)
                        $backgroundAttachment = $innerSectionData['backgroundAttachment'] ?? 'scroll';
                        
                        // FIXED: Use same background syntax as page-builder for consistency
                        $sectionStyle .= "background-image: linear-gradient(#000,#000c 18%), url({$imageUrl}) !important; ";
                        $sectionStyle .= "background-position: 0 0, 50% !important; ";
                        $sectionStyle .= "background-size: auto, cover !important; ";
                        $sectionStyle .= "background-attachment: scroll, {$backgroundAttachment} !important; ";
                        $sectionStyle .= "background-repeat: no-repeat !important; ";
                    }
                }
                
                // Calculate Bootstrap column classes for proper grid
                $bootstrapClass = '';
                switch($columns) {
                    case 1: $bootstrapClass = 'col-12'; break;
                    case 2: $bootstrapClass = 'col-lg-6 col-md-6'; break;
                    case 3: $bootstrapClass = 'col-lg-4 col-md-6'; break;
                    case 4: $bootstrapClass = 'col-lg-3 col-md-6'; break;
                    case 5: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    case 6: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    default: $bootstrapClass = 'col-lg-4 col-md-6';
                }
            @endphp
            
            @if($fullWidth)
                {{-- Full Width Section - Use CSS to break out of container --}}
                     <div class="inner-section-fullwidth {{ $animationEnabled ? 'animated-section' : '' }}" id="{{ $componentId }}" style="margin-top: 25px; {{ $sectionStyle }}" data-animation="{{ $animationType }}" data-duration="{{ $animationDuration }}" data-delay="{{ $animationDelay }}">
                    @if($hasVideoBackground)
                        <div class="inner-section-video-layer" aria-hidden="true">
                            @if($backgroundVideoType === 'youtube')
                                <iframe src="{{ $backgroundVideoEmbed }}" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; pointer-events:none; z-index: -1;"></iframe>
                            @else
                                <video src="{{ $backgroundVideoUrl }}" @if($backgroundVideoAutoplay) autoplay @endif @if($backgroundVideoLoop) loop @endif muted playsinline webkit-playsinline style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; pointer-events:none; z-index: -1;"></video>
                            @endif
                            <div class="inner-section-video-overlay"></div>
                        </div>
                    @endif
                    <style>
                        #{{ $componentId }} {
                            width: 100vw;
                            position: relative;
                            left: 50%;
                            transform: translateX(-50%);
                            box-sizing: border-box;
                        }
                        
                        @if($contentWidth === 'boxed')
                        /* Boxed content - keep components centered like a regular container */
                        #{{ $componentId }} .content-wrapper {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 15px;
                        }
                        #{{ $componentId }} .row {
                            margin: 0;
                        }
                        @else
                        /* Full width content - components spread across full width */
                        #{{ $componentId }} .row {
                            margin: 0;
                            width: 100%;
                            max-width: 100%;
                        }
                        @endif
                        
                        /* Custom gap handling for full width */
                        @if($gap !== '0px' && $gap !== '15px')
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: calc({{ $gap }} / 2);
                            padding-right: calc({{ $gap }} / 2);
                        }
                        #{{ $componentId }} .row {
                            margin-left: calc(-{{ $gap }} / 2) !important;
                            margin-right: calc(-{{ $gap }} / 2) !important;
                        }
                        @else
                        /* Default Bootstrap gutters */
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: 15px;
                            padding-right: 15px;
                        }
                        @endif
                        
                        /* Remove ALL padding for video-background components to make them truly full-width */
                        #{{ $componentId }} .row > [class*="col-"]:has(.video-background-section),
                        #{{ $componentId }} .row > [class*="col-"] .nested-component:has(.video-background-section),
                        #{{ $componentId }} .video-background-section {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                            margin-left: 0 !important;
                            margin-right: 0 !important;
                        }
                        
                        /* Force video-background to break out of column padding */
                        #{{ $componentId }} .nested-component .video-background-section {
                            width: 100vw !important;
                            position: relative !important;
                            left: 50% !important;
                            right: 50% !important;
                            margin-left: -50vw !important;
                            margin-right: -50vw !important;
                        }
                        
                        /* Remove ALL padding for slider components to make them truly full-width */
                        #{{ $componentId }} .row > [class*="col-"]:has(.slider-container),
                        #{{ $componentId }} .row > [class*="col-"] .nested-component:has(.slider-container),
                        #{{ $componentId }} .slider-container {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                            margin-left: 0 !important;
                            margin-right: 0 !important;
                        }
                        
                        /* Override inner-section padding when it contains a slider */
                        #{{ $componentId }}:has(.slider-container) {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                        }
                        
                        /* Parallax background support - Force fixed attachment */
                        @if(isset($innerSectionData['backgroundType']) && $innerSectionData['backgroundType'] === 'image' && 
                            !empty($innerSectionData['backgroundImage']) && 
                            isset($innerSectionData['backgroundAttachment']) && $innerSectionData['backgroundAttachment'] === 'fixed')
                        #{{ $componentId }} {
                            background-attachment: fixed !important;
                            background-position: center center !important;
                            background-size: cover !important;
                            background-repeat: no-repeat !important;
                        }
                        @endif
                        
                        /* Mobile responsiveness fixes - Enhanced for true full-width */
                        @media (max-width: 767px) {
                            #{{ $componentId }} {
                                width: 100vw !important;
                                position: relative !important;
                                left: 50% !important;
                                transform: translateX(-50%) !important;
                                margin-left: 0 !important;
                                margin-right: 0 !important;
                                max-width: none !important;
                                padding-left: 0 !important;
                                padding-right: 0 !important;
                                box-sizing: border-box !important;
                            }
                            
                            /* Force parallax backgrounds to scroll on mobile */
                            #{{ $componentId }}[style*="background-attachment"] {
                                background-attachment: scroll !important;
                            }
                            
                            @if($contentWidth === 'boxed')
                            #{{ $componentId }} .content-wrapper {
                                padding: 0 15px !important;
                                max-width: 100% !important;
                            }
                            @else 
                            #{{ $componentId }} .row {
                                margin: 0 !important;
                                padding: 0 15px !important;
                            }
                            @endif
                        }
                    </style>
                    
                    @if($contentWidth === 'boxed')
                        {{-- Boxed content wrapper --}}
                        <div class="content-wrapper">
                            <div class="row">
                                @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                    <div class="{{ $bootstrapClass }} {{ $animationEnabled ? 'animated-column' : '' }}" data-stagger-delay="{{ $columnIndex * floatval($columnStaggerDelay) }}">
                                        @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                            @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                                @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                                <div class="nested-component">
                                                    @include('page-components.render-component', [
                                                        'component' => $nestedComponent, 
                                                        'componentId' => $nestedComponentId,
                                                        'isNested' => true
                                                    ])
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @else
                        {{-- Full width content - direct row --}}
                        <div class="row" style="padding-left: 0px !important; padding-right: 0px !important;">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }} {{ $animationEnabled ? 'animated-column' : '' }}" data-stagger-delay="{{ $columnIndex * floatval($columnStaggerDelay) }}" style=" padding-left: 0px !important; padding-right: 0px !important;">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            @else
                {{-- Regular Section - Stay within container --}}
                <div class="inner-section-frontend {{ $animationEnabled ? 'animated-section' : '' }}" id="{{ $componentId }}" style="{{ $sectionStyle }}" data-animation="{{ $animationType }}" data-duration="{{ $animationDuration }}" data-delay="{{ $animationDelay }}">
                    @if($hasVideoBackground)
                        <div class="inner-section-video-layer" aria-hidden="true">
                            @if($backgroundVideoType === 'youtube')
                                <iframe src="{{ $backgroundVideoEmbed }}" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; pointer-events:none; z-index: -1;"></iframe>
                            @else
                                <video src="{{ $backgroundVideoUrl }}" @if($backgroundVideoAutoplay) autoplay @endif @if($backgroundVideoLoop) loop @endif muted playsinline webkit-playsinline style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; pointer-events:none; z-index: -1;"></video>
                            @endif
                            <div class="inner-section-video-overlay"></div>
                        </div>
                    @endif
                    <style>
                        #{{ $componentId }} {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 15px;
                            position: relative;
                        }
                        
                        @if($gap !== '0px' && $gap !== '15px')
                        /* Custom gap using CSS variables and margin */
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: calc({{ $gap }} / 2);
                            padding-right: calc({{ $gap }} / 2);
                        }
                        #{{ $componentId }} .row {
                            margin-left: calc(-{{ $gap }} / 2);
                            margin-right: calc(-{{ $gap }} / 2);
                        }
                        @endif
                    </style>
                    @if($gap !== '0px' && $gap !== '15px')
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }} {{ $animationEnabled ? 'animated-column' : '' }}" data-stagger-delay="{{ $columnIndex * floatval($columnStaggerDelay) }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @else
                        {{-- Standard Bootstrap grid with default spacing --}}
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }} {{ $animationEnabled ? 'animated-column' : '' }}" data-stagger-delay="{{ $columnIndex * floatval($columnStaggerDelay) }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            @endif
            
            @if($animationEnabled)
                {{-- Animation CSS and JavaScript --}}
                <style>
                    /* Animation base styles - section stays visible, only columns animate */
                    #{{ $componentId }}.animated-section {
                        /* Section background/container stays visible */
                    }
                    
                    #{{ $componentId }}.animated-section.visible {
                        /* Animation completed */
                    }
                    
                    #{{ $componentId }} .animated-column {
                        opacity: 0;
                        transition-property: opacity, transform;
                        transition-timing-function: ease;
                    }
                    
                    #{{ $componentId }} .animated-column.visible {
                        opacity: 1 !important;
                        transform: none !important;
                    }
                    
                    /* Animation types */
                    @if($animationType === 'slideLeft')
                        #{{ $componentId }} .animated-column { transform: translateX(-50px); }
                    @elseif($animationType === 'slideRight')
                        #{{ $componentId }} .animated-column { transform: translateX(50px); }
                    @elseif($animationType === 'slideUp')
                        #{{ $componentId }} .animated-column { transform: translateY(50px); }
                    @elseif($animationType === 'slideDown')
                        #{{ $componentId }} .animated-column { transform: translateY(-50px); }
                    @elseif($animationType === 'fade')
                        #{{ $componentId }} .animated-column { opacity: 0; }
                    @elseif($animationType === 'fadeZoom')
                        #{{ $componentId }} .animated-column { transform: scale(0.8); }
                    @elseif($animationType === 'fadeBlur')
                        #{{ $componentId }} .animated-column { filter: blur(10px); }
                        #{{ $componentId }} .animated-column.visible { filter: blur(0); }
                    @elseif($animationType === 'bounce')
                        #{{ $componentId }} .animated-column.visible {
                            animation: bounceIn {{ $animationDuration }}s ease;
                        }
                        @keyframes bounceIn {
                            0% { opacity: 0; transform: scale(0.3); }
                            50% { opacity: 1; transform: scale(1.05); }
                            70% { transform: scale(0.9); }
                            100% { transform: scale(1); }
                        }
                    @elseif($animationType === 'flip')
                        #{{ $componentId }} .animated-column { transform: perspective(400px) rotateY(90deg); }
                        #{{ $componentId }} .animated-column.visible {
                            animation: flipIn {{ $animationDuration }}s ease;
                        }
                        @keyframes flipIn {
                            0% { transform: perspective(400px) rotateY(90deg); opacity: 0; }
                            40% { transform: perspective(400px) rotateY(-20deg); }
                            60% { transform: perspective(400px) rotateY(10deg); opacity: 1; }
                            80% { transform: perspective(400px) rotateY(-5deg); }
                            100% { transform: perspective(400px) rotateY(0deg); }
                        }
                    @elseif($animationType === 'rotate')
                        #{{ $componentId }} .animated-column { transform: rotate(-180deg); }
                    @endif
                </style>
                
                <script>
                    (function() {
                        function initAnimation() {
                            const section = document.getElementById('{{ $componentId }}');
                            if (!section) {
                                console.error('Section not found: {{ $componentId }}');
                                return;
                            }
                            
                            const columns = section.querySelectorAll('.animated-column');
                            
                            // Fallback: show content after 6 seconds if animation doesn't trigger
                            setTimeout(() => {
                                if (!section.classList.contains('visible')) {
                                    section.classList.add('visible');
                                    columns.forEach(column => column.classList.add('visible'));
                                }
                            }, 6000);
                            
                            // Check if IntersectionObserver is supported
                            if (!('IntersectionObserver' in window)) {
                                // Fallback for older browsers - show immediately
                                section.classList.add('visible');
                                columns.forEach(column => column.classList.add('visible'));
                                return;
                            }
                            
                            const observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        // Animate section first
                                        setTimeout(() => {
                                            section.classList.add('visible');
                                        }, {{ floatval($animationDelay) * 1000 }});
                                        
                                        // Animate columns with stagger
                                        columns.forEach((column, index) => {
                                            const staggerDelay = parseFloat(column.dataset.staggerDelay || 0);
                                            const totalDelay = ({{ floatval($animationDelay) }} + staggerDelay) * 1000;
                                            
                                            setTimeout(() => {
                                                column.style.transitionDuration = '{{ $animationDuration }}s';
                                                column.classList.add('visible');
                                            }, totalDelay);
                                        });
                                        
                                        observer.unobserve(entry.target);
                                    }
                                });
                            }, {
                                threshold: 0.1,
                                rootMargin: '0px 0px -50px 0px'
                            });
                            
                            observer.observe(section);
                        }
                        
                        // Wait for DOM to be ready
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initAnimation);
                        } else {
                            initAnimation();
                        }
                    })();
                </script>
            @endif
            
            @if($parallaxEnabled && isset($innerSectionData['backgroundImage']) && !empty($innerSectionData['backgroundImage']))
                {{-- Parallax Effect JavaScript --}}
                <script>
                    (function() {
                        const section = document.getElementById('{{ $componentId }}');
                        if (!section) {
                            console.error('Parallax: Section not found - {{ $componentId }}');
                            return;
                        }
                        
                        const speed = {{ $parallaxSpeed }};
                        const isFullWidth = section.classList.contains('inner-section-fullwidth');
                        
                        // Ensure background is set up correctly for parallax
                        if (isFullWidth) {
                            // For full-width sections, we need to ensure the background attachment is NOT fixed
                            const currentAttachment = window.getComputedStyle(section).backgroundAttachment;
                            if (currentAttachment === 'fixed') {
                                section.style.setProperty('background-attachment', 'scroll', 'important');
                            }
                        }
                        
                        function updateParallax() {
                            const rect = section.getBoundingClientRect();
                            const scrolled = window.pageYOffset || window.scrollY;
                            const elementTop = rect.top + scrolled;
                            const windowHeight = window.innerHeight;
                            
                            // Calculate parallax offset when element is in viewport
                            if (rect.top < windowHeight && rect.bottom > 0) {
                                const offset = (scrolled - elementTop) * speed;
                                section.style.setProperty('background-position-y', offset + 'px', 'important');
                            }
                        }
                        
                        // Add scroll listener
                        window.addEventListener('scroll', updateParallax, { passive: true });
                        
                        // Also update on resize in case layout changes
                        window.addEventListener('resize', updateParallax, { passive: true });
                        
                        // Initial call
                        function init() {
                            console.log('Parallax initialized for {{ $componentId }}', { speed, isFullWidth });
                            updateParallax();
                        }
                        
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', init);
                        } else {
                            init();
                        }
                    })();
                </script>
            @endif
        @break

        @case('heading')
            @php
                $level = $component['level'] ?? 'h2';
                $text = $component['html'] ?? 'Heading';
            @endphp
            <{{ $level }} style="{{ $styleStr }}">
                {!! $text !!}
            </{{ $level }}>
        @break

        @case('video-background')
            @php
                // Debug: Log what we're receiving
                // error_log('Video Background Component Data: ' . json_encode($component));
                
                // Try multiple data sources: videoData, properties, or html content
                $videoData = $component['videoData'] ?? [];
                
                // If videoData is empty, try to get from properties
                if (empty($videoData) && isset($component['properties'])) {
                    $props = $component['properties'];
                    $videoData = [
                        'videoSource' => $props['video_source'] ?? 'url',
                        'videoUrl' => $props['video_url'] ?? '',
                        'videoType' => $props['video_type'] ?? 'mp4',
                        'overlayColor' => $props['overlay_color'] ?? '#000000',
                        'overlayOpacity' => $props['overlay_opacity'] ?? '0.5',
                        'minHeight' => $props['min_height'] ?? '500',
                        'contentType' => $props['content_type'] ?? 'text',
                        'heading' => $props['heading'] ?? '',
                        'subheading' => $props['subheading'] ?? '',
                        'buttonText' => $props['button_text'] ?? '',
                        'buttonUrl' => $props['button_url'] ?? '#',
                        'buttonColor' => $props['button_color'] ?? '#667eea',
                        'textColor' => $props['text_color'] ?? '#ffffff',
                        'imageUrl' => $props['image_url'] ?? '',
                        'imageWidth' => $props['image_width'] ?? '300',
                        'textAlign' => $props['text_align'] ?? 'center',
                        'verticalAlign' => $props['vertical_align'] ?? 'center',
                        'autoplay' => $props['autoplay'] ?? true,
                        'loop' => $props['loop'] ?? true,
                        'muted' => $props['muted'] ?? true,
                        'controls' => $props['controls'] ?? false,
                    ];
                }
                
                // If still empty, check if there's HTML content with video tag and extract URL
                if (empty($videoData['videoUrl']) && isset($component['html'])) {
                    $html = $component['html'];
                    // Try to extract video URL from HTML using regex
                    if (preg_match('/<source[^>]+src="([^"]+)"/', $html, $matches)) {
                        $videoData['videoUrl'] = $matches[1];
                    }
                    // Try to extract min-height
                    if (preg_match('/min-height:\s*(\d+)px/', $html, $matches)) {
                        $videoData['minHeight'] = $matches[1];
                    }
                }
                
                $videoSource = $videoData['videoSource'] ?? 'url';
                $videoUrl = $videoData['videoUrl'] ?? '';
                $videoType = $videoData['videoType'] ?? 'mp4';
                $overlayColor = $videoData['overlayColor'] ?? '#000000';
                $overlayOpacity = $videoData['overlayOpacity'] ?? '0.5';
                $minHeight = $videoData['minHeight'] ?? '500';
                
                // Handle boolean values properly
                $autoplay = isset($videoData['autoplay']) ? (bool)$videoData['autoplay'] : true;
                $loop = isset($videoData['loop']) ? (bool)$videoData['loop'] : true;
                $muted = isset($videoData['muted']) ? (bool)$videoData['muted'] : true;
                $controls = isset($videoData['controls']) ? (bool)$videoData['controls'] : false;
                
                // Content settings with defaults
                $contentType = $videoData['contentType'] ?? 'text';
                $heading = $videoData['heading'] ?? 'Your Heading Here';
                $subheading = $videoData['subheading'] ?? 'Your subheading or description goes here';
                $buttonText = $videoData['buttonText'] ?? 'Learn More';
                $buttonUrl = $videoData['buttonUrl'] ?? '#';
                $buttonColor = $videoData['buttonColor'] ?? '#667eea';
                $textColor = $videoData['textColor'] ?? '#ffffff';
                $imageUrl = $videoData['imageUrl'] ?? '';
                $imageWidth = $videoData['imageWidth'] ?? '300';
                $textAlign = $videoData['textAlign'] ?? 'center';
                $verticalAlign = $videoData['verticalAlign'] ?? 'center';
                
                // If videoUrl is still empty, use default
                if (empty($videoUrl)) {
                    $videoUrl = 'https://www.w3schools.com/html/mov_bbb.mp4';
                }
                
                // Convert hex to rgba for overlay
                function hexToRgba($hex, $opacity) {
                    $hex = str_replace('#', '', $hex);
                    if (strlen($hex) == 3) {
                        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
                        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
                        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
                    } else {
                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));
                    }
                    return "rgba($r, $g, $b, $opacity)";
                }
                
                $rgbaOverlay = hexToRgba($overlayColor, floatval($overlayOpacity));
                
                $alignmentClasses = [
                    'top' => 'flex-start',
                    'center' => 'center',
                    'bottom' => 'flex-end'
                ];
                $alignClass = $alignmentClasses[$verticalAlign] ?? 'center';
            @endphp
            
            <div class="video-background-section" style="position: relative; width: 100%; min-height: {{ $minHeight }}px; overflow: hidden; display: flex; align-items: {{ $alignClass }}; justify-content: center; {{ $styleStr }}">
                @if(!empty($videoUrl))
                    <video 
                        class="video-bg"
                        @if($autoplay) autoplay @endif
                        @if($loop) loop @endif
                        @if($muted) muted @endif
                        @if($controls) controls @endif
                        playsinline
                        webkit-playsinline
                        x-webkit-airplay="allow"
                        preload="auto"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); min-width: 100%; min-height: 100%; width: auto; height: auto; object-fit: cover; z-index: 0;"
                    >
                        <source src="{{ $videoUrl }}" type="video/{{ $videoType }}">
                        Your browser does not support the video tag.
                    </video>
                @else
                    {{-- Fallback background when no video is set --}}
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); z-index: 0;"></div>
                @endif
                
                {{-- Always show overlay --}}
                <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: {{ $rgbaOverlay }}; z-index: 1; pointer-events: none;"></div>
                
                {{-- Content area - conditional based on contentType --}}
                <div class="video-content" style="position: relative !important; z-index: 10 !important; padding: 40px 20px; width: 100%; display: flex; flex-direction: column; align-items: {{ $textAlign === 'left' ? 'flex-start' : ($textAlign === 'right' ? 'flex-end' : 'center') }};">
                    @if($contentType === 'text' || $contentType === 'both')
                        <div style="text-align: {{ $textAlign }}; color: {{ $textColor }}; z-index: 10; max-width: 800px; margin: {{ $contentType === 'both' ? '0 0 30px 0' : '0' }};">
                            @if(!empty($heading))
                                <h1 style="font-size: 3rem; font-weight: bold; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); color: {{ $textColor }} !important;">{{ $heading }}</h1>
                            @endif
                            @if(!empty($subheading))
                                <p style="font-size: 1.5rem; margin-bottom: 30px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); color: {{ $textColor }} !important;">{{ $subheading }}</p>
                            @endif
                            @php
                                $showButton = $videoData['showButton'] ?? true;
                            @endphp
                            @if($showButton && !empty($buttonText))
                                <a href="{{ $buttonUrl }}" class="btn" style="background-color: {{ $buttonColor }} !important; color: white !important; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-size: 1.1rem; display: inline-block; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">{{ $buttonText }}</a>
                            @endif
                        </div>
                    @endif
                    
                    @if(($contentType === 'image' || $contentType === 'both') && !empty($imageUrl))
                        <div style="z-index: 10; text-align: {{ $textAlign }}; {{ $contentType === 'both' ? 'margin-top: 30px;' : '' }}">
                            <img src="{{ $imageUrl }}" alt="Content Image" style="max-width: {{ $imageWidth }}px; width: 100%; height: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border-radius: 8px;">
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Add script to ensure video plays --}}
            @if(!empty($videoUrl) && $autoplay)
            <script>
                (function() {
                    // Wait for DOM to be ready
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initVideo);
                    } else {
                        initVideo();
                    }
                    
                    function initVideo() {
                        const video = document.querySelector('#{{ $componentId }} video.video-bg');
                        if (!video) return;
                        
                        // Force attributes for mobile compatibility
                        video.setAttribute('muted', '');
                        video.setAttribute('playsinline', '');
                        video.setAttribute('webkit-playsinline', '');
                        video.muted = true;
                        video.playsInline = true;
                        
                        // Try to play
                        const playPromise = video.play();
                        
                        if (playPromise !== undefined) {
                            playPromise.then(() => {
                                console.log('Video autoplay started successfully');
                            }).catch(error => {
                                console.log('Video autoplay prevented:', error);
                                
                                // Fallback: Try to play on any user interaction
                                const playOnInteraction = function() {
                                    video.muted = true;
                                    video.play().then(() => {
                                        console.log('Video played after user interaction');
                                    }).catch(e => console.log('Still cannot play:', e));
                                    
                                    // Remove listeners after first successful attempt
                                    document.removeEventListener('touchstart', playOnInteraction);
                                    document.removeEventListener('click', playOnInteraction);
                                    document.removeEventListener('scroll', playOnInteraction);
                                };
                                
                                document.addEventListener('touchstart', playOnInteraction, { once: true, passive: true });
                                document.addEventListener('click', playOnInteraction, { once: true });
                                document.addEventListener('scroll', playOnInteraction, { once: true, passive: true });
                            });
                        }
                        
                        // Handle page visibility change (when user switches tabs)
                        document.addEventListener('visibilitychange', function() {
                            if (!document.hidden && video.paused) {
                                video.play().catch(e => console.log('Cannot resume video:', e));
                            }
                        });
                    }
                })();
            </script>
            @endif
        @break

        @case('custom-html')
            @php
                $htmlContent = $component['customHtmlData']['htmlContent'] ?? $component['properties']['htmlContent'] ?? '<div style="padding: 20px; text-align: center;"><h3>Custom HTML Content</h3><p>Add your custom HTML code in the page builder</p></div>';
                $height = $component['customHtmlData']['height'] ?? $component['properties']['height'] ?? '300';
                $iframeId = 'custom-html-' . uniqid();
                
                // Inject base styles for iframe normalization
                $iframeBaseStyle = "<style>html { font-size: 10px; }</style>";
                
                // Inject comprehensive script to handle all link navigation
                $linkHandlerScript = "<script>
                    // Intercept ALL link clicks and navigate parent window instead
                    document.addEventListener('click', function(e) {
                        var target = e.target.closest('a[href]');
                        if (!target) return;
                        
                        var href = target.getAttribute('href');
                        if (!href || href === '#') return;
                        
                        // Check if it's an internal link (starts with / or is same domain)
                        var isInternal = href.startsWith('/') || 
                                       href.startsWith('./') || 
                                       href.startsWith('../') ||
                                       href.includes(window.location.hostname) ||
                                       !href.includes('://');
                        
                        if (isInternal) {
                            e.preventDefault();
                            e.stopPropagation();
                            // Navigate the parent/top window
                            try {
                                window.top.location.href = href;
                            } catch(e2) {
                                try {
                                    window.parent.location.href = href;
                                } catch(e3) {
                                    // Fallback: open in new window if parent navigation fails
                                    window.open(href, '_blank');
                                }
                            }
                        }
                    }, true);
                    
                    // Also handle form submissions to internal pages
                    document.addEventListener('submit', function(e) {
                        var form = e.target;
                        var action = form.getAttribute('action');
                        if (action && !action.includes('://')) {
                            e.preventDefault();
                            try {
                                window.top.location.href = action;
                            } catch(e2) {
                                try {
                                    window.parent.location.href = action;
                                } catch(e3) {
                                    // Fallback
                                }
                            }
                        }
                    }, true);
                </script>";
                
                // Append base styles and script to the HTML content
                $htmlContentWithScript = $iframeBaseStyle . $htmlContent . $linkHandlerScript;
            @endphp
            
            <div class="custom-html-component" id="{{ $componentId }}" style="{{ $styleStr }}">
                <iframe 
                    id="{{ $iframeId }}"
                    srcdoc="{!! htmlspecialchars($htmlContentWithScript) !!}" 
                    style="width: 100%; border: none; display: block; min-height: {{ $height }}px;"
                    sandbox="allow-scripts allow-same-origin allow-top-navigation allow-popups"
                    scrolling="no"
                    loading="lazy"
                    onload="(function(iframe){
                        try {
                            var resizeIframe = function() {
                                var doc = iframe.contentDocument || iframe.contentWindow.document;
                                if (doc && doc.body) {
                                    var height = Math.max(
                                        doc.body.scrollHeight,
                                        doc.documentElement.scrollHeight,
                                        {{ $height }}
                                    );
                                    iframe.style.height = height + 'px';
                                }
                            };
                            resizeIframe();
                            setTimeout(resizeIframe, 100);
                            setTimeout(resizeIframe, 500);
                            setTimeout(resizeIframe, 1000);
                        } catch(e) { console.warn('Auto-resize failed:', e); }
                    })(this);">
                </iframe>
            </div>
        @break

        @case('text')
            @php
                // Check all possible keys where text content might be stored
                $text = '';
                $textData = $component['_textData'] ?? $component['textData'] ?? [];
                $properties = $component['properties'] ?? [];
                
                // Try structured textData first, then fallback to various text fields
                if (!empty($textData['content'])) {
                    $text = $textData['content'];
                } elseif (!empty($properties['text'])) {
                    $text = $properties['text'];
                } elseif (!empty($properties['content'])) {
                    $text = $properties['content'];
                } elseif (isset($component['html']) && !empty($component['html'])) {
                    $text = $component['html'];
                } elseif (isset($component['content']) && !empty($component['content'])) {
                    $text = $component['content'];
                } elseif (isset($component['text']) && !empty($component['text'])) {
                    $text = $component['text'];
                } elseif (isset($component['textContent']) && !empty($component['textContent'])) {
                    $text = $component['textContent'];
                } else {
                    $text = '<p style="color: #666; font-style: italic;">Text content will appear here. Configure this component in the admin panel.</p>';
                }
                // Remove border-related styles from $styleStr
                $noBorderStyleStr = preg_replace('/border(-[a-z]+)?\s*:[^;]+;?/i', '', $styleStr);
            @endphp
            <div class="text-component" style="{{ $noBorderStyleStr }}">
                {!! $text !!}
            </div>
        @break

        @case('button')
            @php
                // Check if we have saved HTML from page builder that contains the full structure
                $savedHtml = $component['html'] ?? '';
                
                // If saved HTML contains the full button structure, extract just the text
                if (strpos($savedHtml, '<a href') !== false && strpos($savedHtml, 'class="btn custom-button"') !== false) {
                    // Extract text from HTML using regex
                    preg_match('/>([^<]+)<\/a>/', $savedHtml, $matches);
                    $buttonText = isset($matches[1]) ? trim($matches[1]) : 'Click Me';
                    
                    // Extract href from HTML
                    preg_match('/href="([^"]+)"/', $savedHtml, $hrefMatches);
                    $buttonUrl = isset($hrefMatches[1]) ? $hrefMatches[1] : '#';
                    
                    // Extract target from HTML
                    preg_match('/target="([^"]+)"/', $savedHtml, $targetMatches);
                    $buttonTarget = isset($targetMatches[1]) ? $targetMatches[1] : '_self';
                    
                    // Extract styles from HTML
                    preg_match('/background-color:\s*([^;]+);/', $savedHtml, $bgMatches);
                    $buttonBgColor = isset($bgMatches[1]) ? trim($bgMatches[1]) : '#007bff';
                    
                    // More specific regex for text color (not background-color)
                    preg_match('/(?<!background-)color:\s*([^;]+);/', $savedHtml, $colorMatches);
                    $buttonTextColor = isset($colorMatches[1]) ? trim($colorMatches[1]) : '#ffffff';
                    
                    preg_match('/padding:\s*([^;]+);/', $savedHtml, $paddingMatches);
                    $buttonPadding = isset($paddingMatches[1]) ? trim($paddingMatches[1]) : '10px 20px';
                    
                    preg_match('/border-radius:\s*([^;]+);/', $savedHtml, $radiusMatches);
                    $borderRadius = isset($radiusMatches[1]) ? trim($radiusMatches[1]) : '4px';
                    
                    preg_match('/font-size:\s*([^;]+);/', $savedHtml, $fontSizeMatches);
                    $fontSize = isset($fontSizeMatches[1]) ? trim($fontSizeMatches[1]) : '16px';
                    
                    preg_match('/font-weight:\s*([^;]+);/', $savedHtml, $fontWeightMatches);
                    $fontWeight = isset($fontWeightMatches[1]) ? trim($fontWeightMatches[1]) : '400';
                } else {
                    // Use the new _buttonData structure or fallback to properties
                    $buttonData = $component['_buttonData'] ?? $component['buttonData'] ?? [];
                    $buttonText = $buttonData['buttonText'] ?? $component['properties']['button_text'] ?? $component['text'] ?? 'Click Me';
                    $buttonUrl = $buttonData['buttonUrl'] ?? $component['properties']['button_url'] ?? $component['href'] ?? '#';
                    $buttonTarget = $buttonData['buttonTarget'] ?? $component['properties']['button_target'] ?? (($component['openInNewTab'] ?? false) ? '_blank' : '_self');
                    
                    // Button styling
                    $buttonBgColor = $buttonData['buttonBgColor'] ?? $component['properties']['button_bg_color'] ?? $style['backgroundColor'] ?? '#007bff';
                    $buttonTextColor = $buttonData['buttonTextColor'] ?? $component['properties']['button_text_color'] ?? $style['color'] ?? '#ffffff';
                    $buttonPadding = $buttonData['buttonPadding'] ?? $component['properties']['button_padding'] ?? $style['padding'] ?? '10px 20px';
                    $borderRadius = $buttonData['borderRadius'] ?? $component['properties']['border_radius'] ?? $style['borderRadius'] ?? '4px';
                    $fontSize = $buttonData['fontSize'] ?? $component['properties']['font_size'] ?? $style['fontSize'] ?? '16px';
                    $fontWeight = $buttonData['fontWeight'] ?? $component['properties']['font_weight'] ?? $style['fontWeight'] ?? '400';
                }
                
                $textAlign = $component['properties']['text_align'] ?? $style['textAlign'] ?? 'center';
                $textDecoration = $component['properties']['text_decoration'] ?? 'none';
                $border = $component['properties']['border'] ?? $style['border'] ?? 'none';
                $boxShadow = $component['properties']['box_shadow'] ?? $style['boxShadow'] ?? 'none';
                $transition = $component['properties']['transition'] ?? 'all 0.3s ease';
            @endphp
            <div class="button-component" style="text-align: {{ $textAlign }}; {{ $styleStr }}">
                <a href="{{ $buttonUrl }}" target="{{ $buttonTarget }}" 
                   style="display: inline-block; background-color: {{ $buttonBgColor }}; color: {{ $buttonTextColor }}; 
                          padding: {{ $buttonPadding }}; border-radius: {{ $borderRadius }}; font-size: {{ $fontSize }}; 
                          font-weight: {{ $fontWeight }}; text-decoration: {{ $textDecoration }}; border: {{ $border }}; 
                          box-shadow: {{ $boxShadow }}; transition: {{ $transition }}; cursor: pointer;"
                   class="btn custom-button">
                    {{ $buttonText }}
                </a>
            </div>
        @break

        @case('image')
            @php
                $imageData = $component['imageData'] ?? [];
                $src = $imageData['src'] ?? 'https://via.placeholder.com/400x250';
                $alt = $imageData['alt'] ?? 'Image';
                $width = $imageData['width'] ?? '100%';
                $height = $imageData['height'] ?? 'auto';
                $objectFit = $imageData['objectFit'] ?? 'cover';
                $link = $imageData['link'] ?? '';
                $openInNewTab = $imageData['openInNewTab'] ?? false;
                $alignment = $component['properties']['alignment'] ?? 'left';
            @endphp
            
            <div style="text-align: {{ $alignment }}; {{ $styleStr }}">
                @if($link)
                    <a href="{{ $link }}" {{ $openInNewTab ? 'target="_blank"' : '' }} style="display:inline-block;">
                @endif
                <img src="{{ $src }}" alt="{{ $alt }}" 
                     style="width:{{ $width }};height:{{ $height }};object-fit:{{ $objectFit }};" 
                     class="img-fluid"/>
                @if($link)
                    </a>
                @endif
            </div>
        @break

        @case('gallery')
            @php
                $galleryData = $component['galleryData'] ?? [];
                $images = $galleryData['images'] ?? [];
                $columns = $galleryData['columns'] ?? 3;
                $bootstrapClass = '';
                switch($columns) {
                    case 2: $bootstrapClass = 'col-md-6'; break;
                    case 3: $bootstrapClass = 'col-md-4'; break;
                    case 4: $bootstrapClass = 'col-md-3'; break;
                    case 5: $bootstrapClass = 'col-md-2'; break;
                    case 6: $bootstrapClass = 'col-md-2'; break;
                    default: $bootstrapClass = 'col-md-4';
                }
            @endphp
            <div class="row gallery-component" style="{{ $styleStr }}">
                @foreach($images as $image)
                    @php
                        // Handle both string URLs and object format
                        $imageSrc = is_string($image) ? $image : ($image['src'] ?? 'https://via.placeholder.com/300x200');
                        $imageAlt = is_string($image) ? 'Gallery Image' : ($image['alt'] ?? 'Gallery Image');
                    @endphp
                    <div class="{{ $bootstrapClass }} mb-3">
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $imageAlt }}" 
                             class="img-fluid gallery-img-preview" 
                             style="width:100%;height:250px;object-fit:cover;border-radius:8px;cursor:pointer;">
                    </div>
                @endforeach
            </div>
        @break

        @case('slider')
            @php
                $sliderData = $component['sliderData'] ?? [];
                $images = $sliderData['images'] ?? [];
                $slidesToShow = $sliderData['slidesToShow'] ?? 1;
                $slideSpeed = $sliderData['slideSpeed'] ?? 2000;
                $isMarquee = $sliderData['isMarquee'] ?? false;
                $sliderId = 'slider-' . ($componentId ?? uniqid());
            @endphp

            <!-- Load Owl Carousel CSS and JS if not already loaded -->
            <script>
                if (!window.owlCarouselLoaded) {
                    // Load CSS
                    var owlCSS = document.createElement('link');
                    owlCSS.rel = 'stylesheet';
                    owlCSS.href = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';
                    document.head.appendChild(owlCSS);
                    
                    var owlThemeCSS = document.createElement('link');
                    owlThemeCSS.rel = 'stylesheet';
                    owlThemeCSS.href = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css';
                    document.head.appendChild(owlThemeCSS);
                    
                    // Load JS
                    var owlJS = document.createElement('script');
                    owlJS.src = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
                    document.head.appendChild(owlJS);
                    
                    window.owlCarouselLoaded = true;
                }
            </script>

            <div class="slider-container" style="position: relative; {{ $styleStr }}">
                <div class="owl-carousel owl-theme" id="{{ $sliderId }}">
                @foreach($images as $image)
                    @php
                        // Handle both string URLs and object format
                        $imageSrc = is_string($image) ? $image : ($image['src'] ?? 'https://via.placeholder.com/800x400');
                        $imageAlt = is_string($image) ? 'Slider Image' : ($image['alt'] ?? 'Slider Image');
                    @endphp
                    <div class="item">
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $imageAlt }}" 
                             style="width:100%;height:400px;object-fit:cover;border-radius:8px;">
                    </div>
                @endforeach
                </div>
            </div>
            <script>
                function initSlider{{ str_replace('-', '_', $sliderId) }}() {
                    const isMarquee = {{ $isMarquee ? 'true' : 'false' }};
                    
                    if (isMarquee) {
                        // Marquee Mode - Continuous CSS Animation
                        initMarqueeSlider{{ str_replace('-', '_', $sliderId) }}();
                    } else {
                        // Regular Owl Carousel Mode
                        if (typeof $.fn.owlCarousel !== 'undefined') {
                            const sliderConfig = {
                                items: {{ $slidesToShow }},
                                loop: true,
                                margin: 10,
                                autoplay: true,
                                autoplayTimeout: {{ $slideSpeed }},
                                autoplayHoverPause: true,
                                smartSpeed: 800,
                                animateOut: 'fadeOut',
                                animateIn: 'fadeIn',
                                nav: true,
                                dots: true,
                                navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                                responsive: {
                                    0: { 
                                        items: 1,
                                        margin: 5
                                    },
                                    480: { 
                                        items: {{ min(2, $slidesToShow) }},
                                        margin: 8
                                    },
                                    768: { 
                                        items: {{ min(3, $slidesToShow) }},
                                        margin: 10
                                    },
                                    1000: { 
                                        items: {{ $slidesToShow }},
                                        margin: 10
                                    }
                                },
                                onInitialized: function(event) {
                                    // Add smooth CSS transitions
                                    $(event.target).find('.owl-item').css({
                                        'transition': 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                                        'transform-style': 'preserve-3d'
                                    });
                                    
                                    // Force autoplay settings to ensure consistent speed across all devices
                                    const owl = $(event.target).data('owl.carousel');
                                    if (owl) {
                                        owl.settings.autoplayTimeout = {{ $slideSpeed }};
                                        owl.settings.smartSpeed = 800;
                                    }
                                }
                            };
                            
                            // Initialize slider with config
                            $("#{{ $sliderId }}").owlCarousel(sliderConfig);

                    // Add custom CSS for smoother animations
                    $('<style>').prop('type', 'text/css').html(`
                        #{{ $sliderId }} .owl-stage {
                            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
                        }
                        #{{ $sliderId }} .owl-item img {
                            transition: all 0.3s ease-in-out;
                        }
                        #{{ $sliderId }} .owl-item:hover img {
                            transform: scale(1.02);
                        }
                        .slider-container {
                            position: relative !important;
                        }
                        #{{ $sliderId }} .owl-nav {
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            pointer-events: none;
                        }
                        #{{ $sliderId }} .owl-nav button {
                            position: absolute;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0,0,0,0.7) !important;
                            color: white !important;
                            border: none !important;
                            border-radius: 50% !important;
                            width: 40px !important;
                            height: 40px !important;
                            font-size: 16px !important;
                            transition: all 0.3s ease !important;
                            z-index: 10;
                            pointer-events: all;
                        }
                        #{{ $sliderId }} .owl-nav .owl-prev {
                            left: 10px;
                        }
                        #{{ $sliderId }} .owl-nav .owl-next {
                            right: 10px;
                        }
                        #{{ $sliderId }} .owl-nav button:hover {
                            background: rgba(0,0,0,0.9) !important;
                            transform: translateY(-50%) scale(1.1) !important;
                        }
                        #{{ $sliderId }} .owl-dots {
                            text-align: center;
                            margin-top: 15px;
                        }
                        #{{ $sliderId }} .owl-dot {
                            display: inline-block;
                            width: 10px;
                            height: 10px;
                            margin: 0 5px;
                            background: rgba(0,0,0,0.3);
                            border-radius: 50%;
                            transition: all 0.3s ease;
                        }
                        #{{ $sliderId }} .owl-dot.active {
                            background: #007bff;
                            transform: scale(1.2);
                        }
                        /* Remove mobile padding for full-width sliders */
                        @media (max-width: 767px) {
                            .slider-container {
                                padding-left: 0 !important;
                                padding-right: 0 !important;
                                margin-left: 0 !important;
                                margin-right: 0 !important;
                            }
                            #{{ $sliderId }} .owl-stage-outer,
                            #{{ $sliderId }} .owl-stage,
                            #{{ $sliderId }} .owl-item {
                                padding-left: 0 !important;
                                padding-right: 0 !important;
                            }
                            #{{ $sliderId }} .item {
                                margin: 0 !important;
                            }
                            #{{ $sliderId }} .item img {
                                border-radius: 0 !important;
                            }
                        }
                    `).appendTo('head');
                        } else {
                            // Retry after a short delay if Owl Carousel is not loaded yet
                            setTimeout(initSlider{{ str_replace('-', '_', $sliderId) }}, 100);
                        }
                    }
                }

                function initMarqueeSlider{{ str_replace('-', '_', $sliderId) }}() {
                    const slider = $("#{{ $sliderId }}");
                    const container = slider.parent();
                    
                    // Safety check - ensure slider exists
                    if (slider.length === 0) {
                        console.log('Slider element not found:', '{{ $sliderId }}');
                        return;
                    }
                    
                    // Remove owl-carousel classes for marquee mode
                    slider.removeClass('owl-carousel owl-theme');
                    
                    // Get original items
                    const items = slider.find('.item');
                    
                    // Safety check - ensure items exist
                    if (items.length === 0) {
                        console.log('No slider items found in:', '{{ $sliderId }}');
                        return;
                    }
                    
                    console.log('Initializing marquee for {{ $sliderId }} with', items.length, 'items');
                    
                    // Calculate item width based on slidesToShow
                    const itemWidth = 100 / {{ $slidesToShow }};
                    const animationDuration = {{ $slideSpeed / 50 }};
                    const animationName = 'marqueeScroll_' + Math.random().toString(36).substr(2, 9);
                    
                    // Create individual item elements with proper sizing for 70px height
                    let itemsHtml = '';
                    items.each(function(index) {
                        const img = $(this).find('img');
                        const src = img.attr('src');
                        const alt = img.attr('alt');
                        itemsHtml += `
                            <div class="marquee-item" style="
                                flex: 0 0 auto;
                                padding: 0 10px;
                                box-sizing: border-box;
                                display: flex;
                                align-items: center;
                                height: 70px;
                            ">
                                <img src="${src}" alt="${alt}" style="
                                    width: auto;
                                    max-height: 70px;
                                    height: auto;
                                    object-fit: contain;
                                    border-radius: 8px;
                                    display: block;
                                ">
                            </div>
                        `;
                    });
                    
                    // Create seamless marquee with enough duplicates
                    const totalSets = 4; // Duplicate content 4 times for smooth infinite scroll
                    let allItemsHtml = '';
                    for(let i = 0; i < totalSets; i++) {
                        allItemsHtml += itemsHtml;
                    }
                    
                    const marqueeHTML = `
                        <div class="marquee-wrapper">
                            <div class="marquee-track" id="track_{{ $sliderId }}" style="
                                display: flex;
                                animation: ${animationName} ${animationDuration}s linear infinite;
                                width: ${totalSets * 100}%;
                            ">
                                ${allItemsHtml}
                            </div>
                        </div>
                    `;
                    
                    slider.html(marqueeHTML);
                    console.log('Infinite marquee initialized for {{ $sliderId }}');
                    
                    // Start animation immediately
                    setTimeout(() => {
                        const track = slider.find('.marquee-track');
                        if (track.length) {
                            track.css('animation-play-state', 'running');
                            console.log('Marquee animation started for {{ $sliderId }}');
                        }
                    }, 100);

                    // Add enhanced marquee CSS animation with unique keyframe name
                    $('<style>').prop('type', 'text/css').html(`
                        @keyframes ${animationName} {
                            0% { transform: translateX(0%); }
                            100% { transform: translateX(-25%); }
                        }
                        
                        #{{ $sliderId }} {
                            overflow: hidden;
                            position: relative;
                            width: 100%;
                        }
                        
                        #{{ $sliderId }} .marquee-wrapper {
                            width: 100%;
                            overflow: hidden;
                            position: relative;
                            height: 70px;
                        }
                        
                        #{{ $sliderId }} .marquee-track {
                            display: flex;
                            animation-name: ${animationName};
                            animation-duration: ${animationDuration}s;
                            animation-timing-function: linear;
                            animation-iteration-count: infinite;
                            animation-play-state: running;
                            transform: translateZ(0);
                            backface-visibility: hidden;
                            will-change: transform;
                            height: 70px;
                            align-items: center;
                        }
                        
                        #{{ $sliderId }} .marquee-track:hover {
                            animation-play-state: paused;
                        }
                        
                        #{{ $sliderId }} .marquee-item {
                            flex-shrink: 0;
                            display: flex;
                            align-items: center;
                            height: 70px;
                            padding: 0 10px;
                        }
                        
                        #{{ $sliderId }} .marquee-item img {
                            width: auto;
                            max-height: 70px;
                            height: auto;
                            object-fit: contain;
                            border-radius: 8px;
                            transition: transform 0.3s ease;
                            display: block;
                        }
                        
                        #{{ $sliderId }} .marquee-item img:hover {
                            transform: scale(1.05);
                        }
                        
                        /* Responsive adjustments - maintain 70px max height on all devices */
                        @media (max-width: 768px) {
                            #{{ $sliderId }} .marquee-wrapper {
                                height: 70px;
                            }
                            #{{ $sliderId }} .marquee-track {
                                height: 70px;
                                animation-duration: ${animationDuration}s !important;
                            }
                            #{{ $sliderId }} .marquee-item {
                                height: 70px;
                                padding: 0 8px;
                            }
                            #{{ $sliderId }} .marquee-item img {
                                max-height: 70px;
                            }
                        }
                        
                        @media (max-width: 480px) {
                            #{{ $sliderId }} .marquee-wrapper {
                                height: 70px;
                            }
                            #{{ $sliderId }} .marquee-track {
                                height: 70px;
                                animation-duration: ${animationDuration}s !important;
                            }
                            #{{ $sliderId }} .marquee-item {
                                height: 70px;
                                padding: 0 6px;
                            }
                            #{{ $sliderId }} .marquee-item img {
                                max-height: 70px;
                            }

                            .inner-section-frontend {
                        padding: 0 !important;
                    }
                        }
                    `).appendTo('head');
                }

                // Initialize when document is ready and try multiple times if needed
                $(document).ready(function(){
                    initSlider{{ str_replace('-', '_', $sliderId) }}();
                });

                // Also try initialization after a delay to ensure all scripts are loaded
                setTimeout(function() {
                    if (!$("#{{ $sliderId }}").hasClass('owl-loaded')) {
                        initSlider{{ str_replace('-', '_', $sliderId) }}();
                    }
                }, 500);
            </script>
        @break

        @case('custom-form')
            @php
                $formFields = $component['customFormFields'] ?? [];
            @endphp
            <form class="custom-form" style="{{ $styleStr }}">
                @foreach($formFields as $field)
                    <div class="mb-3">
                        <label class="form-label">{{ $field['label'] ?? 'Field' }}</label>
                        @switch($field['type'] ?? 'text')
                            @case('textarea')
                                <textarea class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                          {{ ($field['required'] ?? false) ? 'required' : '' }}></textarea>
                            @break
                            @case('select')
                                <select class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @break
                            @default
                                <input type="{{ $field['type'] ?? 'text' }}" class="form-control" 
                                       name="{{ $field['name'] ?? 'field' }}" 
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                        @endswitch
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        @break

        @case('divider')
            @php
                // Support both style (old format) and properties (new format)
                $height = $component['properties']['height'] ?? $style['height'] ?? '2px';
                $backgroundColor = $component['properties']['background_color'] ?? $style['backgroundColor'] ?? '#ddd';
                $margin = $component['properties']['margin'] ?? $style['margin'] ?? '1rem 0';
                $borderRadius = $component['properties']['border_radius'] ?? $style['borderRadius'] ?? '0';
                $opacity = $component['properties']['opacity'] ?? $style['opacity'] ?? '1';
            @endphp
            <hr style="height:{{ $height }};background-color:{{ $backgroundColor }};border:none;margin:{{ $margin }};border-radius:{{ $borderRadius }};opacity:{{ $opacity }};{{ $styleStr }}">
        @break

        @case('spacer')
            @php
                $height = $style['height'] ?? '20px';
            @endphp
            <div style="height:{{ $height }};{{ $styleStr }}"></div>
        @break

        @case('event-countdown')
            @if(isset($component['countdownData']))
                @php
                    $countdownData = $component['countdownData'];
                    $label = $countdownData['label'] ?? '';
                    $date = $countdownData['date'] ?? '';
                    $fontWeight = $countdownData['fontWeight'] ?? 'bold'; // Legacy support
                    
                    // Color options for different elements
                    $numberColor = $countdownData['numberColor'] ?? '#000';
                    $textColor = $countdownData['textColor'] ?? '#000';
                    $remainingVerbiageColor = $countdownData['remainingVerbiageColor'] ?? '#000';
                    
                    // Font weight options for different elements
                    $numberFontWeight = $countdownData['numberFontWeight'] ?? 'bold';
                    $textFontWeight = $countdownData['textFontWeight'] ?? 'normal';
                    $remainingFontWeight = $countdownData['remainingFontWeight'] ?? 'normal';
                    
                    // Show/hide remaining text option
                    $showRemainingText = $countdownData['showRemainingText'] ?? true;
                    
                    // Convert font weight values to CSS
                    $numberWeight = $numberFontWeight === 'bold' ? 600 : 400;
                    $textWeight = $textFontWeight === 'bold' ? 600 : 400;
                    $remainingWeight = $remainingFontWeight === 'bold' ? 600 : 400;
                    
                    // Build wrapper style (for margin, etc.)
                    $wrapperStyle = $wrapperStyleStr;
                    $backgroundColor = '';
                    
                    // Build countdown style (for color, background, padding, etc.)
                    $countdownStyle = $styleStr;
                    $color = '#000';
                    
                    // Extract color from style (fallback if individual colors not set)
                    if (isset($style['color'])) {
                        $color = $style['color'];
                        // Use main color as fallback if specific colors not provided
                        if ($countdownData['numberColor'] ?? false === false) $numberColor = $color;
                        if ($countdownData['textColor'] ?? false === false) $textColor = $color;
                        if ($countdownData['remainingVerbiageColor'] ?? false === false) $remainingVerbiageColor = $color;
                    }
                    
                    // Legacy fontWeight fallback support
                    if (!isset($countdownData['numberFontWeight']) && isset($countdownData['fontWeight'])) {
                        $numberWeight = $fontWeight === 'normal' ? 400 : 600;
                        $remainingWeight = $fontWeight === 'normal' ? 400 : 600;
                    }
                    
                    // Extract background color
                    if (isset($style['backgroundColor'])) {
                        $backgroundColor = $style['backgroundColor'];
                        $wrapperStyle .= 'background-color:' . $backgroundColor . ';';
                    }
                    
                    // Generate unique IDs for this countdown
                    $uniqueId = 'countdown_' . uniqid();
                @endphp
                <div class="event-countdown" style="padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;{{ $wrapperStyle }}">
                    <div class="timer text-center mt-5" style="{{ $countdownStyle }}">
                        <div class="d-flex justify-content-center align-items-center flex-wrap">
                            <div class="mx-2 counters">
                                <h1 id="months_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Months</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="days_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Days</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="hours_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Hours</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="minutes_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Minutes</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="seconds_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Seconds</p>
                            </div>
                        </div>
                        @if($showRemainingText && $label)
                            <p style="font-size: .8em; font-weight:{{ $remainingWeight }} !important; color:{{ $remainingVerbiageColor }}">{{ $label }}</p>
                        @endif
                    </div>
                    <input type="hidden" id="timer_{{ $uniqueId }}" class="date-countdown" value="{{ $date }}">
                </div>
                <script>
                    (function() {
                        const timerId = "{{ $uniqueId }}";
                        const dateValue = document.getElementById("timer_" + timerId).value;
                        
                        if (!dateValue) return;
                        
                        const targetDate = new Date(dateValue).getTime();
                        
                        function updateCountdown() {
                            const now = new Date().getTime();
                            const timeLeft = targetDate - now;
                            
                            if (timeLeft <= 0) {
                                document.getElementById("months_" + timerId).textContent = 0;
                                document.getElementById("days_" + timerId).textContent = 0;
                                document.getElementById("hours_" + timerId).textContent = 0;
                                document.getElementById("minutes_" + timerId).textContent = 0;
                                document.getElementById("seconds_" + timerId).textContent = 0;
                                return;
                            }
                            
                            const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
                            const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                            
                            document.getElementById("months_" + timerId).textContent = months;
                            document.getElementById("days_" + timerId).textContent = days;
                            document.getElementById("hours_" + timerId).textContent = hours;
                            document.getElementById("minutes_" + timerId).textContent = minutes;
                            document.getElementById("seconds_" + timerId).textContent = seconds;
                        }
                        
                        // Initial update
                        updateCountdown();
                        
                        // Update every second
                        setInterval(updateCountdown, 1000);
                    })();
                </script>
            @endif
        @break

        @case('custom-banner')
            @php
                $banner = $component['customBannerData'] ?? [];
            @endphp
            <style>
                .custom-banner-wrapper {
                    position: relative;
                    width: 100%;
                    min-height: 400px;
                    overflow: hidden;
                }
                .custom-banner-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    min-height: 400px;
                }
                .custom-banner-title {
                    position: absolute;
                    top: 40%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 90%;
                    margin: 0;
                    line-height: 1.2;
                }
                .custom-banner-subtitle {
                    position: absolute;
                    top: 55%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 90%;
                    margin: 0;
                    line-height: 1.4;
                }
                @media screen and (max-width: 767px) {
                    .custom-banner-wrapper {
                        min-height: 300px;
                    }
                    .custom-banner-image {
                        min-height: 300px;
                        object-fit: cover;
                    }
                    .custom-banner-title {
                        font-size: 1.5em !important;
                        width: 85%;
                        top: 35%;
                        word-wrap: break-word;
                        overflow-wrap: break-word;
                    }
                    .custom-banner-subtitle {
                        font-size: 0.9em !important;
                        width: 85%;
                        top: 60%;
                        word-wrap: break-word;
                        overflow-wrap: break-word;
                    }
                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                @media screen and (min-width: 768px) and (max-width: 991px) {
                    .custom-banner-wrapper {
                        min-height: 350px;
                    }
                    .custom-banner-image {
                        min-height: 350px;
                    }
                    .custom-banner-title {
                        font-size: 1.8em !important;
                        width: 88%;
                    }
                    .custom-banner-subtitle {
                        font-size: 1em !important;
                        width: 88%;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                        @if($hasVideoBackground)
                        #{{ $componentId }} .inner-section-video-layer {
                            position: absolute;
                            inset: 0;
                            overflow: hidden;
                            z-index: 0;
                        }
                        #{{ $componentId }} .inner-section-video-layer video,
                        #{{ $componentId }} .inner-section-video-layer iframe {
                            position: absolute;
                            inset: 0;
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                        #{{ $componentId }} .content-wrapper,
                        #{{ $componentId }} .row,
                        #{{ $componentId }} .nested-component,
                        #{{ $componentId }} .animated-column {
                            position: relative;
                            z-index: 1;
                        }
                        #{{ $componentId }} .inner-section-video-overlay {
                            position: absolute;
                            inset: 0;
                            background: linear-gradient(180deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.35) 100%);
                        }
                        @endif
            </style>
            <div class="custom-banner-wrapper" style="text-align:{{ $banner['textAlign'] ?? 'center' }};{{ $styleStr }}">
                @if(!empty($banner['imgSrc']))
                    <img src="{{ $banner['imgSrc'] }}" class="custom-banner-image" alt="Banner Image">
                @endif
                @if(!empty($banner['title']))
                    <h3 style="color:{{ $banner['titleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['titleFontSize'] ?? '2em' }};
                        text-align:{{ $banner['textAlign'] ?? 'center' }};" class="custom-banner-title">
                        {{ $banner['title'] }}
                    </h3>
                @endif
                @if(!empty($banner['subtitle']))
                    <p style="color:{{ $banner['subtitleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }};
                        text-align:{{ $banner['textAlign'] ?? 'center' }};
                        margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }};" class="custom-banner-subtitle">
                        {{ $banner['subtitle'] }}
                    </p>
                @endif
            </div>
        @break

        @case('donor-list')
            <div style="{{ $styleStr }}">
                <div class="col-12 mt-4 donor-list-component">
                    <div class="col-12 mt-4">
                        <div id="donorTable_wrapper" class="dataTables_wrapper no-footer">
                            <table id="donorTable" class="display table dataTable no-footer" role="grid">
                                <tbody>
                                    @php
                                        $url = url()->current();
                                        $domain = parse_url($url, PHP_URL_HOST);
                                        $check = \App\Models\Website::where('domain', $domain)->first();
                                        $website = \App\Models\Website::where('domain', $domain)->first();
                                    @endphp
                                    @if($website)
                                        @php
                                            $donations = \App\Models\Donation::where('website_id', $website->id)->with('user')->where('status', 1)->get();
                                        @endphp
                                        @if($donations && count($donations) > 0)
                                            @foreach($donations as $donation)
                                                <tr class="grid" role="row">
                                                    <td class="grid-data">
                                                        <div class="non-float">
                                                            <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">{{ $donation->amount }}</p>
                                                            <p style="color: #f4f3f0; font-size: 12px; margin-bottom: 0;">
                                                                @php
                                                                    $firstName = $donation->user->name ?? 'Anonymous';
                                                                    $words = explode(' ', $firstName);
                                                                    $displayName = $words[0];
                                                                    if (isset($words[1])) {
                                                                        $displayName .= ' ' . substr($words[1], 0, 1) . '.';
                                                                    }
                                                                    echo $displayName;
                                                                @endphp
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="grid" role="row">
                                                <td class="grid-data">
                                                    <div class="non-float">
                                                        <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">No donations found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr class="grid" role="row">
                                            <td class="grid-data">
                                                <div class="non-float">
                                                    <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">Website not found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('faq')
            @php
                // Debug: Log the component data structure
                error_log("FAQ COMPONENT DEBUG: " . json_encode($component));
                
                $faqData = $component['_faqData'] ?? $component['faqData'] ?? [
                    'questions' => [],
                    'questionBackgroundColor' => '#f3f4f6',
                    'questionTextColor' => '#1f2937',
                    'answerBackgroundColor' => '#ffffff',
                    'answerTextColor' => '#374151',
                    'iconColor' => '#059669',
                    'borderRadius' => '8px',
                    'spacing' => '10px'
                ];
                
                // Debug: Log the extracted FAQ data
                error_log("FAQ DATA EXTRACTED: " . json_encode($faqData));
            @endphp
            <div id="{{ $componentId }}" class="faq-component" style="{{ $styleStr }}">
                @php
                    error_log("FAQ QUESTIONS CHECK: " . (empty($faqData['questions']) ? 'EMPTY' : 'HAS DATA'));
                    error_log("FAQ QUESTIONS COUNT: " . count($faqData['questions'] ?? []));
                @endphp
                @if(!empty($faqData['questions']))
                    <div class="faq-container" style="max-width: 100%;">
                        @foreach($faqData['questions'] as $index => $item)
                            <div class="faq-item" style="
                                margin-bottom: {{ $faqData['spacing'] }};
                                border-radius: {{ $faqData['borderRadius'] }};
                                overflow: hidden;
                                border: 1px solid #e5e7eb;
                            ">
                                <div class="faq-question" style="
                                    background-color: {{ $faqData['questionBackgroundColor'] }};
                                    color: {{ $faqData['questionTextColor'] }};
                                    padding: 16px 20px;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 500;
                                    font-size: 16px;
                                    user-select: none;
                                " onclick="toggleFrontendFaqItem(this, {{ $index }})">
                                    <span>{{ $item['question'] ?? 'Question' }}</span>
                                    <div class="faq-icon" style="
                                        width: 32px;
                                        height: 32px;
                                        border-radius: 50%;
                                        background-color: {{ $faqData['iconColor'] }};
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                        font-weight: bold;
                                        font-size: 18px;
                                        flex-shrink: 0;
                                        margin-left: 15px;
                                    ">+</div>
                                </div>
                                <div class="faq-answer" style="
                                    background-color: {{ $faqData['answerBackgroundColor'] }};
                                    color: {{ $faqData['answerTextColor'] }};
                                    padding: 0 20px;
                                    max-height: 0;
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    font-size: 15px;
                                    line-height: 1.6;
                                ">{{ $item['answer'] ?? 'Answer' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 40px; text-align: center; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">No FAQ questions added yet.</p>
                        <!-- DEBUG INFO -->
                        <details style="margin-top: 10px; text-align: left;">
                            <summary style="cursor: pointer; color: #059669;">Debug Info (Remove in production)</summary>
                            <pre style="background: #1f2937; color: #fff; padding: 10px; margin-top: 10px; font-size: 12px; overflow-x: auto;">
Component Data: {{ json_encode($component, JSON_PRETTY_PRINT) }}

FAQ Data: {{ json_encode($faqData, JSON_PRETTY_PRINT) }}

Questions Empty: {{ empty($faqData['questions']) ? 'YES' : 'NO' }}
Questions Count: {{ count($faqData['questions'] ?? []) }}
                            </pre>
                        </details>
                    </div>
                @endif
            </div>
            
            <script>
                function toggleFrontendFaqItem(questionElement, index) {
                    const faqItem = questionElement.closest('.faq-item');
                    const answerElement = faqItem.querySelector('.faq-answer');
                    const iconElement = questionElement.querySelector('.faq-icon');
                    const faqContainer = questionElement.closest('.faq-container');
                    
                    // Close all other items (accordion behavior)
                    if (faqContainer) {
                        const allItems = faqContainer.querySelectorAll('.faq-item');
                        allItems.forEach((item, i) => {
                            if (i !== index) {
                                const otherAnswer = item.querySelector('.faq-answer');
                                const otherIcon = item.querySelector('.faq-icon');
                                otherAnswer.style.maxHeight = '0';
                                otherAnswer.style.padding = '0 20px';
                                otherIcon.textContent = '+';
                            }
                        });
                    }
                    
                    // Toggle current item
                    const isExpanded = answerElement.style.maxHeight !== '0px' && answerElement.style.maxHeight !== '';
                    
                    if (isExpanded) {
                        answerElement.style.maxHeight = '0';
                        answerElement.style.padding = '0 20px';
                        iconElement.textContent = '+';
                    } else {
                        answerElement.style.maxHeight = '1000px';
                        answerElement.style.padding = '20px';
                        iconElement.textContent = '−';
                    }
                }
            </script>
        @break

        @case('simple-comments')
            @php
                $simpleCommentsData = $component['_simpleCommentsData'] ?? $component['simpleCommentsData'] ?? [
                    'title' => 'Comments',
                    'showTitle' => true,
                    'allowAnonymous' => true,
                    'moderationEnabled' => false,
                    'requireEmail' => true,
                    'maxComments' => 100,
                    'sortOrder' => 'newest',
                    'backgroundColor' => '#ffffff',
                    'borderColor' => '#e0e0e0',
                    'textColor' => '#333333',
                    'buttonColor' => '#007bff'
                ];
                
                // Get the current page identifier and component ID for comments
                $pageIdentifier = request()->path();
                $componentId = $componentId ?? 'comments-' . uniqid();
                
                // Get website_id from context (available as $check variable)
                $websiteId = isset($check) ? $check->id : (isset($website) ? $website->id : null);
                
                // If no website_id available, try to get from URL or set a default
                if (!$websiteId) {
                    // Try to get website by domain from request
                    $domain = request()->getHost();
                    $websiteFromDomain = \App\Models\Website::where('domain', $domain)->first();
                    $websiteId = $websiteFromDomain ? $websiteFromDomain->id : '1'; // fallback to website ID 1
                }
                
                // Fetch existing comments for this component - with error handling
                $existingComments = collect();
                try {
                    if (class_exists('\App\Models\PageComment') && $websiteId) {
                        $existingComments = \App\Models\PageComment::where('page_identifier', $pageIdentifier)
                            ->where('component_id', $componentId)
                            ->where('website_id', $websiteId)
                            ->where('is_approved', true)
                            ->whereNull('parent_id')
                            ->with(['replies' => function($query) use ($websiteId) {
                                $query->where('is_approved', true)
                                      ->orderBy('created_at', 'asc');
                            }])
                            ->orderBy('created_at', $simpleCommentsData['sortOrder'] === 'newest' ? 'desc' : 'asc')
                            ->limit($simpleCommentsData['maxComments'])
                            ->get();
                    }
                } catch (\Exception $e) {
                    // If there's any error fetching comments, just use empty collection
                    $existingComments = collect();
                }
            @endphp
            
            <div id="{{ $componentId }}" class="simple-comments-component" style="{{ $styleStr }}">
                <div style="
                    background: {{ $simpleCommentsData['backgroundColor'] }};
                    border: 1px solid {{ $simpleCommentsData['borderColor'] }};
                    border-radius: 8px;
                    padding: 20px;
                    color: {{ $simpleCommentsData['textColor'] }};
                ">
                    @if($simpleCommentsData['showTitle'])
                        <h3 style="margin: 0 0 20px 0; color: {{ $simpleCommentsData['textColor'] }};">
                            {{ $simpleCommentsData['title'] }}
                        </h3>
                    @endif
                    
                    <!-- Comment Form -->
                    <form id="comment-form-{{ $componentId }}" class="comment-form" style="margin-bottom: 30px;">
                        @csrf
                        <input type="hidden" name="page_identifier" value="{{ $pageIdentifier }}">
                        <input type="hidden" name="component_id" value="{{ $componentId }}">
                        <input type="hidden" name="website_id" value="{{ $websiteId }}">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <input type="text" name="author_name" placeholder="Your Name" required
                                       style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px;">
                            </div>
                            @if($simpleCommentsData['requireEmail'])
                                <div>
                                    <input type="email" name="author_email" placeholder="Your Email" required
                                           style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px;">
                                </div>
                            @endif
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <textarea name="comment" placeholder="Write your comment..." required
                                      style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px; min-height: 100px; resize: vertical;"></textarea>
                        </div>
                        
                        @if($simpleCommentsData['allowAnonymous'])
                            <div style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; font-size: 14px;">
                                    <input type="checkbox" name="is_anonymous" value="1" style="margin-right: 8px;">
                                    Post as Anonymous
                                </label>
                            </div>
                        @endif
                        
                        <button type="submit" style="
                            background: {{ $simpleCommentsData['buttonColor'] }};
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 16px;
                        ">
                            Post Comment
                        </button>
                        
                        @if($simpleCommentsData['moderationEnabled'])
                            <p style="font-size: 12px; color: #666; margin-top: 10px;">
                                <i class="fas fa-info-circle"></i> Comments are moderated and may take some time to appear.
                            </p>
                        @endif
                    </form>
                    
                    <!-- Comments List -->
                    <div id="comments-list-{{ $componentId }}" class="comments-list">
                        @if($existingComments->count() > 0)
                            @foreach($existingComments as $comment)
                                <div class="comment-item" style="
                                    background: #f8f9fa;
                                    border-left: 4px solid {{ $simpleCommentsData['buttonColor'] }};
                                    padding: 15px;
                                    border-radius: 4px;
                                    margin-bottom: 15px;
                                ">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                        <strong style="color: {{ $simpleCommentsData['textColor'] }};">
                                            {{ $comment->is_anonymous ? 'Anonymous' : $comment->author_name }}
                                        </strong>
                                        <small style="color: #666;">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p style="margin: 0; color: {{ $simpleCommentsData['textColor'] }}; line-height: 1.5;">
                                        {{ $comment->comment }}
                                    </p>
                                    
                                    <!-- Replies -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div style="margin-top: 15px; padding-left: 20px; border-left: 2px solid #dee2e6;">
                                            @foreach($comment->replies as $reply)
                                                <div style="
                                                    background: #ffffff;
                                                    padding: 10px;
                                                    border-radius: 4px;
                                                    margin-bottom: 10px;
                                                    border: 1px solid {{ $simpleCommentsData['borderColor'] }};
                                                ">
                                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                            <strong style="color: {{ $simpleCommentsData['textColor'] }}; font-size: 14px;">
                                                                {{ $reply->is_anonymous ? 'Anonymous' : $reply->author_name }}
                                                            </strong>
                                                            @if($reply->is_admin_reply ?? false)
                                                                <span style="
                                                                    background: #007bff;
                                                                    color: white;
                                                                    font-size: 10px;
                                                                    padding: 2px 6px;
                                                                    border-radius: 12px;
                                                                    font-weight: bold;
                                                                ">ADMIN</span>
                                                            @endif
                                                        </div>
                                                        <small style="color: #666; font-size: 12px;">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p style="margin: 0; color: {{ $simpleCommentsData['textColor'] }}; font-size: 14px; line-height: 1.4;">
                                                        {{ $reply->comment }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div style="
                                text-align: center;
                                padding: 40px 20px;
                                color: #666;
                                font-style: italic;
                            ">
                                <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; color: #ccc;"></i>
                                <p>No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('comment-form-{{ $componentId }}').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    
                    // Handle checkbox properly - if not checked, explicitly set to false
                    const anonymousCheckbox = this.querySelector('input[name="is_anonymous"]');
                    if (anonymousCheckbox && !anonymousCheckbox.checked) {
                        formData.set('is_anonymous', '0');
                    }
                    
                    // Show loading state
                    submitButton.textContent = 'Posting...';
                    submitButton.disabled = true;
                    
                    // Get CSRF token - try multiple methods
                    let csrfToken = '';
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfInput = document.querySelector('input[name="_token"]');
                    
                    if (csrfMeta) {
                        csrfToken = csrfMeta.getAttribute('content');
                    } else if (csrfInput) {
                        csrfToken = csrfInput.value;
                    } else {
                        // Get token from the form's CSRF input
                        const formCsrf = this.querySelector('input[name="_token"]');
                        if (formCsrf) {
                            csrfToken = formCsrf.value;
                        }
                    }
                    
                    // Prepare headers
                    const headers = {};
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                    
                    fetch('/comments', {
                        method: 'POST',
                        body: formData,
                        headers: headers
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset form
                            this.reset();
                            
                            // Show success message
                            const successMsg = document.createElement('div');
                            successMsg.style.cssText = 'background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;';
                            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                            this.parentNode.insertBefore(successMsg, this.nextSibling);
                            
                            // Remove success message after 6 seconds
                            setTimeout(() => successMsg.remove(), 6000);
                            
                            // Reload comments if not moderated
                            @if(!$simpleCommentsData['moderationEnabled'])
                                setTimeout(() => location.reload(), 1000);
                            @endif
                        } else {
                            throw new Error(data.message || 'Failed to post comment');
                        }
                    })
                    .catch(error => {
                        console.error('Comment submission error:', error);
                        // Show error message
                        const errorMsg = document.createElement('div');
                        errorMsg.style.cssText = 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;';
                        errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + (error.message || 'Failed to submit comment. Please try again.');
                        this.parentNode.insertBefore(errorMsg, this.nextSibling);
                        
                        // Remove error message after 6 seconds
                        setTimeout(() => errorMsg.remove(), 6000);
                    })
                    .finally(() => {
                        // Reset button state
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    });
                });
            </script>
        @break

        @case('disqus')
            @php
                $disqusData = $component['_disqusData'] ?? $component['disqusData'] ?? [
                    'shortname' => '',
                    'identifier' => '',
                    'title' => '',
                    'url' => '',
                    'showInPreview' => true
                ];
                
                // Fallback to empty if no shortname provided
                if (empty($disqusData['shortname'])) {
                    echo '<div style="padding: 40px 20px; text-align: center; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; color: #6c757d;">
                        <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; color: #adb5bd;"></i>
                        <h4 style="margin: 0 0 8px 0; color: #495057;">Disqus Comments</h4>
                        <p style="margin: 0; font-size: 14px;">Configure your Disqus shortname to enable comments.</p>
                    </div>';
                    return;
                }
            @endphp
            
            <div id="{{ $componentId }}" class="disqus-component" style="{{ $styleStr }}">
                <div id="disqus_thread_{{ $componentId }}"></div>
                
                <script>
                    (function() {
                        // Generate unique identifier for this instance
                        const componentId = '{{ $componentId }}';
                        const disqusThread = document.getElementById('disqus_thread_' + componentId);
                        
                        // Disqus configuration variables
                        var disqus_config = function () {
                            @if(!empty($disqusData['identifier']))
                                this.page.identifier = '{{ $disqusData['identifier'] }}';
                            @else
                                this.page.identifier = window.location.pathname;
                            @endif
                            
                            @if(!empty($disqusData['url']))
                                this.page.url = '{{ $disqusData['url'] }}';
                            @else
                                this.page.url = window.location.href;
                            @endif
                            
                            @if(!empty($disqusData['title']))
                                this.page.title = '{{ addslashes($disqusData['title']) }}';
                            @else
                                this.page.title = document.title;
                            @endif
                        };
                        
                        // Load Disqus script
                        var d = document, s = d.createElement('script');
                        s.src = 'https://{{ $disqusData['shortname'] }}.disqus.com/embed.js';
                        s.setAttribute('data-timestamp', +new Date());
                        
                        // Override DISQUS global to use our specific thread container
                        window.DISQUS = window.DISQUS || {};
                        const originalReset = window.DISQUS.reset;
                        
                        s.onload = function() {
                            // If Disqus is already loaded, reset it for this container
                            if (window.DISQUS && window.DISQUS.reset) {
                                window.DISQUS.reset({
                                    reload: true,
                                    config: disqus_config
                                });
                            }
                        };
                        
                        (d.head || d.body).appendChild(s);
                        
                        // Set the container for Disqus to use
                        window.disqus_container_id = 'disqus_thread_' + componentId;
                    })();
                </script>
                
                <noscript>
                    <div style="padding: 20px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; text-align: center; color: #6c757d;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        Please enable JavaScript to view the 
                        <a href="https://disqus.com/?ref_noscript" style="color: #007bff;">comments powered by Disqus.</a>
                    </div>
                </noscript>
            </div>
        @break

        @case('invest-cta')
           <div style="max-width: 100%; overflow: hidden; {{ $styleStr }}">
               {!! $component['html'] !!}
           </div>
        @break

        @case('investment-tier')
            @php
                $tierData = $component['investmentTierData'] ?? [];
                $tierName = $tierData['tierName'] ?? 'TIER 1';
                $tierPrice = $tierData['tierPrice'] ?? '$2,500';
                $tierDescription = $tierData['tierDescription'] ?? 'Investment tier description';
                $buttonText = $tierData['buttonText'] ?? 'INVEST NOW';
                $buttonUrl = $tierData['buttonUrl'] ?? '#';
                $buttonTarget = $tierData['buttonTarget'] ?? '_self';
                $backgroundColor = $tierData['backgroundColor'] ?? '#1a1a1a';
                $backgroundImage = $tierData['backgroundImage'] ?? '';
                $backgroundType = $tierData['backgroundType'] ?? 'color';
                $textColor = $tierData['textColor'] ?? '#ffffff';
                $buttonBgColor = $tierData['buttonBgColor'] ?? '#28a745';
                $buttonTextColor = $tierData['buttonTextColor'] ?? '#ffffff';
                $borderRadius = $tierData['borderRadius'] ?? '12px';
                $padding = $tierData['padding'] ?? '2rem';
                
                // Build background style based on type
                $backgroundStyle = '';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    $backgroundStyle = "background-image: linear-gradient(359deg,#000000a3,#000),url({$imageUrl}); background-position: 0 0,50%; background-size: auto,cover;";
                } else {
                    $backgroundStyle = "background-color: {$backgroundColor};";
                }
                
                // Temporary debug - remove after testing
                if ($backgroundType === 'image') {
                    error_log("INVESTMENT TIER FRONTEND DEBUG: Type={$backgroundType}, Image={$backgroundImage}, Style={$backgroundStyle}");
                }
                
                // Temporary debug - remove after testing
                if ($backgroundType === 'image') {
                    error_log("INVESTMENT TIER FRONTEND DEBUG: Type={$backgroundType}, Image={$backgroundImage}, Style={$backgroundStyle}");
                }
            @endphp
            {!! $component['html'] !!}
        @break

        @case('feature-grid')
            @php
                // Debug feature-grid component
                error_log("FEATURE GRID DEBUG: Component received");
                
                // Try multiple data sources
                $featureGridData = $component['featureGridData'] ?? [];
                $features = $featureGridData['features'] ?? [];
                
                // If no features in featureGridData, check if features are directly in component
                if (empty($features) && isset($component['features'])) {
                    $features = $component['features'];
                }
                
                // Get colors
                $iconColor = $featureGridData['iconColor'] ?? '#000000';
                $titleColor = $featureGridData['titleColor'] ?? '#1f2937';
                $descriptionColor = $featureGridData['descriptionColor'] ?? '#000000';
                
                error_log("FEATURE GRID DEBUG: Features count = " . count($features));
                error_log("FEATURE GRID DEBUG: Icon color = " . $iconColor);
                error_log("FEATURE GRID DEBUG: featureGridData = " . json_encode($featureGridData));
                
                // If still no features but we have html, fall back to html
                if (empty($features) && isset($component['html']) && !empty($component['html'])) {
                    error_log("FEATURE GRID DEBUG: Falling back to HTML content");
                    echo $component['html'];
                    break;
                }
            @endphp
            
            @if(count($features) > 0)
                <div class="feature-grid-frontend row" style="{{ $styleStr }}">
                    @foreach($features as $index => $feature)
                        <div class="feature-item col-md-6" style="display: block;">
                            <div class="feature-icon" style="width: 48px; height: 48px; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <i class="{{ $feature['icon'] ?? 'fas fa-star' }}" style="font-size: 24px;"></i>
                            </div>
                            <div class="feature-content">
                                <h3 class="feature-title" style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: {{ $titleColor }};">{{ $feature['title'] ?? 'Feature Title' }}</h3>
                                <p class="feature-description" style="margin: 0; color: {{ $descriptionColor }}; line-height: 1.5;">{{ $feature['description'] ?? 'Feature description' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <style>
                    @media (max-width: 768px) {
                        .feature-grid-frontend {
                            grid-template-columns: 1fr !important;
                            gap: 1.5rem !important;
                            padding: 1rem !important;
                        }

                        .inner-section-frontend {
                        padding: 0 !important;
                    }
                    }
                </style>
            @else
                {{-- Fallback: Use HTML content if available --}}
                @if(isset($component['html']) && !empty($component['html']))
                    {!! $component['html'] !!}
                @else
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">Feature Grid: No features data found</p>
                        <p style="color: #9ca3af; font-size: 0.875rem; margin: 0.5rem 0 0 0;">featureGridData: {{ json_encode($featureGridData) }}</p>
                    </div>
                @endif
            @endif
        @break

        @case('numbered-timeline')
            @php
                $timelineData = $component['timelineData'] ?? [];
                $items = $timelineData['items'] ?? [];
                $colors = $timelineData['colors'] ?? [];
                $numberBackground = $colors['numberBackground'] ?? '#22c55e';
                $numberText = $colors['numberText'] ?? '#22c55e';
                $titleColor = $colors['titleColor'] ?? '#22c55e';
                $descriptionColor = $colors['descriptionColor'] ?? '#374151';
                $lineColor = $colors['lineColor'] ?? '#22c55e';
                
                // Completion status colors
                $completedBackground = $colors['completedBackground'] ?? '#22c55e';
                $uncompletedBackground = $colors['uncompletedBackground'] ?? '#e5e7eb';
                $completedText = $colors['completedText'] ?? '#ffffff';
                $uncompletedText = $colors['uncompletedText'] ?? '#9ca3af';
                $completedLineColor = $colors['completedLineColor'] ?? '#22c55e';
                $uncompletedLineColor = $colors['uncompletedLineColor'] ?? '#e5e7eb';
            @endphp
            
            <style>
                .numbered-timeline-container {
                    position: relative;
                    max-width: 100%;
                    margin: 0 auto;
                }
                
                .timeline-item {
                    display: flex;
                    align-items: flex-start;
                    margin-bottom: 3rem;
                    position: relative;
                }
                
                .timeline-number {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 18px;
                    margin-right: 1.5rem;
                    flex-shrink: 0;
                    position: relative;
                    z-index: 3;
                    transition: all 0.3s ease;
                }
                
                .timeline-content {
                    flex: 1;
                    padding-top: 8px;
                }
                
                .timeline-line {
                    position: absolute;
                    left: 24px;
                    top: 50px;
                    width: 2px;
                    height: calc(100% + 1rem);
                    z-index: 1;
                    transition: background-color 0.3s ease;
                }

                
                
                /* Desktop layout */
                @media (min-width: 769px) {
                    .numbered-timeline {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 2rem;
                        position: relative;
                    }
                    
                    .timeline-column {
                        flex: 1;
                        min-width: 250px;
                        position: relative;
                    }
                    
                    /* Hide lines after every 4th item on desktop */
                    .timeline-line.desktop-hidden {
                        display: none !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                
                /* Mobile layout - single column with continuous line */
                @media (max-width: 768px) {
                    .numbered-timeline {
                        display: block !important;
                        position: relative;
                    }
                    
                    .timeline-column {
                        width: 100% !important;
                        min-width: auto !important;
                    }
                    
                    /* Show individual lines on mobile based on completion status */
                    .timeline-line {
                        display: block !important; /* Show lines on mobile */
                        position: absolute;
                        left: 24px;
                        top: 50px;
                        width: 2px;
                        height: calc(100% + 1rem);
                        z-index: 1;
                        transition: background-color 0.3s ease;
                    }
                    
                    /* Override desktop-hidden class on mobile - show all lines */
                    .timeline-line.desktop-hidden {
                        display: block !important;
                    }
                    
                    /* Hide the continuous line approach */
                    .numbered-timeline::before {
                        display: none;
                    }
                    
                    .timeline-item {
                        margin-bottom: 2.5rem;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>
            
            <div class="numbered-timeline-container" style="{{ $styleStr }}">
                <div class="numbered-timeline">
                    @php
                        $itemsPerColumn = 4;
                        $columns = array_chunk($items, $itemsPerColumn);
                    @endphp
                    @foreach($columns as $columnIndex => $column)
                        <div class="timeline-column">
                            @foreach($column as $index => $item)
                                @php
                                    $globalIndex = $columnIndex * $itemsPerColumn + $index;
                                    $isCompleted = $item['completed'] ?? true; // Default to completed if not specified
                                    $isLastInColumn = $index === count($column) - 1;
                                    $isLastOverall = $globalIndex === count($items) - 1;
                                    
                                    // Desktop: Hide line after every 4th item (end of column)
                                    $isDesktopColumnEnd = ($globalIndex + 1) % $itemsPerColumn === 0;
                                    
                                    // Check if next item exists and determine line logic
                                    $showCompletedLine = false;
                                    $showIncompleteLine = false;
                                    
                                    if (!$isLastOverall) {
                                        $nextGlobalIndex = $globalIndex + 1;
                                        if ($nextGlobalIndex < count($items)) {
                                            $nextItemCompleted = $items[$nextGlobalIndex]['completed'] ?? true;
                                            
                                            if ($isCompleted && $nextItemCompleted) {
                                                // Both current and next are completed - show completed line
                                                $showCompletedLine = true;
                                            } else if ($isCompleted && !$nextItemCompleted) {
                                                // Current is completed, next is not - show incomplete line to connect to remaining items
                                                $showIncompleteLine = true;
                                            } else if (!$isCompleted) {
                                                // Current is incomplete - show incomplete line to next item (if not last)
                                                $showIncompleteLine = true;
                                            }
                                        }
                                    }
                                    
                                    // Determine colors based on completion status
                                    $bgColor = $isCompleted ? $completedBackground : $uncompletedBackground;
                                    $textColor = $isCompleted ? $completedText : $uncompletedText;
                                @endphp
                                <div class="timeline-item">
                                    <div class="timeline-number" style="
                                        background: {{ $bgColor }};
                                        color: {{ $textColor }};
                                        {{ !$isCompleted ? 'border: 2px solid ' . $uncompletedBackground . ';' : '' }}
                                    ">
                                        {{ $item['number'] ?? ($globalIndex + 1) }}
                                    </div>
                                    <div class="timeline-content">
                                        <h3 style="color: {{ $isCompleted ? $titleColor : $uncompletedText }}; margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;">
                                            {{ $item['title'] ?? 'Timeline Item' }}
                                        </h3>
                                        <p style="color: {{ $isCompleted ? $descriptionColor : $uncompletedText }}; margin: 0; line-height: 1.6; opacity: {{ $isCompleted ? '1' : '0.7' }};">
                                            {{ $item['description'] ?? 'Timeline description' }}
                                        </p>
                                        @if(isset($item['status']) && !empty($item['status']))
                                            <div class="timeline-status" style="
                                                margin-top: 0.5rem;
                                                padding: 4px 12px;
                                                border-radius: 12px;
                                                font-size: 12px;
                                                font-weight: 500;
                                                display: inline-block;
                                                background: {{ $isCompleted ? 'rgba(34, 197, 94, 0.1)' : 'rgba(156, 163, 175, 0.1)' }};
                                                color: {{ $isCompleted ? '#059669' : '#6b7280' }};
                                            ">
                                                {{ $item['status'] }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($showCompletedLine)
                                        <div class="timeline-line {{ $isDesktopColumnEnd ? 'desktop-hidden' : '' }}" style="background: {{ $completedLineColor }};"></div>
                                    @elseif($showIncompleteLine)
                                        <div class="timeline-line {{ $isDesktopColumnEnd ? 'desktop-hidden' : '' }}" style="background: {{ $uncompletedLineColor }};"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @break

        @case('investment-tier')
            @php
                $tierData = $component['investmentTierData'] ?? [];
                $tierName = $tierData['tierName'] ?? 'TIER 1';
                $tierPrice = $tierData['tierPrice'] ?? '$2,500';
                $tierDescription = $tierData['tierDescription'] ?? '';
                $receiveLabel = $tierData['receiveLabel'] ?? 'Receive';
                $buttonText = $tierData['buttonText'] ?? 'INVEST NOW';
                $buttonUrl = $tierData['buttonUrl'] ?? '#';
                $buttonTarget = $tierData['buttonTarget'] ?? '_self';
                $backgroundType = $tierData['backgroundType'] ?? 'color';
                $backgroundColor = $tierData['backgroundColor'] ?? '#f8f9fa';
                $backgroundImage = $tierData['backgroundImage'] ?? '';
                
                // Color fields with fallbacks
                $titleColor = $tierData['titleColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $priceColor = $tierData['priceColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $receiveLabelColor = $tierData['receiveLabelColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $descriptionColor = $tierData['descriptionColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $buttonBgColor = $tierData['buttonBgColor'] ?? '#28a745';
                $buttonTextColor = $tierData['buttonTextColor'] ?? '#ffffff';
                
                // Extract numeric value from tier price for URL parameter
                $numericPrice = preg_replace('/[^0-9.,]/', '', $tierPrice);
                $numericPrice = str_replace(',', '', $numericPrice);
                
                // Always redirect to /invest with amount parameter
                $buttonUrl = '/invest?amount=' . urlencode($numericPrice);
                
                $backgroundStyle = 'background-color: ' . $backgroundColor . ';';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    if (!empty($imageUrl)) {
                        $backgroundStyle = 'background: linear-gradient(0deg, rgba(0,0,0,0.85) 80%, rgba(0,0,0,0.85) 100%), url(\'' . $imageUrl . '\') center/cover no-repeat;';
                    }
                }
            @endphp
            
            {{-- Add specific CSS to override any responsive margin conflicts and ensure links work --}}
            <style>
                #{{ $componentId }} .investment-tier {
                    margin: 0 auto !important;
                    max-width: 370px !important;
                    position: relative !important;
                    z-index: 1 !important;
                }
                
                #{{ $componentId }} .investment-tier a {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 10 !important;
                    display: inline-block !important;
                }
                
                /* Ensure investment tier works in full-width sections */
                .inner-section-fullwidth #{{ $componentId }} .investment-tier,
                .inner-section-frontend #{{ $componentId }} .investment-tier {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 1 !important;
                }
                
                .inner-section-fullwidth #{{ $componentId }} .investment-tier a,
                .inner-section-frontend #{{ $componentId }} .investment-tier a {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 10 !important;
                }
            </style>
            
            <div class="investment-tier" style="{{ $backgroundStyle }} padding: 2rem; border-radius: 8px; text-align: center; margin: 0 auto !important; max-width: 370px;">
                <h2 style="color: {{ $titleColor }}; margin: 0 0 1rem 0; font-size: 2rem; font-weight: bold;">{{ $tierName }}</h2>
                <div style="font-size: 3rem; font-weight: bold; margin: 1rem 0; color: {{ $priceColor }};">{{ $tierPrice }}</div>
                @if($receiveLabel || $tierDescription)
                    <div style="margin: 1rem 0;">
                        @if($receiveLabel)
                            <div style="color: {{ $receiveLabelColor }}; font-weight: bold; font-size: 1.2rem; margin-bottom: 0.5rem;">{{ $receiveLabel }}</div>
                        @endif
                        @if($tierDescription)
                            <p style="color: {{ $descriptionColor }}; margin: 0; line-height: 1.6; font-size: 1.1rem;">{{ $tierDescription }}</p>
                        @endif
                    </div>
                @endif
                <a href="{{ $buttonUrl }}" target="{{ $buttonTarget }}" style="
                    display: inline-block; 
                    background: {{ $buttonBgColor }}; 
                    color: {{ $buttonTextColor }}; 
                    padding: 1rem 2rem; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    font-weight: bold; 
                    margin-top: 1rem; 
                    transition: background 0.3s ease;
                " onmouseover="this.style.background='{{ $buttonBgColor }}ee'" onmouseout="this.style.background='{{ $buttonBgColor }}'">
                    {{ $buttonText }}
                </a>
            </div>
        @break

        @case('section-title')
            @php
                // Try multiple data sources for backwards compatibility
                $sectionTitleData = $component['sectionTitleData'] ?? [];
                // Get rich HTML content from Quill editor or fallback to simple text
                $title = $sectionTitleData['title'] ?? $component['text'] ?? $component['textContent'] ?? $component['html'] ?? 'Section Title';
                $subtitle = $sectionTitleData['subtitle'] ?? '';
                $alignment = $sectionTitleData['alignment'] ?? $component['properties']['alignment'] ?? 'left';
                
                // Don't strip HTML tags - preserve Quill editor formatting including colors
                // Only clean if title contains suspicious content
                if (strpos($title, '<script') !== false || strpos($title, 'javascript:') !== false) {
                    $title = strip_tags($title);
                }
                
                // Check if this is rich HTML content from Quill editor
                $isRichContent = strip_tags($title) !== $title;
                
                // For fallback color when no Quill formatting is present
                $hasStyleColor = !empty($component['style']['color']);
                $titleColor = $hasStyleColor ? $component['style']['color'] : '#1f2937';
                $subtitleColor = $sectionTitleData['subtitleColor'] ?? '#6b7280';
                
                // Don't override colors in styleStr if content is rich HTML (Quill handles colors)
                $filteredStyleStr = $styleStr;
                if ($isRichContent && $hasStyleColor) {
                    $filteredStyleStr = preg_replace('/color\s*:[^;]+;?/', '', $styleStr);
                }
            @endphp
            <div class="section-title" style="margin: 2rem 0; {{ $filteredStyleStr }}">
                @if($isRichContent)
                    {{-- Rich HTML content from Quill editor - preserve all formatting including colors --}}
                    <div style="margin: 0 0 1rem 0;">{!! $title !!}</div>
                @else
                    {{-- Simple text content - apply fallback styling --}}
                    <h2 style="color: {{ $titleColor }}; margin: 0 0 1rem 0; font-size: 2.5rem; font-weight: bold;">{{ $title }}</h2>
                @endif
                @if($subtitle)
                    <p style="color: {{ $subtitleColor }}; margin: 0; font-size: 1.25rem; line-height: 1.6;">{{ $subtitle }}</p>
                @endif
            </div>
        @break

        @case('video')
            @php
                // Handle multiple data formats for video - check all possible locations
                $allComponentData = $component;
                $videoData = null;
                
                // Try different possible keys where video data might be stored
                if (isset($component['videoData'])) {
                    $videoData = $component['videoData'];
                } elseif (isset($component['_videoData'])) {
                    $videoData = $component['_videoData'];
                } elseif (isset($component['properties']['videoData'])) {
                    $videoData = $component['properties']['videoData'];
                } elseif (isset($component['content']['_videoData'])) {
                    $videoData = $component['content']['_videoData'];
                } else {
                    // NEW: Extract from HTML if videoData is missing
                    $videoData = [
                        'url' => '',
                        'type' => 'youtube',
                        'autoplay' => false,
                        'width' => null,
                        'height' => null
                    ];
                    
                    // Try to extract video info from HTML content
                    if (isset($component['html']) && !empty($component['html'])) {
                        $html = $component['html'];
                        
                        // Check for uploaded video (video tag with source)
                        if (preg_match('/<source\s+src="([^"]+)"/', $html, $matches)) {
                            $videoData['url'] = $matches[1];
                            $videoData['type'] = 'uploaded';
                            
                            // Check for autoplay
                            if (strpos($html, 'autoplay') !== false) {
                                $videoData['autoplay'] = true;
                            }
                            
                            // Try to extract width and height
                            if (preg_match('/width="([^"]+)"/', $html, $widthMatch)) {
                                $width = $widthMatch[1];
                                if (is_numeric($width)) {
                                    $videoData['width'] = (int)$width;
                                }
                            }
                            if (preg_match('/height="([^"]+)"/', $html, $heightMatch)) {
                                $height = $heightMatch[1];
                                if (is_numeric($height)) {
                                    $videoData['height'] = (int)$height;
                                }
                            }
                        }
                        // Check for YouTube iframe
                        elseif (preg_match('/<iframe[^>]+src="([^"]*youtube[^"]*)"/', $html, $matches)) {
                            $videoData['url'] = $matches[1];
                            $videoData['type'] = 'youtube';
                            
                            // Check for autoplay in iframe src
                            if (strpos($matches[1], 'autoplay=1') !== false) {
                                $videoData['autoplay'] = true;
                            }
                        }
                    }
                    
                    // Fallback: check direct component properties
                    if (empty($videoData['url'])) {
                        $videoData['url'] = $component['videoUrl'] ?? $component['src'] ?? $component['url'] ?? '';
                        $videoData['type'] = $component['videoType'] ?? $component['type'] ?? 'youtube';
                        $videoData['autoplay'] = $component['autoplay'] ?? false;
                        $videoData['width'] = $component['width'] ?? null;
                        $videoData['height'] = $component['height'] ?? null;
                    }
                }
                
                // Ensure videoData is an array
                if (!is_array($videoData)) {
                    $videoData = [];
                }
                
                $videoUrl = $videoData['url'] ?? '';
                $videoType = $videoData['type'] ?? 'youtube';
                $videoFormat = $videoData['videoFormat'] ?? 'mp4';
                $autoplay = $videoData['autoplay'] ?? false;
                $showControls = array_key_exists('controls', $videoData) ? (bool)$videoData['controls'] : true;
                $customWidth = isset($videoData['width']) && $videoData['width'] ? $videoData['width'] . 'px' : '100%';
                $customHeight = isset($videoData['height']) && $videoData['height'] ? $videoData['height'] . 'px' : 'auto';
                
                // Convert YouTube URLs to embed format 
                if ($videoType === 'youtube' && !empty($videoUrl)) {
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $autoplayParam = $autoplay ? '&autoplay=1&mute=1' : '';
                        $controlsParam = $showControls ? '&controls=1' : '&controls=0';
                        $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplayParam}{$controlsParam}";
                    } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $autoplayParam = $autoplay ? '&autoplay=1&mute=1' : '';
                        $controlsParam = $showControls ? '&controls=1' : '&controls=0';
                        $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplayParam}{$controlsParam}";
                    } else {
                        $embedUrl = $videoUrl;
                    }
                } else {
                    $embedUrl = $videoUrl;
                }
            @endphp
            
            {{-- Force responsive video styles --}}
            <style>
                #{{ $componentId }} .video-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    position: relative !important;
                    overflow: hidden !important;
                }
                
                #{{ $componentId }} .video-container iframe,
                #{{ $componentId }} .video-container video {
                    width: 100% !important;
                    height: auto !important;
                    max-width: 100% !important;
                    display: block !important;
                }
                
                /* Force override custom dimensions */
                #{{ $componentId }} .video-container[style] {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                
                #{{ $componentId }} .video-container[style] iframe,
                #{{ $componentId }} .video-container[style] video {
                    width: 100% !important;
                    height: auto !important;
                    max-width: 100% !important;
                }
                
                /* Mobile specific video fixes */
                @media (max-width: 768px) {
                    #{{ $componentId }} .video-container {
                        width: 100% !important;
                        height: auto !important;
                        padding-bottom: 56.25% !important;
                        position: relative !important;
                    }
                    
                    #{{ $componentId }} .video-container iframe,
                    #{{ $componentId }} .video-container video {
                        position: absolute !important;
                        top: 0 !important;
                        left: 0 !important;
                        width: 100% !important;
                        height: 100% !important;
                        min-height: 200px !important;
                        max-height: none !important;
                    }
                    
                    /* Force remove any custom dimensions on mobile */
                    #{{ $componentId }} .video-container[style*="width"],
                    #{{ $componentId }} .video-container[style*="height"] {
                        width: 100% !important;
                        height: auto !important;
                        padding-bottom: 56.25% !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>
            
            <div style="{{ $styleStr }}">
                @if($videoUrl)
                    @if($videoType === 'uploaded' || $videoType === 'custom')
                        <!-- Uploaded or Custom video file -->
                        <div class="video-container" style="width: {{ $customWidth }}; max-width: 100%; overflow: hidden;">
                            <video 
                                width="100%" 
                                height="{{ $customHeight === 'auto' ? 'auto' : $customHeight }}"
                                    @if($showControls) controls @endif
                                @if($autoplay) autoplay muted @endif 
                                style="display: block; {{ $styleStr }}"
                                preload="metadata"
                                playsinline
                                webkit-playsinline>
                                <source src="{{ $videoUrl }}" type="video/{{ $videoFormat }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <!-- YouTube video -->
                        <div class="video-container" style="width: {{ $customWidth }}; max-width: 100%; {{ $customHeight !== 'auto' ? 'height: ' . $customHeight . ';' : 'height: 0; padding-bottom: 56.25%;' }} position: relative; overflow: hidden;">
                            <iframe 
                                src="{{ $embedUrl }}" 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                            </iframe>
                        </div>
                    @endif
                @else
                    <div style="background: #f3f4f6; padding: 2rem; text-align: center; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">No video provided</p>
                        <details style="margin-top: 1rem; text-align: left;">
                            <summary style="cursor: pointer; color: #9ca3af;">Debug Info</summary>
                            <pre style="background: #374151; color: #f3f4f6; padding: 1rem; border-radius: 4px; font-size: 12px; overflow: auto;">All Component Data: {{ json_encode($allComponentData, JSON_PRETTY_PRINT) }}
                                
Extracted Video Data: {{ json_encode($videoData, JSON_PRETTY_PRINT) }}</pre>
                        </details>
                    </div>
                @endif
            </div>
        @break

        @case('alert-message')
            @php
                // Support both old alertData format and new properties format
                $alertData = $component['alertData'] ?? [];
                $properties = $component['properties'] ?? [];
                
                $message = $properties['message'] ?? $alertData['message'] ?? $component['html'] ?? $component['text'] ?? 'Alert message';
                $type = $properties['alert_type'] ?? $alertData['type'] ?? 'info';
                $dismissible = $properties['dismissible'] ?? $alertData['dismissible'] ?? false;
                
                // Custom styling from properties
                $backgroundColor = $properties['background_color'] ?? $style['backgroundColor'] ?? null;
                $textColor = $properties['text_color'] ?? $style['color'] ?? null;
                $borderColor = $properties['border_color'] ?? $style['borderColor'] ?? null;
                $borderRadius = $properties['border_radius'] ?? $style['borderRadius'] ?? '4px';
                $padding = $properties['padding'] ?? $style['padding'] ?? '1rem';
                $margin = $properties['margin'] ?? $style['margin'] ?? '1rem 0';
                $fontSize = $properties['font_size'] ?? $style['fontSize'] ?? '14px';
                $fontWeight = $properties['font_weight'] ?? $style['fontWeight'] ?? '400';
                
                // Default alert colors if no custom colors are set
                $alertColors = [
                    'success' => ['bg' => '#d4edda', 'text' => '#155724', 'border' => '#c3e6cb'],
                    'danger' => ['bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#f5c6cb'],
                    'warning' => ['bg' => '#fff3cd', 'text' => '#856404', 'border' => '#ffeaa7'],
                    'info' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'border' => '#bee5eb']
                ];
                $defaultColors = $alertColors[$type] ?? $alertColors['info'];
                
                // Use custom colors if provided, otherwise use defaults
                $finalBgColor = $backgroundColor ?? $defaultColors['bg'];
                $finalTextColor = $textColor ?? $defaultColors['text'];
                $finalBorderColor = $borderColor ?? $defaultColors['border'];
            @endphp
            <div class="alert alert-{{ $type }}" style="
                background-color: {{ $finalBgColor }}; 
                color: {{ $finalTextColor }}; 
                border: 1px solid {{ $finalBorderColor }}; 
                padding: {{ $padding }}; 
                border-radius: {{ $borderRadius }}; 
                margin: {{ $margin }};
                font-size: {{ $fontSize }};
                font-weight: {{ $fontWeight }};
                {{ $dismissible ? 'position: relative; padding-right: 3rem;' : '' }}
                {{ $styleStr }}
            ">
                {!! $message !!}
                @if($dismissible)
                    <button type="button" style="
                        position: absolute; 
                        right: 1rem; 
                        top: 50%; 
                        transform: translateY(-50%); 
                        background: none; 
                        border: none; 
                        font-size: 1.5rem; 
                        cursor: pointer; 
                        color: {{ $finalTextColor }};
                    " onclick="this.parentElement.style.display='none'">×</button>
                @endif
            </div>
        @break

        @case('press-card')
            @php
                $pressCardData = $component['pressCardData'] ?? [];
                $cards = $pressCardData['cards'] ?? [];
                $slidesToShow = $pressCardData['slidesToShow'] ?? 3;
                $autoplay = $pressCardData['autoplay'] ?? true;
                $autoplaySpeed = $pressCardData['autoplaySpeed'] ?? 3000;
                $sliderId = 'press-slider-' . ($componentId ?? uniqid());
                
                // Global styling options
                $cardBackgroundColor = $pressCardData['cardBackgroundColor'] ?? '#1a1a1a';
                $cardBorderRadius = $pressCardData['cardBorderRadius'] ?? '12px';
                $titleColor = $pressCardData['titleColor'] ?? '#ffffff';
                $dateColor = $pressCardData['dateColor'] ?? '#b0b0b0';
                $logoBackgroundColor = $pressCardData['logoBackgroundColor'] ?? '#2a2a2a';
                
                // Navigation arrows styling
                $arrowBackgroundColor = $pressCardData['arrowBackgroundColor'] ?? '#333333';
                $arrowColor = $pressCardData['arrowColor'] ?? '#ffffff';
                
                // Logo overlay filter
                $logoOverlay = $pressCardData['logoOverlay'] ?? 'brightness(0) invert(1)';
                
                // If no cards exist, create default one for backward compatibility
                if (empty($cards)) {
                    $cards = [[
                        'logoSrc' => $pressCardData['logoSrc'] ?? '',
                        'logoAlt' => $pressCardData['logoAlt'] ?? 'Press Logo',
                        'title' => $pressCardData['title'] ?? 'Press Article Title',
                        'url' => $pressCardData['url'] ?? '#',
                        'date' => $pressCardData['date'] ?? date('F j, Y'),
                        'target' => $pressCardData['target'] ?? '_blank'
                    ]];
                }
            @endphp
             
            <div class="press-cards-slider" style="{{ $styleStr }}">
                <style>
                    .press-cards-slider {
                        position: relative;
                        padding: 0 70px;
                    }
                    
                    #{{ $sliderId }}-wrapper {
                        position: relative;
                    }
                    
                    #{{ $sliderId }} .owl-nav {
                        display: none;
                    }
                    
                    .custom-owl-nav-{{ $sliderId }} {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 100%;
                        pointer-events: none;
                        z-index: 100;
                    }
                    
                    .custom-owl-nav-{{ $sliderId }} button {
                        position: absolute;
                        width: 50px;
                        height: 50px;
                        background: {{ $arrowBackgroundColor }} !important;
                        color: {{ $arrowColor }} !important;
                        border: none;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 28px;
                        font-weight: bold;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        opacity: 0.95;
                        pointer-events: all;
                        line-height: 1;
                        padding: 0;
                    }
                    
                    .custom-owl-nav-{{ $sliderId }} button:hover {
                        opacity: 1;
                        transform: scale(1.1);
                        background: {{ $arrowBackgroundColor }} !important;
                    }
                    
                    .custom-owl-nav-{{ $sliderId }} .custom-prev {
                        left: -60px;
                    }
                    
                    .custom-owl-nav-{{ $sliderId }} .custom-next {
                        right: -60px;
                    }
                    
                    #{{ $sliderId }} .owl-dots {
                        text-align: center;
                        margin-top: 30px;
                    }
                    
                    #{{ $sliderId }} .owl-dot {
                        width: 12px;
                        height: 12px;
                        margin: 0 6px;
                        background: #666;
                        border-radius: 50%;
                        display: inline-block;
                        transition: all 0.3s ease;
                    }
                    
                    #{{ $sliderId }} .owl-dot.active {
                        background: {{ $arrowColor }};
                        transform: scale(1.2);
                    }
                    
                    @media (max-width: 768px) {
                        .press-cards-slider {
                            padding: 0;
                        }
                        
                        .custom-owl-nav-{{ $sliderId }} {
                            display: none;
                        }

                        .inner-section-frontend {
                            padding: 0 !important;
                        }
                    }
                </style>
                
                <div id="{{ $sliderId }}-wrapper">
                    <div class="owl-carousel owl-theme" id="{{ $sliderId }}">
                    @foreach($cards as $card)
                        <div class="press-card-item">
                            <div class="press-card" style="
                                background: {{ $cardBackgroundColor }};
                                border-radius: {{ $cardBorderRadius }};
                                overflow: hidden;
                                transition: all 0.3s ease;
                                cursor: pointer;
                                height: 400px;
                                display: flex;
                                flex-direction: column;
                                margin: 0 10px;
                            ">
                                <!-- Press Logo Section -->
                                <div style="
                                    background: {{ $logoBackgroundColor }};
                                    padding: 30px 20px;
                                    text-align: center;
                                    flex: 1;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    @if(!empty($card['logoSrc']))
                                        <img src="{{ $card['logoSrc'] }}" 
                                             alt="{{ $card['logoAlt'] ?? 'Press Logo' }}" 
                                             style="max-width: 180px; max-height: 80px; filter: {{ $logoOverlay }};">
                                    @else
                                        <div style="
                                            width: 180px; 
                                            height: 60px; 
                                            background: rgba(255,255,255,0.1); 
                                            border: 2px dashed rgba(255,255,255,0.3); 
                                            border-radius: 4px; 
                                            display: flex; 
                                            align-items: center; 
                                            justify-content: center; 
                                            font-size: 12px; 
                                            color: rgba(255,255,255,0.5);
                                        ">Logo</div>
                                    @endif
                                </div>
                                
                                <!-- Press Content Section -->
                                <a href="{{ $card['url'] ?? '#' }}" 
                                   target="{{ $card['target'] ?? '_blank' }}" 
                                   style="
                                       display: block;
                                       text-decoration: none;
                                       color: inherit;
                                       padding: 25px 20px;
                                       border-top: 1px solid rgba(255,255,255,0.1);
                                   ">
                                    <div style="
                                        font-size: 16px;
                                        font-weight: 600;
                                        line-height: 1.4;
                                        color: {{ $titleColor }};
                                        margin-bottom: 15px;
                                        display: flex;
                                        align-items: flex-start;
                                        justify-content: space-between;
                                        gap: 10px;
                                        min-height: 44px;
                                    ">
                                        <span>{{ $card['title'] ?? 'Press Article Title' }}</span>
                                        <div style="
                                            width: 16px;
                                            height: 16px;
                                            flex-shrink: 0;
                                            margin-top: 2px;
                                            color: {{ $titleColor }};
                                            opacity: 0.7;
                                        ">
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                 width="100%" height="100%" 
                                                 viewBox="0 0 32 32" 
                                                 fill="currentColor">
                                                <path d="M10 6v2h12.59L6 24.59L7.41 26L24 9.41V22h2V6z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div style="
                                        color: {{ $dateColor }};
                                        font-size: 14px;
                                        font-weight: 400;
                                    ">{{ $card['date'] ?? date('F j, Y') }}</div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Custom Navigation Arrows -->
                <div class="custom-owl-nav-{{ $sliderId }}">
                    <button class="custom-prev" onclick="$('#{{ $sliderId }}').trigger('prev.owl.carousel')">‹</button>
                    <button class="custom-next" onclick="$('#{{ $sliderId }}').trigger('next.owl.carousel')">›</button>
                </div>
            </div>
            </div>
            
            <script>
                $(document).ready(function(){
                    $("#{{ $sliderId }}").owlCarousel({
                        items: {{ $slidesToShow }},
                        loop: {{ count($cards) > $slidesToShow ? 'true' : 'false' }},
                        margin: 0,
                        nav: false,
                        dots: true,
                        autoplay: {{ $autoplay ? 'true' : 'false' }},
                        autoplayTimeout: {{ $autoplaySpeed }},
                        autoplayHoverPause: true,
                        responsive: {
                            0: { 
                                items: 1
                            },
                            600: { 
                                items: {{ min(2, $slidesToShow) }}
                            },
                            1000: { 
                                items: {{ $slidesToShow }}
                            }
                        }
                    });
                });
            </script>
        @break
                        transition: opacity 0.3s ease;
                        pointer-events: none;
                    "></div>
                </div>
                
                <style>
                    .press-card-2:hover .black-overlay {
                        opacity: 1;
                        background: rgba(0,0,0,{{ number_format((float)$overlayOpacity + 0.1, 1) }});
                    }
                    
                    .press-card-2:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                    }
                    
                    .press-link:hover {
                        text-decoration: none !important;
                    }
                </style>
            </div>
        @break

        @case('text-images')
            @php
                $textImagesData = $component['textImagesData'] ?? [];
                $text = $textImagesData['text'] ?? 'Your text here';
                $imgSrc = $textImagesData['imgSrc'] ?? '';
                $imgPosition = $textImagesData['imgPosition'] ?? 'left';
                $imgSize = $textImagesData['imgSize'] ?? 200;
                $imgWidth = $textImagesData['imgWidth'] ?? $imgSize;
                $imgHeight = $textImagesData['imgHeight'] ?? 'auto';
                $showImage = $textImagesData['showImage'] ?? true;
                // Ensure width/height have px if numeric
                if (is_numeric($imgWidth)) {
                    $imgWidth = $imgWidth;
                }
                if (is_numeric($imgHeight)) {
                    $imgHeight = $imgHeight . 'px';
                }
            @endphp
            
            <div style="{{ $styleStr }}">
                @if($imgPosition === 'up')
                    <div class="row">
                        <div class="col-12">
                            @if($showImage && $imgSrc)
                                <div class="text-center mb-3">
                                    <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                                </div>
                            @endif
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                    </div>
                @elseif($imgPosition === 'down')
                    <div class="row">
                        <div class="col-12">
                            <div class="text-content mb-3">
                                {!! $text !!}
                            </div>
                            @if($showImage && $imgSrc)
                                <div class="text-center">
                                    <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($imgPosition === 'right')
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7 col-sm-12">
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                        @if($showImage && $imgSrc)
                            <div class="col-lg-6 col-md-5 col-sm-12 text-center">
                                <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Left position --}}
                    <div class="row align-items-center">
                        @if($showImage && $imgSrc)
                            <div class="col-lg-6 col-md-5 col-sm-12 text-center">
                                <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                            </div>
                        @endif
                        <div class="col-lg-6 col-md-7 col-sm-12">
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @break
                    {{-- <div style="flex:1;">
                        <div style="margin:0;">{!! $text !!}</div>
                    </div>
                </div>
            @endif --}}
        @break

        @case('auction-list')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $auction = \App\Models\Auction::where('website_id', $check->id ?? 1)->where('status',1)->latest()->get();
                
                // Generate unique component ID for responsive CSS
                $uniqueId = 'auction-list-' . uniqid();
                
                // Debug logging
                \Log::info('Auction List Component: Domain=' . $domain . ', Website ID=' . ($check->id ?? 'none') . ', Auction Count=' . $auction->count());
            @endphp
            
            @if($auction->count() > 0)
            <div class="auction-component-container {{ $uniqueId }}" style="{{ $wrapperStyleStr }}">
                <!-- Auction Component Styles -->
                <style>
                .{{ $uniqueId }} .auction-items-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                    padding: 20px 0;
                }
                
                .{{ $uniqueId }} .auction-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                    overflow: hidden;
                }
                
                .{{ $uniqueId }} .auction-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                }
                
                .{{ $uniqueId }} .c-node-ai__image {
                    position: relative;
                    padding-bottom: 60%;
                    overflow: hidden;
                }
                
                .{{ $uniqueId }} .c-node-ai__image img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                
                .{{ $uniqueId }} .c-node-ai__details-wrap {
                    padding: 15px;
                }
                
                .{{ $uniqueId }} .c-node-ai__title {
                    margin-bottom: 15px;
                    font-size: 1.2rem;
                    font-weight: bold;
                }
                
                .{{ $uniqueId }} .c-node-ai__title a {
                    text-decoration: none;
                    color: #333;
                }
                
                .{{ $uniqueId }} .c-node-ai__title a:hover {
                    color: #007bff;
                }
                
                .{{ $uniqueId }} .auction-details-layout {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 15px;
                    flex-wrap: wrap;
                }
                
                .{{ $uniqueId }} .auction-timer-section,
                .{{ $uniqueId }} .auction-price-section {
                    flex: 1;
                    min-width: 120px;
                }
                
                .{{ $uniqueId }} .c-timer__title {
                    font-size: 0.9rem;
                    color: #666;
                    margin-bottom: 8px;
                    font-weight: 500;
                }
                
                .{{ $uniqueId }} .c-timer__body {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }
                
                .{{ $uniqueId }} .c-timer__element {
                    text-align: center;
                    background: #f8f9fa;
                    padding: 8px 6px;
                    border-radius: 4px;
                    min-width: 50px;
                }
                
                .{{ $uniqueId }} .c-timer__value {
                    display: block;
                    font-weight: bold;
                    font-size: 1.1rem;
                    color: #333;
                }
                
                .{{ $uniqueId }} .c-timer__period {
                    display: block;
                    font-size: 0.8rem;
                    color: #666;
                    margin-top: 2px;
                }
                
                .{{ $uniqueId }} .c-price__title {
                    font-size: 0.9rem;
                    color: #666;
                    margin-bottom: 8px;
                    font-weight: 500;
                }
                
                .{{ $uniqueId }} .c-price__value {
                    font-size: 1.3rem;
                    font-weight: bold;
                    color: #28a745;
                    background: #e8f5e8;
                    padding: 8px 12px;
                    border-radius: 4px;
                    display: inline-block;
                }
                
                /* Mobile responsive */
                @media (max-width: 768px) {
                    .{{ $uniqueId }} .auction-items-grid {
                        grid-template-columns: 1fr;
                        gap: 15px;
                        padding: 15px 5px;
                    }
                    
                    .{{ $uniqueId }} .auction-details-layout {
                        flex-direction: column;
                        gap: 12px;
                    }
                    
                    .{{ $uniqueId }} .c-timer__body {
                        justify-content: center;
                    }
                    
                    .{{ $uniqueId }} .c-timer__element {
                        min-width: 45px;
                        padding: 6px 4px;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                </style>
                
                <div class="auction-main-wrapper">
                    <div class="auction-display-container" style="{{ $styleStr }}">
                        <div class="auction-items-wrapper">
                            <div class="auction-items-grid">
                                @foreach ($auction as $item)
                                <div class="auction-item-wrapper">
                                    <div class="auction-card">
                                        <div id="node-{{ $item->id }}"
                                            class="c-node-ai c-node-ai--teaser js-ai js-ai--teaser js-eq js-ai--teaser-view c-node-ai--teaser-view"
                                            about="/auction/{{ $item->id }}" typeof="sioc:Item foaf:Document"
                                            data-entity-id="{{ $item->id }}" data-unmet-reserve="0" data-live-id="{{ $item->id }}"
                                            data-updated="{{ \Carbon\Carbon::parse($item->updated_at)->timestamp }}"
                                            data-leader="{{ $item->leader_id ?? '' }}" data-status="bidding" data-lec="false"
                                            data-expiry="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}">
                                            <div class="c-node-ai__content">
                                                {{-- <div id="air-ai-status-indicator-{{ $item->id }}"
                                                    class="js-ai-status-indicator c-node-ai__status c-node-ai__status--teaser c-tooltip c-tooltip--n"
                                                    aria-label="Bidding is under way."></div> --}}
                                                <div class="c-node-ai__image-wrap">
                                                    <div class="c-node-ai__image">
                                                        <svg viewBox="0 0 100 100"></svg>
                                                        <a href="/product/{{ Str::slug($item->title) }}" class="">
                                                            <img alt="{{ $item->title }}"
                                                                sizes="(min-width: 110em) 420px, (min-width: 90em) 25vw, (min-width: 60em) 33vw, (min-width: 30em) 50vw, 100vw"
                                                                data-src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                data-srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                class="jqo-io-processed"
                                                                src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="c-node-ai__details-wrap">
                                                    <h3 class="c-node-ai__title c-heading--gamma">
                                                        <a href="/product/{{ Str::slug($item->title) }}" data-mousetrap-trigger="4">
                                                            {{ $item->title }}
                                                        </a>
                                                    </h3>
                                                    <div class="c-node-ai__bidding-details">
                                                        <div class="auction-details-layout">
                                                            <div class="auction-timer-section">
                                                                <div class="c-node-ai__timer">
                                                                    <div id="ai-timer-{{ $item->id }}"
                                                                        class="js-timer-wrapper c-timer c-timer--small-block u-hide-no-js">
                                                                        <div class="c-timer__title"><span class="js-timer-title">Time remaining</span></div>
                                                                        <span class="c-timer__body">
                                                                            <span class="js-timer"
                                                                                data-timer_id="ai-{{ $item->id }}-long-small-block"
                                                                                data-type="expiry"
                                                                                data-timeout="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}"
                                                                                data-format_num="long"
                                                                                data-deadline="{{ $item->dead_line }}"
                                                                                id="auction-timer-{{ $item->id }}">
                                                                                <span class="js-timer-element-days c-timer__element">
                                                                                    <span class="c-timer__value" id="days-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Days</span>
                                                                                </span>
                                                                                <span class="c-timer__element">
                                                                                    <span class="c-timer__value" id="hours-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Hrs</span>
                                                                                </span>
                                                                                <span class="c-timer__element">
                                                                                    <span class="c-timer__value" id="minutes-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Mins</span>
                                                                                </span>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="auction-price-section">
                                                                <div class="c-node-ai__price">
                                                                    <div id="ai-price-{{ $item->id }}" class="c-price  c-price--small-block">
                                                                        <div class="c-price__title"><span class="js-price-title">Current bid</span></div>
                                                                        <div class="c-price__wrapper">
                                                                            <div class="c-price__value js-resize-bid-text u-tc--highlight-bg"
                                                                                id="auction-price-{{ $item->id }}"
                                                                                data-live-item="price"
                                                                                data-tcid="{{ $item->id }}:price"
                                                                                style="font-size: 16px;">
                                                                                ${{ number_format($item->starting_price ?? 0, 0) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Auction Timer JavaScript -->
                <script>
                // Improved auction timer with better error handling and fallbacks
                function startAuctionListTimer(deadline, id) {
                    console.log('Starting auction list timer for auction', id, 'with deadline', deadline);
                    
                    function update() {
                        const now = new Date().getTime();
                        const target = new Date(deadline).getTime();
                        let timeLeft = target - now;

                        if (timeLeft <= 0) {
                            const daysEl = document.getElementById('days-' + id);
                            const hoursEl = document.getElementById('hours-' + id);
                            const minutesEl = document.getElementById('minutes-' + id);
                            
                            if (daysEl) daysEl.textContent = 0;
                            if (hoursEl) hoursEl.textContent = 0;
                            if (minutesEl) minutesEl.textContent = 0;
                            console.log('Timer expired for auction', id);
                            return;
                        }

                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));

                        const daysEl = document.getElementById('days-' + id);
                        const hoursEl = document.getElementById('hours-' + id);
                        const minutesEl = document.getElementById('minutes-' + id);
                        
                        if (daysEl) daysEl.textContent = days;
                        if (hoursEl) hoursEl.textContent = hours;
                        if (minutesEl) minutesEl.textContent = minutes;
                    }
                    
                    // Initial update
                    update();
                    
                    // Set interval for updates
                    const intervalId = setInterval(update, 1000);
                    
                    // Store interval ID for potential cleanup
                    if (!window.auctionListTimers) {
                        window.auctionListTimers = {};
                    }
                    window.auctionListTimers[id] = intervalId;
                }
                
                // Enhanced initialization function
                function initializeAuctionListTimers() {
                    console.log('Initializing auction list timers for auction-list component');
                    @foreach ($auction as $item)
                        // Check if elements exist before starting timer - no variable declaration needed
                        if (document.getElementById('auction-timer-{{ $item->id }}')) {
                            console.log('Found timer container for auction {{ $item->id }}');
                            startAuctionListTimer("{{ $item->dead_line }}", "{{ $item->id }}");
                        } else {
                            console.log('Timer container not found for auction {{ $item->id }}');
                        }
                    @endforeach
                }

                // Initialize timers for auction list component
                document.addEventListener('DOMContentLoaded', function() {
                    // Multiple initialization attempts to handle different loading scenarios
                    setTimeout(initializeAuctionListTimers, 100);
                    setTimeout(initializeAuctionListTimers, 500);
                    setTimeout(initializeAuctionListTimers, 1000);
                });
                
                // Also initialize if page is already loaded
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    setTimeout(initializeAuctionListTimers, 100);
                }
                </script>
                
                <!-- Firebase Real-time Price Updates -->
                <script type="module">
                try {
                    const { initializeApp, getApps } = await import("https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js");
                    const { getFirestore, collection, query, where, orderBy, getDocs, limit } = await import("https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js");

                    const firebaseConfig = {
                        apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
                        authDomain: "charity-390ca.firebaseapp.com",
                        projectId: "charity-390ca",
                        storageBucket: "charity-390ca.firebasestorage.app",
                        messagingSenderId: "875958450032",
                        appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
                        measurementId: "G-FC73HL5XF3"
                    };

                    // Initialize Firebase (check if already initialized)
                    let app;
                    if (!getApps().length) {
                        app = initializeApp(firebaseConfig);
                    } else {
                        app = getApps()[0];
                    }
                    const firestore = getFirestore(app);

                    // Function to update auction prices from Firebase
                    async function updateAuctionPrices() {
                        console.log('Fetching auction prices from Firebase...');
                        
                        @foreach ($auction as $item)
                        {
                            const auctionId = "{{ $item->id }}";
                            const priceDiv = document.getElementById('auction-price-{{ $item->id }}');
                            
                            if (priceDiv) {
                                try {
                                    // Query Firebase for highest bid
                                    const q = query(
                                        collection(firestore, "bid"),
                                        where("auction_id", "==", auctionId),
                                        orderBy("amount", "desc"),
                                        limit(1)
                                    );
                                    
                                    const querySnapshot = await getDocs(q);
                                    
                                    if (!querySnapshot.empty) {
                                        const bidData = querySnapshot.docs[0].data();
                                        const amount = Number(bidData.amount);
                                        
                                        if (amount > 0) {
                                            priceDiv.textContent = `$${amount.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
                                            console.log('Updated auction {{ $item->id }} price to:', amount);
                                        }
                                    } else {
                                        console.log('No bids found for auction {{ $item->id }}');
                                    }
                                } catch (error) {
                                    console.error('Error fetching bid for auction {{ $item->id }}:', error);
                                }
                            }
                        }
                        @endforeach
                    }

                    // Update prices when DOM is ready
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(updateAuctionPrices, 500);
                        });
                    } else {
                        setTimeout(updateAuctionPrices, 500);
                    }
                    
                    // Poll for updates every 6 seconds
                    setInterval(updateAuctionPrices, 6000);
                    
                } catch (error) {
                    console.error('Firebase initialization failed:', error);
                }
                </script>
                </script>
            @else
                <div style="{{ $wrapperStyleStr }}">
                    <div style="{{ $styleStr }}; padding: 40px; text-align: center; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                        <i style="font-size: 3em; margin-bottom: 20px; display: block;">🎯</i>
                        <h3 style="margin-bottom: 10px; color: #495057;">No Active Auctions</h3>
                        <p style="margin: 0;">There are currently no active auctions to display. Please check back later!</p>
                    </div>
                </div>
            @endif
        @break

        @case('student-leaderboard')
                @php
                    $sortedStudents = App\Models\User::whereIn('role', ['individual', 'group_leader', 'member'])
                        ->where('website_id', $check->id)
                        ->withSum(['donations as total_donations' => function ($query) use ($check) {
                            $query->where('website_id', $check->id);
                        }], 'amount')
                        ->orderByDesc('total_donations')
                        ->limit(5)
                        ->get();
                    $key = 0;
                @endphp
                @php
                    $style = $component['style'] ?? [];
                    $wrapperStyle = $component['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    // dd($alertStyleStr);
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                        }
                        // dd($style);
                    @endphp
<div class="col-md-12 mt-4" style="{{ $alertStyleStr }} {{ $wrapperStyleStr }}">

                @foreach($sortedStudents as $student)
                    @php
                        $donationTotal = $student->total_donations ?? 0;
                    @endphp
                    <div class="col-lg-12" style="font-size: 12px; margin-bottom: 1rem; ">
                        <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                            style="width: 100%; max-width: 580px; margin-inline: auto; background: #ebebeb;">
                            <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                            <div class="row gy-3 ">
                                <div class="col-lg-3 d-flex align-items-center">
                                    <span class="jk" style="font-size: 1.5rem !important; font-weight: bold; margin-right: 1rem;">{{ $key + 1}}</span>
                                    <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                        <img src="{{ asset($student->photo) }}" style="object-fit: contain; border-radius: 50%; width: 70px; min-width: 70px; height: 70px; min-height: 70px;" onerror="this.src='{{ asset('uploads/'.$check->setting->logo) ?? asset('images/default-logo.png') }}';">
                                    </div>
                                </div>

                                <div class="col-lg-7 d-flex flex-column justify-content-center" style="margin-top: 0px !important;">
                                    <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                        {{ $student->name }} {{ $student->last_name }}
                                    </h2>

                                    {{-- <span class="opacity-75 text-center text-lg-start mt-2"></span> --}}

                                    <div class="progress" role="progressbar" aria-valuenow="{{ $donationTotal }}"
                                        aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                        data-secondary-color="#28a745" data-duration="5"
                                        data-goal-reached="true" style="height: 14px; border: 1px solid #28a745">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                            style="width:@if($student->goal > 0){{ ($donationTotal / $student->goal)*100 }}@else 1 @endif%; background-color: #28a745 !important;" > <span style="font-size: 13px; font-weight: bold; margin-top: -2px;"> @if($student->goal > 0){{ round(($donationTotal / $student->goal)*100) }}@else 1 @endif% </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                <i class="fa-solid fa-award fa-2xl fa-fw position-absolute" aria-hidden="true" style="
                                @if($key == 0)
                                    color: #FFDf01;
                                @elseif($key == 1)
                                    color: #c0c0c0;
                                @elseif($key == 2)
                                    color: #996515;
                                @else
                                    display: none;
                                @endif
                                    top: 30px; right: 25px; font-size: 2.5rem !important;"></i>
                                <span class="small fw-bold" style="top: 57px; position: relative; left: -36px; right: unset; font-size: 0.74rem; color: #000;">
                                    $ {{ $donationTotal }}
                                </span>
                            </span>
                            </a>
                        </div>
                    </div>
                    @php
                        $key +=1;
                    @endphp
                @endforeach
            </div>
            <div class="col-md-12 mt-4">
                <p class="lead text-center mt-3" style="color: {{ $style['color'] }} !important">
                    @php
                        $count = App\Models\Donation::where('website_id',$check->id)->where('status', 1)->count();
                    @endphp
                    {{ $count }} donations have been made to this site
                </p>
            </div>

@break

@case('sponsorships')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $sponsors = \App\Models\Sponsor::where('website_id', $check->id ?? 1)->latest()->get();
                
                // Debug logging
                \Log::info('Sponsorships Component: Domain=' . $domain . ', Website ID=' . ($check->id ?? 'none') . ', Sponsors Count=' . $sponsors->count());
            @endphp
            
            <div class="sponsorships-component" style="{{ $styleStr }}">
                @if($sponsors->count() > 0)
                    <h4 style="text-align: center; margin-bottom: 2rem;">Our Sponsors</h4>
                    <div class="row justify-content-center align-items-center g-4">
                        @foreach($sponsors as $sponsor)
                            <div class="col-6 col-md-3 text-center">
                                <div class="sponsor-logo">
                                    @if($sponsor->image)
                                    <a href="{{ $sponsor->link }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset($sponsor->image) }}" 
                                             alt="Sponsor {{ $loop->iteration }}" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 180px; object-fit: contain; width: 100%; transition: transform 0.3s ease;"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                    @else
                                        <div class="sponsor-placeholder" style="height: 100px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                            <span style="color: #6c757d; font-size: 14px;">No Image</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 1rem; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                        <i class="fas fa-handshake" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <h5 style="color: #6c757d; margin-bottom: 0.5rem;">No Sponsors Yet</h5>
                        <p style="color: #6c757d; margin: 0; font-size: 14px;">Sponsors will be displayed here once they are added to this website.</p>
                    </div>
                @endif
            </div>
                                @break

@case('site-goal')
                                    @if(isset($component['goalData']))
                                        @php
                                        // Get goal from website settings
                                        $goal = isset($setting) && isset($setting->goal) ? (float)$setting->goal : 10000;
                                        
                                        // Get raised amount from all approved donations
                                        $raised = 0;
                                        if (isset($check) && isset($check->id)) {
                                            // Get donations for this website
                                            $raised = \App\Models\Donation::where('website_id', $check->id)
                                                ->where('status', 1)
                                                ->sum('amount');
                                        } elseif (isset($data) && isset($data->user_id)) {
                                            // Fallback: get donations for the page owner
                                            $raised = \App\Models\Donation::where('user_id', $data->user_id)
                                                ->where('status', 1)
                                                ->sum('amount');
                                        }
                                        
                                        $raised = (float)$raised;
                                        $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100, 2)) : 0;
                                        $label = $component['goalData']['label'] ?? 'Fundraising Goal';
                                        $showTicks = true;
                                        $ticks = $component['goalData']['ticks'] ?? [0, 0.25, 0.5, 0.75, 1];
                                        @endphp

                                        @php
                                            $style = $component['style'] ?? [];
                                            $wrapperStyle = $component['wrapperStyle'] ?? [];
                                            $wrapperStyleStr = '';
                                            foreach ($wrapperStyle as $k => $v) {
                                                if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            $alertStyleStr = '';
                                            foreach ($style as $k => $v) {
                                                if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            // dd($alertStyleStr);
                                        @endphp
                                        @php
                                                // Ensure background color is applied to the wrapper
                                                if (!empty($style['backgroundColor'])) {
                                                    $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                                }
                                            @endphp

                                        <div class="site-goal-modernmb-4" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    {{-- <button type="button" class="btn-close" style="font-size: 1.1rem; opacity: 0.7;" aria-label="Close" onclick="this.closest('.site-goal-modern').style.display='none';"></button> --}}
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="fw-semibold" style="color: {{ $component['style']['color'] }};">${{ number_format($raised, 2) }}</span>
                                                    <span class="mx-2" style="color: {{ $component['style']['color'] }};">/</span>
                                                    <span style="color: {{ $component['style']['color'] }};">${{ number_format($goal, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; padding-bottom: 10px; color: {{ $component['style']['color'] }} !important">${{ $raised }} Raised</span>
                                                </div>
                                                <div class="progress position-relative" style="height: 35px; background: #e5e7eb; border-radius: 9px;">
                                                @php $barId = 'siteGoalProgressBar_' . uniqid(); @endphp
                                                <div class="progress-bar" role="progressbar"
                                                    style="background-color: {{ $component['goalData']['barColor'] ?? '#0d6efd'}}; width:0%; border-radius: 9px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1);"
                                                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"
                                                    id="{{ $barId }}">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                    var bar = document.getElementById('{{ $barId }}');
                                                    if (bar) {
                                                        setTimeout(function() {
                                                        bar.style.width = '{{ $percent }}%';
                                                        }, 150);
                                                    }
                                                    });
                                                </script>
                                                <div class="site-goal-ticks position-absolute w-100" style="top: 100%; left: 0; height: 24px; pointer-events: none; z-index: 10;">
                                                    @foreach($ticks as $tick)
                                                        @php
                                                            $tickPercent = 0;
                                                            $tickValue = 0;
                                                            if (is_numeric($tick)) {
                                                                if ($tick <= 1) {
                                                                    $tickPercent = $tick * 100;
                                                                    $tickValue = $tick * $goal;
                                                                } else {
                                                                    $tickPercent = min($tick / ($goal != 0 ? $goal : 1), 1) * 100;
                                                                    $tickValue = $tick;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($tickPercent >= 0 && $tickPercent <= 100)
                                                        <div class="site-goal-tick" style="position: absolute; left: {{ $tickPercent }}%; top: 0; width: 2px; height: 24px; background: #6f7c8b; z-index: 11;">
                                                            <div class="site-goal-tick-label" style="position: absolute; top: 22px; left: 50%; transform: translateX(-50%); font-size: 12px; color: {{ $component['style']['color'] ?? '#222' }}; white-space: nowrap; background: #fff; padding: 0 2px; border-radius: 2px; z-index: 12;">
                                                                ${{ number_format($tickValue, 0) }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
{{-- @if($showTicks && !empty($ticks) && $goal > 0)
@endif --}}
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; color: {{ $component['style']['color'] }} !important">${{ $goal }} Goal</span>
                                                </div>
                                                <div class="mt-3" style="font-size: 1.1rem; color: {{ $component['style']['color'] }};">
                                                    <span class="fw-bold">{{ $percent }}%</span> of goal reached
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @break

@case('student-listing')
@php
                    $style = $component['style'] ?? [];
                    $wrapperStyle = $component['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $alertStyleStr .= 'background-color:' . $style['backgroundColor'] . ' !important;';
                        }
                    // dd($alertStyleStr);

                    @endphp
        <div class="row" style="{{ $wrapperStyleStr }}">
                <div class="col-12 col-md-11 col-lg-9 col-xl-7 d-flex align-items-center" style="margin: auto;">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                    </div>
                </div>
                <div class="col-12 mt-4">
                        <div id="studentEntryInfo" style="margin-bottom: 10px; font-size: 14px; color: #666;">
                            Showing <span id="studentCount">0</span> of <span id="totalStudentCount">0</span> participants
                        </div>
                        <div id="studentListContainer" class="row" style="display: flex; flex-wrap: wrap; gap: 20px;">
    @php
        $students = App\Models\User::whereIn('role', ['individual', 'group_leader', 'member'])->where('website_id', $check->id)->latest()->get();
    @endphp

    @foreach ($students as $index => $student)
        <div class="student-card-wrapper" data-student-id="{{ $student->id }}" data-index="{{ $index }}" style="flex: 0 0 calc(50% - 10px); padding-left: 0px; padding-right: 0px; {{ $index >= 10 ? 'display: none;' : '' }}" class="student-item">
            <div class="student-card-content" style="background: #fff">
                <div style="font-size: 12px;">
                    <div class="position-relative rounded-3 shadow-sm border listingg"
                        style="width: 100%; max-width: 580px; margin-inline: auto; padding-bottom: 50px;">
                        <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                            <div class="row lsls gy-3" style="padding: 0.5rem;">
                                <div class="col-lg-3 d-flex align-items-center">
                                    <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50% !important; border-color: #2e4053 !important; overflow: hidden; width: 80px; height: 80px; aspect-ratio: 1/1;">
                                        <img src="{{ asset($student->photo) }}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 50%;" onerror="this.src='{{ asset('uploads/'.$check->setting->logo) ?? asset('images/default-logo.png') }}';">
                                    </div>
                                </div>

                                <div class="col-lg-8 d-flex flex-column justify-content-center">
                                    <h2 class="fs-1.25 fw-semibold text-center text-lg-start" style="font-size: 1.25rem; word-break: normal; overflow-wrap: break-word;">
                                        {{ $student->name }} {{ $student->last_name }}
                                    </h2>
                                    <span class="opacity-75 text-center text-lg-start mt-2"></span>
                                    <div class="progress mt-3" role="progressbar"
                                        aria-valuenow="{{ $student->donations->sum('amount') }}"
                                        aria-valuemin="0"
                                        aria-valuemax="{{ $student->goal == 0 ? 1 : $student->goal }}"
                                        data-primary-color="#2e4053"
                                        data-secondary-color="#b7bcc4"
                                        data-duration="5"
                                        data-goal-reached="true"
                                        style="height: 14px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                            style="width: @if($student->goal > 0){{ ($student->donations->sum('amount') / $student->goal) * 100 }}% @elseif($student->donations->sum('amount') > 0)100% @else 0% @endif;">
                                            <span style="font-size: 13px; font-weight: bold;">@if($student->goal > 0){{ round(($student->donations->sum('amount') / $student->goal) * 100) }}@elseif($student->donations->sum('amount') > 0)100 @else 0 @endif%</span>
                                        </div>
                                    </div>
                                    <span class="fw-semibold d-block text-center mt-2">
                                        @php $to = $student->donations->sum('amount'); @endphp
                                        ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $student->goal ?? 0 }} <small class="opacity-75 fw-light">Goal</small>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <div style="position: absolute; bottom: 10px; left: 0; right: 0; display: flex; gap: 10px; padding: 0 10px; justify-content: center;">
                            <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" class="btn btn-sm btn-primary" style="flex: 1; font-size: clamp(0.65rem, 1.8vw, 0.875rem); padding: 0.35rem 0.5rem;" target="_blank">
                                <i class="fa fa-heart me-1"></i>Donate Now
                            </a>
                            <button class="btn btn-sm btn-outline-primary add-student-to-cart" 
                                data-item-id="{{ $student->id }}"
                                data-item-type="student"
                                data-item-name="{{ $student->name }}"
                                data-item-price="0"
                                style="flex: 1; font-size: clamp(0.65rem, 1.8vw, 0.875rem); padding: 0.35rem 0.5rem; white-space: nowrap;">
                                <i class="fa fa-shopping-cart me-1"></i>Add to Donation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
                        </div>
                        
                        <!-- Load More Button -->
                        <div id="loadMoreContainer" style="text-align: center; margin-top: 30px; display: {{ count($students) > 10 ? 'block' : 'none' }};">
                            <button id="loadMoreBtn" class="btn btn-primary btn-lg" style="padding: 12px 40px; font-size: 16px; font-weight: 600;">
                                <i class="fa fa-plus-circle me-2"></i>Load More participants
                            </button>
                        </div>
                </div>
            </div>
        <script>
            // Fixed student listing counter and search with load more functionality
            (function() {
                const searchInput = document.getElementById('search');
                const studentListContainer = document.getElementById('studentListContainer');
                const studentCountEl = document.getElementById('studentCount');
                const totalStudentCountEl = document.getElementById('totalStudentCount');
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                const loadMoreContainer = document.getElementById('loadMoreContainer');
                const allStudentCards = document.querySelectorAll('.student-card-wrapper');
                
                let currentlyVisible = 10; // Initially show 10 students
                const loadMoreCount = 10; // Load 10 more each time
                const totalStudents = allStudentCards.length;
                
                // Update total count
                if (totalStudentCountEl) {
                    totalStudentCountEl.textContent = totalStudents;
                }
                
                const updateStudentCount = function() {
                    const visibleCards = Array.from(allStudentCards).filter(function(card) {
                        const computedDisplay = window.getComputedStyle(card).display;
                        return computedDisplay !== 'none' && card.getAttribute('data-hidden-by-search') !== 'true';
                    });
                    if (studentCountEl) {
                        studentCountEl.textContent = visibleCards.length;
                    }
                    
                    // Show/hide load more button based on hidden cards
                    const hiddenByPagination = Array.from(allStudentCards).filter(function(card) {
                        const index = parseInt(card.getAttribute('data-index'));
                        return index >= currentlyVisible && card.getAttribute('data-hidden-by-search') !== 'true';
                    });
                    
                    if (loadMoreContainer) {
                        loadMoreContainer.style.display = hiddenByPagination.length > 0 ? 'block' : 'none';
                    }
                };
                
                const filterStudents = function() {
                    const keyword = searchInput.value.toLowerCase().trim();
                    
                    allStudentCards.forEach(function(card) {
                        const studentContent = card.textContent.toLowerCase();
                        const matches = !keyword || studentContent.includes(keyword);
                        const index = parseInt(card.getAttribute('data-index'));
                        
                        if (!matches) {
                            // Doesn't match search - hide it
                            card.style.display = 'none';
                            card.setAttribute('data-hidden-by-search', 'true');
                        } else {
                            // Matches search
                            card.removeAttribute('data-hidden-by-search');
                            // If there's an active search, show ALL matching results regardless of pagination
                            // If no search, respect the pagination limit
                            if (keyword) {
                                card.style.display = ''; // Show all search matches
                            } else if (index < currentlyVisible) {
                                card.style.display = ''; // Respect pagination when not searching
                            } else {
                                card.style.display = 'none'; // Hide from pagination
                            }
                        }
                    });
                    
                    updateStudentCount();
                };
                
                // Load More functionality
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const oldVisible = currentlyVisible;
                        currentlyVisible += loadMoreCount;
                        
                        // Show next batch of students
                        allStudentCards.forEach(function(card) {
                            const index = parseInt(card.getAttribute('data-index'));
                            if (index >= oldVisible && index < currentlyVisible && card.getAttribute('data-hidden-by-search') !== 'true') {
                                card.style.display = '';
                            }
                        });
                        
                        updateStudentCount();
                        
                        // Smooth scroll to first newly loaded student
                        setTimeout(function() {
                            const firstNewCard = document.querySelector('.student-card-wrapper[data-index="' + oldVisible + '"]');
                            if (firstNewCard) {
                                firstNewCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        }, 100);
                    });
                }
                
                // Initialize count
                updateStudentCount();
                
                // Add search event listener
                if (searchInput) {
                    searchInput.addEventListener('input', filterStudents);
                }
                
                // CRITICAL: Use TRUE GLOBAL flag to prevent rapid clicking across ALL components
                if (typeof window._isAddingToCart === 'undefined') {
                    window._isAddingToCart = false;
                }
                
                // Add to cart button functionality - Strict one-at-a-time processing
                document.addEventListener('click', async function(e) {
                    const btn = e.target.closest('.add-student-to-cart');
                    if (!btn) return;
                    
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // CRITICAL: Check GLOBAL flag FIRST - only ONE operation allowed at a time
                    if (window._isAddingToCart) {
                        console.log('⏳ Another item is being added, please wait...');
                        return;
                    }
                    
                    // Set GLOBAL flag immediately
                    window._isAddingToCart = true;
                    
                    const itemId = btn.dataset.itemId;
                    const itemName = btn.dataset.itemName;
                    const itemType = btn.dataset.itemType;
                    const itemPrice = btn.dataset.itemPrice || '0';
                    
                    console.log('🛒 Adding to cart:', itemName);
                    
                    // Get ALL add-to-cart buttons and disable them
                    const allButtons = document.querySelectorAll('.add-student-to-cart');
                    const buttonStates = new Map();
                    
                    allButtons.forEach(function(button) {
                        buttonStates.set(button, {
                            html: button.innerHTML,
                            classes: button.className
                        });
                        button.disabled = true;
                    });
                    
                    // Show loading state on clicked button
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Adding...';
                    
                    try {
                        // Use window.ShoppingCart.addItem if available
                        if (window.ShoppingCart && typeof window.ShoppingCart.addItem === 'function') {
                            const success = await window.ShoppingCart.addItem({
                                id: itemId,
                                name: itemName,
                                type: itemType,
                                price: parseFloat(itemPrice),
                                quantity: 1
                            });
                            
                            if (success) {
                                // Keep "Adding..." state during 7-second persistence window
                                // Don't change to "Added!" yet
                                
                                // CRITICAL: Reload cart to verify item was actually added
                                console.log('🔄 Reloading cart to verify...');
                                await window.ShoppingCart.loadCart();
                                console.log('✅ Cart verified, item count:', window.ShoppingCart.state.cart?.item_count);
                                
                                // Update displays with verified cart
                                window.ShoppingCart.updateCartDisplay();
                                window.ShoppingCart.updateCartBadge();
                                
                                // Wait 7 seconds total for database persistence - keep "Adding..." visible
                                await new Promise(r => setTimeout(r, 7000));
                                
                                // NOW show success after 7-second delay
                                btn.innerHTML = '<i class="fa fa-check me-2"></i>Added!';
                                btn.classList.remove('btn-outline-primary');
                                btn.classList.add('btn-success');
                            } else {
                                btn.innerHTML = '<i class="fa fa-exclamation-triangle me-2"></i>Failed';
                                btn.classList.add('btn-danger');
                                await new Promise(r => setTimeout(r, 2000));
                            }
                        } else {
                            console.error('❌ ShoppingCart not available');
                            btn.innerHTML = '<i class="fa fa-exclamation-triangle me-2"></i>Error';
                            btn.classList.add('btn-danger');
                            await new Promise(r => setTimeout(r, 2000));
                        }
                    } catch (error) {
                        console.error('❌ Error:', error);
                        btn.innerHTML = '<i class="fa fa-exclamation-triangle me-2"></i>Error';
                        btn.classList.add('btn-danger');
                        await new Promise(r => setTimeout(r, 2000));
                    }
                    
                    // Re-enable ALL buttons NOW (after verification complete)
                    allButtons.forEach(function(button) {
                        const state = buttonStates.get(button);
                        if (state) {
                            button.innerHTML = state.html;
                            button.className = state.classes;
                        }
                        button.disabled = false;
                    });
                    
                    // Release GLOBAL flag
                    window._isAddingToCart = false;
                    console.log('✅ Ready for next item');
                });
            })();
        </script>
@break

        @case('sell-tickets')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $tickets = \App\Models\Ticket::where('website_id', $check->id ?? 1)->where('status',1)->where('type', 'ticket')->latest()->get();
                
                // Get sell tickets data with defaults
                $sellTicketsData = $component['sellTicketsData'] ?? [];
                $title = $sellTicketsData['title'] ?? 'Buy Tickets';
                $buttonText = $sellTicketsData['buttonText'] ?? 'Buy Now';
                $buttonBg = $sellTicketsData['buttonBg'] ?? '#007bff';
                $buttonColor = $sellTicketsData['buttonColor'] ?? '#fff';
                $buttonPadding = $sellTicketsData['buttonPadding'] ?? '10px 20px';
                $buttonRadius = $sellTicketsData['buttonRadius'] ?? '4px';
            @endphp
            
            <section class="mt-2 mb-2">
                @if($tickets->count() > 0)
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            {{-- @if($title && $title !== 'Buy Tickets' || !empty($sellTicketsData))
                                <div class="text-center mb-4">
                                    <h3>{{ $title }}</h3>
                                </div>
                            @endif --}}
                            <form action="/tickets" method="POST">
                                @csrf
                                @foreach ($tickets as $item)
                                    <div class="card ticket-mask mt-2 mb-2" style="{{ $wrapperStyleStr }} {{ $styleStr }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset($item->image) }}" width="64px" height="64px;" alt="{{ $item->name }}">
                                                    </div>
                                                    <div class="col-md-10" style="color: {{ $buttonColor }};">
                                                        <h4 style="margin-bottom: 2px;" style="color: {{ $buttonColor }};">{{ $item->name }} (${{ $item->price }})</h4>
                                                        <p style="margin-bottom: 2px;" style="color: {{ $buttonColor }};">{{ $item->description }}</p>
                                                        <span style="color: {{ $buttonColor }};">Only {{ $item->quantity }} left!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="ticket[{{ $item->id }}][id]" value="{{ $item->id }}">
                                                <select name="ticket[{{ $item->id }}][quantity]" class="form-control tickets">
                                                    <option value="null">Select an option</option>
                                                    @for ($i = 1; $i <= $item->quantity; $i++)
                                                        <option value="{{ $i }}">You selected a total of {{ $i }} {{ $item->name }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-12 text-center mt-4 mb-4">
                                    @guest
                                        <button type="button" class="btn" style="
                                            background: {{ $buttonBg }};
                                            color: {{ $buttonColor }};
                                            padding: {{ $buttonPadding }};
                                            border-radius: {{ $buttonRadius }};
                                            border: none;
                                            font-size: 16px;
                                            cursor: pointer;
                                            transition: all 0.3s ease;
                                        " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'" onclick="window.openAuthModal && window.openAuthModal();">
                                            {{ $buttonText }}
                                        </button>
                                    @else
                                        <button type="submit" class="btn" style="
                                            background: {{ $buttonBg }};
                                            color: {{ $buttonColor }};
                                            padding: {{ $buttonPadding }};
                                            border-radius: {{ $buttonRadius }};
                                            border: none;
                                            font-size: 16px;
                                            cursor: pointer;
                                            transition: all 0.3s ease;
                                        " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                            {{ $buttonText }}
                                        </button>
                                    @endguest
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div style="padding: 40px; text-align: center; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                                <i style="font-size: 3em; margin-bottom: 20px; display: block;">🎫</i>
                                <h3 style="margin-bottom: 10px; color: #495057;">No Tickets Available</h3>
                                <p style="margin: 0;">There are currently no tickets available for purchase. Please check back later!</p>
                            </div>
                        </div>
                    </div>
                @endif
            </section>
            
            @include('partials.ticket-auth-modal')
            @include('partials.investor-info-modal')
        @break

        @case('newsletter')
            @php
                // Check if we have saved HTML from page builder
                $savedHtml = $component['html'] ?? '';
                $newsletterData = $component['newsletterData'] ?? [];
                
                // Get component settings with defaults
                $buttonText = $newsletterData['buttonText'] ?? 'DOWNLOAD STUDY';
                $buttonColor = $newsletterData['buttonColor'] ?? '#20b2aa';
                $backgroundColor = $newsletterData['backgroundColor'] ?? '#2d2d2d';
                $textColor = $newsletterData['textColor'] ?? '#ffffff';
                $labelColor = $newsletterData['labelColor'] ?? '#9ca3af';
                $privacyText = $newsletterData['privacyText'] ?? 'By submitting this form and signing up for texts, you consent to receive marketing text messages (e.g. promos, cart reminders) from EnergyX at the number provided, including messages sent by autodialer. Consent is not a condition of purchase. Msg & data rates may apply. Msg frequency varies. Unsubscribe at any time by replying STOP or clicking the unsubscribe link (where available).';
                $privacyPolicyUrl = $newsletterData['privacyPolicyUrl'] ?? '#';
                $termsUrl = $newsletterData['termsUrl'] ?? '#';
            @endphp
            
            @if($savedHtml)
                {{-- Use the saved HTML with styles from page builder --}}
                <div id="{{ $componentId }}">
                    {!! $savedHtml !!}
                </div>
                
                {{-- Add newsletter functionality script --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const newsletterForm = document.querySelector('#{{ $componentId }} .newsletter-form');
                        const messageDiv = document.querySelector('#{{ $componentId }} .newsletter-message');
                        
                        if (newsletterForm) {
                            // Remove any existing event listeners to prevent duplicates
                            newsletterForm.replaceWith(newsletterForm.cloneNode(true));
                            const form = document.querySelector('#{{ $componentId }} .newsletter-form');
                            
                            // Update form action to proper route
                            form.action = '{{ route("newsletter.subscribe") }}';
                            form.method = 'POST';
                            
                            // Add CSRF token if not present
                            if (!form.querySelector('input[name="_token"]')) {
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = '{{ csrf_token() }}';
                                form.appendChild(csrfInput);
                            }
                            
                            // Add website_id if not present
                            if (!form.querySelector('input[name="website_id"]')) {
                                const websiteInput = document.createElement('input');
                                websiteInput.type = 'hidden';
                                websiteInput.name = 'website_id';
                                websiteInput.value = '{{ $check->id ?? "" }}';
                                form.appendChild(websiteInput);
                            }
                            
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                // Validate required fields
                                const emailInput = form.querySelector('input[name="email"]');
                                const firstNameInput = form.querySelector('input[name="first_name"]');
                                const lastNameInput = form.querySelector('input[name="last_name"]');
                                const phoneInput = form.querySelector('input[name="phone"]');
                                
                                if (!emailInput || !emailInput.value.trim()) {
                                    alert('Please enter an email address.');
                                    return;
                                }
                                
                                if (!firstNameInput || !firstNameInput.value.trim()) {
                                    alert('Please enter your first name.');
                                    return;
                                }
                                
                                if (!lastNameInput || !lastNameInput.value.trim()) {
                                    alert('Please enter your last name.');
                                    return;
                                }
                                
                                if (!phoneInput || !phoneInput.value.trim()) {
                                    alert('Please enter your phone number.');
                                    return;
                                }
                                
                                // Basic email validation
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if (!emailRegex.test(emailInput.value.trim())) {
                                    alert('Please enter a valid email address.');
                                    return;
                                }
                                
                                const formData = new FormData(form);
                                const button = form.querySelector('.newsletter-submit-btn, button[type="submit"], input[type="submit"]');
                                const originalText = button ? (button.textContent || button.value) : '';
                                
                                if (button) {
                                    if (button.tagName === 'INPUT') {
                                        button.value = 'Submitting...';
                                    } else {
                                        button.textContent = 'Submitting...';
                                    }
                                    button.disabled = true;
                                }
                                
                                fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (messageDiv) {
                                        messageDiv.style.display = 'block';
                                        if (data.success) {
                                            messageDiv.innerHTML = '<div style="color: green; font-weight: 500; padding: 10px; background: #f0f9ff; border: 1px solid #22c55e; border-radius: 4px;">' + data.message + '</div>';
                                            form.reset();
                                        } else {
                                            messageDiv.innerHTML = '<div style="color: red; font-weight: 500; padding: 10px; background: #fef2f2; border: 1px solid #ef4444; border-radius: 4px;">' + data.message + '</div>';
                                        }
                                    }
                                })
                                .catch(error => {
                                    if (messageDiv) {
                                        messageDiv.style.display = 'block';
                                        messageDiv.innerHTML = '<div style="color: red; font-weight: 500; padding: 10px; background: #fef2f2; border: 1px solid #ef4444; border-radius: 4px;">An error occurred. Please try again.</div>';
                                    }
                                })
                                .finally(() => {
                                    if (button) {
                                        if (button.tagName === 'INPUT') {
                                            button.value = originalText;
                                        } else {
                                            button.textContent = originalText;
                                        }
                                        button.disabled = false;
                                    }
                                    
                                    // Hide message after 6 seconds
                                    if (messageDiv) {
                                        setTimeout(() => {
                                            messageDiv.style.display = 'none';
                                        }, 6000);
                                    }
                                });
                            });
                        }
                    });
                </script>
            @else
                {{-- New Advanced Newsletter Form Design --}}
                <section class="newsletter-section" style="background-color: {{ $backgroundColor }}; color: {{ $textColor }}; padding: 40px 20px;" id="{{ $componentId }}">
                    <div style="max-width: 600px; margin: 0 auto;">
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" style="width: 100%;">
                            @csrf
                            <input type="hidden" name="website_id" value="{{ $check->id ?? '' }}">
                            
                            <!-- Name Fields Row -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <label style="display: block; color: {{ $labelColor }}; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">FIRST NAME</label>
                                    <input 
                                        type="text" 
                                        name="first_name" 
                                        placeholder="First name"
                                        required
                                        style="width: 100%; padding: 15px; border: none; border-radius: 8px; font-size: 16px; background-color: #f5f5f5; color: #333; outline: none; box-sizing: border-box;"
                                    >
                                </div>
                                <div>
                                    <label style="display: block; color: {{ $labelColor }}; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">LAST NAME</label>
                                    <input 
                                        type="text" 
                                        name="last_name" 
                                        placeholder="Last name"
                                        required
                                        style="width: 100%; padding: 15px; border: none; border-radius: 8px; font-size: 16px; background-color: #f5f5f5; color: #333; outline: none; box-sizing: border-box;"
                                    >
                                </div>
                            </div>
                            
                            <!-- Phone and Email Row -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                                <div>
                                    <label style="display: block; color: {{ $labelColor }}; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">PHONE</label>
                                    <div style="display: flex; background-color: #f5f5f5; border-radius: 8px; overflow: hidden;">
                                        <select 
                                            name="country_code" 
                                            style="padding: 15px 12px; background-color: #e5e5e5; border: none; border-right: 1px solid #d1d5db; font-size: 16px; color: #333; font-weight: 500; outline: none; cursor: pointer; min-width: 80px;"
                                        >
                                            <option value="+1" selected>🇺🇸 +1</option>
                                            <option value="+44">🇬🇧 +44</option>
                                            <option value="+33">🇫🇷 +33</option>
                                            <option value="+49">🇩🇪 +49</option>
                                            <option value="+39">🇮🇹 +39</option>
                                            <option value="+34">🇪🇸 +34</option>
                                            <option value="+31">🇳🇱 +31</option>
                                            <option value="+32">🇧🇪 +32</option>
                                            <option value="+41">🇨🇭 +41</option>
                                            <option value="+43">🇦🇹 +43</option>
                                            <option value="+45">🇩🇰 +45</option>
                                            <option value="+46">🇸🇪 +46</option>
                                            <option value="+47">🇳🇴 +47</option>
                                            <option value="+358">🇫🇮 +358</option>
                                            <option value="+420">🇨🇿 +420</option>
                                            <option value="+48">🇵🇱 +48</option>
                                            <option value="+351">🇵🇹 +351</option>
                                            <option value="+30">🇬🇷 +30</option>
                                            <option value="+36">🇭🇺 +36</option>
                                            <option value="+353">🇮🇪 +353</option>
                                            <option value="+1">🇨🇦 +1</option>
                                            <option value="+52">🇲🇽 +52</option>
                                            <option value="+55">🇧🇷 +55</option>
                                            <option value="+54">🇦🇷 +54</option>
                                            <option value="+56">🇨🇱 +56</option>
                                            <option value="+57">🇨🇴 +57</option>
                                            <option value="+51">🇵🇪 +51</option>
                                            <option value="+598">🇺🇾 +598</option>
                                            <option value="+595">🇵🇾 +595</option>
                                            <option value="+591">🇧🇴 +591</option>
                                            <option value="+593">🇪🇨 +593</option>
                                            <option value="+594">🇬🇫 +594</option>
                                            <option value="+61">🇦� +61</option>
                                            <option value="+64">🇳🇿 +64</option>
                                            <option value="+81">🇯🇵 +81</option>
                                            <option value="+82">🇰🇷 +82</option>
                                            <option value="+86">🇨🇳 +86</option>
                                            <option value="+91">🇮🇳 +91</option>
                                            <option value="+92">🇵🇰 +92</option>
                                            <option value="+880">🇧🇩 +880</option>
                                            <option value="+94">🇱🇰 +94</option>
                                            <option value="+977">🇳🇵 +977</option>
                                            <option value="+975">🇧🇹 +975</option>
                                            <option value="+960">🇲🇻 +960</option>
                                            <option value="+65">🇸🇬 +65</option>
                                            <option value="+60">🇲🇾 +60</option>
                                            <option value="+66">🇹🇭 +66</option>
                                            <option value="+84">🇻🇳 +84</option>
                                            <option value="+63">🇵🇭 +63</option>
                                            <option value="+62">🇮🇩 +62</option>
                                            <option value="+673">🇧🇳 +673</option>
                                            <option value="+856">🇱🇦 +856</option>
                                            <option value="+855">🇰🇭 +855</option>
                                            <option value="+95">🇲🇲 +95</option>
                                            <option value="+20">🇪🇬 +20</option>
                                            <option value="+27">🇿🇦 +27</option>
                                            <option value="+234">🇳🇬 +234</option>
                                            <option value="+254">🇰🇪 +254</option>
                                            <option value="+256">🇺🇬 +256</option>
                                            <option value="+255">🇹🇿 +255</option>
                                            <option value="+251">🇪🇹 +251</option>
                                            <option value="+233">🇬🇭 +233</option>
                                            <option value="+225">🇨🇮 +225</option>
                                            <option value="+221">🇸🇳 +221</option>
                                            <option value="+212">🇲🇦 +212</option>
                                            <option value="+216">🇹🇳 +216</option>
                                            <option value="+218">🇱🇾 +218</option>
                                            <option value="+213">🇩🇿 +213</option>
                                            <option value="+966">🇸🇦 +966</option>
                                            <option value="+971">🇦🇪 +971</option>
                                            <option value="+974">🇶🇦 +974</option>
                                            <option value="+965">🇰🇼 +965</option>
                                            <option value="+973">🇧🇭 +973</option>
                                            <option value="+968">🇴🇲 +968</option>
                                            <option value="+964">🇮🇶 +964</option>
                                            <option value="+963">🇸🇾 +963</option>
                                            <option value="+962">🇯🇴 +962</option>
                                            <option value="+961">🇱🇧 +961</option>
                                            <option value="+972">🇮🇱 +972</option>
                                            <option value="+970">🇵🇸 +970</option>
                                            <option value="+90">🇹🇷 +90</option>
                                            <option value="+98">🇮🇷 +98</option>
                                            <option value="+93">🇦🇫 +93</option>
                                            <option value="+7">🇷🇺 +7</option>
                                            <option value="+380">🇺🇦 +380</option>
                                            <option value="+375">🇧🇾 +375</option>
                                            <option value="+373">🇲🇩 +373</option>
                                            <option value="+382">🇲🇪 +382</option>
                                            <option value="+381">🇷🇸 +381</option>
                                            <option value="+385">🇭🇷 +385</option>
                                            <option value="+387">🇧🇦 +387</option>
                                            <option value="+389">🇲🇰 +389</option>
                                            <option value="+383">🇽🇰 +383</option>
                                            <option value="+355">🇦🇱 +355</option>
                                            <option value="+359">🇧🇬 +359</option>
                                            <option value="+40">🇷🇴 +40</option>
                                        </select>
                                        <input 
                                            type="tel" 
                                            name="phone" 
                                            placeholder="Phone number"
                                            required
                                            style="flex: 1; padding: 15px; border: none; font-size: 16px; background-color: transparent; color: #333; outline: none;"
                                        >
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; color: {{ $labelColor }}; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">EMAIL</label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        placeholder="Enter your email"
                                        required
                                        style="width: 100%; padding: 15px; border: none; border-radius: 8px; font-size: 16px; background-color: #f5f5f5; color: #333; outline: none; box-sizing: border-box;"
                                    >
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="newsletter-submit-btn"
                                style="width: 100%; background-color: {{ $buttonColor }}; color: #ffffff; border: none; border-radius: 8px; padding: 18px; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;"
                                onmouseover="this.style.backgroundColor='{{ $buttonColor }}dd'"
                                onmouseout="this.style.backgroundColor='{{ $buttonColor }}'"
                            >
                                {{ $buttonText }}
                            </button>
                            
                            <!-- Privacy Text -->
                            <p style="font-size: 12px; color: {{ $labelColor }}; line-height: 1.5; margin: 0; text-align: left;">
                                {{ $privacyText }}
                            </p>
                        </form>
                        
                        <div class="newsletter-message" style="margin-top: 20px; display: none;">
                            <!-- Success/Error messages will appear here -->
                        </div>
                    </div>
                    
                    <!-- Mobile Responsive Styles -->
                    <style>
                        @media (max-width: 768px) {
                            #{{ $componentId }} .newsletter-form > div[style*="grid-template-columns"] {
                                display: block !important;
                            }
                            #{{ $componentId }} .newsletter-form > div[style*="grid-template-columns"] > div {
                                margin-bottom: 20px;
                            }
                            #{{ $componentId }} .newsletter-form > div[style*="grid-template-columns"] > div:last-child {
                                margin-bottom: 0;
                            }

                            .inner-section-frontend {
                        padding: 0 !important;
                    }
                        }

                        @if($hasVideoBackground)
                        #{{ $componentId }} .inner-section-video-layer {
                            position: absolute;
                            inset: 0;
                            overflow: hidden;
                            z-index: 0;
                        }
                        #{{ $componentId }} .inner-section-video-layer video,
                        #{{ $componentId }} .inner-section-video-layer iframe {
                            position: absolute;
                            inset: 0;
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                        #{{ $componentId }} .inner-section-video-overlay {
                            position: absolute;
                            inset: 0;
                            background: linear-gradient(180deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.35) 100%);
                        }
                        #{{ $componentId }} .row,
                        #{{ $componentId }} .nested-component,
                        #{{ $componentId }} .animated-column {
                            position: relative;
                            z-index: 1;
                        }
                        @endif
                    </style>
                </section>
            @endif
        @break

        @case('contact-form')
            @php
                // Get contact form data with defaults
                $contactFormData = $component['contactFormData'] ?? [];
                // dd($component);
                $hasContactFormData = !empty($contactFormData);
                
                // Contact form settings
                $title = $contactFormData['title'] ?? 'Contact Us';
                $nameLabel = $contactFormData['nameLabel'] ?? 'Your name';
                $emailLabel = $contactFormData['emailLabel'] ?? 'Email address';
                $messageLabel = $contactFormData['messageLabel'] ?? 'Message';
                $buttonText = $contactFormData['buttonText'] ?? 'Submit';
                $nameRequired = $contactFormData['nameRequired'] ?? true;
                $emailRequired = $contactFormData['emailRequired'] ?? true;
                $messageRequired = $contactFormData['messageRequired'] ?? true;
                $showPrivacyText = $contactFormData['showPrivacyText'] ?? true;
                $privacyText = $contactFormData['privacyText'] ?? 'This form is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.';
                
                // Styling options
                $backgroundColor = $contactFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $contactFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $contactFormData['buttonTextColor'] ?? '#ffffff';
                $labelColor = $contactFormData['labelColor'] ?? '#000000';
                $borderRadius = $contactFormData['borderRadius'] ?? '4px';
                $buttonPadding = $contactFormData['buttonPadding'] ?? '12px 24px';
                
                // Legacy support
                $emails = $component['contactEmails'] ?? [];
                $style = $component['style'] ?? [];
                $wrapperStyle = $component['wrapperStyle'] ?? [];
                $wrapperStyleStr = '';
                foreach ($wrapperStyle as $k => $v) {
                    if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                $alertStyleStr = '';
                foreach ($style as $k => $v) {
                    if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                
                // Apply background color to wrapper if set in style
                if (!empty($style['backgroundColor'])) {
                    $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                }
            @endphp
            
            @if($hasContactFormData)
                {{-- New contact-form with customizable properties --}}
                <div class="contact-form-component" style="{{ $wrapperStyleStr }} background-color: {{ $backgroundColor }}; padding: 2rem; border-radius: {{ $borderRadius }};">
                    @if($title)
                        <h3 class="text-center mb-4" style="color: {{ $labelColor }};">{{ $title }}</h3>
                    @endif
                    
                    <form method="POST" action="/contact-form">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $nameLabel }}@if($nameRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" @if($nameRequired) required @endif>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $emailLabel }}@if($emailRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" @if($emailRequired) required @endif>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $messageLabel }}@if($messageRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <textarea class="form-control" id="message" name="message" rows="8" @if($messageRequired) required @endif></textarea>
                                    </div>
                                    
                                    @foreach($emails as $email)
                                        <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                                    @endforeach
                                    
                                    @if($showPrivacyText && $privacyText)
                                        <div class="col-12">
                                            <small class="text-muted" style="color: {{ $labelColor }} !important;">{!! $privacyText !!}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3 mt-md-4">
                            <button type="submit" class="btn btn-lg" style="
                                background-color: {{ $buttonColor }}; 
                                color: {{ $buttonTextColor }}; 
                                border-color: {{ $buttonColor }};
                                padding: {{ $buttonPadding }};
                                border-radius: {{ $borderRadius }};
                                transition: all 0.3s ease;
                            " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                {{ $buttonText }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Legacy contact-form with basic styling --}}
                <form method="POST" action="/contact-form" class="contact-form-component" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                            <div class="row gy-3">
                                <div class="col-12">
                                    <label for="name" class="form-label fw-semibold">
                                        Your name
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label fw-semibold">
                                        Email address
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">
                                        Message
                                    </label>
                                    <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                                </div>
                                <input type="hidden" name="template" value="e7d0b613d125406ea714907d6507c2a9">
                                @foreach($emails as $email)
                                    <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                                @endforeach
                                <div class="col-12">
                                    <small class="text-muted">This form is protected by reCAPTCHA and the Google <a
                                            href="https://policies.google.com/privacy" style="color: #2e4053">Privacy Policy</a>
                                        and <a href="https://policies.google.com/terms" style="color: #2e4053">Terms of Service</a>
                                        apply.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3 mt-md-4">
                        <button type="submit" class="btn btn-primary btn-lg text-white" style="background-color: #2e4053; border-color: #2e4053">
                            Submit
                        </button>
                    </div>
                </form>
            @endif
@break

        @case('auth-form')
            @php
                // Get current website and fetch teachers
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $currentWebsite = \App\Models\Website::where('domain', $domain)->first();
                $teachers = $currentWebsite ? \App\Models\Teacher::where('website_id', $currentWebsite->id)->where('is_active',1)->get() : collect();
                
                // Check if this is a new auth-form with authFormData or old one with hardcoded HTML
                $authFormData = $component['authFormData'] ?? [];
                $hasAuthFormData = !empty($authFormData);
                
                // Get colors from authFormData if available, otherwise use defaults
                $backgroundColor = $authFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $authFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $authFormData['buttonTextColor'] ?? '#ffffff';
                $avatarIconColor = $authFormData['avatarIconColor'] ?? '#2e4053';
                $linkColor = $authFormData['linkColor'] ?? '#2e4053';
            @endphp
            @php
                // Check if this is a new auth-form with authFormData or old one with hardcoded HTML
                $authFormData = $component['authFormData'] ?? [];
                $hasAuthFormData = !empty($authFormData);
                // dd($authFormData);
                
                // Get colors from authFormData if available, otherwise use defaults
                $backgroundColor = $authFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $authFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $authFormData['buttonTextColor'] ?? '#ffffff';
                $avatarIconColor = $authFormData['avatarIconColor'] ?? '#2e4053';
                $linkColor = $authFormData['linkColor'] ?? '#2e4053';
            @endphp
            
            @if($hasAuthFormData)
                {{-- Modern Dynamic Auth Form --}}
                <style>
                    /* Modern Auth Form Styling */
                    .modern-auth-container {
                        background: linear-gradient(135deg, {{ $backgroundColor }} 0%, {{ $backgroundColor }}dd 100%);
                        padding: 3rem 2rem;
                        border-radius: 20px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                        backdrop-filter: blur(10px);
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .modern-auth-container::before {
                        content: '';
                        position: absolute;
                        top: -50%;
                        right: -50%;
                        width: 200%;
                        height: 200%;
                        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                        pointer-events: none;
                    }
                    
                    .auth-header {
                        position: relative;
                        z-index: 1;
                    }
                    
                    .auth-avatar {
                        width: 120px;
                        height: 120px;
                        background: linear-gradient(135deg, {{ $avatarIconColor }}, {{ $avatarIconColor }}cc);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 1.5rem;
                        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                        transition: transform 0.3s ease;
                    }
                    
                    .auth-avatar:hover {
                        transform: scale(1.05) rotate(5deg);
                    }
                    
                    .auth-avatar i {
                        font-size: 4rem;
                        color: white;
                    }
                    
                    .auth-title {
                        font-weight: 700;
                        font-size: 2.5rem;
                        margin-bottom: 0.5rem;
                        background: linear-gradient(135deg, #333, #666);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    }
                    
                    .modern-form-group {
                        position: relative;
                        margin-bottom: 1.5rem;
                    }
                    
                    .modern-form-group label {
                        font-weight: 600;
                        color: #555;
                        margin-bottom: 0.5rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        font-size: 0.9rem;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    
                    .modern-form-group input,
                    .modern-form-group select {
                        border: 2px solid #e0e0e0;
                        border-radius: 12px;
                        padding: 0.875rem 1rem;
                        font-size: 1rem;
                        transition: all 0.3s ease;
                        background-color: white;
                    }
                    
                    .modern-form-group input:focus,
                    .modern-form-group select:focus {
                        border-color: {{ $buttonColor }};
                        box-shadow: 0 0 0 4px {{ $buttonColor }}22;
                        outline: none;
                        transform: translateY(-2px);
                    }
                    
                    .modern-form-group input:hover,
                    .modern-form-group select:hover {
                        border-color: {{ $buttonColor }}88;
                    }
                    
                    .form-icon {
                        position: absolute;
                        right: 1rem;
                        top: 2.5rem;
                        color: #999;
                        pointer-events: none;
                    }
                    
                    .password-toggle-btn {
                        position: absolute;
                        right: 1rem;
                        top: 2.5rem;
                        background: none;
                        border: none;
                        color: #666;
                        cursor: pointer;
                        padding: 0.5rem;
                        transition: color 0.3s ease;
                        font-size: 1.1rem;
                    }
                    
                    .password-toggle-btn:hover {
                        color: {{ $buttonColor }};
                    }
                    
                    .required-asterisk {
                        color: #e74c3c;
                        font-weight: 700;
                        margin-left: 0.25rem;
                    }
                    
                    .modern-btn-primary {
                        background: linear-gradient(135deg, {{ $buttonColor }}, {{ $buttonColor }}dd);
                        border: none;
                        border-radius: 12px;
                        padding: 1rem 2rem;
                        font-size: 1.1rem;
                        font-weight: 600;
                        color: {{ $buttonTextColor }};
                        transition: all 0.3s ease;
                        box-shadow: 0 6px 20px {{ $buttonColor }}44;
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .modern-btn-primary::before {
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        width: 0;
                        height: 0;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.3);
                        transform: translate(-50%, -50%);
                        transition: width 0.5s, height 0.5s;
                    }
                    
                    .modern-btn-primary:hover::before {
                        width: 300px;
                        height: 300px;
                    }
                    
                    .modern-btn-primary:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 10px 30px {{ $buttonColor }}66;
                    }
                    
                    .modern-btn-link {
                        color: {{ $linkColor }};
                        background: transparent;
                        border: 2px solid transparent;
                        border-radius: 12px;
                        padding: 0.75rem 2rem;
                        font-size: 1rem;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        text-decoration: none;
                    }
                    
                    .modern-btn-link:hover {
                        border-color: {{ $linkColor }};
                        color: {{ $linkColor }};
                        background: {{ $linkColor }}11;
                        transform: translateY(-2px);
                    }
                    
                    .teacher-select-animate {
                        animation: slideInRight 0.4s ease;
                    }
                    
                    .teacher-select-hide {
                        animation: slideOutRight 0.4s ease;
                    }
                    
                    @keyframes slideInRight {
                        from {
                            opacity: 0;
                            transform: translateX(30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                    
                    @keyframes slideOutRight {
                        from {
                            opacity: 1;
                            transform: translateX(0);
                        }
                        to {
                            opacity: 0;
                            transform: translateX(30px);
                        }
                    }
                    
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    
                    .fade-in {
                        animation: fadeIn 0.5s ease;
                    }
                    
                    .form-row {
                        margin-bottom: 1rem;
                    }
                    
                    @media (max-width: 768px) {
                        .modern-auth-container {
                            padding: 2rem 1rem;
                        }
                        .auth-title {
                            font-size: 2rem;
                        }

                        .inner-section-frontend {
                        padding: 0 !important;
                    }
                    }
                </style>
                
                <div id="{{ $componentId }}" style="{{ $styleStr }}" class="modern-auth-container">
                    {{-- Registration Success Modal --}}
                    @if(session('success'))
                    <div class="modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                                <div class="modal-header" style="border: none; padding: 2rem 2rem 1rem; flex-direction: column; align-items: center;">
                                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                        <i class="fa-solid fa-check" style="font-size: 2.5rem; color: white;"></i>
                                    </div>
                                    <h3 class="modal-title" id="successModalLabel" style="color: #2c3e50; font-weight: 700; margin: 0;">Congratulations!</h3>
                                </div>
                                <div class="modal-body text-center" style="padding: 1rem 2rem 2rem;">
                                    <p style="color: #666; font-size: 1.1rem; line-height: 1.6; margin-bottom: 1.5rem; white-space: pre-line;">{{ session('success') }}</p>
                                    <button type="button" class="btn" onclick="document.getElementById('successModal').querySelector('[data-bs-dismiss]').click()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 40px; border-radius: 25px; border: none; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
                                        Got it!
                                    </button>
                                    <button type="button" class="d-none" data-bs-dismiss="modal"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();
                            
                            // Auto scroll to auth form when modal is shown
                            document.getElementById('successModal').addEventListener('shown.bs.modal', function() {
                                document.getElementById('{{ $componentId }}').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            });
                        });
                    </script>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h5 class="alert-heading" style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.5rem;"></i>
                                <strong>{{ session('error') }}</strong>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="auth-header text-center fade-in">
                        <div class="auth-avatar">
                            <i class="fa-solid fa-circle-user" aria-hidden="true"></i>
                        </div>
                        <h2 class="auth-title tit">Register</h2>
                        <p class="text-muted mb-4">Join our community today</p>
                    </div>
                    
                    <div class="register fade-in" style="display: block; opacity: 1; transition: opacity 0.3s ease;">
                        <div class="container">
                            <form action="/register" method="POST" id="registerForm">
                                @csrf
                                <div class="row justify-content-center form-row">
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="first_name">
                                                <i class="fa-solid fa-user"></i> PARENT/GUARDIAN FIRST NAME
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="first_name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="last_name">
                                                <i class="fa-solid fa-user"></i> PARENT/GUARDIAN LAST NAME
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center form-row">
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="email">
                                                <i class="fa-solid fa-envelope"></i> PARENT/GUARDIAN EMAIL ADDRESS
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="confirm_email">
                                                <i class="fa-solid fa-envelope-circle-check"></i> CONFIRM PARENT/GUARDIAN EMAIL ADDRESS
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="confirm_email" name="confirm_email" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center form-row">
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="register_as">
                                                <i class="fa-solid fa-user-tag"></i> Register as
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <select class="form-select" id="register_as" name="register_as" onchange="toggleRegistrationFields(this)" required>
                                                <option value="individual">Individual</option>
                                                <option value="parent">Parent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="teacher_select_wrapper" style="display:block;">
                                        <div class="modern-form-group teacher-select-animate">
                                            <label for="teacher_id">
                                                <i class="fa-solid fa-chalkboard-teacher"></i> Select Teacher
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <select class="form-select" id="teacher_id" name="teacher_id" required>
                                                <option value="">Choose your teacher</option>
                                                @if(isset($teachers))
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center form-row">
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="password">
                                                <i class="fa-solid fa-lock"></i> Password
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="password" class="form-control password-input" id="password" name="password" required>
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password')">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="confirm_password">
                                                <i class="fa-solid fa-lock-keyhole"></i> Confirm password
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="password" class="form-control password-input" id="confirm_password" name="confirm_password" required>
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password')">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center mt-4">
                                    <div class="col-8">
                                        <div class="d-grid gap-3">
                                            <button class="modern-btn-primary" type="submit">
                                                <i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i>
                                                Create Account
                                            </button>
                                            <button class="modern-btn-link" type="button" onclick="showLoginForm('{{ $componentId }}')">
                                                Already have an account? <strong>Login</strong>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="login" style="display: none; opacity: 0; transition: opacity 0.3s ease;">
                        <div class="container fade-in">
                            <form action="/login" method="POST" id="loginForm">
                                @csrf
                                <div class="row justify-content-center form-row">
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="login_email">
                                                <i class="fa-solid fa-envelope"></i> Email address
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="login_email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="modern-form-group">
                                            <label for="login_password">
                                                <i class="fa-solid fa-lock"></i> Password
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <input type="password" class="form-control password-input" id="login_password" name="password" required>
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('login_password')">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center mt-4">
                                    <div class="col-8">
                                        <div class="d-grid gap-3">
                                            <button class="modern-btn-primary" type="submit">
                                                <i class="fa-solid fa-right-to-bracket me-2" aria-hidden="true"></i>
                                                Sign In
                                            </button>
                                            <button class="modern-btn-link" type="button" onclick="showRegisterForm('{{ $componentId }}')">
                                                Don't have an account? <strong>Register</strong>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        // Toggle password visibility function
                        function togglePasswordVisibility(fieldId) {
                            const passwordInput = document.getElementById(fieldId);
                            const toggleBtn = event.currentTarget;
                            const icon = toggleBtn.querySelector('i');
                            
                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                icon.classList.remove('fa-eye');
                                icon.classList.add('fa-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                icon.classList.remove('fa-eye-slash');
                                icon.classList.add('fa-eye');
                            }
                        }
                        
                        // Define toggleRegistrationFields globally first with smooth animations
                        if (!window.toggleRegistrationFields) {
                            window.toggleRegistrationFields = function(selectElement) {
                                const teacherWrapper = document.getElementById('teacher_select_wrapper');
                                if (teacherWrapper) {
                                    const innerGroup = teacherWrapper.querySelector('.modern-form-group');
                                    
                                    if (selectElement.value === 'individual') {
                                        teacherWrapper.style.display = 'block';
                                        if (innerGroup) {
                                            innerGroup.classList.remove('teacher-select-hide');
                                            innerGroup.classList.add('teacher-select-animate');
                                        }
                                        // Make teacher field required for individuals
                                        const teacherSelect = document.getElementById('teacher_id');
                                        if (teacherSelect) teacherSelect.setAttribute('required', 'required');
                                    } else {
                                        if (innerGroup) {
                                            innerGroup.classList.remove('teacher-select-animate');
                                            innerGroup.classList.add('teacher-select-hide');
                                            setTimeout(() => {
                                                teacherWrapper.style.display = 'none';
                                            }, 400);
                                        } else {
                                            teacherWrapper.style.display = 'none';
                                        }
                                        // Remove required for parents
                                        const teacherSelect = document.getElementById('teacher_id');
                                        if (teacherSelect) teacherSelect.removeAttribute('required');
                                    }
                                }
                            };
                        }
                        
                        // Form toggling with smooth transitions
                        function showLoginForm(componentId) {
                            console.log('showLoginForm called with componentId:', componentId);
                            const component = document.getElementById(componentId);
                            console.log('Found component:', component);
                            if (!component) {
                                console.warn('Auth form component not found:', componentId);
                                return;
                            }
                            
                            // Check if there's a success modal or error message
                            const successModal = document.getElementById('successModal');
                            const errorAlert = component.querySelector('.alert-danger');
                            const hasMessage = successModal || errorAlert;
                            
                            const registerForm = component.querySelector('.register');
                            const loginForm = component.querySelector('.login');
                            const titleElement = component.querySelector('.tit');
                            const subtitleElement = component.querySelector('.auth-header p');
                            
                            console.log('Register form:', registerForm);
                            console.log('Login form:', loginForm);
                            console.log('Has success modal or error message:', hasMessage);
                            
                            if (registerForm) {
                                registerForm.style.opacity = '0';
                                setTimeout(() => {
                                    registerForm.style.display = 'none';
                                    if (loginForm) {
                                        loginForm.style.display = 'block';
                                        loginForm.style.opacity = '0';
                                        // Force reflow before changing opacity
                                        loginForm.offsetHeight;
                                        setTimeout(() => {
                                            loginForm.style.opacity = '1';
                                            console.log('Login form should now be visible');
                                        }, 50);
                                        }, 50);
                                    }
                                }, 300);
                            }
                            
                            if (titleElement) titleElement.textContent = 'Login';
                            if (subtitleElement) subtitleElement.textContent = 'Welcome back!';
                            
                            console.log('Switched to login form for component:', componentId);
                        }
                        
                        function showRegisterForm(componentId) {
                            console.log('showRegisterForm called with componentId:', componentId);
                            const component = document.getElementById(componentId);
                            console.log('Found component:', component);
                            if (!component) {
                                console.warn('Auth form component not found:', componentId);
                                return;
                            }
                            
                            const registerForm = component.querySelector('.register');
                            const loginForm = component.querySelector('.login');
                            const titleElement = component.querySelector('.tit');
                            const subtitleElement = component.querySelector('.auth-header p');
                            
                            console.log('Register form:', registerForm);
                            console.log('Login form:', loginForm);
                            
                            if (loginForm) {
                                loginForm.style.opacity = '0';
                                setTimeout(() => {
                                    loginForm.style.display = 'none';
                                    if (registerForm) {
                                        registerForm.style.display = 'block';
                                        registerForm.style.opacity = '0';
                                        // Force reflow before changing opacity
                                        registerForm.offsetHeight;
                                        setTimeout(() => {
                                            registerForm.style.opacity = '1';
                                            console.log('Register form should now be visible');
                                        }, 50);
                                    }
                                }, 300);
                            }
                            
                            if (titleElement) titleElement.textContent = 'Register';
                            if (subtitleElement) subtitleElement.textContent = 'Join our community today';
                            
                            console.log('Switched to register form for component:', componentId);
                        }
                        
                        // Initialize form opacity for transitions
                        document.addEventListener('DOMContentLoaded', function() {
                            const component = document.getElementById('{{ $componentId }}');
                            if (component) {
                                const registerForm = component.querySelector('.register');
                                const loginForm = component.querySelector('.login');
                                if (registerForm) registerForm.style.transition = 'opacity 0.3s ease';
                                if (loginForm) loginForm.style.transition = 'opacity 0.3s ease';
                            }
                        });
                    </script>
                </div>
            @else
                {{-- Legacy auth-form with hardcoded HTML - fallback for existing components --}}
                
                {{-- Registration Success Modal --}}
                @if(session('success'))
                <div class="modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                            <div class="modal-header" style="border: none; padding: 2rem 2rem 1rem; flex-direction: column; align-items: center;">
                                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                    <i class="fa-solid fa-check" style="font-size: 2.5rem; color: white;"></i>
                                </div>
                                <h3 class="modal-title" id="successModalLabel" style="color: #2c3e50; font-weight: 700; margin: 0;">Congratulations!</h3>
                            </div>
                            <div class="modal-body text-center" style="padding: 1rem 2rem 2rem;">
                                <p style="color: #666; font-size: 1.1rem; line-height: 1.6; margin-bottom: 1.5rem; white-space: pre-line;">{{ session('success') }}</p>
                                <button type="button" class="btn" onclick="document.getElementById('successModal').querySelector('[data-bs-dismiss]').click()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 40px; border-radius: 25px; border: none; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
                                    Got it!
                                </button>
                                <button type="button" class="d-none" data-bs-dismiss="modal"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    });
                </script>
                @endif
                
                <style>
                    .legacy-auth-form .password-toggle-btn {
                        position: absolute;
                        right: 12px;
                        top: 50%;
                        transform: translateY(-50%);
                        background: none;
                        border: none;
                        cursor: pointer;
                        color: #666;
                        padding: 5px 8px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: color 0.2s;
                        z-index: 10;
                    }
                    .legacy-auth-form .password-toggle-btn:hover {
                        color: #2e4053;
                    }
                    .legacy-auth-form .required-asterisk {
                        color: #e74c3c;
                        font-weight: 700;
                        margin-left: 0.25rem;
                    }
                    .legacy-auth-form .password-input-wrapper {
                        position: relative;
                    }
                </style>
                <div style="{{ $styleStr }} margin-top: 3rem;" class="legacy-auth-form">
                    @if(isset($component['html']) && !empty($component['html']))
                        <script>console.log('📋 TEACHERS COUNT:', {{ count($teachers) }});</script>
                        {!! $component['html'] !!}

                        <script>
                        // Log teachers data immediately
                        const teachersData = @json($teachers ?? []);
                        console.log('👨‍🏫 TEACHERS DATA LOADED:', teachersData, 'Count:', teachersData.length);
                        
                        // Function to populate teachers
                        function populateTeachers() {
                            const teachers = teachersData;
                            console.log('🔍 Searching for teacher selects...');
                            
                            const teacherSelects = document.querySelectorAll('select[name="teacher_id"], select#teacher_id');
                            console.log('✅ Found teacher selects:', teacherSelects.length);
                            
                            if (teacherSelects.length === 0) {
                                console.warn('⚠️ No teacher selects found');
                                return false;
                            }
                            
                            teacherSelects.forEach(function(select, index) {
                                console.log('📝 Processing select #' + index, select);
                                
                                // Clear existing options except the first one (placeholder)
                                const initialLength = select.options.length;
                                while (select.options.length > 1) {
                                    select.remove(1);
                                }
                                console.log('   Cleared ' + (initialLength - 1) + ' options');
                                
                                // Add teacher options
                                teachers.forEach(function(teacher) {
                                    const option = document.createElement('option');
                                    option.value = teacher.id;
                                    option.textContent = teacher.name;
                                    select.appendChild(option);
                                });
                                
                                console.log('   ✅ Added ' + teachers.length + ' teachers, total options: ' + select.options.length);
                            });
                            
                            return true;
                        }
                        
                        // Try immediately
                        console.log('📌 Attempt 1: Populating immediately...');
                        if (!populateTeachers()) {
                            // Try after a short delay
                            setTimeout(function() {
                                console.log('📌 Attempt 2: Populating after 100ms...');
                                populateTeachers();
                            }, 100);
                            
                            // Try on DOMContentLoaded
                            document.addEventListener('DOMContentLoaded', function() {
                                console.log('📌 Attempt 3: Populating on DOMContentLoaded...');
                                populateTeachers();
                            });
                            
                            // Try after page fully loaded
                            window.addEventListener('load', function() {
                                console.log('📌 Attempt 4: Populating on window.load...');
                                populateTeachers();
                            });
                        }
                        
                        // Add global onclick functions for page builder preview
                            if (!window.showLoginFormPreview) {
                                window.showLoginFormPreview = function(button) {
                                    const container = button.closest('.auth-form-container') || button.closest('[style*="auth-form"]') || button.closest('div');
                                    
                                    const registerForm = document.getElementsByClassName('register');
                                    console.log(registerForm);
                                    const loginForm = document.getElementsByClassName('login');
                                    const titleElement = document.getElementsByClassName('tit');

                                    if (registerForm) registerForm[0].style.display = 'none';
                                    if (loginForm) loginForm[0].style.display = 'block';
                                    if (titleElement) titleElement[0].textContent = 'Login';
                                };
                            }
                            
                            if (!window.showRegisterFormPreview) {
                                window.showRegisterFormPreview = function(button) {
                                    const container = button.closest('.auth-form-container') || button.closest('[style*="auth-form"]') || button.closest('div');
                                    const registerForm = document.getElementsByClassName('register');
                                    const loginForm = document.getElementsByClassName('login');
                                    const titleElement = document.getElementsByClassName('tit');

                                    if (loginForm) loginForm[0].style.display = 'none';
                                    if (registerForm) registerForm[0].style.display = 'block';
                                    if (titleElement) titleElement.textContent = 'Register';
                                };
                            }
                            
                            // Add togglePasswordVisibility function for legacy auth-form
                            if (!window.togglePasswordVisibility) {
                                window.togglePasswordVisibility = function(fieldId) {
                                    const passwordInput = document.getElementById(fieldId);
                                    const toggleBtn = event.currentTarget;
                                    const icon = toggleBtn.querySelector('i');
                                    
                                    if (passwordInput && icon) {
                                        if (passwordInput.type === 'password') {
                                            passwordInput.type = 'text';
                                            icon.classList.remove('fa-eye');
                                            icon.classList.add('fa-eye-slash');
                                        } else {
                                            passwordInput.type = 'password';
                                            icon.classList.remove('fa-eye-slash');
                                            icon.classList.add('fa-eye');
                                        }
                                    }
                                };
                            }
                    </script>
                    @else
                        {{-- Default auth form using dynamic properties from component --}}
                        <style>
                            .dynamic-auth-container {
                                background-color: transparent;
                                /* padding: 2rem; */
                                border-radius: 0.5rem;
                            }
                            .dynamic-auth-container .form-label {
                                color: #333;
                                font-weight: 500;
                            }
                            .dynamic-auth-container .form-select,
                            .dynamic-auth-container .form-control {
                                border-color: #ddd;
                            }
                            .dynamic-auth-container .form-select:focus,
                            .dynamic-auth-container .form-control:focus {
                                border-color: {{ $buttonColor }};
                                box-shadow: 0 0 0 0.2rem {{ $buttonColor }}33;
                            }
                            .dynamic-auth-container .btn-primary-custom {
                                background-color: {{ $buttonColor }};
                                color: {{ $buttonTextColor }};
                                border-color: {{ $buttonColor }};
                                font-weight: 600;
                            }
                            .dynamic-auth-container .btn-primary-custom:hover {
                                background-color: {{ $buttonColor }};
                                color: {{ $buttonTextColor }};
                                opacity: 0.9;
                            }
                            .dynamic-auth-container .btn-link-custom {
                                color: {{ $linkColor }};
                                text-decoration: none;
                                font-weight: 500;
                                padding: 0.5rem 1rem;
                            }
                            .dynamic-auth-container .btn-link-custom:hover {
                                color: {{ $linkColor }};
                                opacity: 0.8;
                            }
                            .dynamic-auth-container .avatar-icon {
                                color: {{ $avatarIconColor }};
                            }
                        </style>
                        <div class="auth-form-container dynamic-auth-container">
                            <div class="row">
                                <div class="col-md-12 mt-4 mb-4 text-center">
                                    <i class="fa-solid fa-circle-user fa-fw mb-3 avatar-icon" aria-hidden="true" style="font-size: 8rem !important;"></i>
                                    <h2 class="display-6 tit">Parent/Guardian Registration</h2>
                                </div>
                            </div>
                            <div class="register">
                                <div class="container">
                                    <form action="/register" method="POST">
                                        @csrf
                                        <input type="hidden" name="register_as" value="parents">
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <label for="first_name" class="form-label">Parent/Guardian First Name<span class="required-asterisk">*</span></label>
                                                <input type="text" class="form-control" id="first_name" name="name" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="last_name" class="form-label">Parent/Guardian Last Name<span class="required-asterisk">*</span></label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <label for="email" class="form-label">Parent/Guardian Email Address<span class="required-asterisk">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="confirm_email" class="form-label">Confirm Parent/Guardian Email Address<span class="required-asterisk">*</span></label>
                                                <input type="email" class="form-control" id="confirm_email" name="confirm_email" required>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <label for="password" class="form-label">Parent/Guardian Password<span class="required-asterisk">*</span></label>
                                                <div class="password-input-wrapper">
                                                    <input type="password" class="form-control" id="password" name="password" required>
                                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password')"><i class="fa-solid fa-eye"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="confirm_password" class="form-label">Confirm Parent/Guardian Password<span class="required-asterisk">*</span></label>
                                                <div class="password-input-wrapper">
                                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password')"><i class="fa-solid fa-eye"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-8">
                                                <div class="d-grid gap-3 mt-2">
                                                    <button class="btn btn-lg text-white btn-primary-custom" type="submit">
                                                        <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                                        Register
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                        function toggleRegistrationFields(selectElement) {
                            const teacherWrapper = document.getElementById('teacher_select_wrapper');
                            if (teacherWrapper) {
                                if (selectElement.value === 'individual') {
                                    teacherWrapper.style.display = 'block';
                                } else {
                                    teacherWrapper.style.display = 'none';
                                }
                            }
                        }
                        
                        // Global togglePasswordVisibility function for default auth-form
                        if (!window.togglePasswordVisibility) {
                            window.togglePasswordVisibility = function(fieldId) {
                                const passwordInput = document.getElementById(fieldId);
                                const toggleBtn = event.currentTarget;
                                const icon = toggleBtn.querySelector('i');
                                
                                if (passwordInput && icon) {
                                    if (passwordInput.type === 'password') {
                                        passwordInput.type = 'text';
                                        icon.classList.remove('fa-eye');
                                        icon.classList.add('fa-eye-slash');
                                    } else {
                                        passwordInput.type = 'password';
                                        icon.classList.remove('fa-eye-slash');
                                        icon.classList.add('fa-eye');
                                    }
                                }
                            };
                        }
                        </script>
                    @endif
                </div>
            @endif
        @break

        @case('social-share')
            @php
                // Always use dynamic rendering for social-share, ignore legacy HTML
                $shareData = $component['shareData'] ?? [];
                
                // If no structured data exists, use smart defaults
                if (empty($shareData)) {
                    $shareData = [
                        'title' => 'I Just Want to Help!',
                        'show_title' => true,
                        'icon_size' => '4rem',
                        'icon_color' => '#1877f2',
                        'text_color' => '#000000',
                        'title_color' => '#000000',
                        'max_columns' => 4,
                        'platforms' => [
                            'facebook' => ['enabled' => true, 'url' => '', 'text' => 'Share on Facebook'],
                            'twitter' => ['enabled' => true, 'url' => '', 'text' => 'Share on Twitter'], 
                            'linkedin' => ['enabled' => true, 'url' => '', 'text' => 'Share on LinkedIn'],
                            'instagram' => ['enabled' => true, 'url' => '', 'text' => 'Share on Instagram'],
                            'tiktok' => ['enabled' => false, 'url' => '', 'text' => 'Share on TikTok'],
                            'youtube' => ['enabled' => false, 'url' => '', 'text' => 'Share on YouTube'],
                            'pinterest' => ['enabled' => false, 'url' => '', 'text' => 'Share on Pinterest'],
                            'whatsapp' => ['enabled' => false, 'url' => '', 'text' => 'Share on WhatsApp'],
                            'telegram' => ['enabled' => false, 'url' => '', 'text' => 'Share on Telegram'],
                            'copy' => ['enabled' => true, 'url' => url()->current(), 'text' => 'Copy Link']
                        ]
                    ];
                }
                
                $platforms = $shareData['platforms'] ?? [
                    'facebook' => ['enabled' => true, 'url' => '', 'text' => 'Share on Facebook'],
                    'twitter' => ['enabled' => true, 'url' => '', 'text' => 'Share on Twitter'], 
                    'linkedin' => ['enabled' => true, 'url' => '', 'text' => 'Share on LinkedIn'],
                    'instagram' => ['enabled' => true, 'url' => '', 'text' => 'Share on Instagram'],
                    'tiktok' => ['enabled' => false, 'url' => '', 'text' => 'Share on TikTok'],
                    'youtube' => ['enabled' => false, 'url' => '', 'text' => 'Share on YouTube'],
                    'pinterest' => ['enabled' => false, 'url' => '', 'text' => 'Share on Pinterest'],
                    'whatsapp' => ['enabled' => false, 'url' => '', 'text' => 'Share on WhatsApp'],
                    'telegram' => ['enabled' => false, 'url' => '', 'text' => 'Share on Telegram'],
                    /* 'copy' => ['enabled' => true, 'url' => url()->current(), 'text' => 'Copy Link'] */
                ];
                
                $mainTitle = $shareData['title'] ?? 'I Just Want to Help!';
                $iconSize = $shareData['icon_size'] ?? $shareData['iconSize'] ?? '4rem';
                $iconColor = $shareData['icon_color'] ?? $shareData['iconColor'] ?? '';
                $textColor = $shareData['text_color'] ?? $shareData['textColor'] ?? '#000000';
                $titleColor = $shareData['title_color'] ?? $shareData['titleColor'] ?? '#000000';
                $showTitle = $shareData['show_title'] ?? $shareData['showTitle'] ?? true;
                $layout = $shareData['layout'] ?? 'grid';
                $maxColumns = $shareData['max_columns'] ?? $shareData['maxColumns'] ?? 4;
                
                // Calculate bootstrap class based on maxColumns with better responsive behavior
                $colXl = 12 / $maxColumns; // Large screens: use max columns
                $colLg = min(12 / max(1, $maxColumns - 1), 6); // Medium screens: reduce by 1 column, max 6 cols (2 per row)
                $colMd = min(12 / max(1, $maxColumns - 2), 4); // Small tablets: reduce by 2, max 4 cols (3 per row)
                $colSm = 6; // Small screens: always 2 per row
                $col = 12; // Extra small: 1 per row
                
                $bootstrapClass = "col-{$col} col-sm-{$colSm} col-md-{$colMd} col-lg-{$colLg} col-xl-{$colXl}";
                
                // Get current page URL for sharing
                $currentUrl = url()->current();
                $pageTitle = $mainTitle;
            @endphp
            
            <div class="social-share-component" style="{{ $styleStr }}">
                {{-- Add responsive CSS for better icon layout --}}
                <style>
                .social-share-component .row {
                    --bs-gutter-x: 1rem;
                    --bs-gutter-y: 1rem;
                }
                
                .social-share-component a {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    transition: transform 0.2s ease, opacity 0.2s ease;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
                }
                
                .social-share-component a:hover {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
                
                @media (max-width: 576px) {
                    .social-share-component .row {
                        --bs-gutter-x: 0.5rem;
                        --bs-gutter-y: 0.75rem;
                    }
                    
                    .social-share-component a {
                        padding: 0.25rem;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                </style>
                
                @if($showTitle && $mainTitle)
                    <div class="text-center mb-4">
                        <h2 class="display-5 fw-normal" style="color: {{ $titleColor }};">{{ $mainTitle }}</h2>
                    </div>
                @endif
                
                <div class="row justify-content-center align-items-center">
                    @foreach($platforms as $platform => $config)
                        @if($config['enabled'] ?? false)
                            {{-- <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center"> --}}
                                    @switch($platform)
                                        @case('facebook')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($currentUrl);
                                            @endphp
                                            <a class="text-center btn-facebook-share" href="{{ $shareUrl }}" target="_blank" 
                                               style="color: {{ $iconColor ?: '#1877f2' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('twitter')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://twitter.com/intent/tweet?url=" . urlencode($currentUrl) . "&text=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-twitter-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#1da1f2' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('linkedin')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($currentUrl);
                                            @endphp
                                            <a class="text-center btn-linkedin-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#0077b5' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('instagram')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.instagram.com/";
                                            @endphp
                                            <a class="text-center btn-instagram-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#e1306c' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('tiktok')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.tiktok.com/";
                                            @endphp
                                            <a class="text-center btn-tiktok-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#000000' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('youtube')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.youtube.com/";
                                            @endphp
                                            <a class="text-center btn-youtube-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#ff0000' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('pinterest')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://pinterest.com/pin/create/button/?url=" . urlencode($currentUrl) . "&description=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-pinterest-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#bd081c' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.404-5.958 1.404-5.958s-.359-.219-.359-1.219c0-1.141.66-1.993 1.482-1.993.699 0 1.037.525 1.037 1.155 0 .703-.449 1.753-.68 2.723-.194.821.412 1.492 1.222 1.492 1.467 0 2.595-1.544 2.595-3.773 0-1.972-1.415-3.353-3.437-3.353-2.343 0-3.718 1.756-3.718 3.571 0 .708.273 1.466.614 1.878.067.082.077.154.057.238-.062.26-.2.814-.227.927-.035.146-.116.177-.268.107-1.001-.465-1.624-1.926-1.624-3.1 0-2.596 1.884-4.982 5.432-4.982 2.851 0 5.071 2.032 5.071 4.75 0 2.837-1.789 5.121-4.27 5.121-.834 0-1.622-.435-1.89-1.013l-.514 1.96c-.185.716-.685 1.613-1.019 2.16C9.394 23.924 10.675 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('whatsapp')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://wa.me/?text=" . urlencode($pageTitle . ' ' . $currentUrl);
                                            @endphp
                                            <a class="text-center btn-whatsapp-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#25d366' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('telegram')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://t.me/share/url?url=" . urlencode($currentUrl) . "&text=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-telegram-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#0088cc' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                    @endswitch
                                {{-- </div>
                            </div> --}}
                        @endif
                    @endforeach
                </div>
            </div>
        @break

        @case('statistics-metric')
            @php
            // dd($component);
                // Get statistics data from component
                $statisticsData = $component['statisticsData'] ?? [];
                $metric = $statisticsData['metric'] ?? '3X';
                $description = $statisticsData['description'] ?? 'More lithium extracted than conventional methods';
                $metricColor = $statisticsData['metricColor'] ?? '#14B8A6'; // Teal/cyan default
                $descriptionColor = $statisticsData['descriptionColor'] ?? '#FFFFFF';
                $backgroundColor = $statisticsData['backgroundColor'] ?? '#1F2937'; // Dark gray default
                $backgroundType = $statisticsData['backgroundType'] ?? 'color';
                $backgroundImage = $statisticsData['backgroundImage'] ?? '';
                $borderRadius = $statisticsData['borderRadius'] ?? '12px';
                $padding = $statisticsData['padding'] ?? '3rem 2rem';
                $textAlign = $statisticsData['textAlign'] ?? 'center';
                $metricFontSize = $statisticsData['metricFontSize'] ?? '4rem';
                $descriptionFontSize = $statisticsData['descriptionFontSize'] ?? '1.25rem';
                $maxWidth = $statisticsData['maxWidth'] ?? '400px';
                $marginBottom = $statisticsData['marginBottom'] ?? '1.5rem';
                
                // Build background style
                $backgroundStyle = '';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    if (!empty($imageUrl)) {
                        // Add overlay for better text readability
                        $backgroundStyle = "background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{$imageUrl}'); background-size: cover; background-position: center; background-repeat: no-repeat;";
                    } else {
                        $backgroundStyle = "background-color: {$backgroundColor};";
                    }
                } else {
                    $backgroundStyle = "background-color: {$backgroundColor};";
                }
            @endphp
            
            <div id="{{ $componentId }}" class="statistics-metric-component" style="{{ $styleStr }}">
                <div class="statistics-metric-card" 
                     style="{{ $backgroundStyle }} 
                            padding: {{ $padding }}; 
                            border-radius: {{ $borderRadius }}; 
                            text-align: {{ $textAlign }}; 
                            max-width: {{ $maxWidth }}; 
                            margin: 0 auto;">
                    
                    {{-- Large metric display --}}
                    <div class="metric-number" 
                         style="font-size: {{ $metricFontSize }}; 
                                font-weight: 900; 
                                color: {{ $metricColor }}; 
                                line-height: 1; 
                                margin-bottom: {{ $marginBottom }}; 
                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                        {{ $metric }}
                    </div>
                    
                    {{-- Description text --}}
                    <div class="metric-description" 
                         style="font-size: {{ $descriptionFontSize }}; 
                                color: {{ $descriptionColor }}; 
                                line-height: 1.5; 
                                font-weight: 400; 
                                margin: 0; 
                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                        {{ $description }}
                    </div>
                </div>
            </div>
            
            {{-- Responsive styles --}}
            <style>
                #{{ $componentId }} .statistics-metric-card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                
                #{{ $componentId }} .statistics-metric-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                }
                
                @media (max-width: 768px) {
                    #{{ $componentId }} .metric-number {
                        font-size: calc({{ $metricFontSize }} * 0.75) !important;
                    }
                    
                    #{{ $componentId }} .metric-description {
                        font-size: calc({{ $descriptionFontSize }} * 0.9) !important;
                    }
                    
                    #{{ $componentId }} .statistics-metric-card {
                        max-width: 100% !important;
                        padding: 2rem 1.5rem !important;
                    }
                }
                
                @media (max-width: 480px) {
                    #{{ $componentId }} .metric-number {
                        font-size: calc({{ $metricFontSize }} * 0.6) !important;
                    }
                    
                    #{{ $componentId }} .metric-description {
                        font-size: calc({{ $descriptionFontSize }} * 0.85) !important;
                    }
                    
                    #{{ $componentId }} .statistics-metric-card {
                        padding: 1.5rem 1rem !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>
        @break

        @case('donation-form')
            <div style="{{ $styleStr }}">
                @if(isset($component['html']) && !empty($component['html']))
                    {!! $component['html'] !!}
                @else
                    {{-- Use exact donation form structure with dynamic properties from component --}}
                    @php
                        // Extract donation form properties from component
                        $donationFormData = $component['donationFormData'] ?? [];
                        $formTitle = $donationFormData['formTitle'] ?? 'Make a general donation';
                        $borderColor = $donationFormData['borderColor'] ?? '#2e4053';
                        $headerColor = $donationFormData['headerColor'] ?? '#2e4053';
                        $headerTextColor = $donationFormData['headerTextColor'] ?? '#ffffff';
                        $backgroundColor = $donationFormData['backgroundColor'] ?? '#ffffff';
                        $feeText = $donationFormData['feeText'] ?? 'I elect to pay the fees';
                        $feeTooltip = $donationFormData['feeTooltip'] ?? 'By selecting this option, you elect to pay the credit card and transaction fees for this donation. The fees will be displayed in the next step.';
                        $anonymousText = $donationFormData['anonymousText'] ?? 'Anonymous';
                        $anonymousTooltip = $donationFormData['anonymousTooltip'] ?? 'Selecting this option will hide your name from everyone but the organizer.';
                        $anonymousDescription = $donationFormData['anonymousDescription'] ?? 'Choose to make your donation anonymous';
                        $buttonText = $donationFormData['buttonText'] ?? 'Donate';
                    @endphp
                    <style>
                        .dynamic-donation-form .form-control:focus,
                        .dynamic-donation-form .form-select:focus {
                            border-color: {{ $borderColor }};
                            box-shadow: 0 0 0 0.2rem {{ $borderColor }}33;
                        }
                        .dynamic-donation-form .input-group-text {
                            border-color: {{ $borderColor }};
                            background-color: #fff;
                            color: #333;
                        }
                        .dynamic-donation-form .card {
                            border-color: {{ $borderColor }};
                        }
                        .dynamic-donation-form .form-check-input:checked {
                            background-color: {{ $headerColor }};
                            border-color: {{ $headerColor }};
                        }
                        .dynamic-donation-form .form-check-input:focus {
                            border-color: {{ $headerColor }};
                            box-shadow: 0 0 0 0.25rem {{ $headerColor }}44;
                        }
                    </style>
                    <div class="donation-form-component dynamic-donation-form" style="margin-top: 3rem;">
                        <form method="POST" action="/donation-general" class="donation-form-block">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card shadow" style="border-width: 3px; border-color: {{ $borderColor }} !important;">
                                    <div class="card-header rounded-0 text-center fs-2"
                                        style="border-width: 3px !important; border-color: {{ $headerColor }} !important; background-color: {{ $headerColor }} !important; color: {{ $headerTextColor }} !important;">
                                        {{ $formTitle }}
                                    </div>
                                    <div class="card-body" style="background-color: {{ $backgroundColor }} !important;">
                                        <input type="hidden" name="profile_uuid" value="">
                                        <input type="hidden" name="team_uuid" value="">

                                        <div class="row gy-3">
                                            <div class="col-12">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-right-width: 0; border-color: {{ $borderColor }} !important;">$</span>
                                                    <input type="number" placeholder="0"
                                                        class="form-control fs-2 fs-lg-4 text-center border-primary"
                                                        style="border-width: 2px; border-color: {{ $borderColor }} !important;" name="donation_amount" value="" required>
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-left-width: 0; border-color: {{ $borderColor }} !important;">.00</span>
                                                </div>
                                                <input type="hidden" name="amount" value="">
                                                <div class="text-center">
                                                    <small class="form-text text-muted">
                                                        * The minimum donation amount is 8.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center align-items-center">
                                                <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: {{ $borderColor }} !important;">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="pay_fees" name="pay_fees" checked="">
                                                        <label class="form-check-label fw-semibold" for="pay_fees">
                                                            {{ $feeText }}
                                                        </label>
                                                        <i role="button"
                                                            class="fa-solid fa-circle-info text-info btn-modal-info ms-2"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ $feeTooltip }}"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="first_name" class="form-label fw-semibold required">
                                                    First name
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="" required>
                                            </div>

                                            <div class="col-12">
                                                <label for="last_name" class="form-label fw-semibold required">
                                                    Last name
                                                </label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="" required>
                                            </div>

                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold required">
                                                    Email address
                                                </label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="" required>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="anonymous_donation" name="anonymous_donation">
                                                    <label class="form-check-label fw-semibold" for="anonymous_donation">
                                                        {{ $anonymousText }}
                                                    </label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info btn-modal-info ms-2"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $anonymousTooltip }}"></i>
                                                    <small class="text-muted d-block mt-1">{{ $anonymousDescription }}</small>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer rounded-0 p-0"
                                        style="border-width: 3px !important; border-color: {{ $headerColor }} !important; background-color: {{ $headerColor }} !important;">
                                        <button type="submit"
                                            class="btn btn-lg w-100 h-100 rounded-0 shadow-none" 
                                            style="background: {{ $headerColor }} !important; border-color: {{ $headerColor }} !important; color: {{ $headerTextColor }} !important;">
                                            {{ $buttonText }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @break

        @case('ticket-carousel')
            @php
                $properties = $component['properties'] ?? [];
                $slidesToShow = $properties['slides_to_show'] ?? 3;
                $autoplay = ($properties['autoplay'] ?? 0) == 1;
                $autoplaySpeed = $properties['autoplay_speed'] ?? 3000;
                $dots = true;
                $arrows = true;
                $sliderId = 'ticket-slider-' . ($componentId ?? uniqid());

                $cardBackgroundColor = $properties['card_background_color'] ?? '#ffffff';
                $cardBorderRadius = '8px';
                $titleColor = '#000';
                $priceColor = $properties['price_text_color'] ?? '#2e7d3e';
                $descriptionColor = $properties['description_text_color'] ?? '#666666';
                $buttonBackgroundColor = $properties['button_background_color'] ?? '#007bff';
                $buttonTextColor = $properties['button_text_color'] ?? '#ffffff';

                $tickets = \App\Models\Ticket::where('website_id', $check->id ?? 1)
                    ->where('status', 1)
                    ->where('type', 'product')
                    ->latest()
                    ->get();
            @endphp

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

            <div class="ticket-carousel-component" style="{{ $styleStr }}">
                <style>
                    .ticket-carousel-wrapper-{{ $sliderId }} {
                        position: relative;
                    }
                    #{{ $sliderId }} .owl-nav { display: none; }
                    .custom-nav-{{ $sliderId }} {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 100%;
                        pointer-events: none;
                        z-index: 100;
                        left: 0;
                        right: 0;
                    }
                    .custom-nav-{{ $sliderId }} button {
                        position: absolute;
                        width: 45px;
                        height: 45px;
                        background: rgba(0,0,0,0.6) !important;
                        color: white !important;
                        border: none;
                        border-radius: 50%;
                        font-size: 24px;
                        font-weight: normal;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        opacity: 0.9;
                        pointer-events: all;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        line-height: 1;
                        padding: 0;
                    }
                    .custom-nav-{{ $sliderId }} button:hover {
                        opacity: 1;
                        transform: scale(1.1);
                        background: rgba(0,0,0,0.8) !important;
                    }
                    .custom-nav-{{ $sliderId }} .prev { left: 15px; }
                    .custom-nav-{{ $sliderId }} .next { right: 15px; }
                    @media (max-width: 768px) {
                        .custom-nav-{{ $sliderId }} button {
                            width: 35px;
                            height: 35px;
                            font-size: 20px;
                        }
                        .custom-nav-{{ $sliderId }} .prev { left: 10px; }
                        .custom-nav-{{ $sliderId }} .next { right: 10px; }
                    }
                </style>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @if($tickets->count() > 0)
                                <div class="ticket-carousel-wrapper-{{ $sliderId }}">
                                    <div class="owl-carousel ticket-carousel" id="{{ $sliderId }}">
                                    @foreach($tickets as $ticket)
                                        <div class="ticket-card-wrapper">
                                            <div class="ticket-card">
                                                <a href="{{ route('product.details', $ticket->slug) }}" class="ticket-link">
                                                    <div class="ticket-image">
                                                        <img src="{{ asset($ticket->image) }}" alt="{{ $ticket->name }}">
                                                    </div>
                                                    <div class="ticket-info">
                                                        <h3 class="ticket-title">{{ $ticket->name }}</h3>
                                                        <div class="ticket-meta">
                                                            <span class="ticket-price">${{ number_format($ticket->price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                    <div class="custom-nav-{{ $sliderId }}">
                                        <button class="prev" onclick="$('#{{ $sliderId }}').trigger('prev.owl.carousel')">‹</button>
                                        <button class="next" onclick="$('#{{ $sliderId }}').trigger('next.owl.carousel')">›</button>
                                    </div>
                                </div>
                            @else
                                <div class="no-tickets">
                                    <p>No tickets available at this time.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <style>
            .ticket-carousel-component { padding: 40px 0; }
            .ticket-card-wrapper { display: flex; justify-content: center; }
            .ticket-card {
                background: {{ $cardBackgroundColor }};
                border-radius: {{ $cardBorderRadius }};
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
                max-width: 262px;  /* Desktop max width */
                width: 100%;       /* Responsive width */
                margin: 0 10px;
            }
            .ticket-card:hover { transform: translateY(-5px); }
            .ticket-link { text-decoration: none; color: inherit; display: block; }
            .ticket-image {
                position: relative;
                height: 262px;      /* Fixed height for image */
                width: 100%;
                overflow: hidden;
            }
            .ticket-image img {
                position: absolute; top:0; left:0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .ticket-info { padding: 15px; }
            .ticket-title {
                font-size: 0.875rem !important;
                font-weight: 400 !important;
                color: {{ $titleColor }};
                margin-bottom: 8px;
                overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            }
            .ticket-meta { display: flex; justify-content: space-between; align-items: center; }
            .ticket-price { font-size: 18px; font-weight: 700; color: {{ $priceColor }}; }
            .no-tickets { text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; }

            /* Responsive */
            @media (max-width: 768px) {
                .ticket-carousel-component { padding: 20px 0; }
                .ticket-card { margin: 0 auto; } /* Center card */
                .ticket-title { font-size: 14px; }
                .ticket-price { font-size: 16px; }
            }
            </style>

            <script>
            $(document).ready(function(){
                $("#{{ $sliderId }}").owlCarousel({
                    loop: true,
                    margin: 20,
                    autoplay: {{ $autoplay ? 'true' : 'false' }},
                    autoplayTimeout: {{ $autoplaySpeed }},
                    autoplayHoverPause: true,
                    dots: {{ $dots ? 'true' : 'false' }},
                    nav: false,
                    responsive: {
                        0: { items: 1 },                    // Mobile: 1 item
                        576: { items: 1 },
                        768: { items: Math.min({{ $slidesToShow }}, 2) }, // Tablets
                        992: { items: {{ min($slidesToShow, 3) }} }       // Desktop
                    }
                });
            });
            </script>
        @break


        @case('ticket-category-carousel')
            @php
                $properties = $component['properties'] ?? [];
                $selectedCategoryId = $properties['category_id'] ?? null;
                $slidesToShow = $properties['slides_to_show'] ?? 3;
                $autoplay = ($properties['autoplay'] ?? 0) == 1;
                $autoplaySpeed = $properties['autoplay_speed'] ?? 3000;
                $enableLoop = ($properties['loop'] ?? 1) == 1;
                $dots = true;
                $arrows = true;
                $sliderId = 'ticket-category-slider-' . ($componentId ?? uniqid());

                $cardBackgroundColor = $properties['card_background_color'] ?? '#ffffff';
                $cardBorderRadius = '8px';
                $titleColor = '#000';
                $priceColor = $properties['price_text_color'] ?? '#2e7d3e';
                $descriptionColor = $properties['description_text_color'] ?? '#666666';
                $buttonBackgroundColor = $properties['button_background_color'] ?? '#007bff';
                $buttonTextColor = $properties['button_text_color'] ?? '#ffffff';

                $query = \App\Models\Ticket::where('website_id', $check->id ?? 1)
                    ->where('status', 1)
                    ->where('type', 'product');

                if ($selectedCategoryId) {
                    $query->where('category_id', $selectedCategoryId);
                }

                $tickets = $query->latest()->get();
                $actualLoop = $enableLoop && count($tickets) >= $slidesToShow;

                $ticketCount = count($tickets);
                $debugInfo = [
                    'enableLoop' => $enableLoop,
                    'ticketCount' => $ticketCount,
                    'slidesToShow' => $slidesToShow,
                    'actualLoop' => $actualLoop
                ];
            @endphp

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

            <div class="ticket-category-carousel-component" style="{{ $styleStr }}">
                <style>
                    .ticket-cat-wrapper-{{ $sliderId }} {
                        position: relative;
                    }
                    #{{ $sliderId }} .owl-nav { display: none; }
                    .custom-nav-cat-{{ $sliderId }} {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 100%;
                        pointer-events: none;
                        z-index: 100;
                        left: 0;
                        right: 0;
                    }
                    .custom-nav-cat-{{ $sliderId }} button {
                        position: absolute;
                        width: 45px;
                        height: 45px;
                        background: rgba(0,0,0,0.6) !important;
                        color: white !important;
                        border: none;
                        border-radius: 50%;
                        font-size: 24px;
                        font-weight: normal;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        opacity: 0.9;
                        pointer-events: all;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        line-height: 1;
                        padding: 0;
                    }
                    .custom-nav-cat-{{ $sliderId }} button:hover {
                        opacity: 1;
                        transform: scale(1.1);
                        background: rgba(0,0,0,0.8) !important;
                    }
                    .custom-nav-cat-{{ $sliderId }} .prev { left: 15px; }
                    .custom-nav-cat-{{ $sliderId }} .next { right: 15px; }
                    @media (max-width: 768px) {
                        .custom-nav-cat-{{ $sliderId }} button {
                            width: 35px;
                            height: 35px;
                            font-size: 20px;
                        }
                        .custom-nav-cat-{{ $sliderId }} .prev { left: 10px; }
                        .custom-nav-cat-{{ $sliderId }} .next { right: 10px; }
                    }
                </style>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @if($tickets->count() > 0)
                                <div class="ticket-cat-wrapper-{{ $sliderId }}">
                                    <div class="owl-carousel ticket-category-carousel" id="{{ $sliderId }}">
                                    @foreach($tickets as $ticket)
                                        <div class="ticket-card-wrapper">
                                            <div class="ticket-card">
                                                <a href="{{ route('product.details', $ticket->slug) }}" class="ticket-link">
                                                    <div class="ticket-image">
                                                        <img src="{{ asset($ticket->image) }}" alt="{{ $ticket->name }}">
                                                    </div>
                                                    <div class="ticket-info">
                                                        <h3 class="ticket-title">{{ $ticket->name }}</h3>
                                                        <div class="ticket-meta">
                                                            <span class="ticket-price">${{ number_format($ticket->price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                    <div class="custom-nav-cat-{{ $sliderId }}">
                                        <button class="prev" onclick="$('#{{ $sliderId }}').trigger('prev.owl.carousel')">‹</button>
                                        <button class="next" onclick="$('#{{ $sliderId }}').trigger('next.owl.carousel')">›</button>
                                    </div>
                                </div>
                            @else
                                <div class="no-tickets">
                                    <p>No tickets available for this category at this time.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <style>
            .ticket-category-carousel-component { padding: 40px 0; }
            .ticket-card-wrapper { display: flex; justify-content: center; }
            .ticket-card {
                background: {{ $cardBackgroundColor }};
                border-radius: {{ $cardBorderRadius }};
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
                max-width: 262px;  /* Desktop max width */
                width: 100%;       /* Responsive width */
                margin: 0 10px;
            }
            .ticket-card:hover { transform: translateY(-5px); }
            .ticket-link { text-decoration: none; color: inherit; display: block; }
            .ticket-image {
                position: relative;
                height: 262px;      /* Fixed image height */
                width: 100%;
                overflow: hidden;
            }
            .ticket-image img {
                position: absolute; top:0; left:0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .ticket-info { padding: 15px; }
            .ticket-title {
                font-size: 0.875rem !important;
                font-weight: 400 !important;
                color: {{ $titleColor }};
                margin-bottom: 8px;
                overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            }
            .ticket-meta { display: flex; justify-content: space-between; align-items: center; }
            .ticket-price { font-size: 18px; font-weight: 700; color: {{ $priceColor }}; }
            .no-tickets { text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; }

            /* Responsive */
            @media (max-width: 768px) {
                .ticket-category-carousel-component { padding: 20px 0; }
                .ticket-card { margin: 0 auto; } /* Center card on mobile */
                .ticket-title { font-size: 14px; }
                .ticket-price { font-size: 16px; }
            }
            </style>

            <script>
            console.log('Ticket Category Carousel Debug:', {!! json_encode($debugInfo) !!});

            $(document).ready(function(){
                $("#{{ $sliderId }}").owlCarousel({
                    loop: {{ $actualLoop ? 'true' : 'false' }},
                    margin: 20,
                    autoplay: {{ $autoplay ? 'true' : 'false' }},
                    autoplayTimeout: {{ $autoplaySpeed }},
                    autoplayHoverPause: true,
                    dots: {{ $dots ? 'true' : 'false' }},
                    nav: false,
                    responsive: {
                        0: {
                            items: 1,
                            loop: {{ count($tickets) >= 1 && $enableLoop ? 'true' : 'false' }}
                        },
                        576: {
                            items: 1,
                            loop: {{ count($tickets) >= 1 && $enableLoop ? 'true' : 'false' }}
                        },
                        768: {
                            items: Math.min({{ $slidesToShow }}, 2),
                            loop: {{ count($tickets) >= 2 && $enableLoop ? 'true' : 'false' }}
                        },
                        992: {
                            items: {{ min($slidesToShow, 3) }},
                            loop: {{ count($tickets) >= min($slidesToShow, 3) && $enableLoop ? 'true' : 'false' }}
                        }
                    }
                });
            });
            </script>
        @break


        @case('property-category-carousel')
            @php
                // Get component properties
                $properties = $component['properties'] ?? [];
                $selectedCategoryId = $properties['category_id'] ?? null;
                $slidesToShow = $properties['slides_to_show'] ?? 3;
                $autoplay = ($properties['autoplay'] ?? 0) == 1;
                $autoplaySpeed = $properties['autoplay_speed'] ?? 3000;
                $enableLoop = ($properties['loop'] ?? 1) == 1;
                $dots = true; // Default to true for now
                $arrows = true; // Default to true for now
                $sliderId = 'property-category-slider-' . ($componentId ?? uniqid());
                
                // Styling options
                $cardBackgroundColor = $properties['card_background_color'] ?? '#ffffff';
                $cardBorderRadius = '8px'; // Default value
                $titleColor = '#000'; // Default value
                $priceColor = $properties['price_text_color'] ?? '#2e7d3e';
                $descriptionColor = $properties['description_text_color'] ?? '#666666';
                $buttonBackgroundColor = $properties['button_background_color'] ?? '#007bff';
                $buttonTextColor = $properties['button_text_color'] ?? '#ffffff';

                // Get properties filtered by category for this website
                $query = \App\Models\Ticket::where('website_id', $check->id ?? 1)
                    ->where('status', 1)
                    ->where('type', 'property');
                
                // Apply category filter if selected
                if ($selectedCategoryId) {
                    $query->where('category_id', $selectedCategoryId);
                }
                
                $properties = $query->latest()->get();
                
                // Determine if loop should be enabled (only if we have enough items)
                $actualLoop = $enableLoop && count($properties) >= $slidesToShow;
                
                // Debug information
                $propertyCount = count($properties);
                $debugInfo = [
                    'enableLoop' => $enableLoop,
                    'propertyCount' => $propertyCount,
                    'slidesToShow' => $slidesToShow,
                    'actualLoop' => $actualLoop
                ];
            @endphp
            
            <!-- Load Required CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
            
            <!-- Component Structure -->
            <div class="property-category-carousel-component" style="{{ $styleStr }}">
                <style>
                    .property-wrapper-{{ $sliderId }} {
                        position: relative;
                    }
                    #{{ $sliderId }} .owl-nav { display: none; }
                    .custom-nav-prop-{{ $sliderId }} {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 100%;
                        pointer-events: none;
                        z-index: 100;
                        left: 0;
                        right: 0;
                    }
                    .custom-nav-prop-{{ $sliderId }} button {
                        position: absolute;
                        width: 45px;
                        height: 45px;
                        background: rgba(0,0,0,0.6) !important;
                        color: white !important;
                        border: none;
                        border-radius: 50%;
                        font-size: 24px;
                        font-weight: normal;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        opacity: 0.9;
                        pointer-events: all;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        line-height: 1;
                        padding: 0;
                    }
                    .custom-nav-prop-{{ $sliderId }} button:hover {
                        opacity: 1;
                        transform: scale(1.1);
                        background: rgba(0,0,0,0.8) !important;
                    }
                    .custom-nav-prop-{{ $sliderId }} .prev { left: 15px; }
                    .custom-nav-prop-{{ $sliderId }} .next { right: 15px; }
                    @media (max-width: 768px) {
                        .custom-nav-prop-{{ $sliderId }} button {
                            width: 35px;
                            height: 35px;
                            font-size: 20px;
                        }
                        .custom-nav-prop-{{ $sliderId }} .prev { left: 10px; }
                        .custom-nav-prop-{{ $sliderId }} .next { right: 10px; }
                    }
                    #{{ $sliderId }}.owl-carousel .owl-item {
                        min-width: 262px;
                        max-width: 262px;
                        width: 262px !important;
                    }
                    #{{ $sliderId }}.owl-carousel .property-card {
                        width: 262px;
                        max-width: 262px;
                        min-width: 262px;
                    }
                    @media (max-width: 767px) {
                        #{{ $sliderId }}.owl-carousel .owl-item {
                            width: 262px !important;
                        }

                        .inner-section-frontend {
                        padding: 0 !important;
                    }
                    }
                </style>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @if(isset($properties) && count($properties) > 0)
                                <div class="property-wrapper-{{ $sliderId }}">
                                    <div class="owl-carousel property-category-carousel" id="{{ $sliderId }}">
                                    @foreach($properties as $property)
                                    @php
                                        $totalSold = \App\Models\TicketSellDetail::where('ticket_id', $property->id)
                                            ->whereHas('ticketSell', function($query) {
                                                $query->where('status', 1);
                                            })
                                            ->sum('quantity');
                                
                                        $actualAvailableShares = $property->total_shares - $totalSold;
                                    @endphp
                                        <div class="property-card">
                                            <a href="{{ route('product.details', $property->slug) }}" class="property-link">
                                                <div class="property-image">
                                                    <img src="{{ asset($property->image) }}" alt="{{ $property->name }}">
                                                    @if($property->available_shares && $property->total_shares)
                                                        <div class="property-badge">{{ number_format($actualAvailableShares) }} / {{ number_format($property->total_shares) }} shares</div>
                                                    @endif
                                                </div>
                                                <div class="property-info">
                                                    <h3 class="property-title">{{ $property->name }}</h3>
                                                    <div class="property-meta">
                                                        <span class="property-price">${{ number_format($property->price_per_share, 2) }} per share</span>
                                                    </div>
                                                    <div class="property-shares-info">
                                                        <span class="shares-available">{{ number_format($property->available_shares) }} available</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                    <div class="custom-nav-prop-{{ $sliderId }}">
                                        <button class="prev" onclick="$('#{{ $sliderId }}').trigger('prev.owl.carousel')">‹</button>
                                        <button class="next" onclick="$('#{{ $sliderId }}').trigger('next.owl.carousel')">›</button>
                                    </div>
                                </div>
                            @else
                                <div class="no-properties">
                                    <p>No properties available for this category at this time.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Component Styles -->
            <style>
                .property-category-carousel-component {
                    padding: 40px 0;
                }

                .property-category-carousel-component .property-card {
                    background: {{ $cardBackgroundColor }};
                    border-radius: {{ $cardBorderRadius }};
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease;
                    margin: 10px;
                }

                .property-category-carousel-component .property-card:hover {
                    transform: translateY(-5px);
                }

                .property-category-carousel-component .property-link {
                    text-decoration: none;
                    color: inherit;
                    display: block;
                }

                .property-category-carousel-component .property-image {
                    position: relative;
                    padding-top: 66.67%; /* 3:2 Aspect ratio */
                    overflow: hidden;
                    height: 262px;
                    width: 262px;
                }

                .property-category-carousel-component .property-image img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: unset;
                    height: 262px !important;
                    width: 262px !important;
                }

                .property-category-carousel-component .property-badge {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: rgba(46, 125, 62, 0.95);
                    color: white;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 11px;
                    font-weight: 600;
                }

                .property-category-carousel-component .property-info {
                    padding: 15px;
                }

                .property-category-carousel-component .property-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: {{ $titleColor }};
                    margin-bottom: 8px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    font-size: 0.875rem !important;
                    font-weight: 400 !important;
                }

                .property-category-carousel-component .property-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;
                }

                .property-category-carousel-component .property-price {
                    font-size: 16px;
                    font-weight: 700;
                    color: {{ $priceColor }};
                }

                .property-category-carousel-component .property-shares-info {
                    font-size: 12px;
                    color: {{ $descriptionColor }};
                }

                .property-category-carousel-component .shares-available {
                    font-weight: 500;
                }

                .property-category-carousel-component .no-properties {
                    text-align: center;
                    padding: 40px;
                    background: #f8f9fa;
                    border-radius: 8px;
                }

                /* Responsive styles */
                @media (max-width: 768px) {
                    .property-category-carousel-component {
                        padding: 20px 0;
                    }
                    
                    .property-category-carousel-component .property-card {
                        margin: 5px;
                    }

                    .property-category-carousel-component .property-title {
                        font-size: 14px;
                    }

                    .property-category-carousel-component .property-price {
                        font-size: 14px;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>

            <!-- Initialize Owl Carousel -->
            <script>
                console.log('Property Category Carousel Debug:', {!! json_encode($debugInfo) !!});
                
                $(document).ready(function(){
                    $("#{{ $sliderId }}").owlCarousel({
                        items: {{ $slidesToShow }},
                        loop: {{ $actualLoop ? 'true' : 'false' }},
                        margin: 20,
                        autoplay: {{ $autoplay ? 'true' : 'false' }},
                        autoplayTimeout: {{ $autoplaySpeed }},
                        autoplayHoverPause: true,
                        dots: {{ $dots ? 'true' : 'false' }},
                        nav: false,
                        responsive: {
                            0: {
                                items: 1,
                                loop: {{ count($properties) >= 1 && $enableLoop ? 'true' : 'false' }}
                            },
                            768: {
                                items: 2,
                                loop: {{ count($properties) >= 2 && $enableLoop ? 'true' : 'false' }}
                            },
                            992: {
                                items: {{ min($slidesToShow, 3) }},
                                loop: {{ count($properties) >= min($slidesToShow, 3) && $enableLoop ? 'true' : 'false' }}
                            }
                        }
                    });
                });
            </script>
        @break

        @case('video-background')
            @php
                $videoData = $component['videoData'] ?? [];
                $properties = $component['properties'] ?? [];
                
                // Get video settings (support both builder and properties format)
                $videoSource = $videoData['videoSource'] ?? $properties['video_source'] ?? 'url';
                $videoUrl = $videoData['videoUrl'] ?? $properties['video_url'] ?? '';
                $videoType = $videoData['videoType'] ?? $properties['video_type'] ?? 'mp4';
                
                // Overlay settings
                $overlayColor = $videoData['overlayColor'] ?? $properties['overlay_color'] ?? '#000000';
                $overlayOpacity = $videoData['overlayOpacity'] ?? $properties['overlay_opacity'] ?? '0.5';
                
                // Content settings
                $contentType = $videoData['contentType'] ?? $properties['content_type'] ?? 'text';
                $heading = $videoData['heading'] ?? $properties['heading'] ?? '';
                $subheading = $videoData['subheading'] ?? $properties['subheading'] ?? '';
                $buttonText = $videoData['buttonText'] ?? $properties['button_text'] ?? '';
                $buttonUrl = $videoData['buttonUrl'] ?? $properties['button_url'] ?? '#';
                $buttonColor = $videoData['buttonColor'] ?? $properties['button_color'] ?? '#667eea';
                $textColor = $videoData['textColor'] ?? $properties['text_color'] ?? '#ffffff';
                $imageUrl = $videoData['imageUrl'] ?? $properties['image_url'] ?? '';
                $imageWidth = $videoData['imageWidth'] ?? $properties['image_width'] ?? '300';
                
                // Layout settings
                $textAlign = $videoData['textAlign'] ?? $properties['text_align'] ?? 'center';
                $verticalAlign = $videoData['verticalAlign'] ?? $properties['vertical_align'] ?? 'center';
                $minHeight = $videoData['minHeight'] ?? $properties['min_height'] ?? '500';
                
                // Playback settings
                $autoplay = ($videoData['autoplay'] ?? $properties['autoplay'] ?? true);
                if (is_string($autoplay)) {
                    $autoplay = $autoplay === '1' || $autoplay === 'true';
                }
                $loop = ($videoData['loop'] ?? $properties['loop'] ?? true);
                if (is_string($loop)) {
                    $loop = $loop === '1' || $loop === 'true';
                }
                $muted = ($videoData['muted'] ?? $properties['muted'] ?? true);
                if (is_string($muted)) {
                    $muted = $muted === '1' || $muted === 'true';
                }
                $controls = ($videoData['controls'] ?? $properties['controls'] ?? false);
                if (is_string($controls)) {
                    $controls = $controls === '1' || $controls === 'true';
                }
                
                // Convert hex color to rgba
                function hexToRgbaFrontend($hex, $alpha) {
                    $hex = ltrim($hex, '#');
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                    return "rgba($r, $g, $b, $alpha)";
                }
                
                $rgbaOverlay = hexToRgbaFrontend($overlayColor, floatval($overlayOpacity));
                
                // Alignment classes
                $alignmentMap = [
                    'top' => 'flex-start',
                    'center' => 'center',
                    'bottom' => 'flex-end'
                ];
                $verticalAlignClass = $alignmentMap[$verticalAlign] ?? 'center';
                $horizontalAlignClass = $textAlign === 'left' ? 'flex-start' : ($textAlign === 'right' ? 'flex-end' : 'center');
            @endphp
            
            <div class="video-background-component" id="{{ $componentId }}" style="{{ $styleStr }}">
                <div class="video-background-section" style="position: relative; min-height: {{ $minHeight }}px; overflow: hidden; display: flex; align-items: {{ $verticalAlignClass }}; justify-content: center;">
                    {{-- Video Element --}}
                    <video 
                        class="video-bg" 
                        @if($autoplay) autoplay @endif
                        @if($loop) loop @endif
                        @if($muted) muted @endif
                        @if($controls) controls @endif
                        playsinline
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); min-width: 100%; min-height: 100%; width: auto; height: auto; object-fit: cover; z-index: 0;">
                        <source src="{{ $videoUrl }}" type="video/{{ $videoType }}">
                        Your browser does not support the video tag.
                    </video>
                    
                    {{-- Overlay --}}
                    <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: {{ $rgbaOverlay }}; z-index: 1;"></div>
                    
                    {{-- Content --}}
                    <div style="position: relative; z-index: 2; padding: 40px 20px; width: 100%; display: flex; flex-direction: column; align-items: {{ $horizontalAlignClass }};">
                        @if($contentType === 'text' || $contentType === 'both')
                            <div style="text-align: {{ $textAlign }}; color: {{ $textColor }}; z-index: 2; max-width: 800px; margin: {{ $contentType === 'both' ? '0 0 30px 0' : '0' }};">
                                @if($heading)
                                    <h1 style="font-size: 3rem; font-weight: bold; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">{{ $heading }}</h1>
                                @endif
                                @if($subheading)
                                    <p style="font-size: 1.5rem; margin-bottom: 30px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">{{ $subheading }}</p>
                                @endif
                                @if($buttonText)
                                    <a href="{{ $buttonUrl }}" class="btn" style="background-color: {{ $buttonColor }}; color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-size: 1.1rem; display: inline-block; transition: all 0.3s;">{{ $buttonText }}</a>
                                @endif
                            </div>
                        @endif
                        
                        @if($contentType === 'image' || $contentType === 'both')
                            <div style="z-index: 2; text-align: {{ $textAlign }};">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="Content Image" style="max-width: {{ $imageWidth }}px; width: 100%; height: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border-radius: 8px;">
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Responsive Styles --}}
            <style>
                #{{ $componentId }} .video-background-section .btn:hover {
                    opacity: 0.9;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                }
                
                @media (max-width: 768px) {
                    #{{ $componentId }} .video-background-section h1 {
                        font-size: 2rem !important;
                    }
                    
                    #{{ $componentId }} .video-background-section p {
                        font-size: 1.2rem !important;
                    }
                    
                    #{{ $componentId }} .video-background-section .btn {
                        padding: 12px 30px !important;
                        font-size: 1rem !important;
                    }
                    
                    #{{ $componentId }} .video-background-section img {
                        max-width: 90% !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
                
                @media (max-width: 480px) {
                    #{{ $componentId }} .video-background-section h1 {
                        font-size: 1.5rem !important;
                    }
                    
                    #{{ $componentId }} .video-background-section p {
                        font-size: 1rem !important;
                    }
                    
                    #{{ $componentId }} .video-background-section .btn {
                        padding: 10px 25px !important;
                        font-size: 0.9rem !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>
            
            {{-- Auto-play video script --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const video = document.querySelector('#{{ $componentId }} video');
                    if (video && {{ $autoplay ? 'true' : 'false' }}) {
                        video.play().catch(e => console.log('Video autoplay prevented:', e));
                    }
                });
            </script>
        @break

        @case('property-listing-grid')
            @php
                // Get component settings with defaults
                $columns = $component['columns'] ?? 3;
                $perPage = $component['perPage'] ?? 9;
                $showFilter = ($component['showFilter'] ?? 'true') === 'true';
                $defaultCategory = $component['defaultCategory'] ?? 'all';
                $showCarousel = ($component['showCarousel'] ?? 'true') === 'true';
                $descriptionLength = $component['descriptionLength'] ?? 100;
                $showShares = ($component['showShares'] ?? 'true') === 'true';
                $sortBy = $component['sort'] ?? 'newest';
                $cardStyle = $component['cardStyle'] ?? 'border';
                $primaryColor = $component['primaryColor'] ?? '#667eea';
                $bgColor = $component['bgColor'] ?? '#ffffff';
                $padding = $component['padding'] ?? 60;
                $sectionTitle = $component['title'] ?? '';
                $sectionSubtitle = $component['subtitle'] ?? '';
                
                // Get properties with their categories
                $properties = \App\Models\Ticket::where('website_id', $check->id)
                    ->where('type', 'property')
                    ->where('status', 1)
                    ->with(['category', 'images']);
                
                // Apply sorting
                switch($sortBy) {
                    case 'oldest':
                        $properties = $properties->oldest();
                        break;
                    case 'price_low':
                        $properties = $properties->orderBy('price_per_share', 'asc');
                        break;
                    case 'price_high':
                        $properties = $properties->orderBy('price_per_share', 'desc');
                        break;
                    case 'shares_available':
                        $properties = $properties->orderBy('available_shares', 'desc');
                        break;
                    default: // newest
                        $properties = $properties->latest();
                }
                
                $properties = $properties->take($perPage)->get();
                
                // Get all property categories
                $categories = \App\Models\TicketCategory::where('website_id', $check->id)
                    ->where('status', 1)
                    ->whereHas('tickets', function($query) use ($check) {
                        $query->where('type', 'property')
                              ->where('website_id', $check->id)
                              ->where('status', 1);
                    })->get();
                
                // Card style classes
                $cardStyleClass = '';
                switch($cardStyle) {
                    case 'shadow':
                        $cardStyleClass = 'property-card-shadow';
                        break;
                    case 'minimal':
                        $cardStyleClass = 'property-card-minimal';
                        break;
                    default:
                        $cardStyleClass = 'property-card-border';
                }
                
                $gridClass = 'col-md-' . (12 / $columns);
            @endphp

            <div class="property-listing-grid-component" style="{{ $styleStr }} background: {{ $bgColor }}; padding: {{ $padding }}px 0;">
                <div class="container">
                    @if($sectionTitle)
                        <div class="text-center mb-5">
                            <h2 class="section-title" style="color: #1e293b; font-weight: 700; font-size: 2.5rem; margin-bottom: 1rem;">
                                {{ $sectionTitle }}
                            </h2>
                            @if($sectionSubtitle)
                                <p class="section-subtitle" style="color: #64748b; font-size: 1.125rem;">
                                    {{ $sectionSubtitle }}
                                </p>
                            @endif
                        </div>
                    @endif

                    @if($showFilter && $categories->count() > 0)
                        <!-- Category Pills (icon + label) -->
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-2xN7l8W3K8m2cJpD9X7k7bGzS8n6uQjFf7G1n1b2b5n3a0q0+0S3m7qz0o4P1vWkQp8m5Q8D4R5Y+1YzZf7R2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                        <div class="property-filter-pills mb-4" id="propertyListingFilter-{{ $componentId }}" style="--primary-color: {{ $primaryColor }};">
                            <div class="category-pills d-flex justify-content-center flex-wrap gap-4">
                                <button type="button" class="category-pill active" data-category="all">
                                    <div class="icon"><i class="fa-solid fa-table-cells"></i></div>
                                    <div class="label">All Properties</div>
                                </button>
                                @foreach($categories as $category)
                                    <button type="button" class="category-pill" data-category="{{ $category->id }}">
                                        <div class="icon">
                                            <i class="{{ $category->icon ?: 'fa-regular fa-tag' }}"></i>
                                        </div>
                                        <div class="label">{{ $category->name }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Properties Grid -->
                    <div class="row property-grid-container" id="propertyGrid-{{ $componentId }}">
                        @foreach($properties as $property)
                            @php
                                // Calculate actual available shares from sales
                                $totalSold = \App\Models\TicketSellDetail::where('ticket_id', $property->id)
                                    ->whereHas('ticketSell', function($query) {
                                        $query->where('status', 1);
                                    })
                                    ->sum('quantity');
                                
                                $actualAvailableShares = $property->total_shares - $totalSold;
                                $percentageSold = $property->total_shares > 0 ? (($property->total_shares - $actualAvailableShares) / $property->total_shares) * 100 : 0;
                            @endphp
                            
                            <div class="{{ $gridClass }} mb-4 property-item" data-category="{{ $property->category_id ?? 'uncategorized' }}">
                                <div class="property-card {{ $cardStyleClass }}">
                                    <a href="/product/{{ $property->slug }}" class="property-link">
                                        <!-- Property Image with Carousel -->
                                        <div class="property-image-container">
                                            @if($property->images && $property->images->count() > 0)
                                                <div class="property-carousel" id="propertyCarousel-{{ $componentId }}-{{ $property->id }}">
                                                    <div class="carousel-images">
                                                        <img src="{{ asset($property->image) }}" alt="{{ $property->name }}" class="property-img active">
                                                        @foreach($property->images as $index => $image)
                                                            @if($index > 0)
                                                                <img src="{{ asset($image->image_path) }}" alt="{{ $property->name }}" class="property-img">
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    
                                                    @if($showCarousel && $property->images->count() > 1)
                                                        <button class="carousel-btn carousel-prev" onclick="event.preventDefault(); navigatePropertyCarousel('{{ $componentId }}-{{ $property->id }}', -1)">
                                                            <i class="fas fa-chevron-left"></i>
                                                        </button>
                                                        <button class="carousel-btn carousel-next" onclick="event.preventDefault(); navigatePropertyCarousel('{{ $componentId }}-{{ $property->id }}', 1)">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </button>
                                                        <div class="carousel-indicators">
                                                            <span class="active"></span>
                                                            @foreach($property->images as $index => $image)
                                                                @if($index > 0)
                                                                    <span></span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <img src="{{ asset($property->image) }}" alt="{{ $property->name }}" class="property-img">
                                            @endif
                                        </div>

                                        <!-- Property Content -->
                                        <div class="property-content">
                                            <h3 class="property-name">{{ $property->name }}</h3>
                                            
                                            @if($property->description)
                                                <p class="property-description">
                                                    {{ Str::limit(strip_tags($property->description), $descriptionLength) }}
                                                </p>
                                            @endif

                                            @if($showShares)
                                                <div class="property-shares-info" style="border-top: 2px solid #e2e8f0; padding-top: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                                    <div style="border-left: 4px solid {{ $primaryColor }}; padding-left: 12px;">
                                                        <div style="font-size: 0.7rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Price per Share</div>
                                                        <div style="font-size: 1.5rem; font-weight: 700; color: {{ $primaryColor }};">${{ number_format($property->price_per_share, 2) }}</div>
                                                    </div>
                                                    <div style="border-left: 4px solid #10b981; padding-left: 12px;">
                                                        <div style="font-size: 0.7rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Available Shares</div>
                                                        <div style="font-size: 1.125rem; font-weight: 700; color: #1e293b;">{{ number_format($actualAvailableShares) }} / {{ number_format($property->total_shares) }}</div>
                                                    </div>
                                                    
                                                    <!-- Progress Bar -->
                                                    <div style="grid-column: 1 / -1; margin-top: 5px;">
                                                        <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 6px;">
                                                            <div style="height: 100%; width: {{ $percentageSold }}%; background: {{ $primaryColor }}; transition: width 0.3s ease;"></div>
                                                        </div>
                                                        <div style="font-size: 0.75rem; color: #64748b; text-align: center;">{{ number_format($percentageSold, 1) }}% Funded</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($properties->count() === 0)
                        <div class="text-center py-5">
                            <i class="fas fa-building" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                            <p style="color: #64748b;">No properties available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Component Styles -->
            <style>
                /* Category pills */
                .property-listing-grid-component .category-pills {
                    gap: 28px;
                }
                .property-listing-grid-component .category-pill {
                    background: transparent;
                    border: 0;
                    padding: 6px 8px 0;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    cursor: pointer;
                    color: #6b7280;
                }
                .property-listing-grid-component .category-pill .icon {
                    font-size: 24px;
                    line-height: 1;
                    color: #6b7280;
                    transition: color 0.2s ease;
                }
                .property-listing-grid-component .category-pill .label {
                    font-size: 0.875rem;
                    font-weight: 600;
                    color: #6b7280;
                    margin-top: 6px;
                    position: relative;
                }
                .property-listing-grid-component .category-pill .label::after {
                    content: "";
                    display: block;
                    height: 3px;
                    width: 0;
                    background: var(--primary-color);
                    border-radius: 2px;
                    margin: 6px auto 0;
                    transition: width 0.2s ease;
                }

                .category-pill .label {
  background: transparent !important;
}
                .property-listing-grid-component .category-pill.active .icon,
                .property-listing-grid-component .category-pill:hover .icon {
                    color: #111827;
                }
                .property-listing-grid-component .category-pill.active .label {
                    color: #111827;
                    font-weight: 700;
                }
                .property-listing-grid-component .category-pill.active .label::after {
                    width: 30px;
                }

                .property-listing-grid-component .property-card {
                    border-radius: 12px;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                .property-listing-grid-component .property-card-border {
                    border: 1px solid #e2e8f0;
                    background: white;
                }

                .property-listing-grid-component .property-card-shadow {
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    background: white;
                    border: none;
                }

                .property-listing-grid-component .property-card-minimal {
                    background: white;
                    border: none;
                }

                .property-listing-grid-component .property-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                }

                .property-listing-grid-component .property-link {
                    text-decoration: none;
                    color: inherit;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                }

                .property-listing-grid-component .property-image-container {
                    position: relative;
                    width: 100%;
                    padding-top: 66.67%;
                    overflow: hidden;
                    background: #f1f5f9;
                }

                .property-listing-grid-component .property-carousel {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }

                .property-listing-grid-component .carousel-images {
                    position: relative;
                    width: 100%;
                    height: 100%;
                }

                .property-listing-grid-component .property-img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    opacity: 0;
                    transition: opacity 0.4s ease;
                }

                .property-listing-grid-component .property-img.active {
                    opacity: 1;
                }

                .property-listing-grid-component .carousel-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255,255,255,0.9);
                    border: none;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                    transition: all 0.3s ease;
                    color: #1e293b;
                }

                .property-listing-grid-component .carousel-btn:hover {
                    background: white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                }

                .property-listing-grid-component .carousel-prev {
                    left: 10px;
                }

                .property-listing-grid-component .carousel-next {
                    right: 10px;
                }

                .property-listing-grid-component .carousel-indicators {
                    position: absolute;
                    bottom: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    display: flex;
                    gap: 6px;
                    z-index: 10;
                }

                .property-listing-grid-component .carousel-indicators span {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: rgba(255,255,255,0.6);
                    transition: all 0.3s ease;
                }

                .property-listing-grid-component .carousel-indicators span.active {
                    background: white;
                    width: 24px;
                    border-radius: 4px;
                }

                .property-listing-grid-component .property-content {
                    padding: 20px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                .property-listing-grid-component .property-name {
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 10px;
                }

                .property-listing-grid-component .property-description {
                    color: #64748b;
                    font-size: 0.875rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                    flex: 1;
                }

                .property-listing-grid-component .property-shares-info {
                    border-top: 2px solid #e2e8f0;
                    padding-top: 15px;
                    background: transparent !important;
                }

                .property-listing-grid-component .share-price,
                .property-listing-grid-component .shares-available {
                    margin-bottom: 12px;
                    padding: 10px 0 10px 15px;
                    border-left: 4px solid;
                    display: block;
                    background: transparent !important;
                }

                .property-listing-grid-component .share-price {
                    border-left-color: {{ $primaryColor }};
                }

                .property-listing-grid-component .shares-available {
                    border-left-color: #10b981;
                }

                .property-listing-grid-component .share-price .label,
                .property-listing-grid-component .shares-available .label {
                    color: #64748b;
                    font-size: 0.875rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    background: transparent !important;
                    display: inline !important;
                    float: none !important;
                }

                .property-listing-grid-component .share-price .value {
                    font-weight: 700;
                    font-size: 1.5rem;
                    color: {{ $primaryColor }};
                    background: transparent !important;
                    display: inline !important;
                    float: none !important;
                    margin-left: 10px;
                }

                .property-listing-grid-component .shares-available .value {
                    font-weight: 700;
                    font-size: 1.125rem;
                    color: #1e293b;
                    background: transparent !important;
                    display: inline !important;
                    float: none !important;
                    margin-left: 10px;
                }

                .property-listing-grid-component .shares-progress {
                    margin-top: 12px;
                }

                .property-listing-grid-component .progress {
                    height: 8px;
                    background: #e2e8f0;
                    border-radius: 4px;
                    overflow: hidden;
                    margin-bottom: 6px;
                }

                .property-listing-grid-component .progress-bar {
                    height: 100%;
                    transition: width 0.3s ease;
                }

                .property-listing-grid-component .progress-text {
                    font-size: 0.75rem;
                    color: #64748b;
                }

                .property-listing-grid-component .property-item {
                    transition: opacity 0.3s ease;
                }

                .property-listing-grid-component .property-item.filtered-out {
                    display: none;
                }

                /* Mobile Responsive Styles */
                @media (max-width: 767px) {
                    /* Override inline padding on parent wrapper with highest specificity */
                    div[style].inner-section-frontend > .property-listing-grid-component,
                    div.inner-section-frontend[style*="padding"] > .property-listing-grid-component,
                    div[style*="padding"].inner-section-frontend .property-listing-grid-component {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                    }
                    
                    /* Remove padding from inner-section-frontend */
                    .property-listing-grid-component.inner-section-frontend,
                    .inner-section-frontend .property-listing-grid-component {
                        padding: 0 !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                    
                    /* Remove all container constraints */
                    .property-listing-grid-component .container {
                        max-width: 100% !important;
                        width: 100% !important;
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                    }
                    
                    /* Remove all row margins */
                    .property-listing-grid-component .property-grid-container {
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                        width: 100% !important;
                    }
                    
                    /* Make items full width with minimal padding */
                    .property-listing-grid-component .property-grid-container .property-item {
                        width: 100% !important;
                        max-width: 100% !important;
                        flex: 0 0 100% !important;
                        padding-left: 8px !important;
                        padding-right: 8px !important;
                        margin-bottom: 16px !important;
                    }
                    
                    /* Make cards full width */
                    .property-listing-grid-component .property-card {
                        max-width: 100% !important;
                        width: 100% !important;
                        margin: 0 !important;
                    }
                    
                    .property-listing-grid-component .property-name {
                        font-size: 1.125rem;
                    }
                    
                    .property-listing-grid-component .property-shares-info {
                        grid-template-columns: 1fr !important;
                        gap: 10px;
                    }
                    
                    /* Adjust section padding */
                    .property-listing-grid-component {
                        padding-left: 8px !important;
                        padding-right: 8px !important;
                    }
                }
                
                @media (min-width: 768px) and (max-width: 991px) {
                    .property-listing-grid-component .property-grid-container .property-item {
                        width: 50% !important;
                        max-width: 50% !important;
                        flex: 0 0 50% !important;
                    }

                    .inner-section-frontend {
                        padding: 0 !important;
                    }
                }
            </style>

            <!-- Component Scripts -->
            <script>
                // Image carousel navigation
                function navigatePropertyCarousel(carouselId, direction) {
                    const carousel = document.getElementById('propertyCarousel-' + carouselId);
                    const images = carousel.querySelectorAll('.property-img');
                    const indicators = carousel.querySelectorAll('.carousel-indicators span');
                    
                    let currentIndex = 0;
                    images.forEach((img, index) => {
                        if (img.classList.contains('active')) {
                            currentIndex = index;
                        }
                    });
                    
                    images[currentIndex].classList.remove('active');
                    indicators[currentIndex].classList.remove('active');
                    
                    currentIndex = (currentIndex + direction + images.length) % images.length;
                    
                    images[currentIndex].classList.add('active');
                    indicators[currentIndex].classList.add('active');
                }

                // Category filtering (icon pills)
                document.addEventListener('DOMContentLoaded', function() {
                    const pillSelector = '#propertyListingFilter-{{ $componentId }} .category-pill';
                    const filterButtons = document.querySelectorAll(pillSelector);
                    const propertyItems = document.querySelectorAll('#propertyGrid-{{ $componentId }} .property-item');
                    const defaultCategory = @json($defaultCategory ?? 'all');

                    function applyFilter(category) {
                        filterButtons.forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-category') === category));
                        propertyItems.forEach(item => {
                            if (category === 'all' || item.getAttribute('data-category') === String(category)) {
                                item.classList.remove('filtered-out');
                            } else {
                                item.classList.add('filtered-out');
                            }
                        });
                    }

                    filterButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            applyFilter(this.getAttribute('data-category'));
                        });
                    });

                    // Apply default category on load if provided
                    if (defaultCategory && defaultCategory !== 'all') {
                        applyFilter(String(defaultCategory));
                    }
                });
            </script>
        @break

        @case('product-listing-grid')
            @php
                // Get component settings with defaults
                $columns = $component['columns'] ?? 3;
                $perPage = $component['perPage'] ?? 12;
                $showFilter = ($component['showFilter'] ?? 'true') === 'true';
                $defaultCategory = $component['defaultCategory'] ?? 'all';
                $showCarousel = ($component['showCarousel'] ?? 'true') === 'true';
                $descriptionLength = $component['descriptionLength'] ?? 100;
                $showPrice = ($component['showPrice'] ?? 'true') === 'true';
                $showStock = ($component['showStock'] ?? 'true') === 'true';
                $sortBy = $component['sort'] ?? 'newest';
                $cardStyle = $component['cardStyle'] ?? 'border';
                $primaryColor = $component['primaryColor'] ?? '#3b82f6';
                $bgColor = $component['bgColor'] ?? '#ffffff';
                $padding = $component['padding'] ?? 60;
                $sectionTitle = $component['title'] ?? '';
                $sectionSubtitle = $component['subtitle'] ?? '';
                
                // Get products with their categories
                $products = \App\Models\Ticket::where('website_id', $check->id)
                    ->whereIn('type', ['product', 'ticket'])
                    ->where('status', 1)
                    ->with(['category', 'images']);
                
                // Apply sorting
                switch($sortBy) {
                    case 'oldest':
                        $products = $products->oldest();
                        break;
                    case 'price_low':
                        $products = $products->orderBy('price', 'asc');
                        break;
                    case 'price_high':
                        $products = $products->orderBy('price', 'desc');
                        break;
                    case 'name_az':
                        $products = $products->orderBy('name', 'asc');
                        break;
                    case 'name_za':
                        $products = $products->orderBy('name', 'desc');
                        break;
                    default: // newest
                        $products = $products->latest();
                }
                
                $products = $products->take($perPage)->get();
                
                // Get all product categories
                $categories = \App\Models\TicketCategory::where('website_id', $check->id)
                    ->where('status', 1)
                    ->whereHas('tickets', function($query) use ($check) {
                        $query->whereIn('type', ['product', 'ticket'])
                              ->where('website_id', $check->id)
                              ->where('status', 1);
                    })->get();
                
                // Card style classes
                $cardStyleClass = '';
                switch($cardStyle) {
                    case 'shadow':
                        $cardStyleClass = 'product-card-shadow';
                        break;
                    case 'minimal':
                        $cardStyleClass = 'product-card-minimal';
                        break;
                    default:
                        $cardStyleClass = 'product-card-border';
                }
                
                $gridClass = 'col-md-' . (12 / $columns);
            @endphp

            <div class="product-listing-grid-component" style="{{ $styleStr }} background: {{ $bgColor }}; padding: {{ $padding }}px 0;">
                <div class="container">
                    @if($sectionTitle)
                        <div class="text-center mb-5">
                            <h2 class="section-title" style="color: #1e293b; font-weight: 700; font-size: 2.5rem; margin-bottom: 1rem;">
                                {{ $sectionTitle }}
                            </h2>
                            @if($sectionSubtitle)
                                <p class="section-subtitle" style="color: #64748b; font-size: 1.125rem;">
                                    {{ $sectionSubtitle }}
                                </p>
                            @endif
                        </div>
                    @endif

                    @if($showFilter && $categories->count() > 0)
                        <!-- Category Pills (icon + label) -->
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-2xN7l8W3K8m2cJpD9X7k7bGzS8n6uQjFf7G1n1b2b5n3a0q0+0S3m7qz0o4P1vWkQp8m5Q8D4R5Y+1YzZf7R2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                        <div class="product-filter-pills mb-4" id="productListingFilter-{{ $componentId }}" style="--primary-color: {{ $primaryColor }};">
                            <div class="category-pills d-flex justify-content-center flex-wrap gap-4">
                                <button type="button" class="category-pill active" data-category="all">
                                    <div class="icon"><i class="fa-solid fa-table-cells"></i></div>
                                    <div class="label">All Products</div>
                                </button>
                                @foreach($categories as $category)
                                    <button type="button" class="category-pill" data-category="{{ $category->id }}">
                                        <div class="icon">
                                            <i class="{{ $category->icon ?: 'fa-regular fa-tag' }}"></i>
                                        </div>
                                        <div class="label">{{ $category->name }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Products Grid -->
                    <div class="row product-grid-container" id="productGrid-{{ $componentId }}">
                        @foreach($products as $product)
                            <div class="{{ $gridClass }} mb-4 product-item" data-category="{{ $product->category_id ?? 'uncategorized' }}">
                                <div class="product-card {{ $cardStyleClass }}">
                                    <a href="/product/{{ $product->slug }}" class="product-link">
                                        <!-- Product Image with Carousel -->
                                        <div class="product-image-container">
                                            @if($product->images && $product->images->count() > 0)
                                                <div class="product-carousel" id="productCarousel-{{ $componentId }}-{{ $product->id }}">
                                                    <div class="carousel-images">
                                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-img active">
                                                        @foreach($product->images as $index => $image)
                                                            @if($index > 0)
                                                                <img src="{{ asset($image->image_path) }}" alt="{{ $product->name }}" class="product-img">
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    
                                                    @if($showCarousel && $product->images->count() > 1)
                                                        <button class="carousel-btn carousel-prev" onclick="event.preventDefault(); navigateProductCarousel('{{ $componentId }}-{{ $product->id }}', -1)">
                                                            <i class="fas fa-chevron-left"></i>
                                                        </button>
                                                        <button class="carousel-btn carousel-next" onclick="event.preventDefault(); navigateProductCarousel('{{ $componentId }}-{{ $product->id }}', 1)">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </button>
                                                        <div class="carousel-indicators">
                                                            <span class="active"></span>
                                                            @foreach($product->images as $index => $image)
                                                                @if($index > 0)
                                                                    <span></span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-img">
                                            @endif

                                            @if($showStock)
                                                @if($product->quantity > 0)
                                                    <span class="stock-badge stock-in">In Stock</span>
                                                @else
                                                    <span class="stock-badge stock-out">Out of Stock</span>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Product Content -->
                                        <div class="product-content">
                                            <h3 class="product-name">{{ $product->name }}</h3>
                                            
                                            @if($product->description)
                                                <p class="product-description">
                                                    {{ Str::limit(strip_tags($product->description), $descriptionLength) }}
                                                </p>
                                            @endif

                                            <div class="product-footer">
                                                @if($showPrice)
                                                    <div class="product-price" style="color: {{ $primaryColor }};">
                                                        ${{ number_format($product->price, 2) }}
                                                    </div>
                                                @endif
                                                
                                                @if($showStock && $product->quantity > 0)
                                                    <div class="product-quantity">
                                                        {{ $product->quantity }} available
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products->count() === 0)
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                            <p style="color: #64748b;">No products available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Component Styles -->
            <style>
                /* Category pills */
                .product-listing-grid-component .category-pills {
                    gap: 28px;
                }
                .product-listing-grid-component .category-pill {
                    background: transparent;
                    border: 0;
                    padding: 6px 8px 0;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    cursor: pointer;
                    color: #6b7280;
                }
                .product-listing-grid-component .category-pill .icon {
                    font-size: 24px;
                    line-height: 1;
                    color: #6b7280;
                    transition: color 0.2s ease;
                }
                .product-listing-grid-component .category-pill .label {
                    font-size: 0.875rem;
                    font-weight: 600;
                    color: #6b7280;
                    margin-top: 6px;
                    position: relative;
                }
                .product-listing-grid-component .category-pill .label::after {
                    content: "";
                    display: block;
                    height: 3px;
                    width: 0;
                    background: var(--primary-color);
                    border-radius: 2px;
                    margin: 6px auto 0;
                    transition: width 0.2s ease;
                }
                .product-listing-grid-component .category-pill.active .icon,
                .product-listing-grid-component .category-pill:hover .icon {
                    color: #111827;
                }
                .product-listing-grid-component .category-pill.active .label {
                    color: #111827;
                    font-weight: 700;
                }
                .product-listing-grid-component .category-pill.active .label::after {
                    width: 30px;
                }

                .product-listing-grid-component .product-card {
                    border-radius: 12px;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                .product-listing-grid-component .product-card-border {
                    border: 1px solid #e2e8f0;
                    background: white;
                }

                .product-listing-grid-component .product-card-shadow {
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    background: white;
                    border: none;
                }

                .product-listing-grid-component .product-card-minimal {
                    background: white;
                    border: none;
                }

                .product-listing-grid-component .product-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                }

                .product-listing-grid-component .product-link {
                    text-decoration: none;
                    color: inherit;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                }

                .product-listing-grid-component .product-image-container {
                    position: relative;
                    width: 100%;
                    padding-top: 100%;
                    overflow: hidden;
                    background: #f1f5f9;
                }

                .product-listing-grid-component .product-carousel {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }

                .product-listing-grid-component .carousel-images {
                    position: relative;
                    width: 100%;
                    height: 100%;
                }

                .product-listing-grid-component .product-img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    opacity: 0;
                    transition: opacity 0.4s ease;
                }

                .product-listing-grid-component .product-img.active {
                    opacity: 1;
                }

                .product-listing-grid-component .stock-badge {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    z-index: 10;
                }

                .product-listing-grid-component .stock-in {
                    background: #10b981;
                    color: white;
                }

                .product-listing-grid-component .stock-out {
                    background: #ef4444;
                    color: white;
                }

                .product-listing-grid-component .carousel-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255,255,255,0.9);
                    border: none;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                    transition: all 0.3s ease;
                    color: #1e293b;
                }

                .product-listing-grid-component .carousel-btn:hover {
                    background: white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                }

                .product-listing-grid-component .carousel-prev {
                    left: 10px;
                }

                .product-listing-grid-component .carousel-next {
                    right: 10px;
                }

                .product-listing-grid-component .carousel-indicators {
                    position: absolute;
                    bottom: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    display: flex;
                    gap: 6px;
                    z-index: 10;
                }

                .product-listing-grid-component .carousel-indicators span {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: rgba(255,255,255,0.6);
                    transition: all 0.3s ease;
                }

                .product-listing-grid-component .carousel-indicators span.active {
                    background: white;
                    width: 24px;
                    border-radius: 4px;
                }

                .product-listing-grid-component .product-content {
                    padding: 20px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                .product-listing-grid-component .product-name {
                    font-size: 1.125rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 10px;
                }

                .product-listing-grid-component .product-description {
                    color: #64748b;
                    font-size: 0.875rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                    flex: 1;
                }

                .product-listing-grid-component .product-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-top: 1px solid #e2e8f0;
                    padding-top: 15px;
                }

                .product-listing-grid-component .product-price {
                    font-size: 1.5rem;
                    font-weight: 700;
                }

                .product-listing-grid-component .product-quantity {
                    color: #64748b;
                    font-size: 0.875rem;
                }

                .product-listing-grid-component .product-item {
                    transition: opacity 0.3s ease;
                }

                .product-listing-grid-component .product-item.filtered-out {
                    display: none;
                }

                /* Mobile Responsive Styles */
                @media (max-width: 767px) {
                    /* Override inline padding on parent wrapper with highest specificity */
                    div[style].inner-section-frontend > .product-listing-grid-component,
                    div.inner-section-frontend[style*="padding"] > .product-listing-grid-component,
                    div[style*="padding"].inner-section-frontend .product-listing-grid-component {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                    }
                    
                    /* Remove padding from inner-section-frontend */
                    .product-listing-grid-component.inner-section-frontend,
                    .inner-section-frontend .product-listing-grid-component {
                        padding: 0 !important;
                    }
                    
                    /* Remove all container constraints */
                    .product-listing-grid-component .container {
                        max-width: 100% !important;
                        width: 100% !important;
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                    }
                    
                    /* Remove all row margins */
                    .product-listing-grid-component .product-grid-container {
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                        width: 100% !important;
                    }
                    
                    /* Make items full width with minimal padding */
                    .product-listing-grid-component .product-grid-container .product-item {
                        width: 100% !important;
                        max-width: 100% !important;
                        flex: 0 0 100% !important;
                        padding-left: 8px !important;
                        padding-right: 8px !important;
                        margin-bottom: 16px !important;
                    }
                    
                    /* Make cards full width */
                    .product-listing-grid-component .product-card {
                        max-width: 100% !important;
                        width: 100% !important;
                        margin: 0 !important;
                    }
                    
                    .product-listing-grid-component .product-name {
                        font-size: 1.0625rem;
                    }
                    
                    .product-listing-grid-component .product-content {
                        padding: 16px;
                    }
                    
                    /* Adjust section padding */
                    .product-listing-grid-component {
                        padding-left: 8px !important;
                        padding-right: 8px !important;
                    }
                }
                
                @media (min-width: 768px) and (max-width: 991px) {
                    .product-listing-grid-component .product-grid-container .product-item {
                        width: 50% !important;
                        max-width: 50% !important;
                        flex: 0 0 50% !important;
                    }
                }
            </style>

            <!-- Component Scripts -->
            <script>
                // Image carousel navigation
                function navigateProductCarousel(carouselId, direction) {
                    const carousel = document.getElementById('productCarousel-' + carouselId);
                    const images = carousel.querySelectorAll('.product-img');
                    const indicators = carousel.querySelectorAll('.carousel-indicators span');
                    
                    let currentIndex = 0;
                    images.forEach((img, index) => {
                        if (img.classList.contains('active')) {
                            currentIndex = index;
                        }
                    });
                    
                    images[currentIndex].classList.remove('active');
                    indicators[currentIndex].classList.remove('active');
                    
                    currentIndex = (currentIndex + direction + images.length) % images.length;
                    
                    images[currentIndex].classList.add('active');
                    indicators[currentIndex].classList.add('active');
                }

                // Category filtering (icon pills)
                document.addEventListener('DOMContentLoaded', function() {
                    const pillSelector = '#productListingFilter-{{ $componentId }} .category-pill';
                    const filterButtons = document.querySelectorAll(pillSelector);
                    const productItems = document.querySelectorAll('#productGrid-{{ $componentId }} .product-item');
                    const defaultCategory = @json($defaultCategory ?? 'all');

                    function applyFilter(category) {
                        filterButtons.forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-category') === category));
                        productItems.forEach(item => {
                            if (category === 'all' || item.getAttribute('data-category') === String(category)) {
                                item.classList.remove('filtered-out');
                            } else {
                                item.classList.add('filtered-out');
                            }
                        });
                    }

                    filterButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            applyFilter(this.getAttribute('data-category'));
                        });
                    });

                    // Apply default category on load if provided
                    if (defaultCategory && defaultCategory !== 'all') {
                        applyFilter(String(defaultCategory));
                    }
                });
            </script>
        @break

        @case('scrap-metal-calculator')
            @php
                $scrap = $component['scrapCalculatorData'] ?? [];
                $title = $scrap['title'] ?? 'Scrap Metal Calculator';
                $subtitle = $scrap['subtitle'] ?? 'Estimate your payout using live market prices.';
                $visibleMetals = $scrap['visibleMetals'] ?? ['gold', 'silver', 'platinum', 'palladium'];
                if (!is_array($visibleMetals) || empty($visibleMetals)) {
                    $visibleMetals = ['gold'];
                }
                $allowedMetals = ['gold', 'silver', 'platinum', 'palladium'];
                $visibleMetals = array_values(array_filter($visibleMetals, function ($m) use ($allowedMetals) {
                    return in_array($m, $allowedMetals, true);
                }));
                if (empty($visibleMetals)) {
                    $visibleMetals = ['gold'];
                }

                $defaultMetal = $scrap['defaultMetal'] ?? 'gold';
                if (!in_array($defaultMetal, $visibleMetals, true)) {
                    $defaultMetal = $visibleMetals[0];
                }

                $weightUnit = $scrap['weightUnit'] ?? 'grams';
                $defaultWeight = $scrap['defaultWeight'] ?? '10';
                $defaultPurity = $scrap['defaultPurity'] ?? '18';
                if (!in_array((string) $defaultPurity, ['24', '22', '21', '18'], true)) {
                    $defaultPurity = '18';
                }
                $showLivePrices = isset($scrap['showLivePrices']) ? (bool) $scrap['showLivePrices'] : true;
                $refreshSeconds = (int) ($scrap['refreshSeconds'] ?? 60);
                if ($refreshSeconds < 15) {
                    $refreshSeconds = 15;
                }
                if ($refreshSeconds > 600) {
                    $refreshSeconds = 600;
                }

                $metalDeductions = $scrap['metalDeductions'] ?? [];
                if (!is_array($metalDeductions)) {
                    $metalDeductions = [];
                }
                $metalDeductions = [
                    'gold' => max(0, min(100, (float) ($metalDeductions['gold'] ?? 0))),
                    'silver' => max(0, min(100, (float) ($metalDeductions['silver'] ?? 0))),
                    'platinum' => max(0, min(100, (float) ($metalDeductions['platinum'] ?? 0))),
                    'palladium' => max(0, min(100, (float) ($metalDeductions['palladium'] ?? 0))),
                ];
            @endphp

            <div class="scrap-calc-shell" id="{{ $componentId }}-scrap" style="{{ $styleStr }}">
                <style>
                    #{{ $componentId }}-scrap {
                        border: 1px solid rgba(15, 23, 42, 0.08);
                        border-radius: 16px;
                        background: linear-gradient(160deg, #ffffff 0%, #f8fafc 100%);
                        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
                        padding: 22px;
                    }

                    #{{ $componentId }}-scrap .scrap-header h3 {
                        margin: 0;
                        font-size: 24px;
                        font-weight: 700;
                        color: #0f172a;
                    }

                    #{{ $componentId }}-scrap .scrap-header p {
                        margin: 8px 0 0;
                        color: #64748b;
                        font-size: 14px;
                    }

                    #{{ $componentId }}-scrap .scrap-toolbar {
                        display: grid;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 12px;
                        margin-top: 16px;
                    }

                    #{{ $componentId }}-scrap .scrap-field label {
                        display: block;
                        font-size: 12px;
                        color: #334155;
                        margin-bottom: 6px;
                        font-weight: 600;
                    }

                    #{{ $componentId }}-scrap .scrap-field select,
                    #{{ $componentId }}-scrap .scrap-field input {
                        width: 100%;
                        border: 1px solid #cbd5e1;
                        border-radius: 10px;
                        padding: 10px 12px;
                        font-size: 14px;
                        background: #fff;
                    }

                    #{{ $componentId }}-scrap .scrap-prices {
                        margin-top: 16px;
                        display: grid;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 10px;
                    }

                    #{{ $componentId }}-scrap .price-card {
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        background: #fff;
                        padding: 10px;
                    }

                    #{{ $componentId }}-scrap .price-card .metal {
                        font-size: 12px;
                        text-transform: capitalize;
                        color: #475569;
                    }

                    #{{ $componentId }}-scrap .price-card .value {
                        font-size: 16px;
                        font-weight: 700;
                        color: #0f766e;
                    }

                    #{{ $componentId }}-scrap .result {
                        margin-top: 16px;
                        border: 1px solid #99f6e4;
                        background: linear-gradient(160deg, #ecfeff 0%, #f0fdfa 100%);
                        border-radius: 12px;
                        padding: 14px;
                    }

                    #{{ $componentId }}-scrap .result .label {
                        font-size: 12px;
                        color: #0f766e;
                        margin-bottom: 4px;
                    }

                    #{{ $componentId }}-scrap .result .amount {
                        font-size: 28px;
                        font-weight: 800;
                        color: #115e59;
                        line-height: 1.1;
                    }

                    #{{ $componentId }}-scrap .meta {
                        margin-top: 8px;
                        font-size: 12px;
                        color: #64748b;
                        display: flex;
                        justify-content: space-between;
                        gap: 10px;
                        flex-wrap: wrap;
                    }

                    #{{ $componentId }}-scrap .calc-breakdown {
                        margin-top: 8px;
                        font-size: 12px;
                        color: #0f766e;
                        background: #f0fdfa;
                        border: 1px dashed #99f6e4;
                        border-radius: 8px;
                        padding: 8px 10px;
                    }

                    @media (max-width: 768px) {
                        #{{ $componentId }}-scrap {
                            padding: 16px;
                        }

                        #{{ $componentId }}-scrap .scrap-toolbar,
                        #{{ $componentId }}-scrap .scrap-prices {
                            grid-template-columns: 1fr;
                        }

                        #{{ $componentId }}-scrap .result .amount {
                            font-size: 24px;
                        }
                    }
                </style>

                <div class="scrap-header">
                    <h3>{{ $title }}</h3>
                    <p>{{ $subtitle }}</p>
                </div>

                <div class="scrap-toolbar">
                    <div class="scrap-field">
                        <label>Metal</label>
                        <select id="{{ $componentId }}-metal">
                            @foreach($visibleMetals as $metal)
                                <option value="{{ $metal }}" {{ $defaultMetal === $metal ? 'selected' : '' }}>{{ ucfirst($metal) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="scrap-field">
                        <label>Unit</label>
                        <select id="{{ $componentId }}-unit">
                            <option value="grams" {{ $weightUnit === 'grams' ? 'selected' : '' }}>Grams (g)</option>
                            <option value="ounces" {{ $weightUnit === 'ounces' ? 'selected' : '' }}>Troy Ounces (oz)</option>
                        </select>
                    </div>
                    <div class="scrap-field">
                        <label>Weight</label>
                        <input id="{{ $componentId }}-weight" type="number" min="0" step="0.01" value="{{ $defaultWeight }}">
                    </div>
                    <div class="scrap-field">
                        <label>Purity (Karats)</label>
                        <select id="{{ $componentId }}-purity">
                            <option value="24" {{ $defaultPurity === '24' ? 'selected' : '' }}>24K (100%)</option>
                            <option value="22" {{ $defaultPurity === '22' ? 'selected' : '' }}>22K (91.6%)</option>
                            <option value="21" {{ $defaultPurity === '21' ? 'selected' : '' }}>21K (87.5%)</option>
                            <option value="18" {{ ($defaultPurity === '18' || !$defaultPurity) ? 'selected' : '' }}>18K (75.0%)</option>
                        </select>
                    </div>
                </div>

                @if($showLivePrices)
                    <div class="scrap-prices" id="{{ $componentId }}-price-cards"></div>
                @endif

                <div class="result">
                    <div class="label" style="color: #fff !Important">Estimated Scrap Value (USD)</div>
                    <div class="amount" id="{{ $componentId }}-result">$0.00</div>
                    <div class="calc-breakdown" id="{{ $componentId }}-calc-breakdown">Waiting for price and inputs...</div>
                    <div class="meta">
                        <span id="{{ $componentId }}-rate">Loading live price...</span>
                        <span id="{{ $componentId }}-updated">--</span>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const allowedMetals = @json($visibleMetals);
                    const defaultMetal = @json($defaultMetal);
                    const metalDeductions = @json($metalDeductions);
                    const refreshMs = {{ $refreshSeconds }} * 1000;
                    const showCards = {{ $showLivePrices ? 'true' : 'false' }};

                    const elMetal = document.getElementById('{{ $componentId }}-metal');
                    const elUnit = document.getElementById('{{ $componentId }}-unit');
                    const elWeight = document.getElementById('{{ $componentId }}-weight');
                    const elPurity = document.getElementById('{{ $componentId }}-purity');
                    const elResult = document.getElementById('{{ $componentId }}-result');
                    const elBreakdown = document.getElementById('{{ $componentId }}-calc-breakdown');
                    const elRate = document.getElementById('{{ $componentId }}-rate');
                    const elUpdated = document.getElementById('{{ $componentId }}-updated');
                    const elCards = document.getElementById('{{ $componentId }}-price-cards');

                    let prices = {
                        ounce: {},
                        gram: {}
                    };

                    function formatMoney(value) {
                        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value || 0);
                    }

                    function getDeductionPercent(metal) {
                        const raw = metalDeductions && Object.prototype.hasOwnProperty.call(metalDeductions, metal)
                            ? parseFloat(metalDeductions[metal])
                            : 0;
                        if (!Number.isFinite(raw)) return 0;
                        return Math.max(0, Math.min(100, raw));
                    }

                    function getAdjustedRate(rawRate, metal) {
                        const base = parseFloat(rawRate);
                        if (!Number.isFinite(base) || base <= 0) return 0;
                        const pct = getDeductionPercent(metal) / 100;
                        return base * (1 - pct);
                    }

                    function getPurityRatio(karatValue) {
                        const key = String(karatValue || '18');
                        const map = {
                            '24': 1.0,
                            '22': 0.916,
                            '21': 0.875,
                            '18': 0.75,
                        };
                        return Object.prototype.hasOwnProperty.call(map, key) ? map[key] : 0.75;
                    }

                    function getPurityPercentLabel(karatValue) {
                        const key = String(karatValue || '18');
                        const labels = {
                            '24': '100',
                            '22': '91.6',
                            '21': '87.5',
                            '18': '75.0',
                        };
                        return Object.prototype.hasOwnProperty.call(labels, key) ? labels[key] : '75.0';
                    }

                    function renderCards() {
                        if (!showCards || !elCards) return;

                        elCards.innerHTML = allowedMetals.map((metal) => {
                            const perGram = getAdjustedRate(prices.gram[metal], metal);
                            const value = perGram ? `${formatMoney(perGram)}/g` : 'N/A';
                            return `<div class="price-card"><div class="metal">${metal}</div><div class="value">${value}</div></div>`;
                        }).join('');
                    }

                    function calculate() {
                        const metal = elMetal ? elMetal.value : defaultMetal;
                        const unit = elUnit ? elUnit.value : 'grams';
                        const weight = parseFloat(elWeight ? elWeight.value : 0) || 0;
                        const purityCode = elPurity ? elPurity.value : '18';
                        const purityRatio = getPurityRatio(purityCode);

                        const rawRate = unit === 'ounces' ? prices.ounce[metal] : prices.gram[metal];
                        const rate = getAdjustedRate(rawRate, metal);
                        const estimated = (rate || 0) * weight * purityRatio;

                        if (elResult) {
                            elResult.textContent = formatMoney(estimated);
                        }

                        if (elBreakdown) {
                            const purityPct = getPurityPercentLabel(purityCode);
                            const suffix = unit === 'ounces' ? '/oz' : '/g';
                            const safeRate = rate || 0;
                            elBreakdown.textContent = `${weight} ${unit} x ${formatMoney(safeRate)}${suffix} x ${purityPct}% = ${formatMoney(estimated)}`;
                        }

                        if (elRate) {
                            const suffix = unit === 'ounces' ? '/oz' : '/g';
                            elRate.textContent = rate
                                ? `Live ${metal} rate: ${formatMoney(rate)}${suffix}`
                                : `Live ${metal} rate unavailable`;
                        }
                    }

                    async function loadPrices() {
                        try {
                            console.log('🔄 [Metals API] Fetching prices from:', '{{ route('api.metals.prices') }}');
                            
                            const response = await fetch('{{ route('api.metals.prices') }}', { headers: { 'Accept': 'application/json' } });
                            
                            console.log('📥 [Metals API] Response status:', response.status, response.statusText);
                            
                            const data = await response.json();
                            
                            console.log('📊 [Metals API] Full API Response:', data);
                            console.log('💰 [Metals API] Price Source:', data.source || 'unknown', 
                                       data.source === 'live' ? '(✓ REAL MARKET PRICES)' : 
                                       data.source === 'scraped' ? '(✓ SCRAPED MARKET PRICES)' :
                                       data.source === 'scraped-partial' ? '(⚠ MIXED: SCRAPED + FALLBACK)' :
                                       data.source === 'stale' ? '(⚠ USING LAST KNOWN PRICES)' :
                                       data.source === 'fallback' ? '(⚠ FALLBACK - APIs FAILED)' :
                                       data.source === 'partial' ? '(⚠ SOME DEMO PRICES)' : '(⚠ DEMO PRICES)');
                            
                            // Show backend debug log if available
                            if (data.debug && Array.isArray(data.debug)) {
                                console.log('🔍 [Metals API] Backend Debug Log:');
                                data.debug.forEach(log => console.log('   ', log));
                            }
                            
                            console.log('🥇 [Metals API] Gold Price:', data.prices_per_ounce_usd?.gold, '$/oz', 
                                       '→', data.prices_per_gram_usd?.gold, '$/g');
                            console.log('🥈 [Metals API] Silver Price:', data.prices_per_ounce_usd?.silver, '$/oz',
                                       '→', data.prices_per_gram_usd?.silver, '$/g');

                            if (!data || !data.success) {
                                throw new Error('Invalid prices payload');
                            }

                            prices.ounce = data.prices_per_ounce_usd || {};
                            prices.gram = data.prices_per_gram_usd || {};

                            renderCards();
                            calculate();

                            if (elUpdated) {
                                const stamp = data.last_updated ? new Date(data.last_updated) : new Date();
                                const sourceLabel = data.source === 'live' || data.source === 'scraped'
                                    ? '✓ Market Data'
                                    : data.source === 'scraped-partial'
                                    ? '⚠ Mixed Data'
                                    : data.source === 'stale'
                                    ? '⚠ Last Known Data'
                                    : '⚠ Approximate Data';
                                elUpdated.textContent = `Updated: ${stamp.toLocaleTimeString()} (${sourceLabel})`;
                            }
                        } catch (error) {
                            console.error('❌ [Metals API] Price load failed:', error);
                            console.error('❌ [Metals API] Error details:', error.message, error.stack);
                            if (elRate) {
                                elRate.textContent = 'Unable to refresh prices right now (using last values)';
                            }
                        }
                    }

                    [elMetal, elUnit, elWeight, elPurity].forEach((el) => {
                        if (!el) return;
                        el.addEventListener('input', calculate);
                        el.addEventListener('change', calculate);
                    });

                    if (elMetal && !allowedMetals.includes(elMetal.value) && allowedMetals.length > 0) {
                        elMetal.value = allowedMetals[0];
                    }

                    calculate();
                    loadPrices();
                    setInterval(loadPrices, refreshMs);
                })();
            </script>
        @break

        @default
            {{-- Fallback for any unhandled component types --}}
            <div style="{{ $styleStr }}">
                @if(isset($component['html']))
                    
                    {!! $component['html'] !!}
                @else
                    {{-- Silent fallback - no placeholder text displayed --}}
                    <div style="display: none;"></div>
                @endif
            </div>
        {{-- @break --}}

        
    @endswitch
</div>


<script>
    // Add global onclick functions for page builder preview
            if (!window.showLoginFormPreview) {
                window.showLoginFormPreview = function(button) {
                    const container = button.closest('.auth-form-container') || button.closest('[style*="auth-form"]') || button.closest('div');
                    const registerForm = container.querySelector('.register');
                    const loginForm = container.querySelector('.login');
                    const titleElement = container.querySelector('.tit');
                    
                    if (registerForm) registerForm.style.display = 'none';
                    if (loginForm) loginForm.style.display = 'block';
                    if (titleElement) titleElement.textContent = 'Login';
                };
            }
            
            if (!window.showRegisterFormPreview) {
                window.showRegisterFormPreview = function(button) {
                    const container = button.closest('.auth-form-container') || button.closest('[style*="auth-form"]') || button.closest('div');
                    const registerForm = container.querySelector('.register');
                    const loginForm = container.querySelector('.login');
                    const titleElement = container.querySelector('.tit');
                    
                    if (loginForm) loginForm.style.display = 'none';
                    if (registerForm) registerForm.style.display = 'block';
                    if (titleElement) titleElement.textContent = 'Register';
                };
            }
            
            // Add global toggleRegistrationFields function for this component
            if (!window.toggleRegistrationFields) {
                window.toggleRegistrationFields = function(selectElement) {
                    const teacherWrapper = document.getElementById('teacher_select_wrapper');
                    if (teacherWrapper) {
                        if (selectElement.value === 'individual') {
                            teacherWrapper.style.display = 'block';
                        } else {
                            teacherWrapper.style.display = 'none';
                        }
                    }
                };
            }
            
            // Remove inline padding from inner-section-frontend on mobile for grid components
            if (window.innerWidth <= 767) {
                document.addEventListener('DOMContentLoaded', function() {
                    // Find all inner-section-frontend elements containing grid components
                    const innerSections = document.querySelectorAll('.inner-section-frontend');
                    innerSections.forEach(section => {
                        const hasPropertyGrid = section.querySelector('.property-listing-grid-component');
                        const hasProductGrid = section.querySelector('.product-listing-grid-component');
                        
                        if (hasPropertyGrid || hasProductGrid) {
                            // Remove only left and right padding with !important
                            section.style.setProperty('padding-left', '0', 'important');
                            section.style.setProperty('padding-right', '0', 'important');
                        }
                    });
                });
                
                // Re-run on resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth <= 767) {
                        const innerSections = document.querySelectorAll('.inner-section-frontend');
                        innerSections.forEach(section => {
                            const hasPropertyGrid = section.querySelector('.property-listing-grid-component');
                            const hasProductGrid = section.querySelector('.product-listing-grid-component');
                            
                            if (hasPropertyGrid || hasProductGrid) {
                                section.style.setProperty('padding-left', '0', 'important');
                                section.style.setProperty('padding-right', '0', 'important');
                            }
                        });
                    }
                });
            }
</script>