<!-- Navbar -->
<nav class="navbar">


    <div class="navbar-container">

        <div class="sidebar-header">
            <span class="sidebar-title">
                <img src="{{ asset('images/logo/logo-v.png') }}" alt="Nimbuns" class="h-7">
            </span>
        </div>

        <button id="hamburgerButton" class="menu-button">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>


        <div class="flex items-center gap-4 user-container">

            @include('components.languageSelect')
            <!-- Botão de tema -->
            <div class="relative">
                <button id="toggleThemeBtn" class="theme-toggle-btn">
                    <div id="themeToggleThumb" class="theme-toggle-thumb">
                        <!-- Lua -->
                        <svg id="iconMoon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="theme-icon-moon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75
                                     c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752
                                     A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21
                                     12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <!-- Sol -->
                        <svg id="iconSun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="theme-icon-sun">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25
                                m-.386 6.364-1.591-1.591M12 18.75V21
                                m-4.773-4.227-1.591 1.591M5.25 12H3
                                m4.227-4.773L5.636 5.636
                                M15.75 12a3.75 3.75 0 1 1-7.5 0
                                3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </div>
                </button>
            </div>


            <!-- Botão do usuário -->
            <button id="userDropdownButton" class="flex items-center gap-1 dropdown-button">
                {{ Auth::user()->name }}
                <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="dropdownContent" class="absolute z-50 hidden dropdownContent-one">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">Perfil</a>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="relative flex items-center px-4 py-2 space-x-3 dropdown-item">
                    <!-- Ícone arrow-right-circle (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Sair</span>
                </a>


            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </div>
</nav>
