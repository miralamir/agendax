<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BAMARTEController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ArtistasController;
use App\Http\Controllers\EspaciosController;
use App\Http\Controllers\CiclosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArteController;
use App\Http\Controllers\MusicaController;
use App\Http\Controllers\CineController;
use App\Http\Controllers\TeatroController;
use App\Http\Controllers\LiteraturaController;

Route::get("/", function () {
    return view("welcome");
});

// Public main sections
Route::get("/arte", [ArteController::class, "index"])->name("arte");
Route::get("/musica", [MusicaController::class, "index"])->name("musica");
Route::get("/cine", [CineController::class, "index"])->name("cine");
Route::get("/teatro", [TeatroController::class, "index"])->name("teatro");
Route::get("/literatura", [LiteraturaController::class, "index"])->name("literatura");

Route::get("/dashboard", function () {
    return view("dashboard");
})->middleware(["auth", "verified"])->name("dashboard");

Route::middleware("auth")->group(function () {
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
