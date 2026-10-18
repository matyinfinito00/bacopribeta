<x-layouts.admin title="Gestión de Residentes" active="residentes">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de residentes</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.residentes.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo residente
                </a>
            </div>
        </div>
    </div>

    <!-- MENSAJE -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de residentes
            </h2>
        </div>
        <div class="card-body p-0">
            @if($residentes->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($residentes as $residente)
                        <tr>

                            <!-- Nombre + icono -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-card-icon stat-card-blue me-3 icon-sm">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong class="text-primary-dark">{{ $residente->nombre_completo }}</strong>
                                        <br>
                                        @if($residente->es_propietario)
                                        <small class="text-success"><i class="fas fa-home me-1"></i>Propietario</small>
                                        @else
                                        <small class="text-info"><i class="fas fa-user me-1"></i>Inquilino</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">{{ $residente->direccion_completa }}</td>
                            <td>{{ $residente->telefono }}</td>
                            <td>{{ $residente->numero_unidad }}</td>

                            <td>
                                <span class="badge badge-modern {{ $residente->es_propietario ? 'bg-success' : 'bg-info' }}">
                                    {{ $residente->es_propietario ? 'Propietario' : 'Inquilino' }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td>
                                <span class="badge badge-modern {{ $residente->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $residente->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('admin.residentes.show', $residente) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.residentes.edit', $residente) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Activar/Desactivar -->
                                    <form action="{{ route('admin.residentes.cambiar-estado', $residente) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $residente->estado ? 'btn-outline-danger' : 'btn-outline-success' }} rounded"
                                            title="{{ $residente->estado ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $residente->estado ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('admin.residentes.destroy', $residente) }}"
                                        class="d-inline" onsubmit="return confirm('¿Eliminar este residente?');">
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

            <!-- Paginación -->
            @if($residentes->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $residentes->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-users icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay residentes registrados.</p>
                <a href="{{ route('admin.residentes.create') }}" class="btn btn-modern-primary">
                    Crear primer residente
                </a>
            </div>
            @endif

        </div>
    </div>

</x-layouts.admin>