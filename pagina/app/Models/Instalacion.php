<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instalacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunidad_id',
        'nombre',
        'descripcion',
        'tipo',
        'ubicacion',
        'lat',
        'lng',
        'capacidad',
        'telefono',
        'horario',
        'amenidades',
        'imagenes',
        'es_reservable',
        'link_reserva',
        'estado',
        'reglas',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'amenidades' => 'array',
        'imagenes' => 'array',
        'reglas' => 'array',
        'horario' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'es_reservable' => 'boolean',
        'estado' => 'boolean',
    ];

    protected $table = 'instalaciones';
    
    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'instalacion_id');
    }

    /**
     * Obtiene el horario como array
     *
     * @return array
     */
    public function getHorarioArrayAttribute(): array
    {
        return array_filter((array) $this->horario);
    }

    /**
     * Resumen de los horarios para mostrar en listados
     *
     * @return string
     */
    public function getHorarioResumenAttribute(): string
    {
        $horarios = $this->horario_array;
        
        if (empty($horarios)) {
            return 'Sin horarios';
        }
        
        $primeros = array_slice($horarios, 0, 2);
        $resumen = implode(', ', $primeros);
        
        if (count($horarios) > 2) {
            $resumen .= '...';
        }
        
        return $resumen;
    }

    /**
     * Clase del icono FontAwesome según el tipo de instalación
     *
     * @return string
     */
    public function getTipoIconoAttribute(): string
    {
        if (!$this->tipo) {
            return 'fa-building';
        }

        $iconos = [
            'deportiva' => 'fa-futbol',
            'gastronomia' => 'fa-utensils',
            'eventos' => 'fa-calendar-alt',
            'recreacion' => 'fa-child',
            'otra' => 'fa-building',
        ];

        return $iconos[$this->tipo] ?? 'fa-building';
    }

    /**
     * Texto legible del tipo de instalación
     *
     * @return string
     */
    public function getTipoLabelAttribute(): string
    {
        if (!$this->tipo) {
            return 'Sin tipo';
        }

        $labels = [
            'deportiva' => 'Deportiva',
            'gastronomia' => 'Gastronomía',
            'eventos' => 'Eventos',
            'recreacion' => 'Recreación',
            'otra' => 'Otra',
        ];

        return $labels[$this->tipo] ?? 'Sin tipo';
    }

    /**
     * Tipos de instalación disponibles para formularios
     *
     * @return array
     */
    public static function getTiposDisponibles(): array
    {
        return [
            'deportiva' => 'Deportiva',
            'gastronomia' => 'Gastronomía',
            'eventos' => 'Eventos',
            'recreacion' => 'Recreación',
            'otra' => 'Otra',
        ];
    }

    /**
     * Reglas como array
     *
     * @return array
     */
    public function getReglasArrayAttribute(): array
    {
        return array_filter((array) $this->reglas);
    }

    /**
     * Amenidades como array
     *
     * @return array
     */
    public function getAmenidadesArrayAttribute(): array
    {
        return array_filter((array) $this->amenidades);
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
