@extends('layouts.app')

@section('content')
    @php
        $user = auth()->user();
        $currentSubscription = $user?->subscription('default');
        $currentPlan = $currentSubscription?->items()->first()?->stripe_price ?? null;
        $currentPlanPrice = $plans->firstWhere('stripe_id', $currentPlan)?->price ?? 0;
    @endphp

    <div class="mx-auto content-box">
        <h2 class="mb-12 text-3xl font-bold text-center text-principal">{{ __('Escolha seu plano') }}</h2>

        <x-alert />

        <div x-data="{ tab: 'monthly' }" class="mx-auto max-w-7xl">
            <div class="flex justify-center mb-8 space-x-4">
                <button @click="tab = 'monthly'"
                    :class="tab === 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'"
                    class="px-6 py-2 font-semibold transition-colors duration-300 rounded-full cursor-pointer">
                    Mensal
                </button>
                <button @click="tab = 'yearly'"
                    :class="tab === 'yearly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'"
                    class="px-6 py-2 font-semibold transition-colors duration-300 rounded-full cursor-pointer">
                    Anual
                </button>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($plans as $plan)
                    <div x-show="tab === '{{ $plan->billing_cycle }}'" x-transition
                        class="flex flex-col p-6 transition bg-white shadow-lg rounded-2xl hover:shadow-xl
                    {{ $plan->recommended ? 'border-2 border-blue-600' : '' }}">

                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-semibold">{{ $plan->name }}</h3>
                            @if ($plan->recommended)
                                <span class="px-2 py-1 text-xs text-white bg-blue-600 rounded">Mais Popular</span>
                            @endif
                        </div>

                        <p class="mb-4 text-gray-400">{{ $plan->description }}</p>

                        <div class="mb-4">
                            <span class="text-xl font-bold text-blue-600">
                                R${{ number_format($plan->price, 2, ',', '.') }} /
                                {{ $plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}
                            </span>
                        </div>

                        @if (!empty($plan->features) && is_array($plan->features))
                            <ul class="mb-6 space-y-1 text-gray-600">
                                @foreach ($plan->features as $feature)
                                    <li>• {{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @php
                            $isCurrentPlan = $currentPlan === $plan->stripe_id;
                            $isUpgrade = $plan->price > $currentPlanPrice;
                            $isDowngrade = $plan->price < $currentPlanPrice;
                            $forceSobeConsulta = $plan->name === 'Nim Ultra'; // sempre habilita
                        @endphp

                        <form action="{{ route('subscriptions.update-plan') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $plan->id }}">

                            @if ($isCurrentPlan && !$forceSobeConsulta)
                                <button type="button" disabled
                                    class="w-full px-4 py-3 font-semibold text-center text-white bg-gray-400 rounded cursor-not-allowed">
                                    Plano Ativo
                                </button>
                            @elseif($isUpgrade || $forceSobeConsulta)
                                <button type="submit"
                                    class="w-full px-4 py-3 font-semibold text-center text-white bg-blue-600 rounded cursor-pointer hover:bg-blue-700">
                                    Sobe Consulta
                                </button>
                            @elseif($isDowngrade)
                                <button type="button" disabled
                                    class="w-full px-4 py-3 font-semibold text-center text-white bg-gray-400 rounded cursor-not-allowed">
                                    Plano Inferior
                                </button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
