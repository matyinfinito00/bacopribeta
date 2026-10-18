<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\Comunidad;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    // Listado de instalaciones con coordenadas
    public function index()
    {
        $admin = auth('admin')->user();
        
        $instalaciones = Instalacion::where('comunidad_id', $admin->comunidad_id)
            ->orderBy('nombre')
            ->get();

        return view('admin.mapa.index', compact('instalaciones'));
    }

    // Formulario para agregar coordenadas a una instalación
    public function create()
    {
        $admin = auth('admin')->user();
        
        $instalaciones = Instalacion::where('comunidad_id', $admin->comunidad_id)
            ->whereNull('lat')
            ->whereNull('lng')
            ->orderBy('nombre')
            ->get();

        if ($instalaciones->isEmpty()) {
            return redirect()->route('admin.mapa.index')
                ->with('info', 'Todas las instalaciones ya tienen coordenadas asignadas.');
        }

        $comunidad = $admin->comunidad;
        $mapaComunidadConfig = [
            'centerLat' => $comunidad->mapa_center_lat,
            'centerLng' => $comunidad->mapa_center_lng,
            'zoom' => $comunidad->mapa_zoom,
        ];

        return view('admin.mapa.create', compact('instalaciones', 'comunidad', 'mapaComunidadConfig'));
    }

    // Guardar coordenadas de una instalación
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $instalacion = Instalacion::where('comunidad_id', $admin->comunidad_id)
            ->findOrFail($validated['instalacion_id']);

        $instalacion->update([
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
        ]);

        return redirect()
            ->route('admin.mapa.index')
            ->with('success', 'Coordenadas guardadas exitosamente.');
    }

    // Ver detalles de coordenadas de una instalación
    public function show(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        if (!$instalacion->lat || !$instalacion->lng) {
            return redirect()->route('admin.mapa.index')
                ->with('error', 'Esta instalación no tiene coordenadas asignadas.');
        }

        $mapaInstalacionConfig = [
            'lat' => $instalacion->lat,
            'lng' => $instalacion->lng,
            'nombre' => $instalacion->nombre,
        ];

        return view('admin.mapa.show', compact('instalacion', 'mapaInstalacionConfig'));
    }

    // Formulario para editar coordenadas
    public function edit(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        if (!$instalacion->lat || !$instalacion->lng) {
            return redirect()->route('admin.mapa.index')
                ->with('error', 'Esta instalación no tiene coordenadas asignadas.');
        }

        $comunidad = $admin->comunidad;
        $mapaComunidadConfig = [
            'centerLat' => $comunidad->mapa_center_lat,
            'centerLng' => $comunidad->mapa_center_lng,
            'zoom' => $comunidad->mapa_zoom,
        ];
        $mapaInstalacionConfig = [
            'lat' => $instalacion->lat,
            'lng' => $instalacion->lng,
            'nombre' => $instalacion->nombre,
        ];

        return view('admin.mapa.edit', compact('instalacion', 'comunidad', 'mapaComunidadConfig', 'mapaInstalacionConfig'));
    }

    // Actualizar coordenadas
    public function update(Request $request, Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $instalacion->update([
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
        ]);

        return redirect()
            ->route('admin.mapa.index')
            ->with('success', 'Coordenadas actualizadas exitosamente.');
    }

    // Eliminar coordenadas
    public function destroy(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $instalacion->update([
            'lat' => null,
            'lng' => null,
        ]);

        return redirect()
            ->route('admin.mapa.index')
            ->with('success', 'Coordenadas eliminadas exitosamente.');
    }

    // Configurar mapa del barrio (centro y zoom)
    public function configurarBarrio()
    {
        $admin = auth('admin')->user();
        $comunidad = $admin->comunidad;
        $mapaComunidadConfig = [
            'centerLat' => $comunidad->mapa_center_lat,
            'centerLng' => $comunidad->mapa_center_lng,
            'zoom' => $comunidad->mapa_zoom,
        ];

        return view('admin.mapa.configurar-barrio', compact('comunidad', 'mapaComunidadConfig'));
    }

    // Guardar configuración del mapa del barrio
    public function guardarConfiguracionBarrio(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'mapa_center_lat' => 'required|numeric|between:-90,90',
            'mapa_center_lng' => 'required|numeric|between:-180,180',
            'mapa_zoom' => 'required|integer|min:1|max:20',
        ]);

        $comunidad = $admin->comunidad;
        $comunidad->update([
            'mapa_center_lat' => $validated['mapa_center_lat'],
            'mapa_center_lng' => $validated['mapa_center_lng'],
            'mapa_zoom' => $validated['mapa_zoom'],
        ]);

        return redirect()
            ->route('admin.mapa.configurar-barrio')
            ->with('success', 'Configuración del mapa guardada exitosamente.');
    }
}
