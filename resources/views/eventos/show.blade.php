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

    <main class="max-w-4xl mx-auto px-4 py-16 md:py-24">
        <article class="bg-white rounded-[2rem] shadow-boutique p-8 md:p-12 mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">{{ $event->title }}</h1>
            
            @if($event->mainImageUrl)
                <img src="{{ $event->mainImageUrl }}" alt="{{ $event->title }}" class="w-full h-96 object-cover rounded-2xl mb-8 shadow-sm">
            @endif

            <div class="text-lg text-gray-700 leading-relaxed mb-8">
                {!! nl2br(e($event->description)) !!}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Fechas y Horarios</h3>
                    <ul class="text-gray-600 space-y-2">
                        @if($event->inaugurationDate)
                            <li><span class="font-semibold">Inauguración:</span> {{ \Carbon\Carbon::parse($event->inaugurationDate)->locale('es')->isoFormat('dddd D [de] MMMM, H:mm [h]') }}</li>
                        @endif
                        @if($event->startDate && $event->endDate)
                            <li><span class="font-semibold">Del:</span> {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D [de] MMMM') }}</li>
                            <li><span class="font-semibold">Al:</span> {{ \Carbon\Carbon::parse($event->endDate)->locale('es')->isoFormat('D [de] MMMM') }}</li>
                        @elseif($event->startDate)
                            <li><span class="font-semibold">Desde:</span> {{ \Carbon\Carbon::parse($event->startDate)->locale('es')->isoFormat('D [de] MMMM') }}</li>
                        @elseif($event->singleDate)
                             <li><span class="font-semibold">Fecha única:</span> {{ \Carbon\Carbon::parse($event->singleDate)->locale('es')->isoFormat('dddd D [de] MMMM, H:mm [h]') }}</li>
                        @endif
                        @if($event->venueHours)
                            <li><span class="font-semibold">Horario del lugar:</span> {{ $event->venueHours }}</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Lugar y Contacto</h3>
                    <ul class="text-gray-600 space-y-2">
                        @if($event->locationName)
                            <li><span class="font-semibold">Nombre:</span> {{ $event->locationName }}</li>
                        @endif
                        @if($event->venueAddress)
                            <li><span class="font-semibold">Dirección:</span> {{ $event->venueAddress }}</li>
                        @endif
                        @if($event->room)
                            <li><span class="font-semibold">Sala:</span> {{ $event->room }}</li>
                        @endif
                        @if($event->venuePhone)
                            <li><span class="font-semibold">Teléfono:</span> {{ $event->venuePhone }}</li>
                        @endif
                        @if($event->venueEmail)
                            <li><span class="font-semibold">Email:</span> {{ $event->venueEmail }}</li>
                        @endif
                        @if($event->venueWebsite)
                            <li><span class="font-semibold">Web:</span> <a href="{{ $event->venueWebsite }}" target="_blank" class="text-blue-600 hover:underline">{{ $event->venueWebsite }}</a></li>
                        @endif
                        @if($event->venueSocial)
                            <li><span class="font-semibold">Redes:</span> {{ $event->venueSocial }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($event->artists || $event->curators || $event->artistBio)
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Artistas y Curadores</h3>
                    <ul class="text-gray-600 space-y-2">
                        @if($event->artists)
                            <li><span class="font-semibold">Artistas:</span> {{ implode(', ', json_decode($event->artists, true)) }}</li>
                        @endif
                        @if($event->curators)
                            <li><span class="font-semibold">Curadores:</span> {{ implode(', ', json_decode($event->curators, true)) }}</li>
                        @endif
                        @if($event->artistBio)
                            <li><span class="font-semibold">Biografía:</span> {!! nl2br(e(implode(', ', json_decode($event->artistBio, true)))) !!}</li>
                        @endif
                    </ul>
                </div>
            @endif

            @if($event->priceInfo || $event->ticketUrl || $event->catalogPdfUrl)
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Extras</h3>
                    <ul class="text-gray-600 space-y-2">
                        @if($event->priceInfo)
                            <li><span class="font-semibold">Precio:</span> {{ $event->priceInfo }}</li>
                        @endif
                        @if($event->ticketUrl)
                            <li><span class="font-semibold">Tickets:</span> <a href="{{ $event->ticketUrl }}" target="_blank" class="text-blue-600 hover:underline">Comprar entradas</a></li>
                        @endif
                        @if($event->catalogPdfUrl)
                            <li><span class="font-semibold">Catálogo:</span> <a href="{{ $event->catalogPdfUrl }}" target="_blank" class="text-blue-600 hover:underline">Descargar PDF</a></li>
                        @endif
                    </ul>
                </div>
            @endif
        </article>
    </main>
</x-app-layout>
