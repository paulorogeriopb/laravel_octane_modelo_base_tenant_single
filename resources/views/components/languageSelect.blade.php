<form action="{{ route('change-language') }}" method="POST" x-data="languageSelect('{{ app()->getLocale() }}')" @change.prevent="submitForm">
    @csrf

    <div class="relative flex justify-center" @mouseenter="open = true" @mouseleave="open = false">

        <!-- Botão: só a bandeira -->
        <button type="button" @click="open = !open"
            class="flex items-center justify-center w-8 h-8 overflow-hidden transition rounded-full cursor-pointer focus:outline-none ring-1 ring-gray-300 hover:ring-gray-300 dark:ring-gray-600 dark:hover:ring-gray-500">
            <img :src="selected.flag" alt="lang"
                class="object-cover w-6 h-6 transition duration-200 rounded-full" />
        </button>

        <!-- Dropdown animado -->
        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-75 -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-75 -translate-y-2"
            class="absolute z-50 mt-2 transform -translate-x-1/2 bg-white border border-gray-200 rounded-md shadow-lg left-1/2 top-full w-44 dark:bg-gray-700 dark:border-gray-600"
            style="display: none;">

            <template x-for="lang in languages" :key="lang.code">
                <label
                    class="flex items-center px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                    :class="{ 'bg-gray-100 dark:bg-gray-600': lang.code === selected.code }">

                    <input type="radio" name="locale" :value="lang.code" class="hidden"
                        x-model="selected.code" />

                    <img :src="lang.flag" alt="" class="w-4 h-4 mr-2 rounded-full" />
                    <span x-text="lang.name" class="text-gray-800 truncate dark:text-gray-100"></span>
                </label>
            </template>
        </div>
    </div>
</form>
