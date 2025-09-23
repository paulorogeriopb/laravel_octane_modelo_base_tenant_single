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

                <x-list-button base-route="cursos" />

            </div>
        </div>

        <x-alert />

        @include('cursos._form', [
            'route' => route('cursos.store'),
            'method' => 'POST',
        ])

    </div>
@endsection
