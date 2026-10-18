<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    
    // Listar reservas del residente autenticado
    public function index(Request $request)
    {
        $residente = $request->user();

        $reservas = $residente->reservas()
            ->with('instalacion:id,nombre,tipo')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get();

        return response()->json([
            'reservas' => $reservas->map(function ($reserva) {
                return [
                    'id' => $reserva->id,
                    'instalacion_id' => $reserva->instalacion_id,
                    'instalacion' => $reserva->instalacion ? [
                        'id' => $reserva->instalacion->id,
                        'nombre' => $reserva->instalacion->nombre,
                        'tipo' => $reserva->instalacion->tipo,
                    ] : null,
                    'fecha' => $reserva->fecha->format('Y-m-d'),
                    'hora_inicio' => $reserva->hora_inicio,
                    'duracion_minutos' => $reserva->duracion_minutos,
                    'estado' => $reserva->estado,
                    'creado_en' => $reserva->created_at->toISOString(),
                ];
            }),
        ]);
    }

    // Crear una nueva reserva
    public function store(Request $request)
    {
        $residente = $request->user();

        $validated = $request->validate([
            'instalacion_id' => 'required|exists:instalaciones,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion_minutos' => 'required|integer|min:30|max:480', // Entre 30 min y 8 horas
        ]);

        // Verificar que la instalación existe y pertenece a la misma comunidad
        $instalacion = Instalacion::findOrFail($validated['instalacion_id']);
        
        if ($instalacion->comunidad_id !== $residente->comunidad_id) {
            throw ValidationException::withMessages([
                'instalacion_id' => ['La instalación no pertenece a tu comunidad.'],
            ]);
        }

        if (!$instalacion->es_reservable) {
            throw ValidationException::withMessages([
                'instalacion_id' => ['Esta instalación no es reservable.'],
            ]);
        }

        if (!$instalacion->estado) {
            throw ValidationException::withMessages([
                'instalacion_id' => ['Esta instalación no está disponible.'],
            ]);
        }

        // Verificar que el usuario no tenga ya una reserva activa en esta instalación
        $reservaActivaExistente = $residente->reservas()
            ->where('instalacion_id', $instalacion->id)
            ->where('estado', 'activa')
            ->exists();

        if ($reservaActivaExistente) {
            throw ValidationException::withMessages([
                'instalacion_id' => ['Ya tienes una reserva activa para esta instalación. Solo puedes tener una reserva activa por instalación a la vez.'],
            ]);
        }

        // Verificar que no haya conflictos con otras reservas
        $fecha = $validated['fecha'];
        $horaInicio = $validated['hora_inicio'];
        $duracion = $validated['duracion_minutos'];

        // Convertir hora_inicio a minutos desde medianoche
        [$hora, $minuto] = explode(':', $horaInicio);
        $inicioMinutos = (int)$hora * 60 + (int)$minuto;
        $finMinutos = $inicioMinutos + $duracion;

        // Buscar reservas que se solapen
        $conflictos = $instalacion->reservas()
            ->where('fecha', $fecha)
            ->where('estado', 'activa')
            ->get()
            ->filter(function ($reserva) use ($inicioMinutos, $finMinutos) {
                [$rHora, $rMinuto] = explode(':', $reserva->hora_inicio);
                $rInicioMinutos = (int)$rHora * 60 + (int)$rMinuto;
                $rFinMinutos = $rInicioMinutos + $reserva->duracion_minutos;

                // Verificar solapamiento
                return max($rInicioMinutos, $inicioMinutos) < min($rFinMinutos, $finMinutos);
            });

        if ($conflictos->count() > 0) {
            throw ValidationException::withMessages([
                'hora_inicio' => ['Este horario ya está reservado. Por favor selecciona otro.'],
            ]);
        }

        // Verificar que el horario esté dentro del horario de la instalación
        $horarios = $instalacion->horario_array;
        
        // Si no hay horarios configurados, permitir cualquier horario
        if (!empty($horarios)) {
            // Usar Carbon para calcular el día de la semana de forma más confiable
            $fechaObj = \Carbon\Carbon::parse($fecha);
            $diaSemana = $fechaObj->dayOfWeek; // 0 = domingo, 1 = lunes, etc.
            
            // Mapa de días con todas las variantes posibles (en minúsculas para comparación)
            $diasMap = [
                'domingo' => 0, 'dom' => 0, 'sunday' => 0,
                'lunes' => 1, 'lun' => 1, 'monday' => 1,
                'martes' => 2, 'mar' => 2, 'tuesday' => 2,
                'miercoles' => 3, 'miércoles' => 3, 'mie' => 3, 'wednesday' => 3,
                'jueves' => 4, 'jue' => 4, 'thursday' => 4,
                'viernes' => 5, 'vie' => 5, 'friday' => 5,
                'sabado' => 6, 'sábado' => 6, 'sab' => 6, 'saturday' => 6,
            ];
            
            $horarioValido = false;
            
            foreach ($horarios as $horario) {
                if (!is_string($horario) || empty($horario)) {
                    continue;
                }
                
                // Regex que captura el formato exacto: "Lunes 09:00 a 18:00"
                // Acepta mayúsculas/minúsculas y diferentes separadores (a o -)
                if (preg_match('/^(domingo|dom|lunes|lun|martes|mar|miercoles|mi[eé]rcoles|mie|jueves|jue|viernes|vie|sabado|s[aá]bado|sab|sunday|monday|tuesday|wednesday|thursday|friday|saturday)\s+(\d{1,2}):(\d{2})\s+(?:a|-)\s+(\d{1,2}):(\d{2})$/iu', trim($horario), $matches)) {
                    $diaNombre = mb_strtolower(trim($matches[1]));
                    // Limpiar caracteres especiales del nombre del día
                    $diaNombre = preg_replace('/[^a-záéíóúñ]/iu', '', $diaNombre);
                    $diaHorario = $diasMap[$diaNombre] ?? null;
                    
                    if ($diaHorario !== null && $diaHorario === $diaSemana) {
                        // Normalizar formato de horas (asegurar HH:MM)
                        $horaDesde = str_pad($matches[2], 2, '0', STR_PAD_LEFT) . ':' . str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                        $horaHasta = str_pad($matches[4], 2, '0', STR_PAD_LEFT) . ':' . str_pad($matches[5], 2, '0', STR_PAD_LEFT);
                        
                        [$hDesde, $mDesde] = explode(':', $horaDesde);
                        [$hHasta, $mHasta] = explode(':', $horaHasta);
                        $desdeMinutos = (int)$hDesde * 60 + (int)$mDesde;
                        $hastaMinutos = (int)$hHasta * 60 + (int)$mHasta;
                        
                        // Verificar que el horario de la reserva esté dentro del rango
                        // Permitir que el inicio sea igual al desde y el fin sea igual al hasta
                        if ($inicioMinutos >= $desdeMinutos && $finMinutos <= $hastaMinutos) {
                            $horarioValido = true;
                            break;
                        }
                    }
                }
            }
            
            if (!$horarioValido) {
                // Log para depuración
                \Log::warning('Validación de horario falló', [
                    'fecha' => $fecha,
                    'dia_semana' => $diaSemana,
                    'hora_inicio' => $horaInicio,
                    'duracion' => $duracion,
                    'inicio_minutos' => $inicioMinutos,
                    'fin_minutos' => $finMinutos,
                    'horarios_configurados' => $horarios,
                ]);
                
                throw ValidationException::withMessages([
                    'hora_inicio' => ['El horario seleccionado no está dentro del horario de atención de la instalación para este día.'],
                ]);
            }
        }

        // Crear la reserva
        $reserva = Reserva::create([
            'residente_id' => $residente->id,
            'instalacion_id' => $validated['instalacion_id'],
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'duracion_minutos' => $duracion,
            'estado' => 'activa',
        ]);

        return response()->json([
            'reserva' => [
                'id' => $reserva->id,
                'instalacion_id' => $reserva->instalacion_id,
                'fecha' => $reserva->fecha->format('Y-m-d'),
                'hora_inicio' => $reserva->hora_inicio,
                'duracion_minutos' => $reserva->duracion_minutos,
                'estado' => $reserva->estado,
                'creado_en' => $reserva->created_at->toISOString(),
            ],
        ], 201);
    }

    // Cancelar una reserva
    public function cancelar(Request $request, $id)
    {
        $residente = $request->user();

        $reserva = $residente->reservas()
            ->where('id', $id)
            ->firstOrFail();

        if ($reserva->estado !== 'activa') {
            throw ValidationException::withMessages([
                'reserva' => ['Solo se pueden cancelar reservas activas.'],
            ]);
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json([
            'message' => 'Reserva cancelada exitosamente.',
            'reserva' => [
                'id' => $reserva->id,
                'instalacion_id' => $reserva->instalacion_id,
                'fecha' => $reserva->fecha->format('Y-m-d'),
                'hora_inicio' => $reserva->hora_inicio,
                'duracion_minutos' => $reserva->duracion_minutos,
                'estado' => $reserva->estado,
            ],
        ]);
    }
}

