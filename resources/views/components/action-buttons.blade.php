@props(['entity', 'baseRoute'])

@php
    $id = $entity->id;
@endphp

<div class="relative">

    {{-- Desktop --}}
    <div class="hidden gap-2 lg:flex">
        @can($baseRoute . '-update')
            <x-edit-button :route="$baseRoute . '.edit'" :id="$id" />
        @endcan



        @can($baseRoute . '-destroy')
            <x-delete-button :route="$baseRoute . '.destroy'" :id="$id" />
        @endcan
    </div>

    {{-- Mobile --}}
    <div class="relative inline-block lg:hidden">
        <button type="button" class="p-2 rounded-full btn-light" onclick="toggleActionMenu({{ $id }}, this)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path
                    d="M10 3a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM10 8.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM11.5 15.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
            </svg>
        </button>
    </div>

    @push('scripts')
        <script>
            let openActionMenu = null;

            function toggleActionMenu(id, button) {
                // Remove dropdown existente
                if (openActionMenu) {
                    openActionMenu.remove();
                    openActionMenu = null;
                }

                // Cria dropdown
                const menu = document.createElement('ul');
                menu.id = 'action-menu-' + id;
                menu.className = 'btn-action-mobile ';
                menu.style.padding = '0.25rem 0';

                // Conteúdo do dropdown (mesmo do Blade)
                menu.innerHTML = `
                @can($baseRoute . '-update')
                <li>
                    <x-edit-button :route="$baseRoute . '.edit'" :id="$id" classes="w-full px-4 py-2 text-left flex items-center gap-2 btn-light" />
                </li>
                @endcan
                @can($baseRoute . '-destroy')
                <li>
                    <x-delete-button :route="$baseRoute . '.destroy'" :id="$id" classes="w-full px-4 py-2 text-left flex items-center gap-2 btn-light form-delete" />
                </li>
                @endcan
            `;

                document.body.appendChild(menu);
                openActionMenu = menu;

                // Posiciona ao lado do botão (ajusta se não couber)
                const rect = button.getBoundingClientRect();
                const spaceRight = window.innerWidth - (rect.right + 8);
                const spaceLeft = rect.left - 8;

                if (spaceRight < menu.offsetWidth && spaceLeft >= menu.offsetWidth) {
                    // Abre à esquerda
                    menu.style.left = `${window.scrollX + rect.left - menu.offsetWidth - 8}px`;
                } else {
                    // Abre à direita
                    menu.style.left = `${window.scrollX + rect.right + 8}px`;
                }
                menu.style.top = `${window.scrollY + rect.top}px`;

            }

            // Fecha dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (openActionMenu && !openActionMenu.contains(e.target) && !e.target.closest('button')) {
                    openActionMenu.remove();
                    openActionMenu = null;
                }
            });

            // Fecha dropdown ao redimensionar
            window.addEventListener('resize', function() {
                if (openActionMenu) {
                    openActionMenu.remove();
                    openActionMenu = null;
                }
            });
        </script>
    @endpush
</div>
