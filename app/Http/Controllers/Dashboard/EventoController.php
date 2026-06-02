<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%")
                  ->orWhere('locationName', 'like', "%{$search}%");
        }

        $eventos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('dashboard.eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('dashboard.eventos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'locationName' => 'nullable|string|max:255',
            'venueAddress' => 'nullable|string|max:255',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'singleDate' => 'nullable|date',
            'artist' => 'nullable|string|max:255',
            'isPublished' => 'boolean',
            'isFeatured' => 'boolean',
        ]);

        $validated['isPublished'] = $request->has('isPublished');
        $validated['isFeatured'] = $request->has('isFeatured');

        Evento::create($validated);

        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento creado exitosamente.');
    }

    public function edit(Evento $evento)
    {
        return view('dashboard.eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'locationName' => 'nullable|string|max:255',
            'venueAddress' => 'nullable|string|max:255',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'singleDate' => 'nullable|date',
            'artist' => 'nullable|string|max:255',
            'isPublished' => 'boolean',
            'isFeatured' => 'boolean',
        ]);

        $validated['isPublished'] = $request->has('isPublished');
        $validated['isFeatured'] = $request->has('isFeatured');

        $evento->update($validated);

        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
