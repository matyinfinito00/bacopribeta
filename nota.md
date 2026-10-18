Guía del Proyecto BACOPRI - App móvil para residentes
De qué se trata el proyecto
Aplicación móvil hecha con Expo + React Native para residentes de barrios privados. Se conecta a la API Laravel de BACOPRI para permitir a los vecinos ver novedades, alertas de seguridad y gestionar su relación con la comunidad desde el teléfono. La app mantiene la sesión del residente, consume la API REST.

Funcionalidades implementadas
Autenticación de residentes contra la API (/api/residente/login), guarda token y perfil en AsyncStorage y bloquea pantallas si no hay sesión.
Inicio con accesos rápidos a reservas, invitados, incidencias, expensas, contactos, alertas, mapa y eventos desde app/(tabs)/index.tsx.
Novedades: listado con pull-to-refresh, estados de carga/error y vista detalle por id.
Alertas de seguridad: listado con prioridades (baja, media, alta, urgente) y vista detalle por id.
Configuración: muestra datos básicos del residente/comunidad, cierre de sesión y accesos a edición de datos, métodos de pago, reglamento y contactos.
Ayuda y soporte: centro de FAQ filtrables y contacto directo a soporte@bacopri.com.
Secciones preparadas para completar con lógica de negocio: reservas, comunidad, invitados, incidencias, expensas, notificaciones, mapa y eventos.
API que consume
Autenticación: POST /api/residente/login, POST /api/residente/logout, GET /api/residente/perfil.
Contenido: GET /api/novedades, GET /api/novedades/{id}, GET /api/alertas-seguridad, GET /api/alertas-seguridad/{id}.
Todas las peticiones autenticadas envían el token Bearer almacenado en AsyncStorage.
Tecnologías utilizadas
Frontend móvil: Expo, React Native Librerias: @expo/vector-icons, react-native-heroicons, react-native-reanimated.
Persistencia y utilidades: AsyncStorage para tokens/usuario, fetch nativo con helpers en services/api.ts.
Backend esperado: API Laravel con Laravel Sanctum; la URL base se configura en services/api.ts (BASE_URL).
Requisitos previos
Node.js y npm instalados.
Expo CLI (npm install -g expo-cli) o Expo Go en tu dispositivo para probar.
Instalación
Instala dependencias del proyecto: npm install.
Ejecución
Servidor de desarrollo: npx expo start.
Estructura principal
app/_layout.tsx: layout raíz y guardia de navegación según autenticación.
app/login.tsx: formulario de inicio de sesión y validaciones locales.
app/(tabs)/: navegación por pestañas e inicio con accesos rápidos.
app/novedades*.tsx: listados y detalle de novedades desde la API.
app/alertas*.tsx: listados y detalle de alertas con prioridades.
app/configuraciones*.tsx: perfil básico, cierre de sesión y ayuda/FAQ.
contexts/AuthContext.tsx: estado global de sesión y recuperación desde storage.
services/api.ts y services/storage.ts: cliente REST y persistencia de token/usuario.