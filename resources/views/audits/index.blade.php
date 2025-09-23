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
            <h3 class="content-box-title">{{ __('mensagens.system_audit') }}</h3>
            <div class="content-box-btn">

            </div>
        </div>

        <form method="GET" action="{{ route('audits.index') }}" class="flex flex-wrap items-end w-full gap-2 md:flex-nowrap">
            <input type="text" name="event" class="w-full h-10 px-4 md:flex-grow form-input" placeholder="Evento"
                value="{{ request('event') }}" />
            <input type="date" name="date_from" class="w-full h-10 px-4 md:w-44 form-input"
                value="{{ request('date_from') }}" />

            <input type="date" name="date_to" class="w-full h-10 px-4 md:w-44 form-input"
                value="{{ request('date_to') }}" />

            <div class="flex w-full gap-2 md:w-auto">
                <button type="submit" class="flex items-center justify-center h-10 gap-1 px-4 mt-1 btn-default">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Filtrar</span>
                </button>

                <a href="{{ url()->current() }}" class="flex items-center justify-center h-10 gap-1 px-4 mt-1 btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Limpar</span>
                </a>
            </div>
        </form>





        @if ($audits->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 table-container">
                <table class="table">
                    <thead>
                        <tr class="table-row-header">
                            <th class="table-header">ID</th>
                            <th class="table-header">Evento</th>
                            <th class="table-header">Usuário</th>
                            <th class="table-header">Entidade</th>
                            <th class="table-header">Alterações</th>
                            <th class="table-header">Data</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @foreach ($audits as $audit)
                            <tr class="table-row-body">
                                <td class="table-body">{{ $audit->id }}</td>
                                <td class="table-body">{{ $audit->event }}</td>
                                <td class="table-body">{{ $audit->user?->name ?? 'Sistema' }}</td>
                                <td class="table-body">
                                    {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}
                                </td>
                                <td class="table-body">
                                    <details>
                                        <summary class="link-padrao">Ver alterações</summary>
                                        <div class="mt-1">
                                            <strong>Antes:</strong>
                                            <pre class="whitespace-pre-wrap">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            <strong>Depois:</strong>
                                            <pre class="whitespace-pre-wrap">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </details>
                                </td>
                                <td class="table-body">{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Mensagem de Nenhum Registro Encontrado -->
            <div class="flex flex-col items-center justify-center py-20 text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.75 9.75h.008v.008H9.75v-.008ZM14.25 9.75h.008v.008h-.008v-.008ZM12 15.75a6.75 6.75 0 1 0 0-13.5 6.75 6.75 0 0 0 0 13.5Z" />
                </svg>
                <p class="text-lg font-medium">Nenhum registro encontrado.</p>
                <p class="text-sm text-gray-400">Tente ajustar os filtros de pesquisa ou aguarde por novos registros.</p>
            </div>
        @endif

        <div class="mt-4">
            {{ $audits->withQueryString()->links() }}
        </div>

    @endsection
