<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SmartVoting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ponytail: override glass-card yellow shadow on landing pages only */
        .landing-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body class="font-poppins antialiased min-h-screen flex flex-col bg-white text-ink overflow-x-hidden">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <a href="#" class="text-lg font-bold text-brand-brown">SmartVoting</a>

            <nav class="hidden md:flex items-center gap-6 text-sm text-pewter font-medium">
                <a href="#kandidat" class="border-b-2 border-brand-yellow text-ink pb-0.5">Kandidat</a>
                <a href="#visi-misi" class="hover:text-ink transition-colors">Visi & Misi</a>
            </nav>

            <a href="{{ route('login') }}" class="bg-brand-brown text-white text-sm font-semibold px-4 py-1.5 rounded-full inline-flex items-center gap-1.5 hover:bg-brand-brown/90 transition-colors">
                Masuk <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
            </a>
        </div>
    </header>

    {{-- Main Content — full width, sections handle their own containers --}}
    <main class="relative z-10 flex-grow w-full">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-carbon text-white/70">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <span class="font-bold text-brand-yellow text-sm">SmartVoting</span>
            <p>&copy; {{ date('Y') }} Didukung oleh platform e-voting SmartVoting. Seluruh hak cipta dilindungi.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>
</body>
</html>
