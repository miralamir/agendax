<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiteraturaController extends Controller
    public function agenda() { return view("literatura.agenda"); }
    public function novedadesEditoriales() { return view("literatura.novedades-editoriales"); }
    public function ferias() { return view("literatura.ferias"); }
    public function novedades() { return view("literatura.novedades"); }

    public function agenda() { return view("literatura.agenda"); }
    public function novedadesEditoriales() { return view("literatura.novedades-editoriales"); }
    public function ferias() { return view("literatura.ferias"); }
    public function novedades() { return view("literatura.novedades"); }

{
    public function index()
    {
        return view('literatura.index');
    }
}
