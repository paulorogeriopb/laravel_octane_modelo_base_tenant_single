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
            <h3 class="content-box-title">{{ __('mensagens.list') }}</h3>
            <div class="content-box-btn">
                <x-create-button base-route="permissions" />
            </div>
        </div>

        <x-search-form base-route="permissions" placeholder="Buscar Permissões..." />


        <x-alert />


        @if ($permissions->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 table-container">
                <table class="table">
                    <thead>
                        <tr class="table-row-header">
                            <th class="table-header">ID</th>
                            <th class="table-header">Titulo</th>
                            <th class="table-header">Nome</th>
                            <th class="table-header center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @foreach ($permissions as $d)
                            <tr class="table-row-body">
                                <td class="table-body">{{ $d->id }}</td>
                                <td class="table-body">{{ $d->title }}</td>
                                <td class="table-body">{{ $d->name }}</td>
                                <td class="table-actions">
                                    <div class="table-actions-align">
                                        <x-action-buttons :entity="$d" base-route="permissions" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Mensagem de Nenhum Registro Encontrado -->
            <x-empty-message />
        @endif

        <div class="mt-4">
            {{ $permissions->links() }}
        </div>
    </div>



@endsection
