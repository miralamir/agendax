<x-app-layout>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-black text-gray-900 mb-2">Mi Agenda</h1>
        <p class="text-gray-500 mb-8">Los eventos y novedades que guardaste.</p>

        @if($favoritos->isEmpty())
        <div class="text-center py-16 border border-dashed border-gray-300 rounded-xl">
            <p class="text-gray-400 mb-4">Todavía no guardaste nada.</p>
            <a href="{{ url('/') }}" class="text-sm font-bold" style="color: var(--color-visuales);">Explorar la agenda &rarr;</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favoritos as $fav)
                @php
                    $item = $fav->favoritable;
                    $esEvento = $item instanceof \App\Models\Evento;
                    $link = $esEvento ? route('evento.show', $item->id) : route('novedades.show', $item->slug);
                    $img = $esEvento
                        ? ($item->mainImage ? Storage::url($item->mainImage) : ($item->mainImageUrl ?: '/img/placeholder.jpg'))
                        : ($item->image ? Storage::url($item->image) : '/img/placeholder.jpg');
                    $color = strtolower(str_replace(' ', '', $item->category ?? ''));
                @endphp
                <div class="bg-white rounded-xl border border-[var(--border-color)] overflow-hidden hover:shadow-md transition">
                    <a href="{{ $link }}" class="block">
                        <div class="h-40 overflow-hidden" style="background:#f3f3f3">
                            <img src="{{ $img }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <span class="text-xs font-bold uppercase" style="color: var(--color-{{ $color }});">{{ $item->category }}</span>
                            <h3 class="font-bold text-gray-900 leading-snug mt-1 line-clamp-2">{{ $item->title }}</h3>
                            <p class="text-xs text-gray-400 mt-1">{{ $item->locationName ?? '' }}</p>
                        </div>
                    </a>
                    <div class="px-4 pb-4">
                        <form action="{{ route('favorito.toggle') }}" method="POST" onsubmit="return confirm('¿Quitar de tu agenda?');">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $esEvento ? 'evento' : 'novedad' }}">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700">Quitar de mi agenda</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </main>
</x-app-layout>
