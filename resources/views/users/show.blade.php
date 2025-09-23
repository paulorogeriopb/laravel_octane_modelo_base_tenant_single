@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>
    </div>

    <div class="content-box ">
        <div class="content-box-header">
            <!-- Container principal: coluna no mobile, linha no sm+ -->
            <div
                class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0 sm:w-full">

                <!-- Avatar + Nome -->
                <div class="flex items-center space-x-4">
                    <div
                        class="flex items-center justify-center w-16 h-16 text-gray-500 bg-gray-200 rounded-full dark:text-gray-300 dark:bg-gray-600">
                        <span class="text-2xl uppercase">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                </div>

                <!-- Botões -->
                <div class="content-box-btn">
                    @can('user-edit')
                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn-warning align-icon-btn">
                            <!-- Ícone pencil-square (Heroicons) -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span>Editar Usuário</span>
                        </a>
                    @endcan
                    @can('users-edit-password')
                        <a href="{{ route('users.edit_password', $user) }}" class="btn-default align-icon-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m-1.5 0h12a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-12a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                            </svg>
                            <span>Alterar Senha</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>


        <x-alert />


        <!-- Conteúdo principal -->
        <div class="grid grid-cols-1 px-6 py-8 sm:grid-cols-2 gap-x-8 gap-y-6">
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">ID</label>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->id }}</div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">E-mail</label>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Status</label>
                <div class="mt-1">
                    @php
                        $statusName = strtolower($user->userStatus->name ?? '');
                        $statusClasses = [
                            'ativo' => 'badge-success',
                            'inativo' => 'badge-danger',
                            'aguardando confirmação' => 'badge-info',
                            'spam' => 'badge-warning',
                        ];
                        $badgeClass = $statusClasses[$statusName] ?? 'badge-primary';
                    @endphp

                    @if ($user->userStatus)
                        <span class="{{ $badgeClass }}">
                            {{ $user->userStatus->name }}
                        </span>
                    @else
                        <span class="italic text-gray-400">Sem status</span>
                    @endif
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Papel</label>
                <div class="mt-1 text-gray-900 dark:text-gray-100">
                    @forelse ($user->getRoleNames() as $index => $role)
                        {{ $role }}{{ !$loop->last ? ',' : '' }}
                    @empty
                        <span class="italic text-gray-400">Nenhum papel</span>
                    @endforelse
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Criado em</label>
                <div class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Editado em</label>
                <div class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->updated_at->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>



    </div>




    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 content-box">

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Tem certeza que deseja deletar sua conta?
            </h3>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Uma vez que sua conta for deletada, não será possível recuperar. Essa ação é irreversível.
            </p>

            <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="mt-6">
                @csrf
                @method('DELETE')

                <label for="confirmation" class="flex items-center cursor-pointer select-none">
                    <div class="relative">
                        <input id="confirmation" name="confirmation" type="checkbox" required class="sr-only peer" />
                        <div
                            class="h-6 bg-gray-300 rounded-full shadow-inner t w-11 peer-checked:bg-red-600 dark:bg-gray-700 dark:peer-checked:bg-red-500">
                        </div>
                        <div
                            class="absolute w-4 h-4 bg-white rounded-full shadow-md left-1 top-1 peer-checked:translate-x-5">
                        </div>
                    </div>
                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                        Confirmo a desativação da minha conta
                    </span>
                </label>


                <div class="flex justify-end mt-6">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 ml-3 text-xs font-semibold tracking-widest text-white uppercase bg-red-600 border border-transparent rounded-md pace-x-1 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span> Deletar Conta</span>
                    </button>

                </div>
            </form>

        </div>
    </div>
@endsection
