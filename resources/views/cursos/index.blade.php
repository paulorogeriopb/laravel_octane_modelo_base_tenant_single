@extends('layouts.app')

@section('content')

    <div class="content-box">
        <div class="content-box-header">
            <h3 class="content-box-title">{{ __('mensagens.list') }}</h3>
            <div class="content-box-btn">
                <x-create-button base-route="cursos" />
            </div>
        </div>

        <x-search-form base-route="cursos" placeholder="Buscar Curso..." />

        <x-alert />

        @if ($data->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 table-container">
                <table class="table">
                    <thead>
                        <tr class="table-row-header">
                            <th class="table-header">ID</th>
                            <th class="table-header">Arquivo</th>
                            <th class="table-header">Nome</th>
                            <th class="table-header center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @foreach ($data as $d)
                            <tr class="table-row-body">
                                <td class="table-body">{{ $d->id }}</td>
                                <td
                                    class="inline-block max-w-sm p-1 border border-gray-300 rounded-full dark:border-gray-600 table-body">
                                    <img src="{{ asset('storage/uploads/' . $d->image ?? 'images/defaults/photo.png') }}"
                                        alt="{{ $d->name }}"
                                        class="object-contain w-10 h-10 bg-gray-100 rounded-full" />
                                </td>
                                <td class="table-body">{{ $d->name }}</td>
                                <td class="table-actions">
                                    <div class="table-actions-align">
                                        <x-action-buttons :entity="$d" base-route="cursos" />
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
            {{ $data->links() }}
        </div>
    </div>

@endsection
