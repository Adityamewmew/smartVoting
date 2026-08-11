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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-normal antialiased min-h-screen flex flex-col items-center justify-center select-none">
    
    <main class="w-full h-full min-h-screen flex flex-col relative">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
