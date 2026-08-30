<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * clase de middleware para verificar el rol del usuario
     * se usa para restringir el acceso a ciertas rutas
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = \Illuminate\Support\Facades\Auth::User();

        // Verificamos si el id_rol coincide con el que exige la ruta
        if ($user->id_rol != $role) {
            abort(403, 'No tienes permiso para acceder a esta área.');
        }

        return $next($request);
    }
}
