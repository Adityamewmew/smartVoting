<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bilik Suara') — SmartVoting</title>

    <!-- Meta tags for Kiosk Mode (prevent caching, zoom, etc.) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col items-center justify-center select-none bg-[#F9FAFB] bg-radial-subtle text-gray-900 selection:bg-transparent" oncontextmenu="return false;">

    <main class="w-full h-full min-h-screen flex flex-col relative">
        @yield('content')
    </main>

    <script>
        // Kiosk mode hardening: prevent accidental drag & drop, context menu
        document.addEventListener('dragstart', (e) => e.preventDefault());
        document.addEventListener('drop', (e) => e.preventDefault());
    </script>
    @stack('scripts')
</body>
</html>
