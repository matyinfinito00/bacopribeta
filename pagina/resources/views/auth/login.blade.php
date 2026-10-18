<x-layout title="Iniciar Sesión - Administrador">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-body p-5">
                        <!-- LOGO -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('img/Logo.png') }}" alt="BACOPRI Logo" class="login-logo">
                            <h1 class="h4 mb-3 text-primary-custom">
                                Panel de Administración
                            </h1>
                            <p class="text-muted">Inicia sesión para acceder al panel</p>
                        </div>

                        <!-- ERRORES DE VALIDACIÓN -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- MENSAJE DE LOGOUT EXITOSO -->
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- FORMULARIO DE LOGIN -->
                        <form method="POST" action="{{ route('login.admin.submit') }}">
                            @csrf

                            <!-- CORREO -->
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" id="correo" name="correo"
                                    class="form-control @error('correo') is-invalid @enderror"
                                    value="{{ old('correo') }}" required autofocus>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CONTRASEÑA -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- RECORDARME -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label for="remember" class="form-check-label">Recordarme</label>
                            </div>

                            <!-- BOTÓN -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                Si olvidaste tu contraseña, contactate con <a href="mailto:soporte@bacopri.com" class="text-decoration-none" style="color: #0D3B66; font-weight: 600;">soporte@bacopri.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>