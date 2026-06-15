<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">{{ $creador->exists ? 'Editar' : 'Nuevo' }} Creador</h2>

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-300 text-red-700 rounded-lg p-4">
        <p class="font-bold mb-2">No se pudo guardar:</p>
        <ul class="list-disc list-inside text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ $creador->exists ? route('dashboard.creadores.update', $creador) : route('dashboard.creadores.store') }}" enctype="multipart/form-data" class="bg-white border border-[var(--border-color)] rounded-lg p-6 space-y-5 max-w-2xl">
        @csrf
        @if($creador->exists) @method('PUT') @endif

        <div>
            <label class="dashboard-label">Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $creador->nombre) }}" required class="mt-1 block w-full dashboard-input">
        </div>

        <div>
            <label class="dashboard-label">Rol</label>
            <input type="text" name="rol" value="{{ old('rol', $creador->rol) }}" placeholder="Artista, Curador/a, Músico..." class="mt-1 block w-full dashboard-input">
        </div>

        <div>
            <label class="dashboard-label">Biografía</label>
            <textarea name="bio" rows="6" class="mt-1 block w-full dashboard-input">{{ old('bio', $creador->bio) }}</textarea>
        </div>

        <div>
            <label class="dashboard-label">Foto</label>
            @if($creador->foto)
            <img src="{{ str_starts_with($creador->foto, 'http') ? $creador->foto : Storage::url($creador->foto) }}" class="w-20 h-20 rounded-full object-cover mb-2">
            @endif
            <input type="file" name="foto" accept="image/*" class="mt-1 block w-full text-sm">
            <p class="text-xs text-gray-400 mt-1">O pegá una URL:</p>
            <input type="text" name="fotoUrl" placeholder="https://..." class="mt-1 block w-full dashboard-input">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="dashboard-button-primary">{{ $creador->exists ? 'Actualizar' : 'Crear' }} Creador</button>
            <a href="{{ route('dashboard.creadores.index') }}" class="dashboard-button-outline">Cancelar</a>
        </div>
    </form>
</x-dashboard-layout>
