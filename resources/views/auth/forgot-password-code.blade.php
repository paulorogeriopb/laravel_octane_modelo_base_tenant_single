@extends('layouts.guest')

@section('content')
    <div class="card-login">

        @include('components.application-logo')

        <x-alert />


        <h1 class="title-login">Recuperar Senha</h1>

        <div class="mt-4 mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>


        <form action="{{ route('password.code.send') }}" method="POST">
            @csrf

            <div class="form-group-login">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" placeholder="{{ __('Email') }}"
                    value="{{ old('email') }}" required autofocus autocomplete="username" class="form-input">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex items-center justify-center mt-4">
                <button type="submit" class="w-full py-2 mt-4rounded btn-default-md">
                    {{ __('Email Password Reset Link') }}</button>
            </div>

        </form>
    </div>
@endsection
