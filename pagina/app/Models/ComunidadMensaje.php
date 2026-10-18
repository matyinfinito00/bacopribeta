<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComunidadMensaje extends Model
{
    use HasFactory;

    protected $table = 'comunidad_mensajes';

    protected $fillable = [
        'residente_id',
        'comunidad_id',
        'mensaje',
        'tipo_archivo',
        'archivo',
    ];

    protected $casts = [
        'residente_id' => 'integer',
        'comunidad_id' => 'integer',
    ];

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class, 'residente_id');
    }

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
}
