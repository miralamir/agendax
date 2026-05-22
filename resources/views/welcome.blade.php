<x-app-layout>
    {{-- Dependencias y estilos específicos de la Home --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .hero-text-gradient {
            background: linear-gradient(to right, #a855f7, #d946ef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a855f7;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    {{-- Contenido de la Home --}}
    <main class="text-center py-16 md:py-24">
        <h2 class="text-4xl md:text-6xl font-black mb-4">
            Descubrí el <span class="hero-text-gradient">Arte que te Rodea</span> con BAMARTE
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            Tu guía completa para inauguraciones, muestras actuales, noticias y todos los eventos culturales en tu ciudad.
        </p>
    </main>

    <section id="mapa" class="py-16 bg-white rounded-2xl shadow-lg mb-16 relative z-0">
        <div class="text-center mb-6 px-4">
            <h3 class="text-3xl font-bold">Mapa Cultural Interactivo</h3>
            <p class="text-gray-500 mt-2 mb-6">Encuentra la ubicación de cada evento y descubre qué hay cerca de ti.</p>
            <div class="flex flex-col md:flex-row justify-center items-center gap-4 max-w-4xl mx-auto">
                <button id="btn-locate" class="w-full md:w-auto inline-flex justify-center items-center space-x-2 px-6 py-3 bg-purple-100 text-purple-700 font-bold rounded-full hover:bg-purple-200 transition-colors shadow-sm text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Mi Ubicación</span>
                </button>
                <div class="relative w-full md:w-[450px]">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="map-search-input" placeholder="Buscar artista, lugar u obra..." class="w-full pl-12 pr-24 py-3 rounded-full border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium">
                    <button id="btn-map-search" class="absolute right-2 top-1.5 px-4 py-1.5 bg-purple-600 text-white font-bold rounded-full hover:bg-purple-700 text-sm transition-colors">Buscar</button>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-2 mt-6" id="map-filters">
                <button data-category="todos" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-purple-600 text-white border-purple-600 transition-all">Todos</button>
                <button data-category="arte" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-cyan-600 border-cyan-200 hover:bg-cyan-50 transition-all">Artes Visuales</button>
                <button data-category="musica" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-orange-500 border-orange-200 hover:bg-orange-50 transition-all">Música</button>
                <button data-category="teatro" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-pink-500 border-pink-200 hover:bg-pink-50 transition-all">Teatro</button>
                <button data-category="cine" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-blue-500 border-blue-200 hover:bg-blue-50 transition-all">Cine</button>
                <button data-category="literatura" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-emerald-500 border-emerald-200 hover:bg-emerald-50 transition-all">Literatura</button>
            </div>
        </div>
        <div id="map" class="h-[500px] rounded-lg mx-4 md:mx-8 z-0 border border-gray-100 shadow-inner"></div>
    </section>

    <section id="arte" class="py-16">
        <h3 class="text-3xl font-bold text-center mb-12">Agenda de Destacados</h3>
        <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div id="loader-container" class="col-span-full flex justify-center items-center h-40">
                <div class="loader"></div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getFirestore, collection, getDocs, query, where } from "https://www.gstatic.com/firebasejs/11.6.1/firestore.js";

        const firebaseConfig = {
            apiKey: "YOUR_API_KEY", // Replace with your actual Firebase API key
            authDomain: "zumaq-8bcaa.firebaseapp.com",
            projectId: "zumaq-8bcaa",
            storageBucket: "zumaq-8bcaa.appspot.com",
            messagingSenderId: "812019183354",
            appId: "1:812019183354:web:05a6904d4c5491c4fb2343"
        };
        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        const eventsContainer = document.getElementById('events-container');
        const loaderContainer = document.getElementById('loader-container');
        const map = L.map('map').setView([-34.6037, -58.3816], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        async function fetchAndDisplayFeaturedEvents() {
            // ... (rest of the full JS code from source)
        }
        
        async function fetchAndPlotEvents() {
            // ... (rest of the full JS code from source)
        }

        // Event listeners and other logic
        // ... (rest of the full JS code from source)

        fetchAndDisplayFeaturedEvents();
        fetchAndPlotEvents();
    </script>
</x-app-layout>
