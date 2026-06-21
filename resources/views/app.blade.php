<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Sadman Cart</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bangla:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon Configuration (Logo in Tab of PC) -->
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    @php
        $siteFavicon = \App\Models\Setting::get('site_favicon') ?? \App\Models\Setting::get('site_logo') ?? '/favicon.ico';
    @endphp
    <link rel="icon" type="image/png" href="{{ asset($siteFavicon) }}">

    @routes
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
    <script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1980990109188940');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1980990109188940&ev=PageView&noscript=1"
/></noscript>

</head>
<body class="font-sans antialiased h-full text-slate-900 bg-gray-50">
    @inertia
</body>
</html>
