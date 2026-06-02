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

    private function validateAndProcess(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'inaugurationDate' => 'nullable|date',
            'singleDate' => 'nullable|date',
            
            'artist' => 'nullable|string|max:255',
            'artistBio' => 'nullable|string',
            'curator' => 'nullable|string|max:255',
            
            'locationName' => 'nullable|string|max:255',
            'venueAddress' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'venueHours' => 'nullable|string|max:255',
            'priceInfo' => 'nullable|string|max:255',
            'venuePhone' => 'nullable|string|max:255',
            'venueEmail' => 'nullable|string|max:255',
            'venueWebsite' => 'nullable|string|max:255',
            'venueSocial' => 'nullable|string|max:255',
            
            'mainImageUrl' => 'nullable|string',
            'secondaryImageUrl' => 'nullable|string',
            'artistImageUrl' => 'nullable|string',
            'catalogPdfUrl' => 'nullable|string',
            'ticketUrl' => 'nullable|string',
            'gallery' => 'nullable|string',
        ]);

        $validated['isPublished'] = $request->has('isPublished');
        $validated['isFeatured'] = $request->has('isFeatured');

        if (!empty($validated['gallery'])) {
            $urls = array_filter(array_map('trim', explode("\n", $validated['gallery'])));
            $validated['gallery'] = array_values($urls);
        } else {
            $validated['gallery'] = [];
        }

        return $validated;
    }

    public function store(Request $request)
    {
        $data = $this->validateAndProcess($request);
        Evento::create($data);
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento creado exitosamente.');
    }

    public function edit(Evento $evento)
    {
        return view('dashboard.eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $this->validateAndProcess($request);
        $evento->update($data);
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
