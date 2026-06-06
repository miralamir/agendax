<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Novedad; // Importar modelo Novedad

class ArteController extends Controller
{
    public function index()
    {
        $sub = request('sub');
        $categoryName = 'Artes Visuales'; // Variable para la categoría actual

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
            ->sortByDesc(fn ($item) => $item->created_at ?? $item->published_at) // Ordenar por created_at o published_at
            ->paginate(12)
            ->withQueryString();

        return view('arte.index', compact('featuredEvents', 'latestItems'));
    }
    public function agenda() { return view("arte.agenda"); }
    public function creadores() { return view("arte.creadores"); }
    public function ferias() { return view("arte.ferias"); }
    public function novedades() { return view("arte.novedades"); }
}
