<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MusicaController extends Controller
{
    public function index() { return view("musica.index"); }
    public function agenda() { return view("musica.agenda"); }
    public function lanzamientos() { return view("musica.lanzamientos"); }
    public function festivales() { return view("musica.festivales"); }
    public function novedades() { return view("musica.novedades"); }
}
