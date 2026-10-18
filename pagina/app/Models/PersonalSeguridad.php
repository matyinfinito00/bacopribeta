<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class PersonalSeguridad extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'personal_seguridad';

    protected $fillable = [
        'comunidad_id',
        'nombre',
        'apellido',
        'correo',
        'password',
        'telefono',
        'tipo_documento',
        'numero_documento',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'password' => 'hashed',
        'estado' => 'boolean',
    ];

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'personal_seguridad_id');
    }

    /**
     * Nombre completo del personal de seguridad
     *
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /**
     * Tipos de documento disponibles para formularios
     *
     * @return array
     */
    public static function getTiposDocumentoDisponibles(): array
    {
        return [
            'DNI' => 'DNI',
            'Pasaporte' => 'Pasaporte',
            'Cédula' => 'Cédula',
        ];
    }
}
