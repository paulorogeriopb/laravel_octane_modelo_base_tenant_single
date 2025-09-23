<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#32a2b9">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app_auth.js'])
</head>

<body class="relative">

    @yield('content')

    <!-- Banner de instalação PWA -->
    <div id="install-banner"
        class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-[#32a2b9] text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 hidden z-50">
        <span>Instale nosso app para melhor experiência!</span>
        <button id="install-btn"
            class="bg-white text-[#32a2b9] px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Instalar</button>
    </div>

</body>

<script>
    // Registrar Service Worker
    if ("serviceWorker" in navigator) {
        window.addEventListener("load", function() {
            navigator.serviceWorker
                .register("/service-worker.js")
                .then(function(registration) {
                    console.log("ServiceWorker registrado com sucesso:", registration);
                })
                .catch(function(error) {
                    console.log("Falha ao registrar ServiceWorker:", error);
                });
        });
    }

    // Banner de instalação PWA
    let deferredPrompt;
    const installBanner = document.getElementById('install-banner');
    const installBtn = document.getElementById('install-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // previne prompt automático
        deferredPrompt = e;
        installBanner.classList.remove('hidden'); // mostra banner
    });

    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt(); // abre prompt oficial
        const {
            outcome
        } = await deferredPrompt.userChoice;
        console.log('Resultado da instalação:', outcome);
        deferredPrompt = null;
        installBanner.classList.add('hidden'); // esconde banner
    });
</script>

</html>
