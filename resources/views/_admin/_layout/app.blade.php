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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])

    <!-- NProgress -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

</head>

<body>
    <!-- ========== HEADER ========== -->
    <header class="fixed top-0 left-0 right-0 z-[48] w-full bg-white shadow-sm border-b border-gray-100">
        <nav class="flex items-center justify-between px-4 sm:px-6 w-full h-16">
            <div class="flex items-center justify-between w-full lg:w-auto">
                <!-- Logo -->
                <a class="flex-none rounded-md inline-block focus:outline-hidden focus:opacity-80" href="#">
                    <img src="{{ asset('images/logo-light.png') }}" alt="Logo Smart Project Starter Kit" class="h-8 w-auto">
                </a>

                <!-- Navigation Toggle (Mobile) -->
                <button type="button"
                    class="lg:hidden me-0 size-8 px-3 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-hidden focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none glass-button shadow-none"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar"
                    aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
                    <span class="sr-only">Toggle Navigation</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <path d="M15 3v18" />
                        <path d="m8 9 3 3-3 3" />
                    </svg>
                </button>
            </div>

            <!-- Date Time area (Hidden on mobile) -->
            <div class="hidden lg:flex items-center gap-3">
                <div class="bg-blue-50 text-blue-800 text-sm font-semibold px-4 py-1.5 rounded-full border border-blue-100">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
                <div class="bg-[#FFB22C]/10 text-[#FFB22C] text-sm font-bold px-4 py-1.5 rounded-full border border-[#FFB22C]/20">
                    {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H.i') }} WIB
                </div>
            </div>
        </nav>
    </header>
    <div class="mt-16"></div>
    <!-- ========== END HEADER ========== -->

    @include('_admin._layout.sidebar.sidebar')

    <!-- Content -->
    <div class="w-full lg:ps-64 min-h-screen relative z-10">
        <div id="main-content" class="space-y-6 px-6 py-8 sm:px-8 sm:py-10 max-w-screen-2xl mx-auto w-full">
            @if (session('success'))
                <div id="spa-flash-success" style="display: none;">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div id="spa-flash-error" style="display: none;">{{ session('error') }}</div>
            @endif
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
