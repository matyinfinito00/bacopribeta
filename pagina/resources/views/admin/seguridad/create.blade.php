<x-layouts.admin title="Crear Usuario de Seguridad" active="seguridad">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nuevo usuario de seguridad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Habilita credenciales de acceso para el personal de garita, rondines y control de acceso.</p>
        </div>
        <div>
            <a href="{{ route('admin.seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- FORMULARIO PRINCIPAL -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        Datos del personal de seguridad
                    </h2>
                </div>

                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.seguridad.store') }}" id="guardForm">
                        @csrf

                        <!-- NOMBRE Y APELLIDO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" 
                                       value="{{ old('nombre') }}" 
                                       placeholder="Ej: Carlos" required autofocus>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">
                                    Apellido <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" name="apellido" 
                                       value="{{ old('apellido') }}" 
                                       placeholder="Ej: Gómez" required>
                                @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CORREO Y TELÉFONO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo electrónico <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="far fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control border-start-0 @error('correo') is-invalid @enderror" 
                                           id="correo" name="correo" 
                                           value="{{ old('correo') }}" 
                                           placeholder="seguridad.garita@barrio.com" required>
                                </div>
                                @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono / Interno <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control border-start-0 @error('telefono') is-invalid @enderror" 
                                           id="telefono" name="telefono" 
                                           value="{{ old('telefono') }}" 
                                           placeholder="Ej: +54 11 4455-6677 o Int. 101" required>
                                </div>
                                @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DOCUMENTO -->
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="tipo_documento" class="form-label">
                                    Tipo de documento <span class="required-asterisk">*</span>
                                </label>
                                <select id="tipo_documento" name="tipo_documento"
                                    class="form-select @error('tipo_documento') is-invalid @enderror" required>
                                    <option value="">Seleccionar tipo</option>
                                    @foreach($tiposDocumento as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('tipo_documento', 'DNI') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7 mb-3">
                                <label for="numero_documento" class="form-label">
                                    Número de documento <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="numero_documento" name="numero_documento"
                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                    value="{{ old('numero_documento') }}" placeholder="Ej: 34567890" maxlength="9" required>
                                @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">
                                    Contraseña de acceso <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                                </div>
                                <small class="text-muted" style="font-size: 0.78rem;">Utilizada para iniciar sesión en la app de garita.</small>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar contraseña <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-shield-alt"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Repetí la contraseña" required>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.seguridad.index') }}" class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-modern-primary px-4">
                                <i class="fas fa-save me-2"></i>Crear usuario de seguridad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: VISTA PREVIA & ROL -->
        <div class="col-lg-4">
            <!-- TARJETA CREDENCIAL -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-id-badge"></i>
                        </span>
                        Credencial de servicio
                    </h2>
                </div>
                <div class="admin-card-body text-center p-4">
                    <div class="profile-avatar-large mx-auto mb-3" id="previewAvatar" style="background: linear-gradient(135deg, #0284c7, #0f766e);">
                        GS
                    </div>
                    <h3 class="h5 fw-bold text-navy-950 mb-1" id="previewName">Nombre del Guardia</h3>
                    <p class="text-muted small mb-2" id="previewEmail">correo@seguridad.com</p>
                    <span class="badge-admin badge-success mb-3">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> Guardia Habilitado
                    </span>

                    <div class="bg-light rounded-3 p-3 text-start small mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Rol:</span>
                            <span class="fw-semibold text-navy-950">Seguridad / Portería</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Documento:</span>
                            <span class="fw-semibold text-navy-950" id="previewDoc">DNI -</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tel / Interno:</span>
                            <span class="fw-semibold text-navy-950" id="previewTel">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA FUNCIONES Y PRIVILEGIOS -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-warning-subtle text-warning">
                            <i class="fas fa-shield-virus"></i>
                        </span>
                        Alcance del perfil
                    </h2>
                </div>
                <div class="admin-card-body p-3">
                    <ul class="list-unstyled mb-0 small">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Control de accesos peatonales y vehiculares.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Validación de invitados mediante escaneo QR.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Recepción de alertas de pánico y emergencias.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Registro de ingresos y salidas en el libro de guardia digital.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nombreInput = document.getElementById('nombre');
            const apellidoInput = document.getElementById('apellido');
            const correoInput = document.getElementById('correo');
            const telInput = document.getElementById('telefono');
            const tipoDoc = document.getElementById('tipo_documento');
            const numDoc = document.getElementById('numero_documento');

            const previewAvatar = document.getElementById('previewAvatar');
            const previewName = document.getElementById('previewName');
            const previewEmail = document.getElementById('previewEmail');
            const previewDoc = document.getElementById('previewDoc');
            const previewTel = document.getElementById('previewTel');

            function updatePreview() {
                const nom = nombreInput.value.trim();
                const ape = apellidoInput.value.trim();
                const fullName = (nom || ape) ? `${nom} ${ape}`.trim() : 'Nombre del Guardia';
                previewName.textContent = fullName;

                let initials = 'GS';
                if (nom && ape) {
                    initials = (nom[0] + ape[0]).toUpperCase();
                } else if (nom) {
                    initials = nom.substring(0, 2).toUpperCase();
                }
                previewAvatar.textContent = initials;

                previewEmail.textContent = correoInput.value.trim() || 'correo@seguridad.com';
                previewDoc.textContent = `${tipoDoc.value || 'DNI'} ${numDoc.value.trim() || '-'}`;
                previewTel.textContent = telInput.value.trim() || '-';
            }

            [nombreInput, apellidoInput, correoInput, telInput, tipoDoc, numDoc].forEach(el => {
                if (el) el.addEventListener('input', updatePreview);
            });
            updatePreview();
        });
    </script>
</x-layouts.admin>
