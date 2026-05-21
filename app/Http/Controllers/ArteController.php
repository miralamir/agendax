<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArteController extends Controller
{
    public function index() { return view("arte.index"); }
    public function agenda() { return view("arte.agenda"); }
    public function creadores() { return view("arte.creadores"); }
    public function ferias() { return view("arte.ferias"); }
    public function novedades() { return view("arte.novedades"); }
}
