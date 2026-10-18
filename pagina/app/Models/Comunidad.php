<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comunidad extends Model
{
    use HasFactory;

    protected $table = 'comunidades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'ciudad',
        'provincia',
        'codigo_postal',
        'telefono',
        'correo',
        'estado',
        'mapa_center_lat',
        'mapa_center_lng',
        'mapa_zoom',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'mapa_center_lat' => 'float',
        'mapa_center_lng' => 'float',
        'mapa_zoom' => 'integer',
    ];

    public function administradores(): HasMany
    {
        return $this->hasMany(Administrador::class, 'comunidad_id');
    }
    
    public function personalSeguridad(): HasMany
    {
        return $this->hasMany(PersonalSeguridad::class, 'comunidad_id');
    }

    public function residentes(): HasMany
    {
        return $this->hasMany(Residente::class, 'comunidad_id');
    }

    public function instalaciones(): HasMany
    {
        return $this->hasMany(Instalacion::class, 'comunidad_id');
    }

    public function novedades(): HasMany
    {
        return $this->hasMany(Novedad::class, 'comunidad_id');
    }

    public function alertasSeguridad(): HasMany
    {
        return $this->hasMany(AlertaSeguridad::class, 'comunidad_id');
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'comunidad_id');
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'comunidad_id');
    }

    public function comunidadMensajes(): HasMany
    {
        return $this->hasMany(ComunidadMensaje::class, 'comunidad_id');
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'comunidad_id');
    }

    public function reglamentos(): HasMany
    {
        return $this->hasMany(Reglamento::class, 'comunidad_id');
    }

    public function expensas(): HasMany
    {
        return $this->hasMany(Expensa::class, 'comunidad_id');
    }
}
