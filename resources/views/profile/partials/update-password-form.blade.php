<section>
    <header>
        <h2 class="content-title">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 form-label">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <div>
                <x-password-input id="update_password_current_password" name="current_password" label="Senha Atual"
                    required="true" errorBag="updatePassword" />
            </div>
        </div>

        <div>
            <x-password-input id="password" name="password" label="Nova Senha" />

            <x-password-input id="password_confirmation" name="password_confirmation" label="Confirmar Senha" required
                showRules="true" validateTarget="password" />




        </div>



        <div class="flex items-center gap-4">
            <x-save-button />

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="mt-6 text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
