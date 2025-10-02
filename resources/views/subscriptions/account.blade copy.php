@extends('layouts.app')

@section('content')
<div class="mx-auto space-y-6 ">

  {{-- Mensagens --}}
  <x-alert />

  {{-- Assinatura --}}
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
        <p class="text-lg font-medium">R$ {{ $currentPlan['price'] ?? '-' }}
          <span class="text-gray-500">/ {{ ucfirst($currentPlan['interval']) }}</span>
        </p>
      </div>

      {{-- Aviso de período de graça --}}
      @if ($subscription && $subscription->onGracePeriod())
      @php
      $graceEnd = $subscription->ends_at;
      $diff = $graceEnd->diff(now());
      $graceDays = $diff->d;
      $graceHours = str_pad($diff->h, 2, '0', STR_PAD_LEFT);
      $graceMinutes = str_pad($diff->i, 2, '0', STR_PAD_LEFT);
      @endphp
      <div class="p-4 mt-4 mb-4 shadow alert-danger">
        <p>
          Sua assinatura foi cancelada e está em <strong>período de Teste</strong>.
        </p>
        <p>
          Restam <strong>{{ $graceDays }} dias e {{ $graceHours }}:{{ $graceMinutes }}
            horas</strong> de acesso, até
          <strong>{{ $graceEnd->format('d/m/Y H:i') }}</strong>.
        </p>
      </div>

      {{-- Aviso de trial apenas se não estiver em período de graça --}}
      @elseif ($isTrial && $trialDaysLeft > 0)
      <div class="p-4 mt-4 mb-4 shadow alert-warning">
        <p>
          Seu Trial termina em <strong>{{ $trialDaysLeft }} dias e
            {{ $trialHours }}:{{ $trialMinutes }} horas</strong>
          (até {{ $subscription->trial_ends_at->format('d/m/Y H:i') }}).
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
        <p class="text-sm text-gray-500">Valor da Fatura</p>
        <p class="text-lg font-medium">{{ $nextAmount ?? '-' }}</p>
      </div>

      <div>
        <p class="text-sm text-gray-500">Fim do Ciclo</p>
        <p class="text-lg font-medium">
          {{ $planEndDate ? $planEndDate->format('d/m/Y') : '-' }}
        </p>
      </div>
    </div>

    {{-- Botões --}}
    <div class="flex gap-4 mt-6">
      @if ($subscription->onGracePeriod())
      <a href="{{ route('subscriptions.resume') }}" class="px-4 py-2 font-medium btn-success">
        {{ __('Reativar Assinatura') }}
      </a>
      @else
      <a href="{{ route('subscriptions.cancel') }}" class="px-4 py-2 font-medium btn-danger ">
        {{ __('Cancelar Assinatura') }}
      </a>
      @endif
      <a href="{{ route('subscriptions.change-plan') }}" class="px-4 py-2 btn btn-default">
        {{ __('Alterar Plano') }}
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
          <td class="px-3 py-2">{{ $invoice->formatted_date }}</td>
          <td class="px-3 py-2">R$ {{ $invoice->formatted_total }}</td>
          <td class="px-3 py-2">
            <span class="{{ $invoice->status_class }}">
              {{ $invoice->status_label }}
            </span>
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
