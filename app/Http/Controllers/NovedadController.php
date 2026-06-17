<?php

namespace App\Http\Controllers;

use App\Models\Novedad;

class NovedadController extends Controller
{
    public function show($slug)
    {
        $novedad = Novedad::where('slug', $slug)->where('isPublished', 1)->firstOrFail();
        $novedad->increment('vistas');
        return view('novedades.show', compact('novedad'));
    }
}
