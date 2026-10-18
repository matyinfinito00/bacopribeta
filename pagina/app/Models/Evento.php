<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'comunidad_id',
        'administrador_id',
        'titulo',
        'descripcion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'categoria',
        'publicado_en',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'administrador_id' => 'integer',
        'fecha' => 'date',
        'publicado_en' => 'datetime',
    ];

    /**
     * Accessor para hora_inicio: asegurar formato HH:MM:SS
     */
    public function getHoraInicioAttribute($value)
    {
        if (!$value) return null;
        // Si viene sin segundos (HH:MM), agregar :00
        if (strlen($value) === 5) {
            return $value . ':00';
        }
        return $value;
    }

    /**
     * Accessor para hora_fin: asegurar formato HH:MM:SS
     */
    public function getHoraFinAttribute($value)
    {
        if (!$value) return null;
        // Si viene sin segundos (HH:MM), agregar :00
        if (strlen($value) === 5) {
            return $value . ':00';
        }
        return $value;
    }

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'administrador_id');
    }
}
