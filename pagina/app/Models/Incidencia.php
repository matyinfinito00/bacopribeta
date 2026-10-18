<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    protected $fillable = [
        'residente_id',
        'administrador_id',
        'personal_seguridad_id',
        'comunidad_id',
        'tipo',
        'titulo',
        'descripcion',
        'ubicacion',
        'imagen',
        'estado',
    ];

    protected $casts = [
        'residente_id' => 'integer',
        'administrador_id' => 'integer',
        'personal_seguridad_id' => 'integer',
        'comunidad_id' => 'integer',
    ];

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class, 'residente_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'administrador_id');
    }

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function personalSeguridad(): BelongsTo
    {
        return $this->belongsTo(PersonalSeguridad::class, 'personal_seguridad_id');
    }

    /**
     * Tipos de incidencia disponibles
     *
     * @return array
     */
    public static function getTiposDisponibles(): array
    {
        return [
            'agua' => 'Problemas con el agua',
            'areas_verdes' => 'Áreas verdes',
            'iluminacion' => 'Iluminación',
            'infraestructura' => 'Infraestructura',
            'mascotas_plagas' => 'Mascotas y plagas',
            'servicios_suministros' => 'Servicios/Suministros',
            'emergencias_medicas' => 'Emergencias médicas',
        ];
    }

    /**
     * Texto legible del tipo de incidencia
     *
     * @return string
     */
    public function getTipoLabelAttribute(): string
    {
        $tipos = self::getTiposDisponibles();
        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Estados disponibles
     *
     * @return array
     */
    public static function getEstadosDisponibles(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En proceso',
            'resuelta' => 'Resuelta',
        ];
    }

    /**
     * Texto legible del estado
     *
     * @return string
     */
    public function getEstadoLabelAttribute(): string
    {
        $estados = self::getEstadosDisponibles();
        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Clase CSS de Bootstrap para el badge de estado
     *
     * @return string
     */
    public function getEstadoColorAttribute(): string
    {
        $colores = [
            'pendiente' => 'bg-warning text-dark',
            'en_proceso' => 'bg-info',
            'resuelta' => 'bg-success',
        ];

        return $colores[$this->estado] ?? 'bg-secondary';
    }
}
