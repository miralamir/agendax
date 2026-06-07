<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $novedades = Novedad::orderBy('published_at', 'desc')->paginate(10);
        return view('dashboard.novedades.index', compact('novedades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.novedades.form');
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
            $data['image'] = $request->file('image')->store('public/novedades/images');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('public/novedades/pdfs');
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
        if ($request->hasFile('image')) {
            if ($novedad->image) {
                Storage::delete($novedad->image);
            }
            $data['image'] = $request->file('image')->store('public/novedades/images');
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
            $data['pdf'] = $request->file('pdf')->store('public/novedades/pdfs');
        } elseif ($request->input('clear_pdf')) {
            if ($novedad->pdf) {
                Storage::delete($novedad->pdf);
            }
            $data['pdf'] = null;
        }

        // Handle gallery (URLs as text area)
        $data['gallery'] = json_encode(array_filter(preg_split('/\r\n|\r|\n|,/', $request->input('gallery', ''))));
        // Handle videos (URLs as dynamic inputs)
        $data['videos'] = json_encode(array_filter($request->input('videos', [])));

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
        $galleryImages = json_decode($novedad->gallery, true);
        foreach ($galleryImages as $img) {
            // Asumimos que si no es una URL http/https, es un path de storage
            if (!Str::startsWith($img, ['http://', 'https://'])) {
                 Storage::delete($img);
            }
        }

        $novedad->delete();

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad eliminada exitosamente.');
    }
}