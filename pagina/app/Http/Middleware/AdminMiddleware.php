<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Verificar si hay un admin logueado
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login.admin');
        }

        $admin = Auth::guard('admin')->user();

        // Verificar que la comunidad esté activa
        if ($admin->comunidad_id) {
            $admin->load('comunidad');
            if ($admin->comunidad && !$admin->comunidad->estado) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login.admin')
                    ->with('error', 'La comunidad está suspendida. Contacta a soporte.');
            }
        }
        
        // Todo OK → continuar
        return $next($request);
    }
}

