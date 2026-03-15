@php
    $components = $footerBuilderComponents
        ?? ($footerBuilderState['components'] ?? (is_array($footerBuilderState) ? $footerBuilderState : []));
@endphp

@foreach($components as $componentData)
    @include('builders.render-builder-component', [
        'componentData' => $componentData,
        'header' => $header,
        'footer' => $footer,
        'check' => $check,
        'data' => $data,
        'menuSections' => $menuSections,
    ])
@endforeach
