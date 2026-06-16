<x-app-layout>
    @php $subCat = request('sub'); @endphp
    <x-breadcrumb :items="array_filter([
        'Cine' => $subCat ? route('cine') : null,
        $subCat => null
    ])"/>
    <main>
        <!-- 1. Category Header -->
        <header class="py-6" style="background-color: var(--color-cine-light); border-bottom: 3px solid var(--color-cine);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-6xl font-black" style="color: var(--color-cine);">Cine</h1>
                <div class="mt-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ request()->fullUrlWithoutQuery('sub') }}" class="px-4 py-2 text-sm font-bold rounded-full {{ !request('sub') ? 'text-white' : 'border' }}" style="background-color: {{ !request('sub') ? 'var(--color-cine)' : 'transparent' }}; color: {{ !request('sub') ? 'white' : 'var(--color-cine)' }}; border-color: {{ !request('sub') ? 'none' : 'var(--color-cine)' }};">Todos</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Estrenos']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Estrenos' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Estrenos' ? 'var(--color-cine)' : 'transparent' }}; color: {{ request('sub') == 'Estrenos' ? 'white' : 'var(--color-cine)' }}; border-color: var(--color-cine);">Estrenos</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Festivales / Ciclos']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Festivales / Ciclos' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Festivales / Ciclos' ? 'var(--color-cine)' : 'transparent' }}; color: {{ request('sub') == 'Festivales / Ciclos' ? 'white' : 'var(--color-cine)' }}; border-color: var(--color-cine);">Festivales / Ciclos</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Novedades']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Novedades' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Novedades' ? 'var(--color-cine)' : 'transparent' }}; color: {{ request('sub') == 'Novedades' ? 'white' : 'var(--color-cine)' }}; border-color: var(--color-cine);">Novedades</a>
                </div>
                </div>
            </div>
        </header>

        <!-- 3. Destacados Section -->
        <!-- Swiper (destacados) -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <section class="pt-10 pb-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @php
                    $linkDe = fn($i) => $i instanceof \App\Models\Evento ? route('evento.show', $i->id) : route('novedades.show', $i->slug);
                    $imgDe = function($i) {
                        if ($i instanceof \App\Models\Evento) return $i->mainImage ? Storage::url($i->mainImage) : ($i->mainImageUrl ?: '/img/placeholder.jpg');
                        return $i->image ? Storage::url($i->image) : '/img/placeholder.jpg';
                    };
                    $fechaDe = fn($i) => $i instanceof \App\Models\Evento ? ($i->startDate ?? $i->singleDate ?? $i->created_at) : ($i->published_at ?? $i->created_at);
                @endphp
                @if($featuredEvents->isNotEmpty())
                <div class="rounded-2xl p-6 shadow-[0_2px_16px_rgba(0,0,0,0.06)]" style="background: linear-gradient(to top, color-mix(in srgb, var(--color-cine) 12%, white), color-mix(in srgb, var(--color-cine) 4%, white)); border: 1px solid color-mix(in srgb, var(--color-cine) 25%, white);">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Carrusel grande (Izquierda) -->
                    <div class="lg:col-span-2 flex">
                        <div class="swiper carousel-cine rounded-lg overflow-hidden relative w-full">
                            <div class="swiper-wrapper">
                                @foreach($featuredEvents as $item)
                                <div class="swiper-slide">
                                    <a href="{{ $linkDe($item) }}" class="block relative h-[480px]">
                                        <img src="{{ $imgDe($item) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/10"></div>
                                        <div class="absolute bottom-0 left-0 p-8 text-white">
                                            <span class="text-sm font-bold uppercase tracking-wider" style="color: #fff; background: var(--color-cine); padding: 3px 10px; border-radius: 9999px; display: inline-block;">{{ $item->category }}@if($item->subCategory) &middot; {{ $item->subCategory }}@endif</span>
                                            <h3 class="text-3xl font-bold mt-2 leading-tight">{{ $item->title }}</h3>
                                            <p class="mt-2 text-sm">{{ $item->locationName ?? '' }}</p>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next text-white"></div>
                            <div class="swiper-button-prev text-white"></div>
                        </div>
                    </div>
                    <!-- Lista lateral (Derecha) con imagenes + scroll -->
                    <div class="hidden sm:flex flex-col" id="lista-cine">
                        @if($featuredEvents->count() > 4)
                        <button type="button" class="lista-arriba w-full flex justify-center py-1 text-gray-400 hover:text-gray-700" aria-label="Anterior">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        @endif
                        <div class="lista-viewport overflow-hidden flex-1" style="max-height: 28rem;">
                            <div class="lista-track flex flex-col gap-4 transition-transform duration-300">
                                @foreach($featuredEvents as $item)
                                <a href="{{ $linkDe($item) }}" class="flex gap-3 p-3 border border-[var(--border-color)] rounded-lg bg-white hover:bg-gray-50 transition flex-shrink-0">
                                    <div class="w-20 h-20 rounded-md overflow-hidden flex-shrink-0" style="background:#f3f3f3">
                                        <img src="{{ $imgDe($item) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs font-bold uppercase" style="color: var(--color-cine);">{{ $item->category }}</span>
                                        <p class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mt-0.5">{{ $item->title }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($fechaDe($item))->locale('es')->isoFormat('D MMM') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @if($featuredEvents->count() > 4)
                        <button type="button" class="lista-abajo w-full flex justify-center py-1 text-gray-400 hover:text-gray-700" aria-label="Siguiente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
                </div>
                @endif
            </div>
        </section>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('.carousel-cine')) {
                    new Swiper('.carousel-cine', {
                        loop: true,
                        autoplay: { delay: 3800, disableOnInteraction: false },
                        pagination: { el: '.carousel-cine .swiper-pagination', clickable: true },
                        navigation: { nextEl: '.carousel-cine .swiper-button-next', prevEl: '.carousel-cine .swiper-button-prev' },
                    });
                }

            // Scroll de la lista lateral con flechas
            (function() {
                const cont = document.getElementById('lista-cine');
                if (!cont) return;
                const track = cont.querySelector('.lista-track');
                const items = track ? track.children : [];
                if (items.length <= 4) return;
                let pos = 0;
                const visibles = 4;
                function itemH() { return items[0].offsetHeight + 16; }
                function aplicar() { track.style.transform = 'translateY(-' + (pos * itemH()) + 'px)'; }
                const up = cont.querySelector('.lista-arriba');
                const down = cont.querySelector('.lista-abajo');
                if (up) up.addEventListener('click', () => { if (pos > 0) { pos--; aplicar(); } });
                if (down) down.addEventListener('click', () => { if (pos < items.length - visibles) { pos++; aplicar(); } });
            })();
            });
        </script>

        <!-- Banner entre destacados y noticias -->
        <div class="max-w-7xl mx-auto px-4 pb-8">
            <x-banner posicion="cat_entre_secciones" />
        </div>
        <!-- 4. Últimas Novedades Section -->
        <section class="pt-4 pb-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="section-title">Últimas Novedades</h2>
                    <a href="#" class="text-sm font-bold" style="color: var(--color-cine);">Ver todos &rarr;</a>
                </div>
                <!-- Grilla de 3 columnas para los posts -->
                @if($latestItems->isNotEmpty())
                @php $totalPaginasCel = (int) ceil($latestItems->count() / 3); @endphp
                <div x-data="{ pagina: 1, totalPaginas: {{ max($totalPaginasCel, 1) }} }">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestItems as $idx => $item)
                        @php
                            $isEvento = $item instanceof \App\Models\Evento;
                            $link = $isEvento ? route('evento.show', $item->id) : route('novedades.show', $item->slug);
                            $imageUrl = null;
                            if ($isEvento) {
                                $img = $item->mainImage ? Storage::url($item->mainImage) : ($item->mainImageUrl ? (str_starts_with($item->mainImageUrl, 'http') ? $item->mainImageUrl : Storage::url($item->mainImageUrl)) : null);
                                $imageUrl = $img;
                            } else {
                                $imageUrl = $item->image ? Storage::url($item->image) : '/img/placeholder.jpg';
                            }
                            $categoryName = $isEvento ? ($item->category ?? 'Sin categoría') : ($item->category ?? 'Sin categoría');
                            $date = $isEvento ? ($item->startDate ?? $item->singleDate ?? null) : ($item->published_at ?? null);
                            $location = $isEvento ? ($item->locationName ?? null) : null;
                        @endphp
                        <a href="{{ $link }}"
                           x-show="window.innerWidth >= 768 || (Math.floor({{ $idx }} / 3) + 1 === pagina)"
                           class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
                            <div class="relative h-48 overflow-hidden">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Sin imagen</div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="text-xs font-bold uppercase tracking-wider mb-2 block" style="color: var(--color-{{ strtolower(str_replace(' ', '', $categoryName)) }})">
                                    {{ $categoryName }}@if($item->subCategory) · {{ $item->subCategory }}@endif
                                </span>
                                <h4 class="text-xl font-bold mb-2 text-gray-800 leading-tight">{{ $item->title }}</h4>
                                <p class="text-sm text-gray-700 font-medium">
                                    @if ($date)
                                        {{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('D MMM') }}
                                    @endif
                                    @if ($location)
                                        | {{ $location }}
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-center items-center gap-2 md:hidden" x-show="totalPaginas > 1">
                    <button @click="pagina = Math.max(1, pagina - 1)" :disabled="pagina === 1" :class="{ 'opacity-30 cursor-not-allowed': pagina === 1 }" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600">‹</button>
                    <template x-for="p in totalPaginas" :key="p">
                        <button @click="pagina = p" :class="pagina === p ? 'text-white' : 'text-gray-600 border border-gray-300'" :style="pagina === p ? 'background-color: var(--color-cine)' : ''" class="w-9 h-9 flex items-center justify-center rounded-full text-sm font-bold" x-text="p"></button>
                    </template>
                    <button @click="pagina = Math.min(totalPaginas, pagina + 1)" :disabled="pagina === totalPaginas" :class="{ 'opacity-30 cursor-not-allowed': pagina === totalPaginas }" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600">›</button>
                </div>
                </div>
                <div class="mt-12 text-center">
                    <div class="hidden md:block">{{ $latestItems->links() }}</div>
                </div>
                @else
                <p class="text-gray-500">No hay más novedades ni eventos por el momento.</p>
                @endif
    </main>
</x-app-layout>
