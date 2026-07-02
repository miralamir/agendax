<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BAMARTEController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ArtistasController;
use App\Http\Controllers\EspaciosController;
use App\Http\Controllers\CiclosController;
use App\Http\Controllers\Dashboard\EventoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArteController;
use App\Http\Controllers\MusicaController;
use App\Http\Controllers\CineController;
use App\Http\Controllers\TeatroController;
use App\Http\Controllers\LiteraturaController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\EventoController as FrontendEventoController; // <-- Alias añadido
use App\Http\Controllers\NovedadController as FrontendNovedadController; // <-- Nuevo alias para controlador público
use App\Http\Controllers\Dashboard\NovedadController;
use App\Http\Controllers\Dashboard\CreadorController as DashCreadorController; // <-- Importar controlador del dashboard

Route::get("/", function () {
    // Aquí pasamos los eventos destacados y todos los eventos para el mapa en la Home
    $featEventos = \App\Models\Evento::where('isPublished', 1)->where('isFeatured', 1)->orderBy('updated_at', 'desc')->get();
    $featNovedades = \App\Models\Novedad::where('isPublished', 1)->where('isFeatured', 1)->orderBy('updated_at', 'desc')->get();
    $featuredEvents = $featEventos->merge($featNovedades)->sortByDesc('updated_at')->take(8)->values();
    $allEvents = \App\Models\Evento::where('isPublished', 1)->with('funciones')->get()->map(function($e) {
        $arr = $e->toArray();
        $arr['fechasFuncion'] = $e->funciones->pluck('fecha')->map(fn($f) => $f->format('Y-m-d'))->values();
        return $arr;
    });
    
    // Últimos items por categoría para los tabs
    $categories = [
        'visuales' => 'Artes Visuales',
        'musica'   => 'Música',
        'teatro'   => 'Teatro',
        'cine'     => 'Cine',
        'literatura' => 'Literatura',
    ];
    $latestByCategory = [];
    foreach ($categories as $key => $cat) {
        $eventos = \App\Models\Evento::where('category', $cat)->where('isPublished', 1)->orderBy('created_at', 'desc')->take(9)->get();
        $novedades = \App\Models\Novedad::where('category', $cat)->where('isPublished', 1)->orderByRaw('COALESCE(published_at, created_at) DESC')->take(9)->get();
        $latestByCategory[$key] = $eventos->merge($novedades)->sortByDesc('created_at')->take(9)->values();
    }
    
    return view("welcome", compact('featuredEvents', 'allEvents', 'latestByCategory'));
})->name("home"); // Se añade un nombre a la ruta principal

Route::get('/buscar', [BuscadorController::class, 'index'])->name('buscar');

// Ruta para el detalle del evento (con implicit model binding)
Route::get('/evento/{event}', [FrontendEventoController::class, 'show'])->name('evento.show');

// Rutas genéricas para las categorías de agenda
Route::get('/agenda/{category}', [FrontendEventoController::class, 'categoryShow'])->name('agenda.category');


// Las rutas específicas de categorías (ej: /arte, /musica) ahora son manejadas por /agenda/{category}
// Se eliminan para evitar conflictos y centralizar la lógica. También se eliminan sus sub-rutas asociadas.
// Esto limpia las rutas antiguas que ya no son necesarias.
Route::prefix("arte")->name("arte.")->group(function () {
    Route::get("creadores", [ArteController::class, "creadores"])->name("creadores");
    Route::get("ferias", [ArteController::class, "ferias"])->name("ferias");
    Route::get("novedades", [ArteController::class, "novedades"])->name("novedades");
});
Route::get("/arte", [ArteController::class, "index"])->name("arte");
Route::get("/musica", [MusicaController::class, "index"])->name("musica");
Route::get("/cine", [CineController::class, "index"])->name("cine");
Route::get("/teatro", [TeatroController::class, "index"])->name("teatro");
Route::get("/literatura", [LiteraturaController::class, "index"])->name("literatura");

// Se mantienen las sub-rutas de otras categorías que no sean 'agenda' si son necesarias.
// Para Música
Route::prefix("musica")->name("musica.")->group(function () {
    Route::get("lanzamientos", [MusicaController::class, "lanzamientos"])->name("lanzamientos");
    Route::get("festivales", [MusicaController::class, "festivales"])->name("festivales");
    Route::get("novedades", [MusicaController::class, "novedades"])->name("novedades");
});
// Para Teatro
Route::prefix("teatro")->name("teatro.")->group(function () {
    Route::get("cartelera", [TeatroController::class, "cartelera"])->name("cartelera");
    Route::get("festivales", [TeatroController::class, "festivales"])->name("festivales");
    Route::get("novedades", [TeatroController::class, "novedades"])->name("novedades");
});
// Para Cine
Route::prefix("cine")->name("cine.")->group(function () {
    Route::get("estrenos", [CineController::class, "estrenos"])->name("estrenos");
    Route::get("festivales-ciclos", [CineController::class, "festivalesCiclos"])->name("festivales-ciclos");
    Route::get("novedades", [CineController::class, "novedades"])->name("novedades");
});
// Para Literatura
Route::prefix("literatura")->name("literatura.")->group(function () {
    Route::get("novedades-editoriales", [LiteraturaController::class, "novedadesEditoriales"])->name("novedades-editoriales");
    Route::get("ferias", [LiteraturaController::class, "ferias"])->name("ferias");
    Route::get("novedades", [LiteraturaController::class, "novedades"])->name("novedades");
});

