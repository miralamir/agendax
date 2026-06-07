<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovedadController extends Controller
{
    public function index()
    {
        $novedades = Novedad::orderBy('published_at', 'desc')->paginate(10);
        return view('dashboard.novedades.index', compact('novedades'));
    }

    public function create()
    {
        return view('dashboard.novedades.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:novedades,slug',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 2MB max
            'gallery.*' => 'nullable|image|max:2048', // Each image 2MB max
            'videos.*' => 'nullable|url',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'isPublished' => 'boolean',
            'isFeatured' => 'boolean',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public/novedades/main');
        }

        // Handle gallery images upload
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('public/novedades/gallery');
            }
        }
        $validated['gallery'] = $galleryPaths;

        // Handle videos (array of URLs)
        $validated['videos'] = $request->input('videos');

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $validated['pdf'] = $request->file('pdf')->store('public/novedades/pdf');
        }

        $novedad = Novedad::create($validated);

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad creada exitosamente.');
    }

    public function edit(Novedad $novedad)
    {
        return view('dashboard.novedades.form', compact('novedad'));
    }

    public function update(Request $request, Novedad $novedad)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:novedades,slug,' . $novedad->id,
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 2MB max
            'gallery.*' => 'nullable|image|max:2048', // Each image 2MB max
            'videos.*' => 'nullable|url',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'category' => 'required|string|max:255',
            'subCategory' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'isPublished' => 'boolean',
            'isFeatured' => 'boolean',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($novedad->image) {
                Storage::delete($novedad->image);
            }
            $validated['image'] = $request->file('image')->store('public/novedades/main');
        }

        // Handle gallery images upload
        $galleryPaths = $novedad->gallery ?? []; // Keep existing if no new ones
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            foreach ($galleryPaths as $oldImage) {
                Storage::delete($oldImage);
            }
            $galleryPaths = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('public/novedades/gallery');
            }
        }
        $validated['gallery'] = $galleryPaths;

        // Handle videos (array of URLs)
        $validated['videos'] = $request->input('videos');

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF if exists
            if ($novedad->pdf) {
                Storage::delete($novedad->pdf);
            }
            $validated['pdf'] = $request->file('pdf')->store('public/novedades/pdf');
        }

        $novedad->update($validated);

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad actualizada exitosamente.');
    }

    public function destroy(Novedad $novedad)
    {
        // Delete associated files
        if ($novedad->image) {
            Storage::delete($novedad->image);
        }
        if ($novedad->gallery) {
            foreach ($novedad->gallery as $image) {
                Storage::delete($image);
            }
        }
        if ($novedad->pdf) {
            Storage::delete($novedad->pdf);
        }

        $novedad->delete();

        return redirect()->route('dashboard.novedades.index')->with('success', 'Novedad eliminada exitosamente.');
    }
}
