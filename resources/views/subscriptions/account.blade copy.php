@extends('layouts.app')

@section('content')
<div class="max-w-4xl p-6 mx-auto space-y-6">

  {{-- Mensagens de sucesso/erro --}}
  @if (session('success'))
  <div class="p-4 text-green-800 bg-green-100 rounded-lg">
    {{ session('success') }}
  </div>
  @endif
  @if (session('error'))
  <div class="p-4 text-red-800 bg-red-100 rounded-lg">
    {{ session('error') }}
  </div>
  @endif

  {{-- Card de status --}}
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
        <span class="text-gray-500">/ {{ ucfirst($currentPlan['interval']) }}</span>
      </span>
      @endif
    </div>

    <div class="grid grid-cols-2 gap-6">
      {{-- Valor do plano --}}
      <div>
        <p class="text-sm text-gray-500">Valor do Plano</p>
        <p class="text-lg font-medium">
          {{ $currentPlan['price'] ?? '-' }}
        </p>
      </div>

      {{-- Trial --}}
      @if ($isTrial)
      <div>
        <p class="text-sm text-gray-500">Dias Restantes no Trial</p>
        <p class="text-lg font-medium">
          {{ $currentPlan['trial_days'] }} dias
        </p>
      </div>
      @endif

      {{-- Próximo Pagamento --}}
      <div>
        <p class="text-sm text-gray-500">Próxima Cobrança</p>
        <p class="text-lg font-medium">
          @if ($nextPayment)
          {{ $nextPayment->format('d/m/Y') }}
          @else
          -
          @endif
        </p>
      </div>

      {{-- Valor da Próxima Fatura --}}
      <div>
        <p class="text-sm text-gray-500">Valor da Próxima Fatura</p>
        <p class="text-lg font-medium">
          {{ $nextAmount ?? '-' }}
        </p>
      </div>

      {{-- Data de encerramento do ciclo --}}
      <div>
        <p class="text-sm text-gray-500">Fim do Ciclo</p>
        <p class="text-lg font-medium">
          @if ($planEndDate)
          {{ $planEndDate->format('d/m/Y') }}
          @else
          -
          @endif
        </p>
      </div>
    </div>

    {{-- Botões de ação --}}
    <div class="flex flex-wrap gap-4 mt-6">
      @if ($subscription->onGracePeriod())
      <a href="{{ route('subscriptions.resume') }}"
        class="px-4 py-2 font-medium text-white transition bg-green-600 rounded hover:bg-green-700">
        Reativar Assinatura
      </a>
      @else
      <a href="{{ route('subscriptions.cancel') }}"
        class="px-4 py-2 font-medium text-white transition bg-red-600 rounded hover:bg-red-700">
        Cancelar Assinatura
      </a>
      @endif

      <a href="{{ route('subscriptions.change-plan') }}"
        class="px-4 py-2 font-medium text-gray-800 transition bg-gray-200 rounded hover:bg-gray-300">
        Alterar Plano
      </a>
    </div>
    @else
    <p class="text-gray-600">Você ainda não possui nenhuma assinatura ativa.</p>
    @endif
  </div>

  {{-- Histórico de faturas --}}
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
          <td class="px-3 py-2">
            {{ $invoice->date()->format('d/m/Y') }}
          </td>
          <td class="px-3 py-2">
            R$ {{ $invoice->total() }}
          </td>
          <td class="px-3 py-2">
            @if ($invoice->paid)
            <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded">
              Pago
            </span>
            @else
            <span class="px-2 py-1 text-xs text-red-800 bg-red-200 rounded">
              Pendente
            </span>
            @endif
          </td>
          <td class="px-3 py-2 text-right">
            <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-blue-600 hover:underline">
              Ver Fatura
            </a>
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
