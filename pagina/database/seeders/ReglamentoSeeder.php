<?php

namespace Database\Seeders;

use App\Models\Reglamento;
use App\Models\Comunidad;
use App\Models\Administrador;
use Illuminate\Database\Seeder;

class ReglamentoSeeder extends Seeder
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

        // Reglamento del Barrio
        Reglamento::updateOrCreate(
            ['titulo' => 'Reglamento del Barrio Nordelta', 'comunidad_id' => $community->id],
            [
                'comunidad_id'   => $community->id,
                'administrador_id' => $admin->id,
                'titulo'         => 'Reglamento del Barrio Nordelta',
                'contenido'      => <<<'EOT'
# REGLAMENTO DEL BARRIO NORDELTA

## CAPÍTULO I - DISPOSICIONES GENERALES

### Artículo 1 - Objetivo
El presente reglamento tiene por objeto establecer las normas de convivencia, uso de espacios comunes y responsabilidades de los residentes de la comunidad Nordelta, con el fin de garantizar una convivencia armónica y el cuidado del patrimonio común.

### Artículo 2 - Ámbito de Aplicación
Este reglamento es de aplicación obligatoria para todos los residentes, propietarios, inquilinos, visitantes y personal de la comunidad.

## CAPÍTULO II - USO DE INSTALACIONES COMUNES

### Artículo 3 - Reservas
1. Todas las instalaciones reservables deben ser reservadas con anticipación a través del sistema de gestión.
2. Las reservas son personales e intransferibles.
3. El incumplimiento de una reserva sin cancelación previa puede resultar en la suspensión temporal del derecho a reservar.

### Artículo 4 - Cancha de Fútbol
- Horario de uso: Lunes a Viernes de 08:00 a 22:00, Sábados y Domingos de 07:00 a 23:00
- Uso de calzado deportivo obligatorio
- Prohibido el consumo de bebidas alcohólicas
- Respetar los horarios de reserva

### Artículo 5 - Piscina
- Horario de uso: Lunes a Viernes de 07:00 a 20:00, Sábados y Domingos de 08:00 a 21:00
- Uso de gorros de baño obligatorio
- Prohibido el ingreso de alimentos
- Menores de 12 años deben estar acompañados por un adulto responsable

### Artículo 6 - Gimnasio
- Horario de uso: Lunes a Viernes de 06:00 a 22:00, Sábados y Domingos de 08:00 a 20:00
- Uso de toalla obligatorio
- Limpiar máquinas después de usar
- Prohibido el uso de calzado de calle

### Artículo 7 - Restaurante del Club
- Reservas con 24 horas de anticipación
- Código de vestimenta casual elegante
- No se permiten mascotas

## CAPÍTULO III - CONVIVENCIA Y RESPETO

### Artículo 8 - Ruidos
1. Se debe mantener un nivel de ruido razonable, especialmente entre las 22:00 y las 08:00 horas.
2. Está prohibido el uso de equipos de sonido a volumen alto en espacios comunes.
3. Las fiestas o eventos deben ser comunicados previamente a la administración.

### Artículo 9 - Mascotas
1. Las mascotas deben estar siempre bajo control de sus dueños.
2. Es obligatorio recoger los desechos de las mascotas.
3. Las mascotas no pueden circular por áreas de piscina y restaurante.
4. Los perros deben estar con correa en espacios comunes.

### Artículo 10 - Estacionamiento
1. Cada unidad tiene asignado su lugar de estacionamiento.
2. Está prohibido estacionar en lugares asignados a otros residentes.
3. Los vehículos de visitantes deben registrarse en seguridad.
4. No se permite el estacionamiento de vehículos comerciales o de gran porte sin autorización.

## CAPÍTULO IV - MANTENIMIENTO Y CONSERVACIÓN

### Artículo 11 - Áreas Verdes
1. Está prohibido dañar árboles, plantas y césped.
2. No se permite la instalación de estructuras permanentes sin autorización.
3. El riego de áreas verdes es responsabilidad del personal de mantenimiento.

### Artículo 12 - Basura y Reciclaje
1. La basura debe ser depositada en los contenedores designados.
2. Se debe respetar el sistema de separación de residuos.
3. Los días de recolección deben ser respetados.
4. No se permite dejar basura fuera de los contenedores.

## CAPÍTULO V - SEGURIDAD

### Artículo 13 - Acceso a la Comunidad
1. Todos los visitantes deben ser registrados en el sistema de gestión.
2. Los residentes son responsables de sus invitados.
3. Se debe portar siempre la identificación de residente cuando se solicite.

### Artículo 14 - Seguridad Personal
1. Se recomienda mantener las puertas de acceso cerradas.
2. Reportar cualquier actividad sospechosa a seguridad.
3. No compartir códigos de acceso con personas no autorizadas.

## CAPÍTULO VI - EXPENSAS Y PAGOS

### Artículo 15 - Pago de Expensas
1. Las expensas deben ser pagadas antes de la fecha de vencimiento.
2. El retraso en el pago puede generar intereses y recargos.
3. Los métodos de pago aceptados son: transferencia bancaria, efectivo y tarjeta de crédito.

### Artículo 16 - Información Bancaria
- Banco: Banco Ejemplo
- CBU: 00000000-00000000000000000000
- Alias: nordelta.expensas

## CAPÍTULO VII - SANCIONES

### Artículo 17 - Infracciones Leves
Las infracciones leves pueden resultar en una advertencia escrita por parte de la administración.

### Artículo 18 - Infracciones Graves
Las infracciones graves pueden resultar en:
- Multa económica
- Suspensión temporal del uso de instalaciones comunes
- Notificación a las autoridades competentes si corresponde

### Artículo 19 - Reincidencia
La reincidencia en cualquier tipo de infracción puede resultar en sanciones más severas, incluyendo la suspensión permanente de ciertos privilegios.

## CAPÍTULO VIII - DISPOSICIONES FINALES

### Artículo 20 - Modificaciones
Este reglamento puede ser modificado por la administración con previa notificación a los residentes.

### Artículo 21 - Consultas
Para consultas sobre este reglamento, contactar a la administración:
- Email: admin@nordelta.com
- Teléfono: +54 11 4747-0001

### Artículo 22 - Vigencia
Este reglamento entra en vigencia a partir de su publicación y es de cumplimiento obligatorio para todos los residentes.

---

*Última actualización: Enero 2026*
EOT
            ]
        );

        $this->command->info('Reglamento creado correctamente.');
    }
}
