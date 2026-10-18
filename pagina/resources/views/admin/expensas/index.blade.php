<x-layouts.admin title="Gestión de Expensas" active="expensas">

    <div class="page-header-modern">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div>
                <h1>Gestión de expensas</h1>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.expensas.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus me-2"></i>Nueva expensa
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
                Lista de expensas
            </h2>
        </div>
        <div class="card-body p-0">
            @if($expensas->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="text-center">Monto</th>
                            <th class="text-center">Vencimiento</th>
                            <th class="text-center">Pagos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensas as $expensa)
                        <tr>
                            <td>
                                <strong class="table-cell-primary">{{ $expensa->periodo }}</strong>
                                @if($expensa->notas)
                                <br>
                                <small class="text-muted-light">
                                    {{ Str::limit($expensa->notas, 50) }}
                                </small>
                                @endif
                            </td>
                            <td class="text-center">
                                <strong class="text-success">${{ number_format($expensa->monto, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                @if($expensa->vence_en)
                                <span class="text-muted-light">
                                    {{ $expensa->vence_en->format('d/m/Y') }}
                                </span>
                                @else
                                <span class="text-muted-light">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-modern bg-info">
                                    {{ $expensa->pagados_count ?? 0 }} / {{ $expensa->pagos_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($expensa->publicado)
                                <span class="badge badge-modern bg-success">Publicada</span>
                                @else
                                <span class="badge badge-modern bg-secondary">Borrador</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.expensas.show', $expensa) }}"
                                        class="btn btn-sm btn-outline-info rounded" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.expensas.edit', $expensa) }}"
                                        class="btn btn-sm btn-outline-warning rounded" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.expensas.destroy', $expensa) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta expensa?')">
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

            @if($expensas->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $expensas->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-file-invoice-dollar icon-empty-state-lg mb-3"></i>
                <p class="text-muted mb-3">No hay expensas creadas aún.</p>
                <a href="{{ route('admin.expensas.create') }}" class="btn btn-modern-primary">
                    Crear primera expensa
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
