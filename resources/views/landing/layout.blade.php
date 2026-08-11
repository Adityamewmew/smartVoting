<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SmartVoting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col font-normal">
    <!-- Navbar -->
    <header class="glass-header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-ink flex items-center justify-center text-paper font-normal text-xl">
                    S
                </div>
                <span class="text-xl font-normal text-ink">SmartVoting</span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-sm font-normal text-ink hover:underline">Login Admin</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} SmartVoting. Hak Cipta Dilindungi.
        </div>
    </footer>
</body>
</html>
