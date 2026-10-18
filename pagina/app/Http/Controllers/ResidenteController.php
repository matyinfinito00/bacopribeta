<?php

namespace App\Http\Controllers;

use App\Models\Residente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResidenteController extends Controller
{
    // Listado de residentes
    public function index()
    {
        $admin = auth('admin')->user(); 

        $residentes = Residente::where('comunidad_id', $admin->comunidad_id)
            ->latest()
            ->paginate(10);

        return view('admin.residentes.index', compact('residentes'));
    }

    // Formulario de creación
    public function create()
    {
        $tiposDocumento = Residente::getTiposDocumentoDisponibles();
        return view('admin.residentes.create', compact('tiposDocumento'));
    }

    // Guardar un residente
    public function store(Request $request)
    {
        $admin = auth('admin')->user(); 
        
        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:residentes,correo',
            'password'          => 'required|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:residentes,telefono',
            'direccion'         => 'required|string|max:255',
            'altura'            => 'nullable|string|max:20',
            'numero_lote'       => 'nullable|string|max:20',
            'numero_unidad'     => 'required|string|max:50',
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
            'fecha_nacimiento'  => 'required|date|before:today|after:1900-01-01',
            'es_propietario'    => 'nullable|boolean', 
        ]);

        // Validación unique compuesta para documento
        $existeDocumento = Residente::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe un residente con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['password'] = Hash::make($validated['password']);
        $data['estado'] = true; 

        // Manejar checkbox propietario
        $data['es_propietario'] = $request->boolean('es_propietario', false);

        Residente::create($data);

        return redirect()->route('admin.residentes.index')
            ->with('success', 'Residente creado exitosamente.');
    }

    // Mostrar residente
    public function show(Residente $residente)
    {
        $admin = auth('admin')->user();

        if ($residente->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        // Cargar reservas recientes (últimas 5)
        $reservas = $residente->reservas()
            ->with('instalacion:id,nombre,tipo')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->limit(5)
            ->get();

        // Cargar incidencias recientes (últimas 5)
        $incidencias = $residente->incidencias()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.residentes.show', compact('residente', 'reservas', 'incidencias'));
    }

    // Editar residente
    public function edit(Residente $residente)
    {
        $admin = auth('admin')->user();

        if ($residente->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $tiposDocumento = Residente::getTiposDocumentoDisponibles();
        return view('admin.residentes.edit', compact('residente', 'tiposDocumento'));
    }

    // Actualizar residente
    public function update(Request $request, Residente $residente)
    {
        $admin = auth('admin')->user();

        if ($residente->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'correo'            => 'required|email|unique:residentes,correo,' . $residente->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'telefono'          => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/|unique:residentes,telefono,' . $residente->id,
            'direccion'         => 'required|string|max:255',
            'altura'            => 'nullable|string|max:20',
            'numero_lote'       => 'nullable|string|max:20',
            'numero_unidad'     => 'required|string|max:50',
            'tipo_documento'    => 'required|string|max:20',
            'numero_documento'  => 'required|string|max:9',
            'fecha_nacimiento'  => 'required|date|before:today|after:1900-01-01',
            'es_propietario'    => 'nullable|boolean',
        ]);

        // Validación unique compuesta para documento (excluyendo registro actual)
        $existeDocumento = Residente::where('tipo_documento', $request->tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->where('id', '!=', $residente->id)
            ->exists();

        if ($existeDocumento) {
            return back()->withErrors(['numero_documento' => 'Ya existe un residente con este tipo y número de documento.'])->withInput();
        }

        $data = $validated;

        // Actualizar password sólo si envían una nueva
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        } else {
            unset($data['password']);
        }

        $data['estado'] = $residente->estado;
        $data['es_propietario'] = $request->boolean('es_propietario', $residente->es_propietario);

        $residente->update($data);

        return redirect()->route('admin.residentes.index')
            ->with('success', 'Residente actualizado exitosamente.');
    }

    // Eliminar
    public function destroy(Residente $residente)
    {
        $admin = auth('admin')->user();

        if ($residente->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $residente->delete();

        return redirect()->route('admin.residentes.index')
            ->with('success', 'Residente eliminado exitosamente.');
    }

    // Activar/Desactivar
    public function toggleStatus(Residente $residente)
    {
        $admin = auth('admin')->user();

        if ($residente->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $residente->update(['estado' => !$residente->estado]);

        $estado = $residente->estado ? 'activado' : 'desactivado';

        return redirect()->route('admin.residentes.index')
            ->with('success', "Residente {$estado} exitosamente.");
    }
}
