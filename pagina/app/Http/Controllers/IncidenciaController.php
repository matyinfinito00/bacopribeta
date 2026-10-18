<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class IncidenciaController extends Controller
{
    /**
     * Listado de incidencias de la comunidad del administrador
     */
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = Incidencia::with([
                'residente:id,nombre,correo,telefono,numero_unidad',
                'administrador:id,nombre,correo,telefono'
            ])
            ->where('comunidad_id', $admin->comunidad_id)
            ->orderBy('created_at', 'desc');

        // Filtros opcionales
        $estadoFiltro = $request->get('estado');
        if ($estadoFiltro && in_array($estadoFiltro, ['pendiente', 'en_proceso', 'resuelta'])) {
            $query->where('estado', $estadoFiltro);
        }

        $tipoFiltro = $request->get('tipo');
        if ($tipoFiltro && in_array($tipoFiltro, [
            'agua', 'areas_verdes', 'iluminacion', 'infraestructura',
            'mascotas_plagas', 'servicios_suministros', 'emergencias_medicas'
        ])) {
            $query->where('tipo', $tipoFiltro);
        }

        $residenteFiltro = $request->get('residente_id');
        if ($residenteFiltro) {
            $query->where('residente_id', $residenteFiltro);
        }

        $administradorFiltro = $request->get('administrador_id');
        if ($administradorFiltro) {
            $query->where('administrador_id', $administradorFiltro);
        }

        $incidencias = $query->paginate(15);

        return view('admin.incidencias.index', compact('incidencias'));
    }

    /**
     * Mostrar detalle de una incidencia
     */
    public function show(Incidencia $incidencia)
    {
        $admin = auth('admin')->user();

        // Verificar que la incidencia pertenezca a la comunidad del admin
        if ($incidencia->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $incidencia->load(['residente', 'administrador']);

        return view('admin.incidencias.show', compact('incidencia'));
    }

    /**
     * Actualizar estado de una incidencia
     */
    public function updateEstado(Request $request, Incidencia $incidencia)
    {
        $admin = auth('admin')->user();

        // Verificar que la incidencia pertenezca a la comunidad del admin
        if ($incidencia->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,resuelta',
        ]);

        $incidencia->update(['estado' => $request->estado]);

        return redirect()
            ->route('admin.incidencias.show', $incidencia)
            ->with('success', 'Estado de la incidencia actualizado exitosamente.');
    }
}
