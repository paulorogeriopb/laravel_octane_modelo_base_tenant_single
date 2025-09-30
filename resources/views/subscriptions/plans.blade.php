@extends('layouts.app')

@section('content')
    <div class="mx-auto content-box">
        <h2 class="mb-6 text-xl font-semibold">{{ __('Escolha seu plano') }}</h2>

        <x-alert />

        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-3">
            @foreach ($plans as $key => $plan)
                <form action="{{ route('subscriptions.update-plan') }}" method="POST"
                    class="p-4 transition-shadow duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $key }}">

                    <div class="flex flex-col justify-between h-full">
                        <h3 class="mb-2 text-lg font-semibold">{{ $plan['name'] }}</h3>
                        <p class="mb-4 text-gray-600">{{ $plan['price'] }}/mês</p>

                        @if ($currentPlanId === $plan['stripe_id'])
                            <span class="px-6 py-3 text-center btn-success">
                                {{ __('Plano Atual') }}
                            </span>
                        @else
                            <button type="submit" class="px-6 py-3 btn-default">
                                {{ __('Selecionar Plano') }}
                            </button>
                        @endif

                    </div>
                </form>
            @endforeach
        </div>
    </div>
@endsection
