<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class TeatroController extends Controller
{
    public function index()
    {
        $events = Evento::where('category', 'Teatro')
                        ->where('isPublished', 1)
                        ->orderBy('startDate', 'desc')
                        ->get();

        $featuredEvents = $events->where('isFeatured', true);
        $latestEvents = $events->where('isFeatured', false);

        return view("teatro.index", compact('featuredEvents', 'latestEvents'));
    }
    public function agenda() { return view("musica.agenda"); }
    public function lanzamientos() { return view("musica.lanzamientos"); }
    public function festivales() { return view("musica.festivales"); }
    public function novedades() { return view("musica.novedades"); }
}
