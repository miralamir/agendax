<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Novedad; // Importar modelo Novedad

class MusicaController extends Controller
{
    public function index()
    {
        $sub = request('sub');
        $categoryName = 'Música'; // Variable para la categoría actual

        $featuredEvents = \App\Models\Evento::where('category', $categoryName)
            ->where('isPublished', 1)
            ->where('isFeatured', 1)
            ->orderBy('startDate', 'desc')
            ->take(4)
            ->get();

        $latestEvents = \App\Models\Evento::where('category', $categoryName)
            ->when($sub, fn($q) => $q->where('subCategory', $sub))
            ->where('isPublished', 1)
            ->orderBy('startDate', 'desc')
            ->get();

        $latestNovedades = \App\Models\Novedad::where('category', $categoryName)
            ->when($sub, fn($q) => $q->where('subCategory', $sub))
            ->where('isPublished', 1)
            ->orderBy('published_at', 'desc')
            ->get();

        // Mergear eventos y novedades, ordenar por fecha y paginar
        $latestItems = $latestEvents->merge($latestNovedades)
            ->sortByDesc(fn ($item) => $item->created_at ?? $item->published_at)
            ->paginate(12)
            ->withQueryString();

        return view('musica.index', compact('featuredEvents', 'latestItems'));
    }
    public function agenda() { return view("musica.agenda"); }
    public function lanzamientos() { return view("musica.lanzamientos"); }
    public function festivales() { return view("musica.festivales"); }
    public function novedades() { return view("musica.novedades"); }
}
