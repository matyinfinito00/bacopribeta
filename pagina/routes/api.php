<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NovedadController;
use App\Http\Controllers\Api\AlertaSeguridadController;
use App\Http\Controllers\Api\InstalacionController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\InvitadoController;
use App\Http\Controllers\Api\IncidenciaController;
use App\Http\Controllers\Api\ExpensaController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\ReglamentoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\ComunidadMensajeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas (sin autenticación)
Route::post('/residente/login', [AuthController::class, 'login'])->name('api.residente.login');
Route::post('/seguridad/login', [AuthController::class, 'loginSeguridad'])->name('api.seguridad.login');

// Rutas protegidas (requieren autenticación Sanctum y comunidad activa)
Route::middleware(['auth:sanctum', 'comunidad.activa'])->group(function () {
    // Autenticación
    Route::post('/residente/logout', [AuthController::class, 'logout'])->name('api.residente.logout');
    Route::get('/residente/perfil', [AuthController::class, 'perfil'])->name('api.residente.perfil');
    Route::post('/residente/foto-perfil', [AuthController::class, 'updateFotoPerfil'])->name('api.residente.foto-perfil');
    Route::post('/residente/cambiar-password', [AuthController::class, 'cambiarPassword'])->name('api.residente.cambiar-password');
    
    // Novedades
    Route::get('/novedades', [NovedadController::class, 'index'])->name('api.novedades.index');
    Route::get('/novedades/{id}', [NovedadController::class, 'show'])->name('api.novedades.show');

    // Eventos
    Route::get('/eventos', [EventoController::class, 'index'])->name('api.eventos.index');
    Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('api.eventos.show');
    
    // Alertas de Seguridad
    Route::get('/alertas-seguridad', [AlertaSeguridadController::class, 'index'])->name('api.alertas-seguridad.index');
    Route::get('/alertas-seguridad/{id}', [AlertaSeguridadController::class, 'show'])->name('api.alertas-seguridad.show');
    
    // Instalaciones
    Route::get('/instalaciones', [InstalacionController::class, 'index'])->name('api.instalaciones.index');
    Route::get('/instalaciones/mapa', [InstalacionController::class, 'mapa'])->name('api.instalaciones.mapa');
    Route::get('/instalaciones/{id}', [InstalacionController::class, 'show'])->name('api.instalaciones.show');
    
    // Servicios
    Route::get('/servicios', [ServicioController::class, 'index'])->name('api.servicios.index');
    Route::get('/servicios/{id}', [ServicioController::class, 'show'])->name('api.servicios.show');
    
    // Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('api.reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('api.reservas.store');
    Route::post('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('api.reservas.cancelar');

    // Invitados
    Route::get('/invitados', [InvitadoController::class, 'index'])->name('api.invitados.index');
    Route::post('/invitados', [InvitadoController::class, 'store'])->name('api.invitados.store');
    
    // Rutas para seguridad (requieren autenticación como administrador de seguridad)
    Route::prefix('seguridad')->group(function () {
        Route::get('/perfil', [AuthController::class, 'perfilSeguridad'])->name('api.seguridad.perfil');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.seguridad.logout');
        Route::post('/cambiar-password', [AuthController::class, 'cambiarPasswordSeguridad'])->name('api.seguridad.cambiar-password');
        Route::get('/residentes', [InvitadoController::class, 'listarResidentes'])->name('api.seguridad.residentes');
        Route::post('/incidencias', [IncidenciaController::class, 'storeParaSeguridad'])->name('api.seguridad.incidencias.store');
        Route::get('/invitados/activos', [InvitadoController::class, 'listarActivos'])->name('api.seguridad.invitados.activos');
        Route::post('/invitados/buscar-qr', [InvitadoController::class, 'buscarPorQR'])->name('api.seguridad.invitados.buscar-qr');
        Route::post('/invitados/{invitado}/confirmar', [InvitadoController::class, 'confirmarIngreso'])->name('api.seguridad.invitados.confirmar');
        Route::post('/invitados/{invitado}/cancelar', [InvitadoController::class, 'cancelar'])->name('api.seguridad.invitados.cancelar');
        Route::post('/invitados/{invitado}/salida', [InvitadoController::class, 'registrarSalida'])->name('api.seguridad.invitados.salida');
    });

    // Incidencias
    Route::get('/incidencias', [IncidenciaController::class, 'index'])->name('api.incidencias.index');
    Route::post('/incidencias', [IncidenciaController::class, 'store'])->name('api.incidencias.store');
    Route::get('/incidencias/{id}', [IncidenciaController::class, 'show'])->name('api.incidencias.show');

    // Expensas
    Route::get('/expensas', [ExpensaController::class, 'index'])->name('api.expensas.index');
    Route::post('/expensas/{id}/comprobante', [ExpensaController::class, 'subirComprobante'])->name('api.expensas.comprobante');
    Route::delete('/expensas/{id}/comprobante', [ExpensaController::class, 'eliminarComprobante'])->name('api.expensas.comprobante.delete');

    // Reglamentos
    Route::get('/reglamento', [ReglamentoController::class, 'index'])->name('api.reglamento.index');

    // Notificaciones
    Route::get('/notificaciones/contadores', [NotificacionController::class, 'contadores'])->name('api.notificaciones.contadores');
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('api.notificaciones.index');

    // Mensajes de Comunidad
    Route::get('/comunidad/mensajes', [ComunidadMensajeController::class, 'index'])->name('api.comunidad.mensajes.index');
    Route::post('/comunidad/mensajes', [ComunidadMensajeController::class, 'store'])->name('api.comunidad.mensajes.store');
    Route::put('/comunidad/mensajes/{id}', [ComunidadMensajeController::class, 'update'])->name('api.comunidad.mensajes.update');
    Route::delete('/comunidad/mensajes/{id}', [ComunidadMensajeController::class, 'destroy'])->name('api.comunidad.mensajes.destroy');
});
