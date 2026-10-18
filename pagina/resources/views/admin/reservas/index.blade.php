<x-layouts.admin title="Gestión de Reservas" active="reservas">
    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de reservas</h1>
            </div>
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
                Filtros de búsqueda
            </h2>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.reservas.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold label-primary">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activa" {{ request('estado') === 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold label-primary">Tipo de instalación</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($tiposInstalaciones as $value => $label)
                        <option value="{{ $value }}" {{ request('tipo') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold label-primary">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.reservas.index') }}" class="btn btn-modern-secondary">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de reservas
            </h2>
        </div>
        <div class="card-body p-0">
            @if($reservas->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Residente</th>
                            <th>Instalación</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                        <tr>
                            <td>
                                <div>
                                    <strong class="table-cell-primary">{{ $reserva->residente->nombre }}</strong>
                                    <br>
                                    <small class="text-muted-light">
                                        <i class="fas fa-envelope me-1"></i>{{ $reserva->residente->correo }}
                                    </small>
                                    @if($reserva->residente->telefono)
                                    <br>
                                    <small class="text-muted-light">
                                        <i class="fas fa-phone me-1"></i>{{ $reserva->residente->telefono }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-blue me-3 icon-sm">
                                        <i class="fas {{ $reserva->instalacion->tipo_icono }}"></i>
                                    </div>
                                    <div>
                                        <strong class="table-cell-primary">{{ $reserva->instalacion->nombre }}</strong>
                                        <br>
                                        <small class="text-muted-light">{{ $reserva->instalacion->tipo_label }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="table-cell-primary">{{ $reserva->fecha->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted-light">
                                        {{ $reserva->fecha->locale('es')->dayName }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-clock me-1"></i>{{ $reserva->hora_inicio }}
                            </td>
                            <td>
                                {{ $reserva->duracion_minutos }} min
                            </td>
                            <td>
                                @if($reserva->estado === 'activa')
                                <span class="badge badge-modern bg-success">Activa</span>
                                @elseif($reserva->estado === 'cancelada')
                                <span class="badge badge-modern bg-danger">Cancelada</span>
                                @else
                                <span class="badge badge-modern bg-secondary">Completada</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- VER -->
                                    <a href="{{ route('admin.reservas.show', $reserva) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- CANCELAR (solo si está activa) -->
                                    @if($reserva->estado === 'activa')
                                    <form method="POST"
                                        action="{{ route('admin.reservas.cancelar', $reserva) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="Cancelar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            @if($reservas->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $reservas->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-calendar-check icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay reservas registradas.</p>
                @if(request()->hasAny(['estado', 'tipo', 'fecha']))
                <a href="{{ route('admin.reservas.index') }}" class="btn btn-modern-primary">
                    Limpiar filtros
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>
