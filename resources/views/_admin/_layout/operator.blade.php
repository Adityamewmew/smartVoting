<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_ENV') == 'local' ? '[LOCAL] ' : '' }}Smart Project Starter Kit</title>

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])

    <!-- NProgress -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

</head>

<body>
    <!-- Content -->
    <div class="w-full bg-gray-50 dark:bg-neutral-900 min-h-screen">
        <div id="main-content" class="space-y-6 px-6 py-8 sm:px-8 sm:py-10 max-w-screen-2xl mx-auto w-full">
            @if (session('success'))
                <div id="spa-flash-success" style="display: none;">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div id="spa-flash-error" style="display: none;">{{ session('error') }}</div>
            @endif
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">SmartVote</h1>
                    <p class="text-sm text-gray-500">Panel Operator Kiosk</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-600 hover:bg-red-200 focus:outline-none">
                        Keluar
                    </button>
                </form>
            </div>

            @yield('content')
        </div>
    </div>
    <!-- End Content -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- NProgress -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    @stack('scripts')

</body>

</html>
