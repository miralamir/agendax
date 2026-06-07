<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">Editar Evento</h2>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 text-gray-900">
            <form action="{{ route('dashboard.eventos.update', $evento) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6 flex justify-end items-center bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <a href="{{ route('dashboard.eventos.index') }}" class="dashboard-button-outline mr-3">Cancelar</a>
                    <button type="submit" class="dashboard-button-primary">Actualizar Evento</button>
                </div>

                @include('dashboard.eventos._form', ['evento' => $evento])
                
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('dashboard.eventos.index') }}" class="dashboard-button-outline mr-2">Cancelar</a>
                    <button type="submit" class="dashboard-button-primary">Actualizar Evento</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>