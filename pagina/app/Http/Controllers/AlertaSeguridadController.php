<?php

namespace App\Http\Controllers;

use App\Models\AlertaSeguridad;
use Illuminate\Http\Request;

class AlertaSeguridadController extends Controller
{
    
    // Mostrar listado de alertas de seguridad del administrador actual.
    public function index()
    {
        $admin = auth('admin')->user();

        $alertas = AlertaSeguridad::where('comunidad_id', $admin->comunidad_id)
            ->latest('publicado_en')
            ->paginate(10);

        return view('admin.alertas-seguridad.index', compact('alertas'));
    }

    
    // Mostrar formulario para crear una nueva alerta.
    public function create()
    {
        $prioridades = AlertaSeguridad::getPrioridadesDisponibles();
        return view('admin.alertas-seguridad.create', compact('prioridades'));
    }

    // Guardar una nueva alerta de seguridad
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,urgente',
        ]);

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['administrador_id'] = $admin->id;
        $data['publicado_en'] = now();

        AlertaSeguridad::create($data);

        return redirect()->route('admin.alertas-seguridad.index')
            ->with('success', 'Alerta de seguridad publicada exitosamente.');
    }

    // Mostrar detalle de una alerta
    public function show(AlertaSeguridad $alerta)
    {
        $admin = auth('admin')->user();

        if ($alerta->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.alertas-seguridad.show', compact('alerta'));
    }

    // Mostrar formulario de edición de una alerta
    public function edit(AlertaSeguridad $alerta)
    {
        $admin = auth('admin')->user();

        if ($alerta->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $prioridades = AlertaSeguridad::getPrioridadesDisponibles();
        return view('admin.alertas-seguridad.edit', compact('alerta', 'prioridades'));
    }

    // Actualizar una alerta que ya existe
    public function update(Request $request, AlertaSeguridad $alerta)
    {
        $admin = auth('admin')->user();

        if ($alerta->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,urgente',
        ]);

        $alerta->update($validated);

        return redirect()->route('admin.alertas-seguridad.index')
            ->with('success', 'Alerta actualizada exitosamente.');
    }

    // Eliminar una alerta
    public function destroy(AlertaSeguridad $alerta)
    {
        $admin = auth('admin')->user();

        if ($alerta->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $alerta->delete();

        return redirect()->route('admin.alertas-seguridad.index')
            ->with('success', 'Alerta eliminada exitosamente.');
    }
}
