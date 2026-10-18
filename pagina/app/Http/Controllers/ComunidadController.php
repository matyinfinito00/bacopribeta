<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use Illuminate\Http\Request;

class ComunidadController extends Controller
{
    // Listado
    public function index()
    {
        $comunidades = Comunidad::withCount(['administradores', 'residentes', 'instalaciones'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.comunidades.index', compact('comunidades'));
    }

    // Formulario de creación
    public function create()
    {
        return view('admin.comunidades.create');
    }

    // Guardar nueva comunidad
    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'direccion'     => 'required|string|max:255',
            'ciudad'        => 'required|string|max:255',
            'provincia'     => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:20',
            'telefono'      => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'correo'        => 'required|email|unique:comunidades,correo',
        ]);

        $data = [
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'direccion'     => $request->direccion,
            'ciudad'        => $request->ciudad,
            'provincia'     => $request->provincia,
            'codigo_postal' => $request->codigo_postal,
            'telefono'      => $request->telefono,
            'correo'        => $request->correo,
            'estado'        => true, 
        ];

        Comunidad::create($data);

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', 'Comunidad creada exitosamente.');
    }

    // Mostrar detalle
    public function show(Comunidad $comunidad)
    {
        $comunidad->load(['administradores', 'residentes', 'instalaciones']);

        return view('admin.comunidades.show', compact('comunidad'));
    }

    // Formulario de edición
    public function edit(Comunidad $comunidad)
    {
        return view('admin.comunidades.edit', compact('comunidad'));
    }

    // Actualizar comunidad
    public function update(Request $request, Comunidad $comunidad)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'direccion'     => 'required|string|max:255',
            'ciudad'        => 'required|string|max:255',
            'provincia'     => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:20',
            'telefono'      => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'correo'        => 'required|email|unique:comunidades,correo,' . $comunidad->id,
        ]);

        $data = [
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'direccion'     => $request->direccion,
            'ciudad'        => $request->ciudad,
            'provincia'     => $request->provincia,
            'codigo_postal' => $request->codigo_postal,
            'telefono'      => $request->telefono,
            'correo'        => $request->correo,
            'estado'        => $comunidad->estado,
        ];

        $comunidad->update($data);

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', 'Comunidad actualizada exitosamente.');
    }

    // Activar/Desactivar
    public function toggleStatus(Comunidad $comunidad)
    {
        // Proteger comunidad del sistema
        if ($comunidad->nombre === 'BACOPRI Sistema') {
            return redirect()
                ->route('admin.comunidades.index')
                ->with('error', 'No se puede desactivar la comunidad del sistema.');
        }

        $comunidad->update(['estado' => !$comunidad->estado]);

        $estado = $comunidad->estado ? 'activada' : 'desactivada';

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', "Comunidad {$estado} exitosamente.");
    }

    // Eliminar
    public function destroy(Comunidad $comunidad)
    {
        // Proteger comunidad del sistema
        if ($comunidad->nombre === 'BACOPRI Sistema') {
            return redirect()
                ->route('admin.comunidades.index')
                ->with('error', 'No se puede eliminar la comunidad del sistema.');
        }

        if ($comunidad->administradores()->count() > 0) {
            return redirect()
                ->route('admin.comunidades.index')
                ->with('error', 'No se puede eliminar la comunidad porque tiene administradores asociados.');
        }

        $comunidad->delete();

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', 'Comunidad eliminada exitosamente.');
    }
}
