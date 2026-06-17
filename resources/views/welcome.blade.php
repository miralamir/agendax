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
        <!-- 1. Hero Section (full-width, rota por carga) -->
        @php
            $heroEvento = $featuredEvents->shuffle()->first();
            $heroImg = null;
            if ($heroEvento) {
                $heroImg = $heroEvento->mainImage ? \Illuminate\Support\Facades\Storage::url($heroEvento->mainImage) : ($heroEvento->mainImageUrl ?: null);
            }
        @endphp
        <section class="relative overflow-hidden" style="min-height: 300px; width: 100vw; margin-left: calc(50% - 50vw);">
            {{-- Imagen de fondo del evento destacado --}}
            @if($heroImg)
            <div class="absolute inset-0">
                <img src="{{ $heroImg }}" alt="{{ $heroEvento->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.5) 100%);"></div>
            </div>
            @else
            <div class="absolute inset-0 bg-gray-900"></div>
            @endif

            {{-- Banners laterales con efecto glass --}}
            <div class="hidden xl:flex absolute left-0 top-0 bottom-0 z-20 items-center px-6" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-right: 1px solid rgba(255,255,255,0.2);">
                <div class="leading-none [&_.banner-zona]:!my-0">
                    <x-banner posicion="home_hero_izq" />
                </div>
            </div>
            <div class="hidden xl:flex absolute right-0 top-0 bottom-0 z-20 items-center px-6" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-left: 1px solid rgba(255,255,255,0.2);">
                <div class="leading-none [&_.banner-zona]:!my-0">
                    <x-banner posicion="home_hero_der" />
                </div>
            </div>

            {{-- Contenido del hero --}}
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center text-center" style="min-height: 300px;">
                <div class="max-w-2xl">
                    <span class="text-sm font-bold text-white/70 tracking-[3px] uppercase">Agenda Cultural de Buenos Aires</span>
                    <h1 class="mt-4 text-5xl sm:text-7xl font-black text-white leading-[1.05] drop-shadow-lg">
                        Descubrí el arte<br>que te rodea.
                    </h1>
                    <p class="mt-6 max-w-2xl mx-auto text-base sm:text-lg text-white/80 px-4">
                        Inauguraciones, muestras, recitales y eventos culturales. Todo en un solo lugar.
                    </p>

                </div>
            </div>
        </section>

        <!-- Wrapper con degradado continuo hasta el footer -->
        <div style="background: linear-gradient(to bottom, #111827 0%, #1f2937 8%, #4b5563 20%, #9ca3af 40%, #e5e7eb 65%, #f9fafb 100%); width: 100vw; margin-left: calc(50% - 50vw);">
        <!-- 2. Destacados Section -->
        <section class="py-8" style="width: 100vw; margin-left: calc(50% - 50vw);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="p-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Carrusel Principal (Columna Izquierda) -->
                    <div class="lg:col-span-2 flex">
                        <div class="swiper main-carousel rounded-lg overflow-hidden relative w-full">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                @foreach($featuredEvents->take(4) as $event) {{-- Tomamos los primeros 4 para el carrusel --}}
                                <div class="swiper-slide">
                                    <a href="{{ $event instanceof \App\Models\Evento ? route('evento.show', $event->id) : route('novedades.show', $event->slug) }}" class="block relative h-[520px]">
                                        <img src="{{ $event instanceof \App\Models\Evento ? ($event->mainImage ? Storage::url($event->mainImage) : ($event->mainImageUrl ?: '/img/placeholder.jpg')) : ($event->image ? Storage::url($event->image) : '/img/placeholder.jpg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
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
                    <div class="hidden sm:flex">
                        <div class="flex flex-col justify-between gap-4 w-full">
                            @foreach($featuredEvents->skip(4)->take(4) as $event) {{-- Tomamos los siguientes 4 --}}
                            <a href="{{ $event instanceof \App\Models\Evento ? route('evento.show', $event->id) : route('novedades.show', $event->slug) }}" class="flex gap-3 p-3 border border-[var(--border-color)] rounded-lg bg-white hover:bg-gray-50 transition">
                                <div class="w-20 h-20 rounded-md overflow-hidden flex-shrink-0" style="background:#f3f3f3">
                                    <img src="{{ $event instanceof \App\Models\Evento ? ($event->mainImage ? Storage::url($event->mainImage) : ($event->mainImageUrl ?: '/img/placeholder.jpg')) : ($event->image ? Storage::url($event->image) : '/img/placeholder.jpg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs font-bold uppercase" style="color: var(--color-{{ strtolower(str_replace(' ', '', $event->category ?? '')) }});">{{ $event->category ?? 'Sin categoría' }}</span>
                                    <p class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mt-0.5">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }} | {{ $event->locationName }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

        <!-- Banner post-destacados -->
        <div class="max-w-7xl mx-auto px-4">
            <x-banner posicion="home_post_destacados" />
        </div>

        <!-- 3. Mapa Cultural -->
        <section id="mapa" class="py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-t-2xl border border-gray-200 border-b-0 px-6 py-6" style="background: linear-gradient(to bottom, #ffffff 0%, #e5e7eb 70%, #d1d5db 100%);">
                <div class="text-center mb-5">
                    <h2 class="text-2xl font-black text-gray-900 mb-2">Mapa Cultural Interactivo</h2>
                    <p class="text-gray-500 text-sm">Encontrá la ubicación de cada evento y descubrí qué hay cerca tuyo.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-2 mb-4" id="map-filters">
                    <button data-category="todos" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-gray-500 text-gray-700 bg-transparent hover:bg-gray-800 hover:text-white" style="background-color:#1f2937;color:#fff">Todos</button>
                    <button data-category="arte" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-visuales)] text-[var(--color-visuales)] hover:bg-[var(--color-visuales)] hover:text-white">Visuales</button>
                    <button data-category="musica" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-musica)] text-[var(--color-musica)] hover:bg-[var(--color-musica)] hover:text-white">Música</button>
                    <button data-category="teatro" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-teatro)] text-[var(--color-teatro)] hover:bg-[var(--color-teatro)] hover:text-white">Teatro</button>
                    <button data-category="cine" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-cine)] text-[var(--color-cine)] hover:bg-[var(--color-cine)] hover:text-white">Cine</button>
                     <button data-category="literatura" class="filter-btn px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-[var(--color-literatura)] text-[var(--color-literatura)] hover:bg-[var(--color-literatura)] hover:text-white">Literatura</button>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-3 mb-4">
                    <div class="flex gap-2" id="map-date-filters">
                    <button data-fecha="hoy" class="px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-gray-400 text-gray-600 bg-transparent hover:bg-gray-800 hover:text-white hover:border-gray-800">Hoy</button>
                    <button data-fecha="maniana" class="px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-gray-400 text-gray-600 bg-transparent hover:bg-gray-800 hover:text-white hover:border-gray-800">Mañana</button>
                    <button data-fecha="semana" class="px-4 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-colors duration-300 border border-gray-400 text-gray-600 bg-transparent hover:bg-gray-800 hover:text-white hover:border-gray-800">Esta semana</button>
                    </div>
                    <span class="text-gray-300">|</span>
                    <button onclick="irAMiUbicacion()" class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Mi Ubicación
                    </button>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="map-search" placeholder="Buscar..." class="text-sm outline-none w-40 bg-transparent">
                        <button onclick="buscarEnMapa()" class="bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full">Buscar</button>
                    </div>
                </div>
                </div>
                <div id="map" class="h-[500px] rounded-b-2xl z-0 border border-gray-200 border-t-0"></div>
            </div>
        </section>
        
        <!-- Banner post-mapa -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            <x-banner posicion="home_post_mapa" />
        </div>
        <!-- 4. Últimas Novedades (Tabs) -->
        <section class="pt-12 pb-16" x-data="{ activeTab: 'visuales' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="section-title">Últimas Novedades</h2>
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-8 overflow-x-auto">
                    <nav class="-mb-px flex space-x-6 sm:space-x-8 min-w-max" aria-label="Tabs">
                        <button @click="activeTab = 'visuales'" :class="{ 'border-[var(--color-visuales)] text-[var(--color-visuales)]': activeTab === 'visuales', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'visuales' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Artes Visuales</button>
                        <button @click="activeTab = 'musica'"   :class="{ 'border-[var(--color-musica)] text-[var(--color-musica)]': activeTab === 'musica', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'musica' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Música</button>
                        <button @click="activeTab = 'teatro'"   :class="{ 'border-[var(--color-teatro)] text-[var(--color-teatro)]': activeTab === 'teatro', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'teatro' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Teatro</button>
                        <button @click="activeTab = 'cine'"     :class="{ 'border-[var(--color-cine)] text-[var(--color-cine)]': activeTab === 'cine', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'cine' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Cine</button>
                        <button @click="activeTab = 'literatura'" :class="{ 'border-[var(--color-literatura)] text-[var(--color-literatura)]': activeTab === 'literatura', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'literatura' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Literatura</button>
                    </nav>
                </div>

                <!-- Contenido de los Tabs -->
