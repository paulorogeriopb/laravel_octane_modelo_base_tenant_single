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
    </head>

    <body class="font-sans text-gray-800 bg-gray-50">

        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white shadow">
            <div class="container flex items-center justify-between px-6 py-4 mx-auto">
                <!-- Logo -->
                <h1 class="text-2xl font-bold text-principal">UpSoft</h1>

                <!-- Navegação -->
                <nav class="hidden space-x-6 md:flex">
                    <a href="#features" class="hover:text-principal">Recursos</a>
                    <a href="#about" class="hover:text-principal">Sobre</a>
                    <a href="#pricing" class="hover:text-principal">Preços</a>
                    <a href="#contact" class="hover:text-principal">Contato</a>
                </nav>

                <!-- Autenticação -->
                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hover:text-principal">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-principal">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hover:text-principal">Cadastrar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </header>

        <!-- Hero Section -->
        <section class="py-20 text-center text-white bg-principal">
            <div class="container px-6 mx-auto">
                <h2 class="mb-4 text-4xl font-bold">Transforme sua gestão com a UpSoft</h2>
                <p class="mb-8 text-lg">Soluções tecnológicas para empresas modernas. Rápido. Seguro. Inteligente.</p>
                <a href="#features"
                    class="px-6 py-3 font-semibold transition bg-white rounded text-principal hover:bg-gray-100">
                    Conheça os Recursos
                </a>
            </div>
        </section>

        <!-- Recursos -->
        <section id="features" class="py-20 bg-white">
            <div class="container px-6 mx-auto text-center">
                <h3 class="mb-10 text-3xl font-bold text-principal">Recursos Poderosos</h3>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="p-6 border rounded shadow-sm">
                        <h4 class="mb-2 text-xl font-semibold">Painel Inteligente</h4>
                        <p>Tenha uma visão clara e atualizada da sua operação com gráficos e KPIs personalizados.</p>
                    </div>
                    <div class="p-6 border rounded shadow-sm">
                        <h4 class="mb-2 text-xl font-semibold">Controle Financeiro</h4>
                        <p>Automatize receitas, despesas, relatórios e gestão de fluxo de caixa com segurança.</p>
                    </div>
                    <div class="p-6 border rounded shadow-sm">
                        <h4 class="mb-2 text-xl font-semibold">Multiusuário & Permissões</h4>
                        <p>Gerencie diferentes acessos e permissões com total controle e flexibilidade.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sobre -->
        <section id="about" class="py-20 bg-gray-100">
            <div class="container px-6 mx-auto text-center">
                <h3 class="mb-6 text-3xl font-bold text-principal">Sobre a UpSoft</h3>
                <p class="max-w-3xl mx-auto text-lg leading-relaxed">
                    A UpSoft nasceu da visão de integrar tecnologia e eficiência em um só lugar. Nossa missão é simplificar
                    processos complexos por meio de sistemas inteligentes, acessíveis e escaláveis.
                </p>
            </div>
        </section>

        <!-- Preços -->
        <section id="pricing" class="py-20 bg-white">
            <div class="container px-6 mx-auto text-center">
                <h3 class="mb-10 text-3xl font-bold text-principal">Planos Acessíveis</h3>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="p-6 border rounded shadow-sm">
                        <h4 class="mb-2 text-xl font-semibold">Start</h4>
                        <p class="mb-4 text-2xl font-bold">R$ 29/mês</p>
                        <ul class="mb-6 space-y-2 text-sm">
                            <li>✔ 1 usuário</li>
                            <li>✔ Dashboard básico</li>
                            <li>✔ Suporte por e-mail</li>
                        </ul>
                        <a href="#" class="px-4 py-2 text-white rounded bg-principal hover:opacity-90">Começar</a>
                    </div>
                    <div class="p-6 border-2 rounded shadow-md border-principal">
                        <h4 class="mb-2 text-xl font-semibold">Profissional</h4>
                        <p class="mb-4 text-2xl font-bold">R$ 69/mês</p>
                        <ul class="mb-6 space-y-2 text-sm">
                            <li>✔ Até 5 usuários</li>
                            <li>✔ Dashboard completo</li>
                            <li>✔ Suporte prioritário</li>
                        </ul>
                        <a href="#" class="px-4 py-2 text-white rounded bg-principal hover:opacity-90">Assinar</a>
                    </div>
                    <div class="p-6 border rounded shadow-sm">
                        <h4 class="mb-2 text-xl font-semibold">Empresarial</h4>
                        <p class="mb-4 text-2xl font-bold">Sob consulta</p>
                        <ul class="mb-6 space-y-2 text-sm">
                            <li>✔ Usuários ilimitados</li>
                            <li>✔ Integrações personalizadas</li>
                            <li>✔ Suporte dedicado</li>
                        </ul>
                        <a href="#" class="px-4 py-2 text-white rounded bg-principal hover:opacity-90">Fale
                            conosco</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contato -->
        <section id="contact" class="py-20 bg-gray-100">
            <div class="container px-6 mx-auto text-center">
                <h3 class="mb-6 text-3xl font-bold text-principal">Fale Conosco</h3>
                <p class="max-w-xl mx-auto mb-6">Tem dúvidas ou precisa de uma solução personalizada? Nossa equipe está
                    pronta para te ajudar!</p>
                <a href="mailto:contato@upsoft.com" class="px-6 py-3 text-white rounded bg-principal hover:opacity-90">
                    contato@upsoft.com
                </a>
            </div>
        </section>

        <!-- Rodapé -->
        <footer class="py-6 text-center bg-white border-t">
            <div class="container px-6 mx-auto text-sm text-gray-600">
                © {{ date('Y') }} UpSoft. Todos os direitos reservados.
            </div>
        </footer>
    </body>
@endsection
