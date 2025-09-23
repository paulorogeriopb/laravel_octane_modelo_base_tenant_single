<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app_auth.js', 'resources/js/app.js', 'resources/js/spinner.js'])
</head>

<body class="bg-login">

    <div id="global-spinner"
        class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 opacity-100 bg-white/20 backdrop-blur-sm">
        <x-loading-spinner />
    </div>

    @yield('content')

    @stack('scripts') {{-- ESSENCIAL para puxar o password-rules.js --}}

</body>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
