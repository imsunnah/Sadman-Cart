<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'E-commerce') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @php
        $siteLogo = \App\Models\Setting::where('key', 'site_logo')->value('value') ?? '/favicon.ico';
    @endphp
    <link rel="icon" href="{{ asset($siteLogo) }}">

    @routes
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="font-sans antialiased h-full text-slate-900 bg-gray-50">
    @inertia
</body>
</html>
