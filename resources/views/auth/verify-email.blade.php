@extends('layouts.guest')

@section('content')
    <div class="text-center card-login">

        @include('components.application-logo')

        <x-alert />

        <h1 class="mb-4 text-center title-login">Verificação de Código</h1>
        <p class="mb-6 text-center text-gray-600 dark:text-gray-400">
            Digite o código enviado por e-mail
        </p>

        <!-- Form principal de verificação de código -->
        <form id="verify-email-form" method="POST" action="{{ route('email-verification.verify') }}">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', request('email')) }}">

            <div id="step-code" class="text-center">

                <div class="flex justify-center gap-2 mb-4">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric"
                            class="btn-code" value="{{ $code[$i] ?? '' }}">
                    @endfor
                </div>

                <button type="submit" id="verify-code-btn" class="w-full py-2 mt-4 rounded btn-default-md">
                    Verificar Código
                </button>

            </div>

            @error('code')
                <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>

        <!-- Botão Reenviar Código -->
        <form method="POST" action="{{ route('email-verification.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="py-2 cursor-pointer  link-default">
                Reenviar Código
            </button>
        </form>

        <!-- Botão Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="py-2 rounded cursor-pointer link-default">
                Sair
            </button>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        const codeInputs = document.querySelectorAll('input[name="code[]"]');

        // Navegação entre inputs e validação apenas números
        codeInputs.forEach((input, index, arr) => {
            input.addEventListener('input', e => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value && index < arr.length - 1) arr[index + 1].focus();
            });

            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !input.value && index > 0) arr[index - 1].focus();
            });

            // Permitir colar código completo
            input.addEventListener('paste', e => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').replace(/\D/g, '');
                paste.split('').forEach((char, i) => {
                    if (index + i < codeInputs.length) {
                        codeInputs[index + i].value = char;
                    }
                });
                const lastIndex = Math.min(index + paste.length, codeInputs.length - 1);
                codeInputs[lastIndex].focus();
            });
        });

        // Antes de submeter, junta os valores dos inputs em um único campo
        const form = document.getElementById('verify-email-form');
        form.addEventListener('submit', e => {
            const code = Array.from(codeInputs).map(i => i.value).join('');
            if (code.length !== 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Digite os 6 números do código.'
                });
            } else {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'code';
                hidden.value = code;
                form.appendChild(hidden);
            }
        });

        // Foco automático no primeiro input vazio
        window.addEventListener('DOMContentLoaded', () => {
            const firstEmpty = Array.from(codeInputs).find(input => !input.value);
            if (firstEmpty) firstEmpty.focus();
        });
    </script>
@endpush
