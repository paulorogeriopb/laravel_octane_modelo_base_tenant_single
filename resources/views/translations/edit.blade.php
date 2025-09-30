@extends('layouts.app')

@section('content')
    <div class="content-box">
        <div class="content-box-header">
            <h3 class="content-box-title">{{ __('mensagens.edit') }}</h3>
            <div class="content-box-btn">
                <x-list-button base-route="translations" />
            </div>
        </div>

        <x-alert />

        @include('translations._form', [
            'route' => route('translations.update', $data->id),
            'method' => 'PUT',
            'data' => $data,
            'buttonText' => __('mensagens.update'),
        ])
    </div>
@endsection
