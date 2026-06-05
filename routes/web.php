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
use App\Http\Controllers\EventoController as FrontendEventoController; // <-- Alias añadido

Route::get("/", function () {
    // Aquí pasamos los eventos destacados y todos los eventos para el mapa en la Home
    $featuredEvents = \App\Models\Evento::where('isPublished', 1)->where('isFeatured', 1)->orderBy('startDate', 'desc')->take(5)->get();
    $allEvents = \App\Models\Evento::where('isPublished', 1)->get();
    return view("welcome", compact('featuredEvents', 'allEvents'));
})->name("home"); // Se añade un nombre a la ruta principal

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
// Route::get("/arte", [ArteController::class, "index"])->name("arte"); // Eliminada
// Route::get("/musica", [MusicaController::class, "index"])->name("musica"); // Eliminada
// Route::get("/cine", [CineController::class, "index"])->name("cine"); // Eliminada
// Route::get("/teatro", [TeatroController::class, "index"])->name("teatro"); // Eliminada
// Route::get("/literatura", [LiteraturaController::class, "index"])->name("literatura"); // Eliminada

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
})->middleware(["auth", "verified"])->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::resource('/dashboard/eventos', EventoController::class)->names('dashboard.eventos');
    Route::get('/bamarte', [BAMARTEController::class, 'index'])->name('bamarte');
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/artistas', [ArtistasController::class, 'index'])->name('artistas');
    Route::get('/espacios', [EspaciosController::class, 'index'])->name('espacios');
    Route::get('/ciclos', [CiclosController::class, 'index'])->name('ciclos');
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});

require __DIR__."/auth.php";
