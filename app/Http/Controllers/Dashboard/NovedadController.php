<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use App\Models\Creador;
use App\Helpers\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Novedad::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) $query->where('category', $request->get('category'));
        if ($request->filled('subCategory')) $query->where('subCategory', $request->get('subCategory'));
        if ($request->get('published') !== null && $request->get('published') !== '') $query->where('isPublished', $request->get('published'));
        if ($request->get('featured') !== null && $request->get('featured') !== '') $query->where('isFeatured', $request->get('featured'));

        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        if (!in_array($sort, ['id', 'title', 'category', 'published_at', 'created_at'])) $sort = 'id';

        $novedades = $query->orderBy($sort, $dir)->paginate(20)->withQueryString();
        return view('dashboard.novedades.index', compact('novedades'));
    }

    public function bulk(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return back()->with('success', 'No seleccionaste ninguna novedad.');

        switch ($request->input('accion')) {
            case 'eliminar': Novedad::whereIn('id', $ids)->delete(); $msg = count($ids) . ' novedades eliminadas.'; break;
            case 'publicar':
                Novedad::whereIn('id', $ids)->update(['isPublished' => 1]);
                Novedad::whereIn('id', $ids)->whereNull('published_at')->update(['published_at' => now()]);
                $msg = count($ids) . ' novedades publicadas.'; break;
            case 'despublicar': Novedad::whereIn('id', $ids)->update(['isPublished' => 0]); $msg = count($ids) . ' novedades pasadas a borrador.'; break;
            case 'destacar': Novedad::whereIn('id', $ids)->update(['isFeatured' => 1]); $msg = count($ids) . ' novedades destacadas.'; break;
            case 'quitar_destacado': Novedad::whereIn('id', $ids)->update(['isFeatured' => 0]); $msg = 'Destacado quitado a ' . count($ids) . ' novedades.'; break;
            default: $msg = 'Acción no reconocida.';
        }
        return back()->with('success', $msg);
    }

    public function toggle(Novedad $novedad, string $campo)
    {
        if (!in_array($campo, ['isPublished', 'isFeatured'])) abort(404);
        $nuevo = !$novedad->$campo;
        $datos = [$campo => $nuevo];
        if ($campo === 'isPublished' && $nuevo && !$novedad->published_at) $datos['published_at'] = now();
        $novedad->update($datos);
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.novedades.form', ['novedad' => new \App\Models\Novedad()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'galleryFiles' => 'nullable|array',
            'galleryFiles.*' => 'nullable|image|max:20480',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'bios' => 'nullable|array',
            'bios.*.nombre' => 'nullable|string',
            'bios.*.rol' => 'nullable|string',
            'bios.*.bio' => 'nullable|string',
            'bios.*.foto' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'pdf', 'pdf_url', 'gallery', 'videos', 'isPublished', 'isFeatured', 'bios', 'bioFotos']);

        $data['slug'] = Str::slug($request->title);

        $data['bios'] = $this->procesarBios($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'novedades/images');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('novedades/pdfs', 'public');
        } elseif ($request->filled('pdf_url')) {
            $data['pdf'] = trim($request->input('pdf_url'));
        }

        // Galería: alineada por índice (cada fila usa su propio archivo o su url existente).
        $data['gallery'] = $this->procesarGaleria($request, 'novedades/gallery');

        $newVideos = array_filter($request->input('videos', []));
        $data['videos'] = empty($newVideos) ? [] : array_values($newVideos);

        $data['isPublished'] = $request->has('isPublished') ? 1 : 0;
        $data['isFeatured'] = $request->has('isFeatured') ? 1 : 0;

        if ($data['isPublished'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Cuerpo: contenido del editor WYSIWYG. Purificar (whitelist angosta) y
        // autolinkear URLs/emails sueltos, una sola vez, antes de persistir.
        if (!empty($data['body'])) {
            $data['body'] = \App\Helpers\TextHelper::autoLinkHtml(
                \Mews\Purifier\Facades\Purifier::clean($data['body'], 'quill')
            );
        }

        $novedad = Novedad::create($data);
        $this->syncCreadores($novedad->bios ?? []);

        if ($request->input('accion') === 'save') {
            return redirect()->route('dashboard.novedades.edit', $novedad->id)->with('success', 'Novedad creada exitosamente.');
        }
        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Novedad $novedad) // Usar 'novedad'
    {
        return view('dashboard.novedades.form', compact('novedad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Novedad $novedad) // Usar 'novedad'
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'galleryFiles' => 'nullable|array',
            'galleryFiles.*' => 'nullable|image|max:20480',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'bios' => 'nullable|array',
            'bios.*.nombre' => 'nullable|string',
            'bios.*.rol' => 'nullable|string',
            'bios.*.bio' => 'nullable|string',
            'bios.*.foto' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'pdf', 'pdf_url', 'gallery', 'videos', 'isPublished', 'isFeatured', '_method', '_token', 'clear_image', 'clear_pdf', 'bios', 'bioFotos']);

        // Mantener el slug si no se cambia el título
        if ($request->has('title') && $request->title !== $novedad->title) {
             $data['slug'] = Str::slug($request->title);
        }

        // Handle main image
        if (!$request->hasFile('image') && !$request->input('clear_image')) {
            // mantener imagen existente
        } elseif ($request->hasFile('image')) {
            if ($novedad->image) {
                Storage::delete($novedad->image);
            }
            $data['image'] = ImageOptimizer::store($request->file('image'), 'novedades/images');
        } elseif ($request->input('clear_image')) {
            if ($novedad->image) {
                Storage::delete($novedad->image);
            }
            $data['image'] = null;
        }

        // Handle PDF
        if ($request->hasFile('pdf')) {
            if ($novedad->pdf && !str_starts_with($novedad->pdf, 'http')) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('novedades/pdfs', 'public');
        } elseif ($request->filled('pdf_url')) {
            if ($novedad->pdf && !str_starts_with($novedad->pdf, 'http')) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = trim($request->input('pdf_url'));
        } elseif ($request->input('clear_pdf')) {
            if ($novedad->pdf && !str_starts_with($novedad->pdf, 'http')) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = null;
        }

        // Galería: alineada por índice (cada fila usa su propio archivo o su url existente).
        $data['gallery'] = $this->procesarGaleria($request, 'novedades/gallery');

        // Handle videos (URLs as dynamic inputs)
        $newVideos = array_filter($request->input('videos', []));
        $data['videos'] = empty($newVideos) ? [] : array_values($newVideos);

        $data['bios'] = $this->procesarBios($request);

        $data['isPublished'] = $request->has('isPublished') ? 1 : 0;
        $data['isFeatured'] = $request->has('isFeatured') ? 1 : 0;

        if ($data['isPublished'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Cuerpo: contenido del editor WYSIWYG. Purificar (whitelist angosta) y
        // autolinkear URLs/emails sueltos, una sola vez, antes de persistir.
        if (!empty($data['body'])) {
            $data['body'] = \App\Helpers\TextHelper::autoLinkHtml(
                \Mews\Purifier\Facades\Purifier::clean($data['body'], 'quill')
            );
        }

        $novedad->update($data);
        $this->syncCreadores($novedad->bios ?? []);

        if ($request->input('accion') === 'save') {
            return redirect()->route('dashboard.novedades.edit', $novedad->id)->with('success', 'Novedad actualizada exitosamente.');
        }
        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad actualizada exitosamente.');
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

    /**
     * Procesa el array de bios del request: sube las fotos cargadas manualmente
     * y descarta las filas sin nombre. Mismo esquema que eventos.
     */
    private function procesarBios(Request $request): array
    {
        $bios = $request->input('bios', []);
        if (!is_array($bios)) $bios = [];

        if ($request->hasFile('bioFotos')) {
            foreach ($request->file('bioFotos') as $idx => $fotoFile) {
                if ($fotoFile && isset($bios[$idx])) {
                    $bios[$idx]['foto'] = ImageOptimizer::store($fotoFile, 'novedades/bios');
                }
            }
        }

        // Bio (WYSIWYG): purificar + autolinkear cada bios[].bio antes de guardar.
        foreach ($bios as $idx => $bio) {
            if (!empty($bio['bio'])) {
                $bios[$idx]['bio'] = \App\Helpers\TextHelper::autoLinkHtml(
                    \Mews\Purifier\Facades\Purifier::clean($bio['bio'], 'quill')
                );
            }
        }

        // Descartar filas sin nombre
        $bios = array_values(array_filter($bios, fn($b) => !empty(trim($b['nombre'] ?? ''))));

        return $bios;
    }

    /**
     * Crea/actualiza los registros Creador a partir de los bios.
     * Solo completa campos vacíos del creador para no pisar datos cargados desde eventos.
     */
    private function syncCreadores(array $bios): void
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novedad $novedad) // Usar 'novedad'
    {
        if ($novedad->image) {
            Storage::delete($novedad->image);
        }
        if ($novedad->pdf && !str_starts_with($novedad->pdf, 'http')) {
            Storage::delete($novedad->pdf);
        }
        // También borrar imágenes de la galería si se gestionan individualmente y son paths de storage
        $galleryImages = is_array($novedad->gallery) ? $novedad->gallery : json_decode($novedad->gallery ?? '[]', true);
        foreach (($galleryImages ?: []) as $img) {
            // Asumimos que si no es una URL http/https, es un path de storage
            if (!Str::startsWith($img, ['http://', 'https://'])) {
                 Storage::delete($img);
            }
        }

        $novedad->delete();

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad eliminada exitosamente.');
    }
}