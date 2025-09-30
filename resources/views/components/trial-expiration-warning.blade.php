@if ($daysLeft !== null)
    <div class="p-4 mt-4 mb-4 shadow alert-warning ">
        <p>
            ⚠️ Sua assinatura foi cancelada e está em <strong>período de graça</strong>.
        </p>
        <p>
            Restam <strong>{{ $daysLeft }}</strong> {{ Str::plural('dia', $daysLeft) }}
            de acesso, até <strong>{{ $endsAt->format('d/m/Y') }}</strong>.
        </p>
    </div>
@endif
