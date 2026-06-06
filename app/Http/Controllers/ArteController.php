<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class ArteController extends Controller
{
    public function index()
    {
        $sub = request('sub');

        $featuredEvents = \App\Models\Evento::where('category', 'Artes Visuales')
            ->where('isPublished', 1)
            ->where('isFeatured', 1)
            ->orderBy('startDate', 'desc')
            ->take(4)
            ->get();

        $latestEvents = \App\Models\Evento::where('category', 'Artes Visuales')
            ->when($sub, fn($q) => $q->where('subCategory', $sub))
            ->where('isPublished', 1)
            ->orderBy('startDate', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('arte.index', compact('featuredEvents', 'latestEvents'));
    }
    public function agenda() { return view("arte.agenda"); }
    public function creadores() { return view("arte.creadores"); }
    public function ferias() { return view("arte.ferias"); }
    public function novedades() { return view("arte.novedades"); }
}
