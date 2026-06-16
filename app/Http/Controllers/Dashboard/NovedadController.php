<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'pdf', 'gallery', 'videos', 'isPublished', 'isFeatured']);

        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'novedades/images');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('novedades/pdfs', 'public');
        }

        $data['gallery'] = json_encode(array_filter(preg_split('/\r\n|\r|\n|,/', $request->input('gallery', ''))));
        $data['videos'] = json_encode(array_filter($request->input('videos', [])));

        $data['isPublished'] = $request->has('isPublished') ? 1 : 0;
        $data['isFeatured'] = $request->has('isFeatured') ? 1 : 0;

        Novedad::create($data);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'pdf', 'gallery', 'videos', 'isPublished', 'isFeatured', '_method', '_token', 'clear_image', 'clear_pdf']);

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
            if ($novedad->pdf) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('novedades/pdfs', 'public');
        } elseif ($request->input('clear_pdf')) {
            if ($novedad->pdf) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = null;
        }

        // Handle gallery con estructura url+caption
        $rawGallery = $request->input('gallery', []);
        $galleryItems = [];
        if (is_array($rawGallery)) {
            foreach ($rawGallery as $item) {
                if (is_array($item)) {
                    $url = trim($item['url'] ?? '');
                    $caption = trim($item['caption'] ?? '');
                    if ($url) $galleryItems[] = ['url' => $url, 'caption' => $caption];
                } elseif (is_string($item) && trim($item)) {
                    $galleryItems[] = ['url' => trim($item), 'caption' => ''];
                }
            }
        }
        // Manejar archivos subidos
        if ($request->hasFile('galleryFiles')) {
            foreach ($request->file('galleryFiles') as $idx => $f) {
                if ($f && $f->isValid()) {
                    $path = ImageOptimizer::store($f, 'novedades/gallery');
                    if (isset($galleryItems[$idx])) {
                        $galleryItems[$idx]['url'] = $path;
                    } else {
                        $galleryItems[] = ['url' => $path, 'caption' => ''];
                    }
                }
            }
        }
        $data['gallery'] = array_values($galleryItems);

        // Handle videos (URLs as dynamic inputs)
        $newVideos = array_filter($request->input('videos', []));
        $data['videos'] = empty($newVideos) ? [] : array_values($newVideos);

        $data['isPublished'] = $request->has('isPublished') ? 1 : 0;
        $data['isFeatured'] = $request->has('isFeatured') ? 1 : 0;

        $novedad->update($data);

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novedad $novedad) // Usar 'novedad'
    {
        if ($novedad->image) {
            Storage::delete($novedad->image);
        }
        if ($novedad->pdf) {
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