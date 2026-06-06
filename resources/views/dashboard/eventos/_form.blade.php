@php
    $evento = $evento ?? new App\Models\Evento();
    $galleryText = old('gallery', is_array($evento->gallery) ? implode("\n", $evento->gallery) : '');
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
        <input type="text" name="artist" id="artist" value="{{ old('artist', $evento->artist) }}" placeholder="Ej: Pablo Reinoso" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="curator" class="dashboard-label">Curadores</label>
        <input type="text" name="curator" id="curator" value="{{ old('curator', $evento->curator) }}" placeholder="Ej: Virna Gvero" class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="description" class="dashboard-label">Descripción del Evento</label>
        <textarea name="description" id="description" rows="5" class="mt-1 block w-full dashboard-input">{{ old('description', $evento->description) }}</textarea>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="artistBio" class="dashboard-label">Biografía del Artista</label>
        <textarea name="artistBio" id="artistBio" rows="4" class="mt-1 block w-full dashboard-input">{{ old('artistBio', $evento->artistBio) }}</textarea>
    </div>


    <!-- 2. MULTIMEDIA -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Multimedia y URLs</h3>
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="mainImageUrl" class="dashboard-label">URL de la Imagen Principal</label>
        <input type="url" name="mainImageUrl" id="mainImageUrl" value="{{ old('mainImageUrl', $evento->mainImageUrl) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="secondaryImageUrl" class="dashboard-label">URL de la Imagen Secundaria</label>
        <input type="url" name="secondaryImageUrl" id="secondaryImageUrl" value="{{ old('secondaryImageUrl', $evento->secondaryImageUrl) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="artistImageUrl" class="dashboard-label">URL de la Foto del Artista</label>
        <input type="url" name="artistImageUrl" id="artistImageUrl" value="{{ old('artistImageUrl', $evento->artistImageUrl) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="gallery" class="dashboard-label">Galería de Imágenes (URLs)</label>
        <p class="text-xs text-gray-500 mb-1">Pega una URL por línea para añadir múltiples imágenes.</p>
        <textarea name="gallery" id="gallery" rows="4" placeholder="https://imagen1.jpg&#10;https://imagen2.jpg" class="mt-1 block w-full dashboard-input">{{ $galleryText }}</textarea>
    </div>


    <!-- 3. CATEGORIZACIÓN Y ESTADOS -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Categorización y Visibilidad</h3>
    </div>

    <div>
        <label for="category" class="dashboard-label">Categoría</label>
        <select name="category" id="category" class="mt-1 block w-full dashboard-input">
            <option value="">Seleccionar...</option>
            <option value="Arte" {{ old('category', $evento->category) == 'Arte' ? 'selected' : '' }}>Arte</option>
            <option value="Música" {{ old('category', $evento->category) == 'Música' ? 'selected' : '' }}>Música</option>
            <option value="Teatro" {{ old('category', $evento->category) == 'Teatro' ? 'selected' : '' }}>Teatro</option>
            <option value="Cine" {{ old('category', $evento->category) == 'Cine' ? 'selected' : '' }}>Cine</option>
            <option value="Literatura" {{ old('category', $evento->category) == 'Literatura' ? 'selected' : '' }}>Literatura</option>
        </select>
    </div>

    <div>
        <label for="subCategory" class="dashboard-label">Sub-Categoría</label>
        <input type="text" name="subCategory" id="subCategory" value="{{ old('subCategory', $evento->subCategory) }}" placeholder="Ej: Agenda, Festivales..." class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2 flex items-center space-x-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="flex items-center">
            <input id="isPublished" name="isPublished" type="checkbox" value="1" {{ old('isPublished', $evento->isPublished) ? 'checked' : '' }} class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
            <label for="isPublished" class="ml-3 dashboard-label text-gray-900">Publicar Evento</label>
        </div>
        <div class="flex items-center">
            <input id="isFeatured" name="isFeatured" type="checkbox" value="1" {{ old('isFeatured', $evento->isFeatured) ? 'checked' : '' }} class="h-5 w-5 text-yellow-500 focus:ring-yellow-500 border-gray-300 rounded">
            <label for="isFeatured" class="ml-3 dashboard-label text-gray-900">⭐ Marcar como Destacado</label>
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

    <div class="col-span-1 md:col-span-2">
        <label for="venueHours" class="dashboard-label">Horarios Detallados</label>
        <input type="text" name="venueHours" id="venueHours" value="{{ old('venueHours', $evento->venueHours) }}" placeholder="Ej: Lunes a Viernes 10 a 18 hs" class="mt-1 block w-full dashboard-input">
    </div>


    <!-- 5. INFORMACIÓN DEL LUGAR -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="dashboard-section-title">Información del Lugar (Venue)</h3>
    </div>

    <div>
        <label for="locationName" class="dashboard-label">Nombre del Lugar</label>
        <input type="text" name="locationName" id="locationName" value="{{ old('locationName', $evento->locationName) }}" placeholder="Ej: Xippas Punta del Este" class="mt-1 block w-full dashboard-input">
    </div>

    <div>
        <label for="room" class="dashboard-label">Sala</label>
        <input type="text" name="room" id="room" value="{{ old('room', $evento->room) }}" placeholder="Ej: Sala 1" class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="venueAddress" class="dashboard-label">Dirección</label>
        <input type="text" name="venueAddress" id="venueAddress" value="{{ old('venueAddress', $evento->venueAddress) }}" placeholder="Ej: Ruta 104, km 5, Manantiales..." class="mt-1 block w-full dashboard-input">
    </div>

    <div class="col-span-1 md:col-span-2 flex gap-4">
        <div class="flex-1">
            <label for="lat" class="dashboard-label">Latitud</label>
            <input type="number" step="any" name="lat" id="lat" value="{{ old('lat', $evento->lat) }}" placeholder="Ej: -34.8608506" class="mt-1 block w-full dashboard-input">
        </div>
        <div class="flex-1">
            <label for="lng" class="dashboard-label">Longitud</label>
            <input type="number" step="any" name="lng" id="lng" value="{{ old('lng', $evento->lng) }}" placeholder="Ej: -54.8217623" class="mt-1 block w-full dashboard-input">
        </div>
    </div>

    <div>
        <label for="venuePhone" class="dashboard-label">Teléfono</label>
        <input type="text" name="venuePhone" id="venuePhone" value="{{ old('venuePhone', $evento->venuePhone) }}" placeholder="+598..." class="mt-1 block w-full dashboard-input">
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
        <label for="venueSocial" class="dashboard-label">Redes Sociales (IG, etc)</label>
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
        <label for="catalogPdfUrl" class="dashboard-label">URL del Catálogo (PDF)</label>
        <input type="url" name="catalogPdfUrl" id="catalogPdfUrl" value="{{ old('catalogPdfUrl', $evento->catalogPdfUrl) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
    </div>
</div>