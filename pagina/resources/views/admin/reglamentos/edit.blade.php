<x-layouts.admin title="Editar Reglamento" active="reglamentos">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar reglamento</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualiza las cláusulas, normas o articulado de este reglamento.</p>
        </div>
        <div>
            <a href="{{ route('admin.reglamentos.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a reglamentos
            </a>
        </div>
    </div>

    <!-- ALERTAS DE VALIDACIÓN -->
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

    <form method="POST" action="{{ route('admin.reglamentos.update', $reglamento) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (EDITOR) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-primary-subtle text-primary">
                                <i class="fas fa-edit"></i>
                            </span>
                            Edición del Reglamento
                        </h2>
                    </div>
                    <div class="admin-card-body">

                        <div class="form-legend-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Los campos marcados con <span class="required-asterisk">*</span> son estrictamente obligatorios.</span>
                        </div>

                        <!-- TÍTULO -->
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Título del reglamento <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   id="titulo"
                                   name="titulo"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   value="{{ old('titulo', $reglamento->titulo) }}"
                                   placeholder="Ej: Reglamento de Convivencia del Barrio"
                                   required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTENIDO -->
                        <div class="mb-2">
                            <label for="contenido" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Articulado completo <span class="required-asterisk">*</span>
                            </label>
                            <textarea id="contenido"
                                      name="contenido"
                                      rows="12"
                                      class="form-control modern-input @error('contenido') is-invalid @enderror"
                                      style="line-height: 1.7;"
                                      required>{{ old('contenido', $reglamento->contenido) }}</textarea>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-lg-4">

                <!-- GUARDAR CAMBIOS -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-save text-primary"></i>
                        Guardar Cambios
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Creado el</span>
                            <span class="meta-value">{{ $reglamento->created_at->format('d/m/Y') }}</span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Última revisión</span>
                            <span class="meta-value">{{ $reglamento->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-save"></i>
                            <span>Guardar cambios</span>
                        </button>
                        <a href="{{ route('admin.reglamentos.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

</x-layouts.admin>
