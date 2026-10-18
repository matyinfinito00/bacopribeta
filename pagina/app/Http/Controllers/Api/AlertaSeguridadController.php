<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertaSeguridad;
use Illuminate\Http\Request;

class AlertaSeguridadController extends Controller
{
    
    // Listar alertas de seguridad de la comunidad del residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $alertas = AlertaSeguridad::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->latest('publicado_en')
            ->get();

        return response()->json([
            'alertas' => $alertas->map(function ($alerta) {
                return [
                    'id' => $alerta->id,
                    'titulo' => $alerta->titulo,
                    'contenido' => $alerta->contenido,
                    'prioridad' => $alerta->prioridad,
                    'publicado_en' => $alerta->publicado_en,
                    'administrador' => [
                        'id' => $alerta->administrador->id,
                        'nombre' => $alerta->administrador->nombre,
                    ],
                ];
            }),
        ]);
    }

    // Mostrar detalle de una alerta de seguridad
    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $alerta = AlertaSeguridad::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->findOrFail($id);

        return response()->json([
            'alerta' => [
                'id' => $alerta->id,
                'titulo' => $alerta->titulo,
                'contenido' => $alerta->contenido,
                'prioridad' => $alerta->prioridad,
                'publicado_en' => $alerta->publicado_en,
                'administrador' => [
                    'id' => $alerta->administrador->id,
                    'nombre' => $alerta->administrador->nombre,
                ],
            ],
        ]);
    }
}

