<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('dashboard.eventos.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6 flex justify-end items-center bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                            <a href="{{ route('dashboard.eventos.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 font-bold transition mr-3">Cancelar</a>
                            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 font-bold shadow-md shadow-purple-200 transition transform hover:-translate-y-0.5">Guardar Evento</button>
                        </div>

                        @include('dashboard.eventos._form')
                        
                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('dashboard.eventos.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition mr-2">Cancelar</a>
                            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">Guardar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>