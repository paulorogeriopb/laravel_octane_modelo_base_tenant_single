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
            <h3 class="content-box-title">{{ __('mensagens.list') }} - Papel: {{ $role->name }}</h3>
            <div class="content-box-btn">
                @can('role-index')
                    <a href="{{ route('roles.index') }}" class="flex items-center space-x-1 btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        <span>Listar</span>
                    </a>
                @endcan

                @can('permission-create')
                    <a href="{{ route('permissions.create') }}" class="flex items-center space-x-1 btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Cadastrar</span>
                    </a>
                @endcan
            </div>
        </div>

        <x-alert />

        <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-2">

            <!-- COLUNA 1: Permissões -->
            <div class="w-full">
                @if ($permissions->isNotEmpty())
                    <div class="overflow-x-auto rounded-md shadow-sm table-container">
                        <table class="table min-w-full">
                            <thead>
                                <tr class="table-row-header">
                                    <th class="table-header">ID</th>
                                    <th class="table-header">Nome</th>
                                    <th class="table-header">Permissão</th>
                                    <th class="table-header center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="table-zebra-light">
                                @foreach ($permissions as $d)
                                    <tr class="table-row-body">
                                        <td class="table-body">{{ $d->id }}</td>
                                        <td class="table-body">{{ $d->title }}</td>
                                        <td class="table-body">
                                            <span
                                                class="max-w-xs text-xs text-gray-500 break-all truncate">{{ $d->name }}</span>
                                        </td>
                                        <td class="table-actions">
                                            <div class="table-actions-align">
                                                <form
                                                    action="{{ route('role-permissions.update', ['role' => $role->id, 'permission' => $d->id]) }}"
                                                    method="POST" wire:x-data="{ loading: false }">
                                                    @csrf
                                                    @method('PATCH')

                                                    @if (in_array($d->id, $rolePermissions ?? []))
                                                        <button type="submit"
                                                            x-on:click.prevent="
                                                            loading = true;
                                                            setTimeout(() => { $el.closest('form').submit() }, 10);
                                                        "
                                                            :disabled="loading"
                                                            class="inline-flex items-center px-3 py-1 space-x-1 text-xs rounded btn-success no-confirm"
                                                            title="Liberar permissão">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15.75 11.25V7.5a3.75 3.75 0 00-7.5 0M4.5 11.25h15a.75.75 0 01.75.75v7.5a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-7.5a.75.75 0 01.75-.75z" />
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            x-on:click.prevent="
                                                            loading = true;
                                                            setTimeout(() => { $el.closest('form').submit() }, 10);
                                                        "
                                                            :disabled="loading"
                                                            class="inline-flex items-center px-3 py-1 space-x-1 text-xs rounded btn-danger no-confirm"
                                                            title="Bloquear permissão">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m-1.5 0h12a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-12a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                                                            </svg>

                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $permissions->links() }}</div>
                @else
                    <div class="py-20 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 9.75h.008v.008H9.75v-.008ZM14.25 9.75h.008v.008h-.008v-.008ZM12 15.75a6.75 6.75 0 1 0 0-13.5..." />
                        </svg>
                        <p class="text-lg font-medium">Nenhuma Permissão encontrada.</p>
                        <p class="text-sm text-gray-400">Tente ajustar os filtros de pesquisa ou adicione uma nova
                            Permissão.</p>
                    </div>
                @endif
            </div>

            <!-- COLUNA 2: Lista de Usuários Vinculados -->
            <div class="w-full">
                @if ($users->isNotEmpty())
                    <div class="overflow-x-auto rounded-md shadow-sm table-container">
                        <table class="table min-w-full">
                            <thead>
                                <tr class="table-row-header">
                                    <th class="table-header">ID</th>
                                    <th class="table-header">Usuário</th>
                                    <th class="table-header">E-mail</th>
                                    <th class="table-header center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="table-zebra-light">
                                @foreach ($users as $user)
                                    <tr class="table-row-body">
                                        <td class="table-body whitespace-nowrap">{{ $user->id }}</td>
                                        <td class="table-body"> {{ $user->name }} </td>
                                        <td class="table-body"> {{ $user->email }} </td>
                                        <!--  <td class="table-body table-actions whitespace-nowrap"> -->
                                        <td class="table-actions">
                                            <div class="table-actions-align">
                                                <form
                                                    action="{{ route('role-permissions.toggleUser', ['role' => $role->id, 'user' => $user->id]) }}"
                                                    method="POST" x-data="{ loading: false }">
                                                    @csrf
                                                    @method('PATCH')

                                                    @if ($user->hasRole($role))
                                                        <button type="submit"
                                                            x-on:click.prevent="
                                                            loading = true;
                                                            setTimeout(() => { $el.closest('form').submit() }, 10);
                                                        "
                                                            :disabled="loading"
                                                            class="inline-flex items-center px-3 py-1 space-x-1 text-xs rounded btn-danger no-confirm"
                                                            title="Remover usuário do papel">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path
                                                                    d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                                            </svg>

                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            x-on:click.prevent="
                                                            loading = true;
                                                            setTimeout(() => { $el.closest('form').submit() }, 10);
                                                        "
                                                            :disabled="loading"
                                                            class="inline-flex items-center px-3 py-1 space-x-1 text-xs rounded btn-success no-confirm"
                                                            title="Vincular usuário ao papel">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>

                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação opcional --}}
                    {{-- <div class="mt-4">{{ $users->links() }}</div> --}}
                @else
                    <div class="py-20 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 9.75h.008v.008H9.75v-.008ZM14.25 9.75h.008v.008h-.008v-.008ZM12 15.75a6.75 6.75 0 1 0 0-13.5..." />
                        </svg>
                        <p class="text-lg font-medium">Nenhum usuário encontrado.</p>
                        <p class="text-sm text-gray-400">Tente ajustar os filtros ou adicionar usuários ao sistema.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
