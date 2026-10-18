<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarComunidadActiva
{
    /**
     * Verifica que la comunidad del usuario autenticado esté activa.
     * Aplica a Residentes y Administradores (seguridad) que usan la API.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Residente y Administrador tienen relación comunidad
        $comunidad = $user->comunidad ?? null;

        if ($comunidad && !$comunidad->estado) {
            // Revocar token para forzar re-login
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'La comunidad está suspendida. Contacta al administrador.',
            ], 403);
        }

        return $next($request);
    }
}
