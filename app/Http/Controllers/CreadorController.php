<?php
namespace App\Http\Controllers;

use App\Models\Creador;
use App\Models\Evento;

class CreadorController extends Controller
{
    public function show($slug)
    {
        $creador = Creador::where('slug', $slug)->firstOrFail();
        $eventos = Evento::where('isPublished', 1)
            ->where(function ($q) use ($creador) {
                $q->whereRaw('JSON_SEARCH(bios, ?, ?) IS NOT NULL', ['one', $creador->nombre])
                  ->orWhere('artist', 'like', '%'.$creador->nombre.'%')
                  ->orWhere('curator', 'like', '%'.$creador->nombre.'%');
            })
            ->orderBy('startDate', 'desc')
            ->get();
        return view('creador.show', compact('creador', 'eventos'));
    }
}
