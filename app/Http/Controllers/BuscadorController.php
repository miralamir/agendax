<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Novedad;
use Illuminate\Http\Request;

class BuscadorController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $resultados = collect();

        if ($q !== '') {
            $eventos = Evento::where('isPublished', 1)
                ->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('locationName', 'like', "%{$q}%")
                      ->orWhere('artist', 'like', "%{$q}%");
                })
                ->get();

            $novedades = Novedad::where('isPublished', 1)
                ->where('title', 'like', "%{$q}%")
                ->get();

            $resultados = $eventos->merge($novedades)
                ->sortByDesc(fn($item) => $item->created_at ?? $item->published_at)
                ->values();
        }

        return view('buscar.index', compact('resultados', 'q'));
    }
}
