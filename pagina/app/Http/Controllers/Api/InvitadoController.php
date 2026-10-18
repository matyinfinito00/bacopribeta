<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitado;
use Illuminate\Http\Request;

class InvitadoController extends Controller
{
    // Lista las invitaciones creadas por el residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $invitados = Invitado::where('comunidad_id', $residente->comunidad_id)
            ->where('residente_id', $residente->id)
            ->latest('visita_en')
            ->latest('created_at')
            ->get();

        return response()->json([
            'invitados' => $invitados->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'nombre_completo' => $inv->nombre_completo,
                    'numero_documento' => $inv->numero_documento,
                    'visita_en' => $inv->visita_en,
                    'comentario' => $inv->comentario,
                    'ingresa_con_auto' => $inv->ingresa_con_auto,
                    'patente' => $inv->patente,
                    'estado' => $inv->estado,
                ];
            }),
        ]);
    }

    // Crea una invitacion nueva para el residente autenticado
    public function store(Request $request)
    {
        $residente = $request->user();

        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:9',
            'visita_en' => 'required|date',
            'comentario' => 'nullable|string|max:500',
            'ingresa_con_auto' => 'nullable|boolean',
            'patente' => 'nullable|string|max:20|required_if:ingresa_con_auto,true',
        ]);

        $data = $validated;
        $data['comunidad_id'] = $residente->comunidad_id;
        $data['residente_id'] = $residente->id;
        $data['estado'] = 'pendiente';

        $invitado = Invitado::create($data)->fresh();

        return response()->json([
            'invitado' => [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->nombre_completo,
                'numero_documento' => $invitado->numero_documento,
                'visita_en' => $invitado->visita_en,
                'comentario' => $invitado->comentario,
                'ingresa_con_auto' => $invitado->ingresa_con_auto,
                'patente' => $invitado->patente,
                'estado' => $invitado->estado,
            ],
        ], 201);
    }

    // Buscar invitado por datos del QR (para seguridad)
    public function buscarPorQR(Request $request)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
            'nombre' => 'required|string',
            'documento' => 'required|string',
        ]);

        // Buscar por nombre_completo (el QR puede venir con 'nombre' o 'nombre_completo')
        $invitado = Invitado::with('residente')
            ->where('id', $validated['id'])
            ->where('comunidad_id', $admin->comunidad_id)
            ->where(function($query) use ($validated) {
                $query->where('nombre_completo', $validated['nombre'])
                      ->orWhere('nombre_completo', 'LIKE', '%' . $validated['nombre'] . '%');
            })
            ->where('numero_documento', $validated['documento'])
            ->first();

        if (!$invitado) {
            return response()->json([
                'message' => 'Invitado no encontrado o datos incorrectos.'
            ], 404);
        }

        return response()->json([
            'invitado' => [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->nombre_completo,
                'numero_documento' => $invitado->numero_documento,
                'visita_en' => $invitado->visita_en,
                'comentario' => $invitado->comentario,
                'ingresa_con_auto' => $invitado->ingresa_con_auto,
                'patente' => $invitado->patente,
                'estado' => $invitado->estado,
                'ingreso_en' => $invitado->ingreso_en,
                'salida_en' => $invitado->salida_en,
                'residente' => [
                    'nombre' => $invitado->residente->nombre ?? 'N/A',
                    'numero_unidad' => $invitado->residente->numero_unidad ?? 'N/A',
                ],
            ],
        ]);
    }

    // Confirmar ingreso de invitado (para seguridad)
    public function confirmarIngreso(Request $request, Invitado $invitado)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        // Verificar que el invitado pertenece a la misma comunidad
        if ($invitado->comunidad_id !== $admin->comunidad_id) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        $invitado->estado = 'activo';
        $invitado->ingreso_en = now();
        $invitado->confirmado_por_administrador_id = $admin->id;
        $invitado->save();

        return response()->json([
            'message' => 'Ingreso confirmado exitosamente.',
            'invitado' => [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->nombre_completo,
                'numero_documento' => $invitado->numero_documento,
                'estado' => $invitado->estado,
                'ingreso_en' => $invitado->ingreso_en,
            ],
        ]);
    }

    // Cancelar invitado (para seguridad)
    public function cancelar(Request $request, Invitado $invitado)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        // Verificar que el invitado pertenece a la misma comunidad
        if ($invitado->comunidad_id !== $admin->comunidad_id) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        $invitado->estado = 'cancelado';
        $invitado->cancelado_por_administrador_id = $admin->id;
        $invitado->save();

        return response()->json([
            'message' => 'Invitado cancelado exitosamente.',
            'invitado' => [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->nombre_completo,
                'numero_documento' => $invitado->numero_documento,
                'estado' => $invitado->estado,
            ],
        ]);
    }

    // Registrar salida de invitado (para seguridad)
    public function registrarSalida(Request $request, Invitado $invitado)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        // Verificar que el invitado pertenece a la misma comunidad
        if ($invitado->comunidad_id !== $admin->comunidad_id) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        // Verificar que el invitado haya ingresado
        if (!$invitado->ingreso_en) {
            return response()->json([
                'message' => 'El invitado no ha ingresado aún.'
            ], 400);
        }

        $invitado->salida_en = now();
        $invitado->salida_registrada_por_administrador_id = $admin->id;
        $invitado->save();

        return response()->json([
            'message' => 'Salida registrada exitosamente.',
            'invitado' => [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->nombre_completo,
                'numero_documento' => $invitado->numero_documento,
                'salida_en' => $invitado->salida_en,
            ],
        ]);
    }

    // Listar invitados activos (con ingreso confirmado pero sin salida) para seguridad
    public function listarActivos(Request $request)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        // Buscar invitados activos (con ingreso confirmado pero sin salida)
        $invitados = Invitado::with('residente:id,nombre,apellido,numero_unidad')
            ->where('comunidad_id', $admin->comunidad_id)
            ->where('estado', 'activo')
            ->whereNotNull('ingreso_en')
            ->whereNull('salida_en')
            ->latest('ingreso_en')
            ->get();

        return response()->json([
            'invitados' => $invitados->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'nombre_completo' => $inv->nombre_completo,
                    'numero_documento' => $inv->numero_documento,
                    'visita_en' => $inv->visita_en,
                    'comentario' => $inv->comentario,
                    'ingresa_con_auto' => $inv->ingresa_con_auto,
                    'patente' => $inv->patente,
                    'estado' => $inv->estado,
                    'ingreso_en' => $inv->ingreso_en,
                    'salida_en' => $inv->salida_en,
                    'residente' => [
                        'nombre' => $inv->residente->nombre_completo ?? 'N/A',
                        'numero_unidad' => $inv->residente->numero_unidad ?? 'N/A',
                    ],
                ];
            }),
        ]);
    }

    // Listar residentes de la comunidad (para seguridad)
    public function listarResidentes(Request $request)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea un Administrador de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        $query = \App\Models\Residente::where('comunidad_id', $admin->comunidad_id)
            ->where('estado', true) // Solo residentes activos
            ->orderBy('apellido')
            ->orderBy('nombre');

        // Búsqueda por nombre, apellido, correo, número de documento o unidad
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                  ->orWhere('correo', 'LIKE', "%{$buscar}%")
                  ->orWhere('numero_documento', 'LIKE', "%{$buscar}%")
                  ->orWhere('numero_unidad', 'LIKE', "%{$buscar}%");
            });
        }

        $residentes = $query->get();

        return response()->json([
            'residentes' => $residentes->map(function ($res) {
                return [
                    'id' => $res->id,
                    'nombre' => $res->nombre_completo,
                    'correo' => $res->correo,
                    'telefono' => $res->telefono,
                    'direccion' => $res->direccion_completa,
                    'numero_unidad' => $res->numero_unidad,
                    'tipo_documento' => $res->tipo_documento,
                    'numero_documento' => $res->numero_documento,
                    'es_propietario' => $res->es_propietario,
                ];
            }),
        ]);
    }
}

