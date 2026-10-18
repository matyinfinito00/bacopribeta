<x-layouts.admin title="Gestión de Servicios" active="servicios">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de servicios</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.servicios.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo servicio
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

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de servicios
            </h2>
        </div>
        <div class="card-body p-0">
            @if($servicios->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Categoría</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Emergencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                        <tr>
                            <td>
                                <strong class="table-cell-primary">{{ $servicio->nombre }}</strong>
                                @if($servicio->descripcion)
                                <br>
                                <small class="text-muted-light">
                                    {{ Str::limit($servicio->descripcion, 60) }}
                                </small>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-modern bg-info">
                                    {{ $servicio->categoria_label }}
                                </span>
                            </td>

                            <td>
                                {{ $servicio->nombre_contacto }}
                                <br><small class="text-muted-light">{{ $servicio->correo }}</small>
                            </td>

                            <td>{{ $servicio->telefono }}</td>

                            <td>
                                <span class="badge badge-modern {{ $servicio->es_emergencia ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $servicio->es_emergencia ? 'Sí' : 'No' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-modern {{ $servicio->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $servicio->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.servicios.show', $servicio) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.servicios.edit', $servicio) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST"
                                        action="{{ route('admin.servicios.cambiar-estado', $servicio) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $servicio->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded"
                                            title="{{ $servicio->estado ? 'Pausar' : 'Activar' }}">
                                            <i class="fas {{ $servicio->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
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

            @if($servicios->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $servicios->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-address-book icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay servicios registrados.</p>
                <a href="{{ route('admin.servicios.create') }}" class="btn btn-modern-primary">
                    Crear Primer Servicio
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>