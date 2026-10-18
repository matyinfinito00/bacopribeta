<x-layouts.admin title="Crear Expensa" active="expensas">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nueva expensa</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Genera la liquidación del período y los registros individuales de cobro para residentes.</p>
        </div>
        <div>
            <a href="{{ route('admin.expensas.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a expensas
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-exclamation-circle fs-5 mt-1 text-danger"></i>
                <div>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.expensas.store') }}">
        @csrf

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (FORMULARIO) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-success-subtle text-success">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            Datos de la Liquidación & Cobranza
                        </h2>
                    </div>
                    <div class="admin-card-body">

                        <div class="form-legend-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Los campos marcados con <span class="required-asterisk">*</span> son de ingreso obligatorio.</span>
                        </div>

                        <!-- PERÍODO -->
                        <div class="mb-4">
                            <label for="periodo" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Período liquidado <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   class="form-control modern-input @error('periodo') is-invalid @enderror"
                                   id="periodo"
                                   name="periodo"
                                   value="{{ old('periodo') }}"
                                   placeholder="Ej: Septiembre 2026 u Octubre 2026"
                                   autocomplete="off"
                                   required>
                            @error('periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- VENCIMIENTO Y MONTO -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="vence_en" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Fecha límite de vencimiento <span class="required-asterisk">*</span>
                                </label>
                                <input type="date"
                                       class="form-control modern-input @error('vence_en') is-invalid @enderror"
                                       id="vence_en"
                                       name="vence_en"
                                       value="{{ old('vence_en') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('vence_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="monto" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Monto individual por unidad ($) <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted">$</span>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           class="form-control modern-input @error('monto') is-invalid @enderror"
                                           id="monto"
                                           name="monto"
                                           value="{{ old('monto') }}"
                                           placeholder="65000"
                                           required>
                                </div>
                                @error('monto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BANCO -->
                        <div class="mb-4">
                            <label for="banco" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Entidad bancaria receptora <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   class="form-control modern-input @error('banco') is-invalid @enderror"
                                   id="banco"
                                   name="banco"
                                   value="{{ old('banco') }}"
                                   placeholder="Ej: Banco Galicia / Banco Santander"
                                   required>
                            @error('banco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CBU Y ALIAS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="cbu" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    CBU / CVU oficial <span class="required-asterisk">*</span>
                                </label>
                                <input type="text"
                                       class="form-control modern-input @error('cbu') is-invalid @enderror"
                                       id="cbu"
                                       name="cbu"
                                       value="{{ old('cbu') }}"
                                       placeholder="22 dígitos del CBU"
                                       required>
                                @error('cbu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="alias" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Alias de transferencia <span class="required-asterisk">*</span>
                                </label>
                                <input type="text"
                                       class="form-control modern-input @error('alias') is-invalid @enderror"
                                       id="alias"
                                       name="alias"
                                       value="{{ old('alias') }}"
                                       placeholder="ejemplo.nordelta.adm"
                                       required>
                                @error('alias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- NOTAS -->
                        <div class="mb-2">
                            <label for="notas" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Notas / Conceptos de liquidación <span class="badge-field-optional">Opcional</span>
                            </label>
                            <textarea class="form-control modern-input @error('notas') is-invalid @enderror"
                                      id="notas"
                                      name="notas"
                                      rows="4"
                                      placeholder="Aclara conceptos extraordinarios, fondos de reserva o instrucciones para el envío de comprobantes...">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-lg-4">

                <!-- INFORMACIÓN DE GENERACIÓN AUTOMÁTICA -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-magic text-primary"></i>
                        Generación de Cobros
                    </div>

                    <p class="text-muted small mb-3">
                        Al confirmar la creación, el sistema generará automáticamente la obligación de pago para cada uno de los residentes activos de la comunidad.
                    </p>

                    <div class="alert alert-info py-2 px-3 mb-4" style="font-size: 0.82rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Los vecinos verán el botón de pago y subida de comprobantes en su portal personal.
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Crear expensa</span>
                        </button>
                        <a href="{{ route('admin.expensas.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

</x-layouts.admin>
