<?php

namespace Database\Seeders;

use App\Models\Instalacion;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class InstalacionSeeder extends Seeder
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

        // Cancha de Fútbol
        Instalacion::updateOrCreate(
            ['nombre' => 'Cancha de Fútbol', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Cancha de fútbol 11 con césped sintético y sistema de iluminación',
                'tipo'               => 'deportiva',
                'ubicacion'          => 'Sector Deportivo, junto al gimnasio',
                'lat'                => -34.3970,
                'lng'                => -58.6500,
                'capacidad'          => 22,
                'telefono'           => '+54 11 4747-0010',
                'horario'            => [
                    'Lunes 08:00 a 22:00',
                    'Martes 08:00 a 22:00',
                    'Miércoles 08:00 a 22:00',
                    'Jueves 08:00 a 22:00',
                    'Viernes 08:00 a 22:00',
                    'Sábado 07:00 a 23:00',
                    'Domingo 07:00 a 23:00'
                ],
                'amenidades'         => ['Vestuarios', 'Wifi', 'Estacionamiento', 'Iluminación'],
                'imagenes'           => [],
                'es_reservable'      => true,
                'estado'             => true,
                'reglas'             => [
                    'Prohibido el consumo de bebidas alcohólicas',
                    'Uso de calzado deportivo obligatorio',
                    'Respetar los horarios de reserva'
                ],
            ]
        );

        // Restaurante del Club
        Instalacion::updateOrCreate(
            ['nombre' => 'Restaurante del Club', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Restaurante con terraza al aire libre y vista al lago',
                'tipo'               => 'gastronomia',
                'ubicacion'          => 'Club House, Planta Baja',
                'lat'                => -34.3965,
                'lng'                => -58.6505,
                'capacidad'          => 80,
                'telefono'           => '+54 11 4747-0020',
                'horario'            => [
                    'Lunes 12:00 a 15:00',
                    'Martes 12:00 a 15:00',
                    'Miércoles 12:00 a 15:00',
                    'Jueves 12:00 a 15:00',
                    'Viernes 12:00 a 15:00',
                    'Sábado 12:00 a 16:00',
                    'Domingo 12:00 a 16:00'
                ],
                'amenidades'         => ['Terraza', 'Wifi', 'Estacionamiento', 'Aire acondicionado'],
                'imagenes'           => [],
                'es_reservable'      => true,
                'estado'             => true,
                'reglas'             => [
                    'Reservas con 24 horas de anticipación',
                    'Código de vestimenta casual elegante',
                    'No se permiten mascotas'
                ],
            ]
        );

        // Piscina
        Instalacion::updateOrCreate(
            ['nombre' => 'Piscina Principal', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Piscina olímpica climatizada con área de solarium',
                'tipo'               => 'deportiva',
                'ubicacion'          => 'Sector Recreativo, frente al Club House',
                'lat'                => -34.3960,
                'lng'                => -58.6510,
                'capacidad'          => 50,
                'telefono'           => '+54 11 4747-0030',
                'horario'            => [
                    'Lunes 07:00 a 20:00',
                    'Martes 07:00 a 20:00',
                    'Miércoles 07:00 a 20:00',
                    'Jueves 07:00 a 20:00',
                    'Viernes 07:00 a 20:00',
                    'Sábado 08:00 a 21:00',
                    'Domingo 08:00 a 21:00'
                ],
                'amenidades'         => ['Solarium', 'Vestuarios', 'Duchas', 'Guardavidas'],
                'imagenes'           => [],
                'es_reservable'      => false,
                'estado'             => true,
                'reglas'             => [
                    'Uso de gorros de baño obligatorio',
                    'Prohibido el ingreso de alimentos',
                    'Menores de 12 años deben estar acompañados'
                ],
            ]
        );

        // Gimnasio
        Instalacion::updateOrCreate(
            ['nombre' => 'Gimnasio', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Gimnasio completamente equipado con máquinas de última generación',
                'tipo'               => 'deportiva',
                'ubicacion'          => 'Sector Deportivo, Planta Alta',
                'lat'                => -34.3975,
                'lng'                => -58.6502,
                'capacidad'          => 30,
                'telefono'           => '+54 11 4747-0040',
                'horario'            => [
                    'Lunes 06:00 a 22:00',
                    'Martes 06:00 a 22:00',
                    'Miércoles 06:00 a 22:00',
                    'Jueves 06:00 a 22:00',
                    'Viernes 06:00 a 22:00',
                    'Sábado 08:00 a 20:00',
                    'Domingo 08:00 a 20:00'
                ],
                'amenidades'         => ['Vestuarios', 'Duchas', 'Lockers', 'Aire acondicionado'],
                'imagenes'           => [],
                'es_reservable'      => false,
                'estado'             => true,
                'reglas'             => [
                    'Uso de toalla obligatorio',
                    'Limpiar máquinas después de usar',
                    'Prohibido el uso de calzado de calle'
                ],
            ]
        );

        // Salón de Eventos
        Instalacion::updateOrCreate(
            ['nombre' => 'Salón de Eventos', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Amplio salón para eventos sociales con capacidad para 150 personas',
                'tipo'               => 'eventos',
                'ubicacion'          => 'Club House, Planta Alta',
                'lat'                => -34.3968,
                'lng'                => -58.6508,
                'capacidad'          => 150,
                'telefono'           => '+54 11 4747-0050',
                'horario'            => [
                    'Lunes 10:00 a 22:00',
                    'Martes 10:00 a 22:00',
                    'Miércoles 10:00 a 22:00',
                    'Jueves 10:00 a 22:00',
                    'Viernes 10:00 a 23:00',
                    'Sábado 10:00 a 23:00',
                    'Domingo 10:00 a 22:00'
                ],
                'amenidades'         => ['Sonido', 'Iluminación', 'Cocina', 'Estacionamiento'],
                'imagenes'           => [],
                'es_reservable'      => true,
                'estado'             => true,
                'reglas'             => [
                    'Reserva con mínimo 7 días de anticipación',
                    'Depósito de seguridad requerido',
                    'Prohibido fumar en el interior'
                ],
            ]
        );

        // Área de Juegos Infantiles
        Instalacion::updateOrCreate(
            ['nombre' => 'Área de Juegos Infantiles', 'comunidad_id' => $community->id],
            [
                'comunidad_id'      => $community->id,
                'descripcion'        => 'Parque infantil con juegos seguros para niños de todas las edades',
                'tipo'               => 'recreacion',
                'ubicacion'          => 'Sector Sur, junto al parque',
                'lat'                => -34.3972,
                'lng'                => -58.6512,
                'capacidad'          => 40,
                'telefono'           => '+54 11 4747-0060',
                'horario'            => [
                    'Lunes 09:00 a 19:00',
                    'Martes 09:00 a 19:00',
                    'Miércoles 09:00 a 19:00',
                    'Jueves 09:00 a 19:00',
                    'Viernes 09:00 a 19:00',
                    'Sábado 09:00 a 20:00',
                    'Domingo 09:00 a 20:00'
                ],
                'amenidades'         => ['Juegos seguros', 'Área sombreada', 'Bancos para padres'],
                'imagenes'           => [],
                'es_reservable'      => false,
                'estado'             => true,
                'reglas'             => [
                    'Menores deben estar acompañados por un adulto',
                    'Prohibido el uso de bicicletas dentro del área',
                    'Respetar los horarios de cierre'
                ],
            ]
        );
    }
}
