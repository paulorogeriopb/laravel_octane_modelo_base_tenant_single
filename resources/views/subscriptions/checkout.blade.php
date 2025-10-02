@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto content-box">
        <div class="mb-4 content-box-header">
            <h3 class="content-box-title">{{ __('Checkout') }}</h3>
        </div>

        <x-alert />

        <p class="mb-4">Plano selecionado: <strong>{{ $plan->name }}</strong></p>
        <form action="{{ route('subscriptions.store') }}" method="POST" class="space-y-4" id="payment-form">
            @csrf

            <!-- Nome no cartão -->
            <input type="text" name="card-holder-name" placeholder="{{ __('Nome no Cartão') }}"
                class="w-full p-3 bg-white rounded-md shadow-sm">

            <!-- Número do cartão -->
            <label class="block">
                <span class="text-gray-700">{{ __('Número do Cartão') }}</span>
                <div id="card-number" class="w-full p-3 bg-white rounded-md shadow-sm"></div>
            </label>

            <!-- Validade e CVC -->
            <div class="flex gap-4">
                <label class="flex-1">
                    <span class="text-gray-700">{{ __('Validade (MM/AA)') }}</span>
                    <div id="card-expiry" class="p-3 bg-white rounded-md shadow-sm"></div>
                </label>

                <label class="flex-1">
                    <span class="text-gray-700">{{ __('CVC') }}</span>
                    <div id="card-cvc" class="p-3 bg-white rounded-md shadow-sm"></div>
                </label>
            </div>

            <!-- CEP separado -->
            <input type="text" name="postal-code" id="postal-code" placeholder="{{ __('CEP') }}"
                class="w-full p-3 bg-white rounded-md shadow-sm">

            <!-- Botão -->
            <button id="card-button" data-secret="{{ $intent->client_secret }}" type="submit"
                class="flex items-center justify-center w-full h-12 mt-2 rounded-md btn-default-md">
                {{ __('Pagar') }}
            </button>
        </form>
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
                    color: "#374151",
                    "::placeholder": {
                        color: "#9ca3af"
                    },
                },
                invalid: {
                    color: "#dc2626",
                    iconColor: "#dc2626",
                },
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

                // Desabilita botão e altera texto
                cardButton.disabled = true;
                const originalText = cardButton.textContent;
                cardButton.textContent = "{{ __('Processando pagamento, aguarde...') }}";

                try {
                    const {
                        setupIntent,
                        error
                    } = await stripe.confirmCardSetup(clientSecret, {
                        payment_method: {
                            card: cardNumber,
                            billing_details: {
                                name: cardHolderName.value,
                                address: {
                                    postal_code: document.getElementById('postal-code').value
                                }
                            }
                        }
                    });

                    if (error) throw error;

                    // Cria input hidden com token
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'token';
                    tokenInput.value = setupIntent.payment_method;
                    form.appendChild(tokenInput);

                    // Envia formulário
                    form.submit();

                } catch (err) {
                    console.error(err);
                    alert(err.message || "Ocorreu um erro no pagamento.");
                    cardButton.disabled = false;
                    cardButton.textContent = originalText;
                }
            });
        });
    </script>
@endpush
