<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadController extends Controller
{

    // Mostrar listado de novedades del administrador actual.
    public function index()
    {
        $admin = auth('admin')->user();

        $novedades = Novedad::where('comunidad_id', $admin->comunidad_id)
            ->latest('publicado_en')
            ->paginate(10);

        return view('admin.novedades.index', compact('novedades'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('admin.novedades.create');
    }

    // Guardar novedad
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['administrador_id'] = $admin->id;
        $data['publicado_en'] = now();

        // Manejar imagen si se proporciona
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $ruta = $imagen->storeAs('novedades', $nombreArchivo, 'public');
            $data['imagen'] = $ruta;
        }

        Novedad::create($data);

        return redirect()->route('admin.novedades.index')
            ->with('success', 'Novedad publicada exitosamente.');
    }

    // Mostrar detalles
    public function show(Novedad $novedad)
    {
        $admin = auth('admin')->user();

        if ($novedad->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.novedades.show', compact('novedad'));
    }

    // Mostrar formulario de edición
    public function edit(Novedad $novedad)
    {
        $admin = auth('admin')->user();

        if ($novedad->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.novedades.edit', compact('novedad'));
    }

    // Actualizar novedad
    public function update(Request $request, Novedad $novedad)
    {
        $admin = auth('admin')->user();

        if ($novedad->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        // Manejar imagen si se proporciona una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($novedad->imagen && Storage::disk('public')->exists($novedad->imagen)) {
                Storage::disk('public')->delete($novedad->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $ruta = $imagen->storeAs('novedades', $nombreArchivo, 'public');
            $validated['imagen'] = $ruta;
        }

        $novedad->update($validated);

        return redirect()->route('admin.novedades.index')
            ->with('success', 'Novedad actualizada exitosamente.');
    }

    // Eliminar
    public function destroy(Novedad $novedad)
    {
        $admin = auth('admin')->user();

        if ($novedad->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        // Eliminar imagen si existe
        if ($novedad->imagen && Storage::disk('public')->exists($novedad->imagen)) {
            Storage::disk('public')->delete($novedad->imagen);
        }

        $novedad->delete();

        return redirect()->route('admin.novedades.index')
            ->with('success', 'Novedad eliminada exitosamente.');
    }
}
