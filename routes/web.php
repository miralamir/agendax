<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get("/arte/agenda", function () { return view("arte-agenda"); });
Route::get("/arte/ferias", function () { return view("arte-ferias"); });
Route::get("/arte/novedades", function () { return view("arte-novedades"); });
Route::get("/arte/blade", function () { return view("arte-blade"); });
Route::get("/cine/agenda", function () { return view("cine-agenda"); });
Route::get("/cine/festivales", function () { return view("cine-festivales"); });
Route::get("/cine/novedades", function () { return view("cine-novedades"); });
Route::get("/cine/blade", function () { return view("cine-blade"); });
Route::get("/literatura/agenda", function () { return view("literatura-agenda"); });
Route::get("/literatura/ferias", function () { return view("literatura-ferias"); });
Route::get("/literatura/novedades", function () { return view("literatura-novedades"); });
Route::get("/literatura/blade", function () { return view("literatura-blade"); });
Route::get("/musica/agenda", function () { return view("musica-agenda"); });
Route::get("/musica/festivales", function () { return view("musica-festivales"); });
Route::get("/musica/novedades", function () { return view("musica-novedades"); });
Route::get("/musica/blade", function () { return view("musica-blade"); });
Route::get("/teatro/agenda", function () { return view("teatro-agenda"); });
Route::get("/teatro/festivales", function () { return view("teatro-festivales"); });
Route::get("/teatro/novedades", function () { return view("teatro-novedades"); });
Route::get("/teatro/blade", function () { return view("teatro-blade"); });
