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

        </section>

        @include('site._partials.plans', ['plans' => $plans])

    </body>
@endsection
