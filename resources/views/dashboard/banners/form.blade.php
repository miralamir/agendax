<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">{{ $banner->exists ? 'Editar' : 'Nuevo' }} Banner</h2>

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-300 text-red-700 rounded-lg p-4">
        <ul class="list-disc list-inside text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ $banner->exists ? route('dashboard.banners.update', $banner) : route('dashboard.banners.store') }}" enctype="multipart/form-data" class="bg-white border border-[var(--border-color)] rounded-lg p-6 space-y-5 max-w-2xl">
        @csrf
        @if($banner->exists) @method('PUT') @endif

        <div>
            <label class="dashboard-label">Título interno (para identificarlo)</label>
            <input type="text" name="titulo" value="{{ old('titulo', $banner->titulo) }}" placeholder="Ej: Sponsor Teatro Colón - Junio" class="mt-1 block w-full dashboard-input">
        </div>

        <div>
            <label class="dashboard-label">Posiciones * (podés elegir varias)</label>
            @php $posActuales = old('posiciones', $banner->exists ? $banner->listaPosiciones() : []); @endphp
            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 border border-gray-200 rounded-lg p-3 max-h-64 overflow-y-auto">
                @foreach($posiciones as $key => $label)
                <label class="flex items-center gap-2 text-sm py-1">
                    <input type="checkbox" name="posiciones[]" value="{{ $key }}" {{ in_array($key, $posActuales) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-1">El mismo banner puede mostrarse en varias zonas a la vez.</p>
        </div>

        <div>
            <label class="dashboard-label">Imagen del banner</label>
            @if($banner->imagen)
            <img src="{{ \Illuminate\Support\Str::startsWith($banner->imagen, 'http') ? $banner->imagen : Storage::url($banner->imagen) }}" class="h-20 rounded mb-2 object-contain">
            @endif
            <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full text-sm">
            <p class="text-xs text-gray-400 mt-1">O pegá una URL de imagen:</p>
            <input type="text" name="imagenUrl" placeholder="https://..." class="mt-1 block w-full dashboard-input">
        </div>

        <div>
            <label class="dashboard-label">Link (a dónde lleva al hacer clic)</label>
            <input type="url" name="link" value="{{ old('link', $banner->link) }}" placeholder="https://..." class="mt-1 block w-full dashboard-input">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="dashboard-label">Activo desde (opcional)</label>
                <input type="datetime-local" name="desde" value="{{ old('desde', optional($banner->desde)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
            </div>
            <div>
                <label class="dashboard-label">Activo hasta (opcional)</label>
                <input type="datetime-local" name="hasta" value="{{ old('hasta', optional($banner->hasta)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full dashboard-input">
            </div>
        </div>

        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $banner->exists ? $banner->activo : true) ? 'checked' : '' }}>
                Activo
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="nueva_pestana" value="1" {{ old('nueva_pestana', $banner->exists ? $banner->nueva_pestana : true) ? 'checked' : '' }}>
                Abrir link en pestaña nueva
            </label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="dashboard-button-primary">{{ $banner->exists ? 'Actualizar' : 'Crear' }} Banner</button>
            <a href="{{ route('dashboard.banners.index') }}" class="dashboard-button-outline">Cancelar</a>
        </div>
    </form>
</x-dashboard-layout>
