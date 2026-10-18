<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();

        $eventos = Evento::where('comunidad_id', $admin->comunidad_id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(10);

        return view('admin.eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('admin.eventos.create');
    }

    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after_or_equal:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:100',
        ]);

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['administrador_id'] = $admin->id;
        $data['publicado_en'] = now();
        
        // Asegurar formato HH:MM:SS para las horas
        if (!empty($data['hora_inicio']) && strlen($data['hora_inicio']) === 5) {
            $data['hora_inicio'] .= ':00';
        }
        if (!empty($data['hora_fin']) && strlen($data['hora_fin']) === 5) {
            $data['hora_fin'] .= ':00';
        }

        Evento::create($data);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    public function show(Evento $evento)
    {
        $admin = auth('admin')->user();

        if ($evento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $admin = auth('admin')->user();

        if ($evento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $admin = auth('admin')->user();

        if ($evento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after_or_equal:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:100',
        ]);

        // Asegurar formato HH:MM:SS para las horas
        if (!empty($validated['hora_inicio']) && strlen($validated['hora_inicio']) === 5) {
            $validated['hora_inicio'] .= ':00';
        }
        if (!empty($validated['hora_fin']) && strlen($validated['hora_fin']) === 5) {
            $validated['hora_fin'] .= ':00';
        }

        $evento->update($validated);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento actualizado exitosamente.');
    }

    public function destroy(Evento $evento)
    {
        $admin = auth('admin')->user();

        if ($evento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $evento->delete();

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }
}
