@props([
    'id' => 'password',
    'name' => 'password',
    'label' => 'Senha',
    'value' => '',
    'required' => false,
    'showRules' => false,
    'validateTarget' => null, // id do input que vai disparar a validação
    'errorBag' => 'default', // Bag de erros customizado
])

<div x-data="{ show: false }" class="relative mb-4">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>

    <input id="{{ $id }}" name="{{ $name }}" placeholder="{{ $label }}"
        :type="show ? 'text' : 'password'" value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}
        class="form-input @error($name, $errorBag) border-red-600 focus:ring-red-500 @enderror pr-10">

    <!-- Botão toggle show/hide -->
    <button type="button" @click="show = !show"
        class="absolute inset-y-0 right-0 flex items-center px-2 mt-6 text-gray-500 hover:text-gray-700">
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
            <path fill-rule="evenodd"
                d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                clip-rule="evenodd" />
        </svg>
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38 1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194L3.28 2.22ZM7.752 6.69l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092a4 4 0 0 0-5.557-5.557Z" />
            <path
                d="m10.748 13.93 2.523 2.523a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678Z" />
        </svg>
    </button>

    @php
        $errorsForInput = $errors->getBag($errorBag)->get($name);
    @endphp

    @if ($errorsForInput)
        <p class="mt-1 text-sm text-red-600">{{ $errorsForInput[0] }}</p>
    @endif
</div>

@if ($showRules)
    <div data-password-rules="true"
        @if ($validateTarget) data-validate-target="{{ $validateTarget }}" @endif>
        @include('components.password-rules')
    </div>
@endif

@pushOnce('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('[data-password-rules]').forEach(rulesEl => {
                const targetId = rulesEl.dataset.validateTarget;
                if (!targetId) return;
                const targetInput = document.getElementById(targetId);
                if (!targetInput) return;

                targetInput.addEventListener("input", function() {
                    const val = this.value;
                    const rules = {
                        "rule-uppercase": /[A-Z]/.test(val),
                        "rule-lowercase": /[a-z]/.test(val),
                        "rule-number": /[0-9]/.test(val),
                        "rule-symbol": /[^A-Za-z0-9]/.test(val),
                        "rule-length": val.length >= 8,
                    };

                    for (const [id, condition] of Object.entries(rules)) {
                        const el = document.getElementById(id);
                        if (!el) continue;
                        el.classList.toggle("text-green-600", condition);
                        el.classList.toggle("text-red-600", !condition);
                        const icon = el.querySelector(".icon");
                        if (icon) icon.innerHTML = condition ? `
                    <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>` : `
                    <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>`;
                    }
                });
            });
        });
    </script>
@endPushOnce