Route::get("/dashboard", function () {
    return redirect()->route('dashboard.eventos.index');
})->middleware(["auth", "verified", "admin"])->name("dashboard");

Route::middleware(["auth", "admin"])->group(function () {
    Route::post('/dashboard/eventos/bulk', [EventoController::class, 'bulk'])->name('dashboard.eventos.bulk');
    Route::patch('/dashboard/eventos/{evento}/toggle/{campo}', [EventoController::class, 'toggle'])->name('dashboard.eventos.toggle');
    Route::resource('/dashboard/eventos', EventoController::class)->names('dashboard.eventos');
    Route::post('/dashboard/novedades/bulk', [NovedadController::class, 'bulk'])->name('dashboard.novedades.bulk');
    Route::patch('/dashboard/novedades/{novedad}/toggle/{campo}', [NovedadController::class, 'toggle'])->name('dashboard.novedades.toggle');
    Route::post('/dashboard/creadores/bulk', [DashCreadorController::class, 'bulk'])->name('dashboard.creadores.bulk');
    Route::resource('dashboard/creadores', DashCreadorController::class)->parameters(['creadores' => 'creador'])->names('dashboard.creadores');
    Route::patch('/dashboard/banners/{banner}/toggle', [\App\Http\Controllers\Dashboard\BannerController::class, 'toggle'])->name('dashboard.banners.toggle');
    Route::resource('dashboard/banners', \App\Http\Controllers\Dashboard\BannerController::class)->names('dashboard.banners');
    Route::get('/dashboard/usuarios', [\App\Http\Controllers\Dashboard\UsuarioController::class, 'index'])->name('dashboard.usuarios.index');
    Route::patch('/dashboard/usuarios/{usuario}/baneo', [\App\Http\Controllers\Dashboard\UsuarioController::class, 'toggleBaneo'])->name('dashboard.usuarios.baneo');
    Route::patch('/dashboard/usuarios/{usuario}/rol', [\App\Http\Controllers\Dashboard\UsuarioController::class, 'cambiarRol'])->name('dashboard.usuarios.rol');
    Route::delete('/dashboard/usuarios/{usuario}', [\App\Http\Controllers\Dashboard\UsuarioController::class, 'destroy'])->name('dashboard.usuarios.destroy');
    Route::delete('/dashboard/comentarios/{comentario}', [\App\Http\Controllers\Dashboard\UsuarioController::class, 'borrarComentario'])->name('dashboard.comentarios.destroy');
    Route::resource('dashboard/novedades', NovedadController::class, ['parameters' => ['novedades' => 'novedad']])->names('dashboard.novedades'); // <-- Rutas de novedades para el dashboard
    Route::get('/bamarte', [BAMARTEController::class, 'index'])->name('bamarte');
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/artistas', [ArtistasController::class, 'index'])->name('artistas');
    Route::get('/espacios', [EspaciosController::class, 'index'])->name('espacios');
    Route::get('/ciclos', [CiclosController::class, 'index'])->name('ciclos');
});

// Perfil: cualquier usuario logueado (no requiere admin)
Route::middleware("auth")->group(function () {
    Route::get("/mi-agenda", [\App\Http\Controllers\FavoritoController::class, "index"])->name("mi-agenda");
    Route::post("/favorito/toggle", [\App\Http\Controllers\FavoritoController::class, "toggle"])->name("favorito.toggle");
    Route::post("/comentario", [\App\Http\Controllers\ComentarioController::class, "store"])->name("comentario.store");
    Route::delete("/comentario/{comentario}", [\App\Http\Controllers\ComentarioController::class, "destroy"])->name("comentario.destroy");
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});

// Click en banner publicitario (cuenta y redirige)
Route::get('/banner/{banner}/click', function (\App\Models\Banner $banner) {
    $banner->increment('clics');
    return redirect()->away($banner->link ?: '/');
})->name('banner.click');

// Ruta pública para una novedad individual
Route::get('/novedades/{slug}', [FrontendNovedadController::class, 'show'])->name('novedades.show');

require __DIR__."/auth.php";

// API autocomplete lugares
Route::get('/dashboard/api/lugares', function() {
    $q = request('q');
    $lugares = \App\Models\Evento::where('locationName', 'like', "%{$q}%")
        ->whereNotNull('locationName')
        ->select('locationName', 'venueAddress', 'lat', 'lng')
        ->distinct()
        ->limit(10)
        ->get();
    return response()->json($lugares);
})->middleware('auth');

// Perfiles públicos
Route::get('/creador/{slug}', [App\Http\Controllers\CreadorController::class, 'show'])->name('creador.show');
Route::get('/lugar/{slug}', [App\Http\Controllers\LugarController::class, 'show'])->name('lugar.show');
