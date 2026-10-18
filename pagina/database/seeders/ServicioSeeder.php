<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $community = Comunidad::where('nombre', 'Nordelta')->first();

        if (!$community) {
            $this->command->warn('La comunidad Nordelta no existe. Ejecuta ComunidadSeeder primero.');
            return;
        }

        // Servicio de ejemplo
        Servicio::updateOrCreate(
            ['nombre' => 'Mantenimiento y Reparaciones', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'descripcion'    => 'Servicio de mantenimiento general y reparaciones para unidades y áreas comunes',
                'categoria'      => 'mantenimiento',
                'nombre_contacto' => 'Juan Pérez',
                'telefono'        => '+54 11 4747-0020',
                'correo'          => 'mantenimiento@nordelta.com',
                'direccion'       => 'Oficina de Mantenimiento, Sector Administrativo',
                'horario'         => [
                    'Lunes a Viernes: 8:00 - 18:00',
                    'Emergencias 24/7'
                ],
                'es_emergencia'   => true,
                'estado'          => true,
            ]
        );
    }
}
