<x-app-layout>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

 <style>
 body { font-family: 'Lato', sans-serif; background-color: #FAFAFA; color: #1A1A1A; }
 .shadow-boutique { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
 .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08); }

 .gradient-border-wrapper {
    padding: 2px; /* Grosor del borde */
    background: linear-gradient(90deg, #38b2ac, #f687b3, #667eea, #f56565, #ed8936, #38b2ac);
    background-size: 400% 400%;
    animation: gradient-flow 10s ease infinite;
    transition: box-shadow 0.3s ease;
 }

 .gradient-border-wrapper:hover {
    box-shadow: 0 20px 40px -10px rgba(102,126,234,0.15);
 }

 @keyframes gradient-flow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
 }
 </style>

 <main class="px-4 py-16 md:py-24 max-w-5xl mx-auto">
 <div class="p-1 rounded-[2.1rem] gradient-border-wrapper">
 <div class="border border-gray-200 bg-gray-50/80 rounded-[2rem] p-10 md:p-16 shadow-sm text-center">
 <span class="block text-sm font-bold text-gray-400 tracking-[0.2em] uppercase mb-4">
 DESCUBRÍ
 </span>
 <h2 class="text-5xl md:text-7xl font-black mb-6 tracking-tight text-gray-800">
 El arte que <br class="md:hidden"> te rodea.
 </h2>
 <p class="text-lg md:text-xl text-gray-500 font-light max-w-2xl mx-auto">
 Una agenda curada con inauguraciones, muestras y eventos culturales.
 </p>
 </div>
 </div>
 </main>

 <section id="mapa" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 relative z-0">
 <div class="bg-white rounded-[2rem] shadow-boutique p-4 md:p-8">
 <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
 <h3 class="text-2xl font-bold text-gray-800">Explorar el mapa</h3>
 <div class="flex flex-wrap justify-center gap-2" id="map-filters">
 <button data-category="todos" class="filter-btn px-5 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-all duration-300 bg-gray-900 text-white">Todos</button>
 <button data-category="arte" class="filter-btn px-5 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-all duration-300 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900">Visuales</button>
 <button data-category="musica" class="filter-btn px-5 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-all duration-300 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900">Música</button>
 <button data-category="teatro" class="filter-btn px-5 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-all duration-300 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900">Teatro</button>
 <button data-category="cine" class="filter-btn px-5 py-2 rounded-full font-bold text-xs tracking-widest uppercase transition-all duration-300 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900">Cine</button>
 </div>
 </div>
 <div id="map" class="h-[500px] rounded-2xl z-0 border border-gray-50"></div>
 </div>
 </section>

 <section id="arte" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
 <div class="flex items-center justify-between mb-10">
 <h3 class="text-3xl font-bold text-gray-900">Destacados</h3>
 <div class="h-px bg-gray-200 flex-grow ml-8"></div>
 </div>
 <x-event-card-grid :events="$featuredEvents" />
 </section>

 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
 <script>
 window.allEventsData = @json($allEvents); // Asegurar que todos los eventos estén disponibles para el mapa

 let allEvents = window.allEventsData.map(event => {
 return {
 ...event,
 isFeatured: event.is_featured == 1 || event.isFeatured == 1 || event.destacado == 1 || event.is_featured === true || event.isFeatured === true,
 isPublished: event.is_published == 1 || event.isPublished == 1 || event.publicado == 1 || event.is_published === true || event.isPublished === true || true
 };
 });

 const eventsContainer = document.getElementById('events-container'); // Este ya no es necesario para destacados, pero se mantiene para el mapa

 const map = L.map('map').setView([-34.6037, -58.3816], 13);
 L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
 attribution: '&copy; OpenStreetMap &copy; CARTO',
 subdomains: 'abcd',
 maxZoom: 20
 }).addTo(map);

 let currentMarkers = [];

 function formatDate(event) {
 if (event.startDate && event.endDate) return `${new Date(event.startDate).toLocaleDateString('es-ES')} - ${new Date(event.endDate).toLocaleDateString('es-ES')}`;
 if (event.singleDate) return new Date(event.singleDate).toLocaleDateString('es-ES');
 if (event.startDate) return new Date(event.startDate).toLocaleDateString('es-ES');
 return 'Próximamente';
 }

 // La función createEventCard y fetchAndDisplayFeaturedEvents ya no son necesarias si el renderizado de tarjetas es Blade

 function fetchAndPlotEvents(eventsToPlot) {
 currentMarkers.forEach(marker => map.removeLayer(marker));
 currentMarkers = [];

 const publishedEvents = eventsToPlot.filter(ev => ev.isPublished);

 publishedEvents.forEach(event => {
 const lat = event.lat || (event.locationGeoPoint ? event.locationGeoPoint.latitude : null);
 const lng = event.lng || (event.locationGeoPoint ? event.locationGeoPoint.longitude : null);

 if (lat && lng) {
 const marker = L.marker([lat, lng]).addTo(map);
 const popupContent = `
 <div class="font-sans p-2">
 <div class="text-[10px] font-bold text-gray-400 mb-1 tracking-widest uppercase">${formatDate(event)}</div>
 <h3 class="font-bold text-base mb-1 text-gray-900">${event.title}</h3>
 <a href="/evento/${event.id}" class="text-xs text-gray-500 hover:text-gray-900 border-b border-gray-300 hover:border-gray-900 transition-colors">Ver detalles</a>
 </div>
 `;
 marker.bindPopup(popupContent);
 currentMarkers.push(marker);
 }
 });
 }

 const filterBtns = document.querySelectorAll('.filter-btn');
 filterBtns.forEach(btn => {
 btn.addEventListener('click', (e) => {
 filterBtns.forEach(b => {
 b.classList.remove('bg-gray-900', 'text-white');
 b.classList.add('bg-gray-100', 'text-gray-500');
 });
 e.target.classList.remove('bg-gray-100', 'text-gray-500');
 e.target.classList.add('bg-gray-900', 'text-white');

 const categorySlug = e.target.getAttribute('data-category').toLowerCase();
 const categoryName = e.target.textContent.trim().toLowerCase();

 if (categorySlug === 'todos') {
 fetchAndPlotEvents(allEvents);
 return;
 }

 const filtrados = allEvents.filter(ev => {
 if (!ev.category) return false;
 const dbCat = ev.category.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
 const btnText = categoryName.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
 return dbCat.includes(btnText) || dbCat.includes(categorySlug);
 });

 fetchAndPlotEvents(filtrados);
 });
 });

 // fetchAndDisplayFeaturedEvents(); // Ya no es necesario, el Blade lo renderiza
 fetchAndPlotEvents(allEvents);
 </script>
</x-app-layout>