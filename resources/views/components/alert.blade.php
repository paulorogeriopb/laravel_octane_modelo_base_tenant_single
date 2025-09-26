@props(['type' => null, 'message' => null])

@php
    if (!$message) {
        $sessionMessages = [
            'success' => session()->pull('success'),
            'error' => session()->pull('error'),
            'warning' => session()->pull('warning'),
            'info' => session()->pull('info'),
            'status' => session()->pull('status'),
        ];

        if (!$sessionMessages['error'] && $errors->any()) {
            $sessionMessages['error'] = collect($errors->all())->first();
        }

        foreach ($sessionMessages as $key => $msg) {
            if ($msg) {
                $type = $key;
                $message = $msg;
                break;
            }
        }
    }

    // Ícones padrão do SweetAlert2
    $alertIcons = [
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
        'status' => 'info',
    ];
    $icon = $alertIcons[$type] ?? 'info';

    // Títulos em português
    $alertTitles = [
        'success' => 'Sucesso!',
        'error' => 'Erro!',
        'warning' => 'Aviso!',
        'info' => 'Informação',
        'status' => 'Status',
    ];
    $title = $alertTitles[$type] ?? 'Mensagem';
@endphp

@if ($message)
    <script>
        ['DOMContentLoaded', 'pageshow'].forEach(ev =>
            window.addEventListener(ev, function() {
                Swal.fire({
                    title: @json($title),
                    text: @json($message),
                    icon: @json($icon),
                    confirmButtonText: "OK",
                    confirmButtonColor: "#32a2b9",
                    background: document.documentElement.classList.contains("dark") ? "#1f2937" : "#fff",
                    color: document.documentElement.classList.contains("dark") ? "#f9fafb" : "#111827",
                });
            })
        );
    </script>
@endif
