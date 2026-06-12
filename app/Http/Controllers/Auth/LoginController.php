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

    $errores = [];

    if (!$request->filled('username')) {
        $errores['cedula'] = 'Ingrese su numero de cedula';
    } elseif (strlen($request->username) < 7 || strlen($request->username) > 8) {
        $errores['cedula'] = 'Debe tener de 7 a 8 digitos';
    }

    if (!$request->filled('password')) {
        $errores['clave'] = 'Ingrese su contraseña de acceso';
    }
    
    if (!empty($errores)) {
        return response()->json([
            "status" => "errores",
            "errores" => $errores
        ], 422);
    }

    $user = User::findByCredentials($request->username);

    if ($user && !Hash::check($request->password, $user->password_hash)){
        $errores['clave'] = 'La contraseña es incorrecta.';
    }

    if (!$user) {
        $errores['cedula'] = 'Usuario no existente';
    }

    if (!empty($errores)) {
        return response()->json([
            "status" => "errores",
            "errores" => $errores
        ], 422);
    }



    if ($user && Hash::check($request->password, $user->password_hash)) {
        
        if ($user->status === User::STATUS_INACTIVO) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Tu cuenta no se encuentra activa en el sistema'
            ], 422);
        }

        if ($user->status === User::STATUS_VERIFICAR) {
            return response()->json([
                'status' => 'verificar'
            ], 422);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return response()->json([
            'status' => 'success',
            'redirect' => $user->getDashboardUrl()
        ]);
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