@extends('_admin._layout.auth')

@section('title', 'Login')

@section('content')
    <div class="w-full max-w-md">
        <div class="mb-6 flex justify-center">
            <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="w-auto h-15">
        </div>
        <div class="glass-card p-6 ">
            <div class="text-center mb-8">
                <div>
                    <h1 class="block text-3xl font-bold text-gray-800 dark:text-white">Login Aplikasi</h1>
                    <p class="mt-2 text-sm text-gray-400 dark:text-neutral-400">
                        Silahkan login menggunakan Akun anda
                    </p>
                </div>
            </div>

            <form id="login-form" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="grid gap-y-4">
                    @error('login_error')
                        <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-lg p-4 mb-4 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500"
                            role="alert" tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
                            <span id="hs-soft-color-danger-label" class="font-bold"></span> {{ $message }}
                        </div>
                    @enderror

                    <!-- Form Group -->
                    <x-admin.input
                        type="text"
                        id="email"
                        name="email"
                        label="Akun (Email / NPSN)"
                        required
                        aria-describedby="email-error"
                    />
                    <!-- End Form Group -->

                    <!-- Form Group -->
                    <x-admin.input
                        type="password"
                        id="password"
                        name="password"
                        label="Password"
                        required
                        aria-describedby="password-error"
                    />
                    <!-- End Form Group -->

                    <x-admin.button type="submit" id="login-btn" size="lg" class="w-full text-lg">
                        <span id="btn-text" class="tracking-widest">MASUK</span>
                        <span id="btn-spinner"
                            class="animate-spin size-4 border-[3px] border-current border-t-transparent text-white rounded-full hidden"
                            role="status" aria-label="loading">
                            <span class="sr-only">Loading...</span>
                        </span>
                        <span id="btn-loading-text" class="hidden">Loading...</span>
                    </x-admin.button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const btnLoadingText = document.getElementById('btn-loading-text');

            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
            btnSpinner.classList.add('inline-block');
            btnLoadingText.classList.remove('hidden');
        });
    </script>
    </div>
@endsection
