<x-layouts.admin title="Editar Novedad" active="novedades">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar novedad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Modifica los detalles, texto o imagen de este comunicado.</p>
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

    <form method="POST" action="{{ route('admin.novedades.update', $novedad) }}" enctype="multipart/form-data" id="novedadForm">
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
                            Edición del Contenido
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
                                   value="{{ old('titulo', $novedad->titulo) }}"
                                   placeholder="Ej: Obras de mantenimiento en áreas comunes"
                                   autocomplete="off"
                                   required>
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
                                      style="line-height: 1.6;"
                                      required>{{ old('contenido', $novedad->contenido) }}</textarea>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SUBIDA DE IMAGEN DRAG & DROP -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Imagen de portada <span class="badge-field-optional">Opcional</span>
                            </label>

                            @php
                                $hasExistingImg = !empty($novedad->imagen);
                                $existingImgUrl = $hasExistingImg ? Storage::disk('public')->url($novedad->imagen) : '';
                            @endphp

                            <!-- DROPZONE MODERNO -->
                            <div class="modern-dropzone" id="dropzoneArea" style="{{ $hasExistingImg ? 'display: none;' : '' }}">
                                <div class="modern-dropzone-icon">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                </div>
                                <div class="modern-dropzone-title">
                                    Haz clic para subir o arrastra una nueva imagen aquí
                                </div>
                                <div class="modern-dropzone-subtitle">
                                    Formatos soportados: JPG, PNG, JPEG, GIF o WEBP (Máximo 5MB)
                                </div>

                                <input type="file"
                                       id="imagenInput"
                                       name="imagen"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       style="position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;">
                            </div>

                            <!-- PREVIEW DE LA IMAGEN (EXISTENTE O REEMPLAZO) -->
                            <div class="dropzone-preview-container" id="dropzonePreview" style="{{ $hasExistingImg ? 'display: block;' : '' }}">
                                <img id="previewImg" src="{{ $existingImgUrl }}" alt="Vista previa de imagen">
                                <button type="button" class="dropzone-remove-btn" id="btnRemoveImage" title="Cambiar imagen">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>

                            @if($hasExistingImg)
                                <div class="form-text text-muted" id="existingImgNotice">
                                    Imagen actualmente guardada. Haz clic en el botón de recarga para sustituirla por otra.
                                </div>
                            @endif

                            @error('imagen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA (GUARDAR & VISTA PREVIA) -->
            <div class="col-lg-4">

                <!-- TARJETA DE ACCIONES -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-save text-primary"></i>
                        Guardar Cambios
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Publicado</span>
                            <span class="meta-value">{{ optional($novedad->publicado_en)->format('d/m/Y H:i') ?? 'Borrador' }}</span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Última edición</span>
                            <span class="meta-value">{{ optional($novedad->updated_at)->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-save"></i>
                            <span>Guardar cambios</span>
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

                    <div class="feed-preview-card">
                        <div class="feed-preview-image" id="liveFeedImgContainer">
                            <i class="fas fa-image fa-2x" id="liveFeedPlaceholderIcon" style="{{ $hasExistingImg ? 'display: none;' : '' }}"></i>
                            <img id="liveFeedImg" src="{{ $existingImgUrl }}" alt="Vista previa" style="{{ $hasExistingImg ? 'display: block;' : 'display: none;' }}">
                        </div>
                        <div class="feed-preview-body">
                            <div class="feed-preview-date">
                                <i class="far fa-calendar-alt me-1"></i> {{ optional($novedad->publicado_en)->format('d/m/Y') ?? 'Hoy' }}
                            </div>
                            <div class="feed-preview-title" id="liveFeedTitle">
                                {{ $novedad->titulo }}
                            </div>
                            <div class="feed-preview-text" id="liveFeedText">
                                {{ $novedad->contenido }}
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
            const existingImgNotice = document.getElementById('existingImgNotice');

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
                liveFeedText.textContent = val.length > 0 ? val : 'Aquí aparecerá el texto que redactes...';
            });

            // Manejo de carga de imagen
            function handleImageFile(file) {
                if (!file || !file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const src = e.target.result;
                    previewImg.src = src;
                    dropzonePreview.style.display = 'block';
                    dropzoneArea.style.display = 'none';
                    if (existingImgNotice) existingImgNotice.style.display = 'none';

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

            // Cambiar imagen
            btnRemoveImage.addEventListener('click', function (e) {
                e.preventDefault();
                imagenInput.value = '';
                dropzonePreview.style.display = 'none';
                dropzoneArea.style.display = 'block';
                if (existingImgNotice) existingImgNotice.style.display = 'none';
            });

            // Drag & Drop
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, () => dropzoneArea.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, () => dropzoneArea.classList.remove('dragover'), false);
            });
        });
    </script>

</x-layouts.admin>