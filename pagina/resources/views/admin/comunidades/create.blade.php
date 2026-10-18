<x-layouts.admin title="Crear Comunidad" active="comunidades">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nueva comunidad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Completá los datos del barrio privado o condominio para darlo de alta.</p>
        </div>
        <div>
            <a href="{{ route('admin.comunidades.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- INFO DE LA COMUNIDAD -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
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

                    <form method="POST" action="{{ route('admin.comunidades.store') }}">
                        @csrf

                        <!-- NOMBRE + CORREO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre de la comunidad <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Las Palmeras Country Club" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo de contacto <span class="required-asterisk">*</span>
                                </label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                    id="correo" name="correo" value="{{ old('correo') }}" placeholder="administracion@comunidad.com" required>
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
                                name="descripcion" rows="3" placeholder="Breve reseña sobre la comunidad o características del predio...">{{ old('descripcion') }}</textarea>
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
                                    id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Ruta 28 Km 4.5" required>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="ciudad" class="form-label">
                                    Ciudad / Localidad <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror"
                                    id="ciudad" name="ciudad" value="{{ old('ciudad') }}" placeholder="Pilar" required>
                                @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="provincia" class="form-label">
                                    Provincia <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('provincia') is-invalid @enderror"
                                    id="provincia" name="provincia" value="{{ old('provincia') }}" placeholder="Buenos Aires" required>
                                @error('provincia')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CÓDIGO POSTAL / TELÉFONO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo_postal" class="form-label">
                                    Código postal <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror"
                                    id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="B1629" required>
                                @error('codigo_postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono de contacto <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="+54 9 11 1234-5678" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.comunidades.index') }}" class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save"></i> Guardar Comunidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
