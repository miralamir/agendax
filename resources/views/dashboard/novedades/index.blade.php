<x-dashboard-layout>
    <div class="flex justify-between items-center sticky top-0 bg-white z-10 py-4 -mx-6 px-6">
        <h2 class="text-2xl font-black text-gray-900">Administrar Novedades</h2>
        <a href="{{ route('dashboard.novedades.create') }}" class="dashboard-button-primary">+ Nueva Novedad</a>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('dashboard.novedades.index') }}" class="mb-6 bg-gray-50 border border-[var(--border-color)] rounded-lg p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Título o copete..." class="dashboard-input w-56">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Categoría</label>
            <select name="category" class="dashboard-input">
                <option value="">Todas</option>
                @foreach(['Artes Visuales','Música','Teatro','Cine','Literatura'] as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Subcategoría</label>
            <select name="subCategory" class="dashboard-input">
                <option value="">Todas</option>
                @foreach(['Agenda','Cartelera','Creadores','Estrenos','Ferias','Festivales','Festivales / Ciclos','Lanzamientos','Novedades','Novedades Editoriales','Noticias'] as $sub)
                <option value="{{ $sub }}" @selected(request('subCategory') === $sub)>{{ $sub }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Estado</label>
            <select name="published" class="dashboard-input">
                <option value="">Todos</option>
                <option value="1" @selected(request('published') === '1')>Publicado</option>
                <option value="0" @selected(request('published') === '0')>Borrador</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Destacado</label>
            <select name="featured" class="dashboard-input">
                <option value="">Todos</option>
                <option value="1" @selected(request('featured') === '1')>★ Destacado</option>
                <option value="0" @selected(request('featured') === '0')>No destacado</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="dashboard-button-outline">Filtrar</button>
            @if(request()->hasAny(['search','category','subCategory','published','featured','sort']))
            <a href="{{ route('dashboard.novedades.index') }}" class="dashboard-button-outline">Limpiar</a>
            @endif
        </div>
    </form>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- ACCIONES EN BLOQUE --}}
    <form id="bulkForm" method="POST" action="{{ route('dashboard.novedades.bulk') }}" class="mb-4 flex items-center gap-3"
          onsubmit="return (this.accion.value !== 'eliminar') || confirm('¿Eliminar las novedades seleccionadas? Esta acción no se puede deshacer.');">
        @csrf
        <select name="accion" class="dashboard-input" required>
            <option value="">Con las seleccionadas...</option>
            <option value="publicar">Publicar</option>
            <option value="despublicar">Pasar a borrador</option>
            <option value="destacar">★ Destacar</option>
            <option value="quitar_destacado">Quitar destacado</option>
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
                        <th class="px-4 py-3 text-left dashboard-table-header"><a href="{{ $orden('title') }}">Título{{ $flecha('title') }}</a></th>
                        <th class="px-4 py-3 text-left dashboard-table-header"><a href="{{ $orden('category') }}">Categoría{{ $flecha('category') }}</a></th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Estado</th>
                        <th class="px-4 py-3 text-left dashboard-table-header"><a href="{{ $orden('published_at') }}">Publicada{{ $flecha('published_at') }}</a></th>
                        <th class="px-4 py-3 text-right dashboard-table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($novedades as $novedad)
                        <tr class="dashboard-table-row">
                            <td class="px-2 py-3"><input type="checkbox" name="ids[]" value="{{ $novedad->id }}" form="bulkForm" class="check-item"></td>
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $novedad->id }}</td>
                            <td class="px-4 py-3"><span class="block truncate font-bold text-gray-900 max-w-md">{{ $novedad->title }}</span></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-{{ strtolower(str_replace(' ', '', $novedad->category ?? '')) }}">{{ $novedad->category ?: 'Sin categoría' }}</span>
                                @if($novedad->subCategory)<span class="block text-xs text-gray-500 mt-1">{{ $novedad->subCategory }}</span>@endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <form action="{{ route('dashboard.novedades.toggle', [$novedad, 'isPublished']) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Clic para cambiar" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $novedad->isPublished ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $novedad->isPublished ? 'Publicado' : 'Borrador' }}
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.novedades.toggle', [$novedad, 'isFeatured']) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Clic para cambiar" class="px-2 inline-flex text-sm leading-5 font-bold rounded-full {{ $novedad->isFeatured ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-300' }}">{{ $novedad->isFeatured ? '★' : '☆' }}</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $novedad->published_at ? $novedad->published_at->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('dashboard.novedades.edit', $novedad) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('dashboard.novedades.destroy', $novedad) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta novedad?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron novedades.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $novedades->links() }}</div>

    <script>
        const checkTodos = document.getElementById('checkTodos');
        const items = document.querySelectorAll('.check-item');
        const contador = document.getElementById('bulkCount');
        function actualizarContador() {
            const n = document.querySelectorAll('.check-item:checked').length;
            contador.textContent = n ? n + ' seleccionadas' : '';
        }
        if (checkTodos) checkTodos.addEventListener('change', function() { items.forEach(c => c.checked = checkTodos.checked); actualizarContador(); });
        items.forEach(c => c.addEventListener('change', actualizarContador));
    </script>
</x-dashboard-layout>
