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
                <x-create-button base-route="users" />
            </div>
        </div>

        <x-search-form base-route="users" placeholder="Buscar Usuário..." />

        <x-alert />

        @if ($users->isNotEmpty())
            <!-- Tabela -->
            <div class="mt-6 overflow-x-auto rounded-md table-container">
                <table class="table min-w-full">
                    <thead>
                        <tr class="table-row-header">

                            <th class="table-header">Nome</th>
                            <th class="hidden table-header lg:table-cell">E-mail</th>
                            @can('user-status-edit')
                                <th class="hidden table-header sm:table-cell">Status</th>
                            @endcan
                            <th class="table-header center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="table-zebra-light">
                        @forelse ($users as $d)
                            <tr class="table-row-body">

                                <td class="table-body">

                                    @can('user-show')
                                        <a href="{{ route('users.show', ['user' => $d->id]) }}" class="table-link">
                                            <span>{{ $d->name }}</span>
                                        </a>
                                    @else
                                        <span>{{ $d->name }}</span>
                                    @endcan

                                </td>
                                <td class="hidden table-body lg:table-cell">

                                    @can('user-show')
                                        <a href="{{ route('users.show', ['user' => $d->id]) }}" class="table-link">
                                            <span>{{ $d->email }}</span>
                                        </a>
                                    @else
                                        <span>{{ $d->email }}</span>
                                    @endcan

                                </td>

                                @can('user-status-edit')
                                    <td class="hidden table-body sm:table-cell">
                                        <form action="{{ route('users.updateStatus', $d) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <select name="user_status_id"
                                                onchange="this.className = this.options[this.selectedIndex].dataset.class; this.form.submit();"
                                                class="form-input-md {{ $d->userStatus->color_class }}">

                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        data-class="{{ $status->color_class }}"
                                                        class="{{ $status->color_class }}"
                                                        {{ $d->user_status_id == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>

                                    </td>
                                @endcan
                                <td class="table-actions">
                                    <div class="table-actions-align">
                                        <x-action-buttons :entity="$d" base-route="users" />
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
            {{ $users->links() }}
        </div>
    </div>



@endsection


@push('scripts')
    @vite('resources/js/alert-delete.js')
    <script>
        document.querySelectorAll('.select-status').forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const form = this.closest('form');
                const url = form.action;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const formData = new FormData(form);

                fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erro na atualização');
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message); // Ou implemente uma notificação melhor
                    })
                    .catch(() => alert('Erro ao atualizar o status do usuário.'));
            });
        });
    </script>
@endpush
