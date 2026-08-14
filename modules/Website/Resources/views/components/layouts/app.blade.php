{{-- root/modules/Website/Resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="neutral">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($page['page']['meta_description'])
        <meta name="description" content="{{ $page['page']['meta_description'] }}">
    @endisset

    <title>{{ isset($page['page']['title']) ? $page['page']['title'] . ' — ' . __('website::brand.name') : __('website::meta.default_title') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased" x-data="websiteStore()">
    <div class="flex min-h-screen flex-col">
        <x-website::navbar />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-website::footer />
    </div>
</body>
</html>
