<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_ENV') == 'local' ? '[LOCAL] ' : '' }}SmartVoting</title>

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])

    <!-- FontAwesome & EasyMDE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">

    <!-- NProgress -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 font-geist antialiased selection:bg-blue-500 selection:text-white">
    <!-- ========== HEADER ========== -->
    <header class="fixed top-0 left-0 right-0 z-50 w-full bg-white/95 backdrop-blur-md shadow-xs border-b border-gray-200/80">
        <nav class="flex items-center justify-between px-4 sm:px-6 w-full h-16">
            <div class="flex items-center justify-between w-full lg:w-auto">
                <!-- Logo -->
                <a class="flex items-center gap-2.5 rounded-md focus:outline-hidden" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-8 w-auto">
                    @if(!empty($current_tenant?->name))
                        <span class="hidden sm:inline-block text-xs font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200 truncate max-w-[200px]">
                            {{ $current_tenant->name }}
                        </span>
                    @endif
                </a>

                <!-- Navigation Toggle (Mobile) -->
                <button type="button"
                    class="lg:hidden me-0 size-9.5 px-2.5 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-700 hover:bg-gray-50 active:scale-95 rounded-xl focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer transition-transform"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar"
                    aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
                    <span class="sr-only">Toggle Navigation</span>
                    <svg class="shrink-0 size-4.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <path d="M15 3v18" />
                        <path d="m8 9 3 3-3 3" />
                    </svg>
                </button>
            </div>

            <!-- Date Time area (Hidden on mobile) -->
            <div class="hidden md:flex items-center gap-2.5">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100/70 text-blue-700 text-xs font-bold px-3.5 py-1.5 rounded-full border border-blue-200/80 shadow-2xs flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                <div class="bg-gradient-to-r from-slate-50 to-gray-100 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200/80 shadow-2xs">
                    {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H.i') }} WIB
                </div>
            </div>
        </nav>
    </header>
    <div class="mt-16"></div>
    <!-- ========== END HEADER ========== -->

    @include('_admin._layout.sidebar.sidebar')

    <!-- Content -->
    <div class="w-full lg:ps-64 min-h-screen">
        <div id="main-content" class="animate-page-enter space-y-6 px-4 py-5 sm:px-6 sm:py-8 lg:px-8 lg:py-10 max-w-screen-2xl mx-auto w-full">
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
