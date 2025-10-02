@extends('layouts.app')

@section('content')


    <div class="mx-auto space-y-6 content-box">
        <x-alert />


        <!-- Assinatura Atual -->
        <div class="p-6 transition-shadow bg-white rounded-lg shadow dark:bg-gray-800 hover:shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Assinatura Atual') }}</h3>
                @if (auth()->user()->subscription('default'))
                    @if (auth()->user()->subscription('default')->onGracePeriod())
                        <span
                            class="px-2 py-1 text-sm font-medium text-green-800 bg-green-200 rounded-full">{{ __('Período de Graça') }}</span>
                    @elseif(auth()->user()->subscription('default')->active())
                        <span
                            class="px-2 py-1 text-sm font-medium text-green-800 bg-blue-200 rounded-full">{{ __('Ativa') }}</span>
                    @else
                        <span
                            class="px-2 py-1 text-sm font-medium text-gray-800 bg-gray-200 rounded-full">{{ __('Inativa') }}</span>
                    @endif
                @else
                    <span
                        class="px-2 py-1 text-sm font-medium text-gray-800 bg-gray-200 rounded-full">{{ __('Sem assinatura') }}</span>
                @endif
            </div>

            @if (auth()->user()->subscription('default'))
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        @if ($currentPlan)
                            <p>Plano atual:
                                <strong>{{ $currentPlan['name'] }}</strong> -
                                R$ {{ number_format((float) $currentPlan['price'], 2, ',', '.') }}
                            </p>
                        @else
                            <p>Plano atual: Nenhum</p>
                        @endif

                        <p><strong>{{ __('Início:') }}</strong>
                            {{ auth()->user()->subscription('default')->created_at?->format('d/m/Y') ?? __('N/A') }}</p>

                        <p><strong>{{ __('Próximo Pagamento:') }}</strong>
                            {{ auth()->user()->subscription('default')->current_period_end?->format('d/m/Y') ?? __('N/A') }}
                        </p>
                    </div>
                    <div>
                        @if (auth()->user()->defaultPaymentMethod())
                            <p><strong>{{ __('Cartão:') }}</strong>
                                {{ auth()->user()->defaultPaymentMethod()->card->brand }} ****
                                {{ auth()->user()->defaultPaymentMethod()->card->last4 }}</p>
                            <p><strong>{{ __('Expira:') }}</strong>
                                {{ auth()->user()->defaultPaymentMethod()->card->exp_month }}/{{ auth()->user()->defaultPaymentMethod()->card->exp_year }}
                            </p>
                        @else
                            <p class="text-red-500">{{ __('Nenhum método de pagamento cadastrado.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex gap-4 mt-6">
                    @if (auth()->user()->subscription('default')->onGracePeriod())
                        <a href="{{ route('subscriptions.resume') }}" class="px-4 py-2 font-medium btn-success">
                            {{ __('Reativar Assinatura') }}
                        </a>
                    @else
                        <a href="{{ route('subscriptions.cancel') }}" class="px-4 py-2 font-medium btn-danger">
                            {{ __('Cancelar Assinatura') }}
                        </a>
                    @endif
                    <a href="{{ route('subscriptions.change-plan') }}" class="px-4 py-2 btn btn-default">
                        {{ __('Alterar Plano') }}
                    </a>
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">{{ __('Você ainda não possui uma assinatura ativa.') }}
                    <a href="{{ route('subscriptions.change-plan') }}" class="px-4 py-2 btn btn-default">
                        {{ __('Escolher Plano') }}
                    </a>
                </p>
            @endif
        </div>

        <!-- Histórico de Faturas -->
        <div class="p-6 transition-shadow bg-white rounded-lg shadow dark:bg-gray-800 hover:shadow-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Histórico de Faturas') }}</h3>

            @forelse($invoices as $invoice)
                <div
                    class="flex flex-col items-center justify-between gap-4 p-4 mb-3 border border-gray-200 rounded-lg md:flex-row dark:border-gray-700">
                    <div>
                        <p><strong>{{ __('Data:') }}</strong> {{ $invoice->date()->format('d/m/Y') }}</p>
                        <p><strong>{{ __('Valor:') }}</strong> {{ $invoice->total() }}
                        </p>
                        <p><strong>{{ __('Status:') }}</strong>
                            @if ($invoice->paid)
                                <span
                                    class="px-2 py-1 text-sm text-green-800 bg-green-200 rounded-full">{{ __('Pago') }}</span>
                            @else
                                <span
                                    class="px-2 py-1 text-sm text-red-800 bg-red-200 rounded-full">{{ __('Pendente') }}</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('subscriptions.invoice.download', $invoice->id) }}"
                        class="flex items-center gap-2 px-4 py-2 btn-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Download PDF') }}
                    </a>
                </div>
            @empty
                <x-empty-message message="{{ __('Nenhuma fatura encontrada.') }}" />
            @endforelse
        </div>
    </div>
@endsection
