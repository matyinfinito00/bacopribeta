<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Administrador;
use App\Models\Residente;
use App\Models\Novedad;
use App\Models\AlertaSeguridad;
use App\Models\Evento;
use App\Models\Incidencia;
use App\Models\Instalacion;

class PanelAdministradorController extends Controller
{
    // Dashboard para super administradores
    public function superAdminDashboard()
    {
        $totalComunidades = Comunidad::count();
        $comunidadesActivas = Comunidad::where('estado', true)->count();
        $totalAdmins = Administrador::count();
        $adminsActivos = Administrador::where('estado', true)->count();
        $totalResidentes = Residente::count();
        $totalInstalaciones = Instalacion::count();

        // Métricas globales
        $estadisticas = [
            'total_comunidades'       => $totalComunidades,
            'comunidades_activas'     => $comunidadesActivas,
            'comunidades_inactivas'   => $totalComunidades - $comunidadesActivas,
            'total_administradores'   => $totalAdmins,
            'administradores_activos' => $adminsActivos,
            'total_residentes'        => $totalResidentes,
            'total_instalaciones'     => $totalInstalaciones,
        ];

        // Listados recientes
        $comunidadesRecientes = Comunidad::orderBy('created_at', 'desc')->take(6)->get();

        $administradoresRecientes = Administrador::with('comunidad')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('admin.super-admin-dashboard', compact(
            'estadisticas',
            'comunidadesRecientes',
            'administradoresRecientes'
        ));
    }

    // Dashboard para administradores de una comunidad
    public function communityDashboard()
    {
        $admin = auth('admin')->user();
        $comunidad = $admin->comunidad;

        $totalResidentes = $comunidad->residentes()->count();
        $residentesActivos = $comunidad->residentes()->where('estado', true)->count();
        $totalInstalaciones = $comunidad->instalaciones()->count();
        $instalacionesActivas = $comunidad->instalaciones()->where('estado', true)->count();

        $totalIncidencias = Incidencia::where('comunidad_id', $comunidad->id)->count();
        $incidenciasPendientes = Incidencia::where('comunidad_id', $comunidad->id)
            ->whereIn('estado', ['pendiente', 'en_proceso'])
            ->count();

        $totalAlertas = AlertaSeguridad::where('comunidad_id', $comunidad->id)->count();
        $totalEventos = Evento::where('comunidad_id', $comunidad->id)->count();

        // Métricas de la comunidad
        $estadisticas = [
            'total_residentes'       => $totalResidentes,
            'residentes_activos'     => $residentesActivos,
            'total_instalaciones'    => $totalInstalaciones,
            'instalaciones_activas'  => $instalacionesActivas,
            'total_incidencias'      => $totalIncidencias,
            'incidencias_pendientes' => $incidenciasPendientes,
            'total_alertas'          => $totalAlertas,
            'total_eventos'          => $totalEventos,
        ];

        // Novedades recientes
        $novedadesRecientes = Novedad::where('comunidad_id', $comunidad->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Alertas de seguridad recientes
        $alertasRecientes = AlertaSeguridad::where('comunidad_id', $comunidad->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Eventos recientes / próximos
        $eventosRecientes = Evento::where('comunidad_id', $comunidad->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'asc')
            ->take(5)
            ->get();

        // Incidencias recientes
        $incidenciasRecientes = Incidencia::where('comunidad_id', $comunidad->id)
            ->with('residente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.admin-comunidad-dashboard', compact(
            'estadisticas',
            'novedadesRecientes',
            'alertasRecientes',
            'eventosRecientes',
            'incidenciasRecientes',
            'comunidad',
            'admin'
        ));
    }
}
