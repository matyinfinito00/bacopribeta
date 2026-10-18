<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpensaPago extends Model
{
    use HasFactory;

    protected $table = 'expensa_pagos';

    protected $fillable = [
        'expensa_id',
        'residente_id',
        'estado',
        'monto',
        'pagado_en',
        'comprobante_url',
    ];

    protected $casts = [
        'expensa_id' => 'integer',
        'residente_id' => 'integer',
        'monto' => 'decimal:2',
        'pagado_en' => 'datetime',
    ];

    public function expensa(): BelongsTo
    {
        return $this->belongsTo(Expensa::class, 'expensa_id');
    }

    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class, 'residente_id');
    }
}
