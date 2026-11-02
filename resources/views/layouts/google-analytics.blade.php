@php
    $analyticsWebsite = $website ?? $check ?? null;

    if (!$analyticsWebsite) {
        $analyticsWebsite = \App\Models\Website::where('domain', request()->getHost())->first();
    }

    $gaTrackingId = $analyticsWebsite->google_analytics_id ?? null;
@endphp
@if($gaTrackingId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaTrackingId }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $gaTrackingId }}');
</script>
@endif
