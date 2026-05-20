<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferias de Artes Visuales - BAMARTE</title>
    
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
                    <li class="relative group z-50">
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
                    <li class="relative group z-50">
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
                    <li class="relative group z-50">
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
                    <li class="relative group z-50">
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
                    <li class="relative group z-50">
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
                <a href="/#mapa" class="hidden md:flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Mapa Cultural</span>
                </a>
            </nav>
        </header>

        <!-- Contenido Principal de la Página de Ferias de Arte -->
        <main class="py-16">
            <section class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-cyan-500">Ferias de Artes Visuales</h2>
                    <p class="text-lg text-gray-600 mt-2">Una selección de las ferias de arte más importantes de la ciudad.</p>
                </div>
                <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div id="events-loader" class="col-span-full flex justify-center items-center h-40">
                        <div class="loader"></div>
                    </div>
                </div>
            </section>
        </main>

    </div>

    <!-- Firebase SDK Logic -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getFirestore, collection, getDocs, query, where, orderBy } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

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
        const eventsLoader = document.getElementById('events-loader');

        // Función para crear la tarjeta de Evento
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

            return `
                <a href="event-detail.html?id=${event.id}" class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col group">
                    <div class="relative">
                        <img src="${event.mainImageUrl || 'https://placehold.co/400x250/e9d5ff/a855f7?text=BAMARTE'}" alt="${event.title}" class="w-full h-56 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/400x250/e9d5ff/a855f7?text=BAMARTE';">
                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 text-cyan-700 font-black text-xs px-3 py-1 rounded-full shadow-sm uppercase tracking-widest">${dateString}</div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h4 class="text-xl font-black mb-2 uppercase group-hover:text-cyan-600 transition-colors leading-tight">${event.title}</h4>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-4">${event.locationName || 'A confirmar'}</p>
                        <p class="text-gray-600 text-sm flex-grow font-medium">${(event.description || '').substring(0, 100)}...</p>
                    </div>
                </a>`;
        }

        // Fetch de TODAS las ferias de Arte
        async function fetchAndDisplayArtFairs() {
            try {
                const eventsRef = collection(db, "events");
                // *** CAMBIO PRINCIPAL: Se agregó where("subCategory", "==", "feria") para filtrar por ferias ***
                const q = query(
                    eventsRef, 
                    where("isPublished", "==", true), 
                    where("category", "==", "arte"),
                    where("subCategory", "==", "feria")
                );
                const querySnapshot = await getDocs(q);
                
                eventsLoader.style.display = 'none';
                eventsContainer.innerHTML = '';

                const events = [];
                querySnapshot.forEach((doc) => {
                    events.push({ id: doc.id, ...doc.data() });
                });

                const now = new Date();
                
                // Lógica de filtrado seguro para mostrar solo eventos vigentes
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
                        const timeA = (a.startDate && typeof a.startDate.toMillis === 'function') ? a.startDate.toMillis() : 
                                      ((a.singleDate && typeof a.singleDate.toMillis === 'function') ? a.singleDate.toMillis() : 0);
                        const timeB = (b.startDate && typeof b.startDate.toMillis === 'function') ? b.startDate.toMillis() : 
                                      ((b.singleDate && typeof b.singleDate.toMillis === 'function') ? b.singleDate.toMillis() : 0);
                        return timeA - timeB;
                    });

                if (upcomingEvents.length === 0) {
                    eventsContainer.innerHTML = `<p class="col-span-full text-center text-gray-500 font-bold uppercase tracking-widest py-10">No hay ferias de artes visuales en la agenda en este momento.</p>`;
                } else {
                    upcomingEvents.forEach((event) => {
                        eventsContainer.innerHTML += createEventCard(event);
                    });
                }
            } catch (error) {
                console.error("Error al obtener las ferias de arte: ", error);
                eventsLoader.style.display = 'none';
                eventsContainer.innerHTML = `<p class="col-span-full text-center text-red-500 p-4 bg-red-100 rounded-lg font-bold">Ocurrió un error al cargar las ferias. Es posible que necesites crear un índice compuesto en Firestore.</p>`;
            }
        }

        // Ejecutar función
        fetchAndDisplayArtFairs();
    </script>

</body>
</html>
