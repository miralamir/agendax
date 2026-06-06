<x-app-layout>
    <main>
        <!-- 1. Category Header -->
        <header class="py-12" style="background-color: var(--color-visuales-light); border-bottom: 3px solid var(--color-visuales);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-6xl font-black" style="color: var(--color-visuales);">Artes Visuales</h1>
            </div>
        </header>

        <!-- 2. Subcategory Filters (Pills) -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full text-white" style="background-color: var(--color-visuales);">Todos</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-visuales); border-color: var(--color-visuales); background-color: transparent;">Agenda</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-visuales); border-color: var(--color-visuales); background-color: transparent;">Creadores</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-visuales); border-color: var(--color-visuales); background-color: transparent;">Ferias</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-visuales); border-color: var(--color-visuales); background-color: transparent;">Novedades</a>
                </div>
            </div>
        </nav>

        <!-- 3. Destacados Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <a href="#" class="text-sm font-bold" style="color: var(--color-visuales);">Ver todos →</a>
                </div>
                @if($featuredEvents->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Nota Grande (Izquierda) -->
                    <div class="lg:col-span-2">
                        @php $firstFeatured = $featuredEvents->first(); @endphp
                        <a href="{{ route('evento.show', $firstFeatured->id) }}" class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
                           <div class="relative h-96 overflow-hidden">
                               <img src="{{ $firstFeatured->mainImageUrl }}" alt="{{ $firstFeatured->title }}" class="w-full h-full object-cover">
                           </div>
                           <div class="p-6">
                               <h4 class="text-2xl font-bold mb-2 text-gray-800 leading-tight">{{ $firstFeatured->title }}</h4>
                               <p class="text-sm text-gray-500">{{ $firstFeatured->locationName }}</p>
                           </div>
                        </a>
                    </div>
                    <!-- Stack de Notas Pequeñas (Derecha) -->
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
                    <a href="#" class="text-sm font-bold" style="color: var(--color-visuales);">Ver todos →</a>
                </div>
                <!-- Grilla de 3 columnas para los posts -->
                @if($latestEvents->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestEvents as $event)
                        <a href="{{ route('evento.show', $event->id) }}" class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
                            <div class="relative h-48 overflow-hidden">
                                @if($event->mainImageUrl)
                                    <img src="{{ $event->mainImageUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Sin imagen</div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="text-xs font-bold uppercase tracking-wider mb-2 block" style="color: var(--color-{{ strtolower(str_replace(' ', '', $event->category ?? '')) }})">
                                    {{ $event->category ?? 'Sin categoría' }}
                                </span>
                                <h4 class="text-xl font-bold mb-2 text-gray-800 leading-tight">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-500 font-normal">
                                    {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }} | {{ $event->locationName }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $latestEvents->links() }}
                </div>
                @else
                <p class="text-gray-500">No hay más eventos por el momento.</p>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>
