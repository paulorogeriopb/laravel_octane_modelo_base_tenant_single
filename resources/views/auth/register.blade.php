@extends('layouts.guest')

@section('content')
    <div class="card-login">
        @include('components.application-logo')

        <h1 class="title-login">Novo Usuário</h1>

        <form method="POST" action="{{ route('register') }}" class="mt-4">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name" class="form-input" />
                @error('name')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mt-4 form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" class="form-input" />
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>


            <div class="mt-4 form-group">
                <x-password-input id="password" name="password" label="Nova Senha" />

                <x-password-input id="password_confirmation" name="password_confirmation" label="Confirmar Senha" required
                    showRules="true" validateTarget="password" />

            </div>

            <div class="flex items-center justify-end mt-4 ">
                <a class="link-default" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="btn-default-md ms-4">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
@endsection
