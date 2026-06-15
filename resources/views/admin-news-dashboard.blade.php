<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Novedades - BAMARTE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
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
        /* Estilos para el modal de confirmación */
        .modal-overlay {
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="hidden md:flex flex-col w-64 bg-white">
            <div class="flex items-center justify-center h-16 bg-white border-b">
                <h1 class="text-2xl font-bold text-purple-600">BAMARTE</h1>
            </div>
            <div class="flex flex-col flex-grow p-4">
                <nav class="flex-grow space-y-2">
                    <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        Eventos
                    </a>
                    <a href="admin-news-dashboard.html" class="flex items-center px-4 py-2 text-white bg-purple-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-1-5h.01"/></svg>
                        Novedades
                    </a>
                </nav>
                <button id="logout-button" class="w-full mt-4 bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition-colors duration-300">
                    Cerrar Sesión
                </button>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <div class="flex items-center justify-between h-16 bg-white border-b px-4">
                <h2 class="text-xl font-semibold text-gray-700">Gestionar Novedades</h2>
                <div id="user-email" class="text-sm text-gray-500"></div>
            </div>
            <div class="p-4 md:p-8">
                <div class="flex justify-end mb-4">
                    <a href="admin-add-news.html" class="bg-cyan-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-cyan-600 transition-colors duration-300">
                        + Añadir Novedad
                    </a>
                </div>
                
                <!-- Tabla de Novedades -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titular</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha de Publicación</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="news-table-body">
                            <tr>
                                <td colspan="4" class="text-center p-8">
                                    <div class="loader mx-auto"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden modal-overlay">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full">
            <h3 class="text-2xl font-bold text-center mb-4">Confirmar Eliminación</h3>
            <p class="text-gray-600 text-center mb-6">¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.</p>
            <div class="flex justify-center space-x-4">
                <button id="cancel-delete-btn" class="bg-gray-200 text-gray-800 font-bold py-2 px-6 rounded-lg hover:bg-gray-300">Cancelar</button>
                <button id="confirm-delete-btn" class="bg-red-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-red-600">Eliminar</button>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, collection, getDocs, query, orderBy, doc, deleteDoc } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

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
        const auth = getAuth(app);
        const db = getFirestore(app);

        const userEmailElement = document.getElementById('user-email');
        const newsTableBody = document.getElementById('news-table-body');
        const logoutButton = document.getElementById('logout-button');
        const deleteModal = document.getElementById('delete-modal');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        let newsIdToDelete = null;

        onAuthStateChanged(auth, (user) => {
            if (user) {
                userEmailElement.textContent = user.email;
                loadNews();
            } else {
                const currentPath = window.location.pathname;
                const newPath = currentPath.substring(0, currentPath.lastIndexOf('/') + 1) + 'admin-login.html';
                window.location.href = newPath;
            }
        });

        async function loadNews() {
            try {
                const q = query(collection(db, "news"), orderBy("publishDate", "desc"));
                const querySnapshot = await getDocs(q);
                newsTableBody.innerHTML = ''; 

                if (querySnapshot.empty) {
                    newsTableBody.innerHTML = '<tr><td colspan="4" class="text-center p-8 text-gray-500">No hay novedades para mostrar. ¡Añade una!</td></tr>';
                    return;
                }

                querySnapshot.forEach((doc) => {
                    const news = doc.data();
                    const publishDate = news.publishDate.toDate().toLocaleDateString('es-ES', {
                        day: '2-digit', month: '2-digit', year: 'numeric'
                    });

                    const row = `
                        <tr data-id="${doc.id}">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">${news.title}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">${publishDate}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight ${news.isPublished ? 'text-green-900' : 'text-yellow-900'}">
                                    <span aria-hidden class="absolute inset-0 ${news.isPublished ? 'bg-green-200' : 'bg-yellow-200'} opacity-50 rounded-full"></span>
                                    <span class="relative">${news.isPublished ? 'Publicado' : 'Borrador'}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="admin-edit-news.html?id=${doc.id}" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                <button class="delete-btn text-red-600 hover:text-red-900">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    newsTableBody.innerHTML += row;
                });
            } catch (error) {
                console.error("Error al cargar las novedades:", error);
                newsTableBody.innerHTML = '<tr><td colspan="4" class="text-center p-8 text-red-500">No se pudieron cargar las novedades.</td></tr>';
            }
        }

        newsTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-btn')) {
                newsIdToDelete = e.target.closest('tr').dataset.id;
                deleteModal.classList.remove('hidden');
            }
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
            newsIdToDelete = null;
        });

        confirmDeleteBtn.addEventListener('click', async () => {
            if (!newsIdToDelete) return;

            try {
                await deleteDoc(doc(db, "news", newsIdToDelete));
                deleteModal.classList.add('hidden');
                loadNews(); 
            } catch (error) {
                console.error("Error al eliminar la noticia:", error);
                alert("No se pudo eliminar la noticia.");
            }
        });

        logoutButton.addEventListener('click', async () => {
            try {
                await signOut(auth);
            } catch (error) {
                console.error("Error al cerrar sesión:", error);
            }
        });
    </script>
</body>
</html>
