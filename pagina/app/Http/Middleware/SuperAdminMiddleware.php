<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Verificar login con guard de super admin
        if (!Auth::guard('superadmin')->check()) {
            return redirect()->route('login.admin');
        }

        // Todo OK
        return $next($request);
    }
}
