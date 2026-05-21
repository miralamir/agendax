<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CineController extends Controller
{
    public function index() { return view("cine.index"); }
    public function estrenos() { return view("cine.estrenos"); }
    public function festivalesCiclos() { return view("cine.festivales-ciclos"); }
    public function novedades() { return view("cine.novedades"); }
}
