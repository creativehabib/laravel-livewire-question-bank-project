@php
    $defaultStack = "'Hind Siliguri', 'Shurjo', 'Kalpurush', 'SolaimanLipi', 'Roboto', sans-serif";
    $fontBody = trim((string) \App\Models\Setting::get('frontend_font_body', $defaultStack));
    $fontHeading = trim((string) \App\Models\Setting::get('frontend_font_heading', $fontBody ?: $defaultStack));
    $fontImportUrl = trim((string) \App\Models\Setting::get('frontend_font_import_url', ''));

    if ($fontBody === '') {
        $fontBody = $defaultStack;
    }

    if ($fontHeading === '') {
        $fontHeading = $fontBody;
    }

    if ($fontImportUrl === '') {
        $fontImportUrl = null;
    }
@endphp
@if ($fontImportUrl)
    <link rel="stylesheet" href="{{ $fontImportUrl }}">
@endif
<style>
    :root {
        --app-font-body: {{ $fontBody }};
        --app-font-heading: {{ $fontHeading }};
    }
</style>
