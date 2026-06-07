<x-dashboard-layout>
    <div class="flex justify-between items-center sticky top-0 bg-white z-10 py-4 -mx-6 px-6">
        <h2 class="text-2xl font-black text-gray-900">Administrar Eventos</h2>
        <a href="{{ route('dashboard.eventos.create') }}" class="dashboard-button-primary">
            + Nuevo Evento
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('dashboard.eventos.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar eventos..." class="dashboard-input w-64">
            <button type="submit" class="dashboard-button-outline">Buscar</button>
            @if(request('search'))
                <a href="{{ route('dashboard.eventos.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">Limpiar</a>
            @endif
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--border-color)]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left dashboard-table-header">Título / Artista</th>
                        <th class="px-6 py-3 text-left dashboard-table-header">Categoría</th>
                        <th class="px-6 py-3 text-left dashboard-table-header">Lugar</th>
                        <th class="px-6 py-3 text-left dashboard-table-header">Estado</th>
                        <th class="px-6 py-3 text-right dashboard-table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[var(--border-color)]">
                    @forelse($eventos as $evento)
                        <tr class="dashboard-table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $evento->title }}</div>
                                <div class="text-sm text-gray-600">{{ $evento->artist ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-{{ strtolower(str_replace(' ', '', $evento->category ?? '')) }}">
                                    {{ $evento->category ?: 'Sin categoría' }}
                                </span>
                                @if($evento->subCategory)
                                    <div class="text-xs text-gray-500 mt-1">{{ $evento->subCategory }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $evento->locationName ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($evento->isPublished)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Publicado</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Borrador</span>
                                @endif
                                @if($evento->isFeatured)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">⭐ Destacado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('dashboard.eventos.edit', $evento) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('dashboard.eventos.destroy', $evento) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No se encontraron eventos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $eventos->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>