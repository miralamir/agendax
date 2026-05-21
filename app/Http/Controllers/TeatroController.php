<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeatroController extends Controller
    public function cartelera() { return view("teatro.cartelera"); }
    public function festivales() { return view("teatro.festivales"); }
    public function novedades() { return view("teatro.novedades"); }

    public function cartelera() { return view("teatro.cartelera"); }
    public function festivales() { return view("teatro.festivales"); }
    public function novedades() { return view("teatro.novedades"); }

{
    public function index()
    {
        return view('teatro.index');
    }
}
