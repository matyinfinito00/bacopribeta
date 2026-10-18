<x-layouts.admin title="Gestión de Eventos" active="eventos">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de eventos</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.eventos.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo evento
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de eventos
            </h2>
        </div>
        <div class="card-body p-0">
            @if($eventos->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Hora</th>
                            <th>Lugar</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                        <tr>
                            <td>
                                <strong class="table-cell-primary">{{ $evento->titulo }}</strong>
                                @if($evento->categoria)
                                <br>
                                <small class="text-muted-light">Categoría: {{ $evento->categoria }}</small>
                                @endif
                                @if($evento->descripcion)
                                <br>
                                <small class="text-muted-light">
                                    {{ Str::limit($evento->descripcion, 70) }}
                                </small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-muted-light">
                                    {{ optional($evento->fecha)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted-light">
                                    {{ $evento->hora_inicio ? substr($evento->hora_inicio, 0, 5) : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted-light">{{ $evento->lugar ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.eventos.show', $evento) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.eventos.edit', $evento) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.eventos.destroy', $evento) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
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

            @if($eventos->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $eventos->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-calendar-alt icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay eventos cargados aún.</p>
                <a href="{{ route('admin.eventos.create') }}" class="btn btn-modern-primary">
                    Crear primer evento
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
