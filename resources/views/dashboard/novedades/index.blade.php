<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">Administrar Novedades</h2>

    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('dashboard.novedades.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar novedades..." class="dashboard-input w-64">
            <button type="submit" class="dashboard-button-outline">Buscar</button>
            @if(request('search'))
                <a href="{{ route('dashboard.novedades.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">Limpiar</a>
            @endif
        </form>
        <a href="{{ route('dashboard.novedades.create') }}" class="dashboard-button-primary">
            + Nueva Novedad
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--border-color)] table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left dashboard-table-header w-3/12">Título</th>
                        <th class="px-4 py-3 text-left dashboard-table-header w-2/12">Categoría</th>
                        <th class="px-4 py-3 text-left dashboard-table-header w-2/12">Subcategoría</th>
                        <th class="px-4 py-3 text-left dashboard-table-header w-2/12">Estado</th>
                        <th class="px-4 py-3 text-left dashboard-table-header w-2/12">Fecha</th>
                        <th class="px-4 py-3 text-right dashboard-table-header w-1/12">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($novedades as $novedad)
                        <tr class="dashboard-table-row">
                            <td class="px-4 py-3 max-w-0">
                                <span class="block truncate font-bold text-gray-900">{{ $novedad->title }}</span>
                            </td>
                            <td class="px-4 py-3 max-w-0">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-{{ strtolower(str_replace(' ', '', $novedad->category ?? '')) }}">
                                    {{ $novedad->category ?: 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-0">
                                <span class="block truncate text-sm text-gray-700">{{ $novedad->subCategory ?: 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3 max-w-0">
                                @if($novedad->isPublished)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Publicado</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Borrador</span>
                                @endif
                                @if($novedad->isFeatured)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">⭐ Destacado</span>
                                @endif
                            </td>
                             <td class="px-4 py-3 max-w-0">
                                <span class="block truncate text-sm text-gray-700">{{ $novedad->published_at ? $novedad->published_at->format('d M Y') : 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium max-w-0">
                                <a href="{{ route('dashboard.novedades.edit', $novedad) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('dashboard.novedades.destroy', $novedad) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta novedad?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No se encontraron novedades.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $novedades->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>