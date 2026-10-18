<x-layout title="Solicitar Demo">

   <!-- HERO SECTION CON FORMULARIO (form abajo del texto, layout horizontal) -->
<section class="demo-hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-13">
                <h1 class="demo-hero-title text-start">Descubrí BACOPRI en acción</h1>
                <p class="demo-hero-description text-start">
                    Coordinamos una demostración personalizada para conocer tu barrio, mostrarte cómo funciona
                    la plataforma y diseñar un plan de implementación a medida.
                </p>

                @if(session('success'))
                <div class="alert alert-success shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif

                <div class="demo-form-card mt-4">
                    <h2 class="demo-form-title">Contanos sobre tu comunidad</h2>
                    <p class="demo-form-subtitle">Completá el formulario y coordinamos la demo en menos de 24 horas hábiles.</p>

                    <form method="POST" action="{{ route('demo.enviar') }}">
                        @csrf

                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre y apellido *</label>
                                <input type="text" name="nombre" id="nombre" class="form-control demo-input" placeholder="Ej: Ana Pérez" required>
                            </div>

                            <div class="col-md-6">
                                <label for="correo" class="form-label">Correo electrónico *</label>
                                <input type="email" name="correo" id="correo" class="form-control demo-input" placeholder="tu-email@ejemplo.com" required>
                            </div>

                            <div class="col-md-4">
                                <label for="telefono" class="form-label">Teléfono de contacto</label>
                                <input type="text" name="telefono" id="telefono" class="form-control demo-input" placeholder="+54 11 5555 5555">
                            </div>

                            <div class="col-md-4">
                                <label for="cargo" class="form-label">Cargo / Rol</label>
                                <input type="text" name="cargo" id="cargo" class="form-control demo-input" placeholder="Administrador, Presidente, etc.">
                            </div>

                            <div class="col-md-4">
                                <label for="tamano_comunidad" class="form-label">Cantidad de unidades</label>
                                <select name="tamano_comunidad" id="tamano_comunidad" class="form-select demo-input">
                                    <option value="" selected>Seleccioná una opción</option>
                                    <option value="menos_50">Hasta 50</option>
                                    <option value="50_150">50 a 150</option>
                                    <option value="150_300">150 a 300</option>
                                    <option value="mas_300">Más de 300</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Preferencia de contacto</label>
                                <div class="demo-contact-options-grid">
                                    <input type="radio" class="btn-check" name="preferencia_contacto"
                                        id="preferencia_contacto_llamada" value="llamada">
                                    <label class="demo-radio-btn" for="preferencia_contacto_llamada">
                                        <i class="fas fa-phone me-2"></i>Llamada
                                    </label>

                                    <input type="radio" class="btn-check" name="preferencia_contacto"
                                        id="preferencia_contacto_whatsapp" value="whatsapp">
                                    <label class="demo-radio-btn" for="preferencia_contacto_whatsapp">
                                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                    </label>

                                    <input type="radio" class="btn-check" name="preferencia_contacto"
                                        id="preferencia_contacto_email" value="email">
                                    <label class="demo-radio-btn" for="preferencia_contacto_email">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="mensaje" class="form-label">Contanos qué necesitás</label>
                                <textarea name="mensaje" id="mensaje" rows="3" class="form-control demo-input"
                                    placeholder="¿Qué desafíos querés resolver con BACOPRI?"></textarea>
                            </div>

                            <div class="col-12 demo-form-actions-left">
                                <button type="submit" class="btn demo-submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar solicitud
                                </button>
                                <p class="demo-form-note mt-3 mb-0">
                                    Al enviar tus datos aceptás que el equipo de BACOPRI se comunique con vos para coordinar la demo.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-2 d-none d-lg-block"></div>
        </div>
    </div>
</section>

<!-- SECCIÓN DE PROCESO -->
<section class="demo-process-section">
    <div class="container">
        <div class="demo-process-heading">
            <h2 class="demo-process-title">Cómo funciona la demo</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="demo-process-card">
                    <div class="icon-circle mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="process-title">Proceso ágil</h3>
                    <p class="process-description">Coordinamos la demo en tres pasos: relevamiento, recorrido guiado
                        y propuesta personalizada para tu comunidad.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="demo-process-card">
                    <div class="icon-circle mb-3">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3 class="process-title">Equipo especializado</h3>
                    <p class="process-description">Te acompaña un especialista en barrios privados que conoce las
                        mejores prácticas de implementación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="demo-process-card">
                    <div class="icon-circle mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="process-title">Seguridad y soporte</h3>
                    <p class="process-description">Infraestructura segura, backup automático y soporte local 24/7
                        para que tu barrio nunca se detenga.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN DE BENEFICIOS -->
<section class="demo-benefits-section">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-6">
                <h2 class="demo-benefits-title">Lo que incluye la demostración</h2>
                <ul class="demo-benefits-checklist">
                    <li><i class="fas fa-angle-right"></i>Panel administrativo completo</li>
                    <li><i class="fas fa-angle-right"></i>App móvil para residentes</li>
                    <li><i class="fas fa-angle-right"></i>Recorrida por módulos de reservas, incidencias y servicios</li>
                    <li><i class="fas fa-angle-right"></i>Reporte ejecutivo con recomendaciones</li>
                </ul>
            </div>

            <div class="col-lg-6">
                <div class="demo-faq-card">
                    <h2 class="demo-benefits-title">Preguntas frecuentes</h2>

                    <div class="faq-item">
                        <p class="faq-question">¿Cuánto dura la demo?</p>
                        <p class="faq-answer">Entre 30 y 45 minutos. Podemos adaptarnos a la agenda de tu equipo.</p>
                    </div>

                    <div class="faq-item">
                        <p class="faq-question">¿Tiene costo?</p>
                        <p class="faq-answer">No. La demo y el diagnóstico inicial son totalmente gratuitos.</p>
                    </div>

                    <div class="faq-item">
                        <p class="faq-question">¿Necesito instalar algo?</p>
                        <p class="faq-answer">Solo una conexión a internet. Realizamos la demo por videollamada
                            o de manera presencial.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN DE TESTIMONIOS -->

<section class="demo-testimonials-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <h2 class="demo-testimonials-title">Testimonios y resultados</h2>
                <p class="demo-testimonials-subtitle">Barrios y countries que ya trabajan con BACOPRI destacan la mejora en la
                    comunicación, la trazabilidad de incidencias y la adopción de la app por parte de los
                    residentes.</p>
            </div>
            <div class="col-lg-7">
                <div id="testimoniosCarousel" class="carousel slide demo-carousel"
                    data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-indicators">
                        @foreach($testimonios as $index => $testimonio)
                        <button type="button" data-bs-target="#testimoniosCarousel"
                            data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Testimonio {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($testimonios as $index => $testimonio)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="demo-testimonial-card">
                                <i class="fas fa-quote-left testimonial-quote"></i>
                                <p class="testimonial-text">{!! $testimonio['mensaje'] !!}</p>
                                <div class="testimonial-author">
                                    <span class="author-name">{{ $testimonio['nombre'] }}</span>
                                    <span class="author-community">{{ $testimonio['comunidad'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimoniosCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimoniosCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layout>
