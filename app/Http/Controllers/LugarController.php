<?php
namespace App\Http\Controllers;

use App\Models\Lugar;
use App\Models\Evento;

class LugarController extends Controller
{
    public function show($slug)
    {
        $lugar = Lugar::where('slug', $slug)->firstOrFail();
        $eventos = Evento::where('locationName', $lugar->nombre)
            ->where('isPublished', 1)
            ->orderBy('startDate', 'desc')
            ->get();
        return view('lugar.show', compact('lugar', 'eventos'));
    }
}
