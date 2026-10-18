<?php

namespace App\Http\Controllers;

use App\Models\Invitado;

class InvitadoController extends Controller
{
    // Lista las invitaciones registradas por residentes de la comunidad
    public function index()
    {
        $admin = auth('admin')->user();

        $invitados = Invitado::with('residente:id,nombre,numero_unidad')
            ->where('comunidad_id', $admin->comunidad_id)
            ->latest('created_at')
            ->paginate(10);

        return view('admin.invitados.index', compact('invitados'));
    }

    // Muestra los detalles de una invitación específica
    public function show(Invitado $invitado)
    {
        $admin = auth('admin')->user();

        // Verificar que el invitado pertenece a la comunidad del admin
        if ($invitado->comunidad_id !== $admin->comunidad_id) {
            abort(403, 'No tienes permisos para ver este invitado.');
        }

        // Cargar relaciones necesarias
        $invitado->load([
            'residente:id,nombre,numero_unidad,correo,telefono',
            'confirmadoPor:id,nombre,correo,telefono',
            'canceladoPor:id,nombre,correo,telefono',
            'salidaRegistradaPor:id,nombre,correo,telefono'
        ]);

        return view('admin.invitados.show', compact('invitado'));
    }
}

