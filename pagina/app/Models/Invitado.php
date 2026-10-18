<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitado extends Model
{
    use HasFactory;

    protected $table = 'invitados';

    protected $fillable = [
        'comunidad_id',
        'residente_id',
        'nombre_completo',
        'numero_documento',
        'visita_en',
        'comentario',
        'ingresa_con_auto',
        'patente',
        'estado',
        'ingreso_en',
        'salida_en',
        'confirmado_por_administrador_id',
        'cancelado_por_administrador_id',
        'salida_registrada_por_administrador_id',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'residente_id' => 'integer',
        'visita_en' => 'datetime',
        'ingresa_con_auto' => 'boolean',
        'ingreso_en' => 'datetime',
        'salida_en' => 'datetime',
        'confirmado_por_administrador_id' => 'integer',
        'cancelado_por_administrador_id' => 'integer',
        'salida_registrada_por_administrador_id' => 'integer',
    ];

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class, 'residente_id');
    }

    public function confirmadoPor(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'confirmado_por_administrador_id');
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'cancelado_por_administrador_id');
    }

    public function salidaRegistradaPor(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'salida_registrada_por_administrador_id');
    }
}

