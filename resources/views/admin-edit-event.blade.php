<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Admin BAMARTE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Lato', sans-serif; background-color: #f1f5f9; } 
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a855f7;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 pb-20">

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
            <div class="h-16 flex items-center justify-center border-b border-gray-200">
                <h1 class="text-2xl font-black text-purple-600">BAMARTE</h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="admin-dashboard.html" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-bold uppercase text-xs">
                    ← Volver al Dashboard
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <h2 class="text-xl font-bold uppercase tracking-tight">Editar Muestra Cultural</h2>
                <div id="user-email" class="text-sm text-gray-500 font-bold italic">Cargando...</div>
            </header>

            <div id="error-banner" class="max-w-5xl mx-auto mt-4 px-4 hidden">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">¡Error!</strong>
                    <span id="error-message-text" class="block sm:inline"></span>
                </div>
            </div>

            <div class="p-4 md:p-8 max-w-5xl mx-auto">
                <form id="edit-event-form" class="space-y-8">
                    
                    <!-- 1. Información Básica -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">1. Información Básica</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Título del Evento</label>
                                <input type="text" name="title" id="form-title" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 outline-none font-bold">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Artistas</label>
                                    <div id="artists-container" class="space-y-2 mb-2"></div>
                                    <button type="button" onclick="addDynamicField('artists-container', 'artists[]', 'artist-list')" class="text-[10px] font-black text-purple-600 hover:underline uppercase tracking-tighter">+ Añadir Artista</button>
                                    <datalist id="artist-list"></datalist>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Curadores</label>
                                    <div id="curators-container" class="space-y-2 mb-2"></div>
                                    <button type="button" onclick="addDynamicField('curators-container', 'curators[]')" class="text-[10px] font-black text-purple-600 hover:underline uppercase tracking-tighter">+ Añadir Curador</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Clasificación -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">2. Clasificación</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Categoría</label>
                                <select name="category" id="form-category" required class="w-full px-4 py-2 border rounded-lg bg-white outline-none font-bold">
                                    <option value="arte">Arte</option>
                                    <option value="musica">Música</option>
                                    <option value="teatro">Teatro</option>
                                    <option value="cine">Cine</option>
                                    <option value="literatura">Literatura</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sub-Categoría</label>
                                <select name="subCategory" id="form-subCategory" class="w-full px-4 py-2 border rounded-lg bg-white outline-none font-bold">
                                    <option value="agenda">Agenda</option>
                                    <option value="feria">Feria</option>
                                    <option value="novedades">Novedades</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-8 pt-4">
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="checkbox" name="isPublished" id="form-isPublished" class="w-5 h-5 text-purple-600 rounded">
                                    <span class="text-sm font-bold text-gray-700 uppercase group-hover:text-purple-600">Publicado</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="checkbox" name="isFeatured" id="form-isFeatured" class="w-5 h-5 text-yellow-500 rounded">
                                    <span class="text-sm font-bold text-gray-700 uppercase group-hover:text-yellow-600">Destacado ★</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Fechas y Horarios (Flexibles) -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">3. Fechas y Horarios (Flexibles)</h3>
                        <p class="text-xs text-slate-500 mb-6 font-bold">Rellena solo los campos que apliquen a tu evento. No es obligatorio llenarlos todos.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Inauguración / Estreno</label>
                                <input type="datetime-local" name="inaugurationDate" id="form-inaugurationDate" class="w-full px-4 py-2 border rounded-lg outline-none font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Evento Único (1 solo día)</label>
                                <input type="datetime-local" name="singleDate" id="form-singleDate" class="w-full px-4 py-2 border rounded-lg outline-none font-bold">
                            </div>
                            
                            <div class="md:col-span-2 p-4 bg-slate-50 rounded-lg border border-slate-100">
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-4 tracking-widest italic">Rango de fechas (Muestras de larga duración)</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-[10px] font-bold text-gray-600 uppercase">Inicio</label><input type="datetime-local" name="startDate" id="form-startDate" class="w-full px-3 py-2 border rounded-lg text-sm font-bold"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-600 uppercase">Fin</label><input type="datetime-local" name="endDate" id="form-endDate" class="w-full px-3 py-2 border rounded-lg text-sm font-bold"></div>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2 p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <label class="block text-[10px] font-black text-purple-500 uppercase mb-2 tracking-widest">Días y Horarios Recurrentes</label>
                                <input type="text" name="recurringSchedule" id="form-recurringSchedule" placeholder="Ej: Miércoles y Viernes de 20 a 22 hs" class="w-full px-4 py-2 border border-purple-200 rounded-lg font-bold text-purple-900 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Información del Lugar -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">4. Información del Espacio</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Espacio / Galería</label>
                                <input type="text" id="locationName" name="locationName" list="venue-list" autocomplete="off" required class="w-full px-4 py-2 border border-purple-200 rounded-lg bg-purple-50 outline-none font-bold shadow-inner">
                                <datalist id="venue-list"></datalist>
                                <p class="text-[10px] text-purple-500 mt-1 font-bold uppercase italic">Si eliges una galería existente, se autocompletarán los datos.</p>
                            </div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Sala</label><input type="text" name="room" id="form-room" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Dirección</label><input type="text" name="venueAddress" id="form-venueAddress" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Teléfono</label><input type="text" name="venuePhone" id="form-venuePhone" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Horarios</label><input type="text" name="venueHours" id="form-venueHours" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Web</label><input type="text" name="venueWebsite" id="form-venueWebsite" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email</label><input type="email" name="venueEmail" id="form-venueEmail" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Instagram</label><input type="text" name="venueSocial" id="form-venueSocial" placeholder="@usuario" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-bold text-gray-700 mb-2">Latitud</label><input type="text" name="latitude" id="form-latitude" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                                <div><label class="block text-sm font-bold text-gray-700 mb-2">Longitud</label><input type="text" name="longitude" id="form-longitude" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Multimedia -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">5. Multimedia y Contenido</h3>
                        <div class="space-y-10">
                            <!-- Imagen Principal -->
                            <div class="p-4 bg-gray-50 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-4 uppercase underline italic">A. Imagen Principal (Portada)</label>
                                <div class="flex flex-col md:flex-row items-start md:space-x-6">
                                    <img id="imagePreview" src="https://placehold.co/400x300?text=IMG" class="w-40 h-40 object-cover rounded border shadow-sm">
                                    <div class="flex-1">
                                        <input type="file" id="imageUpload" accept="image/*" class="text-sm block w-full text-slate-500">
                                        <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase italic">Deja vacío para mantener la imagen actual.</p>
                                    </div>
                                </div>
                                <input type="hidden" id="currentMainImageUrl">
                            </div>

                            <!-- NUEVA: Imagen de Transición (Flyer) -->
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <label class="block text-sm font-bold text-purple-800 mb-4 uppercase underline italic">B. Imagen de Transición / Flyer (Entre textos)</label>
                                <div class="flex flex-col md:flex-row items-start md:space-x-6">
                                    <img id="secondaryImagePreview" src="https://placehold.co/400x300/f5f3ff/a855f7?text=Flyer" class="w-40 h-40 object-contain bg-white rounded border border-purple-200 shadow-sm">
                                    <div class="flex-1">
                                        <input type="file" id="secondaryImageUpload" accept="image/*" class="text-xs block w-full text-purple-700">
                                        <p class="text-[10px] text-purple-600 mt-4 font-black uppercase tracking-tight">Si se carga, se verá completa (Flyer) entre los bloques de texto. Si no, usará la principal.</p>
                                    </div>
                                </div>
                                <input type="hidden" id="currentSecondaryImageUrl">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                                <textarea name="description" id="form-description" rows="8" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-purple-500 font-medium"></textarea>
                            </div>

                            <!-- Artista -->
                            <div class="p-4 bg-gray-50 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2 font-black">C. Artista (Foto y Bio)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-4">
                                        <img id="artistImagePreview" src="https://placehold.co/100x100?text=Foto" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow">
                                        <input type="file" id="artistImageUpload" accept="image/*" class="text-xs">
                                    </div>
                                    <textarea name="artistBio" id="form-artistBio" rows="4" placeholder="Bio del artista..." class="w-full px-4 py-2 border rounded-lg text-sm outline-none font-medium"></textarea>
                                </div>
                                <input type="hidden" id="currentArtistImageUrl">
                            </div>

                            <!-- Galería -->
                            <div class="p-4 bg-gray-50 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2 font-black">D. Galería de Obras</label>
                                <div id="gallery-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"></div>
                                <button type="button" id="add-gallery-btn" class="px-6 py-2 bg-purple-100 text-purple-700 font-black rounded-lg text-[10px] uppercase hover:bg-purple-200 transition-colors tracking-widest">+ Añadir Imagen</button>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Extras -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-purple-600 mb-6 border-b pb-2 uppercase tracking-widest">6. Extras</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-xs font-black text-gray-400 uppercase mb-2">Precio</label><input type="text" name="priceInfo" id="form-priceInfo" placeholder="Gratis" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div><label class="block text-xs font-black text-gray-400 uppercase mb-2">URL Tickets</label><input type="text" name="ticketUrl" id="form-ticketUrl" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-black text-gray-400 uppercase mb-2">URL Catálogo (PDF)</label><input type="text" name="catalogPdfUrl" id="form-catalogPdfUrl" class="w-full px-4 py-2 border rounded-lg font-bold"></div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="admin-dashboard.html" class="px-8 py-3 border rounded-xl font-black text-gray-600 hover:bg-gray-50 transition-colors uppercase text-xs">Cancelar</a>
                        <button type="submit" id="save-btn" class="px-12 py-3 bg-purple-600 text-white font-black rounded-xl hover:bg-purple-700 shadow-xl transition-all transform hover:scale-105 uppercase text-xs">
                            GUARDAR CAMBIOS DEFINITIVOS
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Overlay de Carga -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl flex flex-col items-center max-w-xs w-full">
            <div class="loader mb-6 border-t-purple-600"></div>
            <p id="overlay-text" class="font-black text-gray-700 uppercase tracking-widest text-xs text-center">Actualizando servidor...</p>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, doc, getDoc, updateDoc, collection, getDocs, Timestamp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";
        import { getStorage, ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-storage.js";

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
        const storage = getStorage(app);

        const urlParams = new URLSearchParams(window.location.search);
        const eventId = urlParams.get('id');

        let venueDataMap = {};

        onAuthStateChanged(auth, (user) => {
            if (user) {
                document.getElementById('user-email').textContent = user.email;
                loadAutocompleteData();
                if(eventId) loadEventData(eventId);
                else showError("No se especificó un ID de evento.");
            } else {
                window.location.href = "admin-login.html";
            }
        });

        function showError(msg) {
            const banner = document.getElementById('error-banner');
            document.getElementById('error-message-text').textContent = msg;
            banner.classList.remove('hidden');
            document.getElementById('overlay').classList.add('hidden');
        }

        function toDatetimeLocal(ts) {
            if (!ts) return '';
            try {
                const d = ts.toDate();
                const pad = n => n.toString().padStart(2, '0');
                return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            } catch (e) { return ''; }
        }

        async function loadEventData(id) {
            try {
                const snap = await getDoc(doc(db, "events", id));
                if(!snap.exists()) { showError("El evento no existe."); return; }
                const data = snap.data();

                document.getElementById('form-title').value = data.title || '';
                document.getElementById('form-category').value = data.category || 'arte';
                document.getElementById('form-subCategory').value = data.subCategory || 'agenda';
                document.getElementById('form-isPublished').checked = !!data.isPublished;
                document.getElementById('form-isFeatured').checked = !!data.isFeatured;
                
                // FECHAS
                document.getElementById('form-inaugurationDate').value = toDatetimeLocal(data.inaugurationDate);
                document.getElementById('form-singleDate').value = toDatetimeLocal(data.singleDate);
                document.getElementById('form-startDate').value = toDatetimeLocal(data.startDate);
                document.getElementById('form-endDate').value = toDatetimeLocal(data.endDate);
                document.getElementById('form-recurringSchedule').value = data.recurringSchedule || '';
                
                document.getElementById('locationName').value = data.locationName || '';
                document.getElementById('form-room').value = data.room || '';
                document.getElementById('form-venueAddress').value = data.venueAddress || '';
                document.getElementById('form-venuePhone').value = data.venuePhone || '';
                document.getElementById('form-venueHours').value = data.venueHours || '';
                document.getElementById('form-venueWebsite').value = data.venueWebsite || '';
                document.getElementById('form-venueEmail').value = data.venueEmail || '';
                document.getElementById('form-venueSocial').value = data.venueSocial || '';
                document.getElementById('form-latitude').value = data.latitude || '';
                document.getElementById('form-longitude').value = data.longitude || '';

                document.getElementById('form-description').value = data.description || '';
                document.getElementById('form-artistBio').value = data.artistBio || '';
                document.getElementById('form-priceInfo').value = data.priceInfo || '';
                document.getElementById('form-ticketUrl').value = data.ticketUrl || '';
                document.getElementById('form-catalogPdfUrl').value = data.catalogPdfUrl || '';

                if(data.mainImageUrl) {
                    document.getElementById('imagePreview').src = data.mainImageUrl;
                    document.getElementById('currentMainImageUrl').value = data.mainImageUrl;
                }
                if(data.secondaryImageUrl) {
                    document.getElementById('secondaryImagePreview').src = data.secondaryImageUrl;
                    document.getElementById('currentSecondaryImageUrl').value = data.secondaryImageUrl;
                }
                if(data.artistImageUrl) {
                    document.getElementById('artistImagePreview').src = data.artistImageUrl;
                    document.getElementById('currentArtistImageUrl').value = data.artistImageUrl;
                }

                const artists = data.artists || (data.artist ? [data.artist] : []);
                artists.forEach(a => addDynamicField('artists-container', 'artists[]', 'artist-list', a));
                if(artists.length === 0) addDynamicField('artists-container', 'artists[]', 'artist-list');

                const curators = data.curators || (data.curator ? [data.curator] : []);
                curators.forEach(c => addDynamicField('curators-container', 'curators[]', null, c));
                if(curators.length === 0) addDynamicField('curators-container', 'curators[]', null);

                if(data.gallery) data.gallery.forEach(img => addGalleryItem(img.url, img.description));

            } catch (err) { showError("Fallo al conectar con Firestore: " + err.message); }
        }

        async function loadAutocompleteData() {
            try {
                const querySnapshot = await getDocs(collection(db, "events"));
                const artistsSet = new Set();
                querySnapshot.forEach(doc => {
                    const data = doc.data();
                    if(data.artists) data.artists.forEach(a => artistsSet.add(a));
                    if(data.locationName) venueDataMap[data.locationName] = data;
                });
                const aList = document.getElementById('artist-list');
                artistsSet.forEach(a => aList.innerHTML += `<option value="${a}">`);
                const vList = document.getElementById('venue-list');
                Object.keys(venueDataMap).forEach(v => vList.innerHTML += `<option value="${v}">`);
            } catch (e) { console.error(e); }
        }

        window.addDynamicField = function(containerId, fieldName, dListId = null, val = "") {
            const input = document.createElement('input');
            input.type = 'text'; input.name = fieldName; input.value = val;
            input.className = 'w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-purple-500 outline-none mb-2 font-bold';
            if(dListId) input.setAttribute('list', dListId);
            document.getElementById(containerId).appendChild(input);
        }

        function addGalleryItem(url = "", desc = "") {
            const div = document.createElement('div');
            div.className = 'gallery-row bg-white p-3 rounded-lg border shadow-sm space-y-2';
            div.innerHTML = `
                ${url ? `<img src="${url}" class="w-full h-24 object-cover rounded mb-2 border">` : ''}
                <input type="file" accept="image/*" class="gallery-file text-xs w-full">
                <input type="hidden" class="current-gallery-url" value="${url}">
                <input type="text" value="${desc}" placeholder="Epígrafe" class="gallery-desc w-full border rounded px-2 py-1 text-xs outline-none">
                <button type="button" class="remove-btn text-red-500 font-bold text-xs hover:underline uppercase">Borrar</button>
            `;
            div.querySelector('.remove-btn').onclick = () => div.remove();
            document.getElementById('gallery-container').appendChild(div);
        }
        document.getElementById('add-gallery-btn').onclick = () => addGalleryItem();

        function compressImage(file, maxSide = 1200) {
            return new Promise((resolve, reject) => {
                const timeout = setTimeout(() => reject(new Error("Tiempo excedido.")), 20000);
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e => {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = () => {
                        let w = img.width, h = img.height;
                        if(w > maxSide || h > maxSide) {
                            if(w > h) { h *= maxSide/w; w = maxSide; }
                            else { w *= maxSide/h; h = maxSide; }
                        }
                        const canvas = document.createElement('canvas');
                        canvas.width = w; canvas.height = h;
                        canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                        canvas.toBlob(blob => { clearTimeout(timeout); resolve(blob); }, 'image/webp', 0.8);
                    };
                };
            });
        }

        document.getElementById('edit-event-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const overlay = document.getElementById('overlay');
            const overlayText = document.getElementById('overlay-text');
            overlay.classList.remove('hidden');

            try {
                const formData = new FormData(e.target);
                
                // Imagen Principal
                let mainUrl = document.getElementById('currentMainImageUrl').value;
                const mainFile = document.getElementById('imageUpload').files[0];
                if(mainFile) {
                    overlayText.textContent = "Subiendo Portada...";
                    const blob = await compressImage(mainFile);
                    const snap = await uploadBytes(ref(storage, `eventos/${Date.now()}_main.webp`), blob);
                    mainUrl = await getDownloadURL(snap.ref);
                }

                // NUEVO: Imagen Secundaria (Transición)
                let secondaryUrl = document.getElementById('currentSecondaryImageUrl').value || null;
                const secFile = document.getElementById('secondaryImageUpload').files[0];
                if(secFile) {
                    overlayText.textContent = "Subiendo Flyer...";
                    const blob = await compressImage(secFile);
                    const snap = await uploadBytes(ref(storage, `eventos/${Date.now()}_sec.webp`), blob);
                    secondaryUrl = await getDownloadURL(secSnap.ref);
                }

                // Foto Artista
                let artistUrl = document.getElementById('currentArtistImageUrl').value || null;
                const artFile = document.getElementById('artistImageUpload').files[0];
                if(artFile) {
                    overlayText.textContent = "Subiendo Artista...";
                    const blob = await compressImage(artFile, 800);
                    const snap = await uploadBytes(ref(storage, `artistas/${Date.now()}_art.webp`), blob);
                    artistUrl = await getDownloadURL(snap.ref);
                }

                // Galería
                const galleryItems = [];
                const rows = document.querySelectorAll('.gallery-row');
                for(let i=0; i<rows.length; i++) {
                    const file = rows[i].querySelector('.gallery-file').files[0];
                    const currentUrl = rows[i].querySelector('.current-gallery-url').value;
                    const desc = rows[i].querySelector('.gallery-desc').value;
                    if(file) {
                        overlayText.textContent = `Galería (${i+1}/${rows.length})...`;
                        const blob = await compressImage(file);
                        const snap = await uploadBytes(ref(storage, `eventos/galeria_${Date.now()}_${i}.webp`), blob);
                        const url = await getDownloadURL(snap.ref);
                        galleryItems.push({ url, description: desc });
                    } else if(currentUrl) {
                        galleryItems.push({ url: currentUrl, description: desc });
                    }
                }

                const artistsArr = Array.from(document.getElementsByName('artists[]')).map(i => i.value).filter(v => v !== "");
                const curatorsArr = Array.from(document.getElementsByName('curators[]')).map(i => i.value).filter(v => v !== "");

                overlayText.textContent = "Guardando cambios...";
                await updateDoc(doc(db, "events", eventId), {
                    title: formData.get('title'),
                    artists: artistsArr, artist: artistsArr[0] || "",
                    curators: curatorsArr, curator: curatorsArr[0] || "",
                    category: formData.get('category'),
                    subCategory: formData.get('subCategory'),
                    isPublished: formData.get('isPublished') === 'on',
                    isFeatured: formData.get('isFeatured') === 'on',
                    
                    // FECHAS (Con validación ternaria para permitir nulos)
                    inaugurationDate: formData.get('inaugurationDate') ? Timestamp.fromDate(new Date(formData.get('inaugurationDate'))) : null,
                    singleDate: formData.get('singleDate') ? Timestamp.fromDate(new Date(formData.get('singleDate'))) : null,
                    startDate: formData.get('startDate') ? Timestamp.fromDate(new Date(formData.get('startDate'))) : null,
                    endDate: formData.get('endDate') ? Timestamp.fromDate(new Date(formData.get('endDate'))) : null,
                    recurringSchedule: formData.get('recurringSchedule'),
                    
                    locationName: formData.get('locationName'),
                    room: formData.get('room'),
                    venueAddress: formData.get('venueAddress'),
                    venuePhone: formData.get('venuePhone'),
                    venueHours: formData.get('venueHours'),
                    venueWebsite: formData.get('venueWebsite'),
                    venueEmail: formData.get('venueEmail'),
                    venueSocial: formData.get('venueSocial'),
                    latitude: formData.get('latitude'),
                    longitude: formData.get('longitude'),
                    description: formData.get('description'),
                    mainImageUrl: mainUrl,
                    secondaryImageUrl: secondaryUrl, // CAMPO GUARDADO
                    artistImageUrl: artistUrl,
                    artistBio: formData.get('artistBio'),
                    gallery: galleryItems,
                    priceInfo: formData.get('priceInfo'),
                    ticketUrl: formData.get('ticketUrl'),
                    catalogPdfUrl: formData.get('catalogPdfUrl')
                });

                overlayText.textContent = "¡LISTO!";
                setTimeout(() => window.location.href = "admin-dashboard.html", 1000);

            } catch (err) { 
                showError("Error al guardar: " + err.message); 
            }
        });

        // Previews visuales al elegir archivo
        function setupPreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => document.getElementById(previewId).src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }
        setupPreview('imageUpload', 'imagePreview');
        setupPreview('secondaryImageUpload', 'secondaryImagePreview'); 
        setupPreview('artistImageUpload', 'artistImagePreview');
    </script>
</body>
</html>