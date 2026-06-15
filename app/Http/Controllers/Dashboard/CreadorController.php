<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Creador;
use App\Helpers\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreadorController extends Controller
{
    public function index(Request $request)
    {
        $query = Creador::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('rol', 'like', "%{$search}%");
            });
        }
        if ($request->filled('rol')) $query->where('rol', $request->get('rol'));
        if ($request->get('conbio') === '1') $query->whereNotNull('bio')->where('bio', '!=', '');
        if ($request->get('conbio') === '0') $query->where(fn($q) => $q->whereNull('bio')->orWhere('bio', ''));
        if ($request->get('confoto') === '1') $query->whereNotNull('foto')->where('foto', '!=', '');
        if ($request->get('confoto') === '0') $query->where(fn($q) => $q->whereNull('foto')->orWhere('foto', ''));

        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        if (!in_array($sort, ['id', 'nombre', 'rol'])) $sort = 'id';

        $creadores = $query->orderBy($sort, $dir)->paginate(20)->withQueryString();
        return view('dashboard.creadores.index', compact('creadores'));
    }

    public function create()
    {
        return view('dashboard.creadores.form', ['creador' => new Creador()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('foto')) $data['foto'] = ImageOptimizer::store($request->file('foto'), 'creadores/images');
        $data['slug'] = $this->slugUnico($data['nombre']);
        Creador::create($data);
        return redirect()->route('dashboard.creadores.index')->with('success', 'Creador creado exitosamente.');
    }

    public function edit(Creador $creador)
    {
        return view('dashboard.creadores.form', compact('creador'));
    }

    public function update(Request $request, Creador $creador)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('foto')) $data['foto'] = ImageOptimizer::store($request->file('foto'), 'creadores/images');
        if ($data['nombre'] !== $creador->nombre) $data['slug'] = $this->slugUnico($data['nombre'], $creador->id);
        $creador->update($data);
        return redirect()->route('dashboard.creadores.index')->with('success', 'Creador actualizado exitosamente.');
    }

    public function destroy(Creador $creador)
    {
        $creador->delete();
        return back()->with('success', 'Creador eliminado.');
    }

    public function bulk(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return back()->with('success', 'No seleccionaste ningún creador.');
        if ($request->input('accion') === 'eliminar') {
            Creador::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' creadores eliminados.');
        }
        return back()->with('success', 'Acción no reconocida.');
    }

    private function validateData(Request $request): array
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'rol' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'fotoUrl' => 'nullable|string',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'rol' => $request->rol,
            'bio' => $request->bio,
        ];

        // Si pegó una URL y no subió archivo, usar la URL como foto
        if ($request->filled('fotoUrl') && !$request->hasFile('foto')) {
            $data['foto'] = $request->fotoUrl;
        }

        return $data;
    }

    private function slugUnico(string $nombre, $ignorarId = null): string
    {
        $base = Str::slug($nombre);
        $slug = $base;
        $i = 1;
        while (Creador::where('slug', $slug)->when($ignorarId, fn($q) => $q->where('id', '!=', $ignorarId))->exists()) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
