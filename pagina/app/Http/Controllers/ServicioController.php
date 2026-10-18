<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Listado de servicios
    public function index()
    {
        $admin = auth('admin')->user();

        $servicios = Servicio::where('comunidad_id', $admin->comunidad_id)
            ->latest()
            ->paginate(10);

        return view('admin.servicios.index', compact('servicios'));
    }

    // Formulario de creación
    public function create()
    {
        $categorias = Servicio::getCategoriasDisponibles();
        $dias = Servicio::getDiasSemana();
        return view('admin.servicios.create', compact('categorias', 'dias'));
    }

    // Guardar nuevo servicio
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'categoria'       => 'required|in:emergencia,mantenimiento,seguridad,limpieza,jardineria,otro',
            'nombre_contacto' => 'required|string|max:255',
            'telefono'        => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'correo'          => 'required|email|max:255',
            'direccion'       => 'required|string|max:255',
            'horario_general_desde' => 'required_with:horario_general_hasta,horario_general_dias|nullable|string',
            'horario_general_hasta' => 'required_with:horario_general_desde,horario_general_dias|nullable|string',
            'horario_general_dias'  => 'required_with:horario_general_desde|nullable|array|min:1',
            'horarios_adicionales'  => 'nullable|array',
            'horarios_adicionales.*.dia' => 'required_with:horarios_adicionales.*.desde|nullable|string',
            'horarios_adicionales.*.desde' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'horarios_adicionales.*.hasta' => 'required_with:horarios_adicionales.*.dia|nullable|string',
        ]);

        // Validar que haya al menos un horario (general o adicional)
        if (empty($validated['horario_general_dias']) && empty($validated['horarios_adicionales'])) {
            return redirect()->back()
                ->withErrors(['horario' => 'Debes definir un horario general con al menos un día seleccionado, o agregar horarios adicionales.'])
                ->withInput();
        }

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['es_emergencia'] = $request->categoria === 'emergencia';
        $data['estado'] = true;
        $data['horario'] = $this->procesarHorariosDesdeFormulario(
            $request->horario_general_desde,
            $request->horario_general_hasta,
            $request->horario_general_dias ?? [],
            $request->horarios_adicionales ?? []
        );

        Servicio::create($data);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    // Mostrar servicio
    public function show(Servicio $servicio)
    {
        $admin = auth('admin')->user();

        if ($servicio->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.servicios.show', compact('servicio'));
    }

    // Formulario de edición
    public function edit(Servicio $servicio)
    {
        $admin = auth('admin')->user();

        if ($servicio->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $categorias = Servicio::getCategoriasDisponibles();
        $dias = Servicio::getDiasSemana();
        $horariosParseados = $this->parsearHorariosParaEdicion($servicio->horario_array);
        
        return view('admin.servicios.edit', compact('servicio', 'categorias', 'dias', 'horariosParseados'));
    }

    // Actualizar servicio
    public function update(Request $request, Servicio $servicio)
    {
        $admin = auth('admin')->user();

        if ($servicio->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'categoria'       => 'required|in:emergencia,mantenimiento,seguridad,limpieza,jardineria,otro',
            'nombre_contacto' => 'required|string|max:255',
            'telefono'        => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'correo'          => 'required|email|max:255',
            'direccion'       => 'required|string|max:255',
            'horario_general_desde' => 'required_with:horario_general_hasta,horario_general_dias|nullable|string',
            'horario_general_hasta' => 'required_with:horario_general_desde,horario_general_dias|nullable|string',
            'horario_general_dias'  => 'required_with:horario_general_desde|nullable|array|min:1',
            'horarios_adicionales'  => 'nullable|array',
            'horarios_adicionales.*.dia' => 'required_with:horarios_adicionales.*.desde|nullable|string',
            'horarios_adicionales.*.desde' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'horarios_adicionales.*.hasta' => 'required_with:horarios_adicionales.*.dia|nullable|string',
        ]);

        // Validar que haya al menos un horario (general o adicional)
        if (empty($validated['horario_general_dias']) && empty($validated['horarios_adicionales'])) {
            return redirect()->back()
                ->withErrors(['horario' => 'Debes definir un horario general con al menos un día seleccionado, o agregar horarios adicionales.'])
                ->withInput();
        }

        $data = $validated;
        $data['es_emergencia'] = $request->categoria === 'emergencia';
        $data['horario'] = $this->procesarHorariosDesdeFormulario(
            $request->horario_general_desde,
            $request->horario_general_hasta,
            $request->horario_general_dias ?? [],
            $request->horarios_adicionales ?? []
        );
        $data['estado'] = $servicio->estado;

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    // Eliminar
    public function destroy(Servicio $servicio)
    {
        $admin = auth('admin')->user();

        if ($servicio->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }

    // Activar/Desactivar
    public function toggleStatus(Servicio $servicio)
    {
        $admin = auth('admin')->user();

        if ($servicio->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $servicio->update(['estado' => !$servicio->estado]);

        $estado = $servicio->estado ? 'activado' : 'desactivado';

        return redirect()->route('admin.servicios.index')
            ->with('success', "Servicio {$estado} exitosamente.");
    }

    // Procesar texto de horario a array
    private function procesarHorario(?string $input): array
    {
        if (!$input) {
            return [];
        }

        $horarios = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $input))));
        
        // Si después de procesar está vacío, retornar array vacío en lugar de null
        return $horarios ?: [];
    }

    // Procesar horarios desde el formulario (general + adicionales) a array
    private function procesarHorariosDesdeFormulario(
        ?string $desdeGeneral,
        ?string $hastaGeneral,
        ?array $diasGeneral,
        ?array $horariosAdicionales
    ): array {
        $horarios = [];
        $diasMap = Servicio::getDiasSemana();

        // Procesar horario general
        if ($desdeGeneral && $hastaGeneral && !empty($diasGeneral)) {
            foreach ($diasGeneral as $diaValue) {
                if (isset($diasMap[$diaValue])) {
                    $horarios[] = $diasMap[$diaValue] . ' ' . $desdeGeneral . ' a ' . $hastaGeneral;
                }
            }
        }

        // Procesar horarios adicionales
        if (!empty($horariosAdicionales)) {
            foreach ($horariosAdicionales as $adicional) {
                if (
                    !empty($adicional['dia']) &&
                    !empty($adicional['desde']) &&
                    !empty($adicional['hasta'])
                ) {
                    $diaValue = $adicional['dia'];
                    if (isset($diasMap[$diaValue])) {
                        $horarios[] = $diasMap[$diaValue] . ' ' . $adicional['desde'] . ' a ' . $adicional['hasta'];
                    }
                }
            }
        }

        return array_values(array_filter($horarios));
    }

    // Parsear horarios existentes para formulario de edición
    private function parsearHorariosExistentes(array $horarios): array
    {
        $resultado = [];
        
        foreach ($horarios as $horario) {
            // Formato esperado: "Lunes 09:00 a 18:00" o "Lunes 09:00-18:00"
            if (preg_match('/(Lunes|Martes|Miércoles|Jueves|Viernes|Sábado|Domingo)\s+(\d{2}:\d{2})\s+(?:a|-)\s+(\d{2}:\d{2})/i', $horario, $matches)) {
                $dia = ucfirst(strtolower($matches[1]));
                $desde = $matches[2];
                $hasta = $matches[3];
                
                // Normalizar nombre del día
                $diaMap = [
                    'Lunes' => 'lunes',
                    'Martes' => 'martes',
                    'Miércoles' => 'miercoles',
                    'Miercoles' => 'miercoles',
                    'Jueves' => 'jueves',
                    'Viernes' => 'viernes',
                    'Sábado' => 'sabado',
                    'Sabado' => 'sabado',
                    'Domingo' => 'domingo'
                ];
                
                $diaKey = $diaMap[$dia] ?? strtolower($dia);
                $resultado[$diaKey] = [
                    'activo' => true,
                    'desde' => $desde,
                    'hasta' => $hasta
                ];
            }
        }
        
        return $resultado;
    }

    // Parsear horarios para el nuevo formato de edición (general + adicionales)
    private function parsearHorariosParaEdicion(array $horarios): array
    {
        $horariosParseados = [];
        $horariosPorTiempo = [];
        
        // Agrupar horarios por tiempo
        foreach ($horarios as $horario) {
            if (preg_match('/(Lunes|Martes|Miércoles|Jueves|Viernes|Sábado|Domingo)\s+(\d{2}:\d{2})\s+(?:a|-)\s+(\d{2}:\d{2})/i', $horario, $matches)) {
                $dia = ucfirst(strtolower($matches[1]));
                $desde = $matches[2];
                $hasta = $matches[3];
                $tiempo = $desde . '-' . $hasta;
                
                if (!isset($horariosPorTiempo[$tiempo])) {
                    $horariosPorTiempo[$tiempo] = ['desde' => $desde, 'hasta' => $hasta, 'dias' => []];
                }
                
                $diaMap = [
                    'Lunes' => 'lunes',
                    'Martes' => 'martes',
                    'Miércoles' => 'miercoles',
                    'Miercoles' => 'miercoles',
                    'Jueves' => 'jueves',
                    'Viernes' => 'viernes',
                    'Sábado' => 'sabado',
                    'Sabado' => 'sabado',
                    'Domingo' => 'domingo'
                ];
                
                $diaKey = $diaMap[$dia] ?? strtolower($dia);
                $horariosPorTiempo[$tiempo]['dias'][] = $diaKey;
            }
        }
        
        // Encontrar el horario más común (general)
        $horarioGeneral = null;
        $maxDias = 0;
        foreach ($horariosPorTiempo as $tiempo => $data) {
            if (count($data['dias']) > $maxDias) {
                $maxDias = count($data['dias']);
                $horarioGeneral = $data;
                $horarioGeneral['tiempo'] = $tiempo;
            }
        }
        
        // Separar general y adicionales
        if ($horarioGeneral && $maxDias > 0) {
            $horariosParseados['general'] = [
                'desde' => $horarioGeneral['desde'],
                'hasta' => $horarioGeneral['hasta'],
                'dias' => $horarioGeneral['dias']
            ];
            
            // Los demás son adicionales
            $horariosParseados['adicionales'] = [];
            foreach ($horariosPorTiempo as $tiempo => $data) {
                if ($tiempo !== $horarioGeneral['tiempo']) {
                    foreach ($data['dias'] as $dia) {
                        $horariosParseados['adicionales'][] = [
                            'dia' => $dia,
                            'desde' => $data['desde'],
                            'hasta' => $data['hasta']
                        ];
                    }
                }
            }
        } else {
            // Si no hay horario común, todos son adicionales
            $horariosParseados['general'] = ['desde' => '', 'hasta' => '', 'dias' => []];
            $horariosParseados['adicionales'] = [];
            foreach ($horariosPorTiempo as $tiempo => $data) {
                foreach ($data['dias'] as $dia) {
                    $horariosParseados['adicionales'][] = [
                        'dia' => $dia,
                        'desde' => $data['desde'],
                        'hasta' => $data['hasta']
                    ];
                }
            }
        }
        
        return $horariosParseados;
    }
}
