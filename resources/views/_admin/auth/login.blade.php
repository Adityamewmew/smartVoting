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
        <div class="bg-white p-7 sm:p-8 rounded-2xl border border-gray-100 shadow-sm">
            <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                @error('login_error')
                    <div class="p-4 bg-red-50 border border-red-200 text-xs font-semibold text-red-700 rounded-xl flex items-center gap-2">
                        <svg class="size-4 shrink-0 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

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

                <x-admin.button type="submit" id="login-btn" size="md" color="primary" class="w-full justify-center text-sm font-bold shadow-xs mt-2">
                    <span id="btn-text">Masuk ke Sistem</span>
                    <span id="btn-spinner"
                        class="animate-spin size-4 border-2 border-white border-t-transparent rounded-full hidden"
                        role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <span id="btn-loading-text" class="hidden text-xs">Memverifikasi...</span>
                </x-admin.button>
            </form>
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
