<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $residente = $request->user();

        $eventos = Evento::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        return response()->json([
            'eventos' => $eventos->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'titulo' => $evento->titulo,
                    'descripcion' => $evento->descripcion,
                    'fecha' => optional($evento->fecha)->format('Y-m-d'),
                    'hora_inicio' => $evento->hora_inicio,
                    'hora_fin' => $evento->hora_fin,
                    'lugar' => $evento->lugar,
                    'categoria' => $evento->categoria,
                    'administrador' => $evento->administrador ? [
                        'id' => $evento->administrador->id,
                        'nombre' => $evento->administrador->nombre,
                    ] : null,
                ];
            }),
        ]);
    }

    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $evento = Evento::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->findOrFail($id);

        return response()->json([
            'evento' => [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'descripcion' => $evento->descripcion,
                'fecha' => optional($evento->fecha)->format('Y-m-d'),
                'hora_inicio' => $evento->hora_inicio,
                'hora_fin' => $evento->hora_fin,
                'lugar' => $evento->lugar,
                'categoria' => $evento->categoria,
                'administrador' => $evento->administrador ? [
                    'id' => $evento->administrador->id,
                    'nombre' => $evento->administrador->nombre,
                ] : null,
            ],
        ]);
    }
}
