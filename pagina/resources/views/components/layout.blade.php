<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow"> <!-- No indexar la página para los robots de los buscadores -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BACOPRI' }} - Gestión Inteligente para Barrios Privados</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/Logo-icono.png') }}">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">

            <span class="navbar-brand m-0">
                <a class="d-flex align-items-center text-decoration-none" href="{{ route('home') }}">
                    <img src="{{ asset('img/Logo_final.png') }}" alt="BACOPRI - Comunidad Digital" class="navbar-logo">
                    <span class="visually-hidden">BACOPRI - Comunidad Digital</span>
                </a>
            </span>

            <button class="navbar-toggler navbar-toggler-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon navbar-toggler-icon-light"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
                        <x-nav-link route="home">Inicio</x-nav-link>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#caracteristicas">Características</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contacto">Contacto</a></li>

                    <!-- USUARIO AUTENTICADO -->
                    @auth('admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-1"></i>
                            {{ auth('admin')->user()->nombre_completo }}
                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item"
                                    href="{{ auth('admin')->user()->es_super_admin ? route('admin.super-admin-dashboard') : route('admin.admin-comunidad-dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    <!-- USUARIO NO AUTENTICADO -->
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login.admin') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Administrador
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-custom" href="{{ route('demo.solicitud') }}">
                            Solicitar Demo
                        </a>
                    </li>
                    @endauth

                </ul>
            </div>

        </div>
    </nav>

    <!-- MAIN -->
    <main>
        {{ $slot }}
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">

            <div class="row">

                <div class="col-md-4 mb-4">
                    <h3 class="footer-title">BACOPRI</h3>
                    <p class="footer-text-light">
                        La solución integral para la gestión de barrios privados y countries.
                        Innovación, seguridad y conectividad al servicio de tu comunidad.
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">Enlaces Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#caracteristicas">Características</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contacto">Contacto</a></li>
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">Descargá la app</h4>
                    <p class="footer-text-lighter">Gestioná tu barrio desde el celular.</p>

                    <div class="store-badges d-flex gap-2 flex-wrap">
                        <a href="#" class="store-badge">
                            <i class="fab fa-google-play me-2"></i>
                            Google Play
                        </a>

                        <a href="#" class="store-badge">
                            <i class="fab fa-apple me-2"></i>
                            App Store
                        </a>
                    </div>
                </div>

            </div>

            <div class="copyright">
                <p>&copy; 2025 BACOPRI. Todos los derechos reservados.</p>
            </div>

        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- AOS Initialization -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });
    </script>
</body>
</html>