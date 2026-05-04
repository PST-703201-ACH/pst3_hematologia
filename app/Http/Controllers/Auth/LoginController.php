<?php
  
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
|
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
            'password_hash' => ['required', 'string'],
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

        switch ($user->id_rol) {
            case 1:
                return redirect()->intended('/admin/');
            default:
                return redirect()->intended('/admin/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
