<x-app-layout>
<x-breadcrumb :items="['Lugares' => null, $lugar->nombre => null]"/>
<main class="max-w-4xl mx-auto px-4 py-10">


    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 mb-2">{{ $lugar->nombre }}</h1>
        @if($lugar->direccion)
        <p class="text-gray-500 text-sm">{{ $lugar->direccion }}</p>
        @endif
        <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
            @if($lugar->telefono)<span>📞 {{ $lugar->telefono }}</span>@endif
            @if($lugar->email)<span>✉️ {{ $lugar->email }}</span>@endif
            @if($lugar->website)<a href="{{ $lugar->website }}" target="_blank" class="underline">{{ $lugar->website }}</a>@endif
            @if($lugar->social)<span>{{ $lugar->social }}</span>@endif
        </div>
    </div>

    {{-- MAPA --}}
    @if($lugar->lat && $lugar->lng)
    <div class="mb-8">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <div id="lugar-map" class="h-48 rounded-xl border border-gray-200"></div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('lugar-map', { scrollWheelZoom: false })
                .setView([{{ $lugar->lat }}, {{ $lugar->lng }}], 15);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO'
            }).addTo(map);
            const icon = L.divIcon({
                className: '',
                html: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 36" width="24" height="36"><path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 24 12 24s12-15 12-24C24 5.373 18.627 0 12 0z" fill="#333" stroke="white" stroke-width="1.5"/><circle cx="12" cy="12" r="4" fill="white" opacity="0.8"/></svg>',
                iconSize: [24, 36], iconAnchor: [12, 36], popupAnchor: [0, -36]
            });
            L.marker([{{ $lugar->lat }}, {{ $lugar->lng }}], { icon })
                .addTo(map)
                .bindPopup('<strong>{{ addslashes($lugar->nombre) }}</strong>')
                .openPopup();
        });
        </script>
    </div>
    @endif

    {{-- EVENTOS --}}
    @if($eventos->count() > 0)
    <section>
        <h2 class="text-xs font-bold uppercase tracking-widest mb-6 border-l-4 border-gray-800 pl-3">Historial de Eventos</h2>
        <div class="space-y-4">
            @foreach($eventos as $evento)
            @php
                $img = $evento->mainImage ? Storage::url($evento->mainImage) : ($evento->mainImageUrl ?: null);
                $catColors = ['Artes Visuales'=>'#7B2D8B','Música'=>'#1A3A7C','Teatro'=>'#8B1A2D','Cine'=>'#E67E22','Literatura'=>'#2E8B57'];
                $color = $catColors[$evento->category] ?? '#555';
            @endphp
            <a href="{{ route('evento.show', $evento->id) }}" class="flex gap-4 items-start p-4 rounded-xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                @if($img)
                <img src="{{ $img }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                @else
                <div class="w-20 h-20 rounded-lg flex-shrink-0 bg-gray-100"></div>
                @endif
                <div class="flex-1">
                    <span class="text-xs font-bold uppercase tracking-wider" style="color:{{ $color }}">{{ $evento->category }}</span>
                    <h3 class="font-bold text-gray-900 leading-snug mt-1">{{ $evento->title }}</h3>
                    @if($evento->startDate)
                    <p class="text-xs text-gray-400 mt-1">{{ $evento->startDate->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @else
    <p class="text-gray-400 text-center py-10">No hay eventos registrados en este lugar aún.</p>
    @endif

</main>
</x-app-layout>
