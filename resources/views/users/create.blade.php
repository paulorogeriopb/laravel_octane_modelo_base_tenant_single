@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>
    </div>

    <div class="content-box">
        <div class="content-box-header">
            <h3 class="content-box-title">{{ __('mensagens.create_new') }}</h3>
            <div class="content-box-btn">
                <x-list-button base-route="users" />
            </div>
        </div>

        <x-alert />

        @include('users._form', [
            'action' => route('users.store'),
            'method' => 'POST',
            'user' => null,
            'roles' => $roles,
            'userRoles' => [],
            'buttonText' => __('mensagens.save'),
        ])
    </div>
@endsection
