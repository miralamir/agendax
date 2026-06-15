<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Cultural - BAMARTE</title>
    
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
        #map { 
            height: calc(100vh - 88px); /* Altura del mapa = 100% de la pantalla menos el header */
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
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40">
                            <li><a href="/arte/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/arte/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="/arte/ferias" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                        </ul>
                    </li>
                    <!-- Música -->
                    <li class="relative group z-50 pb-4">
                        <a href="/musica" class="uppercase text-orange-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Música
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40">
                            <li><a href="/musica/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/musica/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="/musica/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Teatro -->
                    <li class="relative group z-50 pb-4">
                        <a href="/teatro" class="uppercase text-pink-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Teatro
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40">
                            <li><a href="/teatro/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/teatro/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="/teatro/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Cine -->
                    <li class="relative group z-50 pb-4">
                        <a href="/cine" class="uppercase text-blue-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Cine
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40">
                            <li><a href="/cine/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/cine/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="/cine/festivales" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Literatura -->
                    <li class="relative group z-50 pb-4">
                        <a href="/literatura" class="uppercase text-emerald-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Literatura
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40">
                            <li><a href="/literatura/agenda" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="/literatura/novedades" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="/literatura/ferias" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="/mapa" class="hidden md:flex items-center space-x-2 bg-purple-600 text-white rounded-lg px-4 py-2 hover:bg-purple-700 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Mapa Cultural</span>
                </a>
            </nav>
        </header>
    </div>

    <!-- Contenedor del Mapa -->
    <div id="map" class="w-full"></div>

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

        // Inicializar el mapa centrado en Buenos Aires por defecto
        const map = L.map('map').setView([-34.6037, -58.3816], 13);

        // **CAMBIO**: Usamos un nuevo proveedor de mapas (Esri World Topo Map) para un estilo más detallado y colorido.
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
        }).addTo(map);

        // Función para obtener la ubicación del usuario
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    // Centrar el mapa en la ubicación del usuario
                    map.setView([lat, lon], 15);
                    
                    // Añadir un marcador para el usuario
                    L.marker([lat, lon], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(map).bindPopup('<b>¡Estás aquí!</b>').openPopup();

                }, () => {
                    console.log("El usuario no permitió el acceso a la ubicación.");
                });
            } else {
                console.log("Geolocalización no es soportada por este navegador.");
            }
        }

        // Función para obtener y mostrar los eventos en el mapa
        async function fetchAndPlotEvents() {
            try {
                const eventsRef = collection(db, "events");
                const q = query(eventsRef, where("isPublished", "==", true));
                const querySnapshot = await getDocs(q);

                querySnapshot.forEach((doc) => {
                    const event = { id: doc.id, ...doc.data() };

                    // Comprobar si el evento tiene coordenadas
                    if (event.locationGeoPoint && event.locationGeoPoint.latitude && event.locationGeoPoint.longitude) {
                        const { latitude, longitude } = event.locationGeoPoint;
                        
                        // Crear un marcador para el evento
                        const marker = L.marker([latitude, longitude]).addTo(map);
                        
                        // Crear el contenido del popup (la ventana que se abre al hacer clic)
                        const popupContent = `
                            <div class="font-sans">
                                <h3 class="font-bold text-lg mb-1">${event.title}</h3>
                                <p class="text-gray-600 mb-2">${event.locationName}</p>
                                <a href="event-detail.html?id=${event.id}" class="text-purple-600 font-semibold hover:underline">Ver más detalles</a>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                    }
                });
            } catch (error) {
                console.error("Error al obtener los eventos para el mapa: ", error);
            }
        }

        // Iniciar todo
        getUserLocation();
        fetchAndPlotEvents();
    </script>

</body>
</html>
