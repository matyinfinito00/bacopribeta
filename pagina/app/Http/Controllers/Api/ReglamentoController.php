<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reglamento;
use Illuminate\Http\Request;

class ReglamentoController extends Controller
{
    // Obtener el reglamento más reciente de la comunidad del residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $reglamento = Reglamento::where('comunidad_id', $residente->comunidad_id)
            ->with('administrador:id,nombre')
            ->latest('created_at')
            ->first();

        if (!$reglamento) {
            return response()->json([
                'reglamento' => null,
                'message' => 'No hay reglamento disponible para tu comunidad.',
            ], 404);
        }

        return response()->json([
            'reglamento' => [
                'id' => $reglamento->id,
                'titulo' => $reglamento->titulo,
                'contenido' => $reglamento->contenido,
                'created_at' => $reglamento->created_at,
                'updated_at' => $reglamento->updated_at,
                'administrador' => [
                    'id' => $reglamento->administrador->id,
                    'nombre' => $reglamento->administrador->nombre,
                ],
            ],
        ]);
    }
}
