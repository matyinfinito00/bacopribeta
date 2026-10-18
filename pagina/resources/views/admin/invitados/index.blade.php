<x-layouts.admin title="Gestión de Invitados" active="invitados">

    <div class="page-header-modern">
        <div>
            <h1>Gestión de invitados</h1>
        </div>
    </div>

    <div class="card modern-card">
        <div class="modern-card-header">
            <h2 class="h3">
                Lista de invitados
            </h2>
        </div>
        <div class="card-body p-0">
            @if($invitados->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Invitado</th>
                            <th>DNI</th>
                            <th>Fecha de Creación</th>
                            <th>Residente</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invitados as $inv)
                        <tr>
                            <td class="table-cell-primary">{{ $inv->nombre_completo }}</td>
                            <td class="text-muted-light">{{ $inv->numero_documento }}</td>
                            <td class="text-muted-light">{{ $inv->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-muted-light">{{ $inv->residente?->nombre ?? 'N/D' }}</td>
                            <td>
                                <span class="badge badge-modern 
                                    @if($inv->estado === 'activo') bg-success
                                    @elseif($inv->estado === 'cancelado') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($inv->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.invitados.show', $inv) }}" class="btn btn-sm btn-outline-info rounded">
                                    <i class="fas fa-eye me-1"></i>Ver detalles
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($invitados->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $invitados->links() }}
            </div>
            @endif
            @else
            <div class="p-4 text-center">
                <i class="fas fa-user-friends icon-empty-state-lg mb-3"></i>
                <h2 class="h4 text-muted mb-2">Sin invitaciones registradas</h2>
                <p class="text-muted mb-0">Cuando los residentes creen invitaciones desde la app, las verás aquí.</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>