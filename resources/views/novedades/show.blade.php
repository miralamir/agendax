<x-app-layout>
    @php
        $catRoute = ['Artes Visuales'=>'arte','Música'=>'musica','Teatro'=>'teatro','Cine'=>'cine','Literatura'=>'literatura'][$novedad->category] ?? 'arte';
        $subUrl = $novedad->subCategory ? route($catRoute) . '?sub=' . urlencode($novedad->subCategory) : null;
    @endphp
    <x-breadcrumb :items="[
        $novedad->category => route($catRoute),
        $novedad->subCategory => $subUrl
    ]"/>
<!-- Banners laterales (solo desktop ancho) -->
<div class="hidden 2xl:block fixed left-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="articulo_izq" />
</div>
<div class="hidden 2xl:block fixed right-4 top-1/2 -translate-y-1/2 z-10">
    <x-banner posicion="articulo_der" />
</div>
    <main>
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-6">
                <x-banner posicion="articulo_post_breadcrumb" />
            </div>
            <!-- Imagen Principal con Overlay de Título -->
            @if ($novedad->image)
                <div class="relative w-full h-96 bg-gray-200 rounded-lg overflow-hidden mb-8">
                    <img src="{{ Storage::url($novedad->image) }}" alt="{{ $novedad->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex items-end p-8">
                        <div>
                            <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight">{{ $novedad->title }}</h1>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Autor y Fecha -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-8">
                @if ($novedad->author)
                    <span>Por {{ $novedad->author }}</span>
                @endif
                @if ($novedad->published_at)
                    <span>{{ $novedad->published_at->locale('es')->isoFormat('D MMMM YYYY') }}</span>
                @endif
            </div>

            <!-- Bajada/Resumen -->
            @if ($novedad->excerpt)
                <p class="text-xl text-gray-800 font-normal leading-relaxed mb-8">{{ $novedad->excerpt }}</p>
            @endif

            <!-- Galería como carrusel -->
            @if ($novedad->gallery && count($novedad->gallery) > 0)
            <div class="mb-8">
                <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
                <div class="swiper novedad-swiper rounded-xl overflow-hidden max-w-2xl mx-auto">
                    <div class="swiper-wrapper">
                        @foreach ($novedad->gallery as $image)
                        @php $imgUrl = is_array($image) ? ($image["url"] ?? "") : $image; $imgCap = is_array($image) ? ($image["caption"] ?? "") : ""; @endphp
                        @if($imgUrl)
                        <div class="swiper-slide">
                            <div class="bg-gray-100 flex items-center justify-center" style="max-height:420px;min-height:200px;">
                                <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : Storage::url($imgUrl) }}" class="max-h-[420px] w-full object-contain cursor-zoom-in" alt="{{ $imgCap }}" onclick="openLightbox(this.src, this.dataset.caption)" data-caption="{{ $imgCap }}">
                            </div>
                            @if($imgCap)
                            <div class="px-2 py-2 text-center" style="background:#f9f9f9;border-top:1px solid #eee;">
                                <p class="text-gray-600 text-xs">{{ $imgCap }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
                <script>document.addEventListener("DOMContentLoaded",function(){new Swiper(".novedad-swiper",{loop:true,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}});});</script>
                <style>.novedad-swiper .swiper-button-prev,.novedad-swiper .swiper-button-next{color:white;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.8));}</style>
            </div>
            @endif
            <!-- Cuerpo del Artículo -->
            <div class="prose prose-lg max-w-none mb-12 text-gray-800">
                {!! \App\Helpers\TextHelper::autoLink($novedad->body) !!}
            </div>

            <!-- Videos Embebidos -->
            @if ($novedad->videos && count($novedad->videos) > 0)
                <div class="space-y-4 max-w-2xl mx-auto mb-12">
                    @foreach ($novedad->videos as $videoUrl)
                        @php
                            $videoId = null;
                            $platform = null;

                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $videoUrl, $match)) {
                                $videoId = $match[1];
                                $platform = 'youtube';
                            } elseif (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]+\/)?videos\/|album\/\d+\/video\/|video\/|)(\d+)/i', $videoUrl, $match)) {
                                $videoId = $match[1];
                                $platform = 'vimeo';
                            }
                        @endphp

                        @if ($videoId && $platform == 'youtube')
                            <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @elseif ($videoId && $platform == 'vimeo')
                            <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                <iframe class="absolute top-0 left-0 w-full h-full" src="https://player.vimeo.com/video/{{ $videoId }}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- PDF Adjunto -->
            @if ($novedad->pdf)
                <div class="mb-12">
                    <a href="{{ (str_starts_with($novedad->pdf, "http") ? $novedad->pdf : Storage::url($novedad->pdf)) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Descargar PDF: {{ basename($novedad->pdf) }}
                    </a>
                    <div class="mt-4">
                        <iframe src="{{ (str_starts_with($novedad->pdf, "http") ? $novedad->pdf : Storage::url($novedad->pdf)) }}#toolbar=0" width="100%" height="600px" style="border: none;"></iframe>
                    </div>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ url()->previous() }}" class="dashboard-button-outline">← Volver</a>
            </div>

            <!-- Guardar en agenda -->
            <div class="mt-10 pt-8 border-t border-gray-200">
                <x-boton-favorito tipo="novedad" :item="$novedad" />
            </div>
        </article>
        <div class="max-w-3xl mx-auto px-4 pb-12">
            <div class="mb-8">
                <x-banner posicion="articulo_pre_comentarios" />
            </div>
            <x-comentarios tipo="novedad" :item="$novedad" />
        </div>
    </main>

<style>
#lightbox-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;}
#lightbox-overlay.active{display:flex;}
#lightbox-overlay img{max-width:90vw;max-height:90vh;object-fit:contain;border-radius:8px;}
#lightbox-close{position:fixed;top:20px;right:24px;color:white;font-size:32px;cursor:pointer;z-index:10000;}
</style>
<div id="lightbox-overlay" onclick="closeLightbox()">
    <span id="lightbox-close">×</span>
    <div style="display:flex;flex-direction:column;align-items:center;max-width:90vw;">
        <img id="lightbox-img" src="" alt="">
        <p id="lightbox-caption" style="color:white;font-size:14px;margin-top:12px;text-align:center;display:none;"></p>
    </div>
</div>
<script>
function openLightbox(src,caption){
    document.getElementById("lightbox-img").src=src;
    const cap=document.getElementById("lightbox-caption");
    if(cap){cap.textContent=caption||"";cap.style.display=caption?"block":"none";}
    document.getElementById("lightbox-overlay").classList.add("active");
    document.body.style.overflow="hidden";
}
function closeLightbox(){
    document.getElementById("lightbox-overlay").classList.remove("active");
    document.body.style.overflow="";
}
document.addEventListener("keydown",e=>{if(e.key==="Escape")closeLightbox();});
</script>
</x-app-layout>
