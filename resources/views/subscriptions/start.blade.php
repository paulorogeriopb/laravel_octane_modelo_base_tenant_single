@extends('layouts.app')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>
    </div>

    <div class="content-box">
        <!-- inicio  Content box header -->
        <div class="content-box-header">
            <h3 class="content-box-title">{{ __('Área do assinante') }}</h3>
            <div class="content-box-btn"></div>
        </div>
        <!-- fim  Content box header -->

        <x-alert />

        <!-- Inicio do conteudo -->

        <div class="flex flex-wrap justify-between w-full gap-4">
            <div class="flex-1 min-w-[300px] box-cont ">
                Olá, assinante Start
            </div>
        </div>
        <!-- Fim do conteudo -->
    </div>
@endsection
