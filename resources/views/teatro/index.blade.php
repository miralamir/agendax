<x-app-layout>
    <main>
        <!-- 1. Category Header -->
        <header class="py-12" style="background-color: var(--color-teatro-light); border-bottom: 3px solid var(--color-teatro);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-black" style="color: var(--color-teatro);">Teatro</h1>
            </div>
        </header>

        <!-- 2. Subcategory Filters (Pills) -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full text-white" style="background-color: var(--color-teatro);">Todos</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-teatro); border-color: var(--color-teatro); background-color: transparent;">Cartelera</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-teatro); border-color: var(--color-teatro); background-color: transparent;">Festivales</a>
                    <a href="#" class="px-4 py-2 text-sm font-bold rounded-full border" style="color: var(--color-teatro); border-color: var(--color-teatro); background-color: transparent;">Novedades</a>
                </div>
            </div>
        </nav>

        <!-- 3. Destacados Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="section-title">Destacados</h2>
                    <a href="#" class="text-sm font-bold" style="color: var(--color-teatro);">Ver todos →</a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Nota Grande (Izquierda) -->
                    <div class="lg:col-span-2">
                        <!-- Placeholder para card destacada grande -->
                        <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center">
                            <p class="text-gray-500">Nota destacada principal</p>
                        </div>
                    </div>
                    <!-- Stack de Notas Pequeñas (Derecha) -->
                    <div class="space-y-4">
                        <!-- Placeholder para 3 notas pequeñas -->
                        <div class="p-4 border border-[var(--border-color)] rounded-lg">
                            <p class="font-bold text-gray-800">Título de nota pequeña</p>
                        </div>
                        <div class="p-4 border border-[var(--border-color)] rounded-lg">
                            <p class="font-bold text-gray-800">Otra nota interesante</p>
                        </div>
                        <div class="p-4 border border-[var(--border-color)] rounded-lg">
                            <p class="font-bold text-gray-800">Tercera noticia</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Últimos Posts Section -->
        <section class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="section-title">Últimos Posts</h2>
                    <a href="#" class="text-sm font-bold" style="color: var(--color-teatro);">Ver todos →</a>
                </div>
                <!-- Grilla de 3 columnas para los posts -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Placeholder para las cards de los posts -->
                    @for ($i = 0; $i < 6; $i++)
                        <div class="bg-white rounded-lg border border-[var(--border-color)] h-64 flex items-center justify-center">
                            <p class="text-gray-500">Post #{{ $i + 1 }}</p>
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
