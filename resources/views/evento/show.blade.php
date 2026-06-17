<x-app-layout>
@php
    $catColor = ['Artes Visuales'=>'#7B2D8B','Arte'=>'#7B2D8B','Música'=>'#1A3A7C','Teatro'=>'#8B1A2D','Cine'=>'#E67E22','Literatura'=>'#2E8B57'][$evento->category] ?? '#555';
    $catLight = ['Artes Visuales'=>'#f3eafc','Arte'=>'#f3eafc','Música'=>'#e6edf9','Teatro'=>'#faeaed','Cine'=>'#fef3e8','Literatura'=>'#e8f5ee'][$evento->category] ?? '#f5f5f5';
    $imageUrl = $evento->mainImage ? Storage::url($evento->mainImage) : ($evento->mainImageUrl ?: null);
    $bios = $evento->bios ?? [];
@endphp
@php
    $catRouteEv = ['Artes Visuales'=>'arte','Música'=>'musica','Teatro'=>'teatro','Cine'=>'cine','Literatura'=>'literatura'][$evento->category] ?? 'arte';
    $subUrlEv = $evento->subCategory ? route($catRouteEv) . '?sub=' . urlencode($evento->subCategory) : null;
@endphp
<x-breadcrumb :items="array_filter([
    $evento->category => route($catRouteEv),
    $evento->subCategory => $subUrlEv,
    $evento->title => null
])"/>
<!-- Banners laterales (solo desktop ancho) -->
<div class="hidden 2xl:block fixed left-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="articulo_izq" />
</div>
<div class="hidden 2xl:block fixed right-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="articulo_der" />
</div>
<main class="max-w-4xl mx-auto px-4 py-10">
    <div class="mb-6">
        <x-banner posicion="articulo_post_breadcrumb" />
    </div>

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-4xl font-black text-gray-900 leading-tight mb-1">{{ $evento->title }}</h1>
        @php
            $artistas = collect($evento->bios ?? [])->where('rol', 'Artista')->pluck('nombre');
            $curadores = collect($evento->bios ?? [])->where('rol', 'Curador/a')->pluck('nombre');
            $otrosBios = collect($evento->bios ?? [])->whereNotIn('rol', ['Artista', 'Curador/a']);
        @endphp
        @if($artistas->count() > 0)
        <p class="text-base text-gray-700 font-medium mb-0.5">
            @foreach($artistas as $i => $nombre)
            <a href="{{ route('creador.show', \Illuminate\Support\Str::slug($nombre)) }}" class="hover:underline">{{ $nombre }}</a>@if(!$loop->last) + @endif
            @endforeach
        </p>
        @elseif($evento->artist)
        <p class="text-base text-gray-700 font-medium mb-0.5">
            @php $nombresArtistas = array_filter(array_map('trim', preg_split('/\s*(?:,|\sy\s|&|\|)\s*/u', $evento->artist))); @endphp
            @foreach($nombresArtistas as $nombre)
            <a href="{{ route('creador.show', \Illuminate\Support\Str::slug($nombre)) }}" class="hover:underline">{{ $nombre }}</a>@if(!$loop->last) + @endif
            @endforeach
        </p>
        @endif
        @if($curadores->count() > 0)
        <p class="text-sm text-gray-500 mb-0.5"><strong>Curador/a:</strong>
            @foreach($curadores as $i => $nombre)
            <a href="{{ route('creador.show', \Illuminate\Support\Str::slug($nombre)) }}" class="hover:underline">{{ $nombre }}</a>@if(!$loop->last), @endif
            @endforeach
        </p>
        @elseif($evento->curator)
        <p class="text-sm text-gray-500 mb-0.5"><strong>Curador/a:</strong>
            @foreach(preg_split('/\s*(?:,|\sy\s|&)\s*/u', $evento->curator) as $i => $nombreCur)
            @php $nombreCur = trim($nombreCur); @endphp
            @if($nombreCur)<a href="{{ route('creador.show', \Illuminate\Support\Str::slug($nombreCur)) }}" class="hover:underline">{{ $nombreCur }}</a>@if(!$loop->last), @endif @endif
            @endforeach
        </p>
        @endif
        @foreach($otrosBios as $otro)
        @if(!empty($otro['nombre']))
        <p class="text-sm text-gray-500 mb-0.5"><strong>{{ $otro['rol'] }}:</strong>
            <a href="{{ route('creador.show', \Illuminate\Support\Str::slug($otro['nombre'])) }}" class="hover:underline">{{ $otro['nombre'] }}</a>
        </p>
        @endif
        @endforeach
        <div class="mb-4"></div>
        <div class="space-y-1 text-sm text-gray-600 mb-4">
            @if($evento->startDate && $evento->endDate)
            <p>Desde el {{ ucfirst($evento->startDate->locale('es')->isoFormat('dddd D [de] MMMM')) }} al {{ $evento->endDate->locale('es')->isoFormat('D [de] MMMM') }}</p>
            @elseif($evento->startDate)
            <p>{{ ucfirst($evento->startDate->locale('es')->isoFormat('dddd D [de] MMMM')) }}</p>
            @elseif($evento->singleDate)
            <p>{{ ucfirst($evento->singleDate->locale('es')->isoFormat('dddd D [de] MMMM, HH:mm')) }} hs</p>
            @endif
            @if($evento->horarioResumido())
            <p>Funciones: {{ $evento->horarioResumido() }}</p>
            @elseif($evento->recurringSchedule)
            <p>Funciones: {{ $evento->recurringSchedule }}</p>
            @endif
            @if($evento->inaugurationDate)
            <p>Inauguración: {{ ucfirst($evento->inaugurationDate->locale('es')->isoFormat('dddd D [de] MMMM, HH:mm')) }} hs</p>
            @endif
        </div>
        @if($evento->locationName || $evento->venueAddress)
        <div class="text-sm text-gray-500 space-y-0.5">
            @if($evento->locationName)<p class="font-semibold text-gray-800"><a href="{{ route('lugar.show', \Illuminate\Support\Str::slug($evento->locationName)) }}" class="hover:underline">{{ $evento->locationName }}</a></p>@endif
            @if($evento->venueAddress)<p>{{ $evento->venueAddress }}</p>@endif
        </div>
        @endif
        <div class="mt-5">
            <x-boton-favorito tipo="evento" :item="$evento" />
        </div>
    </div>

    {{-- IMAGEN PRINCIPAL --}}
    @if($imageUrl)
    <div class="mb-8 rounded-xl overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $evento->title }}" class="gallery-img w-full max-h-[500px] object-cover cursor-zoom-in">
    </div>
    @endif

    {{-- DESCRIPCIÓN --}}
    @if($evento->description)
    <div class="mb-10 prose prose-lg max-w-none text-gray-700 leading-relaxed">
        {!! \App\Helpers\TextHelper::autoLink($evento->description) !!}
    </div>
    @endif

    {{-- GALERÍA --}}
    @if($evento->gallery && count($evento->gallery) > 0)
    <section class="mb-10">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
        <div class="swiper galeria-swiper rounded-xl overflow-hidden max-w-2xl mx-auto">
            <div class="swiper-wrapper">
                @foreach($evento->gallery as $img)
                @php $imgUrl = is_array($img) ? ($img['url'] ?? '') : $img; $imgCaption = is_array($img) ? ($img['caption'] ?? '') : ''; @endphp
                <div class="swiper-slide">
                    <div class="bg-gray-100 flex items-center justify-center" style="max-height:420px;min-height:200px;">
                        <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : Storage::url($imgUrl) }}"
                             class="gallery-img max-h-[420px] w-full object-contain cursor-zoom-in"
                             alt="{{ $imgCaption }}" data-caption="{{ $imgCaption }}">
                    </div>
                    @if($imgCaption)
                    <div class="px-2 py-2 text-center" style="background:#f9f9f9;border-top:1px solid #eee;">
                        <p class="text-gray-600 text-xs leading-snug">{{ $imgCaption }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.galeria-swiper', {
                loop: true,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
            });
        });
        </script>
    </section>
    @endif

    {{-- VIDEOS --}}
    @if(!empty($evento->videos) && count($evento->videos) > 0)
    <section class="mb-10">
        <div class="space-y-4 max-w-2xl mx-auto">
            @foreach($evento->videos as $videoUrl)
            @php
                $embedUrl = null;
                if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $videoUrl, $m)) $embedUrl = "https://www.youtube.com/embed/" . $m[1];
                elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $m)) $embedUrl = "https://www.youtube.com/embed/" . $m[1];
                elseif (preg_match('/vimeo\.com\/([0-9]+)/', $videoUrl, $m)) $embedUrl = "https://player.vimeo.com/video/" . $m[1];
            @endphp
            @if($embedUrl)
            <div class="rounded-xl overflow-hidden aspect-video">
                <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
            @endif
            @endforeach
        </div>
    </section>
    @endif

    <div class="mt-16"></div>

    {{-- BIOGRAFÍAS --}}
    @if(collect($bios)->contains(fn($b) => !empty($b['nombre'])))
    <section class="mb-10">
        <h2 class="text-xs font-bold uppercase tracking-widest mb-6" style="color:{{ $catColor }};border-left:3px solid {{ $catColor }};padding-left:10px;">Participantes</h2>
        <div class="space-y-6">
            @foreach($bios as $bio)
            @if(!empty($bio['nombre']))
            <div class="flex gap-5 items-start">
                @if(!empty($bio['foto']))
                <img src="{{ str_starts_with($bio['foto'], 'http') ? $bio['foto'] : Storage::url($bio['foto']) }}"
                     alt="{{ $bio['nombre'] }}" class="w-16 h-16 rounded-full object-cover flex-shrink-0 border-2" style="border-color:{{ $catColor }}">
                @else
                <div class="w-16 h-16 rounded-full flex-shrink-0 flex items-center justify-center text-xl font-bold text-white" style="background:{{ $catColor }}">
                    {{ strtoupper(substr($bio['nombre'], 0, 1)) }}
                </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('creador.show', \Illuminate\Support\Str::slug($bio['nombre'])) }}" class="font-bold text-gray-900 hover:underline">{{ $bio['nombre'] }}</a>
                        @if(!empty($bio['rol']))<span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:{{ $catLight }};color:{{ $catColor }}">{{ $bio['rol'] }}</span>@endif
                    </div>
                    @if(!empty($bio['bio']))<p class="text-sm text-gray-600 leading-relaxed">{!! \App\Helpers\TextHelper::autoLink($bio['bio']) !!}</p>@endif
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- PDF --}}
    @if($evento->catalogPdfUrl)
    <section class="mb-10">
        
        <a href="{{ str_starts_with($evento->catalogPdfUrl, 'http') ? $evento->catalogPdfUrl : Storage::url($evento->catalogPdfUrl) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold border" style="color:{{ $catColor }};border-color:{{ $catColor }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Descargar Catálogo PDF
        </a>
    </section>
    @endif

    {{-- LUGAR + MAPA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <section>
            <h2 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:{{ $catColor }};border-left:3px solid {{ $catColor }};padding-left:10px;">Lugar y Contacto</h2>
            <div class="space-y-2 text-sm text-gray-700">
                @if($evento->locationName)<p><a href="{{ route('lugar.show', \Illuminate\Support\Str::slug($evento->locationName)) }}" class="font-bold text-gray-900 hover:underline">{{ $evento->locationName }}</a></p>@endif
                @if($evento->venueAddress)<p class="text-gray-500">{{ $evento->venueAddress }}</p>@endif
                @if($evento->room)<p>Sala: {{ $evento->room }}</p>@endif
                @if($evento->venueHours)<p><strong>Horarios:</strong> {{ $evento->venueHours }}</p>@endif
                @if($evento->venuePhone)<p><strong>Tel:</strong> {{ $evento->venuePhone }}</p>@endif
                @if($evento->venueEmail)<p><strong>Email:</strong> {{ $evento->venueEmail }}</p>@endif
                @if($evento->venueWebsite)<p><a href="{{ $evento->venueWebsite }}" target="_blank" class="underline" style="color:{{ $catColor }}">{{ $evento->venueWebsite }}</a></p>@endif
            </div>
            @if($evento->priceInfo || $evento->ticketUrl)
            <div class="mt-4 pt-4 border-t border-gray-100">
                @if($evento->priceInfo)<p class="text-sm"><strong>Entrada:</strong> {{ $evento->priceInfo }}</p>@endif
                @if($evento->ticketUrl)<a href="{{ $evento->ticketUrl }}" target="_blank" class="inline-block mt-3 px-5 py-2 rounded-full text-sm font-bold text-white" style="background:{{ $catColor }}">Comprar Entradas →</a>@endif
            </div>
            @endif
        </section>
        @if($evento->lat && $evento->lng)
        <section>
            <h2 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:{{ $catColor }};border-left:3px solid {{ $catColor }};padding-left:10px;">Ubicación</h2>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
            <div id="evento-map" class="h-48 rounded-lg border border-gray-200"></div>
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('evento-map', { zoomControl: true, scrollWheelZoom: false }).setView([{{ $evento->lat }}, {{ $evento->lng }}], 15);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap &copy; CARTO' }).addTo(map);
                const icon = L.divIcon({ className: '', html: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 36" width="24" height="36"><path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 24 12 24s12-15 12-24C24 5.373 18.627 0 12 0z" fill="{{ $catColor }}" stroke="white" stroke-width="1.5"/><circle cx="12" cy="12" r="4" fill="white" opacity="0.8"/></svg>', iconSize: [24,36], iconAnchor: [12,36], popupAnchor: [0,-36] });
                L.marker([{{ $evento->lat }}, {{ $evento->lng }}], { icon }).addTo(map).bindPopup('<strong>{{ addslashes($evento->locationName ?? '') }}</strong><br>{{ addslashes($evento->venueAddress ?? '') }}').openPopup();
            });
            </script>
        </section>
        @endif
    </div>

    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <x-banner posicion="articulo_pre_comentarios" />
        </div>
        <x-comentarios tipo="evento" :item="$evento" />
    </div>
</main>

{{-- LIGHTBOX --}}
<style>
#lightbox-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out; }
#lightbox-overlay.active { display:flex; }
#lightbox-overlay img { max-width:90vw;max-height:90vh;object-fit:contain;border-radius:8px; }
#lightbox-close { position:fixed;top:20px;right:24px;color:white;font-size:32px;cursor:pointer;z-index:10000;line-height:1; }
.galeria-swiper .swiper-pagination { display:none; }
.galeria-swiper .swiper-button-prev, .galeria-swiper .swiper-button-next { color:white;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.8)); }
</style>
<div id="lightbox-overlay" onclick="closeLightbox()">
    <span id="lightbox-close" onclick="closeLightbox()">×</span>
    <div style="display:flex;flex-direction:column;align-items:center;max-width:90vw;">
        <img id="lightbox-img" src="" alt="">
        <p id="lightbox-caption" style="color:white;font-size:14px;margin-top:12px;text-align:center;display:none;"></p>
    </div>
</div>
<script>
function openLightbox(src, caption) {
    document.getElementById('lightbox-img').src = src;
    const cap = document.getElementById('lightbox-caption');
    if (cap) { cap.textContent = caption || ''; cap.style.display = caption ? 'block' : 'none'; }
    document.getElementById('lightbox-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox-overlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-img').forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => openLightbox(img.src, img.dataset.caption || ''));
    });
});
</script>
</x-app-layout>
