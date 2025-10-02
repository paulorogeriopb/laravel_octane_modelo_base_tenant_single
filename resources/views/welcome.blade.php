@extends('layouts.site')

@section('content')
    <style>
        :root {
            --cor-principal: #32a2b9;
        }

        .bg-principal {
            background-color: var(--cor-principal);
        }

        .text-principal {
            color: var(--cor-principal);
        }

        .hover\:text-principal:hover {
            color: var(--cor-principal);
        }

        .hover\:bg-principal:hover {
            background-color: var(--cor-principal);
        }
    </style>

    <body class="font-sans text-gray-800 bg-gray-50">

        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white shadow">
            <div class="container flex items-center justify-between px-6 py-4 mx-auto">
                <h1 class="text-2xl font-bold text-principal">Nimbus</h1>
                <nav class="hidden space-x-6 md:flex">
                    <a href="#features" class="hover:text-principal">Recursos</a>
                    <a href="#about" class="hover:text-principal">Sobre</a>
                    <a href="#pricing" class="hover:text-principal">Planos</a>
                    <a href="#contact" class="hover:text-principal">Contato</a>
                </nav>
                <div class="space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hover:text-principal">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-principal">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hover:text-principal">Cadastrar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>





        <!-- Planos -->
        <section id="pricing" class="py-20 bg-white">
            <div class="container px-4 mx-auto">
                <h2 class="mb-12 text-3xl font-bold text-center text-principal">Planos Nimbus Imobiliária</h2>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">

                    {{-- Nim Start --}}
                    <div class="flex flex-col p-6 transition bg-white shadow-lg rounded-2xl hover:shadow-xl">
                        <h3 class="mb-2 text-xl font-semibold">Nim Start</h3>
                        <p class="mb-4 text-gray-400">Para quem está começando</p>
                        <div class="mb-4">
                            <span class="mr-2 text-gray-400 line-through">R$349,99</span>
                            <span class="text-2xl font-bold text-blue-600">R$149,99 / mês</span>
                        </div>
                        <ul class="mb-6 space-y-2 text-gray-600">
                            <li>2 Usuários</li>
                            <li>300 Imóveis</li>
                            <li>500 Negócios</li>
                            <li>Editor de páginas</li>
                            <li>Page Builder</li>
                            <li>Integração com portais e WhatsApp</li>
                        </ul>
                        <a href="#contact"
                            class="px-4 py-2 mt-auto font-semibold text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                            Escolha seu plano
                        </a>
                    </div>

                    {{-- Nim Basic --}}
                    <div
                        class="flex flex-col p-6 transition bg-white border-2 border-blue-600 shadow-lg rounded-2xl hover:shadow-xl">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-semibold">Nim Basic</h3>
                            <span class="px-2 py-1 text-xs text-white bg-blue-600 rounded">Mais Popular</span>
                        </div>
                        <p class="mb-4 text-gray-400">Para negócios em crescimento</p>
                        <div class="mb-4">
                            <span class="mr-2 text-gray-400 line-through">R$549,99</span>
                            <span class="text-2xl font-bold text-blue-600">R$249,99 / mês</span>
                        </div>
                        <ul class="mb-6 space-y-2 text-gray-600">
                            <li>5 Usuários</li>
                            <li>1000 Imóveis</li>
                            <li>2000 Negócios</li>
                            <li>50 contratos de locação</li>
                            <li>2.000 e-mails transacionais</li>
                            <li>Fluxo de Caixa e Relatórios</li>
                        </ul>
                        <a href="#contact"
                            class="px-4 py-2 mt-auto font-semibold text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                            Escolha seu plano
                        </a>
                    </div>

                    {{-- Nim Pro --}}
                    <div class="flex flex-col p-6 transition bg-white shadow-lg rounded-2xl hover:shadow-xl">
                        <h3 class="mb-2 text-xl font-semibold">Nim Pro</h3>
                        <p class="mb-4 text-gray-400">Para negócios que escalaram</p>
                        <div class="mb-4">
                            <span class="mr-2 text-gray-400 line-through">R$799,99</span>
                            <span class="text-2xl font-bold text-blue-600">R$399,99 / mês</span>
                        </div>
                        <ul class="mb-6 space-y-2 text-gray-600">
                            <li>12 Usuários</li>
                            <li>Imóveis ilimitados</li>
                            <li>5.000 Negócios</li>
                            <li>150 contratos de locação</li>
                            <li>Notificações via APP</li>
                        </ul>
                        <a href="#contact"
                            class="px-4 py-2 mt-auto font-semibold text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                            Escolha seu plano
                        </a>
                    </div>

                    {{-- Nim Ultra --}}
                    <div class="flex flex-col p-6 transition bg-white shadow-lg rounded-2xl hover:shadow-xl">
                        <h3 class="mb-2 text-xl font-semibold">Nim Ultra</h3>
                        <p class="mb-4 text-gray-400">Para empresas que exigem alto desempenho</p>
                        <div class="mb-4">
                            <span class="mr-2 text-gray-400 line-through">Consulte</span>
                            <span class="text-2xl font-bold text-blue-600">Sob consulta</span>
                        </div>
                        <ul class="mb-6 space-y-2 text-gray-600">
                            <li>25 Usuários</li>
                            <li>Imóveis ilimitados</li>
                            <li>Negócios ilimitados</li>
                            <li>300 contratos de locação</li>
                            <li>Métricas, múltiplas filiais, gestão de comissão</li>
                        </ul>
                        <a href="#contact"
                            class="px-4 py-2 mt-auto font-semibold text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                            Escolha seu plano
                        </a>
                    </div>

                </div>
            </div>
        </section>



    </body>
@endsection
