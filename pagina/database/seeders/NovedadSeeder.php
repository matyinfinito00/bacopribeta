<?php

namespace Database\Seeders;

use App\Models\Novedad;
use App\Models\Comunidad;
use App\Models\Administrador;
use Illuminate\Database\Seeder;

class NovedadSeeder extends Seeder
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

        // Novedades de ejemplo (con fechas recientes para que aparezcan en notificaciones)
        Novedad::updateOrCreate(
            ['titulo' => 'Nueva Área de Juegos Infantiles', 'comunidad_id' => $community->id],
            [
                'comunidad_id'    => $community->id,
                'administrador_id' => $admin->id,
                'titulo'          => 'Nueva Área de Juegos Infantiles',
                'contenido'        => 'Nos complace informarles que se ha inaugurado una nueva área de juegos infantiles en el Sector Sur, junto al parque. El espacio cuenta con juegos seguros para niños de todas las edades y está disponible de lunes a domingo de 9:00 a 19:00 horas.',
                'publicado_en'    => now()->subDays(2), // Hace 2 días (dentro de los últimos 7 días)
            ]
        );

        // Segunda novedad más reciente
        Novedad::updateOrCreate(
            ['titulo' => 'Mantenimiento de Piscina', 'comunidad_id' => $community->id],
            [
                'comunidad_id'    => $community->id,
                'administrador_id' => $admin->id,
                'titulo'          => 'Mantenimiento de Piscina',
                'contenido'        => 'Informamos que la piscina principal estará cerrada para mantenimiento el próximo viernes de 8:00 a 14:00 horas. Agradecemos su comprensión.',
                'publicado_en'    => now()->subHours(12), // Hace 12 horas
            ]
        );
    }
}
