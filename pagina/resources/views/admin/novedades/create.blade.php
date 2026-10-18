<x-layouts.admin title="Crear Novedad" active="novedades">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nueva novedad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Publica comunicados oficiales y noticias visibles para todos los residentes.</p>
        </div>
        <div>
            <a href="{{ route('admin.novedades.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a novedades
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

    <form method="POST" action="{{ route('admin.novedades.store') }}" enctype="multipart/form-data" id="novedadForm">
        @csrf

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (EDITOR) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-primary-subtle text-primary">
                                <i class="fas fa-pen-nib"></i>
                            </span>
                            Redacción del Comunicado
                        </h2>
                    </div>
                    <div class="admin-card-body">

                        <!-- BANNER EXPLICATIVO DE CAMPOS OBLIGATORIOS -->
                        <div class="form-legend-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Los campos marcados con <span class="required-asterisk">*</span> son de ingreso obligatorio.</span>
                        </div>

                        <!-- TÍTULO -->
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Título de la novedad <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   id="tituloInput"
                                   name="titulo"
                                   value="{{ old('titulo') }}"
                                   placeholder="Ej: Obras de mantenimiento en áreas comunes este fin de semana"
                                   autocomplete="off"
                                   required>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                Usa un título directo y representativo que capte la atención de los vecinos.
                            </div>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTENIDO -->
                        <div class="mb-4">
                            <label for="contenido" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Contenido o cuerpo del comunicado <span class="required-asterisk">*</span>
                            </label>
                            <textarea class="form-control modern-input @error('contenido') is-invalid @enderror"
                                      id="contenidoInput"
                                      name="contenido"
                                      rows="7"
                                      placeholder="Escribe el detalle completo de la novedad, fechas relevantes, recomendaciones y contactos necesarios..."
                                      style="line-height: 1.6;"
                                      required>{{ old('contenido') }}</textarea>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                Puedes escribir párrafos separados para que el texto sea más fácil de leer en la app.
                            </div>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SUBIDA DE IMAGEN DRAG & DROP -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Imagen de portada o fotografía ilustrativa <span class="badge-field-optional">Opcional</span>
                            </label>

                            <!-- DROPZONE MODERNO -->
                            <div class="modern-dropzone" id="dropzoneArea">
                                <div class="modern-dropzone-icon">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                </div>
                                <div class="modern-dropzone-title">
                                    Haz clic para seleccionar o arrastra una imagen aquí
                                </div>
                                <div class="modern-dropzone-subtitle">
                                    Formatos soportados: JPG, PNG, JPEG, GIF o WEBP (Máximo 5MB)
                                </div>

                                <!-- INPUT REAL OCULTO -->
                                <input type="file"
                                       id="imagenInput"
                                       name="imagen"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       style="position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;">
                            </div>

                            <!-- PREVIEW DE LA IMAGEN SELECCIONADA -->
                            <div class="dropzone-preview-container" id="dropzonePreview">
                                <img id="previewImg" src="" alt="Vista previa de imagen">
                                <button type="button" class="dropzone-remove-btn" id="btnRemoveImage" title="Quitar imagen">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            @error('imagen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA (PUBLICACIÓN & VISTA PREVIA) -->
            <div class="col-lg-4">

                <!-- TARJETA DE PUBLICACIÓN -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-paper-plane text-primary"></i>
                        Ajustes de Publicación
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Audiencia</span>
                            <span class="meta-value">
                                <span class="badge-admin badge-info">
                                    <i class="fas fa-users me-1"></i> Todos los vecinos
                                </span>
                            </span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Emisor</span>
                            <span class="meta-value text-truncate" style="max-width: 140px;">
                                {{ auth('admin')->user()->nombre ?? (auth('superadmin')->user()->nombre ?? 'Administración') }}
                            </span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Visibilidad</span>
                            <span class="meta-value text-success">
                                <i class="fas fa-eye me-1"></i> Inmediata
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-paper-plane"></i>
                            <span>Publicar novedad</span>
                        </button>
                        <a href="{{ route('admin.novedades.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- SIMULADOR DE VISTA PREVIA EN VIVO -->
                <div class="detail-sidebar-block mb-0">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-mobile-alt text-primary"></i>
                        Vista Previa en el Feed
                    </div>
                    <p class="text-muted small mb-3">Así verán esta tarjeta los residentes en su panel y teléfono:</p>

                    <div class="feed-preview-card">
                        <div class="feed-preview-image" id="liveFeedImgContainer">
                            <i class="fas fa-image fa-2x" id="liveFeedPlaceholderIcon"></i>
                            <img id="liveFeedImg" src="" alt="Vista previa" style="display: none;">
                        </div>
                        <div class="feed-preview-body">
                            <div class="feed-preview-date">
                                <i class="far fa-calendar-alt me-1"></i> Hoy
                            </div>
                            <div class="feed-preview-title" id="liveFeedTitle">
                                Título del comunicado...
                            </div>
                            <div class="feed-preview-text" id="liveFeedText">
                                Aquí aparecerá el contenido que redactes en el formulario para los vecinos...
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- SCRIPTS DE INTERACTIVIDAD PARA LA VISTA PREVIA Y DROPZONE -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tituloInput = document.getElementById('tituloInput');
            const contenidoInput = document.getElementById('contenidoInput');
            const imagenInput = document.getElementById('imagenInput');
            const dropzoneArea = document.getElementById('dropzoneArea');
            const dropzonePreview = document.getElementById('dropzonePreview');
            const previewImg = document.getElementById('previewImg');
            const btnRemoveImage = document.getElementById('btnRemoveImage');

            const liveFeedTitle = document.getElementById('liveFeedTitle');
            const liveFeedText = document.getElementById('liveFeedText');
            const liveFeedImg = document.getElementById('liveFeedImg');
            const liveFeedPlaceholderIcon = document.getElementById('liveFeedPlaceholderIcon');

            // Actualización de Título en tiempo real
            tituloInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveFeedTitle.textContent = val.length > 0 ? val : 'Título del comunicado...';
            });

            // Actualización de Contenido en tiempo real
            contenidoInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveFeedText.textContent = val.length > 0 ? val : 'Aquí aparecerá el contenido que redactes en el formulario para los vecinos...';
            });

            // Manejo de carga de imagen
            function handleImageFile(file) {
                if (!file || !file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const src = e.target.result;
                    // Preview grande
                    previewImg.src = src;
                    dropzonePreview.style.display = 'block';
                    dropzoneArea.style.display = 'none';

                    // Feed preview
                    liveFeedImg.src = src;
                    liveFeedImg.style.display = 'block';
                    liveFeedPlaceholderIcon.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }

            imagenInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    handleImageFile(this.files[0]);
                }
            });

            // Quitar imagen seleccionada
            btnRemoveImage.addEventListener('click', function (e) {
                e.preventDefault();
                imagenInput.value = '';
                previewImg.src = '';
                dropzonePreview.style.display = 'none';
                dropzoneArea.style.display = 'block';

                liveFeedImg.src = '';
                liveFeedImg.style.display = 'none';
                liveFeedPlaceholderIcon.style.display = 'block';
            });

            // Drag & Drop visual feedback
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, () => dropzoneArea.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, () => dropzoneArea.classList.remove('dragover'), false);
            });
        });
    </script>

</x-layouts.admin>