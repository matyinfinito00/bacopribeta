<x-layouts.admin title="Gestión de Instalaciones" active="instalaciones">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de instalaciones</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.mapa.index') }}" class="btn btn-modern-secondary me-2">
                    <i class="fas fa-map-marked-alt me-2"></i>Mapa
                </a>
                <a href="{{ route('admin.instalaciones.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nueva instalación
                </a>
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

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de instalaciones
            </h2>
        </div>
        <div class="card-body p-0">
            @if($instalaciones->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Instalación</th>
                            <th>Tipo</th>
                            <th>Capacidad</th>
                            <th>Horarios</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instalaciones as $instalacion)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-blue me-3 icon-sm">
                                        <i class="fas {{ $instalacion->tipo_icono }}"></i>
                                    </div>
                                    <div>
                                        <strong class="table-cell-primary">{{ $instalacion->nombre }}</strong>
                                        @if($instalacion->descripcion)
                                        <br><small class="text-muted-light">{{ Str::limit($instalacion->descripcion, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- TIPO DE INSTALACIÓN -->
                            <td>
                                <span class="badge badge-modern bg-info">
                                    {{ $instalacion->tipo_label }}
                                </span>
                            </td>

                            <!-- CAPACIDAD -->
                            <td>
                                <span class="text-primary-dark"><i class="fas fa-users me-1"></i>{{ $instalacion->capacidad }}</span>
                            </td>

                            <!-- HORARIO -->
                            <td>
                                <span class="text-muted-light">{{ $instalacion->horario_resumen }}</span>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                <span class="badge badge-modern {{ $instalacion->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $instalacion->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>

                            <!-- BOTONES -->
                            <td>
                                <div class="d-flex gap-2">

                                    <!-- VER -->
                                    <a href="{{ route('admin.instalaciones.show', $instalacion) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- EDITAR -->
                                    <a href="{{ route('admin.instalaciones.edit', $instalacion) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- CAMBIAR ESTADO -->
                                    <form method="POST"
                                        action="{{ route('admin.instalaciones.cambiar-estado', $instalacion) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $instalacion->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded"
                                            title="{{ $instalacion->estado ? 'Pausar' : 'Activar' }}">
                                            <i class="fas {{ $instalacion->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>

                                    <!-- ELIMINAR -->
                                    <form method="POST"
                                        action="{{ route('admin.instalaciones.destroy', $instalacion) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta instalación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            @if($instalaciones->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $instalaciones->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-building icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay instalaciones registradas.</p>
                <a href="{{ route('admin.instalaciones.create') }}" class="btn btn-modern-primary">
                    Crear Primera Instalación
                </a>
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>
