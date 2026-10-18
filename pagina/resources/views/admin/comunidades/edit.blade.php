<x-layouts.admin title="Editar Comunidad - {{ $comunidad->nombre }}" active="comunidades">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar comunidad: {{ $comunidad->nombre }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualizá la información general y de contacto del barrio o condominio.</p>
        </div>
        <div>
            <a href="{{ route('admin.comunidades.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- INFO DE LA COMUNIDAD -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-city"></i>
                        </span>
                        Información de la comunidad
                    </h2>
                </div>

                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.comunidades.update', $comunidad) }}">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE + CORREO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre de la comunidad <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre', $comunidad->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo de contacto <span class="required-asterisk">*</span>
                                </label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                    id="correo" name="correo" value="{{ old('correo', $comunidad->correo) }}" required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción general <span class="badge-field-optional">Opcional</span>
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                name="descripcion" rows="3">{{ old('descripcion', $comunidad->descripcion) }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DIRECCIÓN / CIUDAD / PROVINCIA -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">
                                    Dirección <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion"
                                    value="{{ old('direccion', $comunidad->direccion) }}" required>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="ciudad" class="form-label">
                                    Ciudad / Localidad <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror"
                                    id="ciudad" name="ciudad" value="{{ old('ciudad', $comunidad->ciudad) }}" required>
                                @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="provincia" class="form-label">
                                    Provincia <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('provincia') is-invalid @enderror"
                                    id="provincia" name="provincia"
                                    value="{{ old('provincia', $comunidad->provincia) }}" required>
                                @error('provincia')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CÓDIGO POSTAL + TELÉFONO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo_postal" class="form-label">
                                    Código postal <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror"
                                    id="codigo_postal" name="codigo_postal"
                                    value="{{ old('codigo_postal', $comunidad->codigo_postal) }}" required>
                                @error('codigo_postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono de contacto <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono', $comunidad->telefono) }}"
                                    required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.comunidades.index') }}" class="btn-modern-secondary">Cancelar</a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: RESUMEN Y ESTADO -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-chart-pie"></i>
                        </span>
                        Resumen del barrio
                    </h2>
                </div>

                <div class="admin-card-body">
                    <ul class="list-unstyled mb-3">
                        <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="fas fa-user-shield me-2 text-primary"></i>Administradores</span>
                            <span class="fw-bold">{{ $comunidad->administradores()->count() }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="fas fa-home me-2 text-success"></i>Residentes</span>
                            <span class="fw-bold">{{ $comunidad->residentes()->count() }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="fas fa-building me-2 text-warning"></i>Instalaciones</span>
                            <span class="fw-bold">{{ $comunidad->instalaciones()->count() }}</span>
                        </li>
                    </ul>

                    <div class="mb-3">
                        <label class="form-label text-muted small d-block mb-1">Estado de la cuenta:</label>
                        @if($comunidad->estado)
                            <span class="badge-admin badge-success">
                                <i class="fas fa-check-circle me-1"></i>Comunidad Activa
                            </span>
                        @else
                            <span class="badge-admin badge-danger">
                                <i class="fas fa-times-circle me-1"></i>Comunidad Inactiva
                            </span>
                        @endif
                    </div>

                    <div class="pt-2 border-top">
                        <small class="text-muted d-block">Última actualización:</small>
                        <span class="small fw-semibold text-secondary">{{ $comunidad->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
