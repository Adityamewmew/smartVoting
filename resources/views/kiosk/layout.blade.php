<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bilik Suara')</title>

    <!-- Meta tags for Kiosk Mode (prevent caching, zoom, etc.) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-geist antialiased min-h-screen flex flex-col items-center justify-center select-none bg-slate-50 text-slate-900">

    <main class="w-full h-full min-h-screen flex flex-col relative">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
