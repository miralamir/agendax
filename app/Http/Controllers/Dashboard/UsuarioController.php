<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('comentarios');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }
        if ($request->get('estado') === 'baneados') $query->where('baneado', true);
        if ($request->get('estado') === 'activos') $query->where('baneado', false);

        $usuarios = $query->latest()->paginate(25)->withQueryString();
        return view('dashboard.usuarios.index', compact('usuarios'));
    }

    public function toggleBaneo(User $usuario)
    {
        // No permitir banear admins
        if ($usuario->isAdmin()) {
            return back()->with('success', 'No se puede banear a un administrador.');
        }
        $usuario->update(['baneado' => !$usuario->baneado]);
        $msg = $usuario->baneado ? 'Usuario baneado. Sus comentarios quedaron ocultos.' : 'Usuario reactivado.';
        return back()->with('success', $msg);
    }

    public function borrarComentario(Comentario $comentario)
    {
        $comentario->delete();
        return back()->with('success', 'Comentario eliminado.');
    }

    public function destroy(User $usuario, Request $request)
    {
        // No permitir borrarse a si mismo
        if ($usuario->id === $request->user()->id) {
            return back()->with('success', 'No podés eliminar tu propia cuenta.');
        }
        // No permitir borrar al ultimo admin
        if ($usuario->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('success', 'No se puede eliminar al único administrador.');
        }
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado definitivamente.');
    }

    public function cambiarRol(User $usuario, Request $request)
    {
        // No permitir quitarse el admin a si mismo
        if ($usuario->id === $request->user()->id) {
            return back()->with('success', 'No podés cambiar tu propio rol.');
        }
        // No dejar sin admins
        if ($usuario->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('success', 'No se puede quitar el rol al único administrador.');
        }
        $usuario->update(['role' => $usuario->isAdmin() ? 'user' : 'admin']);
        $msg = $usuario->isAdmin() ? 'Usuario promovido a administrador.' : 'Usuario pasado a usuario común.';
        return back()->with('success', $msg);
    }
}
