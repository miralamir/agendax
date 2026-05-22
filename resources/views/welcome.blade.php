<x-app-layout>
    {{-- La Home no tiene un tema de color específico, se mantiene neutral. --}}

    <div class="container mx-auto px-4">
        <!-- Sección Hero -->
        <main class="text-center py-16 md:py-24">
            <h2 class="text-4xl md:text-6xl font-black mb-4">
                Descubrí el <span style="background: linear-gradient(to right, #a855f7, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Arte que te Rodea</span> con BAMARTE
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Tu guía completa para inauguraciones, muestras actuales, noticias y todos los eventos culturales en tu ciudad.
            </p>
        </main>

        <!-- Sección Mapa Cultural Interactivo (estructura estática) -->
        <section id="mapa" class="py-16 bg-white rounded-2xl shadow-lg mb-16 relative z-0">
            <div class="text-center mb-6 px-4">
                <h3 class="text-3xl font-bold">Mapa Cultural Interactivo</h3>
                <p class="text-gray-500 mt-2 mb-6">Encuentra la ubicación de cada evento y descubre qué hay cerca de ti.</p>
                
                <div class="flex flex-col md:flex-row justify-center items-center gap-4 max-w-4xl mx-auto">
                    <button id="btn-locate" class="w-full md:w-auto inline-flex justify-center items-center space-x-2 px-6 py-3 bg-purple-100 text-purple-700 font-bold rounded-full hover:bg-purple-200 transition-colors shadow-sm text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Mi Ubicación</span>
                    </button>

                    <div class="relative w-full md:w-[450px]">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="map-search-input" placeholder="Buscar artista, lugar u obra..." class="w-full pl-12 pr-24 py-3 rounded-full border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium">
                        <button id="btn-map-search" class="absolute right-2 top-1.5 px-4 py-1.5 bg-purple-600 text-white font-bold rounded-full hover:bg-purple-700 text-sm transition-colors">Buscar</button>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-2 mt-6" id="map-filters">
                    <button data-category="todos" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-purple-600 text-white border-purple-600 transition-all">Todos</button>
                    <button data-category="arte" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-cyan-600 border-cyan-200 transition-all">Artes Visuales</button>
                    <button data-category="musica" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-orange-600 border-orange-200 transition-all">Música</button>
                    <button data-category="teatro" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-pink-600 border-pink-200 transition-all">Teatro</button>
                    <button data-category="cine" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-blue-600 border-blue-200 transition-all">Cine</button>
                    <button data-category="literatura" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-emerald-600 border-emerald-200 transition-all">Literatura</button>
                </div>
            </div>
            
            <div id="map" class="h-[500px] rounded-lg mx-4 md:mx-8 z-0 border border-gray-100 shadow-inner">
                <p class="p-8 text-center text-gray-500">El mapa interactivo cargará aquí.</p>
            </div>
        </section>

        <!-- Sección Agenda de Destacados (estructura estática) -->
        <section id="arte" class="py-16">
            <h3 class="text-3xl font-bold text-center mb-12">Agenda de Destacados</h3>
            <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="col-span-full flex justify-center items-center h-40">
                    <p class="text-center text-gray-500">Los eventos destacados cargarán aquí.</p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
