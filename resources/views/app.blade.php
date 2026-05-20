<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Sadman Cart</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bangla:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon Configuration (Logo in Tab of PC) -->
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    @php
        $siteFavicon = \App\Models\Setting::where('key', 'site_favicon')->value('value') ?? \App\Models\Setting::where('key', 'site_logo')->value('value') ?? '/favicon.ico';
    @endphp
    <link rel="icon" type="image/png" href="{{ asset($siteFavicon) }}">

    @routes
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="font-sans antialiased h-full text-slate-900 bg-gray-50">
    @inertia
</body>
</html>
