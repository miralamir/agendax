<x-app-layout>
<x-breadcrumb :items="['Creadores' => null, $creador->nombre => null]"/>
<div class="hidden 2xl:block fixed left-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="creador_izq" />
</div>
<div class="hidden 2xl:block fixed right-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="creador_der" />
</div>
<main class="max-w-4xl mx-auto px-4 py-10">
    <div class="mb-6">
        <x-banner posicion="creador_post_breadcrumb" />
    </div>


    {{-- HEADER --}}
    <div class="flex gap-6 items-start mb-10">
        @if($creador->foto)
        <img src="{{ str_starts_with($creador->foto, 'http') ? $creador->foto : Storage::url($creador->foto) }}"
             alt="{{ $creador->nombre }}"
             class="w-24 h-24 rounded-full object-cover flex-shrink-0 border-2 border-gray-200">
        @else
        <div class="w-24 h-24 rounded-full flex-shrink-0 flex items-center justify-center text-3xl font-black text-white bg-gray-400">
            {{ strtoupper(substr($creador->nombre, 0, 1)) }}
        </div>
        @endif
        <div>
            @if($creador->rol)
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ $creador->rol }}</span>
            @endif
            <h1 class="text-3xl font-black text-gray-900 mt-1">{{ $creador->nombre }}</h1>
            @if($creador->bio)
            <p class="text-gray-600 mt-3 leading-relaxed">{!! nl2br(e($creador->bio)) !!}</p>
            @endif
        </div>
    </div>

    {{-- EVENTOS --}}
    @if($eventos->count() > 0)
    <section>
        <div class="my-8">
            <x-banner posicion="creador_post_bio" />
        </div>
        <h2 class="text-xs font-bold uppercase tracking-widest mb-6 border-l-4 border-gray-800 pl-3">Participaciones</h2>
        <div class="space-y-4">
            @foreach($eventos as $evento)
            @php
                $img = $evento->mainImage ? Storage::url($evento->mainImage) : ($evento->mainImageUrl ?: null);
                $catColors = ['Artes Visuales'=>'#7B2D8B','Música'=>'#1A3A7C','Teatro'=>'#8B1A2D','Cine'=>'#E67E22','Literatura'=>'#2E8B57'];
                $color = $catColors[$evento->category] ?? '#555';
                $rolEnEvento = collect($evento->bios ?? [])->firstWhere('nombre', $creador->nombre)['rol'] ?? null;
            @endphp
            <a href="{{ route('evento.show', $evento->id) }}" class="flex gap-4 items-start p-4 rounded-xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                @if($img)
                <img src="{{ $img }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                @else
                <div class="w-20 h-20 rounded-lg flex-shrink-0" style="background:#f3f3f3"></div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider" style="color:{{ $color }}">{{ $evento->category }}</span>
                        @if($rolEnEvento)
                        <span class="text-xs text-gray-400">· {{ $rolEnEvento }}</span>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900 leading-snug">{{ $evento->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $evento->locationName }}</p>
                    @if($evento->startDate)
                    <p class="text-xs text-gray-400">{{ $evento->startDate->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @else
    <p class="text-gray-400 text-center py-10">No hay eventos registrados aún.</p>
    @endif

</main>
</x-app-layout>
