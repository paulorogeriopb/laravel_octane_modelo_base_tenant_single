@extends('layouts.app')

@section('content')
    <div class="max-w-4xl p-6 mx-auto space-y-6">

        <x-alert />

        <div class="p-6 bg-white shadow rounded-xl">
            <h2 class="mb-4 text-xl font-bold">Minha Assinatura</h2>

            @if ($subscription)
                <div class="flex items-center justify-between mb-6">
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                    @if ($currentPlan)
                        <span class="text-lg font-semibold">
                            {{ $currentPlan['name'] }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Valor do Plano</p>
                        <p class="text-lg font-medium">
                            R$ {{ $currentPlan['price'] ?? '-' }} <span class="text-gray-500">/
                                {{ $currentPlan['interval'] }} </span>
                        </p>
                    </div>

                    @if (!empty($currentPlan['trial_days']) && $subscription->onTrial())
                        <div>
                            <p class="text-sm text-gray-500">Tempo Restante no Trial</p>
                            <p class="text-lg font-medium">
                                {{ $remainingPayload['days'] }}
                                {{ \Illuminate\Support\Str::plural('dia', $remainingPayload['days']) }}
                                @if (!empty($remainingPayload['hours']) || !empty($remainingPayload['minutes']))
                                    e
                                    {{ str_pad($remainingPayload['hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($remainingPayload['minutes'], 2, '0', STR_PAD_LEFT) }}
                                    horas
                                @endif
                            </p>
                        </div>
                    @endif




                    <div>
                        <p class="text-sm text-gray-500">Próxima Cobrança</p>
                        <p class="text-lg font-medium">
                            {{ $nextPayment ? $nextPayment->format('d/m/Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Valor da Próxima Fatura</p>
                        <p class="text-lg font-medium">{{ $nextAmount ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Fim do Ciclo</p>
                        <p class="text-lg font-medium">{{ $planEndDate ? $planEndDate->format('d/m/Y') : '-' }}</p>
                    </div>

                </div>

                <div class="flex gap-4 mt-6">
                    @if ($subscription->onGracePeriod())
                        <form action="{{ route('subscriptions.resume') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 font-medium btn-success">Reativar Assinatura</button>
                        </form>
                    @else
                        <form action="{{ route('subscriptions.cancel') }}" method="POST" class="inline form-cancel">
                            @csrf
                            <button type="submit" class="px-4 py-2 font-medium btn-danger">Cancelar Assinatura</button>
                        </form>
                    @endif

                    <a href="{{ route('subscriptions.change-plan') }}" class="px-4 py-2 btn btn-default">Alterar Plano</a>
                </div>

                {{-- Avisos: trial ou grace --}}
                @if ($subscription && $remainingPayload)
                    @if ($remainingPayload['is_trial'] && $subscription->onTrial())
                        <x-trial-expiration-warning :days="$remainingPayload['days']" :hours="$remainingPayload['hours']" :minutes="$remainingPayload['minutes']"
                            :ends-at="$remainingPayload['ends_at']" />
                    @elseif ($remainingPayload['is_grace'] && !$subscription->active())
                        <x-grace-period-warning :days="$remainingPayload['days']" :hours="$remainingPayload['hours']" :minutes="$remainingPayload['minutes']" :ends-at="$remainingPayload['ends_at']" />
                    @endif
                @endif
            @else
                <p class="text-gray-600">Você ainda não possui nenhuma assinatura ativa.</p>
            @endif
        </div>

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
                                    @if ($invoice->hosted_url)
                                        <a href="{{ $invoice->hosted_url }}" target="_blank"
                                            class="text-blue-600 hover:underline">Ver Fatura</a>
                                    @else
                                        <a href="{{ route('subscriptions.invoice.download', $invoice->id) }}"
                                            class="text-blue-600 hover:underline">Download PDF</a>
                                    @endif
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
