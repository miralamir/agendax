<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
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
        <p class="col-span-full text-center text-gray-500 font-light">No hay eventos disponibles.</p>
    @endforelse
</div>
