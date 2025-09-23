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
                <x-list-button base-route="permissions" />
            </div>
        </div>

        <x-alert />


        @include('permissions._form', [
            'route' => route('permissions.update', ['permission' => $permission->id]),
            'method' => 'PUT',
            'data' => $permission,
        ])
    </div>
@endsection
