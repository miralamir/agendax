<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    /**
     * Display the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return view('evento.show', compact('evento'));
    }
}
