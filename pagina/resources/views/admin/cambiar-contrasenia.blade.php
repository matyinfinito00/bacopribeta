<x-layouts.admin title="Cambiar Contraseña" active="">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Cambiar contraseña</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualizá tu clave de acceso para mantener la seguridad de tu cuenta.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-warning-subtle text-warning">
                            <i class="fas fa-key"></i>
                        </span>
                        Seguridad de la cuenta
                    </h2>
                </div>

                <div class="admin-card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.cambiar-contrasenia.submit') }}">
                        @csrf

                        <!-- Contraseña actual -->
                        <div class="mb-3">
                            <label for="password_actual" class="form-label">
                                Contraseña actual <span class="required-asterisk">*</span>
                            </label>
                            <input type="password" id="password_actual" name="password_actual"
                                class="form-control @error('password_actual') is-invalid @enderror"
                                placeholder="Tu contraseña vigente" required autofocus>
                            @error('password_actual')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Nueva contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nueva contraseña <span class="required-asterisk">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Al menos 8 caracteres" required>
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                Debe contener al menos 8 caracteres. Te recomendamos combinar letras y números.
                            </div>
                        </div>

                        <!-- Confirmar nueva contraseña -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmar nueva contraseña <span class="required-asterisk">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Repetí la nueva contraseña" required>
                        </div>

                        <div class="form-actions-bar">
                            <a href="javascript:history.back()" class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-check"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
