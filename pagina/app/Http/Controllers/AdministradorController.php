<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    // Lista de administradores
    public function index()
    {
        $administradores = Administrador::with('comunidad')
            ->latest()
            ->paginate(10);

        return view('admin.administradores.index', compact('administradores'));
    }

    // Formulario de creación
    public function create()
    {
        $comunidades = Comunidad::where('estado', true)->get();
        $tiposDocumento = Administrador::getTiposDocumentoDisponibles();

        return view('admin.administradores.create', compact('comunidades', 'tiposDocumento'));
    }

    // Guardar nuevo administrador
    public function store(Request $request)
    {
        $validated = $request->validate([
            'comunidad_id'      => 'required|exists:comunidades,id',
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:administradores,correo',
            'password'          => 'required|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:administradores,telefono',
            'cargo'             => 'required|string|max:255',
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
        ]);

        // Validación unique compuesta para documento
        $existeDocumento = Administrador::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe un administrador con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;
        $data['password'] = Hash::make($validated['password']);
        $data['estado'] = true;

        Administrador::create($data);

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador creado exitosamente.');
    }

    // Mostrar detalle
    public function show(Administrador $administrador)
    {
        $administrador->load('comunidad');

        return view('admin.administradores.show', compact('administrador'));
    }

    // Formulario de edición
    public function edit(Administrador $administrador)
    {
        $comunidades = Comunidad::where('estado', true)->get();
        $tiposDocumento = Administrador::getTiposDocumentoDisponibles();

        return view('admin.administradores.edit', compact('administrador', 'comunidades', 'tiposDocumento'));
    }

    // Actualizar administrador
    public function update(Request $request, Administrador $administrador)
    {
        $validated = $request->validate([
            'comunidad_id'      => 'required|exists:comunidades,id',
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:administradores,correo,' . $administrador->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:administradores,telefono,' . $administrador->id,
            'cargo'             => 'required|string|max:255',
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
        ]);

        // Validación unique compuesta para documento (excluyendo el registro actual)
        $existeDocumento = Administrador::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->where('id', '!=', $administrador->id)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe un administrador con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;
        $data['estado'] = $administrador->estado;

        // Solo actualizar password si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        } else {
            unset($data['password']);
        }

        $administrador->update($data);

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador actualizado exitosamente.');
    }

    // Eliminar administrador
    public function destroy(Administrador $administrador)
    {
        $administrador->delete();

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador eliminado exitosamente.');
    }

    // Activar/desactivar
    public function toggleStatus(Administrador $administrador)
    {
        $administrador->update(['estado' => !$administrador->estado]);

        $estado = $administrador->estado ? 'activado' : 'desactivado';

        return redirect()->route('admin.administradores.index')
            ->with('success', "Administrador {$estado} exitosamente.");
    }
}
