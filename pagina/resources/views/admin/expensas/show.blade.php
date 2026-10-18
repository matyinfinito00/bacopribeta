<x-layouts.admin title="Detalle de Expensa - {{ $expensa->periodo }}" active="expensas">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de expensa</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Liquidación del período, datos bancarios y control de pagos por residente.</p>
        </div>
        <div>
            <a href="{{ route('admin.expensas.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a expensas
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA EXPENSA -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #059669, #047857);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

            <div class="profile-hero-details">
                <h1>Período: {{ $expensa->periodo }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin badge-success" style="font-size: 0.95rem; font-weight: 700;">
                        ${{ number_format($expensa->monto, 0, ',', '.') }}
                    </span>

                    @if($expensa->publicado)
                        <span class="badge-admin badge-info">
                            <i class="fas fa-check me-1"></i> Publicada
                        </span>
                    @else
                        <span class="badge-admin badge-secondary">
                            <i class="fas fa-pencil-alt me-1"></i> Borrador
                        </span>
                    @endif

                    @if($expensa->vence_en)
                        <span class="badge-admin badge-secondary">
                            <i class="far fa-calendar-alt me-1"></i> Vence: {{ $expensa->vence_en->format('d/m/Y') }}
                        </span>
                    @endif
                </div>

                <div class="profile-hero-meta">
                    @if($expensa->banco)
                        <span>
                            <i class="fas fa-university me-1 text-primary"></i>
                            {{ $expensa->banco }}
                        </span>
                    @endif
                    @if($expensa->alias)
                        <span>
                            <i class="fas fa-at me-1 text-success"></i>
                            Alias: {{ $expensa->alias }}
                        </span>
                    @endif
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Creada el {{ $expensa->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.expensas.edit', $expensa) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar expensa</span>
            </a>

            <form method="POST" action="{{ route('admin.expensas.destroy', $expensa) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Eliminar definitivamente esta expensa? Esto también eliminará todos los pagos asociados.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar expensa">
                    <i class="fas fa-trash"></i>
                    <span>Eliminar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DATOS BANCARIOS Y MONTO -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-money-check-alt"></i>
                        </span>
                        Información de Pago & Cobranza
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Monto por unidad</div>
                                <div class="detail-tile-value text-success" style="font-size: 1.15rem;">
                                    ${{ number_format($expensa->monto, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="far fa-calendar-times"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Fecha de vencimiento</div>
                                <div class="detail-tile-value">
                                    {{ $expensa->vence_en ? $expensa->vence_en->format('d/m/Y') : 'Sin fecha límite' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Entidad bancaria</div>
                                <div class="detail-tile-value">{{ $expensa->banco ?: 'No especificado' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">CBU / CVU</div>
                                <div class="detail-tile-value" style="font-size: 0.85rem; font-family: monospace;">
                                    {{ $expensa->cbu ?: 'No especificado' }}
                                </div>
                            </div>
                        </div>

                        @if($expensa->alias)
                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-at"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Alias de transferencia</div>
                                <div class="detail-tile-value fw-bold text-navy-950">{{ $expensa->alias }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($expensa->notas)
                    <div>
                        <div class="detail-tile-label mb-2">Notas y conceptos de la liquidación</div>
                        <div class="detail-description-box">
                            {!! nl2br(e($expensa->notas)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- TABLA DE PAGOS DE RESIDENTES -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-receipt"></i>
                        </span>
                        Control de Pagos por Residente
                        <span class="badge-admin badge-secondary ms-2">{{ $pagos->count() }}</span>
                    </h2>
                </div>
                <div class="admin-card-body p-0">
                    @if($pagos->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Residente</th>
                                        <th>Unidad</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Comprobante</th>
                                        <th>Fecha de pago</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagos as $pago)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ $pago->residente->nombre }} {{ $pago->residente->apellido ?? '' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-admin badge-secondary">
                                                    {{ $pago->residente->numero_unidad ?: '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-navy-950">${{ number_format($pago->monto, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                @if($pago->estado === 'pagado')
                                                    <span class="badge-admin badge-success">
                                                        <i class="fas fa-circle" style="font-size: 6px;"></i> Pagado
                                                    </span>
                                                @else
                                                    <span class="badge-admin badge-warning">
                                                        <i class="fas fa-circle" style="font-size: 6px;"></i> Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pago->comprobante_url)
                                                    @php
                                                        $comprobanteUrl = Storage::disk('public')->url($pago->comprobante_url);
                                                        $extension = pathinfo($pago->comprobante_url, PATHINFO_EXTENSION);
                                                        $esImagen = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    @endphp
                                                    @if($esImagen)
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                                data-bs-toggle="modal" data-bs-target="#comprobanteModal{{ $pago->id }}">
                                                            <i class="fas fa-image me-1"></i>Ver
                                                        </button>
                                                    @else
                                                        <a href="{{ $comprobanteUrl }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            <i class="fas fa-file-pdf me-1"></i>PDF
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-muted" style="font-size: 0.82rem;">Sin comprobante</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pago->estado === 'pagado' && $pago->pagado_en)
                                                    <span class="text-success" style="font-size: 0.85rem;">
                                                        <i class="far fa-check-circle me-1"></i>{{ $pago->pagado_en->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <form method="POST" action="{{ route('admin.expensas.pagos.estado', $pago->id) }}" class="d-inline-flex align-items-center gap-1">
                                                    @csrf
                                                    <select name="estado" class="form-select form-select-sm modern-input py-1" style="font-size: 0.8rem; width: auto;">
                                                        <option value="pendiente" {{ $pago->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                        <option value="pagado" {{ $pago->estado === 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-2 py-1" title="Actualizar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern py-4">
                            <div class="empty-state-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div class="empty-state-title">Sin pagos registrados</div>
                            <div class="empty-state-text">Aún no se han generado los registros individuales de pago para este período.</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- KPIs DE COBRANZA -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-chart-pie text-primary"></i>
                    Balance del Período
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Total unidades asignadas</span>
                        <span class="meta-value">{{ $expensa->pagos_count ?? 0 }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Pagos confirmados</span>
                        <span class="meta-value text-success">
                            <i class="fas fa-check-circle me-1"></i>{{ $expensa->pagados_count ?? 0 }}
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Pagos pendientes</span>
                        <span class="meta-value text-warning">
                            <i class="fas fa-clock me-1"></i>{{ ($expensa->pagos_count ?? 0) - ($expensa->pagados_count ?? 0) }}
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de emisión</span>
                        <span class="meta-value">{{ $expensa->created_at->format('d/m/Y') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ $expensa->updated_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.expensas.edit', $expensa) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar expensa</span>
                </a>

                <form method="POST" action="{{ route('admin.expensas.destroy', $expensa) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente esta expensa? Se eliminarán todos los pagos asociados.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar expensa</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Modales para ver comprobantes -->
    @foreach($pagos as $pago)
        @if($pago->comprobante_url)
            @php
                $comprobanteUrl = Storage::disk('public')->url($pago->comprobante_url);
                $extension = pathinfo($pago->comprobante_url, PATHINFO_EXTENSION);
                $esImagen = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            @endphp
            @if($esImagen)
            <div class="modal fade" id="comprobanteModal{{ $pago->id }}" tabindex="-1" aria-labelledby="comprobanteModalLabel{{ $pago->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="border: none; border-radius: var(--admin-radius-lg); overflow: hidden;">
                        <div class="modal-header" style="border-bottom: 1px solid var(--admin-border); padding: 1rem 1.5rem;">
                            <h2 class="h5 modal-title fw-bold text-navy-950 mb-0" id="comprobanteModalLabel{{ $pago->id }}">
                                <i class="fas fa-receipt me-2 text-primary"></i>Comprobante - {{ $pago->residente->nombre }}
                            </h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 text-center" style="background: #091e36;">
                            <img src="{{ $comprobanteUrl }}" alt="Comprobante" class="img-fluid" style="max-height: 75vh; object-fit: contain;">
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid var(--admin-border); padding: 0.75rem 1.5rem;">
                            <a href="{{ $comprobanteUrl }}" target="_blank" class="btn-modern-primary">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Abrir en tamaño completo</span>
                            </a>
                            <button type="button" class="btn-modern-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
    @endforeach

</x-layouts.admin>
