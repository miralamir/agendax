{{-- resources/views/layouts/navigation-home.blade.php --}}
<header class="py-6">
    <nav class="flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-3xl font-black text-black">BAMARTE</a>
        <ul class="hidden md:flex items-center space-x-8 font-bold text-sm">
            <!-- Artes Visuales -->
            <li class="relative group z-50">
                <a href="{{ route('arte') }}" class="uppercase text-cyan-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                    Artes Visuales
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48 mt-1">
                    <li><a href="{{ route('arte.agenda') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                    <li><a href="{{ route('arte.creadores') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Creadores</a></li>
                    <li><a href="{{ route('arte.ferias') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                    <li><a href="{{ route('arte.novedades') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                </ul>
            </li>
            <!-- Música -->
            <li class="relative group z-50">
                <a href="{{ route('musica') }}" class="uppercase text-orange-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                    Música
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48 mt-1">
                    <li><a href="{{ route('musica.agenda') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                    <li><a href="{{ route('musica.lanzamientos') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Lanzamientos</a></li>
                    <li><a href="{{ route('musica.festivales') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                    <li><a href="{{ route('musica.novedades') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                </ul>
            </li>
            <!-- Teatro -->
            <li class="relative group z-50">
                <a href="{{ route('teatro') }}" class="uppercase text-pink-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                    Teatro
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48 mt-1">
                    <li><a href="{{ route('teatro.cartelera') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Cartelera</a></li>
                    <li><a href="{{ route('teatro.festivales') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                    <li><a href="{{ route('teatro.novedades') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                </ul>
            </li>
            <!-- Cine -->
            <li class="relative group z-50">
                <a href="{{ route('cine') }}" class="uppercase text-blue-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                    Cine
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48 mt-1">
                    <li><a href="{{ route('cine.estrenos') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Estrenos</a></li>
                    <li><a href="{{ route('cine.festivales-ciclos') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales / Ciclos</a></li>
                    <li><a href="{{ route('cine.novedades') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                </ul>
            </li>
            <!-- Literatura -->
            <li class="relative group z-50">
                <a href="{{ route('literatura') }}" class="uppercase text-emerald-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                    Literatura
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48 mt-1">
                    <li><a href="{{ route('literatura.agenda') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                    <li><a href="{{ route('literatura.novedades-editoriales') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades Editoriales</a></li>
                    <li><a href="{{ route('literatura.novedades') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                    <li><a href="{{ route('literatura.ferias') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                </ul>
            </li>
        </ul>
        <div class="hidden md:flex items-center space-x-4">
            <a href="{{ url('/#mapa') }}" class="flex items-center space-x-2 bg-purple-600 text-white rounded-lg px-4 py-2 hover:bg-purple-700 transition-all duration-300 text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Mapa Cultural</span>
            </a>
            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Register</a>
                @endif
            @endguest
            @auth
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">{{ __('Dashboard') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth
        </div>
    </nav>
</header>
