<x-dashboard-layout>
@php
    $novedad = $novedad ?? new App\Models\Novedad();
    $galleryText = ''; // galería ahora es array de objetos
    $videosArray = old('videos', is_array($novedad->videos) ? $novedad->videos : json_decode($novedad->videos ?? '[]', true) ?? []);
@endphp

<form action="{{ isset($novedad->id) ? route('dashboard.novedades.update', $novedad->id) : route('dashboard.novedades.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($novedad->id))
        @method('PUT')
    @endif

    <div class="mb-6 flex justify-end items-center bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
        <a href="{{ route('dashboard.novedades.index') }}" class="dashboard-button-outline mr-3">Cancelar</a>
        <button type="submit" class="dashboard-button-primary">Guardar Novedad</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- 1. INFORMACIÓN BÁSICA -->
        <div class="col-span-1 md:col-span-2">
            <h3 class="dashboard-section-title">Información Básica</h3>
        </div>
        
        <div class="col-span-1 md:col-span-2">
            <label for="title" class="dashboard-label">Título de la Novedad *</label>
            <input type="text" name="title" id="title" required value="{{ old('title', $novedad->title) }}" class="mt-1 block w-full dashboard-input" oninput="updateSlug()">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="slug" class="dashboard-label">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $novedad->slug) }}" class="mt-1 block w-full dashboard-input">
            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="author" class="dashboard-label">Autor</label>
            <input type="text" name="author" id="author" value="{{ old('author', $novedad->author) }}" placeholder="Ej: Mariano Franze" class="mt-1 block w-full dashboard-input">
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="excerpt" class="dashboard-label">Bajada / Resumen</label>
            <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full dashboard-input">{{ old('excerpt', $novedad->excerpt) }}</textarea>
            @error('excerpt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="body" class="dashboard-label">Cuerpo de la Novedad</label>
            <textarea name="body" id="body" rows="10" class="mt-1 block w-full dashboard-input">{{ old('body', $novedad->body) }}</textarea>
            @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- 2. MULTIMEDIA -->
        <div class="col-span-1 md:col-span-2 mt-4">
            <h3 class="dashboard-section-title">Multimedia</h3>
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="image" class="dashboard-label">Imagen Principal</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full dashboard-input p-1" onchange="previewImage(event, 'image-preview')">
            @if ($novedad->image)
                <img id="image-preview" src="{{ Storage::url($novedad->image) }}" alt="Imagen Principal" class="mt-2 max-h-48 object-cover rounded-lg">
            @else
                <img id="image-preview" class="mt-2 max-h-48 object-cover rounded-lg hidden">
            @endif
            @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <label class="dashboard-label">Galería de Imágenes</label>
                <button type="button" onclick="agregarImagenGaleriaNov()" class="dashboard-button-outline text-sm">+ Agregar imagen</button>
            </div>
            <div id="galeria-nov-container" class="space-y-3">
                @php $galeriaItems = is_array($novedad->gallery) ? $novedad->gallery : []; @endphp
                @foreach($galeriaItems as $gi => $gitem)
                @php
                    $gurl = is_array($gitem) ? ($gitem["url"] ?? "") : $gitem;
                    $gcap = is_array($gitem) ? ($gitem["caption"] ?? "") : "";
                @endphp
                <div class="galeria-nov-item border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                            <input type="file" name="galleryFiles[{{ $gi }}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewGalNov(this, 'gal-nov-prev-{{ $gi }}')">
                            <input type="text" name="gallery[{{ $gi }}][url]" value="{{ $gurl }}" placeholder="O pegar URL..." class="mt-1 block w-full dashboard-input text-xs">
                            @if($gurl)
                            <img id="gal-nov-prev-{{ $gi }}" src="{{ str_starts_with($gurl, 'http') ? $gurl : Storage::url($gurl) }}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200">
                            @else
                            <img id="gal-nov-prev-{{ $gi }}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200 hidden">
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Epígrafe (opcional)</p>
                            <textarea name="gallery[{{ $gi }}][caption]" rows="3" placeholder="Título, artista, año, técnica..." class="block w-full dashboard-input text-xs">{{ $gcap }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="this.closest('.galeria-nov-item').remove()" class="text-red-500 text-xs hover:text-red-700">× Eliminar</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="videos" class="dashboard-label mb-1">URLs de Video (YouTube/Vimeo)</label>
            <div id="video-inputs-container">
                @foreach($videosArray as $videoUrl)
                    <div class="flex items-center space-x-2 mb-2">
                        <input type="url" name="videos[]" value="{{ $videoUrl }}" placeholder="https://youtube.com/watch?v=..." class="block w-full dashboard-input">
                        <button type="button" onclick="this.parentNode.remove()" class="text-red-600 hover:text-red-800">×</button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addVideoInput()" class="mt-2 dashboard-button-outline text-sm">+ Agregar Video</button>
            @error('videos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="pdf" class="dashboard-label">Archivo PDF Adjunto</label>
            <input type="file" name="pdf" id="pdf" class="mt-1 block w-full dashboard-input p-1">
            @if ($novedad->pdf)
                <p class="text-sm text-gray-600 mt-2">PDF actual: <a href="{{ Storage::url($novedad->pdf) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($novedad->pdf) }}</a></p>
            @endif
            @error('pdf') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- 3. CATEGORIZACIÓN -->
        <div class="col-span-1 md:col-span-2 mt-4">
            <h3 class="dashboard-section-title">Categorización y Publicación</h3>
        </div>

        <div>
            <label for="category" class="dashboard-label">Categoría</label>
            <select name="category" id="category" class="mt-1 block w-full dashboard-input" onchange="updateSubcategories()">
                <option value="">Seleccionar...</option>
                <option value="Artes Visuales" {{ old('category', $novedad->category) == 'Artes Visuales' ? 'selected' : '' }}>Artes Visuales</option>
                <option value="Música" {{ old('category', $novedad->category) == 'Música' ? 'selected' : '' }}>Música</option>
                <option value="Teatro" {{ old('category', $novedad->category) == 'Teatro' ? 'selected' : '' }}>Teatro</option>
                <option value="Cine" {{ old('category', $novedad->category) == 'Cine' ? 'selected' : '' }}>Cine</option>
                <option value="Literatura" {{ old('category', $novedad->category) == 'Literatura' ? 'selected' : '' }}>Literatura</option>
            </select>
            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="subCategory" class="dashboard-label">Subcategoría</label>
            <select name="subCategory" id="subCategory" class="mt-1 block w-full dashboard-input">
                <option value="">Seleccionar...</option>
                {{-- Opciones se cargarán con JS --}}
            </select>
            @error('subCategory') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2 flex items-center space-x-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <input id="isPublished" name="isPublished" type="checkbox" value="1" {{ old('isPublished', $novedad->isPublished) ? 'checked' : '' }} class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="isPublished" class="ml-3 dashboard-label text-gray-900">Publicar Novedad</label>
            </div>
            <div class="flex items-center">
                <input id="isFeatured" name="isFeatured" type="checkbox" value="1" {{ old('isFeatured', $novedad->isFeatured) ? 'checked' : '' }} class="h-5 w-5 text-yellow-500 focus:ring-yellow-500 border-gray-300 rounded">
                <label for="isFeatured" class="ml-3 dashboard-label text-gray-900">⭐ Marcar como Destacado</label>
            </div>
        </div>
        
        <div>
            <label for="published_at" class="dashboard-label">Fecha de Publicación</label>
            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $novedad->published_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
            @error('published_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- 4. SEO -->
        <div class="col-span-1 md:col-span-2 mt-4">
            <h3 class="dashboard-section-title">SEO</h3>
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="seo_title" class="dashboard-label">Título SEO (Max 60 caracteres)</label>
            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $novedad->seo_title) }}" class="mt-1 block w-full dashboard-input" maxlength="60" oninput="countChars('seo_title', 'seo-title-count', 60)">
            <p class="text-xs text-gray-500 mt-1"><span id="seo-title-count">{{ strlen(old('seo_title', $novedad->seo_title ?? '')) }}</span>/60 caracteres</p>
            @error('seo_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="col-span-1 md:col-span-2">
            <label for="seo_description" class="dashboard-label">Meta Description (Max 160 caracteres)</label>
            <textarea name="seo_description" id="seo_description" rows="3" class="mt-1 block w-full dashboard-input" maxlength="160" oninput="countChars('seo_description', 'seo-description-count', 160)">{{ old('seo_description', $novedad->seo_description) }}</textarea>
            <p class="text-xs text-gray-500 mt-1"><span id="seo-description-count">{{ strlen(old('seo_description', $novedad->seo_description ?? '')) }}</span>/160 caracteres</p>
            @error('seo_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('dashboard.novedades.index') }}" class="dashboard-button-outline mr-2">Cancelar</a>
        <button type="submit" class="dashboard-button-primary">Guardar Novedad</button>
    </div>
</form>

<script>
    // JavaScript para el slug automático
    function updateSlug() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        if (!slugInput.value) {
            slugInput.value = titleInput.value.toLowerCase()
                                .replace(/[^a-z0-9\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-');
        }
    }

    // JavaScript para previsualizar imágenes
    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewGallery(event, previewId) {
        const previewContainer = document.getElementById(previewId);
        previewContainer.innerHTML = ''; // Limpiar previews anteriores
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('max-h-24', 'object-cover', 'rounded-lg');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    // JavaScript para agregar inputs de video
    function addVideoInput(initialValue = '') {
        const container = document.getElementById('video-inputs-container');
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-2', 'mb-2');
        div.innerHTML = `
            <input type="url" name="videos[]" value="${initialValue}" placeholder="https://youtube.com/watch?v=..." class="block w-full dashboard-input">
            <button type="button" onclick="this.parentNode.remove()" class="text-red-600 hover:text-red-800">×</button>
        `;
        container.appendChild(div);
    }

    // Lógica para el select dinámico de subcategorías
    const subcategoriesData = {
        'Artes Visuales': ['Agenda', 'Creadores', 'Ferias', 'Novedades'],
        'Música':         ['Agenda', 'Lanzamientos', 'Festivales', 'Novedades'],
        'Teatro':         ['Cartelera', 'Festivales', 'Novedades'],
        'Cine':           ['Estrenos', 'Festivales / Ciclos', 'Novedades'],
        'Literatura':     ['Agenda', 'Novedades Editoriales', 'Ferias', 'Noticias'],
    };

    function updateSubcategories() {
        const categorySelect = document.getElementById('category');
        const subCategorySelect = document.getElementById('subCategory');
        const selectedCategory = categorySelect.value;
        const currentSubCategory = "{{ old('subCategory', $novedad->subCategory ?? '') }}";

        subCategorySelect.innerHTML = '<option value="">Seleccionar...</option>';

        if (selectedCategory && subcategoriesData[selectedCategory]) {
            subcategoriesData[selectedCategory].forEach(sub => {
                const option = document.createElement('option');
                option.value = sub;
                option.textContent = sub;
                if (sub === currentSubCategory) {
                    option.selected = true;
                }
                subCategorySelect.appendChild(option);
            });
        }
    }

    // Inicializar subcategorías y contadores al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        updateSubcategories();
        countChars('seo_title', 'seo-title-count', 60);
        countChars('seo_description', 'seo-description-count', 160);

        // Si hay videos precargados, asegurar que se muestren
        const videosArray = @json($videosArray);
        if (videosArray && videosArray.length > 0) {
             // No hace falta, ya los renderiza Blade en el foreach
        }
    });

    // Función genérica para contador de caracteres
    function countChars(inputId, countId, max) {
        const input = document.getElementById(inputId);
        const countSpan = document.getElementById(countId);
        if (input && countSpan) {
            countSpan.textContent = input.value.length;
        }
    }
let galeriaNovCount = {{ count(is_array($novedad->gallery) ? $novedad->gallery : []) }};
function agregarImagenGaleriaNov() {
    const i = galeriaNovCount++;
    const html = `<div class="galeria-nov-item border border-gray-200 rounded-lg p-3 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
            <div>
                <p class="text-xs text-gray-400 mb-1">Subir archivo</p>
                <input type="file" name="galleryFiles[${i}]" accept="image/*" class="block w-full dashboard-input p-1 text-xs" onchange="previewGalNov(this, 'gal-nov-prev-${i}')">
                <input type="text" name="gallery[${i}][url]" placeholder="O pegar URL..." class="mt-1 block w-full dashboard-input text-xs">
                <img id="gal-nov-prev-${i}" class="mt-1 h-16 w-auto rounded object-cover border border-gray-200 hidden">
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Epigrafe (opcional)</p>
                <textarea name="gallery[${i}][caption]" rows="3" placeholder="Titulo, artista, año, tecnica..." class="block w-full dashboard-input text-xs"></textarea>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" onclick="this.closest('.galeria-nov-item').remove()" class="text-red-500 text-xs hover:text-red-700">x Eliminar</button>
        </div>
    </div>`;
    document.getElementById("galeria-nov-container").insertAdjacentHTML("beforeend", html);
}
function previewGalNov(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            if (img) { img.src = e.target.result; img.classList.remove("hidden"); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</x-dashboard-layout>