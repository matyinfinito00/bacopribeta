<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class ComunidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comunidad para super admins
        Comunidad::firstOrCreate(
            ['nombre' => 'BACOPRI Sistema'],
            [
                'descripcion'   => 'Comunidad especial para super administradores del sistema',
                'direccion'     => 'Sistema BACOPRI',
                'ciudad'        => 'Buenos Aires',
                'provincia'     => 'Buenos Aires',
                'codigo_postal' => '0000',
                'telefono'      => '+54 11 0000-0000',
                'correo'        => 'sistema@bacopri.com',
                'estado'        => true,
            ]
        );

        // Comunidad de ejemplo
        Comunidad::firstOrCreate(
            ['nombre' => 'Nordelta'],
            [
                'descripcion'   => 'Exclusivo barrio privado ubicado en el partido de Tigre, Buenos Aires.',
                'direccion'     => 'Av. de los Lagos 1234',
                'ciudad'        => 'Tigre',
                'provincia'     => 'Buenos Aires',
                'codigo_postal' => '1670',
                'telefono'      => '+54 11 4747-0000',
                'correo'        => 'info@nordelta.com',
                'estado'        => true,
            ]
        );
    }
}
