<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function show(Evento $event)
    {
        $event->increment('vistas');
        return view('evento.show', ['evento' => $event]);
    }

    public function categoryShow($category)
    {
        $events = Evento::whereRaw('LOWER(category) = ?', [strtolower($category)])
                        ->where('isPublished', 1)
                        ->orderBy('startDate', 'desc')
                        ->get();

        return view('agenda.category', compact('events', 'category'));
    }
}
