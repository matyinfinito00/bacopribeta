<?php

namespace App\Http\Controllers;

use App\Models\PersonalSeguridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SeguridadController extends Controller
{
    // Lista de personal de seguridad (solo de la comunidad del admin)
    public function index()
    {
        $admin = auth('admin')->user();
        
        $usuariosSeguridad = PersonalSeguridad::where('comunidad_id', $admin->comunidad_id)
            ->latest()
            ->paginate(10);

        return view('admin.seguridad.index', compact('usuariosSeguridad'));
    }

    // Formulario de creación
    public function create()
    {
        $tiposDocumento = PersonalSeguridad::getTiposDocumentoDisponibles();
        return view('admin.seguridad.create', compact('tiposDocumento'));
    }

    // Guardar nuevo personal de seguridad
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:personal_seguridad,correo',
            'password'          => 'required|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:personal_seguridad,telefono',
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
        ]);

        // Validación unique compuesta para documento
        $existeDocumento = PersonalSeguridad::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe personal de seguridad con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;
        $data['password'] = Hash::make($validated['password']);
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['estado'] = true;

        PersonalSeguridad::create($data);

        return redirect()->route('admin.seguridad.index')
            ->with('success', 'Personal de seguridad creado exitosamente.');
    }

    // Mostrar detalle
    public function show(PersonalSeguridad $seguridad)
    {
        $admin = auth('admin')->user();
        
        // Verificar que pertenece a la misma comunidad
        if ($seguridad->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.seguridad.show', compact('seguridad'));
    }

    // Formulario de edición
    public function edit(PersonalSeguridad $seguridad)
    {
        $admin = auth('admin')->user();
        
        // Verificar que pertenece a la misma comunidad
        if ($seguridad->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $tiposDocumento = PersonalSeguridad::getTiposDocumentoDisponibles();
        return view('admin.seguridad.edit', compact('seguridad', 'tiposDocumento'));
    }

    // Actualizar personal de seguridad
    public function update(Request $request, PersonalSeguridad $seguridad)
    {
        $admin = auth('admin')->user();
        
        // Verificar que pertenece a la misma comunidad
        if ($seguridad->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:personal_seguridad,correo,' . $seguridad->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:personal_seguridad,telefono,' . $seguridad->id,
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
        ]);

        // Validación unique compuesta para documento (excluyendo registro actual)
        $existeDocumento = PersonalSeguridad::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->where('id', '!=', $seguridad->id)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe personal de seguridad con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;
        $data['estado'] = $seguridad->estado;
        $data['comunidad_id'] = $seguridad->comunidad_id;

        // Solo actualizar password si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        } else {
            unset($data['password']);
        }

        $seguridad->update($data);

        return redirect()->route('admin.seguridad.index')
            ->with('success', 'Personal de seguridad actualizado exitosamente.');
    }

    // Eliminar personal de seguridad
    public function destroy(PersonalSeguridad $seguridad)
    {
        $admin = auth('admin')->user();
        
        // Verificar que pertenece a la misma comunidad
        if ($seguridad->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $seguridad->delete();

        return redirect()->route('admin.seguridad.index')
            ->with('success', 'Personal de seguridad eliminado exitosamente.');
    }

    // Activar/desactivar
    public function toggleStatus(PersonalSeguridad $seguridad)
    {
        $admin = auth('admin')->user();
        
        // Verificar que pertenece a la misma comunidad
        if ($seguridad->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $seguridad->update(['estado' => !$seguridad->estado]);

        $estado = $seguridad->estado ? 'activado' : 'desactivado';

        return redirect()->route('admin.seguridad.index')
            ->with('success', "Personal de seguridad {$estado} exitosamente.");
    }
}
