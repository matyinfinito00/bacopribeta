<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Residente;
use App\Models\PersonalSeguridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // Login de residente
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $residente = Residente::where('correo', $request->correo)->first();

        if (!$residente || !Hash::check($request->password, $residente->password)) {
            throw ValidationException::withMessages([
                'correo' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar que el residente esté activo
        if (!$residente->estado) {
            throw ValidationException::withMessages([
                'correo' => ['Tu cuenta está desactivada. Contacta al administrador.'],
            ]);
        }

        // Verificar que la comunidad esté activa
        $residente->load('comunidad');
        if (!$residente->comunidad || !$residente->comunidad->estado) {
            throw ValidationException::withMessages([
                'correo' => ['La comunidad está suspendida. Contacta al administrador.'],
            ]);
        }

        // Crear token
        $token = $residente->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'residente' => [
                'id' => $residente->id,
                'nombre' => $residente->nombre,
                'apellido' => $residente->apellido,
                'nombre_completo' => $residente->nombre_completo,
                'correo' => $residente->correo,
                'telefono' => $residente->telefono,
                'direccion' => $residente->direccion,
                'altura' => $residente->altura,
                'numero_lote' => $residente->numero_lote,
                'direccion_completa' => $residente->direccion_completa,
                'numero_unidad' => $residente->numero_unidad,
                'foto_perfil' => $residente->foto_perfil ? Storage::disk('public')->url($residente->foto_perfil) : null,
                'comunidad_id' => $residente->comunidad_id,
                'comunidad' => [
                    'id' => $residente->comunidad->id,
                    'nombre' => $residente->comunidad->nombre,
                    'telefono' => $residente->comunidad->telefono,
                ],
            ],
            'token' => $token,
        ]);
    }

    // Logout de residente o usuario de seguridad
    public function logout(Request $request)
    {
        // Revocar el token actual (funciona para Residente y PersonalSeguridad)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.',
        ]);
    }

    // Obtener perfil del residente autenticado
    public function perfil(Request $request)
    {
        $residente = $request->user();
        $residente->load('comunidad');

        return response()->json([
            'residente' => [
                'id' => $residente->id,
                'nombre' => $residente->nombre,
                'apellido' => $residente->apellido,
                'nombre_completo' => $residente->nombre_completo,
                'correo' => $residente->correo,
                'telefono' => $residente->telefono,
                'direccion' => $residente->direccion,
                'altura' => $residente->altura,
                'numero_lote' => $residente->numero_lote,
                'direccion_completa' => $residente->direccion_completa,
                'numero_unidad' => $residente->numero_unidad,
                'tipo_documento' => $residente->tipo_documento,
                'numero_documento' => $residente->numero_documento,
                'fecha_nacimiento' => $residente->fecha_nacimiento,
                'es_propietario' => $residente->es_propietario,
                'foto_perfil' => $residente->foto_perfil ? Storage::disk('public')->url($residente->foto_perfil) : null,
                'comunidad_id' => $residente->comunidad_id,
                'comunidad' => [
                    'id' => $residente->comunidad->id,
                    'nombre' => $residente->comunidad->nombre,
                    'telefono' => $residente->comunidad->telefono,
                ],
            ],
        ]);
    }

    // Actualizar foto de perfil del residente autenticado
    public function updateFotoPerfil(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $residente = $request->user();

        // Eliminar foto anterior si existe
        if ($residente->foto_perfil && Storage::disk('public')->exists($residente->foto_perfil)) {
            Storage::disk('public')->delete($residente->foto_perfil);
        }

        // Guardar nueva foto
        $imagen = $request->file('foto_perfil');
        $nombreArchivo = 'perfil_' . $residente->id . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = $imagen->storeAs('perfiles', $nombreArchivo, 'public');

        // Actualizar en la base de datos
        $residente->foto_perfil = $ruta;
        $residente->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada exitosamente.',
            'foto_perfil' => Storage::disk('public')->url($ruta),
        ]);
    }

    // Cambiar contraseña del residente autenticado
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password_nueva' => 'required|string|min:6',
            'password_confirmacion' => 'required|string|same:password_nueva',
        ]);

        $residente = $request->user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->password_actual, $residente->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta'
            ], 422);
        }

        // Actualizar la contraseña
        $residente->password = Hash::make($request->password_nueva);
        $residente->save();

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente'
        ]);
    }

    // Login de usuario de seguridad
    public function loginSeguridad(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $seguridad = PersonalSeguridad::where('correo', $request->correo)->first();

        if (!$seguridad || !Hash::check($request->password, $seguridad->password)) {
            throw ValidationException::withMessages([
                'correo' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar que el usuario esté activo
        if (!$seguridad->estado) {
            throw ValidationException::withMessages([
                'correo' => ['Tu cuenta está desactivada. Contacta al administrador.'],
            ]);
        }

        // Verificar que la comunidad esté activa
        $seguridad->load('comunidad');
        if (!$seguridad->comunidad || !$seguridad->comunidad->estado) {
            throw ValidationException::withMessages([
                'correo' => ['La comunidad está suspendida. Contacta al administrador.'],
            ]);
        }

        // Crear token usando Sanctum
        $token = $seguridad->createToken('mobile-app-seguridad')->plainTextToken;

        return response()->json([
            'seguridad' => [
                'id' => $seguridad->id,
                'nombre' => $seguridad->nombre,
                'apellido' => $seguridad->apellido,
                'nombre_completo' => $seguridad->nombre_completo,
                'correo' => $seguridad->correo,
                'telefono' => $seguridad->telefono,
                'comunidad_id' => $seguridad->comunidad_id,
                'comunidad' => [
                    'id' => $seguridad->comunidad->id,
                    'nombre' => $seguridad->comunidad->nombre,
                    'telefono' => $seguridad->comunidad->telefono,
                ],
            ],
            'token' => $token,
        ]);
    }

    // Obtener perfil del usuario de seguridad autenticado
    public function perfilSeguridad(Request $request)
    {
        $seguridad = $request->user();
        $seguridad->load('comunidad');

        return response()->json([
            'seguridad' => [
                'id' => $seguridad->id,
                'nombre' => $seguridad->nombre,
                'apellido' => $seguridad->apellido,
                'nombre_completo' => $seguridad->nombre_completo,
                'correo' => $seguridad->correo,
                'telefono' => $seguridad->telefono,
                'comunidad_id' => $seguridad->comunidad_id,
                'comunidad' => [
                    'id' => $seguridad->comunidad->id,
                    'nombre' => $seguridad->comunidad->nombre,
                    'telefono' => $seguridad->comunidad->telefono,
                ],
            ],
        ]);
    }

    // Cambiar contraseña del usuario de seguridad autenticado
    public function cambiarPasswordSeguridad(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password_nueva' => 'required|string|min:6',
            'password_confirmacion' => 'required|string|same:password_nueva',
        ]);

        $seguridad = $request->user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->password_actual, $seguridad->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta'
            ], 422);
        }

        // Actualizar la contraseña
        $seguridad->password = Hash::make($request->password_nueva);
        $seguridad->save();

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente'
        ]);
    }
}
