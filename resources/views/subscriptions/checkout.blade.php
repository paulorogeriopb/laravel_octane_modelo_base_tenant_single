@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto content-box">
        <div class="mb-6 text-center">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('Checkout') }}</h3>
            <p class="mt-2 text-gray-600">
                Plano selecionado: <span class="font-semibold cor-default">{{ $plan->name }}</span>
            </p>
        </div>

        <x-alert />

        <form action="{{ route('subscriptions.store') }}" method="POST" class="space-y-6" id="payment-form">
            @csrf

            <!-- Nome no cartão -->
            <div>
                <label class="form-label">{{ __('Nome no Cartão') }}</label>
                <input type="text" name="card-holder-name" placeholder="{{ __('Ex: Paulo Rogério') }}"
                    class="form-input">
                <small class="hidden mt-1 text-red-500"
                    id="name-error">{{ __('Informe o nome do titular do cartão') }}</small>
            </div>

            <!-- Número do cartão -->
            <div>
                <label class="form-label">{{ __('Número do Cartão') }}</label>
                <div id="card-number" class="form-input"></div>
                <small class="hidden mt-1 text-red-500" id="number-error"></small>
            </div>

            <!-- Validade e CVC -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="form-label">{{ __('Validade (MM/AA)') }}</label>
                    <div id="card-expiry" class="form-input"></div>
                    <small class="hidden mt-1 text-red-500" id="expiry-error"></small>
                </div>

                <div class="flex-1">
                    <label class="form-label">{{ __('CVC') }}</label>
                    <div id="card-cvc" class="form-input"></div>
                    <small class="hidden mt-1 text-red-500" id="cvc-error"></small>
                </div>
            </div>

            <!-- Mensagem geral -->
            <div id="form-error" class="hidden text-sm font-medium text-red-600"></div>

            <!-- Botão -->
            <div class="pt-2">
                <button id="card-button" data-secret="{{ $intent->client_secret }}" type="submit"
                    class="w-full h-12 transition btn-success">
                    {{ __('Finalizar Pagamento') }}
                </button>
            </div>

            <!-- Segurança -->
            <p class="flex items-center justify-center mt-3 text-xs text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-2 size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                Pagamento 100% seguro via Stripe
            </p>
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
                    iconColor: "#dc2626"
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
            const formError = document.getElementById('form-error');

            // helper p/ mostrar erros
            const setError = (id, message) => {
                const el = document.getElementById(id);
                if (message) {
                    el.textContent = message;
                    el.classList.remove('hidden');
                } else {
                    el.textContent = "";
                    el.classList.add('hidden');
                }
            };

            cardNumber.on('change', e => setError('number-error', e.error?.message));
            cardExpiry.on('change', e => setError('expiry-error', e.error?.message));
            cardCvc.on('change', e => setError('cvc-error', e.error?.message));

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                formError.classList.add('hidden');

                if (!cardHolderName.value.trim()) {
                    setError('name-error', "{{ __('Informe o nome do titular do cartão') }}");
                    return;
                } else {
                    setError('name-error', null);
                }

                cardButton.disabled = true;
                const originalText = cardButton.textContent;
                cardButton.textContent = "{{ __('Processando pagamento...') }}";

                try {
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

                    if (error) throw error;

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'token';
                    tokenInput.value = setupIntent.payment_method;
                    form.appendChild(tokenInput);

                    form.submit();

                } catch (err) {
                    formError.textContent = err.message || "Ocorreu um erro no pagamento.";
                    formError.classList.remove('hidden');
                    cardButton.disabled = false;
                    cardButton.textContent = originalText;
                }
            });
        });
    </script>
@endpush
