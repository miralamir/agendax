<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #FAFAFA; color: #1A1A1A; }
        .shadow-boutique { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08); }
    </style>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-12 capitalize">Agenda: {{ $category }}</h1>

        <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($events as $event)
                <a href="{{ route('evento.show', $event->id) }}" class="group block bg-white rounded-2xl shadow-boutique hover-lift transition-all duration-500 overflow-hidden border border-gray-50">
                    <div class="relative h-64 overflow-hidden">
                        @if($event->mainImageUrl)
                            <img src="{{ $event->mainImageUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-xl">Sin imagen</div>
                        @endif
                    </div>
                    <div class="p-8">
                        <div class="text-xs font-bold text-gray-400 mb-3 tracking-widest uppercase">
                            @if($event->startDate && $event->endDate)
                                {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($event->endDate)->locale('es')->isoFormat('D MMM') }}
                            @elseif($event->startDate)
                                {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }}
                            @elseif($event->singleDate)
                                {{ \Carbon\Carbon::parse($event->singleDate)->locale('es')->isoFormat('D MMM') }}
                            @else
                                Próximamente
                            @endif
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900 leading-tight">{{ $event->title }}</h4>
                        <p class="text-gray-500 font-light mb-6 line-clamp-2">{{ $event->locationName ?? '' }}</p>
                        <div class="inline-flex items-center space-x-2 text-sm font-bold text-gray-900 border-b border-gray-900 pb-1 group-hover:text-gray-500 group-hover:border-gray-500 transition-colors">
                            <span>Ver detalles</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 font-light">No hay eventos disponibles en esta categoría.</p>
            @endforelse
        </div>
    </main>
</x-app-layout>
