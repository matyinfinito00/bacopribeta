<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstalacionController extends Controller
{
    // Listado de instalaciones
    public function index()
    {
        $admin = auth('admin')->user();

        $instalaciones = Instalacion::where('comunidad_id', $admin->comunidad_id)
            ->latest()
            ->paginate(10);

        return view('admin.instalaciones.index', compact('instalaciones'));
    }

    // Formulario de creación
    public function create()
    {
        $tipos = Instalacion::getTiposDisponibles();
        $dias = Instalacion::getDiasSemana();
        return view('admin.instalaciones.create', compact('tipos', 'dias'));
    }

    // Guardar
    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'descripcion'        => 'nullable|string',
            'tipo'               => 'nullable|in:deportiva,gastronomia,eventos,recreacion,otra',
            'ubicacion'          => 'nullable|string|max:255',
            'capacidad'          => 'nullable|integer|min:1',
            'telefono'           => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'horario_general_desde' => 'required_with:horario_general_hasta,horario_general_dias|nullable|string',
            'horario_general_hasta' => 'required_with:horario_general_desde,horario_general_dias|nullable|string',
            'horario_general_dias'  => 'required_with:horario_general_desde|nullable|array|min:1',
            'horarios_adicionales'  => 'nullable|array',
            'horarios_adicionales.*.dia' => 'required_with:horarios_adicionales.*.desde|nullable|string',
            'horarios_adicionales.*.desde' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'horarios_adicionales.*.hasta' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'reglas'             => 'nullable|string',
            'amenidades'         => 'nullable|string',
            'es_reservable'      => 'nullable|boolean',
            'link_reserva'       => 'nullable|url|max:500',
        ]);

        // Validar que haya al menos un horario (general o adicional)
        if (empty($validated['horario_general_dias']) && empty($validated['horarios_adicionales'])) {
            return redirect()->back()
                ->withErrors(['horario' => 'Debes definir un horario general con al menos un día seleccionado, o agregar horarios adicionales.'])
                ->withInput();
        }

        $data = $validated;
        $data['comunidad_id'] = $admin->comunidad_id;
        $data['estado'] = true; 
        $data['es_reservable']      = $request->boolean('es_reservable', true);
        $data['link_reserva']       = $request->link_reserva;
        $data['amenidades'] = $this->procesarLista($request->amenidades);
        $data['reglas']     = $this->procesarLista($request->reglas);
        $data['horario'] = $this->procesarHorariosDesdeFormulario(
            $request->horario_general_desde,
            $request->horario_general_hasta,
            $request->horario_general_dias ?? [],
            $request->horarios_adicionales ?? []
        );
        
        // Procesar imágenes
        if ($request->hasFile('imagenes')) {
            $imagenesPaths = [];
            foreach ($request->file('imagenes') as $imagen) {
                $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('instalaciones', $nombreArchivo, 'public');
                $imagenesPaths[] = $ruta;
            }
            $data['imagenes'] = $imagenesPaths;
        } else {
            $data['imagenes'] = [];
        }
        
        Instalacion::create($data);

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación creada exitosamente.');
    }

    // Ver detalle
    public function show(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        return view('admin.instalaciones.show', compact('instalacion'));
    }

    // Editar
    public function edit(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $tipos = Instalacion::getTiposDisponibles();
        $dias = Instalacion::getDiasSemana();
        $horariosParseados = $this->parsearHorariosParaEdicion($instalacion->horario_array);
        
        return view('admin.instalaciones.edit', compact('instalacion', 'tipos', 'dias', 'horariosParseados'));
    }

    // Actualizar
    public function update(Request $request, Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'descripcion'        => 'nullable|string',
            'tipo'               => 'nullable|in:deportiva,gastronomia,eventos,recreacion,otra',
            'ubicacion'          => 'nullable|string|max:255',
            'capacidad'          => 'nullable|integer|min:1',
            'telefono'           => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'horario_general_desde' => 'required_with:horario_general_hasta,horario_general_dias|nullable|string',
            'horario_general_hasta' => 'required_with:horario_general_desde,horario_general_dias|nullable|string',
            'horario_general_dias'  => 'required_with:horario_general_desde|nullable|array|min:1',
            'horarios_adicionales'  => 'nullable|array',
            'horarios_adicionales.*.dia' => 'required_with:horarios_adicionales.*.desde|nullable|string',
            'horarios_adicionales.*.desde' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'horarios_adicionales.*.hasta' => 'required_with:horarios_adicionales.*.dia|nullable|string',
            'reglas'             => 'nullable|string',
            'amenidades'         => 'nullable|string',
            'imagenes'           => 'nullable|array',
            'imagenes.*'         => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB por imagen
            'imagenes_existentes' => 'nullable|array', // IDs de imágenes existentes a mantener
            'es_reservable'      => 'nullable|boolean',
            'link_reserva'       => 'nullable|url|max:500',
        ]);

        // Validar que haya al menos un horario (general o adicional)
        if (empty($validated['horario_general_dias']) && empty($validated['horarios_adicionales'])) {
            return redirect()->back()
                ->withErrors(['horario' => 'Debes definir un horario general con al menos un día seleccionado, o agregar horarios adicionales.'])
                ->withInput();
        }

        $data = $validated;
        $data['es_reservable']       = $request->boolean('es_reservable', $instalacion->es_reservable);
        $data['link_reserva']        = $request->link_reserva;
        $data['estado']              = $instalacion->estado;
        $data['amenidades'] = $this->procesarLista($request->amenidades);
        $data['reglas']     = $this->procesarLista($request->reglas);
        $data['horario']    = $this->procesarHorariosDesdeFormulario(
            $request->horario_general_desde,
            $request->horario_general_hasta,
            $request->horario_general_dias ?? [],
            $request->horarios_adicionales ?? []
        );
        
        // Procesar imágenes
        $imagenesPaths = [];
        
        // Mantener imágenes existentes si se especificaron
        if ($request->has('imagenes_existentes') && is_array($request->imagenes_existentes)) {
            $imagenesExistentes = $instalacion->imagenes ?? [];
            foreach ($request->imagenes_existentes as $index) {
                if (isset($imagenesExistentes[$index])) {
                    $imagenesPaths[] = $imagenesExistentes[$index];
                }
            }
        }
        
        // Agregar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('instalaciones', $nombreArchivo, 'public');
                $imagenesPaths[] = $ruta;
            }
        }
        
        // Eliminar imágenes que no se mantuvieron
        if ($instalacion->imagenes) {
            foreach ($instalacion->imagenes as $imagenPath) {
                if (!in_array($imagenPath, $imagenesPaths)) {
                    Storage::disk('public')->delete($imagenPath);
                }
            }
        }
        
        $data['imagenes'] = $imagenesPaths;

        $instalacion->update($data);

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación actualizada exitosamente.');
    }

    // Eliminar
    public function destroy(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $instalacion->delete();

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación eliminada exitosamente.');
    }

    // Activar/desactivar
    public function toggleStatus(Instalacion $instalacion)
    {
        $admin = auth('admin')->user();

        if ($instalacion->comunidad_id != $admin->comunidad_id) {
            abort(403);
        }

        $instalacion->update(['estado' => !$instalacion->estado]);

        $estado = $instalacion->estado ? 'activada' : 'desactivada';

        return redirect()->route('admin.instalaciones.index')
            ->with('success', "Instalación {$estado} exitosamente.");
    }


    // Procesar textarea a Array
    private function procesarLista(?string $input): ?array
    {
        if (!$input) return null;

        return array_filter(
            array_map('trim', preg_split('/\r?\n/', $input))
        );
    }

    // Procesar texto del horario a array 
    private function procesarHorario(?string $input): array
    {
        if (!$input) {
            return [];
        }

        $horarios = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $input))));
        
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
        $diasMap = Instalacion::getDiasSemana();

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
