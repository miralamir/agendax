<x-dashboard-layout>
    <div class="flex justify-between items-center sticky top-0 bg-white z-10 py-4 -mx-6 px-6">
        <h2 class="text-2xl font-black text-gray-900">Administrar Creadores</h2>
        <a href="{{ route('dashboard.creadores.create') }}" class="dashboard-button-primary">+ Nuevo Creador</a>
    </div>

    <form method="GET" action="{{ route('dashboard.creadores.index') }}" class="mb-6 bg-gray-50 border border-[var(--border-color)] rounded-lg p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o rol..." class="dashboard-input w-56">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Bio</label>
            <select name="conbio" class="dashboard-input">
                <option value="">Todos</option>
                <option value="1" @selected(request('conbio') === '1')>Con bio</option>
                <option value="0" @selected(request('conbio') === '0')>Sin bio</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Foto</label>
            <select name="confoto" class="dashboard-input">
                <option value="">Todos</option>
                <option value="1" @selected(request('confoto') === '1')>Con foto</option>
                <option value="0" @selected(request('confoto') === '0')>Sin foto</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="dashboard-button-outline">Filtrar</button>
            @if(request()->hasAny(['search','rol','conbio','confoto','sort']))
            <a href="{{ route('dashboard.creadores.index') }}" class="dashboard-button-outline">Limpiar</a>
            @endif
        </div>
    </form>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <form id="bulkForm" method="POST" action="{{ route('dashboard.creadores.bulk') }}" class="mb-4 flex items-center gap-3"
          onsubmit="return (this.accion.value !== 'eliminar') || confirm('¿Eliminar los creadores seleccionados? Esta acción no se puede deshacer.');">
        @csrf
        <select name="accion" class="dashboard-input" required>
            <option value="">Con los seleccionados...</option>
            <option value="eliminar">🗑 Eliminar</option>
        </select>
        <button type="submit" class="dashboard-button-outline">Aplicar</button>
        <span class="text-xs text-gray-500" id="bulkCount"></span>
    </form>

    @php
        $dirActual = request('dir', 'desc');
        $orden = fn($campo) => request()->fullUrlWithQuery(['sort' => $campo, 'dir' => (request('sort') === $campo && $dirActual === 'asc') ? 'desc' : 'asc']);
        $flecha = fn($campo) => request('sort') === $campo ? ($dirActual === 'asc' ? ' ▲' : ' ▼') : '';
    @endphp

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--border-color)]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-3"><input type="checkbox" id="checkTodos"></th>
                        <th class="px-3 py-3 text-left dashboard-table-header"><a href="{{ $orden('id') }}">ID{{ $flecha('id') }}</a></th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Foto</th>
                        <th class="px-4 py-3 text-left dashboard-table-header"><a href="{{ $orden('nombre') }}">Nombre{{ $flecha('nombre') }}</a></th>
                        <th class="px-4 py-3 text-left dashboard-table-header"><a href="{{ $orden('rol') }}">Rol{{ $flecha('rol') }}</a></th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Bio</th>
                        <th class="px-4 py-3 text-right dashboard-table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($creadores as $creador)
                        <tr class="dashboard-table-row">
                            <td class="px-2 py-3"><input type="checkbox" name="ids[]" value="{{ $creador->id }}" form="bulkForm" class="check-item"></td>
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $creador->id }}</td>
                            <td class="px-4 py-3">
                                @if($creador->foto)
                                <img src="{{ str_starts_with($creador->foto, 'http') ? $creador->foto : Storage::url($creador->foto) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($creador->nombre, 0, 1)) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3"><a href="{{ route('creador.show', $creador->slug) }}" target="_blank" class="font-bold text-gray-900 hover:underline">{{ $creador->nombre }}</a></td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $creador->rol ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($creador->bio)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('dashboard.creadores.edit', $creador) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('dashboard.creadores.destroy', $creador) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este creador?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron creadores.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $creadores->links() }}</div>

    <script>
        const checkTodos = document.getElementById('checkTodos');
        const items = document.querySelectorAll('.check-item');
        const contador = document.getElementById('bulkCount');
        function actualizarContador() {
            const n = document.querySelectorAll('.check-item:checked').length;
            contador.textContent = n ? n + ' seleccionados' : '';
        }
        if (checkTodos) checkTodos.addEventListener('change', function() { items.forEach(c => c.checked = checkTodos.checked); actualizarContador(); });
        items.forEach(c => c.addEventListener('change', actualizarContador));
    </script>
</x-dashboard-layout>
