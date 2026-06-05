<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function show(Evento $event)
    {
        return view('eventos.show', compact('event'));
    }

    public function categoryShow($category)
    {
        $events = Evento::where('category', $category)
                        ->where('isPublished', 1)
                        ->orderBy('startDate', 'desc')
                        ->get();

        return view('agenda.category', compact('events', 'category'));
    }
}
