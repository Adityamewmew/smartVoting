<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SmartVoting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col font-normal bg-slate-50 text-slate-800 overflow-x-hidden">

    {{-- Animated Background Orbs --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute rounded-full bg-yellow-200 opacity-60" style="width:45vw;height:45vw;top:-10%;left:-10%;filter:blur(90px);animation:floatOrb 20s infinite alternate ease-in-out;"></div>
        <div class="absolute rounded-full bg-yellow-300 opacity-60" style="width:40vw;height:40vw;bottom:-20%;right:-5%;filter:blur(90px);animation:floatOrb 20s -5s infinite alternate ease-in-out;"></div>
        <div class="absolute rounded-full bg-yellow-100 opacity-50" style="width:35vw;height:35vw;top:30%;left:35%;filter:blur(90px);animation:floatOrb 20s -10s infinite alternate ease-in-out;"></div>
    </div>

    <style>
        @keyframes floatOrb {
            0%   { transform: translate(0,0) scale(1); }
            33%  { transform: translate(30px,-40px) scale(1.05); }
            66%  { transform: translate(-20px,20px) scale(0.95); }
            100% { transform: translate(0,0) scale(1); }
        }
    </style>

    {{-- Main Content --}}
    <main class="relative z-10 flex-grow max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 py-10 text-center">
        <p class="text-slate-500 font-medium text-sm">
            Didukung oleh platform e-voting <span class="text-yellow-600 font-bold">SmartVoting</span>
        </p>
    </footer>
</body>
</html>
