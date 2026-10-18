<x-layouts.admin title="Mapa de Instalaciones" active="instalaciones-mapa">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Mapa de instalaciones</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.mapa.configurar-barrio') }}" class="btn btn-modern-secondary me-2">
                    <i class="fas fa-cog me-2"></i>Configurar mapa del barrio
                </a>
                <a href="{{ route('admin.mapa.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Agregar coordenadas
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

    @if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de instalaciones con coordenadas
            </h2>
        </div>
        <div class="card-body p-0">
            @if($instalaciones->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Instalación</th>
                            <th>Coordenadas</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
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
                            <td>
                                @if($instalacion->lat && $instalacion->lng)
                                    <span class="text-success">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ number_format($instalacion->lat, 6) }}, {{ number_format($instalacion->lng, 6) }}
                                    </span>
                                @else
                                    <span class="text-muted-light">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Sin coordenadas
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($instalacion->lat && $instalacion->lng)
                                    <span class="badge badge-modern bg-success">Ubicada</span>
                                @else
                                    <span class="badge badge-modern bg-warning text-dark">Sin ubicar</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if($instalacion->lat && $instalacion->lng)
                                        <a href="{{ route('admin.mapa.show', $instalacion) }}" class="btn btn-sm btn-outline-info rounded" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mapa.edit', $instalacion) }}" class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.mapa.destroy', $instalacion) }}" 
                                              onsubmit="return confirm('¿Eliminar coordenadas de {{ $instalacion->nombre }}?')" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.mapa.create') }}?instalacion={{ $instalacion->id }}" class="btn btn-sm btn-outline-success rounded" title="Agregar coordenadas">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-4 text-center">
                <i class="fas fa-map-marked-alt icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-0">No hay instalaciones registradas.</p>
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>
