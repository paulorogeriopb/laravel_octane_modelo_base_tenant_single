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

                <x-create-button base-route="translations" />
            </div>
        </div>


        <x-search-form base-route="translations" placeholder="Buscar Tradução..." />


        <x-alert />

        @if ($translations->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 overflow-x-auto table-container">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class=" table-row-header">
                            <th class="p-3 text-left border-b table-header">ID</th>
                            <th class="p-3 text-left border-b table-header">Chave</th>
                            <th class="p-3 text-left border-b table-header">Grupo</th>
                            <th class="p-3 text-left border-b table-header">PT</th>
                            <th class="p-3 text-left border-b table-header">EN</th>
                            <th class="p-3 text-left border-b table-header">ES</th>
                            <th class="p-3 text-center border-b table-header">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @foreach ($translations as $d)
                            <tr class="table-row-body ">
                                <td class="p-3 border-b table-body">{{ $d->id }}</td>
                                <td class="p-3 border-b table-body">{{ $d->key }}</td>
                                <td class="p-3 border-b table-body">{{ $d->group ?? '-' }}</td>
                                <td class="p-3 border-b table-body">{{ $d->getTranslation('text', 'pt') }}</td>
                                <td class="p-3 border-b table-body">{{ $d->getTranslation('text', 'en') }}</td>
                                <td class="p-3 border-b table-body">{{ $d->getTranslation('text', 'es') }}</td>
                                <td class="p-3 text-center border-b table-actions">
                                    <div class="inline-flex justify-center space-x-2 table-actions-align">
                                        <div class="table-actions-align">
                                            <x-action-buttons :entity="$d" base-route="translations" />
                                        </div>
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
            {{ $translations->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/alert-delete.js')
@endpush
