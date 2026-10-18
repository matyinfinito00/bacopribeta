<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expensa;
use App\Models\ExpensaPago;
use App\Models\Residente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpensaController extends Controller
{
    public function index(Request $request)
    {
        $residente = $request->user();

        $expensas = $residente->comunidad->expensas()
            ->with(['pagos' => function ($q) use ($residente) {
                $q->where('residente_id', $residente->id);
            }])
            ->where('publicado', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (Expensa $expensa) use ($residente) {
                $pago = $expensa->pagos->first();

                // Determinar el estado real: si tiene comprobante pero está pendiente, es "en_proceso"
                $estadoReal = $pago?->estado ?? 'pendiente';
                if ($pago && $pago->comprobante_url && $pago->estado === 'pendiente') {
                    $estadoReal = 'en_proceso';
                }

                return [
                    'id' => $expensa->id,
                    'periodo' => $expensa->periodo,
                    'vence_en' => $expensa->vence_en ? $expensa->vence_en->toDateString() : null,
                    'monto' => (float) $expensa->monto,
                    'banco' => $expensa->banco,
                    'cbu' => $expensa->cbu,
                    'alias' => $expensa->alias,
                    'notas' => $expensa->notas,
                    'ref' => '#EXP-' . str_pad((string) $expensa->id, 4, '0', STR_PAD_LEFT),
                    'unidad' => $residente->numero_unidad,
                    'estado_residente' => $estadoReal,
                    'monto_residente' => $pago ? (float) $pago->monto : (float) $expensa->monto,
                    'pagado_en' => $pago && $pago->pagado_en ? $pago->pagado_en->toDateTimeString() : null,
                    'comprobante_url' => $pago && $pago->comprobante_url ? Storage::disk('public')->url($pago->comprobante_url) : null,
                ];
            })
            ->values();

        return response()->json([
            'expensas' => $expensas,
        ]);
    }

    public function subirComprobante(Request $request, $expensaId)
    {
        $residente = $request->user();

        $request->validate([
            'comprobante' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:10240', // Max 10MB
            'metodo_pago' => 'required|in:transferencia,efectivo',
        ]);

        $expensa = $residente->comunidad->expensas()
            ->where('id', $expensaId)
            ->where('publicado', true)
            ->firstOrFail();

        // Buscar todos los residentes de la misma unidad (mismo numero_unidad y comunidad_id)
        $residentesUnidad = Residente::where('comunidad_id', $residente->comunidad_id)
            ->where('numero_unidad', $residente->numero_unidad)
            ->where('estado', true) // Solo residentes activos
            ->get();

        // Guardar nuevo comprobante
        $archivo = $request->file('comprobante');
        $extension = strtolower($archivo->getClientOriginalExtension());
        $nombreArchivo = 'comprobante_' . $expensa->id . '_' . $residente->numero_unidad . '_' . time() . '.' . $extension;
        $ruta = $archivo->storeAs('comprobantes', $nombreArchivo, 'public');

        // Obtener comprobantes anteriores de la unidad para esta expensa (para eliminarlos después si no se usan)
        $pagosAnteriores = $expensa->pagos()
            ->whereIn('residente_id', $residentesUnidad->pluck('id'))
            ->whereNotNull('comprobante_url')
            ->get();
        
        $comprobantesAnteriores = $pagosAnteriores->pluck('comprobante_url')->unique();

        // Crear o actualizar el pago para todos los residentes de la unidad
        $residentesAfectados = [];
        foreach ($residentesUnidad as $residenteUnidad) {
            $pago = ExpensaPago::updateOrCreate(
                [
                    'expensa_id' => $expensa->id,
                    'residente_id' => $residenteUnidad->id,
                ],
                [
                    'monto' => $expensa->monto,
                    'estado' => 'pendiente',
                    'comprobante_url' => $ruta,
                ]
            );

            $residentesAfectados[] = $residenteUnidad->nombre;
        }

        // Eliminar comprobantes anteriores que ya no se usan (solo si no están siendo usados por otros)
        foreach ($comprobantesAnteriores as $comprobanteAnterior) {
            if ($comprobanteAnterior !== $ruta) {
                // Verificar si algún otro pago (fuera de esta unidad) usa este comprobante
                $enUso = $expensa->pagos()
                    ->where('comprobante_url', $comprobanteAnterior)
                    ->whereNotIn('residente_id', $residentesUnidad->pluck('id'))
                    ->exists();
                
                if (!$enUso && Storage::disk('public')->exists($comprobanteAnterior)) {
                    Storage::disk('public')->delete($comprobanteAnterior);
                }
            }
        }

        $mensaje = count($residentesAfectados) > 1
            ? 'Comprobante subido exitosamente. El pago se aplicó a todos los residentes de la unidad (' . implode(', ', $residentesAfectados) . '). La expensa está en proceso de validación.'
            : 'Comprobante subido exitosamente. La expensa está en proceso de validación.';

        return response()->json([
            'message' => $mensaje,
            'comprobante_url' => Storage::disk('public')->url($ruta),
            'residentes_afectados' => $residentesAfectados,
        ], 201);
    }

    /**
     * Eliminar comprobante de una expensa
     */
    public function eliminarComprobante(Request $request, $expensaId)
    {
        $residente = $request->user();

        $expensa = $residente->comunidad->expensas()
            ->where('id', $expensaId)
            ->where('publicado', true)
            ->firstOrFail();

        // Buscar todos los residentes de la misma unidad
        $residentesUnidad = Residente::where('comunidad_id', $residente->comunidad_id)
            ->where('numero_unidad', $residente->numero_unidad)
            ->where('estado', true)
            ->get();

        // Obtener los pagos de la unidad para esta expensa
        $pagos = $expensa->pagos()
            ->whereIn('residente_id', $residentesUnidad->pluck('id'))
            ->get();

        $comprobantesAEliminar = $pagos->pluck('comprobante_url')->filter()->unique();

        // Eliminar los comprobantes del almacenamiento
        foreach ($comprobantesAEliminar as $comprobanteUrl) {
            if (Storage::disk('public')->exists($comprobanteUrl)) {
                Storage::disk('public')->delete($comprobanteUrl);
            }
        }

        // Actualizar los pagos para eliminar el comprobante pero mantener el registro
        foreach ($pagos as $pago) {
            $pago->comprobante_url = null;
            $pago->estado = 'pendiente';
            $pago->save();
        }

        return response()->json([
            'message' => 'Comprobante eliminado exitosamente.',
        ]);
    }
}
