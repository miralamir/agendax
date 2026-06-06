<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class LiteraturaController extends Controller
{
    public function index()
    {
        $events = Evento::where('category', 'Literatura')
                        ->where('isPublished', 1)
                        ->orderBy('startDate', 'desc')
                        ->get();

        $featuredEvents = $events->where('isFeatured', true);
        $latestEvents = $events->where('isFeatured', false);

        return view("literatura.index", compact('featuredEvents', 'latestEvents'));
    }
    public function agenda() { return view("musica.agenda"); }
    public function lanzamientos() { return view("musica.lanzamientos"); }
    public function festivales() { return view("musica.festivales"); }
    public function novedades() { return view("musica.novedades"); }
}
