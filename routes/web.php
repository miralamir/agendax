<?php
use Illuminate\Support\Facades\Route;

Route::get("/", function () { return view("welcome"); });
Route::get("/dashboard", function () { return view("dashboard"); });

// Parent Routes
Route::get("/arte", function () { return view("arte"); });
Route::get("/cine", function () { return view("cine"); });
Route::get("/literatura", function () { return view("literatura"); });
Route::get("/musica", function () { return view("musica"); });
Route::get("/teatro", function () { return view("teatro"); });

// Sub-Routes
Route::get("/arte/agenda", function () { return view("arte-agenda"); });
Route::get("/arte/ferias", function () { return view("arte-ferias"); });
Route::get("/arte/novedades", function () { return view("arte-novedades"); });
Route::get("/cine/agenda", function () { return view("cine-agenda"); });
Route::get("/cine/festivales", function () { return view("cine-festivales"); });
Route::get("/cine/novedades", function () { return view("cine-novedades"); });
Route::get("/literatura/agenda", function () { return view("literatura-agenda"); });
Route::get("/literatura/ferias", function () { return view("literatura-ferias"); });
Route::get("/literatura/novedades", function () { return view("literatura-novedades"); });
Route::get("/musica/agenda", function () { return view("musica-agenda"); });
Route::get("/musica/festivales", function () { return view("musica-festivales"); });
Route::get("/musica/novedades", function () { return view("musica-novedades"); });
Route::get("/teatro/agenda", function () { return view("teatro-agenda"); });
Route::get("/teatro/festivales", function () { return view("teatro-festivales"); });
Route::get("/teatro/novedades", function () { return view("teatro-novedades"); });

Route::get("/admin/dashboard", function () { return view("admin-dashboard"); });
Route::get("/mapa", function () { return view("mapa"); });
