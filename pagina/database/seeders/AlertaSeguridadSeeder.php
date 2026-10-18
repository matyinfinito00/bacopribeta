<?php

namespace Database\Seeders;

use App\Models\AlertaSeguridad;
use App\Models\Comunidad;
use App\Models\Administrador;
use Illuminate\Database\Seeder;

class AlertaSeguridadSeeder extends Seeder
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

        $admin = Administrador::where('correo', 'admin@nordelta.com')->first();

        if (!$admin) {
            $this->command->warn('El administrador admin@nordelta.com no existe. Ejecuta AdminComunidadSeeder primero.');
            return;
        }

        // Alertas de seguridad de ejemplo (con fechas recientes)
        AlertaSeguridad::updateOrCreate(
            ['titulo' => 'Mantenimiento de Cámaras de Seguridad', 'comunidad_id' => $community->id],
            [
                'comunidad_id'    => $community->id,
                'administrador_id' => $admin->id,
                'titulo'          => 'Mantenimiento de Cámaras de Seguridad',
                'contenido'       => 'El próximo martes se realizará mantenimiento preventivo del sistema de cámaras de seguridad. Durante este período, algunas cámaras estarán temporalmente fuera de servicio. El servicio de seguridad personal continuará operando con normalidad.',
                'prioridad'       => 'media',
                'publicado_en'    => now()->subDays(1), // Hace 1 día
            ]
        );

        // Segunda alerta más reciente
        AlertaSeguridad::updateOrCreate(
            ['titulo' => 'Alerta de Seguridad - Zona de Obras', 'comunidad_id' => $community->id],
            [
                'comunidad_id'    => $community->id,
                'administrador_id' => $admin->id,
                'titulo'          => 'Alerta de Seguridad - Zona de Obras',
                'contenido'       => 'Se informa que hay obras en el Sector Norte. Por favor, circulen con precaución y eviten la zona durante las horas de trabajo (8:00 a 18:00).',
                'prioridad'       => 'alta',
                'publicado_en'    => now()->subHours(6), // Hace 6 horas
            ]
        );
    }
}
