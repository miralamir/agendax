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
        <div class="hero-card max-w-4xl mx-auto">
            <span class="intro-tag">Descubrí</span>
            <h2 class="text-4xl md:text-6xl font-black mb-4">
                El <span class="hero-text-gradient">Arte que te Rodea</span>
            </h2>
            <p class="text-lg md:text-xl max-w-3xl mx-auto">
                Tu guía completa de inauguraciones, muestras, noticias y todos los eventos culturales de la ciudad.
            </p>
        </div>
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
        
        // Inicializar el mapa centrado en Buenos Aires por defecto
        const map = L.map('map').setView([-34.6037, -58.3816], 13);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
        }).addTo(map);

        let allEvents = [];
        let currentMarkers = [];

        function createEventCard(event) {
            let dateString = 'Próximamente';
            const hasStart = event.startDate && typeof event.startDate.toDate === 'function';
            const hasEnd = event.endDate && typeof event.endDate.toDate === 'function';
            const hasSingle = event.singleDate && typeof event.singleDate.toDate === 'function';
            
            if (hasStart && hasEnd) {
                const startDate = event.startDate.toDate();
                const endDate = event.endDate.toDate();
                dateString = `${startDate.getDate()} ${startDate.toLocaleString('es-ES', { month: 'short' })} - ${endDate.getDate()} ${endDate.toLocaleString('es-ES', { month: 'short' })}`;
            } else if (hasSingle) {
                const sDate = event.singleDate.toDate();
                dateString = `${sDate.getDate()} ${sDate.toLocaleString('es-ES', { month: 'short' })}`;
            } else if (hasStart && !hasEnd) {
                const startDate = event.startDate.toDate();
                dateString = `${startDate.getDate()} ${startDate.toLocaleString('es-ES', { month: 'short' })}`;
            } else if (event.recurringSchedule) {
                dateString = 'Recurrente';
            }

            const image = (event.gallery && event.gallery.length > 0) ? event.gallery[0] : 'https://via.placeholder.com/400x300?text=BAMARTE';
            
            return `
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <img src="${image}" alt="${event.title}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm font-bold text-purple-600 mb-2 uppercase tracking-wider">${dateString}</div>
                        <h4 class="text-xl font-bold mb-2 text-gray-800">${event.title}</h4>
                        <p class="text-gray-600 mb-4 line-clamp-2">${event.locationName || ''}</p>
                        <a href="/event-detail.html?id=${event.id}" class="inline-flex items-center space-x-2 text-purple-600 font-bold hover:text-purple-800 transition-colors">
                            <span>Ver detalles</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            `;
        }

        async function fetchAndDisplayFeaturedEvents() {
            try {
                const eventsRef = collection(db, "events");
                const q = query(eventsRef, where("isPublished", "==", true), where("isFeatured", "==", true));
                const querySnapshot = await getDocs(q);
                
                if(loaderContainer) loaderContainer.style.display = 'none';
                eventsContainer.innerHTML = '';

                let activeEventsCount = 0;
                const now = new Date();
                now.setHours(0, 0, 0, 0);

                querySnapshot.forEach((doc) => {
                    const event = { id: doc.id, ...doc.data() };
                    
                    let eventDate = null;
                    if (event.endDate && typeof event.endDate.toDate === 'function') {
                        eventDate = event.endDate.toDate();
                    } else if (event.singleDate && typeof event.singleDate.toDate === 'function') {
                        eventDate = event.singleDate.toDate();
                    } else if (event.startDate && typeof event.startDate.toDate === 'function') {
                        eventDate = event.startDate.toDate();
                    }

                    if (eventDate) {
                        eventDate.setHours(23, 59, 59, 999);
                    }

                    if (!eventDate || eventDate >= now || event.recurringSchedule) {
                        eventsContainer.innerHTML += createEventCard(event);
                        activeEventsCount++;
                    }
                });
                
                if (activeEventsCount === 0) {
                    eventsContainer.innerHTML = '<p class="col-span-full text-center text-gray-500 font-bold">No hay eventos destacados en este momento.</p>';
                }
            } catch (error) {
                console.error("Error fetching featured events:", error);
                if(loaderContainer) loaderContainer.style.display = 'none';
                eventsContainer.innerHTML = '<p class="col-span-full text-center text-red-500 font-bold">Error al cargar la agenda.</p>';
            }
        }
        
        async function fetchAndPlotEvents() {
            try {
                const eventsRef = collection(db, "events");
                const q = query(eventsRef, where("isPublished", "==", true));
                const querySnapshot = await getDocs(q);

                allEvents = [];
                const now = new Date();
                now.setHours(0, 0, 0, 0);

                querySnapshot.forEach((doc) => {
                    const event = { id: doc.id, ...doc.data() };
                    
                    let eventDate = null;
                    if (event.endDate && typeof event.endDate.toDate === 'function') {
                        eventDate = event.endDate.toDate();
                    } else if (event.singleDate && typeof event.singleDate.toDate === 'function') {
                        eventDate = event.singleDate.toDate();
                    } else if (event.startDate && typeof event.startDate.toDate === 'function') {
                        eventDate = event.startDate.toDate();
                    }

                    if (eventDate) {
                        eventDate.setHours(23, 59, 59, 999);
                    }

                    if (!eventDate || eventDate >= now || event.recurringSchedule) {
                        allEvents.push(event);
                    }
                });
                renderMapMarkers(allEvents);
            } catch (error) {
                console.error("Error al obtener los eventos para el mapa: ", error);
            }
        }
        
        function renderMapMarkers(events) {
            currentMarkers.forEach(marker => map.removeLayer(marker));
            currentMarkers = [];
            
            events.forEach(event => {
                if (event.locationGeoPoint && event.locationGeoPoint.latitude && event.locationGeoPoint.longitude) {
                    const marker = L.marker([event.locationGeoPoint.latitude, event.locationGeoPoint.longitude]).addTo(map);
                    const popupContent = `
                        <div class="font-sans">
                            <h3 class="font-bold text-lg mb-1">${event.title}</h3>
                            <p class="text-gray-600 mb-2">${event.locationName || ''}</p>
                            <a href="/event-detail.html?id=${event.id}" class="text-purple-600 font-semibold hover:underline">Ver más detalles</a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    currentMarkers.push(marker);
                }
            });
        }

        document.getElementById('btn-locate')?.addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    map.setView([lat, lon], 15);
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
                    console.log("No se pudo acceder a la ubicación.");
                });
            }
        });

        document.getElementById('btn-map-search')?.addEventListener('click', () => {
            const val = document.getElementById('map-search-input').value.toLowerCase();
            const filtered = allEvents.filter(e => e.title.toLowerCase().includes(val) || (e.locationName && e.locationName.toLowerCase().includes(val)));
            renderMapMarkers(filtered);
            if(filtered.length > 0 && filtered[0].locationGeoPoint) {
                map.setView([filtered[0].locationGeoPoint.latitude, filtered[0].locationGeoPoint.longitude], 14);
            }
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.target.getAttribute('data-category');
                if(category === 'todos') {
                    renderMapMarkers(allEvents);
                } else {
                    renderMapMarkers(allEvents.filter(ev => ev.category === category));
                }
            });
        });

        fetchAndDisplayFeaturedEvents();
        fetchAndPlotEvents();
    </script>
</x-app-layout>