@php
$tabCategories = [
    ["key" => "visuales", "label" => "Artes Visuales", "color" => "#7B2D8B", "light" => "#f3eafc", "route" => "arte"],
    ["key" => "musica",   "label" => "Música",          "color" => "#1A3A7C", "light" => "#e6edf9", "route" => "musica"],
    ["key" => "teatro",   "label" => "Teatro",           "color" => "#8B1A2D", "light" => "#faeaed", "route" => "teatro"],
    ["key" => "cine",     "label" => "Cine",             "color" => "#E67E22", "light" => "#fef3e8", "route" => "cine"],
    ["key" => "literatura","label" => "Literatura",      "color" => "#2E8B57", "light" => "#e8f5ee", "route" => "literatura"],
];
@endphp
@foreach($tabCategories as $tab)
@php $totalItems = $latestByCategory[$tab['key']] ?? collect(); $totalPaginas = (int) ceil($totalItems->count() / 3); @endphp
<div x-show="activeTab === '{{ $tab['key'] }}'" x-cloak x-data="{ pagina: 1, totalPaginas: {{ max($totalPaginas, 1) }} }">
    @if(!empty($latestByCategory[$tab['key']]) && $latestByCategory[$tab['key']]->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($latestByCategory[$tab['key']] as $idx => $item)
        @php
            $isEvento = $item instanceof \App\Models\Evento;
            $link = $isEvento ? route('evento.show', $item->id) : route('novedades.show', $item->slug);
            $img = null;
            if ($isEvento) {
                $img = $item->mainImage ? Storage::url($item->mainImage) : ($item->mainImageUrl ?: null);
            } else {
                $img = $item->image ? Storage::url($item->image) : null;
            }
            $titulo = $item->title;
            $fecha = $isEvento ? ($item->startDate ?? $item->created_at) : ($item->published_at ?? $item->created_at);
            $lugar = $isEvento ? ($item->locationName ?? null) : null;
        @endphp
        <a href="{{ $link }}"
           x-show="window.innerWidth >= 768 || (Math.floor({{ $idx }} / 3) + 1 === pagina)"
           class="group block bg-white rounded-lg border border-[var(--border-color)] overflow-hidden hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300">
            <div class="h-40 overflow-hidden" style="background:{{ $tab['light'] }}">
                @if($img)
                <img src="{{ $img }}" alt="{{ $titulo }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="text-3xl font-black opacity-20" style="color:{{ $tab['color'] }}">{{ strtoupper(substr($tab['label'], 0, 1)) }}</span>
                </div>
                @endif
            </div>
            <div class="p-4">
                <span class="text-xs font-bold uppercase tracking-wider" style="color:{{ $tab['color'] }}">{{ $tab['label'] }}@if($item->subCategory) · {{ $item->subCategory }}@endif</span>
                <h3 class="font-bold text-gray-900 mt-1 text-sm leading-snug line-clamp-2">{{ $titulo }}</h3>
                @if($fecha || $lugar)<p class="text-xs text-gray-700 font-medium mt-1">@if($fecha){{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D MMM') }}@endif @if($fecha && $lugar) | @endif @if($lugar){{ $lugar }}@endif</p>@endif
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-6 flex justify-center items-center gap-2 md:hidden" x-show="totalPaginas > 1">
        <button @click="pagina = Math.max(1, pagina - 1)" :disabled="pagina === 1" :class="{ 'opacity-30 cursor-not-allowed': pagina === 1 }" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600">‹</button>
        <template x-for="p in totalPaginas" :key="p">
            <button @click="pagina = p" :class="pagina === p ? 'text-white' : 'text-gray-600 border border-gray-300'" :style="pagina === p ? 'background-color:{{ $tab['color'] }}' : ''" class="w-9 h-9 flex items-center justify-center rounded-full text-sm font-bold" x-text="p"></button>
        </template>
        <button @click="pagina = Math.min(totalPaginas, pagina + 1)" :disabled="pagina === totalPaginas" :class="{ 'opacity-30 cursor-not-allowed': pagina === totalPaginas }" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600">›</button>
    </div>
    <div class="mt-6 text-center">
        <a href="{{ route($tab['route']) }}" class="text-sm font-bold" style="color:{{ $tab['color'] }}">Ver todos →</a>
    </div>
    @else
    <p class="text-gray-400 text-sm text-center py-8">No hay contenido disponible aún.</p>
    @endif
</div>
@endforeach

            </div>
        </section>

        </div>
    </main>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Lógica del mapa (se mantiene la existente, adaptada a nuevos selectores si es necesario)
        document.addEventListener('DOMContentLoaded', function() {
        window.allEventsData = @json($allEvents ?? []);

        const map = L.map('map').setView([-34.6037, -58.3816], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
        }).addTo(map);
        
        // Colores por categoría
        const categoryColors = {
            'Artes Visuales': '#7B2D8B',
            'Arte': '#7B2D8B',
            'Música': '#1A3A7C',
            'Teatro': '#8B1A2D',
            'Cine': '#E67E22',
            'Literatura': '#2E8B57',
        };

        // Crear markers
        let allMarkers = [];
        window.allEventsData.forEach(event => {
            if (!event.lat || !event.lng) return;
            const color = categoryColors[event.category] || '#555';
            const icon = L.divIcon({
                className: '',
                html: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 36" width="24" height="36"><path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 24 12 24s12-15 12-24C24 5.373 18.627 0 12 0z" fill="' + color + '" stroke="white" stroke-width="1.5"/><circle cx="12" cy="12" r="4" fill="white" opacity="0.8"/></svg>',
                iconSize: [24, 36],
                iconAnchor: [12, 36],
                popupAnchor: [0, -36]
            });
            const marker = L.marker([event.lat, event.lng], { icon }).addTo(map);
            marker.category = event.category;
            marker.eventTitle = event.title || '';
            marker.eventLocation = event.locationName || '';
            marker.fechaFiltro = event.inaugurationDate || event.singleDate || null;
            marker.fechasFuncion = Array.isArray(event.fechasFuncion) ? event.fechasFuncion : [];
            const popupContent = '<div style="font-family:Lato,sans-serif;min-width:180px">'
                + '<div style="font-size:10px;font-weight:700;text-transform:uppercase;color:' + color + ';margin-bottom:4px">' + (event.category || '') + '</div>'
                + '<div style="font-size:14px;font-weight:700;margin-bottom:4px">' + event.title + '</div>'
                + '<div style="font-size:12px;color:#666;margin-bottom:8px">' + (event.locationName || '') + '</div>'
                + '<a href="/evento/' + event.id + '" style="font-size:11px;font-weight:700;color:' + color + ';text-decoration:none">Ver evento →</a>'
                + '</div>';
            marker.bindPopup(popupContent);
            allMarkers.push(marker);
        });

        // Mapa de categorías para filtros
        const categoryMap = {
            'todos': null,
            'arte': ['Artes Visuales', 'Arte'],
            'musica': ['Música'],
            'teatro': ['Teatro'],
            'cine': ['Cine'],
            'literatura': ['Literatura'],
        };

        // Estado de filtros combinables
        let filtroCategoria = 'todos';
        let filtroFecha = 'todos';

        function ymd(d) {
            return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
        }
        // helper: dado un Date, devuelve el dia (yyyy-mm-dd) en hora de Buenos Aires
        function diaBA(date) {
            const ba = new Date(date.getTime() - 3 * 60 * 60 * 1000);
            return ba.getUTCFullYear() + '-' + String(ba.getUTCMonth()+1).padStart(2,'0') + '-' + String(ba.getUTCDate()).padStart(2,'0');
        }
        const ahora = new Date();
        const hoyStr = diaBA(ahora);
        const mananaStr = diaBA(new Date(ahora.getTime() + 24*60*60*1000));
        // fin de semana = hoy + 6 dias (en BA)
        const finSemanaStr = diaBA(new Date(ahora.getTime() + 6*24*60*60*1000));

        function aplicarFiltros() {
            const allowed = categoryMap[filtroCategoria];
            allMarkers.forEach(marker => {
                let okCat = (!allowed || allowed.includes(marker.category));
                let okFecha = true;
                if (filtroFecha !== 'todos') {
                    okFecha = false;
                    // Funcion auxiliar: evalua si un string de fecha YYYY-MM-DD pasa el filtro
                    const pasaFiltro = (fStr) => {
                        if (filtroFecha === 'hoy') return (fStr === hoyStr);
                        if (filtroFecha === 'maniana') return (fStr === mananaStr);
                        if (filtroFecha === 'semana') return (fStr >= hoyStr && fStr <= finSemanaStr);
                        return false;
                    };
                    // Si el evento tiene funciones programadas, evaluamos contra todas sus fechas
                    if (marker.fechasFuncion && marker.fechasFuncion.length) {
                        okFecha = marker.fechasFuncion.some(f => pasaFiltro(f));
                    } else if (marker.fechaFiltro) {
                        // Si no, usamos la fecha unica del evento (inauguracion / dia unico)
                        okFecha = pasaFiltro(diaBA(new Date(marker.fechaFiltro)));
                    }
                }
                if (okCat && okFecha) { marker.addTo(map); } else { marker.remove(); }
            });
        }

        // colores por categoria para pintar el boton activo
        const colorBotonCat = {
            'todos': '#1f2937',
            'arte': '#7B2D8B',
            'musica': '#1A3A7C',
            'teatro': '#8B1A2D',
            'cine': '#E67E22',
            'literatura': '#2E8B57',
        };
        // Filtros de categoria
        document.querySelectorAll('#map-filters button').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#map-filters button').forEach(b => {
                    b.style.backgroundColor = '';
                    b.style.color = '';
                    b.classList.remove('text-white');
                });
                const col = colorBotonCat[this.dataset.category] || '#1f2937';
                this.style.backgroundColor = col;
                this.style.color = '#fff';
                this.classList.add('text-white');
                filtroCategoria = this.dataset.category;
                aplicarFiltros();
            });
        });

        // Filtros de fecha (Hoy / Mañana / Semana) - toggle
        document.querySelectorAll('#map-date-filters button').forEach(btn => {
            btn.addEventListener('click', function() {
                const yaActivo = (this.style.backgroundColor !== '');
                document.querySelectorAll('#map-date-filters button').forEach(b => {
                    b.style.backgroundColor = '';
                    b.style.color = '';
                    b.classList.remove('text-white');
                });
                if (yaActivo) { filtroFecha = 'todos'; }
                else { this.style.backgroundColor = '#1f2937'; this.style.color = '#fff'; this.classList.add('text-white'); filtroFecha = this.dataset.fecha; }
                aplicarFiltros();
            });
        });

        // Mi Ubicación
        window.irAMiUbicacion = function() {
            if (!navigator.geolocation) { alert('Tu browser no soporta geolocalización'); return; }
            navigator.geolocation.getCurrentPosition(pos => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 15);
                L.circleMarker([pos.coords.latitude, pos.coords.longitude], {
                    radius: 10, fillColor: '#1A7A4A', color: '#fff', weight: 2, fillOpacity: 1
                }).addTo(map).bindPopup('Tu ubicación').openPopup();
            });
        };

        // Normalizar texto (quita acentos)
        function normalizar(str) {
            return str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        // Buscador
        window.buscarEnMapa = function() {
            const q = normalizar(document.getElementById('map-search').value.trim());
            if (!q) return;
            const found = allMarkers.filter(m => 
                (m.eventTitle && normalizar(m.eventTitle).includes(q)) ||
                (m.eventLocation && normalizar(m.eventLocation).includes(q)) ||
                (m.category && normalizar(m.category).includes(q))
            );
            if (found.length > 0) {
                map.setView(found[0].getLatLng(), 15);
                found[0].openPopup();
            } else {
                alert('No se encontraron resultados para: ' + q);
            }
        };

        document.getElementById('map-search').addEventListener('keydown', e => {
            if (e.key === 'Enter') buscarEnMapa();
        });

        }); // end DOMContentLoaded mapa

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
