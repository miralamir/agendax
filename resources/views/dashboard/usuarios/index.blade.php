<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">Administrar Usuarios</h2>

    <form method="GET" action="{{ route('dashboard.usuarios.index') }}" class="mb-6 bg-gray-50 border border-[var(--border-color)] rounded-lg p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o email..." class="dashboard-input w-64">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Estado</label>
            <select name="estado" class="dashboard-input">
                <option value="">Todos</option>
                <option value="activos" @selected(request('estado') === 'activos')>Activos</option>
                <option value="baneados" @selected(request('estado') === 'baneados')>Baneados</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="dashboard-button-outline">Filtrar</button>
            @if(request()->hasAny(['search','estado']))
            <a href="{{ route('dashboard.usuarios.index') }}" class="dashboard-button-outline">Limpiar</a>
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
                        <th class="px-3 py-3 text-left dashboard-table-header">ID</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Nombre</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Email</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Rol</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Comentarios</th>
                        <th class="px-4 py-3 text-left dashboard-table-header">Estado</th>
                        <th class="px-4 py-3 text-right dashboard-table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($usuarios as $usuario)
                        <tr class="dashboard-table-row">
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $usuario->id }}</td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $usuario->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $usuario->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $usuario->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600' }}">{{ $usuario->role }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $usuario->comentarios_count }}</td>
                            <td class="px-4 py-3">
                                @if($usuario->baneado)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Baneado</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    {{-- Cambiar rol --}}
                                    @if($usuario->id !== auth()->id())
                                    <form action="{{ route('dashboard.usuarios.rol', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('{{ $usuario->isAdmin() ? '¿Quitar admin a este usuario?' : '¿Hacer admin a este usuario? Podrá acceder al dashboard.' }}');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-purple-600 hover:text-purple-800 font-bold">
                                            {{ $usuario->isAdmin() ? 'Quitar admin' : 'Hacer admin' }}
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Banear (solo a no-admins) --}}
                                    @if(!$usuario->isAdmin())
                                    <form action="{{ route('dashboard.usuarios.baneo', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('{{ $usuario->baneado ? '¿Reactivar este usuario?' : '¿Banear este usuario? Sus comentarios quedarán ocultos.' }}');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="{{ $usuario->baneado ? 'text-green-600 hover:text-green-800' : 'text-yellow-600 hover:text-yellow-800' }} font-bold">
                                            {{ $usuario->baneado ? 'Reactivar' : 'Banear' }}
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Eliminar --}}
                                    @if($usuario->id !== auth()->id())
                                    <form action="{{ route('dashboard.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar a {{ $usuario->name }} definitivamente? Esta acción no se puede deshacer.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Eliminar</button>
                                    </form>
                                    @endif

                                    @if($usuario->id === auth()->id())
                                    <span class="text-gray-300 text-xs">(vos)</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron usuarios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $usuarios->links() }}</div>
</x-dashboard-layout>
