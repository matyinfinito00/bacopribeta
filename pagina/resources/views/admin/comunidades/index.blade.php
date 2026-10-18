<x-layouts.admin title="Comunidades - Administración" active="comunidades">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Comunidades</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.comunidades.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nueva comunidad
                </a>
            </div>
        </div>
    </div>

    <!-- MENSAJES -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <!-- TABLA -->
    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de comunidades
            </h2>
        </div>
        <div class="card-body p-0">
            @if ($comunidades->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ciudad</th>
                            <th>Provincia</th>
                            <th>Estado</th>
                            <th class="text-center">Administradores</th>
                            <th class="text-center">Residentes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comunidades as $comunidad)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-blue me-3 icon-sm">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <strong class="text-primary-dark">{{ $comunidad->nombre }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $comunidad->ciudad }}</span>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $comunidad->provincia }}</span>
                            </td>
                            <td>
                                <span class="badge badge-modern {{ $comunidad->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $comunidad->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-primary-dark fw-semibold">{{ $comunidad->administradores_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-primary-dark fw-semibold">{{ $comunidad->residentes_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- VER -->
                                    <a href="{{ route('admin.comunidades.show', $comunidad) }}"
                                        class="btn btn-sm btn-outline-info rounded">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- EDITAR -->
                                    <a href="{{ route('admin.comunidades.edit', $comunidad) }}"
                                        class="btn btn-sm btn-outline-warning rounded">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- CAMBIAR ESTADO -->
                                    @if($comunidad->nombre !== 'BACOPRI Sistema')
                                    <form method="POST" action="{{ route('admin.comunidades.cambiar-estado', $comunidad) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $comunidad->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded">
                                            <i class="fas {{ $comunidad->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- ELIMINAR -->
                                    @if($comunidad->administradores_count == 0 && $comunidad->nombre !== 'BACOPRI Sistema')
                                    <form method="POST" action="{{ route('admin.comunidades.destroy', $comunidad) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta comunidad?')"
                                            class="btn btn-sm btn-outline-danger rounded">
                                            <i class="fas fa-trash"></i>
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
            <div class="d-flex justify-content-center p-3">
                {{ $comunidades->links() }}
            </div>

            @else
            <div class="p-4 text-center">
                <i class="fas fa-building icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay comunidades registradas aún.</p>
                <a href="{{ route('admin.comunidades.create') }}" class="btn btn-modern-primary">
                    Crear Primera Comunidad
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
