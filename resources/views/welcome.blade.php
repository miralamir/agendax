<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAMARTE - Tu Guía Cultural</title>
    
    <!-- Leaflet CSS (para el mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Lato -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f8fafc;
        }
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
</head>
<body class="text-gray-800">

    <!-- Contenedor Principal -->
    <div class="container mx-auto px-4">

        <!-- Header y Navegación -->
        <header class="py-6">
            <nav class="flex justify-between items-center">
                <a href="/" class="text-3xl font-black text-black">BAMARTE</a>
                <ul class="hidden md:flex items-center space-x-8 font-bold text-sm">
                    <!-- Artes Visuales -->
                    <li class="relative group z-50 pb-4">
                        <a href="/arte" class="uppercase text-cyan-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Artes Visuales
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48">
                            <li><a href="/arte/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Creadores</a></li>
                            <li><a href="/arte/ferias" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                            <li><a href="/arte/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Noticias</a></li>
                        </ul>
                    </li>
                    <!-- Música -->
                    <li class="relative group z-50 pb-4">
                        <a href="/musica" class="uppercase text-orange-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Música
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48">
                            <li><a href="/musica/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Lanzamientos</a></li>
                            <li><a href="/musica/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                            <li><a href="/musica/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Noticias</a></li>
                        </ul>
                    </li>
                    <!-- Teatro -->
                    <li class="relative group z-50 pb-4">
                        <a href="/teatro" class="uppercase text-pink-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Teatro
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48">
                            <li><a href="/teatro/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Cartelera</a></li>
                            <li><a href="/teatro/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                            <li><a href="/teatro/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Noticias</a></li>
                        </ul>
                    </li>
                    <!-- Cine -->
                    <li class="relative group z-50 pb-4">
                        <a href="/cine" class="uppercase text-blue-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Cine
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48">
                            <li><a href="/cine/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Estrenos</a></li>
                            <li><a href="/cine/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales / Ciclos</a></li>
                            <li><a href="/cine/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Noticias</a></li>
                        </ul>
                    </li>
                    <!-- Literatura -->
                    <li class="relative group z-50 pb-4">
                        <a href="/literatura" class="uppercase text-emerald-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Literatura
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-48">
                            <li><a href="/literatura/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/literatura/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades Editoriales</a></li>
                            <li><a href="/literatura/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Noticias</a></li>
                            <li><a href="/literatura/ferias" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="/#mapa" class="hidden md:flex items-center space-x-2 bg-purple-600 text-white rounded-lg px-4 py-2 hover:bg-purple-700 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Mapa Cultural</span>
                </a>
            </nav>
        </header>

        <!-- Sección Hero -->
        <main class="text-center py-16 md:py-24">
            <h2 class="text-4xl md:text-6xl font-black mb-4">
                Descubrí el <span class="hero-text-gradient">Arte que te Rodea</span> con BAMARTE
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Tu guía completa para inauguraciones, muestras actuales, noticias y todos los eventos culturales en tu ciudad.
            </p>
        </main>

        <!-- Sección Mapa Cultural Interactivo -->
        <section id="mapa" class="py-16 bg-white rounded-2xl shadow-lg mb-16 relative z-0">
            <div class="text-center mb-6 px-4">
                <h3 class="text-3xl font-bold">Mapa Cultural Interactivo</h3>
                <p class="text-gray-500 mt-2 mb-6">Encuentra la ubicación de cada evento y descubre qué hay cerca de ti.</p>
                
                <!-- Herramientas del Mapa: Geolocalización + Buscador -->
                <div class="flex flex-col md:flex-row justify-center items-center gap-4 max-w-4xl mx-auto">
                    <!-- Botón de Geolocalización -->
                    <button id="btn-locate" class="w-full md:w-auto inline-flex justify-center items-center space-x-2 px-6 py-3 bg-purple-100 text-purple-700 font-bold rounded-full hover:bg-purple-200 transition-colors shadow-sm text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Mi Ubicación</span>
                    </button>

                    <!-- NUEVO: Barra de Búsqueda Inteligente -->
                    <div class="relative w-full md:w-[450px]">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="map-search-input" placeholder="Buscar artista, lugar u obra..." class="w-full pl-12 pr-24 py-3 rounded-full border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium">
                        <button id="btn-map-search" class="absolute right-2 top-1.5 px-4 py-1.5 bg-purple-600 text-white font-bold rounded-full hover:bg-purple-700 text-sm transition-colors">Buscar</button>
                    </div>
                </div>

                <!-- Filtros por Categoría -->
                <div class="flex flex-wrap justify-center gap-2 mt-6" id="map-filters">
                    <button data-category="todos" data-active="bg-purple-600 text-white border-purple-600" data-inactive="bg-white text-purple-600 border-purple-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-purple-600 text-white border-purple-600 transition-all">Todos</button>
                    <button data-category="arte" data-active="bg-cyan-500 text-white border-cyan-500" data-inactive="bg-white text-cyan-600 border-cyan-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-cyan-600 border-cyan-200 hover:bg-cyan-50 transition-all">Artes Visuales</button>
                    <button data-category="musica" data-active="bg-orange-500 text-white border-orange-500" data-inactive="bg-white text-orange-500 border-orange-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-orange-500 border-orange-200 hover:bg-orange-50 transition-all">Música</button>
                    <button data-category="teatro" data-active="bg-pink-500 text-white border-pink-500" data-inactive="bg-white text-pink-500 border-pink-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-pink-500 border-pink-200 hover:bg-pink-50 transition-all">Teatro</button>
                    <button data-category="cine" data-active="bg-blue-500 text-white border-blue-500" data-inactive="bg-white text-blue-500 border-blue-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-blue-500 border-blue-200 hover:bg-blue-50 transition-all">Cine</button>
                    <button data-category="literatura" data-active="bg-emerald-500 text-white border-emerald-500" data-inactive="bg-white text-emerald-500 border-emerald-200" class="filter-btn px-4 py-2 rounded-full font-bold text-sm shadow-sm border bg-white text-emerald-500 border-emerald-200 hover:bg-emerald-50 transition-all">Literatura</button>
                </div>
            </div>
            
            <div id="map" class="h-[500px] rounded-lg mx-4 md:mx-8 z-0 border border-gray-100 shadow-inner"></div>
        </section>

        <!-- Sección Agenda de Destacados -->
        <section id="arte" class="py-16">
            <h3 class="text-3xl font-bold text-center mb-12">Agenda de Destacados</h3>
            <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div id="loader-container" class="col-span-full flex justify-center items-center h-40">
                    <div class="loader"></div>
                </div>
            </div>
        </section>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Firebase SDK Logic -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getFirestore, collection, getDocs, query, where } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyBWlNhxc1smYH8szpjpXLMbjPXHIts-nMc",
            authDomain: "zumaq-8bcaa.firebaseapp.com",
            projectId: "zumaq-8bcaa",
            storageBucket: "zumaq-8bcaa.firebasestorage.app", 
            messagingSenderId: "812019183354",
            appId: "1:812019183354:web:05a6904d4c5491c4fb2343",
            measurementId: "G-DJJS3XS1RL"
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);

        const eventsContainer = document.getElementById('events-container');
        const loaderContainer = document.getElementById('loader-container');

        // --- LÓGICA DEL MAPA ---
        const map = L.map('map').setView([-34.6037, -58.3816], 13); // Centrado en Buenos Aires
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom...'
        }).addTo(map);

        let mapMarkersGroup = L.layerGroup().addTo(map);
        let allValidMapEvents = []; // Todos los eventos activos descargados
        
        // Variables de estado de los filtros
        let currentMapCategory = 'todos';
        let currentSearchTerm = '';

        // -- Geolocalización del Usuario --
        let userMarker = null;
        let userCircle = null;

        document.getElementById('btn-locate').addEventListener('click', () => {
            map.locate({setView: true, maxZoom: 14});
        });

        map.on('locationfound', (e) => {
            const radius = e.accuracy / 2;
            if (userMarker) map.removeLayer(userMarker);
            if (userCircle) map.removeLayer(userCircle);

            userMarker = L.marker(e.latlng).addTo(map).bindPopup("<b class='text-purple-600'>¡Estás aquí!</b>").openPopup();
            userCircle = L.circle(e.latlng, radius, {color: '#a855f7', fillColor: '#a855f7', fillOpacity: 0.2}).addTo(map);
        });

        map.on('locationerror', (e) => {
            alert("No pudimos obtener tu ubicación. Asegúrate de darle permisos al navegador.");
        });

        // -- Lógica de Filtrado de Botones (Categorías) --
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                currentMapCategory = e.currentTarget.getAttribute('data-category');
                
                // Actualizar UI botones
                filterButtons.forEach(b => {
                    const activeClasses = b.getAttribute('data-active').split(' ');
                    const inactiveClasses = b.getAttribute('data-inactive').split(' ');
                    if (b === e.currentTarget) {
                        b.classList.remove(...inactiveClasses);
                        b.classList.add(...activeClasses);
                    } else {
                        b.classList.remove(...activeClasses);
                        b.classList.add(...inactiveClasses);
                    }
                });
                renderMapMarkers();
            });
        });

        // -- NUEVA Lógica de Buscador de Texto --
        const searchInput = document.getElementById('map-search-input');
        const searchBtn = document.getElementById('btn-map-search');

        // Filtra en tiempo real mientras el usuario escribe
        searchInput.addEventListener('input', (e) => {
            currentSearchTerm = e.target.value.toLowerCase().trim();
            renderMapMarkers();
        });

        // Función para acercar el mapa a los resultados encontrados
        function zoomToSearchResults() {
            if (!currentSearchTerm) return; // Si está vacío, no hacer zoom
            
            const layers = mapMarkersGroup.getLayers();
            if (layers.length > 0) {
                // Crear un grupo invisible con los marcadores actuales para calcular el centro
                const featureGroup = new L.featureGroup(layers);
                // Hacer zoom ajustado a los pines visibles
                map.fitBounds(featureGroup.getBounds(), { padding: [50, 50], maxZoom: 15 });
                
                // Si solo hay 1 resultado, abrir automáticamente su popup
                if (layers.length === 1) {
                    layers[0].openPopup();
                }
            } else {
                alert("No se encontraron eventos o lugares con esa búsqueda.");
            }
        }

        // Ejecutar zoom al dar clic en Buscar o presionar Enter
        searchBtn.addEventListener('click', zoomToSearchResults);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                zoomToSearchResults();
            }
        });

        // -- Función Maestra que Dibuja los Marcadores --
        function renderMapMarkers() {
            mapMarkersGroup.clearLayers();

            const filteredEvents = allValidMapEvents.filter(event => {
                // 1. Filtrar por categoría
                const matchCategory = currentMapCategory === 'todos' || event.category === currentMapCategory;
                
                // 2. Filtrar por búsqueda de texto
                let matchSearch = true;
                if (currentSearchTerm) {
                    const title = (event.title || '').toLowerCase();
                    const loc = (event.locationName || '').toLowerCase();
                    // Buscar en el array de artistas o en el campo string (según como se haya guardado)
                    const art = Array.isArray(event.artists) ? event.artists.join(' ').toLowerCase() : (event.artist || '').toLowerCase();
                    
                    matchSearch = title.includes(currentSearchTerm) || loc.includes(currentSearchTerm) || art.includes(currentSearchTerm);
                }

                return matchCategory && matchSearch;
            });

            filteredEvents.forEach(event => {
                // --- NUEVA LÓGICA INTELIGENTE DE COORDENADAS ---
                // Le da prioridad a los campos de texto y cambia comas por puntos automáticamente
                let lat = event.latitude ? parseFloat(event.latitude.toString().replace(',', '.')) : null;
                let lng = event.longitude ? parseFloat(event.longitude.toString().replace(',', '.')) : null;
                
                if (!lat || !lng) {
                    if (event.locationGeoPoint) {
                        lat = event.locationGeoPoint.latitude;
                        lng = event.locationGeoPoint.longitude;
                    }
                }

                // Si logramos obtener coordenadas válidas, dibujamos el pin
                if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                    const marker = L.marker([lat, lng]);
                    
                    // Colores temáticos en el popup y cambio de nombre a Artes Visuales
                    let catColor = "purple";
                    let displayCategory = event.category || 'Evento';
                    
                    if(event.category === 'arte') { catColor = "cyan"; displayCategory = "Artes Visuales"; }
                    if(event.category === 'musica') catColor = "orange";
                    if(event.category === 'teatro') catColor = "pink";
                    if(event.category === 'cine') catColor = "blue";
                    if(event.category === 'literatura') catColor = "emerald";

                    const popupContent = `
                        <div class="font-sans">
                            <span class="text-[10px] font-black uppercase text-${catColor}-500">${displayCategory}</span>
                            <h3 class="font-bold text-lg leading-tight mb-1 mt-1">${event.title}</h3>
                            <p class="text-gray-600 text-sm mb-2 font-medium">${event.locationName}</p>
                            <a href="event-detail.html?id=${event.id}" class="inline-block px-3 py-1 bg-${catColor}-600 text-white rounded text-xs font-bold hover:opacity-80 transition">Ver detalle</a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    mapMarkersGroup.addLayer(marker);
                }
            });
        }

        // -- Cargar Eventos desde Firebase --
        async function fetchAndPlotEvents() {
            try {
                const eventsRef = collection(db, "events");
                const q = query(eventsRef, where("isPublished", "==", true));
                const querySnapshot = await getDocs(q);
                
                const now = new Date();

                // Llenamos el array global con los eventos que tienen coordenadas y no caducaron
                querySnapshot.forEach((doc) => {
                    const event = { id: doc.id, ...doc.data() };
                    
                    // Lógica segura de fechas para el Mapa
                    const hasEnd = event.endDate && typeof event.endDate.toDate === 'function';
                    const hasSingle = event.singleDate && typeof event.singleDate.toDate === 'function';
                    const hasStart = event.startDate && typeof event.startDate.toDate === 'function';
                    
                    let isValidEvent = true;
                    if (hasEnd) {
                        isValidEvent = event.endDate.toDate() >= now;
                    } else if (hasSingle) {
                        isValidEvent = event.singleDate.toDate() >= now;
                    } else if (hasStart) {
                        isValidEvent = event.startDate.toDate() >= now;
                    }

                    // --- NUEVA VALIDACIÓN DE COORDENADAS ---
                    let lat = event.latitude ? parseFloat(event.latitude.toString().replace(',', '.')) : null;
                    let lng = event.longitude ? parseFloat(event.longitude.toString().replace(',', '.')) : null;
                    let hasCoords = (lat && lng && !isNaN(lat) && !isNaN(lng)) || event.locationGeoPoint;

                    if (isValidEvent && hasCoords) {
                        allValidMapEvents.push(event);
                    }
                });

                // Al terminar de descargar, renderizamos todos por defecto
                renderMapMarkers();

            } catch (error) {
                console.error("Error al obtener eventos para el mapa: ", error);
            }
        }
        
        // --- LÓGICA DE AGENDA DESTACADOS (Grilla inferior) ---
        async function fetchAndDisplayFeaturedEvents() {
            try {
                const eventsRef = collection(db, "events");
                const q = query(eventsRef, where("isPublished", "==", true), where("isFeatured", "==", true));
                const querySnapshot = await getDocs(q);
                
                loaderContainer.style.display = 'none';
                eventsContainer.innerHTML = '';

                const events = [];
                querySnapshot.forEach((doc) => {
                    events.push({ id: doc.id, ...doc.data() });
                });

                const now = new Date();
                const upcomingEvents = events
                    .filter(event => {
                        const hasEnd = event.endDate && typeof event.endDate.toDate === 'function';
                        const hasSingle = event.singleDate && typeof event.singleDate.toDate === 'function';
                        const hasStart = event.startDate && typeof event.startDate.toDate === 'function';

                        if (hasEnd) return event.endDate.toDate() >= now;
                        if (hasSingle) return event.singleDate.toDate() >= now;
                        if (hasStart) return event.startDate.toDate() >= now;
                        return true; 
                    })
                    .sort((a, b) => {
                        const timeA = (a.startDate && typeof a.startDate.toMillis === 'function') ? a.startDate.toMillis() : 0;
                        const timeB = (b.startDate && typeof b.startDate.toMillis === 'function') ? b.startDate.toMillis() : 0;
                        return timeA - timeB;
                    });

                if (upcomingEvents.length === 0) {
                    eventsContainer.innerHTML = `<p class="col-span-full text-center text-gray-500">No hay eventos destacados en este momento.</p>`;
                } else {
                    upcomingEvents.forEach((event) => {
                        let dateString = 'Próximamente';
                        if (event.startDate && event.endDate && typeof event.startDate.toDate === 'function' && typeof event.endDate.toDate === 'function') {
                            const sd = event.startDate.toDate();
                            const ed = event.endDate.toDate();
                            dateString = `${sd.getDate()} ${sd.toLocaleString('es-ES', { month: 'short' })} - ${ed.getDate()} ${ed.toLocaleString('es-ES', { month: 'short' })}`;
                        } else if (event.singleDate && typeof event.singleDate.toDate === 'function') {
                            const sDate = event.singleDate.toDate();
                            dateString = `${sDate.getDate()} ${sDate.toLocaleString('es-ES', { month: 'short' })}`;
                        } else if (event.recurringSchedule) {
                            dateString = 'Recurrente';
                        }

                        let borderColor = "slate-100";
                        let displayCategory = event.category || 'Evento';
                        
                        if(event.category === 'arte') { borderColor = "cyan-200"; displayCategory = "Artes Visuales"; }
                        if(event.category === 'musica') borderColor = "orange-200";
                        if(event.category === 'teatro') borderColor = "pink-200";
                        if(event.category === 'cine') borderColor = "blue-200";
                        if(event.category === 'literatura') borderColor = "emerald-200";

                        const eventCard = `
                            <a href="event-detail.html?id=${event.id}" class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col group border border-${borderColor}">
                                <div class="relative">
                                    <img src="${event.mainImageUrl || 'https://placehold.co/400x250/e9d5ff/a855f7?text=BAMARTE'}" alt="${event.title}" class="w-full h-56 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/400x250/e9d5ff/a855f7?text=BAMARTE';">
                                    <div class="absolute top-2 right-2 bg-white bg-opacity-90 text-gray-800 font-black text-xs px-3 py-1 rounded-full shadow-sm uppercase">${dateString}</div>
                                    <div class="absolute top-2 left-2 bg-black bg-opacity-80 text-white font-black text-[10px] px-2 py-1 rounded shadow-sm uppercase tracking-widest">${displayCategory}</div>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <h4 class="text-xl font-black mb-2 uppercase group-hover:opacity-80 transition-colors leading-tight">${event.title}</h4>
                                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-4">${event.locationName || 'Ubicación no especificada'}</p>
                                    <p class="text-gray-600 text-sm flex-grow font-medium">${(event.description || '').substring(0, 100)}...</p>
                                </div>
                            </a>`;
                        eventsContainer.innerHTML += eventCard;
                    });
                }
            } catch (error) {
                console.error("Error al obtener los eventos destacados: ", error);
                loaderContainer.style.display = 'none';
            }
        }

        // Ejecutar funciones al cargar
        fetchAndDisplayFeaturedEvents();
        fetchAndPlotEvents();
    </script>

</body>
</html>