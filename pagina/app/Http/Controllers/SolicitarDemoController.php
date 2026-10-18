<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolicitarDemoController extends Controller
{
    public function solicitardemo()
    {
        $testimonios = [
            ['nombre' => 'Matías', 'comunidad' => 'Nordelta', 'mensaje' => '"En menos de dos meses digitalizamos reservas, reportes y notificaciones. La comunidad está encantada con la app."'],
            ['nombre' => 'Julieta', 'comunidad' => 'Haras del Prado', 'mensaje' => '"El soporte de BACOPRI nos acompañó en cada etapa. Hoy tenemos métricas y reportes para tomar decisiones."'],
            ['nombre' => 'Sofía', 'comunidad' => 'Puertos del Lago', 'mensaje' => '"Mejoró radicalmente la comunicación con los residentes. Las incidencias se resuelven en la mitad de tiempo."'],
            ['nombre' => 'Ignacio', 'comunidad' => 'San Isidro Labrador', 'mensaje' => '"La app móvil tuvo una adopción del 90% en el primer mes. Reservas y pagos funcionan sin fricción."'],
            ['nombre' => 'Paula', 'comunidad' => 'La Alameda', 'mensaje' => '"La mesa de seguridad ahora trabaja con información en tiempo real. Las alertas llegan en segundos."'],
            ['nombre' => 'Gustavo', 'comunidad' => 'Ayres del Pilar', 'mensaje' => '"Los reportes automáticos nos ayudan a presentar números claros al consorcio cada mes."'],
            ['nombre' => 'Camila', 'comunidad' => 'Lagoon Pilar', 'mensaje' => '"La plataforma es súper intuitiva. Los residentes gestionan sus reservas sin necesidad de llamar a la administración."'],
            ['nombre' => 'Federico', 'comunidad' => 'El Cantón', 'mensaje' => '"Pudimos integrar el directorio de proveedores y emergencias. Todo el personal tiene la info que necesita."'],
            ['nombre' => 'Mariana', 'comunidad' => 'Santa Catalina', 'mensaje' => '"Las notificaciones segmentadas mejoraron la asistencia a eventos y asambleas generales."'],
            ['nombre' => 'Lucas', 'comunidad' => 'Los Cardales', 'mensaje' => '"BACOPRI nos permitió automatizar recordatorios de expensas y reducir la morosidad notablemente."'],
        ];

        return view('solicitar-demo.index', compact('testimonios'));
    }

    public function enviarSolicitud(Request $request)
    {
        return redirect()->route('demo.solicitud')
            ->with('success', '¡Gracias por comunicarte con nosotros! Nos contactaremos contigo a la brevedad.');
    }
}
