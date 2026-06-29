<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Helpers\ImageOptimizer;
use App\Models\Creador;
use App\Models\Lugar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%")
                  ->orWhere('locationName', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) $query->where('category', $request->get('category'));
        if ($request->filled('subCategory')) $query->where('subCategory', $request->get('subCategory'));
        if ($request->get('published') !== null && $request->get('published') !== '') $query->where('isPublished', $request->get('published'));
        if ($request->get('featured') !== null && $request->get('featured') !== '') $query->where('isFeatured', $request->get('featured'));

        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        if (!in_array($sort, ['id', 'title', 'category', 'startDate', 'created_at'])) $sort = 'id';

        $eventos = $query->orderBy($sort, $dir)->paginate(20)->withQueryString();
        return view('dashboard.eventos.index', compact('eventos'));
    }

    public function bulk(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return back()->with('success', 'No seleccionaste ningún evento.');

        switch ($request->input('accion')) {
            case 'eliminar': Evento::whereIn('id', $ids)->delete(); $msg = count($ids) . ' eventos eliminados.'; break;
            case 'publicar': Evento::whereIn('id', $ids)->update(['isPublished' => 1]); $msg = count($ids) . ' eventos publicados.'; break;
            case 'despublicar': Evento::whereIn('id', $ids)->update(['isPublished' => 0]); $msg = count($ids) . ' eventos pasados a borrador.'; break;
            case 'destacar': Evento::whereIn('id', $ids)->update(['isFeatured' => 1]); $msg = count($ids) . ' eventos destacados.'; break;
            case 'quitar_destacado': Evento::whereIn('id', $ids)->update(['isFeatured' => 0]); $msg = 'Destacado quitado a ' . count($ids) . ' eventos.'; break;
            default: $msg = 'Acción no reconocida.';
        }
        return back()->with('success', $msg);
    }

    public function toggle(Evento $evento, string $campo)
    {
        if (!in_array($campo, ['isPublished', 'isFeatured'])) abort(404);
        $evento->update([$campo => !$evento->$campo]);
        return back();
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
            'bios' => 'nullable|array',
            'bios.*.nombre' => 'nullable|string',
            'bios.*.rol' => 'nullable|string',
            'bios.*.bio' => 'nullable|string',
            'bios.*.foto' => 'nullable|string',
            'curator' => 'nullable|string|max:255',
            
            'locationName' => 'nullable|string|max:255',
            'venueAddress' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'venueHours' => 'nullable|string|max:255',
            'priceInfo' => 'nullable|string',
            'venuePhone' => 'nullable|string|max:255',
            'venueEmail' => 'nullable|string|max:255',
            'venueWebsite' => 'nullable|string|max:255',
            'venueSocial' => 'nullable|string|max:255',
            
            'mainImageUrl' => 'nullable|string',
            'secondaryImageUrl' => 'nullable|string',
            'artistImageUrl' => 'nullable|string',
            'mainImage' => 'nullable|image|max:5120',
            'secondaryImage' => 'nullable|image|max:5120',
            'artistImage' => 'nullable|image|max:5120',
            'catalogPdfUrl' => 'nullable|string',
            'catalogPdf' => 'nullable|file|mimes:pdf|max:10240',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|url',
            'ticketUrl' => 'nullable|string',
            'gallery' => 'nullable|array',
        ]);

        $validated['isPublished'] = $request->has('isPublished');
        $validated['isFeatured'] = $request->has('isFeatured');

        // Galería: alineada por índice (cada fila usa su propio archivo o su url existente).
        $validated['gallery'] = $this->procesarGaleria($request, 'eventos/gallery');

        // Manejar fotos de biografías
        if (request()->hasFile('bioFotos')) {
            $bios = $validated['bios'] ?? [];
            foreach (request()->file('bioFotos') as $idx => $fotoFile) {
                if ($fotoFile && isset($bios[$idx])) {
                    $bios[$idx]['foto'] = ImageOptimizer::store($fotoFile, 'eventos/bios');
                }
            }
            $validated['bios'] = $bios;
        }
        // Manejar subida de imágenes con optimización
        $disks = ['mainImage', 'secondaryImage', 'artistImage'];
        foreach ($disks as $field) {
            if (request()->hasFile($field)) {
                $validated[$field] = ImageOptimizer::store(request()->file($field), 'eventos/images');
            }
        }
        unset($validated['galleryFiles']);
        // Manejar PDF
        if (request()->hasFile('catalogPdf') && request()->file('catalogPdf')->isValid()) {
            $validated['catalogPdfUrl'] = request()->file('catalogPdf')->store('eventos/pdfs', 'public');
        }
        unset($validated['catalogPdf']);

        // Limpiar videos vacíos
        if (isset($validated['videos'])) {
            $validated['videos'] = array_values(array_filter($validated['videos']));
        }

        return $validated;
    }

    public function store(Request $request)
    {
        $data = $this->validateAndProcess($request);
        $evento = Evento::create($data);
        self::syncCreadoresYLugares($evento);
        $reglaRaw = $request->input('funciones_regla');
        $regla = is_string($reglaRaw) ? json_decode($reglaRaw, true) : $reglaRaw;
        self::generarFunciones($evento, $regla);
        if ($request->input('accion') === 'save') {
            return redirect()->route('dashboard.eventos.edit', $evento->id)->with('success', 'Evento creado exitosamente.');
        }
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
        self::syncCreadoresYLugares($evento);
        $reglaRaw = $request->input('funciones_regla');
        $regla = is_string($reglaRaw) ? json_decode($reglaRaw, true) : $reglaRaw;
        self::generarFunciones($evento, $regla);
        if ($request->input('accion') === 'save') {
            return redirect()->route('dashboard.eventos.edit', $evento->id)->with('success', 'Evento actualizado exitosamente.');
        }
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Genera las funciones de un evento a partir de una regla.
     * Regla: ['desde'=>'Y-m-d', 'hasta'=>'Y-m-d', 'dias'=>[0..6], 'horarios'=>['0'=>'HH:MM',...]]
     * Dia de semana: 0=domingo, 1=lunes ... 6=sabado (formato Carbon dayOfWeek)
     */
    private static function generarFunciones(Evento $evento, $regla)
    {
        // Siempre regeneramos: borramos las funciones previas
        $evento->funciones()->delete();

        if (empty($regla) || empty($regla['desde']) || empty($regla['hasta']) || empty($regla['dias'])) {
            $evento->funciones_regla = null;
            $evento->saveQuietly();
            return;
        }

        $dias = array_map('intval', (array) $regla['dias']);
        $horarios = $regla['horarios'] ?? [];

        try {
            $desde = \Carbon\Carbon::parse($regla['desde'])->startOfDay();
            $hasta = \Carbon\Carbon::parse($regla['hasta'])->startOfDay();
        } catch (\Exception $e) {
            return;
        }

        if ($hasta->lt($desde)) return;

        // Limite de seguridad: no generar mas de 2 anios de funciones
        if ($desde->diffInDays($hasta) > 730) return;

        $cursor = $desde->copy();
        $nuevas = [];
        while ($cursor->lte($hasta)) {
            $dow = (int) $cursor->dayOfWeek; // 0=domingo .. 6=sabado
            if (in_array($dow, $dias, true)) {
                $hora = $horarios[(string) $dow] ?? ($horarios['general'] ?? null);
                $nuevas[] = [
                    'evento_id'  => $evento->id,
                    'fecha'      => $cursor->format('Y-m-d'),
                    'hora'       => $hora ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $cursor->addDay();
        }

        if (!empty($nuevas)) {
            \App\Models\EventoFuncion::insert($nuevas);
        }

        // Guardar la regla para poder mostrarla al editar
        $evento->funciones_regla = $regla;
        $evento->saveQuietly();
    }

    /**
     * Procesa la galería respetando la alineación por índice entre gallery[i] y galleryFiles[i].
     * Cada fila resuelve su url de su propio archivo subido o de su url existente — un archivo
     * nuevo nunca pisa la url de otra fila. Filas nuevas sin fila de texto previa se agregan al final.
     */
    private function procesarGaleria(Request $request, string $dir): array
    {
        $rawGallery = $request->input('gallery', []);
        $files = $request->file('galleryFiles', []);
        if (!is_array($rawGallery)) $rawGallery = [];
        if (!is_array($files)) $files = [];

        $items = [];
        foreach ($rawGallery as $idx => $item) {
            $caption = trim(is_array($item) ? ($item['caption'] ?? '') : '');
            $url = trim(is_array($item) ? ($item['url'] ?? '') : (is_string($item) ? $item : ''));
            $f = $files[$idx] ?? null;
            if ($f && $f->isValid()) {
                $url = ImageOptimizer::store($f, $dir);
            }
            if ($url !== '') $items[] = ['url' => $url, 'caption' => $caption];
        }
        // Archivos en índices que no figuran en rawGallery (filas nuevas) → append al final
        foreach ($files as $idx => $f) {
            if ($f && $f->isValid() && !array_key_exists($idx, $rawGallery)) {
                $items[] = ['url' => ImageOptimizer::store($f, $dir), 'caption' => ''];
            }
        }
        return array_values($items);
    }

    private static function syncCreadoresYLugares(Evento $evento)
    {
        // Sync creadores desde bios
        $bios = $evento->bios ?? [];
        foreach ($bios as $bio) {
            $nombre = trim($bio['nombre'] ?? '');
            if (!$nombre) continue;
            $slug = Str::slug($nombre);
            $creador = Creador::firstOrNew(['slug' => $slug]);
            $creador->nombre = $nombre;
            if (!empty($bio['rol']) && !$creador->rol) $creador->rol = $bio['rol'];
            if (!empty($bio['foto']) && !$creador->foto) $creador->foto = $bio['foto'];
            if (!empty($bio['bio']) && !$creador->bio) $creador->bio = $bio['bio'];
            $creador->save();
        }

        // Sync lugar
        $nombre = trim($evento->locationName ?? '');
        if ($nombre) {
            $slug = Str::slug($nombre);
            $lugar = Lugar::firstOrNew(['slug' => $slug]);
            $lugar->nombre = $nombre;
            if ($evento->venueAddress && !$lugar->direccion) $lugar->direccion = $evento->venueAddress;
            if ($evento->lat && !$lugar->lat) $lugar->lat = $evento->lat;
            if ($evento->lng && !$lugar->lng) $lugar->lng = $evento->lng;
            if ($evento->venuePhone && !$lugar->telefono) $lugar->telefono = $evento->venuePhone;
            if ($evento->venueEmail && !$lugar->email) $lugar->email = $evento->venueEmail;
            if ($evento->venueWebsite && !$lugar->website) $lugar->website = $evento->venueWebsite;
            $lugar->save();
        }
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('dashboard.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
