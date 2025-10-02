@props([
    'type' => 'trial', // 'trial' ou 'grace'
    'days' => 0,
    'hours' => 0,
    'minutes' => 0,
    'endsAt' => null,
])

@if ($days > 0 || $hours > 0 || $minutes > 0)
    @php
        $colors = [
            'trial' => 'bg-yellow-200 text-yellow-800',
            'grace' => 'bg-orange-200 text-orange-800',
        ];

        $labels = [
            'trial' => 'período de teste',
            'grace' => 'período de graça',
        ];
    @endphp

    <div class="p-4 mt-4 mb-4 shadow {{ $colors[$type] ?? 'bg-gray-200 text-gray-800' }}">
        <p>
            ⚠️
            @if ($type === 'trial')
                Você está em <strong>{{ $labels[$type] }}</strong>.
            @else
                Sua assinatura foi cancelada e está em <strong>{{ $labels[$type] }}</strong>.
            @endif
        </p>
        <p>
            Restam <strong>{{ $days }}</strong> {{ \Illuminate\Support\Str::plural('dia', $days) }}
            @if (!is_null($hours))
                e
                <strong>{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</strong>
                horas
            @endif
            — encerra em <strong>{{ $endsAt?->format('d/m/Y H:i') }}</strong>.
        </p>
    </div>
@endif
