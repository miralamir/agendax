<x-app-layout>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

 <style>
 body { font-family: 'Lato', sans-serif; background-color: #FAFAFA; color: #1A1A1A; }
 .shadow-boutique { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
 .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08); }
 </style>

 <main class="px-4 py-16 md:py-24 max-w-5xl mx-auto">
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
 <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
 <!-- Cargado vía JS -->
 </div>
 </section>

 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
 <script>
 window.allEventsData = @json(\App\Models\Evento::all());

 let allEvents = window.allEventsData.map(event => {
 return {
 ...event,
 isFeatured: event.is_featured == 1 || event.isFeatured == 1 || event.destacado == 1 || event.is_featured === true || event.isFeatured === true,
 isPublished: event.is_published == 1 || event.isPublished == 1 || event.publicado == 1 || event.is_published === true || event.isPublished === true || true
 };
 });

 const eventsContainer = document.getElementById('events-container');

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

 function createEventCard(event) {
 const imageUrl = event.mainImageUrl || 'https://via.placeholder.com/600x400?text=BAMARTE';
 return `
 <a href="/evento/${event.id}" class="group block bg-white rounded-2xl shadow-boutique hover-lift transition-all duration-500 overflow-hidden border border-gray-50">
 <div class="relative h-64 overflow-hidden">
 <img src="${imageUrl}" alt="${event.title}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
 </div>
 <div class="p-8">
 <div class="text-xs font-bold text-gray-400 mb-3 tracking-widest uppercase">${formatDate(event)}</div>
 <h4 class="text-2xl font-bold mb-3 text-gray-900 leading-tight">${event.title}</h4>
 <p class="text-gray-500 font-light mb-6 line-clamp-2">${event.locationName || ''}</p>
 <div class="inline-flex items-center space-x-2 text-sm font-bold text-gray-900 border-b border-gray-900 pb-1 group-hover:text-gray-500 group-hover:border-gray-500 transition-colors">
 <span>Ver detalles</span>
 </div>
 </div>
 </a>
 `;
 }

 function fetchAndDisplayFeaturedEvents() {
 eventsContainer.innerHTML = '';
 const destacados = allEvents.filter(ev => ev.isPublished && ev.isFeatured);
 const top5 = destacados.sort((a, b) => b.id - a.id).slice(0, 5);

 if (top5.length === 0) {
 eventsContainer.innerHTML = '<p class="col-span-full text-center text-gray-400 font-light">No hay eventos destacados en este momento.</p>';
 return;
 }

 top5.forEach(event => {
 eventsContainer.innerHTML += createEventCard(event);
 });
 }

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

 fetchAndDisplayFeaturedEvents();
 fetchAndPlotEvents(allEvents);
 </script>
</x-app-layout>