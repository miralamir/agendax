<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    @forelse($events as $event)
        <a href="{{ route('evento.show', $event->id) }}" class="group block bg-white rounded-lg border border-[var(--border-color)] hover:translate-y-[-2px] hover:shadow-[0_4px_12px_rgba(0,0,0,0.09)] transition-all duration-300 overflow-hidden">
            <div class="relative h-48 overflow-hidden">
                @if($event->mainImageUrl)
                    <img src="{{ $event->mainImageUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Sin imagen</div>
                @endif
            </div>
            <div class="p-6">
                <span class="text-xs font-bold uppercase tracking-wider mb-2 block" style="color: var(--color-{{ strtolower(str_replace(' ', '', $event->category->name)) }})">
                    {{ $event->category->name }}
                </span>
                <h4 class="text-xl font-bold mb-2 text-gray-800 leading-tight">{{ $event->title }}</h4>
                <p class="text-sm text-gray-500 font-normal">
                    @if($event->startDate && $event->endDate)
                        {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($event->endDate)->locale('es')->isoFormat('D MMM') }}
                    @elseif($event->startDate)
                        {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D MMM') }}
                    @elseif($event->singleDate)
                        {{ \Carbon\Carbon::parse($event->singleDate)->locale('es')->isoFormat('D MMM') }}
                    @else
                        Fecha a confirmar
                    @endif
                    @if($event->locationName)
                     | {{ $event->locationName }}
                    @endif
                </p>
            </div>
        </a>
    @empty
        <p class="col-span-full text-center text-gray-500 font-light">No hay eventos disponibles.</p>
    @endforelse
</div>
