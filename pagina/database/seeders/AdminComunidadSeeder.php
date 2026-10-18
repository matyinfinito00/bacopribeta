<?php

namespace Database\Seeders;

use App\Models\Administrador;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class AdminComunidadSeeder extends Seeder
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

        // Administrador de comunidad (ya no tiene es_super_admin, nombre y apellido separados)
        Administrador::updateOrCreate(
            ['correo' => 'admin@nordelta.com'],
            [
                'comunidad_id'     => $community->id,
                'nombre'           => 'Carlos',
                'apellido'         => 'Rodríguez',
                'correo'           => 'admin@nordelta.com',
                'password'         => 'password123',
                'telefono'         => '+54 11 4747-0001',
                'tipo_documento'   => 'DNI',
                'numero_documento' => '11223344',
                'estado'           => true,
            ]
        );
    }
}
