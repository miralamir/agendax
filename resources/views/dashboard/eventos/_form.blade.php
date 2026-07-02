@if ($errors->any())
<div class="col-span-1 md:col-span-2 bg-red-50 border border-red-300 text-red-700 rounded-lg p-4 mb-4">
    <p class="font-bold mb-2">No se pudo guardar. Revisá estos campos:</p>
    <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@php
    $evento = $evento ?? new App\Models\Evento();
    $galleryText = ''; // galería ahora es array de objetos, no texto
    $bios = old('bios', $evento->bios ?? []);
    if (is_string($bios)) $bios = json_decode($bios, true) ?? [];
    if (empty($bios)) $bios = [['nombre' => '', 'rol' => '', 'bio' => '', 'foto' => '']];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- 1. INFORMACIÓN BÁSICA -->
    <div class="col-span-1 md:col-span-2">
        <h3 class="dashboard-section-title">Información Básica</h3>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="title" class="dashboard-label">Título del Evento *</label>
        <input type="text" name="title" id="title" required value="{{ old('title', $evento->title) }}" class="mt-1 block w-full dashboard-input">
        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="artist" class="dashboard-label">Artistas</label>
        <input type="text" name="artist" id="artist" value="{{ old('artist', $evento->artist) }}" placeholder="Ej: Pablo Reinoso, León Ferrari" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="curator" class="dashboard-label">Curadores</label>
        <input type="text" name="curator" id="curator" value="{{ old('curator', $evento->curator) }}" placeholder="Ej: Virna Gvero" class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="description" class="dashboard-label">Descripción del Evento</label>
        @php
            $descRaw = old('description', $evento->description ?? '');
            $descInitialHtml = \App\Helpers\TextHelper::toEditableHtml($descRaw);
        @endphp
        <div id="toolbar-description" class="flex flex-wrap gap-1 mb-1 border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-1">
            <button type="button" class="ql-bold" title="Negrita"></button>
            <button type="button" class="ql-italic" title="Itálica"></button>
            <button type="button" class="ql-underline" title="Subrayado"></button>
            <button type="button" class="ql-list" value="ordered" title="Lista numerada"></button>
            <button type="button" class="ql-list" value="bullet" title="Lista con viñetas"></button>
            <button type="button" class="ql-link" title="Hipervínculo"></button>
            <button type="button" id="btn-uppercase-description" title="Mayúsculas/minúsculas" class="font-bold text-xs px-2">Aa</button>
        </div>
        <div id="quill-description" class="bg-white border border-gray-300 rounded-b-md" style="min-height:150px;">{!! $descInitialHtml !!}</div>
        <input type="hidden" name="description" id="input-description" value="{{ old('description', $evento->description) }}">
    </div>

    <!-- BIOGRAFÍAS MÚLTIPLES -->
    <div class="col-span-1 md:col-span-2 mt-2">
        <div class="flex items-center justify-between mb-3">
            <label class="dashboard-label">Biografías</label>
            <button type="button" onclick="agregarBio()" class="dashboard-button-outline text-sm">+ Agregar persona</button>
        </div>
        <div id="bios-container" class="space-y-4">
            @foreach($bios as $i => $bio)
            <div class="bio-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <div>
                        <label class="dashboard-label">Nombre</label>
                        <input type="text" name="bios[{{ $i }}][nombre]" value="{{ $bio['nombre'] ?? '' }}" placeholder="Nombre completo" class="mt-1 block w-full dashboard-input">
                    </div>
                    <div>
                        <label class="dashboard-label">Rol</label>
                        <select name="bios[{{ $i }}][rol]" class="mt-1 block w-full dashboard-input">
                            <option value="">Seleccionar...</option>
                            @foreach(['Artista', 'Curador/a', 'Productor/a', 'Director/a', 'Musico/a', 'Actor/Actriz', 'Escritor/a', 'Fotografo/a', 'Otro'] as $rol)
                                <option value="{{ $rol }}" {{ ($bio['rol'] ?? '') == $rol ? 'selected' : '' }}>{{ $rol }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dashboard-label">Foto</label>
                        <div class="flex gap-2 items-center mt-1">
                            <div class="flex-1">
                                <input type="file" name="bioFotos[{{ $i }}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewBioFoto(this, 'bio-foto-{{ $i }}')">
                                <input type="text" name="bios[{{ $i }}][foto]" value="{{ $bio['foto'] ?? '' }}" placeholder="O URL..." class="mt-1 block w-full dashboard-input text-xs">
                            </div>
                            @if(!empty($bio['foto']))
                                <img id="bio-foto-{{ $i }}" src="{{ str_starts_with($bio['foto'], 'http') ? $bio['foto'] : Storage::url($bio['foto']) }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border-2 border-gray-200">
                            @else
                                <img id="bio-foto-{{ $i }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border-2 border-gray-200 hidden">
                            @endif
                        </div>
                    </div>
                </div>
                @php $bioHtml = \App\Helpers\TextHelper::toEditableHtml($bio['bio'] ?? ''); @endphp
                <div>
                    <label class="dashboard-label">Biografía</label>
                    <div id="toolbar-bio-{{ $i }}" class="flex flex-wrap gap-1 mb-1 border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-1">
                        <button type="button" class="ql-bold" title="Negrita"></button>
                        <button type="button" class="ql-italic" title="Itálica"></button>
                        <button type="button" class="ql-underline" title="Subrayado"></button>
                        <button type="button" class="ql-list" value="ordered" title="Lista numerada"></button>
                        <button type="button" class="ql-list" value="bullet" title="Lista con viñetas"></button>
                        <button type="button" class="ql-link" title="Hipervínculo"></button>
                        <button type="button" class="btn-uppercase-bio font-bold text-xs px-2" data-idx="{{ $i }}" title="Mayúsculas/minúsculas">Aa</button>
                    </div>
                    <div id="quill-bio-{{ $i }}" class="quill-bio-editor bg-white border border-gray-300 rounded-b-md" style="min-height:100px;">{!! $bioHtml !!}</div>
                    <input type="hidden" name="bios[{{ $i }}][bio]" id="input-bio-{{ $i }}" value="{{ $bio['bio'] ?? '' }}">
                </div>
                @if($loop->index > 0)
                <div class="flex justify-end mt-2">
                    <button type="button" onclick="eliminarBio(this, {{ $i }})" class="text-red-500 text-sm hover:text-red-700">× Eliminar</button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- 2. MULTIMEDIA -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Multimedia y URLs</h3>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label class="dashboard-label">Imagen Principal</label>
        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                <input type="file" name="mainImage" accept="image/*" class="block w-full dashboard-input p-1" onchange="previewImg(this, 'prev-main')">
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">O pegar URL</p>
                <input type="url" name="mainImageUrl" value="{{ old('mainImageUrl', $evento->mainImageUrl) }}" placeholder="https://..." class="block w-full dashboard-input">
            </div>
        </div>
        @if($evento->mainImage || $evento->mainImageUrl)
        <div class="mt-2 flex items-center gap-3">
            <img id="prev-main" src="{{ $evento->mainImage ? Storage::url($evento->mainImage) : $evento->mainImageUrl }}" class="h-20 w-auto rounded object-cover border border-gray-200">
            <span class="text-xs text-gray-400">Imagen actual</span>
        </div>
        @else
        <img id="prev-main" class="mt-2 h-20 w-auto rounded object-cover border border-gray-200 hidden">
        @endif
    </div>

    
    <div class="col-span-1 md:col-span-2 mt-4">
        <div class="flex items-center justify-between mb-3">
            <label class="dashboard-label">Galería de Imágenes</label>
            <div class="flex items-center gap-2">
                <input type="file" id="galeriaMultiInput" accept="image/*" multiple class="hidden" onchange="cargarVariasImagenes(this)">
                <button type="button" onclick="document.getElementById('galeriaMultiInput').click()" class="dashboard-button-outline text-sm">⬆ Subir varias</button>
                <button type="button" onclick="agregarImagenGaleria()" class="dashboard-button-outline text-sm">+ Agregar imagen</button>
            </div>
        </div>
        <div id="galeria-container" class="space-y-3">
            @php $galeriaItems = is_array($evento->gallery) ? $evento->gallery : []; @endphp
            @foreach($galeriaItems as $gi => $gitem)
            @php
                $gurl = is_array($gitem) ? ($gitem["url"] ?? "") : $gitem;
                $gcap = is_array($gitem) ? ($gitem["caption"] ?? "") : "";
            @endphp
            <div class="galeria-item border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                        <input type="file" name="galleryFiles[{{ $gi }}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewGaleriaItem(this, 'gal-prev-{{ $gi }}')">
                        <input type="text" name="gallery[{{ $gi }}][url]" value="{{ $gurl }}" placeholder="O pegar URL..." class="mt-1 block w-full dashboard-input text-xs">
                        @if($gurl)
                        <img id="gal-prev-{{ $gi }}" src="{{ str_starts_with($gurl, 'http') ? $gurl : Storage::url($gurl) }}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200">
                        @else
                        <img id="gal-prev-{{ $gi }}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200 hidden">
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Epígrafe (opcional)</p>
                        <textarea name="gallery[{{ $gi }}][caption]" rows="3" placeholder="Título, artista, año, técnica..." class="block w-full dashboard-input text-xs">{{ $gcap }}</textarea>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex gap-1">
                        <button type="button" onclick="moverGaleriaItem(this,-1)" title="Subir" class="px-2 py-1 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded text-sm">▲</button>
                        <button type="button" onclick="moverGaleriaItem(this,1)" title="Bajar" class="px-2 py-1 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded text-sm">▼</button>
                    </div>
                    <button type="button" onclick="this.closest('.galeria-item').remove()" class="text-red-500 text-xs hover:text-red-700">× Eliminar</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- 3. CATEGORIZACIÓN -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Categorización y Visibilidad</h3>
    </div>

    <div>
        <label for="category" class="dashboard-label">Categoría</label>
        <select name="category" id="category" class="mt-1 block w-full dashboard-input" onchange="updateSubcats()">
            <option value="">Seleccionar...</option>
            @foreach(['Artes Visuales', 'Música', 'Teatro', 'Cine', 'Literatura'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $evento->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="subCategory" class="dashboard-label">Sub-Categoría</label>
        <select name="subCategory" id="subCategory" class="mt-1 block w-full dashboard-input">
            <option value="">Seleccionar...</option>
        </select>
    </div>

    <div class="col-span-1 md:col-span-2 flex items-center space-x-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="flex items-center">
            <input id="isPublished" name="isPublished" type="checkbox" value="1" {{ old('isPublished', $evento->isPublished) ? 'checked' : '' }} class="h-5 w-5 text-green-600 border-gray-300 rounded">
            <label for="isPublished" class="ml-3 dashboard-label">Publicar Evento</label>
        </div>
        <div class="flex items-center">
            <input id="isFeatured" name="isFeatured" type="checkbox" value="1" {{ old('isFeatured', $evento->isFeatured) ? 'checked' : '' }} class="h-5 w-5 text-yellow-500 border-gray-300 rounded">
            <label for="isFeatured" class="ml-3 dashboard-label">⭐ Marcar como Destacado</label>
        </div>
    </div>

    <!-- 4. FECHAS Y HORARIOS -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Fechas y Horarios</h3>
    </div>

    <div>
        <label for="inaugurationDate" class="dashboard-label">Fecha de Inauguración</label>
        <input type="datetime-local" name="inaugurationDate" id="inaugurationDate" value="{{ old('inaugurationDate', $evento->inaugurationDate?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="singleDate" class="dashboard-label">Día Único (Ej: Recital)</label>
        <input type="datetime-local" name="singleDate" id="singleDate" value="{{ old('singleDate', $evento->singleDate?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="startDate" class="dashboard-label">Fecha de Inicio</label>
        <input type="datetime-local" name="startDate" id="startDate" value="{{ old('startDate', $evento->startDate?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="endDate" class="dashboard-label">Fecha de Fin</label>
        <input type="datetime-local" name="endDate" id="endDate" value="{{ old('endDate', $evento->endDate?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
    </div>

    <!-- FUNCIONES PROGRAMADAS (obras/ciclos con varias fechas) -->
    <div class="col-span-1 md:col-span-2 mt-4"
         x-data="funcionesProgramadas(@js($evento->funciones_regla ?? null))">
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="activo" class="rounded">
            <span class="dashboard-label !mb-0">Este evento tiene funciones programadas (obra/ciclo con varias fechas)</span>
        </label>

        <div x-show="activo" x-cloak class="mt-3 p-4 border border-purple-200 rounded-lg bg-purple-50 space-y-4">
            <div>
                <label class="dashboard-label">Días de función</label>
                <div class="flex flex-wrap gap-2 mt-1">
                    <template x-for="(nombre, idx) in diasNombres" :key="idx">
                        <label class="inline-flex items-center gap-1 px-3 py-1 bg-white border rounded-lg cursor-pointer text-sm">
                            <input type="checkbox" :value="idx" x-model.number="dias" class="rounded">
                            <span x-text="nombre"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div>
                <p class="text-xs text-purple-700 bg-purple-100 rounded px-3 py-2 mb-1">
                    📅 Definí el período en que se repiten las funciones. <strong>Estas fechas son independientes</strong> de "Fecha de Inicio / Fin" de más arriba — para una obra con funciones, usá solo estas.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="dashboard-label">Desde (primera función)</label>
                    <input type="date" x-model="desde" class="mt-1 block w-full dashboard-input">
                </div>
                <div>
                    <label class="dashboard-label">Hasta (última función)</label>
                    <input type="date" x-model="hasta" class="mt-1 block w-full dashboard-input">
                </div>
            </div>

            <div>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" x-model="horarioPorDia" class="rounded">
                    <span class="text-sm font-bold text-gray-700">Horario distinto por día</span>
                </label>
            </div>

            <div x-show="!horarioPorDia">
                <label class="dashboard-label">Horario (igual para todos los días)</label>
                <input type="time" x-model="horarioGeneral" class="mt-1 block w-40 dashboard-input">
            </div>

            <div x-show="horarioPorDia" class="space-y-2">
                <template x-for="d in diasOrdenados()" :key="d">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold w-24" x-text="diasNombres[d]"></span>
                        <input type="time" x-model="horariosPorDia[d]" class="dashboard-input w-40">
                    </div>
                </template>
            </div>

            <p class="text-xs text-gray-500" x-show="dias.length && desde && hasta">
                Se generarán las funciones para los días seleccionados entre las fechas indicadas.
            </p>
        </div>

        <!-- campo oculto que viaja al backend con la regla en JSON -->
        <input type="hidden" name="funciones_regla" :value="activo ? reglaJson() : ''">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="venueHours" class="dashboard-label">Horarios Detallados</label>
        <input type="text" name="venueHours" id="venueHours" value="{{ old('venueHours', $evento->venueHours) }}" placeholder="Ej: Lunes a Viernes 10 a 18 hs" class="mt-1 block w-full dashboard-input">
    </div>

    <!-- 5. LUGAR -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Información del Lugar (Venue)</h3>
    </div>

    <div>
        <label for="locationName" class="dashboard-label">Nombre del Lugar</label>
        <input type="text" name="locationName" id="locationName" value="{{ old('locationName', $evento->locationName) }}" placeholder="Ej: MALBA" class="mt-1 block w-full dashboard-input" list="lugares-list">
        <datalist id="lugares-list"></datalist>
    </div>

    <div>
        <label for="room" class="dashboard-label">Sala</label>
        <input type="text" name="room" id="room" value="{{ old('room', $evento->room) }}" placeholder="Ej: Sala 1" class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="venueAddress" class="dashboard-label">Dirección</label>
        <div class="flex gap-2 mt-1">
            <input type="text" name="venueAddress" id="venueAddress" value="{{ old('venueAddress', $evento->venueAddress) }}" placeholder="Ej: Av. Figueroa Alcorta 3415, CABA" class="block w-full dashboard-input">
            <button type="button" onclick="geocodificar()" class="dashboard-button-outline whitespace-nowrap text-sm px-4">📍 Geocodificar</button>
        </div>
        <p class="text-xs text-gray-400 mt-1">Al hacer click en Geocodificar se completarán lat/lng automáticamente.</p>
    </div>

    <div>
        <label for="lat" class="dashboard-label">Latitud</label>
        <input type="number" step="any" name="lat" id="lat" value="{{ old('lat', $evento->lat) }}" placeholder="Ej: -34.5881" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="lng" class="dashboard-label">Longitud</label>
        <input type="number" step="any" name="lng" id="lng" value="{{ old('lng', $evento->lng) }}" placeholder="Ej: -58.4068" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="venuePhone" class="dashboard-label">Teléfono</label>
        <input type="text" name="venuePhone" id="venuePhone" value="{{ old('venuePhone', $evento->venuePhone) }}" placeholder="+54..." class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="venueEmail" class="dashboard-label">Email</label>
        <input type="email" name="venueEmail" id="venueEmail" value="{{ old('venueEmail', $evento->venueEmail) }}" placeholder="contacto@..." class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="venueWebsite" class="dashboard-label">Sitio Web</label>
        <input type="url" name="venueWebsite" id="venueWebsite" value="{{ old('venueWebsite', $evento->venueWebsite) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="venueSocial" class="dashboard-label">Redes Sociales</label>
        <input type="text" name="venueSocial" id="venueSocial" value="{{ old('venueSocial', $evento->venueSocial) }}" placeholder="@usuario" class="mt-1 block w-full dashboard-input">
    </div>

    <!-- 6. EXTRAS -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Extras (Tickets, Precios, Catálogo)</h3>
    </div>

    <div>
        <label for="priceInfo" class="dashboard-label">Información de Precio</label>
        <input type="text" name="priceInfo" id="priceInfo" value="{{ old('priceInfo', $evento->priceInfo) }}" placeholder="Ej: Entrada Libre / $5000" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="ticketUrl" class="dashboard-label">URL para Comprar Entradas</label>
        <input type="url" name="ticketUrl" id="ticketUrl" value="{{ old('ticketUrl', $evento->ticketUrl) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label class="dashboard-label">Catálogo PDF</label>
        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                <input type="file" name="catalogPdf" accept="application/pdf" class="block w-full dashboard-input p-1">
                @if($evento->catalogPdfUrl && !str_starts_with($evento->catalogPdfUrl, 'http') === false)
                <p class="text-xs text-gray-400 mt-1">Actual: <a href="{{ $evento->catalogPdfUrl }}" target="_blank" class="underline">Ver PDF</a></p>
                @elseif($evento->catalogPdf ?? false)
                <p class="text-xs text-gray-400 mt-1">Actual: <a href="{{ Storage::url($evento->catalogPdf) }}" target="_blank" class="underline">Ver PDF</a></p>
                @endif
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">O pegar URL</p>
                <input type="text" name="catalogPdfUrl" value="{{ old('catalogPdfUrl', $evento->catalogPdfUrl) }}" placeholder="https://..." class="block w-full dashboard-input">
            </div>
        </div>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label class="dashboard-label mb-1">Videos (YouTube/Vimeo)</label>
        <div id="evento-video-inputs" class="space-y-2">
            @php $videosEvento = old('videos', $evento->videos ?? []); @endphp
            @if(is_string($videosEvento)) @php $videosEvento = json_decode($videosEvento, true) ?? []; @endphp @endif
            @foreach($videosEvento as $videoUrl)
            <div class="flex items-center gap-2">
                <input type="url" name="videos[]" value="{{ $videoUrl }}" placeholder="https://youtube.com/watch?v=..." class="block w-full dashboard-input">
                <button type="button" onclick="this.parentNode.remove()" class="text-red-500 hover:text-red-700 text-lg">×</button>
            </div>
            @endforeach
        </div>
        <button type="button" onclick="addEventoVideo()" class="mt-2 dashboard-button-outline text-sm">+ Agregar Video</button>
    </div>

</div>

<script>
const subcatsData = {
    'Artes Visuales': ['Agenda', 'Ferias', 'Novedades'],
    'Música':         ['Agenda', 'Lanzamientos', 'Festivales', 'Novedades'],
    'Teatro':         ['Cartelera', 'Festivales', 'Novedades'],
    'Cine':           ['Estrenos', 'Festivales / Ciclos', 'Novedades'],
    'Literatura':     ['Agenda', 'Novedades Editoriales', 'Ferias', 'Noticias'],
};
const currentSub = "{{ old('subCategory', $evento->subCategory ?? '') }}";

function updateSubcats() {
    const cat = document.getElementById('category').value;
    const sel = document.getElementById('subCategory');
    sel.innerHTML = '<option value="">Seleccionar...</option>';
    (subcatsData[cat] || []).forEach(s => {
        const o = document.createElement('option');
        o.value = s; o.textContent = s;
        if (s === currentSub) o.selected = true;
        sel.appendChild(o);
    });
}
updateSubcats();

async function geocodificar() {
    const addr = document.getElementById('venueAddress').value.trim();
    if (!addr) { alert('Ingresá una dirección primero.'); return; }
    const btn = event.target;
    btn.textContent = '⏳ Buscando...';
    btn.disabled = true;
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(addr)}&limit=1`, {
            headers: { 'Accept-Language': 'es', 'User-Agent': 'BAMARTE/1.0' }
        });
        const data = await res.json();
        if (data.length > 0) {
            document.getElementById('lat').value = parseFloat(data[0].lat).toFixed(7);
            document.getElementById('lng').value = parseFloat(data[0].lon).toFixed(7);
            btn.textContent = '✅ Listo';
        } else {
            btn.textContent = '❌ No encontrado';
        }
    } catch(e) {
        btn.textContent = '❌ Error';
    }
    setTimeout(() => { btn.textContent = '📍 Geocodificar'; btn.disabled = false; }, 2000);
}

let bioCount = {{ count($bios) }};
function agregarBio() {
    const i = bioCount++;
    const html = `
    <div class="bio-item border border-gray-200 rounded-lg p-4 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div>
                <label class="dashboard-label">Nombre</label>
                <input type="text" name="bios[${i}][nombre]" placeholder="Nombre completo" class="mt-1 block w-full dashboard-input">
            </div>
            <div>
                <label class="dashboard-label">Rol</label>
                <select name="bios[${i}][rol]" class="mt-1 block w-full dashboard-input">
                    <option value="">Seleccionar...</option>
                    ${['Artista','Curador/a','Productor/a','Director/a','Musico/a','Actor/Actriz','Escritor/a','Fotografo/a','Otro'].map(r => `<option value="${r}">${r}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="dashboard-label">Foto (URL)</label>
                <div class="flex gap-2 items-center mt-1">
                    <div class="flex-1">
                        <input type="file" name="bioFotos[${i}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewBioFoto(this, 'bio-foto-${i}')">
                        <input type="text" name="bios[${i}][foto]" placeholder="O URL..." class="mt-1 block w-full dashboard-input text-xs">
                    </div>
                    <img id="bio-foto-${i}" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border-2 border-gray-200 hidden">
                </div>
            </div>
        </div>
        <div>
            <label class="dashboard-label">Biografía</label>
            <div id="toolbar-bio-${i}" class="flex flex-wrap gap-1 mb-1 border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-1">
                <button type="button" class="ql-bold" title="Negrita"></button>
                <button type="button" class="ql-italic" title="Itálica"></button>
                <button type="button" class="ql-underline" title="Subrayado"></button>
                <button type="button" class="ql-list" value="ordered" title="Lista numerada"></button>
                <button type="button" class="ql-list" value="bullet" title="Lista con viñetas"></button>
                <button type="button" class="ql-link" title="Hipervínculo"></button>
                <button type="button" class="btn-uppercase-bio font-bold text-xs px-2" data-idx="${i}" title="Mayúsculas/minúsculas">Aa</button>
            </div>
            <div id="quill-bio-${i}" class="quill-bio-editor bg-white border border-gray-300 rounded-b-md" style="min-height:100px;"></div>
            <input type="hidden" name="bios[${i}][bio]" id="input-bio-${i}">
        </div>
        <div class="flex justify-end mt-2">
            <button type="button" onclick="eliminarBio(this, ${i})" class="text-red-500 text-sm hover:text-red-700">× Eliminar</button>
        </div>
    </div>`;
    document.getElementById('bios-container').insertAdjacentHTML('beforeend', html);
    initBioQuill(i);
}

// Autocompletar lugares
document.getElementById('locationName').addEventListener('input', async function() {
    const q = this.value.trim();
    if (q.length < 2) return;
    try {
        const res = await fetch(`/dashboard/api/lugares?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        const dl = document.getElementById('lugares-list');
        dl.innerHTML = data.map(l => `<option value="${l.locationName}" data-address="${l.venueAddress}" data-lat="${l.lat}" data-lng="${l.lng}">`).join('');
    } catch(e) {}
});

document.getElementById('locationName').addEventListener('change', function() {
    const dl = document.getElementById('lugares-list');
    const opt = Array.from(dl.options).find(o => o.value === this.value);
    if (opt) {
        if (opt.dataset.address) document.getElementById('venueAddress').value = opt.dataset.address;
        if (opt.dataset.lat) document.getElementById('lat').value = opt.dataset.lat;
        if (opt.dataset.lng) document.getElementById('lng').value = opt.dataset.lng;
    }
});

let galeriaCount = {{ count(is_array($evento->gallery) ? $evento->gallery : []) }};
function agregarImagenGaleria() {
    const i = galeriaCount++;
    const html = `<div class="galeria-item border border-gray-200 rounded-lg p-3 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
            <div>
                <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                <input type="file" name="galleryFiles[${i}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewGaleriaItem(this, 'gal-prev-${i}')">
                <input type="text" name="gallery[${i}][url]" placeholder="O pegar URL..." class="mt-1 block w-full dashboard-input text-xs">
                <img id="gal-prev-${i}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200 hidden">
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Epigrafe (opcional)</p>
                <textarea name="gallery[${i}][caption]" rows="3" placeholder="Titulo, artista, año, tecnica..." class="block w-full dashboard-input text-xs"></textarea>
            </div>
        </div>
        <div class="flex justify-between items-center">
            <div class="flex gap-1">
                <button type="button" onclick="moverGaleriaItem(this,-1)" title="Subir" class="px-2 py-1 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded text-sm">&#9650;</button>
                <button type="button" onclick="moverGaleriaItem(this,1)" title="Bajar" class="px-2 py-1 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded text-sm">&#9660;</button>
            </div>
            <button type="button" onclick="this.closest('.galeria-item').remove()" class="text-red-500 text-xs hover:text-red-700">x Eliminar</button>
        </div>
    </div>`;
    document.getElementById("galeria-container").insertAdjacentHTML("beforeend", html);
}
function cargarVariasImagenes(inputMultiple) {
    const archivos = Array.from(inputMultiple.files || []);
    archivos.forEach(file => {
        agregarImagenGaleria();
        const cont = document.getElementById("galeria-container");
        const item = cont.lastElementChild;
        const fileInput = item.querySelector('input[type=file]');
        const prevImg = item.querySelector('img');
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        const reader = new FileReader();
        reader.onload = e => { if (prevImg) { prevImg.src = e.target.result; prevImg.classList.remove("hidden"); } };
        reader.readAsDataURL(file);
    });
    inputMultiple.value = "";
}

function moverGaleriaItem(boton, direccion) {
    const item = boton.closest(".galeria-item");
    const cont = document.getElementById("galeria-container");
    if (!item || !cont) return;
    if (direccion === -1 && item.previousElementSibling) {
        cont.insertBefore(item, item.previousElementSibling);
    } else if (direccion === 1 && item.nextElementSibling) {
        cont.insertBefore(item.nextElementSibling, item);
    }
}

// Al enviar el form, re-numerar los campos de galeria segun el orden visual
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    if (!form) return;
    form.addEventListener("submit", function() {
        const items = document.querySelectorAll("#galeria-container .galeria-item");
        items.forEach((item, idx) => {
            const fileInput = item.querySelector('input[type=file]');
            const urlInput = item.querySelector('input[type=text][name^="gallery"]');
            const captionInput = item.querySelector('textarea[name^="gallery"]');
            if (fileInput) fileInput.name = "galleryFiles[" + idx + "]";
            if (urlInput) urlInput.name = "gallery[" + idx + "][url]";
            if (captionInput) captionInput.name = "gallery[" + idx + "][caption]";
        });
    });
});

function previewGaleriaItem(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            if (img) { img.src = e.target.result; img.classList.remove("hidden"); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function addEventoVideo(val = "") {
    const div = document.createElement("div");
    div.className = "flex items-center gap-2";
    div.innerHTML = `<input type="url" name="videos[]" value="${val}" placeholder="https://youtube.com/watch?v=..." class="block w-full dashboard-input"><button type="button" onclick="this.parentNode.remove()" class="text-red-500 hover:text-red-700 text-lg">×</button>`;
    document.getElementById("evento-video-inputs").appendChild(div);
}
function previewBioFoto(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            if (img) { img.src = e.target.result; img.classList.remove("hidden"); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.classList.remove("hidden");
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewGallery(input) {
    const container = document.getElementById("gallery-preview");
    container.innerHTML = "";
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.className = "h-16 w-16 object-cover rounded border border-gray-200";
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

function funcionesProgramadas(reglaInicial) {
    return {
        diasNombres: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
        activo: !!reglaInicial,
        dias: (reglaInicial && Array.isArray(reglaInicial.dias)) ? reglaInicial.dias.map(Number) : [],
        desde: reglaInicial?.desde ?? '',
        hasta: reglaInicial?.hasta ?? '',
        horarioPorDia: false,
        horarioGeneral: '',
        horariosPorDia: {},

        init() {
            // Prellenar horarios desde la regla guardada (al editar)
            const h = reglaInicial?.horarios;
            if (h) {
                if (h.general !== undefined && h.general !== null) {
                    this.horarioGeneral = (h.general || '').slice(0,5);
                    this.horarioPorDia = false;
                } else {
                    // horarios por dia
                    this.horarioPorDia = true;
                    Object.keys(h).forEach(k => {
                        if (k !== 'general') this.horariosPorDia[k] = (h[k] || '').slice(0,5);
                    });
                }
            }
        },

        diasOrdenados() {
            return [...this.dias].map(Number).sort((a,b) => a-b);
        },

        reglaJson() {
            let horarios;
            if (this.horarioPorDia) {
                horarios = {};
                this.diasOrdenados().forEach(d => { horarios[d] = this.horariosPorDia[d] || null; });
            } else {
                horarios = { general: this.horarioGeneral || null };
            }
            return JSON.stringify({
                desde: this.desde,
                hasta: this.hasta,
                dias: this.dias.map(Number),
                horarios: horarios
            });
        }
    }
}


</script>

{{-- Confirmación al cancelar si hay cambios sin guardar --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('evento-form');
    if (!form) return;
    var dirty = false;
    form.addEventListener('input', function () { dirty = true; });
    form.addEventListener('change', function () { dirty = true; });
    document.querySelectorAll('.btn-cancelar').forEach(function (a) {
        a.addEventListener('click', function (e) {
            if (dirty && !confirm('Hay cambios sin guardar. ¿Querés descartarlos?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var quillDescription = new Quill('#quill-description', {
        theme: 'snow',
        modules: { toolbar: '#toolbar-description' },
        placeholder: 'Descripción del evento...'
    });
    var inputDescription = document.getElementById('input-description');
    quillDescription.on('text-change', function () {
        var html = quillDescription.root.innerHTML;
        inputDescription.value = (html === '<p><br></p>') ? '' : html;
        // Disparar 'input' real para que el chequeo de "cambios sin guardar" (Cancelar) lo detecte
        inputDescription.dispatchEvent(new Event('input', { bubbles: true }));
    });
    document.getElementById('btn-uppercase-description').addEventListener('click', function () {
        var range = quillDescription.getSelection();
        if (!range || range.length === 0) return;
        var text = quillDescription.getText(range.index, range.length);
        var formats = quillDescription.getFormat(range.index, range.length);
        var isAllUpper = text === text.toUpperCase() && text !== text.toLowerCase();
        var newText = isAllUpper ? text.toLowerCase() : text.toUpperCase();
        quillDescription.deleteText(range.index, range.length, 'user');
        quillDescription.insertText(range.index, newText, formats, 'user');
        quillDescription.setSelection(range.index, newText.length, 'silent');
    });
});
</script>

<script>
window.bioQuillInstances = {};

function initBioQuill(idx) {
    var quill = new Quill('#quill-bio-' + idx, {
        theme: 'snow',
        modules: { toolbar: '#toolbar-bio-' + idx },
        placeholder: 'Biografía...'
    });
    window.bioQuillInstances[idx] = quill;
    var input = document.getElementById('input-bio-' + idx);
    quill.on('text-change', function () {
        var html = quill.root.innerHTML;
        input.value = (html === '<p><br></p>') ? '' : html;
        input.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

function eliminarBio(btn, idx) {
    delete window.bioQuillInstances[idx];
    btn.closest('.bio-item').remove();
}

document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-uppercase-bio');
    if (!btn) return;
    var quill = window.bioQuillInstances[btn.dataset.idx];
    if (!quill) return;
    var range = quill.getSelection();
    if (!range || range.length === 0) return;
    var text = quill.getText(range.index, range.length);
    var formats = quill.getFormat(range.index, range.length);
    var isAllUpper = text === text.toUpperCase() && text !== text.toLowerCase();
    var newText = isAllUpper ? text.toLowerCase() : text.toUpperCase();
    quill.deleteText(range.index, range.length, 'user');
    quill.insertText(range.index, newText, formats, 'user');
    quill.setSelection(range.index, newText.length, 'silent');
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quill-bio-editor').forEach(function (el) {
        initBioQuill(el.id.replace('quill-bio-', ''));
    });
});
</script>