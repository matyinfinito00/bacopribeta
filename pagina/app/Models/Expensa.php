<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expensa extends Model
{
    use HasFactory;

    protected $table = 'expensas';

    protected $fillable = [
        'comunidad_id',
        'periodo',
        'vence_en',
        'monto',
        'banco',
        'cbu',
        'alias',
        'notas',
        'publicado',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'monto' => 'decimal:2',
        'vence_en' => 'date',
        'publicado' => 'boolean',
    ];

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(ExpensaPago::class, 'expensa_id');
    }
}
