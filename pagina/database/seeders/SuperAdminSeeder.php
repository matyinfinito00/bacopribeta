<?php

namespace Database\Seeders;

use App\Models\SuperAdministrador;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de super admins (tabla independiente, sin comunidad)
        $superAdmins = [
            [
                'nombre'   => 'Tomás',
                'apellido' => 'Bacopri',
                'correo'   => 'tomas@bacopri.com',
                'password' => 'password123',
                'telefono' => '+54 11 1234-5678',
                'estado'   => true,
            ],
            [
                'nombre'   => 'Matías',
                'apellido' => 'Bacopri',
                'correo'   => 'matias@bacopri.com',
                'password' => 'password123',
                'telefono' => '+54 11 1234-5679',
                'estado'   => true,
            ],
        ];

        // Crear o actualizar
        foreach ($superAdmins as $adminData) {
            SuperAdministrador::updateOrCreate(
                ['correo' => $adminData['correo']],
                $adminData
            );
        }
    }
}
