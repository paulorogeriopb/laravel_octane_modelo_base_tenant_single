@props([
    'days' => null,
    'hours' => null,
    'minutes' => null,
    'endsAt' => null,
])

@if (($days ?? 0) > 0 || ($hours ?? 0) > 0 || ($minutes ?? 0) > 0)
    <div class="p-4 mt-4 mb-4 text-yellow-800 bg-yellow-100 rounded shadow grace-period-warning">
        <p>
            ⚠️ Sua assinatura está em <strong>período de graça</strong>.
        </p>
        <p>
            Restam
            <strong>{{ $days }}</strong> {{ \Illuminate\Support\Str::plural('dia', $days) }}
            @if (!is_null($hours))
                e
                <strong>{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</strong>
                horas
            @endif
            — encerra em <strong>{{ $endsAt?->format('d/m/Y H:i') }}</strong>.
        </p>
    </div>
@endif
