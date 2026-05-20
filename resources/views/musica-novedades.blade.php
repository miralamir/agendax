<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades de Música - BAMARTE</title>

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
                <a href="index.html" class="text-3xl font-black text-black">BAMARTE</a>
                <ul class="hidden md:flex items-center space-x-8 font-bold text-sm">
                    <!-- Artes Visuales -->
                    <li class="relative group z-50">
                        <a href="arte.html" class="uppercase text-cyan-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Artes Visuales
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40 mt-1">
                            <li><a href="arte-agenda.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="arte-novedades.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="arte-ferias.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                        </ul>
                    </li>
                    <!-- Música -->
                    <li class="relative group z-50">
                        <a href="musica.html" class="uppercase text-orange-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Música
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40 mt-1">
                            <li><a href="musica-agenda.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="musica-novedades.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="musica-festivales.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Teatro -->
                    <li class="relative group z-50">
                        <a href="teatro.html" class="uppercase text-pink-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Teatro
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40 mt-1">
                            <li><a href="teatro-agenda.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="teatro-novedades.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="teatro-festivales.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Cine -->
                    <li class="relative group z-50">
                        <a href="cine.html" class="uppercase text-blue-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Cine
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40 mt-1">
                            <li><a href="cine-agenda.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="cine-novedades.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="cine-festivales.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Festivales</a></li>
                        </ul>
                    </li>
                    <!-- Literatura -->
                    <li class="relative group z-50">
                        <a href="literatura.html" class="uppercase text-emerald-500 hover:opacity-75 transition-opacity duration-300 flex items-center">
                            Literatura
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md py-2 w-40 mt-1">
                            <li><a href="literatura-agenda.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Agenda</a></li>
                            <li><a href="literatura-novedades.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Novedades</a></li>
                            <li><a href="literatura-ferias.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ferias</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="index.html#mapa" class="hidden md:flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Mapa Cultural</span>
                </a>
            </nav>
        </header>

        <!-- Contenido Principal: Todas las Novedades -->
        <main class="py-16">
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-orange-500">Novedades de Música</h2>
                    <p class="text-lg text-gray-600 mt-2">Todas las noticias y artículos del mundo de la música, ordenadas por fecha.</p>
                </div>
                <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div id="news-loader" class="col-span-full flex justify-center items-center h-40">
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

        const newsContainer = document.getElementById('news-container');
        const newsLoader = document.getElementById('news-loader');

        function createNewsCard(newsItem) {
            let dateString = '';
            if (newsItem.publishDate && typeof newsItem.publishDate.toDate === 'function') {
                const publishDate = newsItem.publishDate.toDate();
                dateString = `${publishDate.getDate()} ${publishDate.toLocaleString('es-ES', { month: 'long' })} ${publishDate.getFullYear()}`;
            } else if (newsItem.createdAt && typeof newsItem.createdAt.toDate === 'function') {
                const createdAtDate = newsItem.createdAt.toDate();
                dateString = `${createdAtDate.getDate()} ${createdAtDate.toLocaleString('es-ES', { month: 'long' })} ${createdAtDate.getFullYear()}`;
            }

            return `
            <a href="news-detail.html?id=${newsItem.id}" class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col group">
                <div class="relative">
                    <img src="${newsItem.mainImageUrl || 'https://placehold.co/400x250/ffedd5/fb923c?text=BAMARTE'}" alt="${newsItem.title}" class="w-full h-56 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/400x250/ffedd5/fb923c?text=BAMARTE';">
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <p class="text-xs text-orange-600 font-bold mb-2 uppercase tracking-widest">${dateString}</p>
                    <h4 class="text-xl font-black mb-2 uppercase group-hover:text-orange-600 transition-colors leading-tight">${newsItem.title}</h4>
                    <p class="text-gray-600 text-sm flex-grow font-medium">${(newsItem.content || '').substring(0, 150)}...</p>
                </div>
            </a>`;
        }

        async function fetchAndDisplayAllMusicNews() {
            try {
                const newsRef = collection(db, "news");
                const q = query(
                    newsRef,
                    where("isPublished", "==", true),
                    where("category", "==", "musica"),
                    orderBy("publishDate", "desc")
                );
                const querySnapshot = await getDocs(q);

                newsLoader.style.display = 'none';
                newsContainer.innerHTML = '';

                if (querySnapshot.empty) {
                    newsContainer.innerHTML = `<p class="col-span-full text-center text-gray-500 font-bold uppercase tracking-widest py-10">No hay noticias de música en este momento.</p>`;
                    return;
                }

                querySnapshot.forEach((doc) => {
                    const newsItem = { id: doc.id, ...doc.data() };
                    newsContainer.innerHTML += createNewsCard(newsItem);
                });
            } catch (error) {
                console.error("Error al obtener las noticias de música: ", error);
                newsLoader.style.display = 'none';
                newsContainer.innerHTML = `<p class="col-span-full text-center text-red-500 p-4 bg-red-100 rounded-lg font-bold">Ocurrió un error al cargar las noticias. Revisa la consola (F12) para más detalles.</p>`;
            }
        }

        fetchAndDisplayAllMusicNews();
    </script>

</body>
</html>
