<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\Comunidad;
use App\Models\Administrador;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
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

        $admin = Administrador::where('comunidad_id', $community->id)->first();

        if (!$admin) {
            $this->command->warn('No existe un administrador para la comunidad Nordelta. Ejecuta AdminComunidadSeeder primero.');
            return;
        }

        // Evento 1: Torneo de Fútbol
        Evento::updateOrCreate(
            ['titulo' => 'Torneo de Fútbol Anual', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Torneo de Fútbol Anual',
                'descripcion'    => 'Torneo de fútbol para todos los residentes. Inscripciones abiertas hasta el 15 de febrero.',
                'fecha'          => now()->addDays(30)->format('Y-m-d'),
                'hora_inicio'    => '10:00',
                'hora_fin'       => '18:00',
                'lugar'          => 'Cancha de Fútbol',
                'categoria'      => 'Deportivo',
                'publicado_en'   => now(),
            ]
        );

        // Evento 2: Fiesta de Verano
        Evento::updateOrCreate(
            ['titulo' => 'Fiesta de Verano', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Fiesta de Verano',
                'descripcion'    => 'Celebración de verano con música en vivo, comida y bebidas. Entrada libre para residentes.',
                'fecha'          => now()->addDays(45)->format('Y-m-d'),
                'hora_inicio'    => '19:00',
                'hora_fin'       => '01:00',
                'lugar'          => 'Club House - Terraza',
                'categoria'      => 'Social',
                'publicado_en'   => now(),
            ]
        );

        // Evento 3: Clase de Yoga (mañana para que aparezca en notificaciones)
        Evento::updateOrCreate(
            ['titulo' => 'Clase de Yoga al Aire Libre', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Clase de Yoga al Aire Libre',
                'descripcion'    => 'Clase gratuita de yoga para todos los niveles. Traer colchoneta propia.',
                'fecha'          => now()->addDay()->format('Y-m-d'), // Mañana
                'hora_inicio'    => '08:00',
                'hora_fin'       => '09:30',
                'lugar'          => 'Parque Central',
                'categoria'      => 'Bienestar',
                'publicado_en'   => now(),
            ]
        );
        
        // Evento adicional: Evento de hoy (si es antes de las 23:59) o mañana
        Evento::updateOrCreate(
            ['titulo' => 'Reunión de Vecinos - Café Matutino', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Reunión de Vecinos - Café Matutino',
                'descripcion'    => 'Reunión informal para conocernos mejor. Café y facturas incluidos.',
                'fecha'          => now()->addDay()->format('Y-m-d'), // Mañana
                'hora_inicio'    => '10:00',
                'hora_fin'       => '12:00',
                'lugar'          => 'Club House',
                'categoria'      => 'Social',
                'publicado_en'   => now(),
            ]
        );

        // Evento 4: Asamblea de Vecinos
        Evento::updateOrCreate(
            ['titulo' => 'Asamblea General de Vecinos', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Asamblea General de Vecinos',
                'descripcion'    => 'Reunión para tratar temas importantes de la comunidad. Se requiere asistencia de al menos un representante por unidad.',
                'fecha'          => now()->addDays(14)->format('Y-m-d'),
                'hora_inicio'    => '19:00',
                'hora_fin'       => '21:00',
                'lugar'          => 'Salón de Eventos',
                'categoria'      => 'Administrativo',
                'publicado_en'   => now(),
            ]
        );

        $this->command->info('Eventos creados correctamente.');
    }
}
