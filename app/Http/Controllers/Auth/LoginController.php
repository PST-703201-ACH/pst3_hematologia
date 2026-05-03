<?php
  
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->authenticated(request(), Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Maneja el intento de autenticación.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->authenticated($request, Auth::user());
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('username');
    }

    /**
     * Lógica de redirección basada en roles tras la autenticación.
     */
    protected function authenticated(Request $request, $user)
    {
        // Por ahora redirige a admin, pero dejamos la estructura lista para otros roles.
        // Asumimos que el ID del rol de administrador es 1 (ajustar según sea necesario).
        // refactorizar para simplificar la logica de redireccion del usuario autenticado

        switch ($user->id_rol) {
            case 1:
                return redirect()->intended('/admin/');
            case 2:
                return redirect()->intended('/medico/');
            default:
                return redirect()->intended('/admin/');
        }
    }

    /**
     * Cierra la sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}


/*
public function login(Request $request)
{
    $login = $request->input('username'); // El campo del form
    $password = $request->input('password');

    // Buscamos al usuario por username O por la cedula de su persona vinculada
    $user = User::where('username', $login)
        ->orWhereHas('persona', function($query) use ($login) {
            $query->where('cedula', $login);
        })
        ->first();

    if ($user && Hash::check($password, $user->password_hash)) {
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return $this->authenticated($request, $user);
    }

    return back()->withErrors([
        'username' => 'Las credenciales no coinciden.',
    ])->onlyInput('username');
}
*/