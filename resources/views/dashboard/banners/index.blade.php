<x-dashboard-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black text-gray-900">Publicidad</h2>
        <a href="{{ route('dashboard.banners.create') }}" class="dashboard-button-primary">+ Nuevo Banner</a>
    </div>

    <form method="GET" action="{{ route('dashboard.banners.index') }}" class="mb-6 bg-gray-50 border border-[var(--border-color)] rounded-lg p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Posición</label>
            <select name="posicion" class="dashboard-input">
                <option value="">Todas</option>
                @foreach($posiciones as $key => $label)
                <option value="{{ $key }}" @selected(request('posicion') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Estado</label>
            <select name="estado" class="dashboard-input">
                <option value="">Todos</option>
                <option value="activos" @selected(request('estado') === 'activos')>Activos</option>
                <option value="inactivos" @selected(request('estado') === 'inactivos')>Inactivos</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="dashboard-button-outline">Filtrar</button>
            @if(request()->hasAny(['posicion','estado']))
            <a href="{{ route('dashboard.banners.index') }}" class="dashboard-button-outline">Limpiar</a>
            @endif
        </div>
    </form>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--border-color)]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left dashboard-table-header">Preview</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Título</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Posición</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Métricas</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Estado</th>
                        <th class="px-4 py-3 text-right dashboard-table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($banners as $banner)
                        <tr class="dashboard-table-row">
                            <td class="px-4 py-3">
                                <img src="{{ \Illuminate\Support\Str::startsWith($banner->imagen, 'http') ? $banner->imagen : Storage::url($banner->imagen) }}" class="h-12 rounded object-contain" style="max-width:120px">
                            </td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $banner->titulo ?: '(sin título)' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                @forelse($banner->listaPosiciones() as $pos)
                                    <span class="inline-block bg-gray-100 rounded px-2 py-0.5 mb-1">{{ $posiciones[$pos] ?? $pos }}</span>
                                @empty
                                    <span class="text-gray-400">Sin zona</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                👁 {{ $banner->impresiones }} · 🖱 {{ $banner->clics }}
                                @if($banner->impresiones > 0)
                                <br><span class="text-gray-400">CTR {{ round($banner->clics / $banner->impresiones * 100, 1) }}%</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('dashboard.banners.toggle', $banner) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $banner->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $banner->activo ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('dashboard.banners.edit', $banner) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('dashboard.banners.destroy', $banner) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este banner?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay banners cargados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $banners->links() }}</div>
</x-dashboard-layout>
