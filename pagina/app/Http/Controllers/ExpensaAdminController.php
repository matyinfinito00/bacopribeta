<?php

namespace App\Http\Controllers;

use App\Models\Expensa;
use App\Models\ExpensaPago;
use App\Models\Residente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensaAdminController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        $comunidad = $admin->comunidad;

        $expensas = $comunidad->expensas()
            ->withCount([
                'pagos as pagados_count' => function ($q) {
                    $q->where('estado', 'pagado');
                },
            ])
            ->withCount('pagos')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.expensas.index', compact('expensas', 'comunidad'));
    }

    public function create()
    {
        $admin = auth('admin')->user();
        $comunidad = $admin->comunidad;
        return view('admin.expensas.create', compact('comunidad'));
    }

    public function store(Request $request)
    {
        $admin = auth('admin')->user();
        $comunidad = $admin->comunidad;

        $validated = $request->validate([
            'periodo' => ['required', 'string', 'max:255'],
            'vence_en' => ['required', 'date', 'after_or_equal:today'],
            'monto' => ['required', 'numeric', 'min:0'],
            'banco' => ['required', 'string', 'max:255'],
            'cbu' => ['required', 'string', 'max:255'],
            'alias' => ['required', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $comunidad) {
            $expensa = Expensa::create([
                'comunidad_id' => $comunidad->id,
                'periodo' => $validated['periodo'],
                'vence_en' => $validated['vence_en'],
                'monto' => $validated['monto'],
                'banco' => $validated['banco'],
                'cbu' => $validated['cbu'],
                'alias' => $validated['alias'],
                'notas' => $validated['notas'] ?? null,
                'publicado' => true,
            ]);

            $residentes = $comunidad->residentes()->where('estado', true)->get();
            foreach ($residentes as $residente) {
                ExpensaPago::create([
                    'expensa_id' => $expensa->id,
                    'residente_id' => $residente->id,
                    'estado' => 'pendiente',
                    'monto' => $validated['monto'],
                ]);
            }
        });

        return redirect()
            ->route('admin.expensas.index')
            ->with('success', 'Expensa creada y publicada exitosamente.');
    }

    public function show(Expensa $expensa)
    {
        $admin = auth('admin')->user();

        if ($expensa->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $expensa->loadCount([
            'pagos as pagados_count' => function ($q) {
                $q->where('estado', 'pagado');
            },
        ])->loadCount('pagos');

        $pagos = $expensa->pagos()
            ->with('residente')
            ->orderBy('residente_id')
            ->get();

        return view('admin.expensas.show', compact('expensa', 'pagos'));
    }

    public function edit(Expensa $expensa)
    {
        $admin = auth('admin')->user();

        if ($expensa->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.expensas.edit', compact('expensa'));
    }

    public function update(Request $request, Expensa $expensa)
    {
        $admin = auth('admin')->user();

        if ($expensa->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'periodo' => ['required', 'string', 'max:255'],
            'vence_en' => ['required', 'date', 'after_or_equal:today'],
            'monto' => ['required', 'numeric', 'min:0'],
            'banco' => ['required', 'string', 'max:255'],
            'cbu' => ['required', 'string', 'max:255'],
            'alias' => ['required', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        $expensa->update($validated);

        return redirect()
            ->route('admin.expensas.index')
            ->with('success', 'Expensa actualizada exitosamente.');
    }

    public function destroy(Expensa $expensa)
    {
        $admin = auth('admin')->user();

        if ($expensa->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        DB::transaction(function () use ($expensa) {
            $expensa->pagos()->delete();
            $expensa->delete();
        });

        return redirect()
            ->route('admin.expensas.index')
            ->with('success', 'Expensa eliminada exitosamente.');
    }

    public function actualizarEstadoPago(Request $request, ExpensaPago $pago)
    {
        $admin = auth('admin')->user();

        if ($pago->expensa->comunidad_id !== $admin->comunidad_id) {
            abort(403);
        }

        $data = $request->validate([
            'estado' => ['required', 'in:pendiente,pagado'],
        ]);

        // Obtener el residente del pago
        $residente = $pago->residente;

        // Buscar todos los residentes de la misma unidad
        $residentesUnidad = Residente::where('comunidad_id', $residente->comunidad_id)
            ->where('numero_unidad', $residente->numero_unidad)
            ->where('estado', true) // Solo residentes activos
            ->get();

        // Actualizar el estado del pago para todos los residentes de la unidad
        $pagosActualizados = $pago->expensa->pagos()
            ->whereIn('residente_id', $residentesUnidad->pluck('id'))
            ->update([
                'estado' => $data['estado'],
                'pagado_en' => $data['estado'] === 'pagado' ? now() : null,
            ]);

        $mensaje = count($residentesUnidad) > 1
            ? "Estado del pago actualizado exitosamente para todos los residentes de la unidad ({$residente->numero_unidad})."
            : 'Estado del pago actualizado exitosamente.';

        return back()->with('success', $mensaje);
    }
}
