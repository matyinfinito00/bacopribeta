<x-layouts.admin title="Gestión de Administradores" active="administradores">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de administradores</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.administradores.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo administrador
                </a>
            </div>
        </div>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- TABLA -->
    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de administradores
            </h2>
        </div>
        <div class="card-body p-0">
            @if($administradores->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Comunidad</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($administradores as $administrador)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-blue me-3 icon-sm">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <strong class="text-primary-dark">{{ $administrador->nombre_completo }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $administrador->correo }}</span>
                            </td>
                            <td>
                                <span class="badge badge-modern bg-info">
                                    {{ $administrador->comunidad->nombre }}
                                </span>
                            </td>
                            <td>
                                <span class="text-primary-dark">{{ $administrador->cargo }}</span>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $administrador->telefono }}</span>
                            </td>
                            <td>
                                <span class="badge badge-modern {{ $administrador->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $administrador->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- VER -->
                                    <a href="{{ route('admin.administradores.show', $administrador) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- EDITAR -->
                                    <a href="{{ route('admin.administradores.edit', $administrador) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST"
                                        action="{{ route('admin.administradores.cambiar-estado', $administrador) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $administrador->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded"
                                            title="{{ $administrador->estado ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $administrador->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- ELIMINAR -->
                                    <form method="POST"
                                        action="{{ route('admin.administradores.destroy', $administrador) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este administrador?')">
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
            @if($administradores->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $administradores->links() }}
            </div>
            @endif

            @else
            <div class="p-4 text-center">
                <i class="fas fa-users icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay administradores registrados.</p>
                <a href="{{ route('admin.administradores.create') }}" class="btn btn-modern-primary">
                    Crear Primer Administrador
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
