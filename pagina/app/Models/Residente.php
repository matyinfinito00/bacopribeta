<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Residente extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'residentes';

    protected $fillable = [
        'comunidad_id',
        'nombre',
        'apellido',
        'correo',
        'password',
        'telefono',
        'direccion',
        'altura',
        'numero_lote',
        'numero_unidad',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'estado',
        'es_propietario',
        'foto_perfil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'comunidad_id' => 'integer',
        'password' => 'hashed',
        'fecha_nacimiento' => 'date',
        'es_propietario' => 'boolean',
        'estado' => 'boolean',  
    ];

    /**
     * Nombre completo del residente
     *
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /**
     * Dirección completa formateada
     *
     * @return string
     */
    public function getDireccionCompletaAttribute(): string
    {
        $partes = array_filter([
            $this->direccion,
            $this->altura ? "Nº {$this->altura}" : null,
            $this->numero_lote ? "Lote {$this->numero_lote}" : null,
        ]);

        return implode(', ', $partes) ?: 'Sin dirección';
    }
     
    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    // Invitados creados por el residente
    public function invitados(): HasMany
    {
        return $this->hasMany(Invitado::class, 'residente_id');
    }

    // Incidencias reportadas por el residente
    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'residente_id');
    }

    // Mensajes de comunidad
    public function comunidadMensajes(): HasMany
    {
        return $this->hasMany(ComunidadMensaje::class, 'residente_id');
    }

    // Reservas realizadas por el residente
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'residente_id');
    }

    // Pagos de expensas del residente
    public function expensaPagos(): HasMany
    {
        return $this->hasMany(ExpensaPago::class, 'residente_id');
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