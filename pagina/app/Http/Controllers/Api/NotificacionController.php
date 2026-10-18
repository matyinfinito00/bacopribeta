<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expensa;
use App\Models\Novedad;
use App\Models\AlertaSeguridad;
use App\Models\Evento;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Obtener conteos de notificaciones para el residente autenticado
     */
    public function contadores(Request $request)
    {
        $residente = $request->user();

        // Expensas pendientes (con pagos pendientes o sin pagos creados)
        // Solo mostrar expensas nuevas (últimos 30 días) o que aún no hayan vencido
        $expensasPendientes = $residente->comunidad->expensas()
            ->where('publicado', true)
            ->where(function ($q) {
                // Expensas nuevas (creadas en los últimos 30 días) o que aún no vencen
                $q->where('created_at', '>=', now()->subDays(30))
                  ->orWhere('vence_en', '>=', now()->toDateString());
            })
            ->where(function ($q) use ($residente) {
                $q->whereHas('pagos', function ($subQ) use ($residente) {
                    $subQ->where('residente_id', $residente->id)
                          ->where('estado', 'pendiente');
                })
                ->orWhereDoesntHave('pagos', function ($subQ) use ($residente) {
                    $subQ->where('residente_id', $residente->id);
                });
            })
            ->count();

        // Novedades nuevas (publicadas en los últimos 7 días)
        $novedadesNuevas = Novedad::where('comunidad_id', $residente->comunidad_id)
            ->where(function($query) {
                $query->where('publicado_en', '>=', now()->subDays(7))
                      ->orWhereNull('publicado_en')
                      ->where('created_at', '>=', now()->subDays(7));
            })
            ->count();

        // Alertas nuevas (publicadas en los últimos 7 días)
        $alertasNuevas = AlertaSeguridad::where('comunidad_id', $residente->comunidad_id)
            ->where(function($query) {
                $query->where('publicado_en', '>=', now()->subDays(7))
                      ->orWhereNull('publicado_en')
                      ->where('created_at', '>=', now()->subDays(7));
            })
            ->count();

        // Eventos próximos (fecha >= hoy)
        $eventosProximos = Evento::where('comunidad_id', $residente->comunidad_id)
            ->whereDate('fecha', '>=', now()->toDateString())
            ->count();

        $total = $expensasPendientes + $novedadesNuevas + $alertasNuevas + $eventosProximos;

        return response()->json([
            'contadores' => [
                'expensas' => $expensasPendientes,
                'novedades' => $novedadesNuevas,
                'alertas' => $alertasNuevas,
                'eventos' => $eventosProximos,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Obtener notificaciones con detalles para el residente autenticado
     */
    public function index(Request $request)
    {
        $residente = $request->user();
        $notificaciones = [];

        // Expensas pendientes
        // Solo mostrar expensas nuevas (últimos 30 días) o que aún no hayan vencido
        $expensasPendientes = $residente->comunidad->expensas()
            ->where('publicado', true)
            ->where(function ($q) {
                // Expensas nuevas (creadas en los últimos 30 días) o que aún no vencen
                $q->where('created_at', '>=', now()->subDays(30))
                  ->orWhere('vence_en', '>=', now()->toDateString());
            })
            ->where(function ($q) use ($residente) {
                $q->whereHas('pagos', function ($subQ) use ($residente) {
                    $subQ->where('residente_id', $residente->id)
                          ->where('estado', 'pendiente');
                })
                ->orWhereDoesntHave('pagos', function ($subQ) use ($residente) {
                    $subQ->where('residente_id', $residente->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($expensa) use ($residente) {
                $pago = $expensa->pagos()->where('residente_id', $residente->id)->first();
                // Usar created_at para ordenar por cuándo se creó la expensa
                $fechaOrdenamiento = $expensa->created_at->timestamp;
                return [
                    'tipo' => 'expensa',
                    'id' => $expensa->id,
                    'titulo' => "Expensa pendiente: {$expensa->periodo}",
                    'descripcion' => "Monto: $" . number_format($pago ? $pago->monto : $expensa->monto, 2, ',', '.'),
                    'fecha' => $expensa->created_at->toISOString(),
                    'fecha_ordenamiento' => $fechaOrdenamiento,
                    'vence_en' => $expensa->vence_en ? $expensa->vence_en->toDateString() : null,
                ];
            });

        $notificaciones = array_merge($notificaciones, $expensasPendientes->toArray());

        // Novedades nuevas (últimos 7 días)
        $novedadesNuevas = Novedad::where('comunidad_id', $residente->comunidad_id)
            ->where(function($query) {
                $query->where('publicado_en', '>=', now()->subDays(7))
                      ->orWhereNull('publicado_en')
                      ->where('created_at', '>=', now()->subDays(7));
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($novedad) {
                // Usar created_at para ordenar por cuándo se creó la novedad
                $fechaOrdenamiento = $novedad->created_at->timestamp;
                $fechaMostrar = $novedad->publicado_en ?? $novedad->created_at;
                return [
                    'tipo' => 'novedad',
                    'id' => $novedad->id,
                    'titulo' => $novedad->titulo,
                    'descripcion' => substr($novedad->contenido, 0, 100) . (strlen($novedad->contenido) > 100 ? '...' : ''),
                    'fecha' => $fechaMostrar->toISOString(),
                    'fecha_ordenamiento' => $fechaOrdenamiento,
                ];
            });

        $notificaciones = array_merge($notificaciones, $novedadesNuevas->toArray());

        // Alertas nuevas (últimos 7 días)
        $alertasNuevas = AlertaSeguridad::where('comunidad_id', $residente->comunidad_id)
            ->where(function($query) {
                $query->where('publicado_en', '>=', now()->subDays(7))
                      ->orWhereNull('publicado_en')
                      ->where('created_at', '>=', now()->subDays(7));
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($alerta) {
                // Usar created_at para ordenar por cuándo se creó la alerta
                $fechaOrdenamiento = $alerta->created_at->timestamp;
                $fechaMostrar = $alerta->publicado_en ?? $alerta->created_at;
                return [
                    'tipo' => 'alerta',
                    'id' => $alerta->id,
                    'titulo' => $alerta->titulo,
                    'descripcion' => substr($alerta->contenido, 0, 100) . (strlen($alerta->contenido) > 100 ? '...' : ''),
                    'fecha' => $fechaMostrar->toISOString(),
                    'fecha_ordenamiento' => $fechaOrdenamiento,
                    'prioridad' => $alerta->prioridad,
                ];
            });

        $notificaciones = array_merge($notificaciones, $alertasNuevas->toArray());

        // Eventos próximos (fecha >= hoy)
        $eventosProximos = Evento::where('comunidad_id', $residente->comunidad_id)
            ->whereDate('fecha', '>=', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($evento) {
                // Usar created_at para ordenar por cuándo se creó el evento
                $fechaOrdenamiento = $evento->created_at->timestamp;
                $fechaEvento = $evento->fecha ? $evento->fecha : now();
                $fechaCompleta = $evento->hora_inicio 
                    ? $fechaEvento->format('Y-m-d') . 'T' . $evento->hora_inicio
                    : $fechaEvento->format('Y-m-d') . 'T00:00:00';
                return [
                    'tipo' => 'evento',
                    'id' => $evento->id,
                    'titulo' => $evento->titulo,
                    'descripcion' => ($evento->lugar ? "Lugar: {$evento->lugar}. " : '') . ($evento->descripcion ? substr($evento->descripcion, 0, 80) . (strlen($evento->descripcion) > 80 ? '...' : '') : ''),
                    'fecha' => $fechaCompleta,
                    'fecha_ordenamiento' => $fechaOrdenamiento,
                    'hora_inicio' => $evento->hora_inicio,
                ];
            });

        $notificaciones = array_merge($notificaciones, $eventosProximos->toArray());

        // Ordenar por fecha de creación (más recientes primero)
        // Usar fecha_ordenamiento si existe (timestamp de created_at), sino usar fecha
        usort($notificaciones, function ($a, $b) {
            $timestampA = $a['fecha_ordenamiento'] ?? null;
            $timestampB = $b['fecha_ordenamiento'] ?? null;
            
            // Si no hay fecha_ordenamiento, intentar parsear la fecha
            if ($timestampA === null) {
                $fechaA = $a['fecha'] ?? '';
                $timestampA = $fechaA ? strtotime($fechaA) : 0;
            }
            
            if ($timestampB === null) {
                $fechaB = $b['fecha'] ?? '';
                $timestampB = $fechaB ? strtotime($fechaB) : 0;
            }
            
            // Ordenar descendente (más reciente primero)
            return $timestampB - $timestampA;
        });

        return response()->json([
            'notificaciones' => $notificaciones,
        ]);
    }
}
