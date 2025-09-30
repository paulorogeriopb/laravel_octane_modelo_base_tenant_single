@extends('layouts.app')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>
    </div>

    <div class="max-w-md mx-auto content-box">
        <!-- Header -->
        <div class="flex items-center justify-between content-box-header">
            <h3 class="text-lg font-semibold content-box-title">{{ __('Minha Assinatura') }}</h3>
        </div>

        <x-alert />

        <!-- Conteúdo -->

        @if (Auth::user()->subscription('default'))
            @if (Auth::user()->subscription('default')->onGracePeriod())
                <!-- Botão Reativar (Success) -->
                <a href="{{ route('subscriptions.resume') }}" class="flex items-center justify-center p-4 mb-4 btn-success">
                    {{ __('Reativar Assinatura') }}
                </a>
            @else
                <!-- Botão Cancelar (Danger) -->
                <a href="{{ route('subscriptions.cancel') }}" class="flex items-center justify-center p-4 mb-4 btn-danger">
                    {{ __('Cancelar Assinatura') }}
                </a>
            @endif
        @else
            <div
                class="p-4 mb-4 transition-shadow duration-200 bg-gray-100 rounded-lg shadow dark:bg-gray-800 hover:shadow-lg">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Nenhuma assinatura encontrada.') }}</span>
            </div>
        @endif

        <!-- Lista de Faturas -->
        @forelse ($invoices as $invoice)
            <div
                class="p-4 mb-4 transition-shadow duration-200 bg-white rounded-lg shadow dark:bg-gray-800 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $invoice->date()->format('d/m/Y') }}
                    </span>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">
                        {{ $invoice->total() }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Assinatura Mensal') }}</span>
                    <a href="{{ route('subscriptions.invoice.download', $invoice->id) }}"
                        class="flex items-center gap-2 px-4 py-2 btn-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Download') }}
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-empty-message message="{{ __('Nenhuma fatura encontrada.') }}" />
            </div>
        @endforelse
    </div>
@endsection
