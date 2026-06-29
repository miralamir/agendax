<x-dashboard-layout>
    <h2 class="text-2xl font-black text-gray-900 mb-6">Editar Evento</h2>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[var(--border-color)]">
        <div class="p-6 text-gray-900">
            <form id="evento-form" action="{{ route('dashboard.eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6 flex justify-end items-center gap-2 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <a href="{{ route('dashboard.eventos.index') }}" class="dashboard-button-outline btn-cancelar">Cancelar</a>
                    <button type="submit" name="accion" value="save" class="dashboard-button-outline">Guardar</button>
                    <button type="submit" name="accion" value="save_close" class="dashboard-button-primary">Guardar y cerrar</button>
                </div>

                @include('dashboard.eventos._form', ['evento' => $evento])

                <div class="mt-8 flex justify-end gap-2">
                    <a href="{{ route('dashboard.eventos.index') }}" class="dashboard-button-outline btn-cancelar">Cancelar</a>
                    <button type="submit" name="accion" value="save" class="dashboard-button-outline">Guardar</button>
                    <button type="submit" name="accion" value="save_close" class="dashboard-button-primary">Guardar y cerrar</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>