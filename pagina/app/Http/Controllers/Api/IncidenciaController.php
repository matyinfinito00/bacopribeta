<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class IncidenciaController extends Controller
{
    /**
     * Listar incidencias del residente autenticado
     */
    public function index(Request $request)
    {
        $residente = $request->user();

        $incidencias = Incidencia::where('residente_id', $residente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'incidencias' => $incidencias->map(function ($incidencia) {
                return [
                    'id' => $incidencia->id,
                    'tipo' => $incidencia->tipo,
                    'tipo_label' => $incidencia->tipo_label,
                    'titulo' => $incidencia->titulo,
                    'descripcion' => $incidencia->descripcion,
                    'ubicacion' => $incidencia->ubicacion,
                    'imagen' => $incidencia->imagen ? Storage::disk('public')->url($incidencia->imagen) : null,
                    'estado' => $incidencia->estado,
                    'estado_label' => $incidencia->estado_label,
                    'creado_en' => $incidencia->created_at->toISOString(),
                ];
            }),
        ]);
    }

    /**
     * Crear una nueva incidencia
     */
    public function store(Request $request)
    {
        $residente = $request->user();

        $validated = $request->validate([
            'tipo' => 'required|in:agua,areas_verdes,iluminacion,infraestructura,mascotas_plagas,servicios_suministros,emergencias_medicas',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ubicacion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $data = [
            'residente_id' => $residente->id,
            'comunidad_id' => $residente->comunidad_id,
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'ubicacion' => $validated['ubicacion'] ?? null,
            'estado' => 'pendiente',
        ];

        // Manejar imagen si se proporciona
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $ruta = $imagen->storeAs('incidencias', $nombreArchivo, 'public');
            $data['imagen'] = $ruta;
        }

        $incidencia = Incidencia::create($data);

        return response()->json([
            'incidencia' => [
                'id' => $incidencia->id,
                'tipo' => $incidencia->tipo,
                'tipo_label' => $incidencia->tipo_label,
                'titulo' => $incidencia->titulo,
                'descripcion' => $incidencia->descripcion,
                'ubicacion' => $incidencia->ubicacion,
                'imagen' => $incidencia->imagen ? Storage::disk('public')->url($incidencia->imagen) : null,
                'estado' => $incidencia->estado,
                'estado_label' => $incidencia->estado_label,
                'creado_en' => $incidencia->created_at->toISOString(),
            ],
        ], 201);
    }

    /**
     * Mostrar detalle de una incidencia
     */
    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $incidencia = Incidencia::where('residente_id', $residente->id)
            ->findOrFail($id);

        return response()->json([
            'incidencia' => [
                'id' => $incidencia->id,
                'tipo' => $incidencia->tipo,
                'tipo_label' => $incidencia->tipo_label,
                'titulo' => $incidencia->titulo,
                'descripcion' => $incidencia->descripcion,
                'ubicacion' => $incidencia->ubicacion,
                'imagen' => $incidencia->imagen ? Storage::disk('public')->url($incidencia->imagen) : null,
                'estado' => $incidencia->estado,
                'estado_label' => $incidencia->estado_label,
                'creado_en' => $incidencia->created_at->toISOString(),
                'actualizado_en' => $incidencia->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Crear una nueva incidencia (para seguridad)
     */
    public function storeParaSeguridad(Request $request)
    {
        $admin = $request->user(); // Usuario de seguridad autenticado

        // Verificar que sea personal de seguridad
        if (!($admin instanceof \App\Models\PersonalSeguridad)) {
            return response()->json([
                'message' => 'No tienes permisos para esta acción.'
            ], 403);
        }

        $validated = $request->validate([
            'tipo' => 'required|in:agua,areas_verdes,iluminacion,infraestructura,mascotas_plagas,servicios_suministros,emergencias_medicas',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ubicacion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $data = [
            'residente_id' => null, // Las incidencias de seguridad no tienen residente asociado
            'personal_seguridad_id' => $admin->id, // Guardar el ID del personal de seguridad
            'comunidad_id' => $admin->comunidad_id,
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'ubicacion' => $validated['ubicacion'] ?? null,
            'estado' => 'pendiente',
        ];

        // Manejar imagen si se proporciona
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $ruta = $imagen->storeAs('incidencias', $nombreArchivo, 'public');
            $data['imagen'] = $ruta;
        }

        $incidencia = Incidencia::create($data);

        return response()->json([
            'incidencia' => [
                'id' => $incidencia->id,
                'tipo' => $incidencia->tipo,
                'tipo_label' => $incidencia->tipo_label,
                'titulo' => $incidencia->titulo,
                'descripcion' => $incidencia->descripcion,
                'ubicacion' => $incidencia->ubicacion,
                'imagen' => $incidencia->imagen ? Storage::disk('public')->url($incidencia->imagen) : null,
                'estado' => $incidencia->estado,
                'estado_label' => $incidencia->estado_label,
                'creado_en' => $incidencia->created_at->toISOString(),
            ],
        ], 201);
    }
}
