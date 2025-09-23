<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#32a2b9">

    <!-- iOS (Safari) suporte -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <script src="{{ asset('js/theme-init.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/bootstrap.js', 'resources/js/app_auth.js', 'resources/js/no-alert.js', 'resources/js/spinner.js'])



</head>



<body class="bg-dashboard">
    <div id="global-spinner"
        class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 opacity-100 bg-white/20 backdrop-blur-sm">
        <x-loading-spinner />
    </div>


    @include('components.navigation') {{-- sua navbar com h-20 fixed top-0 --}}

    <div class="flex flex-col sm:flex-row">
        @include('components.sidebar')


        <main class="flex-1 p-4">

            @can('dashboard')
                @yield('content')
                @stack('scripts')
            @endcan
        </main>
    </div>

    <footer class="fixed bottom-0 z-50 w-full py-2 pr-4 text-sm text-right text-gray-500">
        Versão: {{ trim(exec('git describe --tags --abbrev=0')) }}
    </footer>




    <script>
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
    </script>

    <script>
        function languageSelect() {
            return {
                open: false,
                selected: {
                    code: '{{ app()->getLocale() }}',
                    name: '',
                    flag: ''
                },
                languages: [{
                        code: 'pt_BR',
                        name: 'Português (BR)',
                        flag: '/images/flags/br.png'
                    },
                    {
                        code: 'en',
                        name: 'English',
                        flag: '/images/flags/us.png'
                    },
                    {
                        code: 'es',
                        name: 'Español',
                        flag: '/images/flags/es.png'
                    },
                ],
                init() {
                    // Inicializa selected com base no código atual
                    let current = this.languages.find(l => l.code === this.selected.code);
                    if (current) {
                        this.selected.name = current.name;
                        this.selected.flag = current.flag;
                    }
                },
                submitForm() {
                    this.open = false;
                    this.selected = this.languages.find(l => l.code === this.selected.code);
                    // Submete o form com o idioma selecionado
                    this.$el.submit();
                }
            }
        }
    </script>




</body>

</html>
