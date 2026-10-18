<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'administradores';

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

    /**
     * Nombre completo del administrador
     *
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function novedades(): HasMany
    {
        return $this->hasMany(Novedad::class, 'administrador_id');
    }

    public function alertasSeguridad(): HasMany
    {
        return $this->hasMany(AlertaSeguridad::class, 'administrador_id');
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'administrador_id');
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
