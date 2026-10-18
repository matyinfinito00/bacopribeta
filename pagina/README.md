# Guía del Proyecto BACOPRI - Sistema de Administración de Comunidades

## De qué se trata el proyecto

Nuestro proyecto trata de un **sistema de administración web para comunidades residenciales (barrios privados o countries)** desarrollado con Laravel. El sistema permite gestionar comunidades, administradores, residentes, instalaciones, servicios, novedades y alertas de seguridad. 

El sistema cuenta con dos tipos de usuarios:
- **SuperAdmin**: Tiene acceso completo al sistema, puede gestionar comunidades y administradores de todas las comunidades.
- **Administrador de Comunidad**: Gestiona únicamente su comunidad asignada, incluyendo residentes, instalaciones, servicios, novedades y alertas de seguridad.

Además, el sistema incluye una **API REST** para que los residentes puedan acceder a información mediante una aplicación móvil, utilizando autenticación mediante Laravel Sanctum.



## Funcionalidades implementadas


### Panel de Administración Web

1. **Autenticación**
   - Sistema de login para administradores
   - Logout seguro
   - Middleware de autenticación y autorización por roles

2. **Gestión de Comunidades** (Solo SuperAdmin)
   - Crear, editar, listar y eliminar comunidades
   - Activar/desactivar comunidades
   - Visualización de información completa de cada comunidad

3. **Gestión de Administradores** (Solo SuperAdmin)
   - Crear, editar, listar y eliminar administradores
   - Asignar administradores a comunidades
   - Activar/desactivar administradores

4. **Gestión de Residentes** (Administradores de Comunidad)
   - CRUD completo de residentes
   - Activar/desactivar residentes
   - Gestión de información personal (nombre, correo, teléfono, dirección, documento, etc.)

5. **Gestión de Instalaciones** (Administradores de Comunidad)
   - CRUD completo de instalaciones (piscinas, gimnasios, salones, etc.)
   - Activar/desactivar instalaciones

6. **Gestión de Servicios** (Administradores de Comunidad)
   - CRUD completo de servicios
   - Activar/desactivar servicios

7. **Gestión de Novedades** (Administradores de Comunidad)
   - Crear, editar, listar y eliminar novedades
   - Publicar información relevante para los residentes

8. **Gestión de Alertas de Seguridad** (Administradores de Comunidad)
   - Crear, editar, listar y eliminar alertas de seguridad
   - Notificar a residentes sobre situaciones de seguridad

9. **Páginas adicionales** (vistas preparadas)
   - Reservas
   - Incidencias
   - Invitados

10. **Página pública**
    - Home page
    - Formulario de solicitud de 
    

### API REST para Residentes

1. **Autenticación**
   - Login de residentes (`POST /api/residente/login`)
   - Logout (`POST /api/residente/logout`)
   - Consultar perfil (`GET /api/residente/perfil`)

2. **Novedades**
   - Listar novedades (`GET /api/novedades`)
   - Ver detalle de novedad (`GET /api/novedades/{id}`)

3. **Alertas de Seguridad**
   - Listar alertas de seguridad (`GET /api/alertas-seguridad`)
   - Ver detalle de alerta (`GET /api/alertas-seguridad/{id}`)



## Tecnologías utilizadas

### Backend

- **Laravel 12.0**: Framework PHP para desarrollo web
- **Laravel Sanctum 4.2**: Autenticación API mediante tokens

### Frontend
- **Bootstrap**: Framework CSS 



## Comandos necesarios para instalar las tecnologías

- **Composer**: Gestor de dependencias de PHP
- **Laravel**: Framework PHP (se instala mediante Composer)



## Pasos necesarios para correr el proyecto

1. **Ejecutar migraciones y seeders**
 
   php artisan migrate --seed
   

2. **Iniciar el servidor**
   
   php artisan serve
 

3. **Acceder al sitio**
   - Sitio web: http://localhost:8000
   
   **Usuarios creados por los seeders:**
   
   - **SuperAdmin** (acceso completo):
     - Correo: `tomas@bacopri.com` - Contraseña: `password123`
     - Correo: `matias@bacopri.com` - Contraseña: `password123`
   
   - **Administrador de Comunidad** (acceso limitado a su comunidad):
     - Correo: `admin@nordelta.com` - Contraseña: `password123`
   
   - **Residente** (acceso mediante API):
     - Correo: `residente@nordelta.com` - Contraseña: `password123`
     - Nombre: Carina Carballido
 

