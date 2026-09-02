@extends('_admin._layout.auth')

@section('title', 'Login')

@section('content')
    <div class="w-full max-w-md space-y-6">
        {{-- Brand Logo & Badge --}}
        <div class="text-center">
            <div class="inline-flex items-center justify-center size-12 rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-600/25 mb-4">
                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/></svg>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900">{{ $currentTenant->name ?? 'SmartVoting' }}</h1>
            <p class="text-xs font-medium text-gray-500 mt-1">
                {{ $currentTenant ? 'Panel Administrasi & Bilik Suara E-Voting' : 'Masuk ke Portal Manajemen & Administrasi' }}
            </p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
            @error('login_error')
                <div class="p-4 bg-red-50 border border-red-200 text-xs font-semibold text-red-700 rounded-xl flex items-center gap-2">
                    <svg class="size-4 shrink-0 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- Primary: Sign In with Google --}}
            <div>
                <a href="{{ route('auth.google') }}" 
                   id="google-login-btn"
                   class="w-full inline-flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold shadow-xs transition duration-200 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="size-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>
            </div>

            {{-- Collapsible Manual Login for Operators / Superadmins --}}
            <div class="pt-2 border-t border-gray-100" x-data="{ open: {{ $errors->has('login_error') || $errors->has('email') ? 'true' : 'false' }} }">
                <button type="button" 
                        @click="open = !open" 
                        class="w-full text-center text-xs font-medium text-gray-500 hover:text-gray-800 transition py-1 flex items-center justify-center gap-1.5">
                    <span>Masuk dengan Email & Password Manual</span>
                    <svg class="size-3.5 transition-transform" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </button>

                <div x-show="open" x-cloak class="mt-4 pt-2 space-y-4">
                    <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <x-admin.input
                            type="text"
                            id="email"
                            name="email"
                            label="Email Akun"
                            placeholder="nama@domain.com"
                            required
                            aria-describedby="email-error"
                        />

                        <!-- Password Input -->
                        <x-admin.input
                            type="password"
                            id="password"
                            name="password"
                            label="Password"
                            placeholder="••••••••"
                            required
                            aria-describedby="password-error"
                        />

                        <x-admin.button type="submit" id="login-btn" size="md" color="primary" class="w-full justify-center text-sm font-bold shadow-xs">
                            <span id="btn-text">Masuk</span>
                            <span id="btn-spinner"
                                class="animate-spin size-4 border-2 border-white border-t-transparent rounded-full hidden"
                                role="status" aria-label="loading">
                                <span class="sr-only">Loading...</span>
                            </span>
                            <span id="btn-loading-text" class="hidden text-xs">Memverifikasi...</span>
                        </x-admin.button>
                    </form>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ $currentTenant->name ?? 'SmartVoting' }} &bull; {{ $currentTenant ? 'Powered by SmartVoting' : 'Aplikasi E-Voting Terpercaya' }}
        </p>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const btnLoadingText = document.getElementById('btn-loading-text');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
            btnSpinner.classList.add('inline-block');
            btnLoadingText.classList.remove('hidden');
        });
    </script>
@endsection
