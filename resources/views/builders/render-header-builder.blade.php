@php
    $components = $headerBuilderComponents
        ?? ($headerBuilderState['components'] ?? (is_array($headerBuilderState) ? $headerBuilderState : []));
@endphp

@if($header && $header->status == 1)
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
@endif
