<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class LiteraturaController extends Controller
{
    public function index()
    {
        $sub = request('sub');

        $featuredEvents = \App\Models\Evento::where('category', 'Literatura')
            ->where('isPublished', 1)
            ->where('isFeatured', 1)
            ->orderBy('startDate', 'desc')
            ->take(4)
            ->get();

        $latestEvents = \App\Models\Evento::where('category', 'Literatura')
            ->when($sub, fn($q) => $q->where('subCategory', $sub))
            ->where('isPublished', 1)
            ->orderBy('startDate', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('literatura.index', compact('featuredEvents', 'latestEvents'));
    }
    public function agenda() { return view("musica.agenda"); }
    public function lanzamientos() { return view("musica.lanzamientos"); }
    public function festivales() { return view("musica.festivales"); }
    public function novedades() { return view("musica.novedades"); }
}
