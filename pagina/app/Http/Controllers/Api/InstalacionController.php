<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstalacionController extends Controller
{
    
    // Listar instalaciones de la comunidad del residente autenticado (solo reservables)
    public function index(Request $request)
    {
        $residente = $request->user();

        $instalaciones = Instalacion::where('comunidad_id', $residente->comunidad_id)
            ->where('estado', true)
            ->where('es_reservable', true)
            ->latest()
            ->get();

        return response()->json([
            'mapa' => [
                'center_lat' => $residente->comunidad->mapa_center_lat,
                'center_lng' => $residente->comunidad->mapa_center_lng,
                'zoom' => $residente->comunidad->mapa_zoom,
            ],
            'instalaciones' => $instalaciones->map(function ($instalacion) {
                return [
                    'id' => $instalacion->id,
                    'nombre' => $instalacion->nombre,
                    'descripcion' => $instalacion->descripcion,
                    'tipo' => $instalacion->tipo,
                    'tipo_label' => $instalacion->tipo_label,
                    'ubicacion' => $instalacion->ubicacion,
                    'lat' => $instalacion->lat,
                    'lng' => $instalacion->lng,
                    'capacidad' => $instalacion->capacidad,
                    'telefono' => $instalacion->telefono,
                    'horario' => $instalacion->horario_array,
                    'amenidades' => $instalacion->amenidades_array,
                    'imagenes' => $instalacion->imagenes ? array_map(function($img) {
                        return Storage::disk('public')->url($img);
                    }, $instalacion->imagenes) : [],
                    'reglas' => $instalacion->reglas_array,
                    'es_reservable' => $instalacion->es_reservable,
                    'link_reserva' => $instalacion->link_reserva,
                    'created_at' => $instalacion->created_at,
                    'updated_at' => $instalacion->updated_at,
                ];
            }),
        ]);
    }

    // Listar todas las instalaciones para el mapa (sin filtrar por reservables)
    public function mapa(Request $request)
    {
        $residente = $request->user();

        $instalaciones = Instalacion::where('comunidad_id', $residente->comunidad_id)
            ->where('estado', true)
            ->latest()
            ->get();

        return response()->json([
            'mapa' => [
                'center_lat' => $residente->comunidad->mapa_center_lat,
                'center_lng' => $residente->comunidad->mapa_center_lng,
                'zoom' => $residente->comunidad->mapa_zoom,
            ],
            'instalaciones' => $instalaciones->map(function ($instalacion) {
                return [
                    'id' => $instalacion->id,
                    'nombre' => $instalacion->nombre,
                    'descripcion' => $instalacion->descripcion,
                    'tipo' => $instalacion->tipo,
                    'tipo_label' => $instalacion->tipo_label,
                    'ubicacion' => $instalacion->ubicacion,
                    'lat' => $instalacion->lat,
                    'lng' => $instalacion->lng,
                    'capacidad' => $instalacion->capacidad,
                    'telefono' => $instalacion->telefono,
                    'horario' => $instalacion->horario_array,
                    'amenidades' => $instalacion->amenidades_array,
                    'imagenes' => $instalacion->imagenes ? array_map(function($img) {
                        return Storage::disk('public')->url($img);
                    }, $instalacion->imagenes) : [],
                    'reglas' => $instalacion->reglas_array,
                    'es_reservable' => $instalacion->es_reservable,
                    'link_reserva' => $instalacion->link_reserva,
                    'created_at' => $instalacion->created_at,
                    'updated_at' => $instalacion->updated_at,
                ];
            }),
        ]);
    }

    // Mostrar detalle de una instalación
    public function show(Request $request, $id)
    {
        $residente = $request->user();

        $instalacion = Instalacion::where('comunidad_id', $residente->comunidad_id)
            ->where('estado', true)
            ->findOrFail($id);

        return response()->json([
            'instalacion' => [
                'id' => $instalacion->id,
                'nombre' => $instalacion->nombre,
                'descripcion' => $instalacion->descripcion,
                'tipo' => $instalacion->tipo,
                'tipo_label' => $instalacion->tipo_label,
                'ubicacion' => $instalacion->ubicacion,
                'lat' => $instalacion->lat,
                'lng' => $instalacion->lng,
                'capacidad' => $instalacion->capacidad,
                'telefono' => $instalacion->telefono,
                'horario' => $instalacion->horario_array,
                'amenidades' => $instalacion->amenidades_array,
                'imagenes' => $instalacion->imagenes ? array_map(function($img) {
                    return Storage::disk('public')->url($img);
                }, $instalacion->imagenes) : [],
                'reglas' => $instalacion->reglas_array,
                'es_reservable' => $instalacion->es_reservable,
                'link_reserva' => $instalacion->link_reserva,
                'created_at' => $instalacion->created_at,
                'updated_at' => $instalacion->updated_at,
            ],
        ]);
    }
}

