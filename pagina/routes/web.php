<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\PanelAdministradorController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AlertaSeguridadController;
use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\NovedadController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SolicitarDemoController;
use App\Http\Controllers\InvitadoController;
use App\Http\Controllers\SeguridadController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\ExpensaAdminController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ReglamentoController;

// Página Home
Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');

// Solicitud de demo
Route::get('solicitar-demo', [SolicitarDemoController::class, 'solicitardemo'])->name('demo.solicitud');
Route::post('solicitar-demo', [SolicitarDemoController::class, 'enviarSolicitud'])->name('demo.enviar');

// ============================================================================
// PANEL DE ADMINISTRACIÓN
// ============================================================================
Route::prefix('admin')->group(function () {

    // ------------------------------------------------------------------
    // Login (solo si NO está logueado en ningún guard)
    // ------------------------------------------------------------------
    Route::middleware(['guest:admin', 'guest:superadmin'])->group(function () {
        Route::get('login', [AutenticacionController::class, 'showLoginForm'])->name('login.admin');
        Route::post('login', [AutenticacionController::class, 'login'])->name('login.admin.submit');
    });

    // ------------------------------------------------------------------
    // Logout (funciona para ambos guards - el controller detecta cuál cerrar)
    // ------------------------------------------------------------------
    Route::post('logout', [AutenticacionController::class, 'logout'])->name('admin.logout');

    // ------------------------------------------------------------------
    // Cambiar contraseña (funciona para ambos guards)
    // ------------------------------------------------------------------
    Route::get('cambiar-contrasenia', [AutenticacionController::class, 'showChangePasswordForm'])
        ->name('admin.cambiar-contrasenia');
    Route::post('cambiar-contrasenia', [AutenticacionController::class, 'changePassword'])
        ->name('admin.cambiar-contrasenia.submit');

    // ==================================================================
    // RUTAS EXCLUSIVAS DE SUPER ADMIN (guard: superadmin)
    // ==================================================================
    Route::middleware('superadmin')->group(function () {

        // Dashboard super admin
        Route::get('super-admin-dashboard', [PanelAdministradorController::class, 'superAdminDashboard'])
            ->name('admin.super-admin-dashboard');

        // Comunidades
        Route::resource('comunidades', ComunidadController::class)
            ->parameters(['comunidades' => 'comunidad'])
            ->names('admin.comunidades');
        Route::post('comunidades/{comunidad}/cambiar-estado', [ComunidadController::class, 'toggleStatus'])
            ->name('admin.comunidades.cambiar-estado');

        // Administradores (CRUD de admins de comunidades)
        Route::resource('administradores', AdministradorController::class)
            ->parameters(['administradores' => 'administrador'])
            ->names('admin.administradores');
        Route::post('administradores/{administrador}/cambiar-estado', [AdministradorController::class, 'toggleStatus'])
            ->name('admin.administradores.cambiar-estado');
    });

    // ==================================================================
    // RUTAS DE ADMIN DE COMUNIDAD (guard: admin)
    // ==================================================================
    Route::middleware('admin')->group(function () {

        // Dashboard admin comunidad
        Route::get('admin-comunidad-dashboard', [PanelAdministradorController::class, 'communityDashboard'])
            ->name('admin.admin-comunidad-dashboard');

        // Residentes
        Route::resource('residentes', ResidenteController::class)
            ->names('admin.residentes');
        Route::post('residentes/{residente}/cambiar-estado', [ResidenteController::class, 'toggleStatus'])
            ->name('admin.residentes.cambiar-estado');

        // Instalaciones
        Route::resource('instalaciones', InstalacionController::class)
            ->names('admin.instalaciones')
            ->parameters(['instalaciones' => 'instalacion']);

        // Mapa de instalaciones
        Route::prefix('mapa')->name('admin.mapa.')->group(function () {
            Route::get('/', [\App\Http\Controllers\MapaController::class, 'index'])->name('index');
            Route::get('create', [\App\Http\Controllers\MapaController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\MapaController::class, 'store'])->name('store');
            // Configurar barrio debe ir ANTES de {instalacion} para evitar conflictos
            Route::get('configurar-barrio', [\App\Http\Controllers\MapaController::class, 'configurarBarrio'])->name('configurar-barrio');
            Route::post('configurar-barrio', [\App\Http\Controllers\MapaController::class, 'guardarConfiguracionBarrio'])->name('guardar-configuracion-barrio');
            // Rutas con parámetros al final
            Route::get('{instalacion}', [\App\Http\Controllers\MapaController::class, 'show'])->name('show');
            Route::get('{instalacion}/edit', [\App\Http\Controllers\MapaController::class, 'edit'])->name('edit');
            Route::put('{instalacion}', [\App\Http\Controllers\MapaController::class, 'update'])->name('update');
            Route::delete('{instalacion}', [\App\Http\Controllers\MapaController::class, 'destroy'])->name('destroy');
        });
        Route::post('instalaciones/{instalacion}/cambiar-estado', [InstalacionController::class, 'toggleStatus'])
            ->name('admin.instalaciones.cambiar-estado');

        // Servicios
        Route::resource('servicios', ServicioController::class)
            ->names('admin.servicios');
        Route::post('servicios/{servicio}/cambiar-estado', [ServicioController::class, 'toggleStatus'])
            ->name('admin.servicios.cambiar-estado');

        // Novedades
        Route::resource('novedades', NovedadController::class)
            ->parameters(['novedades' => 'novedad'])
            ->names('admin.novedades');

        // Reglamentos
        Route::resource('reglamentos', ReglamentoController::class)
            ->parameters(['reglamentos' => 'reglamento'])
            ->names('admin.reglamentos');

        // Eventos
        Route::resource('eventos', EventoController::class)
            ->parameters(['eventos' => 'evento'])
            ->names('admin.eventos');

        // Alertas de seguridad
        Route::resource('alertas-seguridad', AlertaSeguridadController::class)
            ->parameters(['alertas-seguridad' => 'alerta'])
            ->names('admin.alertas-seguridad');

        // Reservas
        Route::get('reservas', [ReservaController::class, 'index'])
            ->name('admin.reservas.index');
        Route::get('reservas/{reserva}', [ReservaController::class, 'show'])
            ->name('admin.reservas.show');
        Route::post('reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])
            ->name('admin.reservas.cancelar');

        // Incidencias
        Route::get('incidencias', [IncidenciaController::class, 'index'])
            ->name('admin.incidencias.index');
        Route::get('incidencias/{incidencia}', [IncidenciaController::class, 'show'])
            ->name('admin.incidencias.show');
        Route::post('incidencias/{incidencia}/cambiar-estado', [IncidenciaController::class, 'updateEstado'])
            ->name('admin.incidencias.cambiar-estado');

        // Invitados
        Route::get('invitados', [InvitadoController::class, 'index'])
            ->name('admin.invitados.index');
        Route::get('invitados/{invitado}', [InvitadoController::class, 'show'])
            ->name('admin.invitados.show');

        // Personal de Seguridad
        Route::resource('seguridad', SeguridadController::class)
            ->parameters(['seguridad' => 'seguridad'])
            ->names('admin.seguridad');
        Route::post('seguridad/{seguridad}/cambiar-estado', [SeguridadController::class, 'toggleStatus'])
            ->name('admin.seguridad.cambiar-estado');

        // Expensas
        Route::resource('expensas', ExpensaAdminController::class)
            ->names('admin.expensas')
            ->parameters(['expensas' => 'expensa']);
        Route::post('expensas/pagos/{pago}/estado', [ExpensaAdminController::class, 'actualizarEstadoPago'])
            ->name('admin.expensas.pagos.estado');
    });
});
