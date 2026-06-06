<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class ArteController extends Controller
{
    public function index() 
    {
        $events = Evento::where('category', 'Artes Visuales')
                        ->where('isPublished', 1)
                        ->orderBy('startDate', 'desc')
                        ->get();

        $featuredEvents = $events->where('isFeatured', true);
        $latestEvents = $events->where('isFeatured', false);

        return view("arte.index", compact('featuredEvents', 'latestEvents'));
    }
    public function agenda() { return view("arte.agenda"); }
    public function creadores() { return view("arte.creadores"); }
    public function ferias() { return view("arte.ferias"); }
    public function novedades() { return view("arte.novedades"); }
}
