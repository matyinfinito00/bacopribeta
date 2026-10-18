<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadController extends Controller
{
    
    // Listar novedades de la comunidad del residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $novedades = Novedad::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->latest('publicado_en')
            ->get();

        return response()->json([
            'novedades' => $novedades->map(function ($novedad) {
                return [
                    'id' => $novedad->id,
                    'titulo' => $novedad->titulo,
                    'contenido' => $novedad->contenido,
                    'imagen' => $novedad->imagen ? Storage::disk('public')->url($novedad->imagen) : null,
                    'publicado_en' => $novedad->publicado_en,
                    'administrador' => [
                        'id' => $novedad->administrador->id,
                        'nombre' => $novedad->administrador->nombre,
                    ],
                ];
            }),
        ]);
    }

    // Mostrar detalle de una novedad
    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $novedad = Novedad::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->findOrFail($id);

        return response()->json([
            'novedad' => [
                'id' => $novedad->id,
                'titulo' => $novedad->titulo,
                'contenido' => $novedad->contenido,
                'imagen' => $novedad->imagen ? Storage::disk('public')->url($novedad->imagen) : null,
                'publicado_en' => $novedad->publicado_en,
                'administrador' => [
                    'id' => $novedad->administrador->id,
                    'nombre' => $novedad->administrador->nombre,
                ],
            ],
        ]);
    }
}

