<?php
  
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended(Auth::user()->getDashboardUrl());
        }
        return view('auth.login');
    }

    /**
     * Maneja el intento de autenticación.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::findByCredentials($request->username);

        if ($user && Hash::check($request->password, $user->password_hash)) {
            // Verificar si el usuario está activo
            if ($user->status !== User::STATUS_ACTIVO) {
                return back()->withErrors([
                    'username' => 'Tu cuenta no se encuentra activa en el sistema.',
                ])->onlyInput('username');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended($user->getDashboardUrl());
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('username');
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