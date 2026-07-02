<x-app-layout>
<x-breadcrumb :items="['Buscar' => null]"/>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-black text-gray-900 mb-6">Buscar</h1>

    <form method="GET" action="{{ route('buscar') }}" class="mb-8 max-w-lg">
        <input type="text" name="q" value="{{ $q }}" placeholder="Buscar eventos, novedades, lugares, artistas..."
               class="w-full border border-gray-300 rounded-full px-5 py-3 text-sm focus:outline-none focus:border-gray-600" autofocus>
    </form>

    @if($q === '')
        <p class="text-gray-400 text-center py-16">Escribí algo para buscar.</p>
    @elseif($resultados->isEmpty())
        <p class="text-gray-400 text-center py-16">No encontramos resultados para "{{ $q }}".</p>
    @else
        <p class="text-sm text-gray-500 mb-6">{{ $resultados->count() }} resultado(s) para "{{ $q }}"</p>
        @php
            $catColors = ['Artes Visuales'=>'#7B2D8B','Música'=>'#1A3A7C','Teatro'=>'#8B1A2D','Cine'=>'#E67E22','Literatura'=>'#2E8B57'];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($resultados as $item)
                @php
                    $isEvento = $item instanceof \App\Models\Evento;
                    $link = $isEvento ? route('evento.show', $item->id) : route('novedades.show', $item->slug);
                    $imageUrl = $isEvento
                        ? ($item->mainImage ? Storage::url($item->mainImage) : ($item->mainImageUrl ?: null))
                        : ($item->image ? Storage::url($item->image) : null);
                    $categoryName = $item->category ?? 'Sin categoría';
                    $color = $catColors[$categoryName] ?? '#555';
                    $date = $isEvento ? ($item->startDate ?? $item->singleDate ?? null) : ($item->published_at ?? null);
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
                        <span class="text-xs font-bold uppercase tracking-wider mb-2 block" style="color: {{ $color }}">{{ $categoryName }}</span>
                        <h4 class="text-xl font-bold mb-2 text-gray-800 leading-tight">{{ $item->title }}</h4>
                        <p class="text-sm text-gray-700 font-medium">
                            @if($date){{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('D MMM YYYY') }}@endif
                            @if($date && $location) | @endif
                            @if($location){{ $location }}@endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</main>
</x-app-layout>
