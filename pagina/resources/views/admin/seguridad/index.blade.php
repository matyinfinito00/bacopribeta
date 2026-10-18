<x-layouts.admin title="Gestión de Usuarios de Seguridad" active="seguridad">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de usuarios de seguridad</h1>
            </div>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.seguridad.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo usuario de seguridad
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
                Lista de usuarios de seguridad
            </h2>
        </div>
        <div class="card-body p-0">
            @if($usuariosSeguridad->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($usuariosSeguridad as $usuario)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-warning me-3 icon-sm">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <strong class="table-cell-primary">{{ $usuario->nombre_completo }}</strong>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $usuario->correo }}</td>

                            <td>{{ $usuario->telefono }}</td>

                            <td>
                                <span class="badge badge-modern {{ $usuario->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <!-- VER -->
                                    <a href="{{ route('admin.seguridad.show', $usuario) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- EDITAR -->
                                    <a href="{{ route('admin.seguridad.edit', $usuario) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- CAMBIAR ESTADO -->
                                    <form method="POST"
                                        action="{{ route('admin.seguridad.cambiar-estado', $usuario) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $usuario->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded"
                                            title="{{ $usuario->estado ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $usuario->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- ELIMINAR -->
                                    <form method="POST"
                                        action="{{ route('admin.seguridad.destroy', $usuario) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este usuario de seguridad?')">
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
            @if($usuariosSeguridad->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $usuariosSeguridad->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-shield-alt icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay usuarios de seguridad registrados.</p>
                <a href="{{ route('admin.seguridad.create') }}" class="btn btn-modern-primary">
                    Crear primer usuario de seguridad
                </a>
            </div>
            @endif

        </div>
    </div>
</x-layouts.admin>
