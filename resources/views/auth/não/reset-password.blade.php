@extends('layouts.guest')

@section('content')
    <div class="card-login">
        <div class="logo-wrapper-login">
            <a href="{{ url('/') }}">

                <h1 class="title-logo">Nimbuns</h1>
            </a>
        </div>

        <form method="POST" action="{{ route('password.store') }} " class="mt-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mt-4 form-group">
                <label for="email" class="form-label">
                    {{ __('Email') }}
                </label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                    autocomplete="username" class="form-input" />
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>



            <!-- Password -->
            <div class="mt-4 form-group">
                <label for="password" class="form-label">
                    {{ __('Password') }}
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="form-input" />
                @error('password')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-4 form-group">
                <label for="password_confirmation" class="form-label">
                    {{ __('Confirm Password') }}
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" class="form-input" />
                @error('password_confirmation')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>


            <div class="flex items-center justify-end mt-4 ">
                <button type="submit" class="btn-primary-md">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
@endsection
