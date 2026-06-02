@php
    $evento = $evento ?? new App\Models\Evento();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Información General -->
    <div class="col-span-1 md:col-span-2">
        <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Información General</h3>
    </div>
    
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
        <input type="text" name="title" id="title" required value="{{ old('title', $evento->title) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="artist" class="block text-sm font-medium text-gray-700">Artista(s)</label>
        <input type="text" name="artist" id="artist" value="{{ old('artist', $evento->artist) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <div>
        <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
        <select name="category" id="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            <option value="">Seleccionar...</option>
            <option value="Arte" {{ old('category', $evento->category) == 'Arte' ? 'selected' : '' }}>Arte</option>
            <option value="Música" {{ old('category', $evento->category) == 'Música' ? 'selected' : '' }}>Música</option>
            <option value="Teatro" {{ old('category', $evento->category) == 'Teatro' ? 'selected' : '' }}>Teatro</option>
            <option value="Cine" {{ old('category', $evento->category) == 'Cine' ? 'selected' : '' }}>Cine</option>
            <option value="Literatura" {{ old('category', $evento->category) == 'Literatura' ? 'selected' : '' }}>Literatura</option>
        </select>
    </div>

    <div>
        <label for="subCategory" class="block text-sm font-medium text-gray-700">SubCategoría</label>
        <input type="text" name="subCategory" id="subCategory" value="{{ old('subCategory', $evento->subCategory) }}" placeholder="Ej: Festivales, Cartelera..." class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="description" id="description" rows="4" class="mt-1 shadow-sm focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description', $evento->description) }}</textarea>
    </div>

    <!-- Fechas -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Fechas</h3>
    </div>

    <div>
        <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
        <input type="datetime-local" name="startDate" id="startDate" value="{{ old('startDate', $evento->startDate?->format('Y-m-d\TH:i')) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <div>
        <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
        <input type="datetime-local" name="endDate" id="endDate" value="{{ old('endDate', $evento->endDate?->format('Y-m-d\TH:i')) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <div>
        <label for="singleDate" class="block text-sm font-medium text-gray-700">Día Único (Ej: Recital)</label>
        <input type="datetime-local" name="singleDate" id="singleDate" value="{{ old('singleDate', $evento->singleDate?->format('Y-m-d\TH:i')) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <!-- Ubicación -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Lugar (Venue)</h3>
    </div>

    <div>
        <label for="locationName" class="block text-sm font-medium text-gray-700">Nombre del Lugar</label>
        <input type="text" name="locationName" id="locationName" value="{{ old('locationName', $evento->locationName) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <div>
        <label for="venueAddress" class="block text-sm font-medium text-gray-700">Dirección</label>
        <input type="text" name="venueAddress" id="venueAddress" value="{{ old('venueAddress', $evento->venueAddress) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>

    <!-- Estados -->
    <div class="col-span-1 md:col-span-2 mt-4">
        <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Estados</h3>
    </div>

    <div class="flex items-center space-x-6">
        <div class="flex items-center">
            <input id="isPublished" name="isPublished" type="checkbox" value="1" {{ old('isPublished', $evento->isPublished) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
            <label for="isPublished" class="ml-2 block text-sm text-gray-900">Publicado</label>
        </div>
        <div class="flex items-center">
            <input id="isFeatured" name="isFeatured" type="checkbox" value="1" {{ old('isFeatured', $evento->isFeatured) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
            <label for="isFeatured" class="ml-2 block text-sm text-gray-900">⭐ Destacado</label>
        </div>
    </div>
</div>