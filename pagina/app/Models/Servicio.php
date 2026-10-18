<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'comunidad_id',
        'nombre',
        'descripcion',
        'categoria',
        'nombre_contacto',
        'telefono',
        'correo',
        'direccion',
        'horario',
        'es_emergencia',
        'estado',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'horario' => 'array',
        'es_emergencia' => 'boolean',
        'estado' => 'boolean',
    ];
    
    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    /**
     * Texto legible de la categoría del servicio
     *
     * @return string
     */
    public function getCategoriaLabelAttribute(): string
    {
        $labels = [
            'emergencia' => 'Emergencias',
            'mantenimiento' => 'Mantenimiento',
            'seguridad' => 'Seguridad',
            'limpieza' => 'Limpieza',
            'jardineria' => 'Jardinería',
            'otro' => 'Otros',
        ];

        return $labels[$this->categoria];
    }

    /**
     * Categorías de servicios disponibles para formularios
     *
     * @return array
     */
    public static function getCategoriasDisponibles(): array
    {
        return [
            'emergencia' => 'Emergencias',
            'mantenimiento' => 'Mantenimiento',
            'seguridad' => 'Seguridad',
            'limpieza' => 'Limpieza',
            'jardineria' => 'Jardinería',
            'otro' => 'Otros',
        ];
    }

    /**
     * Horario como array
     *
     * @return array
     */
    public function getHorarioArrayAttribute(): array
    {
        return array_filter((array) $this->horario);
    }

    /**
     * Días de la semana disponibles para formularios de horarios
     *
     * @return array
     */
    public static function getDiasSemana(): array
    {
        return [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo',
        ];
    }
}