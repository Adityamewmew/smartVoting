<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pemilihan') — SmartVoting</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col bg-[#F9FAFB] bg-radial-subtle text-gray-900 overflow-x-hidden selection:bg-primary-100 selection:text-primary-700">

    {{-- Main --}}
    <main class="relative z-10 flex-grow w-full flex flex-col justify-start">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
