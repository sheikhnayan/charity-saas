@php
    $fallback = request()->headers->get('referer') ?? url('/');
@endphp
<a href="javascript:history.back()" onclick="if(history.length<=1){ location.href='{{ $fallback }}'; return false;}" class="btn btn-ghost back-button" style="display:inline-flex;align-items:center;gap:.5rem;">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9 3L5 7L9 11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Back
</a>
