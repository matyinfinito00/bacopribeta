<?php

namespace Database\Seeders;

use App\Models\Residente;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class ResidenteSeeder extends Seeder
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

        // Residente de ejemplo (nombre y apellido separados, campos nuevos)
        Residente::updateOrCreate(
            ['correo' => 'residente@nordelta.com'],
            [
                'comunidad_id'      => $community->id,
                'nombre'            => 'Carina',
                'apellido'          => 'Carballido',
                'correo'            => 'residente@nordelta.com',
                'password'          => 'password123',
                'telefono'          => '+54 11 4747-0030',
                'direccion'         => 'Calle Los Pinos',
                'altura'            => '456',
                'numero_lote'       => '15',
                'numero_unidad'     => 'Casa 15',
                'tipo_documento'    => 'DNI',
                'numero_documento'  => '12345678',
                'fecha_nacimiento'  => '1985-05-15',
                'estado'            => true,
                'es_propietario'    => true,
                'foto_perfil'       => null,
            ]
        );
    }
}
