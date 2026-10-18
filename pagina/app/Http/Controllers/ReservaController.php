<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Instalacion;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Listado de reservas de la comunidad del administrador
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = Reserva::with(['residente:id,nombre,correo,telefono,numero_unidad', 'instalacion:id,nombre,tipo,ubicacion,capacidad,telefono'])
            ->whereHas('instalacion', function ($q) use ($admin) {
                $q->where('comunidad_id', $admin->comunidad_id);
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc');

        // Filtros opcionales
        $estadoFiltro = $request->get('estado');
        if ($estadoFiltro && in_array($estadoFiltro, ['activa', 'cancelada', 'completada'])) {
            $query->where('estado', $estadoFiltro);
        }

        $tipoFiltro = $request->get('tipo');
        if ($tipoFiltro && in_array($tipoFiltro, ['deportiva', 'gastronomia', 'eventos', 'recreacion', 'otra'])) {
            $query->whereHas('instalacion', function ($q) use ($tipoFiltro) {
                $q->where('tipo', $tipoFiltro);
            });
        }

        $fechaFiltro = $request->get('fecha');
        if ($fechaFiltro) {
            $query->whereDate('fecha', $fechaFiltro);
        }

        $residenteFiltro = $request->get('residente_id');
        if ($residenteFiltro) {
            $query->where('residente_id', $residenteFiltro);
        }

        $reservas = $query->paginate(15);

        // Obtener tipos de instalaciones disponibles para el filtro
        $tiposInstalaciones = Instalacion::getTiposDisponibles();

        return view('admin.reservas.index', compact('reservas', 'tiposInstalaciones'));
    }

    // Mostrar detalle de una reserva
    public function show(Reserva $reserva)
    {
        $admin = auth('admin')->user();

        // Verificar que la reserva pertenezca a la comunidad del admin
        if ($reserva->instalacion->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $reserva->load(['residente', 'instalacion']);

        return view('admin.reservas.show', compact('reserva'));
    }

    // Cancelar una reserva
    public function cancelar(Reserva $reserva)
    {
        $admin = auth('admin')->user();

        // Verificar que la reserva pertenezca a la comunidad del admin
        if ($reserva->instalacion->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        if ($reserva->estado !== 'activa') {
            return redirect()->route('admin.reservas.index')
                ->with('error', 'Solo se pueden cancelar reservas activas.');
        }

        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('admin.reservas.index')
            ->with('success', 'Reserva cancelada exitosamente.');
    }
}

