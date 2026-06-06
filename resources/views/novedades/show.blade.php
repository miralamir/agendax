<x-app-layout>
    <main>
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Imagen Principal con Overlay de Título -->
            @if ($novedad->image)
                <div class="relative w-full h-96 bg-gray-200 rounded-lg overflow-hidden mb-8">
                    <img src="{{ Storage::url($novedad->image) }}" alt="{{ $novedad->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
                        <div>
                            <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight">{{ $novedad->title }}</h1>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Categoría, Autor y Fecha -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-8">
                @if ($novedad->category)
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-{{ strtolower(str_replace(' ', '', $novedad->category ?? '')) }}">
                        {{ $novedad->category }}
                    </span>
                @endif
                @if ($novedad->subCategory)
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-700">
                        {{ $novedad->subCategory }}
                    </span>
                @endif
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

            <!-- Cuerpo del Artículo -->
            <div class="prose prose-lg max-w-none mb-12 text-gray-800">
                {!! $novedad->body !!}
            </div>

            <!-- Galería de Imágenes -->
            @if ($novedad->gallery && count($novedad->gallery) > 0)
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Galería</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-12">
                    @foreach ($novedad->gallery as $image)
                        <img src="{{ Storage::url($image) }}" alt="Galería" class="w-full h-48 object-cover rounded-lg shadow-md">
                    @endforeach
                </div>
            @endif

            <!-- Videos Embebidos -->
            @if ($novedad->videos && count($novedad->videos) > 0)
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Videos</h3>
                <div class="space-y-8 mb-12">
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
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Documento Adjunto</h3>
                <div class="mb-12">
                    <a href="{{ Storage::url($novedad->pdf) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Descargar PDF: {{ basename($novedad->pdf) }}
                    </a>
                    <div class="mt-4">
                        <iframe src="{{ Storage::url($novedad->pdf) }}#toolbar=0" width="100%" height="600px" style="border: none;"></iframe>
                    </div>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ url()->previous() }}" class="dashboard-button-outline">← Volver</a>
            </div>

        </article>
    </main>
</x-app-layout>
