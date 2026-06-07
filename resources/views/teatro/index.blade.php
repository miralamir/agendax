<x-app-layout>
    <main>
        <!-- 1. Category Header -->
        <header class="py-12" style="background-color: var(--color-teatro-light); border-bottom: 3px solid var(--color-teatro);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-6xl font-black" style="color: var(--color-teatro);">Teatro</h1>
            </div>
        </header>

        <!-- 2. Subcategory Filters (Pills) -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ request()->fullUrlWithoutQuery('sub') }}" class="px-4 py-2 text-sm font-bold rounded-full {{ !request('sub') ? 'text-white' : 'border' }}" style="background-color: {{ !request('sub') ? 'var(--color-teatro)' : 'transparent' }}; color: {{ !request('sub') ? 'white' : 'var(--color-teatro)' }}; border-color: {{ !request('sub') ? 'none' : 'var(--color-teatro)' }};">Todos</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Cartelera']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Cartelera' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Cartelera' ? 'var(--color-teatro)' : 'transparent' }}; color: {{ request('sub') == 'Cartelera' ? 'white' : 'var(--color-teatro)' }}; border-color: var(--color-teatro);">Cartelera</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Festivales']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Festivales' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Festivales' ? 'var(--color-teatro)' : 'transparent' }}; color: {{ request('sub') == 'Festivales' ? 'white' : 'var(--color-teatro)' }}; border-color: var(--color-teatro);">Festivales</a>
                    <a href="{{ request()->fullUrlWithQuery(['sub' => 'Novedades']) }}" class="px-4 py-2 text-sm font-bold rounded-full border {{ request('sub') == 'Novedades' ? 'text-white' : '' }}" style="background-color: {{ request('sub') == 'Novedades' ? 'var(--color-teatro)' : 'transparent' }}; color: {{ request('sub') == 'Novedades' ? 'white' : 'var(--color-teatro)' }}; border-color: var(--color-teatro);">Novedades</a>
                </div>
            </div>
        </nav>

        <!-- 3. Destacados Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                </div>
                @if($featuredEvents->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        @php $firstFeatured = $featuredEvents->first(); @endphp
                        <a href="{{ route('evento.show', $firstFeatured->id) }}" class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
                           <div class="relative h-96 overflow-hidden">
                               <img src="{{ $firstFeatured->mainImageUrl ? Storage::url($firstFeatured->mainImageUrl) : '/img/placeholder.jpg' }}" alt="{{ $firstFeatured->title }}" class="w-full h-full object-cover">
                           </div>
                           <div class="p-6">
                               <h4 class="text-2xl font-bold mb-2 text-gray-800 leading-tight">{{ $firstFeatured->title }}</h4>
                               <p class="text-sm text-gray-500">{{ $firstFeatured->locationName }}</p>
                           </div>
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($featuredEvents->skip(1)->take(3) as $event)
                        <a href="{{ route('evento.show', $event->id) }}" class="block p-4 border border-[var(--border-color)] rounded-lg hover:bg-gray-50 transition">
                            <p class="font-bold text-gray-800">{{ $event->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <p class="text-gray-500">No hay eventos destacados en este momento.</p>
                @endif
            </div>
        </section>

        <!-- 4. Últimas Novedades Section -->
        <section class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="section-title">Últimas Novedades</h2>
                    <a href="#" class="text-sm font-bold" style="color: var(--color-teatro);">Ver todos &rarr;</a>
                </div>
                <!-- Grilla de 3 columnas para los posts -->
                @if($latestItems->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestItems as $item)
                        @php
                            $isEvento = $item instanceof \App\Models\Evento;
                            $link = $isEvento ? route('evento.show', $item->id) : route('novedades.show', $item->slug);
                            $imageUrl = null;
                            if ($isEvento) {
                                $imageUrl = $item->mainImageUrl ? Storage::url($item->mainImageUrl) : '/img/placeholder.jpg';
                            } else {
                                $imageUrl = $item->image ? Storage::url($item->image) : '/img/placeholder.jpg';
                            }
                            $categoryName = $isEvento ? ($item->category ?? 'Sin categoría') : ($item->category ?? 'Sin categoría');
                            $date = $isEvento ? ($item->startDate ?? null) : ($item->published_at ?? null);
                            $location = $isEvento ? ($item->locationName ?? null) : null;
                        @endphp
                        <a href="{{ $link }}" class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
                            <div class="relative h-48 overflow-hidden">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Sin imagen</div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="text-xs font-bold uppercase tracking-wider mb-2 block" style="color: var(--color-{{ strtolower(str_replace(' ', '', $categoryName)) }})">
                                    {{ $categoryName }}
                                </span>
                                <h4 class="text-xl font-bold mb-2 text-gray-800 leading-tight">{{ $item->title }}</h4>
                                <p class="text-sm text-gray-500 font-normal">
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
                <div class="mt-12 text-center">
                    {{ $latestItems->links() }}
                </div>
                @else
                <p class="text-gray-500">No hay más novedades ni eventos por el momento.</p>
                @endif
    </main>
</x-app-layout>
