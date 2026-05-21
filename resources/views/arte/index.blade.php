<x-slot name="theme">theme-arte</x-slot>
<x-app-layout>
    {{-- Inyectamos los estilos y fuentes del diseño estático. Idealmente, esto se compilaría en el futuro. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        .font-lato {
            font-family: 'Lato', sans-serif;
        }
    </style>

    {{-- El layout de Blade ya proporciona un contenedor, pero usamos el de la maqueta para máxima fidelidad visual. --}}
    <div class="container mx-auto px-4 font-lato">

        <!-- Contenido Principal de la Página de Arte (migrado del HTML estático) -->
        <main class="py-16">
            <!-- Sección Eventos Destacados de Arte -->
            <section class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-cyan-500">Eventos Destacados de Artes Visuales</h2>
                    <p class="text-lg text-gray-600 mt-2">Una selección de los eventos de artes visuales más relevantes.</p>
                </div>
                {{-- Contenedor para la data que vendrá de la base de datos en el futuro --}}
                <div id="featured-events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <p class="col-span-full text-center text-gray-500">Contenido de eventos se cargará aquí dinámicamente.</p>
                </div>
            </section>

            <!-- Sección Noticias Destacadas de Arte -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-cyan-500">Noticias Destacadas de Artes Visuales</h2>
                    <p class="text-lg text-gray-600 mt-2">Las últimas novedades y artículos del mundo de las artes visuales.</p>
                </div>
                {{-- Contenedor para la data que vendrá de la base de datos en el futuro --}}
                <div id="featured-news-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <p class="col-span-full text-center text-gray-500">Contenido de noticias se cargará aquí dinámicamente.</p>
                </div>
            </section>
        </main>
        
    </div>
</x-app-layout>
