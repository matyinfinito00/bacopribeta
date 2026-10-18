<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reglamento extends Model
{
    use HasFactory;

    protected $table = 'reglamentos';

    protected $fillable = [
        'comunidad_id',
        'administrador_id',
        'titulo',
        'contenido',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'administrador_id' => 'integer',
    ];

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'administrador_id');
    }
}
