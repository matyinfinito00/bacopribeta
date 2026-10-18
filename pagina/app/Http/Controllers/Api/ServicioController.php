<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    
    // Listar servicios de la comunidad del residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $servicios = Servicio::where('comunidad_id', $residente->comunidad_id)
            ->where('estado', true)
            ->latest()
            ->get();

        return response()->json([
            'servicios' => $servicios->map(function ($servicio) {
                return [
                    'id' => $servicio->id,
                    'nombre' => $servicio->nombre,
                    'descripcion' => $servicio->descripcion,
                    'categoria' => $servicio->categoria,
                    'categoria_label' => $servicio->categoria_label,
                    'nombre_contacto' => $servicio->nombre_contacto,
                    'telefono' => $servicio->telefono,
                    'correo' => $servicio->correo,
                    'direccion' => $servicio->direccion,
                    'horario' => $servicio->horario_array,
                    'es_emergencia' => $servicio->es_emergencia,
                    'created_at' => $servicio->created_at,
                    'updated_at' => $servicio->updated_at,
                ];
            }),
        ]);
    }

    // Mostrar detalle de un servicio
    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $servicio = Servicio::where('comunidad_id', $residente->comunidad_id)
            ->where('estado', true)
            ->findOrFail($id);

        return response()->json([
            'servicio' => [
                'id' => $servicio->id,
                'nombre' => $servicio->nombre,
                'descripcion' => $servicio->descripcion,
                'categoria' => $servicio->categoria,
                'categoria_label' => $servicio->categoria_label,
                'nombre_contacto' => $servicio->nombre_contacto,
                'telefono' => $servicio->telefono,
                'correo' => $servicio->correo,
                'direccion' => $servicio->direccion,
                'horario' => $servicio->horario_array,
                'es_emergencia' => $servicio->es_emergencia,
                'created_at' => $servicio->created_at,
                'updated_at' => $servicio->updated_at,
            ],
        ]);
    }
}

