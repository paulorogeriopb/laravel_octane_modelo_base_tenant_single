@extends('layouts.guest')

@section('content')
    <div class="card-login">

        @include('components.application-logo')

        <x-alert />

        <h1 class="title-login">Área Restrita</h1>

        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf
            <div class="form-group-login">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" placeholder="{{ __('Email') }}"
                    value="{{ old('email') }}" required autofocus autocomplete="username" class="form-input">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative form-group-login">
                <label for="password" class="form-label">Password</label>

                <input id="password" type="password" name="password" placeholder="Password" class="pr-10 form-input"
                    required autocomplete="current-password">

                <!-- Botão toggle -->
                <button type="button" onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 flex items-center px-3 mt-6 text-gray-500 hover:text-gray-700">

                    <!-- Eye -->
                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                        class="w-5 h-5">
                        <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0
                                               7.893 2.66 9.336 6.41.147.381.146.804 0
                                               1.186A10.004 10.004 0 0 1 10 17c-4.257
                                               0-7.893-2.66-9.336-6.41ZM14 10a4 4
                                               0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />
                    </svg>

                    <!-- Eye-slash -->
                    <svg id="eye-slash" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                        class="hidden w-5 h-5">
                        <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 0 0-1.06
                                               1.06l14.5 14.5a.75.75 0 1 0
                                               1.06-1.06l-1.745-1.745a10.029
                                               10.029 0 0 0 3.3-4.38 1.651
                                               1.651 0 0 0 0-1.185A10.004
                                               10.004 0 0 0 9.999 3a9.956
                                               9.956 0 0 0-4.744 1.194L3.28
                                               2.22ZM7.752 6.69l1.092 1.092a2.5
                                               2.5 0 0 1 3.374 3.373l1.091
                                               1.092a4 4 0 0 0-5.557-5.557Z" clip-rule="evenodd" />
                        <path d="m10.748 13.93 2.523 2.523a9.987
                                               9.987 0 0 1-3.27.547c-4.258
                                               0-7.894-2.66-9.337-6.41a1.651
                                               1.651 0 0 1 0-1.186A10.007
                                               10.007 0 0 1 2.839 6.02L6.07
                                               9.252a4 4 0 0 0 4.678 4.678Z" />
                    </svg>
                </button>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="border-gray-300 rounded shadow-sm ">
                    <span class="text-gray-700 dark:text-gray-400 ms-2">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mt-4 btn-group-login">
                <a href="{{ url('forgot-password-code') }}" class="link-default">Recuperar senha</a>
                <button type="submit" class="btn-default-md">{{ __('Log in') }}</button>
            </div>

            <div class="mt-4 text-center">
                <a class="link-default" href="{{ route('register') }}">{{ __('create new account') }}</a>
            </div>
        </form>
    </div>
@endsection
