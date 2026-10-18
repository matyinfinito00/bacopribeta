<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\SuperAdministrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticacionController extends Controller
{
    //Mostrar login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $credenciales = $request->only('correo', 'password');
        $remember = $request->boolean('remember', false);

        // 1. Primero buscar en super administradores
        $superAdmin = SuperAdministrador::where('correo', $credenciales['correo'])->first();

        if ($superAdmin && Hash::check($credenciales['password'], $superAdmin->password)) {
            // Validar estado
            if (!$superAdmin->estado) {
                throw ValidationException::withMessages([
                    'correo' => 'Tu cuenta está desactivada. Contacta a soporte.',
                ]);
            }

            // Login con guard superadmin
            Auth::guard('superadmin')->login($superAdmin, $remember);
            $request->session()->regenerate();

            return redirect()->route('admin.super-admin-dashboard');
        }

        // 2. Si no es super admin, buscar en administradores normales
        $admin = Administrador::where('correo', $credenciales['correo'])->first();

        if (!$admin || !Hash::check($credenciales['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'correo' => 'Las credenciales no son correctas.',
            ]);
        }

        // Validar estado del administrador
        if (!$admin->estado) {
            throw ValidationException::withMessages([
                'correo' => 'Tu cuenta está desactivada. Contacta a soporte.',
            ]);
        }

        // Validar que la comunidad esté activa
        if ($admin->comunidad_id) {
            $admin->load('comunidad');
            if ($admin->comunidad && !$admin->comunidad->estado) {
                throw ValidationException::withMessages([
                    'correo' => 'La comunidad está suspendida. Contacta a soporte.',
                ]);
            }
        }

        // Login usando el guard admin
        Auth::guard('admin')->login($admin, $remember);
        $request->session()->regenerate();

        return redirect()->route('admin.admin-comunidad-dashboard');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        // Detectar cuál guard está activo y cerrar sesión
        if (Auth::guard('superadmin')->check()) {
            Auth::guard('superadmin')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.admin')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    // Mostrar formulario para cambiar contraseña
    public function showChangePasswordForm()
    {
        return view('admin.cambiar-contrasenia');
    }

    // Cambiar contraseña
    public function changePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Detectar el usuario autenticado (super admin o admin)
        if (Auth::guard('superadmin')->check()) {
            $usuario = Auth::guard('superadmin')->user();
        } else {
            $usuario = Auth::guard('admin')->user();
        }

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
        }

        // Actualizar contraseña
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }
}
