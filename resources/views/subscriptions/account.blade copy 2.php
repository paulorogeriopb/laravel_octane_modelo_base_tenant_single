@extends('layouts.app')

@section('content')
    <div class="max-w-4xl p-6 mx-auto space-y-6">
        <x-alert />

        {{-- Minha Assinatura --}}
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
                    <p class="text-sm text-gray-500">Método de pagamento</p>

                    @if ($card)
                        <div class="flex items-center justify-between p-3 mt-2 bg-white rounded shadow-sm">
                            <p>{{ ucfirst($card['brand']) }} •••• {{ $card['last4'] }} — expira
                                {{ $card['exp_month'] }}/{{ $card['exp_year'] }}</p>

                            <form action="{{ route('payment-method.remove') }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja remover este cartão?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 text-sm text-red-600 border rounded hover:bg-red-50">
                                    Remover
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Nenhum cartão cadastrado</p>
                    @endif

                    {{-- Adicionar novo cartão --}}
                    <div class="mt-4">
                        <form id="payment-form" action="{{ route('payment-method.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" id="payment_method_id">

                            <div id="card-element" class="p-3 border rounded"></div>

                            <button type="submit" class="px-4 py-2 mt-3 btn btn-success">
                                Adicionar Novo Cartão
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Ações da Assinatura --}}
                <div class="flex gap-4 mt-6">
                    @if ($subscription->onGracePeriod())
                        <form action="{{ route('subscriptions.resume') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 font-medium btn-success">Reativar Assinatura</button>
                        </form>
                    @else
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
                                <td class="px-3 py-2"><span
                                        class="{{ $invoice->status_class }}">{{ $invoice->status_label }}</span></td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('subscriptions.invoice.download', $invoice->id) }}"
                                        class="text-cyan-700 hover:underline">Ver Fatura</a>
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

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                alert(error.message);
            } else {
                document.getElementById('payment_method_id').value = paymentMethod.id;
                form.submit();
            }
        });
    </script>
@endpush
