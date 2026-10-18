<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'residente_id',
        'instalacion_id',
        'fecha',
        'hora_inicio',
        'duracion_minutos',
        'estado',
    ];

    protected $casts = [
        'residente_id' => 'integer',
        'instalacion_id' => 'integer',
        'fecha' => 'date',
        'duracion_minutos' => 'integer',
    ];
    
    // Accessor para hora_inicio (se guarda como time en BD pero lo devolvemos como string HH:MM)
    public function getHoraInicioAttribute($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('H:i');
        }
        return $value;
    }

    protected $table = 'reservas';
    
    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class, 'residente_id');
    }

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class, 'instalacion_id');
    }
}

