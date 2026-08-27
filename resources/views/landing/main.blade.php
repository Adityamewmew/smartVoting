<!DOCTYPE html>
<html class="scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SmartVoting — Platform E-Voting Kiosk Modern, Aman &amp; Terpercaya</title>
    <meta name="description"
        content="Sistem e-voting digital berbasis bilik suara kiosk fisik untuk OSIS, BEM, dan Institusi. 100% suara anonim, anti-double voting, dan rekapitulasi real-time." />

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">

    <!-- Styles / Scripts Starterkit -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])
</head>

<body class="font-geist bg-slate-50 text-slate-900 antialiased selection:bg-brand-500 selection:text-white overflow-x-hidden">

    <!-- Header Navigation -->
    @include('landing.partials.header')

    <main>
        <!-- Hero Section -->
        @include('landing.partials.hero')

        <!-- Solutions & Dashboard Showcase Section -->
        @include('landing.partials.solutions')

        <!-- Features Section -->
        @include('landing.partials.features')

        <!-- Testimonials Section -->
        @include('landing.partials.testimonials')

        <!-- Pricing Section -->
        @include('landing.partials.pricing')
    </main>

    <!-- Footer Section -->
    @include('landing.partials.footer')

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>

</html>
