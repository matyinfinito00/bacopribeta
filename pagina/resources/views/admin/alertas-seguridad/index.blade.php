<x-layouts.admin title="Gestión de Alertas de Seguridad" active="alertas-seguridad">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de alertas de seguridad</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.alertas-seguridad.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nueva alerta
                </a>
            </div>
        </div>
    </div>

    <!-- MENSAJES-->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- TABLA -->
    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de alertas de seguridad
            </h2>
        </div>
        <div class="card-body p-0">
            @if($alertas->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Prioridad</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alertas as $alerta)
                        <tr>
                            <td>
                                <div>
                                    <strong class="table-cell-primary">{{ $alerta->titulo }}</strong>
                                    @if($alerta->contenido)
                                    <br>
                                    <small class="text-muted-light">
                                        {{ Str::limit($alerta->contenido, 70) }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-modern {{ $alerta->prioridad_color }}">
                                    {{ ucfirst($alerta->prioridad) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted-light">
                                    {{ optional($alerta->publicado_en)->format('d/m/Y H:i') ?? 'Sin publicar' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.alertas-seguridad.show', $alerta) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.alertas-seguridad.edit', $alerta) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.alertas-seguridad.destroy', $alerta) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta alerta?');">
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
            @if($alertas->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $alertas->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-shield-alt icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay alertas de seguridad publicadas.</p>
                <a href="{{ route('admin.alertas-seguridad.create') }}" class="btn btn-modern-primary">
                    Crear Primera Alerta
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>