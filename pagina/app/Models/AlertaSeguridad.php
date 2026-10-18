<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaSeguridad extends Model
{
    use HasFactory;

    protected $table = 'alertas_seguridad';

    protected $fillable = [
        'comunidad_id',
        'administrador_id',
        'titulo',
        'contenido',
        'prioridad',
        'publicado_en',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'administrador_id' => 'integer',
        'publicado_en' => 'datetime',
    ];
     
    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
    
    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'administrador_id');
    }

    /**
     * Clase CSS de Bootstrap para el badge de prioridad
     *
     * @return string
     */
    public function getPrioridadColorAttribute(): string
    {
        $colores = [
            'baja' => 'bg-secondary',
            'media' => 'bg-info',
            'alta' => 'bg-warning text-dark',
            'urgente' => 'bg-danger',
        ];

        return $colores[$this->prioridad];
    }

    /**
     * Prioridades disponibles para formularios
     *
     * @return array
     */
    public static function getPrioridadesDisponibles(): array
    {
        return [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    }
}
