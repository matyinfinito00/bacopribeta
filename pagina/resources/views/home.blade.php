<x-layout title="BACOPRI - Gestión Inteligente para Barrios">
    
    <!-- HERO SECTION  -->
    <section class="hero-section" id="inicio">
        <div class="container">
            <div id="heroShowcaseCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroShowcaseCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Gestión inteligente"></button>
                    <button type="button" data-bs-target="#heroShowcaseCarousel" data-bs-slide-to="1" aria-label="Dashboard de administración"></button>
                    <button type="button" data-bs-target="#heroShowcaseCarousel" data-bs-slide-to="2" aria-label="Panel de seguridad"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-lg-6 hero-content" data-aos="fade-right">
                                <h1 class="hero-title">Gestión inteligente para tu barrio</h1>
                                <p class="hero-description">
                                    La plataforma que conecta a residentes, administradores y proveedores en un solo lugar. 
                                    Simplificá reservas, alertas, expensas y comunicación con una experiencia moderna y segura.
                                </p>
                                <div class="hero-buttons hero-buttons--desktop">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-phone" data-aos="fade-left">
                                <div class="hero-phone-wrapper">
                                    <img src="{{ asset('img/celu-home.png') }}" alt="BACOPRI App" class="hero-carousel-img hero-carousel-img--float">
                                </div>
                            </div>
                            <div class="col-12 d-lg-none">
                                <div class="hero-buttons hero-buttons--mobile">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-lg-6 hero-content" data-aos="fade-right">
                                <h2 class="hero-title">Dashboard de administración</h2>
                                <p class="hero-description">
                                    Un panel completo para gestionar expensas, incidencias, proveedores y comunicaciones con trazabilidad total.
                                    Todo en tiempo real y con control por roles.
                                </p>
                                <div class="hero-buttons hero-buttons--desktop">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-phone" data-aos="fade-left">
                                <div class="hero-phone-wrapper">
                                    <img src="{{ asset('img/pcdash1.png') }}" alt="Dashboard de administración" class="hero-carousel-img">
                                </div>
                            </div>
                            <div class="col-12 d-lg-none">
                                <div class="hero-buttons hero-buttons--mobile">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-lg-6 hero-content" data-aos="fade-right">
                                <h2 class="hero-title">Vista del panel de seguridad</h2>
                                <p class="hero-description">
                                    Una interfaz clara para monitorear accesos, incidencias y novedades en tiempo real.
                                    Ideal para el equipo de seguridad y administración.
                                </p>
                                <div class="hero-buttons hero-buttons--desktop">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-phone" data-aos="fade-left">
                                <div class="hero-phone-wrapper">
                                    <img src="{{ asset('img/celu-seguridadd.png') }}" alt="Vista del dashboard" class="hero-carousel-img">
                                </div>
                            </div>
                            <div class="col-12 d-lg-none">
                                <div class="hero-buttons hero-buttons--mobile">
                                    <a class="btn btn-hero-primary" href="#caracteristicas">Conocer Más</a>
                                    <a class="btn btn-hero-secondary" href="{{ route('demo.solicitud') }}">Solicitar Demo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroShowcaseCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroShowcaseCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>



    <!-- FUNCIONALIDADES -->
    <section class="funcionalidades-section" id="caracteristicas">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="funcionalidades-title">Todas tus funciones en una sola app</h2>
                <p class="funcionalidades-subtitle">Todo lo que necesitas para gestionar tu barrio privado</p>
            </div>

            <div class="row g-4">
                <!-- Gestión de Expensas -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="funcion-title">Gestión de Expensas</h3>
                        <p class="funcion-desc">Pagos transparentes y reportes automáticos en un clic.</p>
                    </div>
                </div>

                <!-- Sistema de Invitados -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="funcion-title">Sistema de Invitados</h3>
                        <p class="funcion-desc">Control de acceso ágil mediante códigos QR seguros.</p>
                    </div>
                </div>

                <!-- Reservas -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="funcion-title">Reservas</h3>
                        <p class="funcion-desc">Reserva espacios comunes de forma rápida y organizada.</p>
                    </div>
                </div>

                <!-- Agenda de Eventos -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="400">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="funcion-title">Agenda de Eventos</h3>
                        <p class="funcion-desc">Mantente al día con todas las actividades del barrio.</p>
                    </div>
                </div>

                <!-- Mapa Interactivo -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="500">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="funcion-title">Mapa Interactivo</h3>
                        <p class="funcion-desc">Ubicación precisa de lotes y áreas comunes del country.</p>
                    </div>
                </div>

                <!-- Sistema de Incidencias -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="600">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="funcion-title">Sistema de Incidencias</h3>
                        <p class="funcion-desc">Reporta problemas de mantenimiento en tiempo real.</p>
                    </div>
                </div>

                <!-- Notificaciones -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="700">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="funcion-title">Notificaciones</h3>
                        <p class="funcion-desc">Alertas instantáneas sobre lo importante en tu celular.</p>
                    </div>
                </div>

                <!-- Novedades y Alertas -->
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="800">
                    <div class="funcion-card">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="funcion-title">Novedades y Alertas</h3>
                        <p class="funcion-desc">Comunicación directa de la administración a todos.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- COMO FUNCIONA -->
    <section class="how-it-works-section" id="como-funciona">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">¿Cómo empezar con BACOPRI?</h2>
                <p class="section-subtitle">Transformar la gestión de tu barrio es más simple de lo que pensás</p>
            </div>

            <div class="row g-4 steps-container">
                <!-- Paso 1 -->
                <div class="col-md-4 step-item" data-aos="fade-right" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <div class="step-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="step-title">Solicitás la demo</h3>
                        <p class="step-desc">Nos contactamos y evaluamos las necesidades específicas de tu comunidad para ofrecerte la mejor solución.</p>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div class="col-md-4 step-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <div class="step-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="step-title">Configuración</h3>
                        <p class="step-desc">Cargamos tus datos en la plataforma y capacitamos a tu equipo de administración y seguridad.</p>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div class="col-md-4 step-item" data-aos="fade-left" data-aos-delay="500">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <div class="step-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="step-title">Lanzamiento</h3>
                        <p class="step-desc">¡Listo! Tu barrio empieza a operar con una gestión inteligente, conectada y 100% digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Tu barrio en una sola App -->
    <section class="app-showcase-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Contenido Izquierda -->
                <div class="col-lg-6 app-showcase-content">
                    <h2 class="app-showcase-title">Tu barrio,<br>en una sola app</h2>
                    <p class="app-showcase-description">
                        Con BACOPRI podés gestionar reservas, invitados, novedades, alertas 
                        y comunicación diaria desde tu celular. Rápido, organizado y seguro.
                    </p>
                    
                    <div class="app-showcase-stores app-showcase-stores--desktop">
                        <p class="stores-label text-disponible">Disponible en:</p>
                        <div class="store-badges-row">
                            <a href="#" class="store-badge-link">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="store-badge-img">
                            </a>
                            <a href="#" class="store-badge-link">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="store-badge-img">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mockups Celulares Derecha -->
                <div class="col-lg-6 app-showcase-phones">
                    <div class="phones-container">
                        <img src="{{ asset('img/celu-arriba.png') }}" alt="BACOPRI App - Expensas" class="phone-top">
                        <img src="{{ asset('img/celu-abajo.png') }}" alt="BACOPRI App - Reservas" class="phone-bottom">
                    </div>
                </div>
                <div class="col-12 d-lg-none">
                    <div class="app-showcase-stores app-showcase-stores--mobile">
                        <p class="stores-label">Disponible en:</p>
                        <div class="store-badges-row">
                            <a href="#" class="store-badge-link">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="store-badge-img">
                            </a>
                            <a href="#" class="store-badge-link">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="store-badge-img">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PREGUNTAS FRECUENTES -->
    <section class="faq-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="faq-title">Preguntas frecuentes</h2>
                <p class="faq-subtitle">Todo lo que necesitás saber sobre BACOPRI</p>
            </div>

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        
                        <!-- Pregunta 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    ¿Qué puedo hacer desde la app?
                                </button>
                            </h3>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Podés reservar espacios (canchas, salones, restaurante), gestionar invitados con código QR, enviar comprobantes de expensas, reportar incidencias, consultar eventos, recibir alertas y acceder a contactos útiles del barrio.
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    ¿Puedo pagar las expensas desde la aplicación?
                                </button>
                            </h3>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No. La app no permite pagar expensas. Solo podés subir el comprobante de pago para que el administrador lo valide y marque como abonado.
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    ¿Cómo funciona el ingreso de invitados?
                                </button>
                            </h3>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Cargás los datos del invitado y se genera un código QR que le enviás. En el ingreso, seguridad escanea el QR y habilita o rechaza el acceso.
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    ¿Cómo realizo una reserva?
                                </button>
                            </h3>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Desde la sección de reservas elegís el espacio, seleccionas la fecha, el horario disponible y confirmas. Si el turno está libre, la reserva queda confirmada automáticamente en el momento. <br>
                                    Además, podes consultar en cualquier momento el listado de reservas que realizaste desde la misma sección.
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    ¿Cómo reporto un problema dentro del barrio?
                                </button>
                            </h3>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    En la sección de incidencias podés describir el problema (ej: luz rota, pérdida de agua) y adjuntar fotos para que administración lo gestione.
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 6 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                    ¿Recibiré notificaciones importantes?
                                </button>
                            </h3>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sí. La app envía expensas, alertas de seguridad, novedades y recordatorios de eventos si tenés activadas las notificaciones. 
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION  -->
    <section class="cta-section" id="contacto">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="zoom-y-out">
                    <h2 class="cta-title">¿Listo para transformar tu barrio?</h2>
                    <p class="cta-description">
                        Únete a los barrios que ya están disfrutando de una gestión más eficiente y moderna
                    </p>
                    <div class="mt-4">
                        <a class="btn btn-hero" href="{{ route('demo.solicitud') }}">
                            <i class="fas fa-paper-plane me-2"></i>Solicitar demostración gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</x-layout>

