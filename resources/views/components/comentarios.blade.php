@props(['tipo', 'item'])
@php
    $clase = $tipo === 'evento' ? \App\Models\Evento::class : \App\Models\Novedad::class;
    $comentarios = \App\Models\Comentario::where('comentable_id', $item->id)
        ->where('comentable_type', $clase)
        ->where('oculto', false)
        ->whereHas('user', fn($q) => $q->where('baneado', false))
        ->with('user')
        ->latest()
        ->get();
@endphp

<section id="comentarios" class="mt-12 pt-8 border-t border-gray-200">
    <h2 class="text-xl font-black text-gray-900 mb-6">
        Comentarios <span class="text-gray-400 font-normal">({{ $comentarios->count() }})</span>
    </h2>

    {{-- Formulario --}}
    @auth
        @if(auth()->user()->puedeComentar())
        <form action="{{ route('comentario.store') }}" method="POST" class="mb-8">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">
            <input type="hidden" name="id" value="{{ $item->id }}">
            <textarea name="body" rows="3" required maxlength="2000"
                placeholder="Escribí tu comentario..."
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"></textarea>
            <div class="mt-2 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-bold rounded-full hover:bg-gray-700 transition">
                    Comentar
                </button>
            </div>
        </form>
        @else
        <p class="mb-8 text-sm text-gray-400 italic">Tu cuenta no puede comentar en este momento.</p>
        @endif
    @else
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <p class="text-sm text-gray-600">
                <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Iniciá sesión</a>
                para dejar un comentario.
            </p>
        </div>
    @endauth

    {{-- Listado --}}
    @if($comentarios->isEmpty())
        <p class="text-sm text-gray-400">Todavía no hay comentarios. ¡Sé el primero!</p>
    @else
        <div class="space-y-5">
            @foreach($comentarios as $c)
            <div class="flex gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold flex-shrink-0">
                    {{ strtoupper(substr($c->user->name ?? '?', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-gray-900">{{ $c->user->name ?? 'Usuario' }}</span>
                        <span class="text-xs text-gray-400">{{ $c->created_at->locale('es')->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $c->body }}</p>
                    @auth
                        @if($c->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form action="{{ route('comentario.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este comentario?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 mt-1">Eliminar</button>
                        </form>
                        @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>
