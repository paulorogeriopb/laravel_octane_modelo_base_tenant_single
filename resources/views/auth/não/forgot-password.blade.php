@extends('layouts.guest')

@section('content')
    <!-- Session Status -->

    <x-alert />

    <div class="card-login">
        <div class="logo-wrapper-login">
            <a href="{{ url('/') }}">
                <h1 class="title-logo">Nimbuns</h1>
            </a>
        </div>


        <div class="mt-4 mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <form action="#" method="POST" class="mt-4">
            <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                @csrf
                <!-- Campo e-mail -->
                <div class="form-group-login">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Digite o e-mail cadastrado"
                        class="form-input" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-4">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md bg-cor-padrao hover:bg-cor-padrao-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cor-padrao">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>


            </form>
    </div>
@endsection
