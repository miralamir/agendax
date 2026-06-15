@props(['posicion'])
@php
    $banner = \App\Models\Banner::paraPosicion($posicion);
    // medidas por zona (estandar AdSense)
    $medidas = [
        'home_hero_izq' => [300, 250],
        'home_hero_der' => [300, 250],
        'home_post_destacados' => [728, 90],
        'home_post_mapa' => [970, 250],
        'cat_entre_secciones' => [728, 90],
        'articulo_izq' => [160, 600],
        'articulo_der' => [160, 600],
        'articulo_post_breadcrumb' => [728, 90],
        'articulo_pre_comentarios' => [728, 90],
        'creador_post_breadcrumb' => [728, 90],
        'creador_post_bio' => [728, 90],
        'creador_izq' => [160, 600],
        'creador_der' => [160, 600],
    ];
    [$w, $h] = $medidas[$posicion] ?? [728, 90];
    $src = $banner ? (\Illuminate\Support\Str::startsWith($banner->imagen, 'http') ? $banner->imagen : \Illuminate\Support\Facades\Storage::url($banner->imagen)) : null;
@endphp

@if($banner)
    <div class="banner-zona my-4 mx-auto" style="max-width: {{ $w }}px;">
        @if($banner->link)
        <a href="{{ route('banner.click', $banner) }}" {{ $banner->nueva_pestana ? 'target=_blank rel=noopener' : '' }} class="block">
            <img src="{{ $src }}" alt="{{ $banner->titulo ?? 'Publicidad' }}"
                 style="width:100%; aspect-ratio: {{ $w }} / {{ $h }}; object-fit: cover;"
                 class="rounded-lg">
        </a>
        @else
        <img src="{{ $src }}" alt="{{ $banner->titulo ?? 'Publicidad' }}"
             style="width:100%; aspect-ratio: {{ $w }} / {{ $h }}; object-fit: cover;"
             class="rounded-lg">
        @endif
    </div>
@else
    {{-- Hueco AdSense (vacío hasta producción). El código de Google se pega acá. --}}
    {{-- @production --}}
    {{-- <ins class="adsbygoogle" style="display:inline-block;width:{{ $w }}px;height:{{ $h }}px" data-ad-client="ca-pub-XXXX" data-ad-slot="XXXX"></ins> --}}
    {{-- @endproduction --}}
@endif
