<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComunidadMensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComunidadMensajeController extends Controller
{
    /**
     * Listar mensajes de la comunidad
     */
    public function index(Request $request)
    {
        $residente = $request->user();
        
        $query = ComunidadMensaje::where('comunidad_id', $residente->comunidad_id)
            ->with('residente:id,nombre,foto_perfil');

        // Si se proporciona un timestamp, solo obtener mensajes más recientes
        $since = $request->get('since');
        if ($since) {
            try {
                $sinceDate = \Carbon\Carbon::parse($since);
                $query->where('created_at', '>', $sinceDate);
            } catch (\Exception $e) {
                // Si el formato es inválido, ignorar el filtro
            }
        }

        $mensajes = $query->orderBy('created_at', 'desc')->paginate(20);

        $mensajes->getCollection()->transform(function ($mensaje) {
            return [
                'id' => $mensaje->id,
                'residente_id' => $mensaje->residente_id,
                'residente_nombre' => $mensaje->residente->nombre,
                'residente_foto' => $mensaje->residente->foto_perfil ? Storage::disk('public')->url($mensaje->residente->foto_perfil) : null,
                'mensaje' => $mensaje->mensaje,
                'tipo_archivo' => $mensaje->tipo_archivo,
                'archivo' => $mensaje->archivo ? Storage::disk('public')->url($mensaje->archivo) : null,
                'created_at' => $mensaje->created_at->toISOString(),
            ];
        });

        return response()->json([
            'mensajes' => $mensajes,
        ]);
    }

    /**
     * Crear un nuevo mensaje
     */
    public function store(Request $request)
    {
        $residente = $request->user();

        $validated = $request->validate([
            'mensaje' => 'nullable|string|max:5000',
            'archivo' => 'nullable|file|max:10240', // 10MB máximo
        ]);

        // Validar que haya mensaje o archivo
        if (empty($validated['mensaje']) && !$request->hasFile('archivo')) {
            return response()->json([
                'error' => 'Debe enviar un mensaje o un archivo'
            ], 422);
        }

        $tipoArchivo = 'texto';
        $archivoPath = null;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $extension = strtolower($archivo->getClientOriginalExtension());
            
            // Determinar tipo de archivo
            $imagenes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $videos = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
            
            if (in_array($extension, $imagenes)) {
                $tipoArchivo = 'imagen';
            } elseif (in_array($extension, $videos)) {
                $tipoArchivo = 'video';
            } else {
                $tipoArchivo = 'archivo';
            }

            $nombreArchivo = time() . '_' . uniqid() . '.' . $extension;
            $archivoPath = $archivo->storeAs('comunidad', $nombreArchivo, 'public');
        }

        $mensaje = ComunidadMensaje::create([
            'residente_id' => $residente->id,
            'comunidad_id' => $residente->comunidad_id,
            'mensaje' => $validated['mensaje'] ?? null,
            'tipo_archivo' => $tipoArchivo,
            'archivo' => $archivoPath,
        ]);

        $mensaje->load('residente:id,nombre,foto_perfil');

        $mensaje->load('residente:id,nombre,foto_perfil');

        return response()->json([
            'mensaje' => [
                'id' => $mensaje->id,
                'residente_id' => $mensaje->residente_id,
                'residente_nombre' => $mensaje->residente->nombre,
                'residente_foto' => $mensaje->residente->foto_perfil ? Storage::disk('public')->url($mensaje->residente->foto_perfil) : null,
                'mensaje' => $mensaje->mensaje,
                'tipo_archivo' => $mensaje->tipo_archivo,
                'archivo' => $mensaje->archivo ? Storage::disk('public')->url($mensaje->archivo) : null,
                'created_at' => $mensaje->created_at->toISOString(),
            ],
        ], 201);
    }

    /**
     * Actualizar un mensaje
     */
    public function update(Request $request, $id)
    {
        $residente = $request->user();
        
        $mensaje = ComunidadMensaje::where('id', $id)
            ->where('residente_id', $residente->id)
            ->where('comunidad_id', $residente->comunidad_id)
            ->firstOrFail();

        $validated = $request->validate([
            'mensaje' => 'required|string|max:5000',
        ]);

        $mensaje->mensaje = $validated['mensaje'];
        $mensaje->save();

        $mensaje->load('residente:id,nombre,foto_perfil');

        return response()->json([
            'mensaje' => [
                'id' => $mensaje->id,
                'residente_id' => $mensaje->residente_id,
                'residente_nombre' => $mensaje->residente->nombre,
                'residente_foto' => $mensaje->residente->foto_perfil ? Storage::disk('public')->url($mensaje->residente->foto_perfil) : null,
                'mensaje' => $mensaje->mensaje,
                'tipo_archivo' => $mensaje->tipo_archivo,
                'archivo' => $mensaje->archivo ? Storage::disk('public')->url($mensaje->archivo) : null,
                'created_at' => $mensaje->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Eliminar un mensaje
     */
    public function destroy(Request $request, $id)
    {
        $residente = $request->user();
        
        $mensaje = ComunidadMensaje::where('id', $id)
            ->where('residente_id', $residente->id)
            ->where('comunidad_id', $residente->comunidad_id)
            ->firstOrFail();

        // Eliminar archivo si existe
        if ($mensaje->archivo && Storage::disk('public')->exists($mensaje->archivo)) {
            Storage::disk('public')->delete($mensaje->archivo);
        }

        $mensaje->delete();

        return response()->json([
            'message' => 'Mensaje eliminado exitosamente.',
        ]);
    }
}
