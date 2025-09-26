@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>

        <div class="content-box">
            <div class="content-box-header">
                <h3 class="content-box-title">{{ __('mensagens.edit_password') }}</h3>
                <div class="content-box-btn">
                    <x-list-button base-route="users" />

                    @can('user-show')
                        <a href="{{ route('users.show', ['user' => $user->id]) }}" class="btn-default align-icon-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span>Visualizar</span>
                        </a>
                    @endcan
                </div>
            </div>

            <br>

            {{-- Mostra mensagens flash (success, error, info) --}}
            <x-alert />

            {{-- Formulário com toggle de senha e password-rules --}}
            <form action="{{ route('users.update_password', ['user' => $user->id]) }}" method="POST" class="space-y-6"
                x-data="{ showPassword: false, showConfirm: false }">
                @csrf
                @method('PUT')

                <x-password-input id="password" name="password" label="Nova Senha" />

                <x-password-input id="password_confirmation" name="password_confirmation" label="Confirmar Senha" required
                    showRules="true" validateTarget="password" />


                <x-save-button />

            </form>
        </div>
    </div>
@endsection
