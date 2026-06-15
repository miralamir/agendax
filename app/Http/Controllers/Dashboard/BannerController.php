<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Helpers\ImageOptimizer;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public static array $posiciones = [
        'home_hero_izq' => 'Home · Izquierda del hero (300×250)',
        'home_hero_der' => 'Home · Derecha del hero (300×250)',
        'home_post_destacados' => 'Home · Después de destacados (728×90)',
        'home_post_mapa' => 'Home · Después del mapa (970×250)',
        'cat_entre_secciones' => 'Categorías · Entre destacados y noticias (728×90)',
        'articulo_izq' => 'Artículo · Costado izquierdo (160×600)',
        'articulo_der' => 'Artículo · Costado derecho (160×600)',
        'articulo_post_breadcrumb' => 'Artículo · Debajo del breadcrumb (728×90)',
        'articulo_pre_comentarios' => 'Artículo · Antes de comentarios (728×90)',
        'creador_post_breadcrumb' => 'Creador · Debajo del breadcrumb (728×90)',
        'creador_post_bio' => 'Creador · Debajo de la bio (728×90)',
        'creador_izq' => 'Creador · Costado izquierdo (160×600)',
        'creador_der' => 'Creador · Costado derecho (160×600)',
    ];

    public function index(Request $request)
    {
        $query = Banner::query();
        if ($request->filled('posicion')) $query->whereHas('posiciones', function($q) use ($request) { $q->where('posicion', $request->get('posicion')); });
        if ($request->get('estado') === 'activos') $query->where('activo', true);
        if ($request->get('estado') === 'inactivos') $query->where('activo', false);

        $banners = $query->latest()->paginate(20)->withQueryString();
        return view('dashboard.banners.index', ['banners' => $banners, 'posiciones' => self::$posiciones]);
    }

    public function create()
    {
        return view('dashboard.banners.form', ['banner' => new Banner(), 'posiciones' => self::$posiciones]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        if ($request->hasFile('imagen')) {
            $data['imagen'] = ImageOptimizer::store($request->file('imagen'), 'banners');
        } elseif ($request->filled('imagenUrl')) {
            $data['imagen'] = $request->imagenUrl;
        }
        $banner = Banner::create($data);
        $this->sincronizarPosiciones($banner, $request->input('posiciones', []));
        return redirect()->route('dashboard.banners.index')->with('success', 'Banner creado.');
    }

    public function edit(Banner $banner)
    {
        return view('dashboard.banners.form', ['banner' => $banner, 'posiciones' => self::$posiciones]);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validar($request);
        if ($request->hasFile('imagen')) {
            $data['imagen'] = ImageOptimizer::store($request->file('imagen'), 'banners');
        } elseif ($request->filled('imagenUrl')) {
            $data['imagen'] = $request->imagenUrl;
        }
        $banner->update($data);
        $this->sincronizarPosiciones($banner, $request->input('posiciones', []));
        return redirect()->route('dashboard.banners.index')->with('success', 'Banner actualizado.');
    }

    private function sincronizarPosiciones(Banner $banner, array $posiciones): void
    {
        $banner->posiciones()->delete();
        foreach (array_unique($posiciones) as $pos) {
            if (array_key_exists($pos, self::$posiciones)) {
                $banner->posiciones()->create(['posicion' => $pos]);
            }
        }
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner eliminado.');
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['activo' => !$banner->activo]);
        return back()->with('success', $banner->activo ? 'Banner activado.' : 'Banner desactivado.');
    }

    private function validar(Request $request): array
    {
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'imagen' => 'nullable|image|max:5120',
            'imagenUrl' => 'nullable|url',
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
        ]);
        return [
            'titulo' => $request->titulo,
            'link' => $request->link,
            'nueva_pestana' => $request->boolean('nueva_pestana'),
            'activo' => $request->boolean('activo'),
            'desde' => $request->desde ?: null,
            'hasta' => $request->hasta ?: null,
        ];
    }
}
