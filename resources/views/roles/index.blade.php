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
                <x-create-button base-route="roles" />
            </div>
        </div>

        <x-search-form base-route="roles" placeholder="Buscar Papel..." />

        <x-alert />

        @if ($roles->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 table-container">
                <table class="table">
                    <thead>
                        <tr class="table-row-header">
                            <th class="table-header">ID</th>
                            <th class="table-header">Nome</th>

                            <th class="table-header center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @foreach ($roles as $d)
                            <tr class="table-row-body">
                                <td class="table-body">{{ $d->id }}</td>
                                <td class="table-body">{{ $d->name }}</td>
                                <td class="table-actions">
                                    <div class="table-actions-align">

                                        @can('role-permission-index')
                                            <a href="{{ route('role-permissions.index', ['role' => $d->id]) }}"
                                                class="items-center space-x-1 btn-default md:flex">
                                                <!-- Ícone Edit (Heroicons) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m-1.5 0h12a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-12a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                                                </svg>
                                                <span>Permissões</span>
                                            </a>
                                        @endcan

                                        <x-action-buttons :entity="$d" base-route="roles" />


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
            {{ $roles->links() }}
        </div>
    </div>

@endsection
