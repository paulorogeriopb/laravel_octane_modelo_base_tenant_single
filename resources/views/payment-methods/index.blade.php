@extends('layouts.app')

@section('content')
    <div class="max-w-3xl p-6 mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Meus Cartões</h3>
            <a href="{{ route('subscriptions.account') }}" class="inline-flex items-center btn-default ">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Minha Assinatura
            </a>
        </div>

        <!-- Alert -->
        <x-alert />

        <!-- Lista de cartões -->
        <div class="space-y-4">
            @forelse($paymentMethods as $pm)
                <div
                    class="flex flex-col p-4 transition bg-white rounded-lg shadow md:flex-row md:items-center md:justify-between hover:shadow-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ ucfirst($pm->card->brand) }} •••• {{ $pm->card->last4 }}</p>
                        <p class="text-sm text-gray-500">Expira {{ $pm->card->exp_month }}/{{ $pm->card->exp_year }}</p>
                        @if ($defaultPaymentMethod && $defaultPaymentMethod->id === $pm->id)
                            <span
                                class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Padrão</span>
                        @endif
                    </div>

                    <div class="flex gap-2 mt-2 md:mt-0">
                        @if (!$defaultPaymentMethod || $defaultPaymentMethod->id !== $pm->id)
                            <form action="{{ route('payment-methods.default', $pm->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 btn-light">Definir
                                    Padrão</button>
                            </form>

                            <x-delete-button :route="'payment-methods.destroy'" :id="$pm->id" text="Remover"
                                class="px-3 py-1 text-sm text-white transition btn-danger" />
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Nenhum cartão cadastrado.</p>
            @endforelse
        </div>

        <!-- Adicionar novo cartão -->
        <div class="p-6 mt-6 bg-white rounded-lg shadow">
            <h4 class="mb-4 text-lg font-semibold text-gray-800">Adicionar Novo Cartão</h4>

            <form action="{{ route('payment-methods.store') }}" method="POST" id="payment-form" class="space-y-4">
                @csrf

                <input type="text" name="card-holder-name" placeholder="Nome no Cartão" class="form-input">

                <div id="card-number" class="form-input"></div>
                <div class="flex gap-4">
                    <div id="card-expiry" class="form-input"></div>
                    <div id="card-cvc" class="form-input"></div>
                </div>

                <button id="card-button" data-secret="{{ $intent->client_secret }}" type="submit"
                    class="w-full h-12 transition btn-success">
                    Adicionar Cartão
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe("{{ config('cashier.key') }}");
            const elements = stripe.elements();

            const style = {
                base: {
                    fontSize: "16px",
                    fontFamily: "'Figtree', sans-serif",
                    color: "#111827"
                }
            };

            const cardNumber = elements.create('cardNumber', {
                style,
                showIcon: true
            });
            const cardExpiry = elements.create('cardExpiry', {
                style
            });
            const cardCvc = elements.create('cardCvc', {
                style
            });

            cardNumber.mount('#card-number');
            cardExpiry.mount('#card-expiry');
            cardCvc.mount('#card-cvc');

            const form = document.getElementById('payment-form');
            const cardHolderName = form.querySelector('input[name="card-holder-name"]');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                cardButton.textContent = "Adicionando cartão, favor aguarde...";
                cardButton.disabled = true;

                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardNumber,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                });

                if (error) {
                    alert(error.message);
                    cardButton.disabled = false;
                    cardButton.textContent = "Adicionar Cartão";
                } else {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'token';
                    tokenInput.value = setupIntent.payment_method;
                    form.appendChild(tokenInput);
                    form.submit();
                }
            });
        });
    </script>
@endpush
