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
            <h3 class="content-box-title">{{ __('mensagens.edit') }}</h3>
            <div class="content-box-btn">
                <x-list-button base-route="roles" />
            </div>
        </div>

        <x-alert />

        @include('roles._form', [
            'route' => route('roles.update', ['role' => $role->id]),
            'method' => 'PUT',
            'data' => $role,
        ])
    </div>
@endsection
