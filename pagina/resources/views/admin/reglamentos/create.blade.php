<x-layouts.admin title="Crear Reglamento" active="reglamentos">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nuevo reglamento</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Redacta estatutos, normas de uso de espacios o régimen de convivencia.</p>
        </div>
        <div>
            <a href="{{ route('admin.reglamentos.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a reglamentos
            </a>
        </div>
    </div>

    <!-- VALIDACIÓN DE ERRORES -->
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

    <form method="POST" action="{{ route('admin.reglamentos.store') }}">
        @csrf

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (EDITOR) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-primary-subtle text-primary">
                                <i class="fas fa-file-contract"></i>
                            </span>
                            Redacción del Documento
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
                                Título o nombre de la normativa <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   id="titulo"
                                   name="titulo"
                                   value="{{ old('titulo') }}"
                                   placeholder="Ej: Reglamento Interno de Convivencia y Uso de Instalaciones Comunes"
                                   autocomplete="off"
                                   required>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                Asigna un título oficial para que los propietarios e inquilinos puedan consultarlo fácilmente.
                            </div>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTENIDO -->
                        <div class="mb-2">
                            <label for="contenido" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Articulado / Contenido íntegro del reglamento <span class="required-asterisk">*</span>
                            </label>
                            <textarea class="form-control modern-input @error('contenido') is-invalid @enderror"
                                      id="contenido"
                                      name="contenido"
                                      rows="12"
                                      placeholder="Art. 1° - Disposiciones generales...&#10;Art. 2° - Uso de instalaciones comunes...&#10;Art. 3° - Horarios de descanso y ruidos molestos..."
                                      style="line-height: 1.7; font-family: inherit;"
                                      required>{{ old('contenido') }}</textarea>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                Puedes separar los artículos por líneas o párrafos claros.
                            </div>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-lg-4">

                <!-- AJUSTES DE EMISIÓN -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-gavel text-primary"></i>
                        Aprobación & Publicación
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Carácter</span>
                            <span class="meta-value">
                                <span class="badge-admin badge-purple">Oficial del barrio</span>
                            </span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Acceso</span>
                            <span class="meta-value text-success">
                                <i class="fas fa-eye me-1"></i> Todos los residentes
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-save"></i>
                            <span>Guardar y publicar</span>
                        </button>
                        <a href="{{ route('admin.reglamentos.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- CONSEJOS DE REDACCIÓN -->
                <div class="detail-sidebar-block mb-0">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-lightbulb text-warning"></i>
                        Recomendaciones
                    </div>
                    <ul class="detail-checklist" style="font-size: 0.82rem;">
                        <li class="detail-checklist-item py-2">
                            <span class="detail-checklist-icon" style="width: 18px; height: 18px; font-size: 0.65rem;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span>Especifica claramente las sanciones o multas aplicables en caso de incumplimiento.</span>
                        </li>
                        <li class="detail-checklist-item py-2">
                            <span class="detail-checklist-icon" style="width: 18px; height: 18px; font-size: 0.65rem;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span>Indica horarios exactos para ruidos y eventos en áreas comunes.</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </form>

</x-layouts.admin>
