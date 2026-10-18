<?php

namespace Database\Seeders;

use App\Models\PersonalSeguridad;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class PersonalSeguridadSeeder extends Seeder
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

        // Personal de seguridad de ejemplo
        $personal = [
            [
                'comunidad_id'     => $community->id,
                'nombre'           => 'Roberto',
                'apellido'         => 'Gómez',
                'correo'           => 'seguridad1@nordelta.com',
                'password'         => 'password123',
                'telefono'         => '+54 11 4747-0050',
                'tipo_documento'   => 'DNI',
                'numero_documento' => '33445566',
                'estado'           => true,
            ],
            [
                'comunidad_id'     => $community->id,
                'nombre'           => 'Laura',
                'apellido'         => 'Martínez',
                'correo'           => 'seguridad2@nordelta.com',
                'password'         => 'password123',
                'telefono'         => '+54 11 4747-0051',
                'tipo_documento'   => 'DNI',
                'numero_documento' => '44556677',
                'estado'           => true,
            ],
        ];

        foreach ($personal as $data) {
            PersonalSeguridad::updateOrCreate(
                ['correo' => $data['correo']],
                $data
            );
        }
    }
}
