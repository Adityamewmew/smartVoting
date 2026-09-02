<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Platform E-Voting Modern') | SmartVoting</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">

    <!-- Styles / Scripts Starterkit -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])

    @stack('styles')
</head>
<body class="font-geist antialiased min-h-screen flex flex-col bg-gradient-to-b from-sky-50/80 via-blue-50/30 to-slate-50 text-slate-900 overflow-x-hidden selection:bg-blue-600 selection:text-white">

    {{-- Main Content --}}
    <main class="relative z-10 flex-grow w-full flex flex-col justify-start">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
