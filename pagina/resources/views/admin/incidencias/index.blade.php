<x-layouts.admin title="Gestión de Incidencias" active="incidencias">

    <div class="page-header-modern">
        <div>
            <h1>Gestión de incidencias</h1>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filtros -->
    <div class="card modern-card mb-4">
        <div class="modern-card-header">
            <h2 class="h3">
                Filtros
            </h2>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.incidencias.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold label-primary">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                        <option value="resuelta" {{ request('estado') === 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold label-primary">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Incidencia::getTiposDisponibles() as $key => $label)
                        <option value="{{ $key }}" {{ request('tipo') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.incidencias.index') }}" class="btn btn-modern-secondary">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de incidencias -->
    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de incidencias
            </h2>
        </div>
        <div class="card-body p-0">
            @if($incidencias->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Residente</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidencias as $incidencia)
                        <tr>
                            <td>
                                <span class="badge badge-modern bg-info">
                                    {{ $incidencia->tipo_label }}
                                </span>
                            </td>
                            <td>
                                <strong class="table-cell-primary">{{ Str::limit($incidencia->titulo, 50) }}</strong>
                            </td>
                            <td>
                                @if($incidencia->residente)
                                <div>
                                    <strong class="text-primary-dark">{{ $incidencia->residente->nombre }}</strong>
                                    <br>
                                    <small class="text-muted-light">
                                        <i class="fas fa-envelope me-1"></i>{{ $incidencia->residente->correo }}
                                    </small>
                                    @if($incidencia->residente->telefono)
                                    <br>
                                    <small class="text-muted-light">
                                        <i class="fas fa-phone me-1"></i>{{ $incidencia->residente->telefono }}
                                    </small>
                                    @endif
                                </div>
                                @else
                                <div>
                                    <span class="badge badge-modern bg-warning text-dark">
                                        <i class="fas fa-shield-alt me-1"></i>Reportado por seguridad
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $incidencia->ubicacion ? Str::limit($incidencia->ubicacion, 30) : 'No especificada' }}</span>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $incidencia->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-modern {{ $incidencia->estado_color }}">
                                    {{ $incidencia->estado_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.incidencias.show', $incidencia) }}" class="btn btn-sm btn-outline-info rounded">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($incidencias->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $incidencias->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-inbox icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-0">No hay incidencias registradas.</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
