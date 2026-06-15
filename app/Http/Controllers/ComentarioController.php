<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Novedad;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:evento,novedad',
            'id' => 'required|integer',
            'body' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        if (!$user->puedeComentar()) {
            return back()->with('error', 'No podés comentar en este momento.');
        }

        $clase = $request->tipo === 'evento' ? Evento::class : Novedad::class;
        $modelo = $clase::findOrFail($request->id);

        Comentario::create([
            'user_id' => $user->id,
            'comentable_id' => $modelo->id,
            'comentable_type' => $clase,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comentario publicado.')->withFragment('comentarios');
    }

    public function destroy(Comentario $comentario, Request $request)
    {
        // El autor puede borrar el suyo; el admin puede borrar cualquiera
        $user = $request->user();
        if ($comentario->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
        $comentario->delete();
        return back()->with('success', 'Comentario eliminado.');
    }
}
