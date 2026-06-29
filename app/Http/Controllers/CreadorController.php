<?php
namespace App\Http\Controllers;

use App\Models\Creador;
use App\Models\Evento;
use App\Models\Novedad;
use Illuminate\Support\Facades\Storage;

class CreadorController extends Controller
{
    public function show($slug)
    {
        $creador = Creador::where('slug', $slug)->firstOrFail();

        // Eventos donde participa (por JSON bios + fallback artist/curator)
        $eventos = Evento::where('isPublished', 1)
            ->where(function ($q) use ($creador) {
                $q->whereRaw('JSON_SEARCH(bios, ?, ?) IS NOT NULL', ['one', $creador->nombre])
                  ->orWhere('artist', 'like', '%'.$creador->nombre.'%')
                  ->orWhere('curator', 'like', '%'.$creador->nombre.'%');
            })
            ->orderBy('startDate', 'desc')
            ->get();

        // Novedades donde participa (por JSON bios)
        $novedades = Novedad::where('isPublished', 1)
            ->whereRaw('JSON_SEARCH(bios, ?, ?) IS NOT NULL', ['one', $creador->nombre])
            ->get();

        // Lista unificada normalizada, ordenada por fecha desc
        $participaciones = collect();

        foreach ($eventos as $evento) {
            $participaciones->push([
                'tipo'      => 'evento',
                'titulo'    => $evento->title,
                'categoria' => $evento->category,
                'url'       => route('evento.show', $evento->id),
                'imagen'    => $evento->mainImage ? Storage::url($evento->mainImage) : ($evento->mainImageUrl ?: null),
                'lugar'     => $evento->locationName,
                'fecha'     => $evento->startDate,
                'fechaFmt'  => $evento->startDate ? $evento->startDate->locale('es')->isoFormat('D [de] MMMM, YYYY') : null,
                'rol'       => collect($evento->bios ?? [])->firstWhere('nombre', $creador->nombre)['rol'] ?? null,
            ]);
        }

        foreach ($novedades as $novedad) {
            $fecha = $novedad->published_at ?: $novedad->created_at;
            $participaciones->push([
                'tipo'      => 'novedad',
                'titulo'    => $novedad->title,
                'categoria' => $novedad->category,
                'url'       => route('novedades.show', $novedad->slug),
                'imagen'    => $novedad->image ? Storage::url($novedad->image) : null,
                'lugar'     => null,
                'fecha'     => $fecha,
                'fechaFmt'  => $fecha ? $fecha->locale('es')->isoFormat('D [de] MMMM, YYYY') : null,
                'rol'       => collect($novedad->bios ?? [])->firstWhere('nombre', $creador->nombre)['rol'] ?? null,
            ]);
        }

        // Orden por fecha desc (los sin fecha al final)
        $participaciones = $participaciones->sortByDesc(fn($p) => $p['fecha'] ? $p['fecha']->timestamp : 0)->values();

        return view('creador.show', compact('creador', 'participaciones'));
    }
}
