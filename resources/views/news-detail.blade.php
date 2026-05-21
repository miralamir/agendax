<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Noticia - BAMARTE</title>
    
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
                <a href="/#mapa" class="hidden md:flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Mapa Cultural</span>
                </a>
            </nav>
        </header>

        <!-- Contenido Principal del Detalle de la Noticia -->
        <main id="news-detail-container" class="py-16">
            <div id="loader-container" class="flex justify-center items-center h-64">
                <div class="loader"></div>
            </div>
            <!-- El contenido de la noticia se insertará aquí -->
        </main>

    </div>

    <!-- Firebase SDK Logic -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getFirestore, doc, getDoc } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

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

        const newsDetailContainer = document.getElementById('news-detail-container');
        const loaderContainer = document.getElementById('loader-container');

        function getNewsIdFromURL() {
            const params = new URLSearchParams(window.location.search);
            return params.get('id');
        }

        async function fetchAndDisplayNewsDetail() {
            const newsId = getNewsIdFromURL();

            if (!newsId) {
                loaderContainer.style.display = 'none';
                newsDetailContainer.innerHTML = `<p class="text-center text-red-500">No se ha especificado un ID de noticia.</p>`;
                return;
            }

            try {
                const newsRef = doc(db, "news", newsId);
                const docSnap = await getDoc(newsRef);

                loaderContainer.style.display = 'none';

                if (docSnap.exists()) {
                    const news = docSnap.data();
                    
                    const publishDate = news.publishDate.toDate();
                    const dateString = publishDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });

                    const newsHTML = `
                        <article class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
                            <img src="${news.mainImageUrl}" alt="${news.title}" class="w-full h-96 object-cover">
                            <div class="p-8 md:p-12">
                                <p class="text-gray-500 mb-2">${dateString}</p>
                                <h1 class="text-3xl md:text-4xl font-black mb-6">${news.title}</h1>
                                <div class="prose max-w-none text-gray-700">
                                    ${news.content.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        </article>
                    `;
                    newsDetailContainer.innerHTML = newsHTML;

                } else {
                    newsDetailContainer.innerHTML = `<p class="text-center text-xl text-gray-500">No se encontró la noticia.</p>`;
                }
            } catch (error) {
                console.error("Error al obtener el detalle de la noticia: ", error);
                loaderContainer.style.display = 'none';
                newsDetailContainer.innerHTML = `<p class="text-center text-red-500">Ocurrió un error al cargar la información de la noticia.</p>`;
            }
        }

        fetchAndDisplayNewsDetail();
    </script>

</body>
</html>
