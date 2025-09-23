@props([
    'id' => 'password',
    'name' => 'password',
    'label' => 'Senha',
    'value' => '',
    'required' => false,
    'showRules' => false, // mostra regras de senha se true
])

<div x-data="{ show: false }" class="relative mb-4">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input id="{{ $id }}" name="{{ $name }}" placeholder="{{ $label }}"
        :type="show ? 'text' : 'password'" value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}
        class="form-input @error($name) border-red-600 focus:ring-red-500 @enderror pr-10">

    <button type="button" @click="show = !show"
        class="absolute inset-y-0 right-0 flex items-center px-2 mt-6 text-gray-500 hover:text-gray-700">
        <!-- Eye open -->
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
            <path fill-rule="evenodd"
                d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                clip-rule="evenodd" />
        </svg>
        <!-- Eye slash -->
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38 1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194L3.28 2.22ZM7.752 6.69l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092a4 4 0 0 0-5.557-5.557Z" />
            <path
                d="m10.748 13.93 2.523 2.523a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678Z" />
        </svg>
    </button>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror



</div>
@if ($showRules)
    @include('components.password-rules')
@endif

@push('scripts')
    @vite('resources/js/password-rules.js')
@endpush
