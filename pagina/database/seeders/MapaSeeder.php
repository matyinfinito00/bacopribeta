<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class MapaSeeder extends Seeder
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

        // Configurar el mapa de la comunidad (coordenadas aproximadas de Nordelta, Buenos Aires)
        $community->update([
            'mapa_center_lat' => -34.3965,
            'mapa_center_lng' => -58.6505,
            'mapa_zoom' => 15,
        ]);

        $this->command->info('Mapa de la comunidad configurado correctamente.');
    }
}
