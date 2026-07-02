<nav class="bg-white border-b border-gray-200" x-data="{ menuAbierto: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 w-full items-center">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="/" class="text-2xl font-black tracking-tight" style="font-family: 'Lato', sans-serif; font-weight: 900;">BAM<span style="color: var(--gray-600);">ARTE</span></a>
            </div>

            <!-- Navigation Links (Centered) - solo desktop -->
            <div class="hidden lg:flex lg:items-center lg:justify-center flex-1">
                <div class="space-x-8">
                    <a href="/arte" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Artes Visuales</a>
                    <a href="/musica" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Música</a>
                    <a href="/teatro" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Teatro</a>
                    <a href="/cine" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Cine</a>
                    <a href="/literatura" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Literatura</a>
                </div>
            </div>

            <!-- Search and Auth Buttons - solo desktop -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <div class="relative" x-data="{ buscando: false }" @click.outside="buscando = false">
                    <button type="button" x-show="!buscando"
                            @click="buscando = true; $nextTick(() => $refs.buscarInput.focus())"
                            class="p-2 rounded-full text-gray-600 hover:text-gray-800">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <form action="{{ route('buscar') }}" method="GET" x-show="buscando" x-cloak class="flex items-center">
                        <input x-ref="buscarInput" type="text" name="q" placeholder="Buscar..."
                               class="border-b-2 border-gray-300 focus:outline-none focus:border-gray-800 text-sm px-1 py-1 w-32 sm:w-44">
                    </form>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="ml-4 px-4 py-2 border border-black text-black text-sm font-bold rounded-md hover:bg-gray-100 transition">Ingresar</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-black text-white text-sm font-bold rounded-md hover:bg-gray-800 transition">Registro</a>
                    @endif
                @else
                    <div class="ms-3 relative">
                         @if(file_exists(resource_path('views/components/dropdown.blade.php')))
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('mi-agenda')">Mi Agenda</x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endif
                    </div>
                @endguest
            </div>

            <!-- Botón hamburguesa - celular + tablet -->
            <div class="flex items-center lg:hidden">
                <button @click="menuAbierto = !menuAbierto" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': menuAbierto, 'inline-flex': !menuAbierto }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !menuAbierto, 'inline-flex': menuAbierto }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Panel desplegable - celular + tablet -->
    <div x-show="menuAbierto" x-cloak class="lg:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="/arte" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Artes Visuales</a>
            <a href="/musica" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Música</a>
            <a href="/teatro" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Teatro</a>
            <a href="/cine" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Cine</a>
            <a href="/literatura" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Literatura</a>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 space-y-1">
            @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-900 hover:bg-gray-100">Ingresar</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-bold text-white bg-black hover:bg-gray-800 text-center">Registro</a>
                @endif
            @else
                <div class="px-3 py-1 text-sm text-gray-400">{{ Auth::user()->name }}</div>
                <a href="{{ route('mi-agenda') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Mi Agenda</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Cerrar sesión</a>
                </form>
            @endguest
        </div>
    </div>
</nav>
