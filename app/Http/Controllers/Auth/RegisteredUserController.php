<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Honeypot anti-bots: el campo "sitio_web" está fuera de la pantalla y
        // ningún humano lo ve, así que si viene con contenido es un bot. Se
        // finge éxito (302 a la home, igual que un alta correcta) para no
        // avisarle que lo detectamos: si devolviéramos un error, quien lo
        // opera ajustaría el bot hasta esquivar la trampa.
        if ($request->filled('sitio_web')) {
            return redirect()->route('home');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // A la home: quien se registra es un lector, y /dashboard es solo para
        // admins, así que mandarlo ahí lo hacía rebotar contra el middleware
        // para terminar igual en la home. De paso, esto deja el alta real y el
        // rechazo del honeypot en la misma respuesta exacta.
        // Cuando se active MustVerifyEmail, el destino pasa a ser
        // verification.notice.
        return redirect()->route('home');
    }
}
