<?php

namespace App\Http\Controllers;

use App\Models\Reglamento;
use Illuminate\Http\Request;

class ReglamentoController extends Controller
{
    // Mostrar listado de reglamentos del administrador actual.
    public function index()
    {
        $admin = auth('admin')->user();

        $reglamentos = Reglamento::where('comunidad_id', $admin->comunidad_id)
            ->latest('created_at')
            ->paginate(10);

        $hasReglamento = $reglamentos->total() > 0;

        return view('admin.reglamentos.index', compact('reglamentos', 'hasReglamento'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $admin = auth('admin')->user();
        $hasReglamento = Reglamento::where('comunidad_id', $admin->comunidad_id)->exists();

        if ($hasReglamento) {
            return redirect()->route('admin.reglamentos.index')
                ->with('error', 'Solo se permite publicar un reglamento por comunidad.');
        }

        return view('admin.reglamentos.create');
    }

    // Guardar reglamento
    public function store(Request $request)
    {
        $admin = auth('admin')->user();
        $hasReglamento = Reglamento::where('comunidad_id', $admin->comunidad_id)->exists();

        if ($hasReglamento) {
            return redirect()->route('admin.reglamentos.index')
                ->with('error', 'Solo se permite publicar un reglamento por comunidad.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['administrador_id'] = $admin->id;

        Reglamento::create($data);

        return redirect()->route('admin.reglamentos.index')
            ->with('success', 'Reglamento creado exitosamente.');
    }

    // Mostrar detalles
    public function show(Reglamento $reglamento)
    {
        $admin = auth('admin')->user();

        if ($reglamento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.reglamentos.show', compact('reglamento'));
    }

    // Mostrar formulario de edición
    public function edit(Reglamento $reglamento)
    {
        $admin = auth('admin')->user();

        if ($reglamento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.reglamentos.edit', compact('reglamento'));
    }

    // Actualizar reglamento
    public function update(Request $request, Reglamento $reglamento)
    {
        $admin = auth('admin')->user();

        if ($reglamento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $reglamento->update($validated);

        return redirect()->route('admin.reglamentos.index')
            ->with('success', 'Reglamento actualizado exitosamente.');
    }

    // Eliminar
    public function destroy(Reglamento $reglamento)
    {
        $admin = auth('admin')->user();

        if ($reglamento->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $reglamento->delete();

        return redirect()->route('admin.reglamentos.index')
            ->with('success', 'Reglamento eliminado exitosamente.');
    }
}
