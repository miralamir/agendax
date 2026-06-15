<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Novedad;
use App\Models\Favorito;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    // Guardar o quitar de favoritos (toggle)
    public function toggle(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:evento,novedad',
            'id' => 'required|integer',
        ]);

        $clase = $request->tipo === 'evento' ? Evento::class : Novedad::class;
        $modelo = $clase::findOrFail($request->id);
        $user = $request->user();

        $fav = Favorito::where('user_id', $user->id)
            ->where('favoritable_id', $modelo->id)
            ->where('favoritable_type', $clase)
            ->first();

        if ($fav) {
            $fav->delete();
            $guardado = false;
        } else {
            Favorito::create([
                'user_id' => $user->id,
                'favoritable_id' => $modelo->id,
                'favoritable_type' => $clase,
            ]);
            $guardado = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['guardado' => $guardado]);
        }
        return back();
    }

    // Mi Agenda: listado de favoritos del usuario
    public function index(Request $request)
    {
        $favoritos = $request->user()->favoritos()
            ->with('favoritable')
            ->latest()
            ->get()
            ->filter(fn($f) => $f->favoritable !== null); // por si se borro el evento

        return view('mi-agenda', compact('favoritos'));
    }
}
