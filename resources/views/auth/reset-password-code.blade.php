@extends('layouts.guest')

@section('content')
    <div class="card-login">

        @include('components.application-logo')

        <x-alert />

        <h1 class="mb-4 text-center title-login">Recuperar Senha</h1>

        <form id="reset-password-form" action="{{ route('password.code.reset') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
            <input type="hidden" name="code" id="code-hidden">

            <!-- ETAPA 1: Código -->
            <div id="step-code" class="text-center">
                <p class="mb-2 text-gray-600 dark:text-gray-400">Digite o código enviado por e-mail</p>
                <div class="flex justify-center gap-2 mb-4">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" class="btn-code code-input"
                            value="{{ $code[$i] ?? '' }}">
                    @endfor
                </div>
                <button type="button" id="verify-code-btn" class="w-full py-2 mt-4 rounded btn-default-md">
                    Verificar Código
                </button>
            </div>

            <!-- ETAPA 2: Senha -->
            <div id="step-password" class="hidden">
                <x-password-input id="password" name="password" label="Nova Senha" />

                <x-password-input id="password_confirmation" name="password_confirmation" label="Confirmar Senha" required
                    showRules="true" validateTarget="password" />


                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="w-full py-2 mt-4 rounded btn-default-md">
                        Redefinir Senha
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const codeInputs = document.querySelectorAll('.code-input');
        const stepCode = document.getElementById('step-code');
        const stepPassword = document.getElementById('step-password');
        const form = document.getElementById('reset-password-form');
        const verifyBtn = document.getElementById('verify-code-btn');
        const hiddenCode = document.getElementById('code-hidden');

        // Navegação entre inputs e validação apenas números
        codeInputs.forEach((input, index, arr) => {
            input.addEventListener('input', e => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value && index < arr.length - 1) arr[index + 1].focus();
            });

            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !input.value && index > 0) arr[index - 1].focus();
            });

            input.addEventListener('paste', e => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').replace(/\D/g, '');
                paste.split('').forEach((char, i) => {
                    if (index + i < codeInputs.length) codeInputs[index + i].value = char;
                });
                const lastIndex = Math.min(index + paste.length, codeInputs.length - 1);
                codeInputs[lastIndex].focus();
            });
        });

        // Validação do código via AJAX (primeira etapa)
        verifyBtn.addEventListener('click', async () => {
            const code = Array.from(codeInputs).map(i => i.value).join('');
            const email = form.querySelector('input[name="email"]').value;

            if (code.length !== 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Digite os 6 números do código.'
                });
                return;
            }

            try {
                const res = await fetch('{{ route('password.code.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        email,
                        code
                    })
                });

                const data = await res.json();

                if (data.success) {
                    hiddenCode.value = code; // define o campo oculto para envio
                    stepCode.classList.add('hidden');
                    stepPassword.classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro. Tente novamente.'
                });
            }
        });

        // Foco automático no primeiro input vazio
        window.addEventListener('DOMContentLoaded', () => {
            const firstEmpty = Array.from(codeInputs).find(i => !i.value);
            if (firstEmpty) firstEmpty.focus();
        });
    </script>
@endpush
