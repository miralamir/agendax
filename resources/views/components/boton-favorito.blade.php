@props(['tipo', 'item'])
@php
    $yaGuardado = auth()->check() && auth()->user()->tieneFavorito($item);
@endphp
@auth
    <form action="{{ route('favorito.toggle') }}" method="POST" class="inline-block">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <input type="hidden" name="id" value="{{ $item->id }}">
        <button type="submit"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition border
                   {{ $yaGuardado ? 'bg-red-50 text-red-600 border-red-200' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
            <svg class="w-4 h-4" fill="{{ $yaGuardado ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            {{ $yaGuardado ? 'Guardado en mi agenda' : 'Guardar en mi agenda' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        Guardar en mi agenda
    </a>
@endauth
