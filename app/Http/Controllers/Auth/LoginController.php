<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::User();

            if ($user->primer_ingreso) {
                return redirect()->route('primer.ingreso.datos');
            }

            return redirect()->intended($user->getDashboardUrl());
        }

        return view('auth.login');
    }

    /**
     * Maneja el intento de autenticación.
     */
    public function login(Request $request)
    {

        $errores = [];

        if (! $request->filled('username')) {
            $errores['cedula'] = 'Ingrese su número de cédula';
        } elseif (! preg_match('/^\d{7,8}$/', $request->username)) {
            $errores['cedula'] = 'Ingrese solo los dígitos de su cédula (7 u 8 números)';
        }

        if (! $request->filled('password')) {
            $errores['clave'] = 'Ingrese su contraseña de acceso';
        }

        if (! empty($errores)) {
            return response()->json([
                'status' => 'errores',
                'errores' => $errores,
            ], 422);
        }

        /** @var User|null $user */
        $user = User::findByCredentials($request->username);

        if ($user && ! Hash::check($request->password, $user->password_hash)) {
            $errores['clave'] = 'La contraseña es incorrecta.';
        }

        if (! $user) {
            $errores['cedula'] = 'Usuario no existente';
        }

        if (! empty($errores)) {
            return response()->json([
                'status' => 'errores',
                'errores' => $errores,
            ], 422);
        }

        if ($user && Hash::check($request->password, $user->password_hash)) {

            if ($user->status === User::STATUS_INACTIVO) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Tu cuenta no se encuentra activa en el sistema',
                ], 422);
            }

            if ($user->status === User::STATUS_VERIFICAR) {
                return response()->json([
                    'status' => 'verificar',
                ], 422);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->primer_ingreso) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => route('primer.ingreso.datos'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'redirect' => $user->getDashboardUrl(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'mensaje' => 'No se pudo autenticar el usuario.',
        ], 422);
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
