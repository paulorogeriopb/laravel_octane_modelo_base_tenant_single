@extends('layouts.app')

@section('content')
    <div class="max-w-4xl p-6 mx-auto space-y-6">
        <x-alert />

        {{-- Minha Assinatura --}}
        <div class="p-6 bg-white shadow rounded-xl">
            <h2 class="mb-4 text-xl font-bold">Minha Assinatura</h2>

            @if ($subscription)
                <div class="flex items-center justify-between mb-6">
                    {{-- Status da assinatura --}}
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                    {{-- Plano atual --}}
                    @if ($currentPlan)
                        <span class="text-lg font-semibold">
                            {{ $currentPlan['name'] }}
                        </span>
                    @endif
                </div>

                {{-- Informações detalhadas --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Valor do Plano</p>
                        <p class="text-lg font-medium">
                            R$ {{ $currentPlan['price'] ?? '-' }}
                            <span class="text-gray-500">/ {{ $currentPlan['interval'] }}</span>
                        </p>
                    </div>

                    @if (!empty($currentPlan['trial_days']) && $subscription->onTrial())
                        <div>
                            <p class="text-sm text-gray-500">Tempo Restante no Trial</p>
                            <p class="text-lg font-medium">
                                {{ $trialDaysLeft }} {{ \Illuminate\Support\Str::plural('dia', $trialDaysLeft) }}
                                @if (!empty($trialHours) || !empty($trialMinutes))
                                    e
                                    {{ str_pad($trialHours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($trialMinutes, 2, '0', STR_PAD_LEFT) }}h
                                @endif
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500">Próxima Cobrança</p>
                        <p class="text-lg font-medium">{{ $nextPayment ? $nextPayment->format('d/m/Y') : '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Valor da Próxima Fatura</p>
                        <p class="text-lg font-medium">R$ {{ $nextAmount ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Fim do Ciclo</p>
                        <p class="text-lg font-medium">{{ $planEndDate ? $planEndDate->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                {{-- Métodos de pagamento --}}
                <div class="mt-6">
                    <p class="mb-2 text-sm text-gray-500">Método de pagamento</p>
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        @if ($card)
                            <div class="flex-1 p-3 bg-gray-100 rounded shadow-sm">
                                <p>{{ ucfirst($card['brand']) }} •••• {{ $card['last4'] }} — expira
                                    {{ $card['exp_month'] }}/{{ $card['exp_year'] }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Nenhum cartão cadastrado</p>
                        @endif
                        <a href="{{ route('payment-methods.index') }}" class="btn-light">
                            Gerenciar Cartões
                        </a>
                    </div>
                </div>

                {{-- Ações da assinatura --}}
                <div class="flex gap-4 mt-6">
                    @php
                        $canResume =
                            $subscription->onGracePeriod() ||
                            ($subscription->asStripeSubscription()->cancel_at_period_end ?? false);
                    @endphp

                    @if ($canResume)
                        <form action="{{ route('subscriptions.resume') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 font-medium btn-success">Reativar Assinatura</button>
                        </form>
                    @elseif ($subscription->active())
                        <form action="{{ route('subscriptions.cancel') }}" method="POST" class="form-cancel">
                            @csrf
                            <button type="submit" class="px-4 py-2 font-medium btn-danger">Cancelar Assinatura</button>
                        </form>
                    @endif

                    <a href="{{ route('subscriptions.change-plan') }}" class="px-4 py-2 btn btn-default">Alterar Plano</a>
                </div>
            @else
                <p class="text-gray-600">Você ainda não possui nenhuma assinatura ativa.</p>
            @endif
        </div>

        {{-- Histórico de Faturas --}}
        <div class="p-6 bg-white shadow rounded-xl">
            <h2 class="mb-4 text-xl font-bold">Histórico de Faturas</h2>

            @if (count($invoices))
                <table class="w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="text-gray-700 bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Data</th>
                            <th class="px-3 py-2 text-left">Valor</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ $invoice->formatted_date }}</td>
                                <td class="px-3 py-2">R$ {{ $invoice->formatted_total }}</td>
                                <td class="px-3 py-2">
                                    <span class="{{ $invoice->status_class }}">{{ $invoice->status_label }}</span>
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('subscriptions.invoice.download', $invoice->id) }}"
                                        class="link-default">Ver Fatura</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">Nenhuma fatura encontrada.</p>
            @endif
        </div>
    </div>
@endsection
