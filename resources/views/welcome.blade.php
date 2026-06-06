<x-app-layout>
    {{-- Dependencias y Estilos Específicos de la Home --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        @keyframes blobmove {
            0%, 100% { border-radius: 40% 60% 70% 30% / 40% 40% 60% 50%; }
            25% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; }
            50% { border-radius: 40% 60% 30% 70% / 60% 70% 30% 40%; }
            75% { border-radius: 30% 70% 60% 40% / 70% 40% 60% 30%; }
        }
        .blob {
            position: absolute;
            background: var(--gray-400);
            opacity: 0.06;
            animation: blobmove 20s infinite alternate;
        }
    </style>

    <main>
        <!-- 1. Hero Section -->
        <section class="relative bg-white py-24 sm:py-32 overflow-hidden">
            <div class="absolute inset-0">
                <div class="blob" style="top: -10%; left: -5%; width: 400px; height: 400px; animation-duration: 25s;"></div>
                <div class="blob" style="bottom: -15%; right: 5%; width: 500px; height: 500px; animation-duration: 30s; animation-delay: 3s;"></div>
                <div class="blob" style="top: 10%; right: -10%; width: 300px; height: 300px; animation-duration: 20s; animation-delay: 5s;"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-sm font-bold text-gray-500 tracking-[2px] uppercase">Agenda Cultural</span>
                <h1 class="mt-4 text-5xl sm:text-7xl font-black text-gray-900 leading-tight">
                    Descubrí<span class="sm:block"></span> el arte que te rodea.
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600">
                    Una agenda curada con inauguraciones, muestras y eventos culturales en la ciudad.
                </p>
            </div>
        </section>

        <!-- 2. Destacados Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Carrusel Principal (Columna Izquierda) -->
                    <div class="lg:col-span-2">
                        <div class="swiper main-carousel rounded-lg overflow-hidden relative">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                @foreach($featuredEvents->take(4) as $event) {{-- Tomamos los primeros 4 para el carrusel --}}
                                <div class="swiper-slide">
                                    <a href="{{ route('evento.show', $event->id) }}" class="block">
                                        <img src="{{ $event->mainImageUrl }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 p-8 text-white">
                                            <span class="text-sm font-bold uppercase tracking-wider" style="color: var(--color-{{ strtolower(str_replace(' ', '', $event->category ?? '')) }});">{{ $event->category ?? 'Sin categoría' }}</span>
                                            <h3 class="text-3xl font-bold mt-2">{{ $event->title }}</h3>
                                            <p class="mt-2 text-sm">{{ $event->locationName }}</p>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <!-- Navegación y Paginación -->
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next text-white"></div>
                            <div class="swiper-button-prev text-white"></div>
                        </div>
                    </div>
                    <!-- Noticias Laterales (Columna Derecha) -->
                    <div>
                        <div class="space-y-4">
                            @foreach($featuredEvents->skip(4)->take(4) as $event) {{-- Tomamos los siguientes 4 --}}
                            <a href="{{ route('evento.show', $event->id) }}" class="block p-4 border border-[var(--border-color)] rounded-lg hover:bg-gray-50 transition">
                                <span class="text-xs font-bold uppercase" style="color: var(--color-{{ strtolower(str_replace(' ', '', $event->category ?? '')) }});">{{ $event->category ?? 'Sin categoría' }}</span>
                                <p class="font-bold text-gray-800 mt-1">{{ $event->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }} | {{ $event->locationName }}
                                </p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Mapa Cultural -->
        <section id="mapa" class="py-24 bg-[var(--gray-100)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <h2 class="section-title">Mapa Cultural</h2>
                <div class="flex flex-wrap justify-center gap-2 mb-8" id="map-filters">
                    <button data-category="todos" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 bg-gray-800 text-white">Todos</button>
                    <button data-category="arte" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-visuales)] text-[var(--color-visuales)] hover:bg-[var(--color-visuales)] hover:text-white">Visuales</button>
                    <button data-category="musica" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-musica)] text-[var(--color-musica)] hover:bg-[var(--color-musica)] hover:text-white">Música</button>
                    <button data-category="teatro" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-teatro)] text-[var(--color-teatro)] hover:bg-[var(--color-teatro)] hover:text-white">Teatro</button>
                    <button data-category="cine" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-cine)] text-[var(--color-cine)] hover:bg-[var(--color-cine)] hover:text-white">Cine</button>
                     <button data-category="literatura" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-literatura)] text-[var(--color-literatura)] hover:bg-[var(--color-literatura)] hover:text-white">Literatura</button>
                </div>
                <div id="map" class="h-[500px] rounded-lg z-0 border border-[var(--border-color)]"></div>
            </div>
        </section>
        
        <!-- 4. Últimas Novedades (Tabs) -->
        <section class="py-24 bg-white" x-data="{ activeTab: 'visuales' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="section-title">Últimas Novedades</h2>
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-8">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'visuales'" :class="{ 'border-[var(--color-visuales)] text-[var(--color-visuales)]': activeTab === 'visuales', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'visuales' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Artes Visuales</button>
                        <button @click="activeTab = 'musica'"   :class="{ 'border-[var(--color-musica)] text-[var(--color-musica)]': activeTab === 'musica', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'musica' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Música</button>
                        <button @click="activeTab = 'teatro'"   :class="{ 'border-[var(--color-teatro)] text-[var(--color-teatro)]': activeTab === 'teatro', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'teatro' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Teatro</button>
                        <button @click="activeTab = 'cine'"     :class="{ 'border-[var(--color-cine)] text-[var(--color-cine)]': activeTab === 'cine', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'cine' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Cine</button>
                        <button @click="activeTab = 'literatura'" :class="{ 'border-[var(--color-literatura)] text-[var(--color-literatura)]': activeTab === 'literatura', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'literatura' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Literatura</button>
                    </nav>
                </div>

                <!-- Contenido de los Tabs -->
                <div x-show="activeTab === 'visuales'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Aquí se cargarían los últimos 3 de Artes Visuales --}}
                        <p class="text-gray-500 md:col-span-3">Grid para los últimos 3 posts de Artes Visuales.</p>
                    </div>
                </div>
                <div x-show="activeTab === 'musica'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                       <p class="text-gray-500 md:col-span-3">Grid para los últimos 3 posts de Música.</p>
                    </div>
                </div>
                {{-- (Se repetiría esta estructura para cada categoría) --}}

            </div>
        </section>

    </main>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Lógica del mapa (se mantiene la existente, adaptada a nuevos selectores si es necesario)
        window.allEventsData = @json($allEvents ?? []);

        const map = L.map('map').setView([-34.6037, -58.3816], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
        }).addTo(map);
        
        // ... (resto del script del mapa sin cambios) ...

        // Inicialización del Carrusel
        var swiper = new Swiper('.main-carousel', {
            loop: true,
            autoplay: {
                delay: 3800,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
</x-app-layout>
