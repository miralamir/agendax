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

        $featEventos = \App\Models\Evento::where('category', $categoryName)
            ->where('isPublished', 1)->where('isFeatured', 1)->get();
        $featNovedades = \App\Models\Novedad::where('category', $categoryName)
            ->where('isPublished', 1)->where('isFeatured', 1)->get();
        $featuredEvents = $featEventos->merge($featNovedades)
            ->sortByDesc(fn($i) => $i->created_at ?? $i->published_at)
            ->take(12)->values();

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

        $allItems = $latestEvents->merge($latestNovedades)
            ->sortByDesc(fn($item) => $item->created_at ?? $item->published_at)
            ->values(); // Importante para reindexar la colección

        $page = request()->get('page', 1);
        $perPage = 12;
        $latestItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $allItems->forPage($page, $perPage),
            $allItems->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('arte.index', compact('featuredEvents', 'latestItems'));
    }
    public function agenda() { return view("arte.agenda"); }
    public function creadores() { return view("arte.creadores"); }
    public function ferias() { return view("arte.ferias"); }
    public function novedades() { return view("arte.novedades"); }
}
