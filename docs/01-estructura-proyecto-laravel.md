# 📁 Estructura de Carpetas de Laravel 12

## 🏗️ **Estructura General del Proyecto**

Laravel sigue una estructura de carpetas bien organizada que facilita el desarrollo y mantenimiento de aplicaciones. Cada carpeta tiene un propósito específico y está diseñada para mantener el código organizado y fácil de navegar:

```
IngenieriaDeSoftwareAvanzada-CodigoLaravel/
├── app/                    # Lógica principal de la aplicación (controladores, modelos, servicios)
├── bootstrap/              # Archivos de arranque y configuración inicial
├── config/                 # Archivos de configuración de la aplicación
├── database/               # Migraciones, seeders y factories para la base de datos
├── public/                 # Punto de entrada y assets públicos (CSS, JS, imágenes)
├── resources/              # Vistas, assets sin compilar (Blade templates, Sass, JS)
├── routes/                 # Definición de rutas de la aplicación
├── storage/                # Archivos generados por la aplicación (logs, cache, uploads)
├── tests/                  # Tests automatizados (unitarios y de integración)
├── vendor/                 # Dependencias de Composer (paquetes PHP)
├── .env                    # Variables de entorno (configuración local)
├── .env.example           # Ejemplo de variables de entorno (plantilla)
├── artisan                # Comando CLI de Laravel (herramientas de desarrollo)
├── composer.json          # Dependencias de PHP y configuración del proyecto
├── package.json           # Dependencias de Node.js (frontend)
└── vite.config.js         # Configuración de Vite (bundler de assets)
```

**Explicación de la estructura:**
- **app/**: Contiene toda la lógica de negocio de tu aplicación (controladores, modelos, servicios)
- **bootstrap/**: Archivos necesarios para iniciar Laravel (configuración inicial del framework)
- **config/**: Todos los archivos de configuración de la aplicación (base de datos, email, cache, etc.)
- **database/**: Todo lo relacionado con la base de datos (migraciones, datos de prueba, factories)
- **public/**: Punto de entrada público - solo los archivos aquí son accesibles desde el navegador
- **resources/**: Archivos de frontend sin compilar (vistas Blade, CSS, JavaScript)
- **routes/**: Definición de todas las URLs de tu aplicación
- **storage/**: Archivos generados por la aplicación (logs, cache, archivos subidos)
- **tests/**: Pruebas automatizadas para verificar que tu aplicación funciona correctamente
- **vendor/**: Dependencias de PHP instaladas por Composer (no se edita manualmente)
- **.env**: Variables de entorno específicas de tu entorno (desarrollo, producción)
- **.env.example**: Plantilla de variables de entorno para otros desarrolladores
- **artisan**: Comando CLI que te permite ejecutar comandos de Laravel
- **composer.json**: Define las dependencias de PHP y configuración del proyecto
- **package.json**: Define las dependencias de JavaScript/Node.js para el frontend
- **vite.config.js**: Configuración del bundler Vite que compila los assets frontend

## 📂 **Carpetas Principales Explicadas**

### 🎯 **app/ - Lógica de la Aplicación**

Esta es la carpeta más importante de Laravel, contiene toda la lógica de negocio de tu aplicación. Es donde pasarás la mayor parte del tiempo desarrollando:

```
app/
├── Console/               # Comandos Artisan personalizados (tareas programadas)
├── Exceptions/            # Manejo de excepciones (personalización de errores)
├── Http/                  # Lógica HTTP (todo lo relacionado con peticiones web)
│   ├── Controllers/       # Controladores (manejan las peticiones HTTP)
│   ├── Middleware/        # Middleware personalizado (filtros de peticiones)
│   └── Requests/          # Form Requests (validación de formularios)
├── Models/                # Modelos Eloquent (interacción con la base de datos)
├── Providers/             # Service Providers (configuración de servicios)
└── Services/              # Servicios de negocio (lógica compleja reutilizable)
```

**Explicación detallada de cada carpeta:**

**Console/**: Contiene comandos personalizados que puedes ejecutar con `php artisan`. Útil para tareas programadas, mantenimiento y automatización.

**Exceptions/**: Aquí puedes personalizar cómo Laravel maneja los errores. Puedes crear páginas de error personalizadas o manejar excepciones específicas.

**Http/Controllers/**: Los controladores son el "cerebro" de tu aplicación. Reciben las peticiones HTTP, procesan la lógica y devuelven respuestas. Cada controlador maneja una funcionalidad específica.

**Http/Middleware/**: Middleware son filtros que se ejecutan antes o después de las peticiones. Pueden verificar autenticación, validar datos, modificar respuestas, etc.

**Http/Requests/**: Form Requests encapsulan la validación de formularios. Te permiten centralizar las reglas de validación y hacer el código más limpio.

**Models/**: Los modelos representan las tablas de tu base de datos. Contienen la lógica de interacción con la BD y las relaciones entre tablas.

**Providers/**: Service Providers configuran servicios de Laravel. Aquí registras bindings, configuras servicios externos, etc.

**Services/**: Contienen lógica de negocio compleja que no debe estar en controladores. Ayudan a mantener el código organizado y reutilizable.

### 🌐 **public/ - Punto de Entrada**

Esta carpeta es el punto de entrada público de tu aplicación. Solo los archivos en esta carpeta son accesibles directamente desde el navegador. Es la única carpeta que debe ser pública en tu servidor:

```
public/
├── index.php             # Punto de entrada principal (todas las peticiones pasan por aquí)
├── favicon.ico          # Icono del sitio (aparece en la pestaña del navegador)
├── robots.txt           # Configuración para crawlers (buscadores como Google)
└── assets/              # Archivos públicos (CSS, JS, imágenes compilados)
```

**Explicación detallada:**

**index.php**: Este es el archivo más importante. Todas las peticiones HTTP pasan por aquí. Laravel usa este archivo para iniciar la aplicación y procesar las peticiones. Es como el "portero" de tu aplicación.

**favicon.ico**: El icono que aparece en la pestaña del navegador. Ayuda a identificar tu sitio web.

**robots.txt**: Archivo que le dice a los buscadores (como Google) qué páginas pueden indexar y cuáles no. Importante para SEO.

**assets/**: Carpeta donde se guardan los archivos compilados (CSS, JavaScript, imágenes) que se sirven directamente al navegador.

**Importante:** Por seguridad, solo esta carpeta debe ser accesible públicamente. El resto de carpetas contienen código sensible que no debe estar expuesto.

### 🎨 **resources/ - Recursos de Frontend**

Esta carpeta contiene todos los archivos de frontend que necesitan ser compilados antes de ser utilizados. Los archivos aquí no son accesibles directamente desde el navegador:

```
resources/
├── css/                  # Archivos CSS sin compilar (Sass, Less, CSS puro)
├── js/                   # Archivos JavaScript (Vue, React, JS puro)
├── views/                # Plantillas Blade (vistas de la aplicación)
│   ├── components/       # Componentes Blade (elementos reutilizables)
│   ├── layouts/          # Layouts principales (estructura base de las páginas)
│   └── pages/            # Vistas de páginas (contenido específico)
├── lang/                 # Archivos de idioma (traducciones)
└── sass/                 # Archivos Sass (preprocesador CSS)
```

**Explicación detallada:**

**css/**: Contiene archivos CSS sin compilar. Puedes usar Sass, Less o CSS puro. Estos archivos se compilan y se mueven a public/assets/.

**js/**: Archivos JavaScript de tu aplicación. Puedes usar frameworks como Vue.js o React, o JavaScript puro.

**views/**: Contiene todas las vistas Blade de tu aplicación. Las vistas son las plantillas HTML que se renderizan para mostrar contenido al usuario.

**views/components/**: Componentes Blade reutilizables. Son como "piezas" de HTML que puedes usar en múltiples páginas.

**views/layouts/**: Layouts principales que definen la estructura base de tus páginas (header, footer, navegación).

**views/pages/**: Vistas específicas de cada página de tu aplicación.

**lang/**: Archivos de traducción para hacer tu aplicación multiidioma.

**sass/**: Archivos Sass (preprocesador CSS) que te permiten usar variables, mixins y funciones en CSS.

**Nota importante:** Los archivos en esta carpeta no son accesibles directamente desde el navegador. Deben ser compilados y movidos a la carpeta `public/` usando herramientas como Vite o Laravel Mix.

### 🗄️ **database/ - Base de Datos**

Esta carpeta contiene todo lo relacionado con la base de datos, desde la estructura hasta los datos de prueba. Es fundamental para el control de versiones de tu base de datos:

```
database/
├── factories/            # Factories para datos de prueba (generación automática de datos)
├── migrations/           # Migraciones de base de datos (control de versiones de la BD)
├── seeders/              # Seeders para poblar BD (datos iniciales de la aplicación)
└── .gitignore           # Archivos ignorados por Git (configuración local)
```

**Explicación detallada:**

**factories/**: Contienen clases que generan datos de prueba automáticamente. Útiles para crear datos realistas para testing y desarrollo.

**migrations/**: Archivos que definen cambios en la estructura de tu base de datos. Son como un "control de versiones" para tu BD. Te permiten crear, modificar y eliminar tablas de manera programática.

**seeders/**: Clases que insertan datos iniciales en tu base de datos. Útiles para crear datos de prueba o datos por defecto de la aplicación.

**Importante:** Las migraciones son fundamentales porque te permiten:
- Mantener un historial de cambios en tu base de datos
- Reproducir la misma estructura en diferentes entornos (desarrollo, producción)
- Revertir cambios si es necesario
- Colaborar con otros desarrolladores sin conflictos

### ⚙️ **config/ - Configuración**

Esta carpeta contiene todos los archivos de configuración de Laravel. Cada archivo controla un aspecto específico de la aplicación:

```
config/
├── app.php              # Configuración general (nombre, entorno, timezone)
├── auth.php             # Configuración de autenticación (providers, guards)
├── database.php         # Configuración de BD (conexiones, drivers)
├── filesystems.php      # Configuración de archivos (discos, drivers)
├── mail.php             # Configuración de email (SMTP, drivers)
├── queue.php            # Configuración de colas (jobs, workers)
├── session.php          # Configuración de sesiones (driver, lifetime)
└── cache.php            # Configuración de cache (drivers, stores)
```

**Explicación detallada de cada archivo:**

**app.php**: Configuración general de la aplicación como nombre, entorno, zona horaria, locale, etc.

**auth.php**: Configuración de autenticación y autorización. Define cómo se autentican los usuarios, qué guards usar, etc.

**database.php**: Configuración de conexiones a bases de datos. Define las credenciales y configuraciones para MySQL, PostgreSQL, etc.

**filesystems.php**: Configuración para manejo de archivos. Define discos locales, en la nube (S3, Google Cloud), etc.

**mail.php**: Configuración de envío de emails. Define servidores SMTP, drivers de email, etc.

**queue.php**: Configuración de colas de trabajo. Para procesar tareas en segundo plano.

**session.php**: Configuración de sesiones de usuario. Define dónde guardar las sesiones, tiempo de vida, etc.

**cache.php**: Configuración de cache. Define drivers de cache (Redis, Memcached, archivos), etc.

**Nota importante:** Los valores en estos archivos pueden ser sobrescritos por variables de entorno en el archivo `.env`. Esto te permite tener configuraciones diferentes para desarrollo y producción.

### 🛣️ **routes/ - Definición de Rutas**

Esta carpeta contiene todos los archivos que definen las rutas de tu aplicación. Las rutas son las URLs que los usuarios pueden visitar:

```
routes/
├── web.php              # Rutas web (con sesión, CSRF, middleware web)
├── api.php              # Rutas API (sin sesión, para aplicaciones móviles/frontend)
├── console.php          # Comandos Artisan (tareas de consola)
└── channels.php         # Canales de broadcasting (WebSockets, eventos en tiempo real)
```

**Explicación detallada:**

**web.php**: Contiene rutas para navegadores web. Estas rutas incluyen autenticación, sesiones, protección CSRF y middleware web. Son las rutas que usan los usuarios normales de tu aplicación.

**api.php**: Contiene rutas para APIs. Estas rutas no incluyen sesiones web, sino que usan tokens de autenticación. Son para aplicaciones móviles, frontend JavaScript, o integraciones con otros sistemas.

**console.php**: Define comandos personalizados que puedes ejecutar con `php artisan`. Útil para tareas de mantenimiento, importación de datos, etc.

**channels.php**: Configuración para comunicación en tiempo real usando WebSockets. Para chat, notificaciones en tiempo real, etc.

## 🔄 **Flujo de Funcionamiento Básico**

Cuando un usuario visita tu aplicación Laravel, sigue este flujo paso a paso. Es importante entender este flujo para saber cómo funciona Laravel internamente:

### 1. **Petición HTTP llega**
```
Usuario → public/index.php
```
**Explicación:** El navegador envía una petición HTTP que llega al archivo `index.php` en la carpeta `public/`. Este archivo es el único punto de entrada público de tu aplicación.

### 2. **Bootstrap de la aplicación**
```
index.php → bootstrap/app.php
```
**Explicación:** Laravel inicia la aplicación cargando el archivo `bootstrap/app.php` que configura el contenedor de servicios, carga las configuraciones básicas y prepara el framework para procesar la petición.

### 3. **Carga de configuración**
```
app.php → config/ → .env
```
**Explicación:** Laravel carga todas las configuraciones desde los archivos en `config/` y las variables de entorno en `.env`. Esto incluye configuración de base de datos, email, cache, etc.

### 4. **Enrutamiento**
```
Router → routes/web.php → Controller
```
**Explicación:** Laravel busca en los archivos de rutas (web.php, api.php) para encontrar qué controlador debe manejar la petición. Compara la URL con las rutas definidas.

### 5. **Procesamiento**
```
Controller → Model → Database
```
**Explicación:** El controlador ejecuta la lógica de negocio, interactúa con los modelos y estos acceden a la base de datos. Aquí es donde se procesa la información y se toman las decisiones de la aplicación.

### 6. **Respuesta**
```
View → Response → Usuario
```
**Explicación:** El controlador devuelve una vista (HTML) o respuesta JSON que se envía de vuelta al usuario. Laravel renderiza la vista y envía la respuesta HTTP.

## 🎯 **Ejemplo Práctico: Flujo Completo**

Veamos un ejemplo completo de cómo funciona Laravel cuando un usuario visita la página de servicios. Este ejemplo te ayudará a entender cómo todos los componentes trabajan juntos:

```php
// 1. Usuario visita: /servicios
// El navegador envía una petición GET a la URL /servicios
// Laravel recibe esta petición en public/index.php

// 2. routes/web.php
Route::get('/servicios', [ServicioController::class, 'index']);
// Laravel encuentra esta ruta y sabe que debe llamar al método 'index' del ServicioController
// El enrutador de Laravel compara la URL '/servicios' con las rutas definidas

// 3. app/Http/Controllers/ServicioController.php
public function index()
{
    $servicios = Servicio::all(); // Obtiene todos los servicios de la base de datos
    return view('servicios.index', compact('servicios')); // Devuelve la vista con los datos
}
// El controlador ejecuta la lógica de negocio:
// - Llama al modelo Servicio para obtener datos
// - Pasa los datos a la vista
// - Devuelve la respuesta

// 4. app/Models/Servicio.php
class Servicio extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio']; // Campos que se pueden llenar masivamente
}
// El modelo:
// - Representa la tabla 'servicios' en la base de datos
// - Proporciona métodos para interactuar con la BD
// - Define qué campos se pueden llenar masivamente por seguridad

// 5. resources/views/servicios/index.blade.php
@extends('layouts.app') // Extiende el layout principal
@section('content')
    @foreach($servicios as $servicio) // Itera sobre cada servicio
        <div>{{ $servicio->nombre }}</div> // Muestra el nombre de cada servicio
    @endforeach
@endsection
// La vista:
// - Recibe los datos del controlador
// - Renderiza el HTML final
// - Usa la sintaxis Blade para mostrar datos dinámicamente
```

**Explicación detallada del flujo:**

1. **Usuario visita la URL** → El navegador envía una petición HTTP a `/servicios`
2. **Laravel recibe la petición** → `public/index.php` recibe la petición y inicia Laravel
3. **Enrutamiento** → Laravel busca en `routes/web.php` y encuentra la ruta que coincide
4. **Controlador** → Se ejecuta el método `index()` del `ServicioController`
5. **Modelo** → El controlador llama al modelo `Servicio` que consulta la base de datos
6. **Base de datos** → Se ejecuta la consulta SQL para obtener todos los servicios
7. **Vista** → El controlador pasa los datos a la vista `servicios.index`
8. **Renderizado** → Laravel renderiza la vista Blade y genera el HTML final
9. **Respuesta** → Se envía el HTML al navegador del usuario

## 🔧 **Archivos de Configuración Importantes**

### **.env - Variables de Entorno**

El archivo `.env` es crucial para la configuración de tu aplicación. Contiene variables específicas de tu entorno (desarrollo, producción, testing):

```env
APP_NAME="Mi Aplicación"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mi_base_datos
DB_USERNAME=root
DB_PASSWORD=
```

**Explicación de cada variable:**

**APP_NAME**: Nombre de tu aplicación que aparece en emails, títulos de página, etc.

**APP_ENV**: Entorno de la aplicación (local, production, testing). Afecta el comportamiento de Laravel.

**APP_DEBUG**: Si está en `true`, muestra errores detallados. En producción debe ser `false` por seguridad.

**APP_URL**: URL base de tu aplicación. Usado para generar URLs absolutas.

**DB_CONNECTION**: Tipo de base de datos (mysql, pgsql, sqlite, etc.).

**DB_HOST**: Dirección del servidor de base de datos.

**DB_PORT**: Puerto de la base de datos (3306 para MySQL, 5432 para PostgreSQL).

**DB_DATABASE**: Nombre de la base de datos.

**DB_USERNAME**: Usuario de la base de datos.

**DB_PASSWORD**: Contraseña de la base de datos.

**Importante:** Nunca subas el archivo `.env` a Git. Usa `.env.example` como plantilla.

### **composer.json - Dependencias PHP**

Este archivo define las dependencias de PHP y la configuración del proyecto:

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

**Explicación:**

**require**: Lista las dependencias que tu aplicación necesita para funcionar.

**php**: "^8.2" significa que requiere PHP 8.2 o superior, pero menor a 9.0.

**laravel/framework**: El framework Laravel y su versión.

**autoload**: Configuración de autoloading (carga automática de clases).

**psr-4**: Estándar de autoloading que mapea namespaces a directorios.

**"App\\": "app/"**: Las clases con namespace `App\` se cargan desde la carpeta `app/`.

### **package.json - Dependencias Frontend**

Este archivo define las dependencias de JavaScript/Node.js para el frontend:

```json
{
    "devDependencies": {
        "tailwindcss": "^3.4.0",
        "vite": "^5.0.0"
    }
}
```

**Explicación:**

**devDependencies**: Dependencias solo necesarias para desarrollo (no en producción).

**tailwindcss**: Framework CSS para crear interfaces rápidamente.

**vite**: Bundler moderno que compila y optimiza assets frontend.

**Importante:** Estas dependencias se instalan con `npm install` y se usan para compilar CSS y JavaScript.

## 🎯 **Buenas Prácticas**

### ✅ **Organización de Carpetas**

**Mantener app/ limpio y organizado:**
- Separa la lógica por funcionalidad
- Usa subcarpetas cuando sea necesario
- Mantén los controladores delgados

**Usar Services/ para lógica de negocio compleja:**
- Mueve lógica compleja de controladores a servicios
- Los servicios son reutilizables
- Facilita el testing

**Separar Controllers/ por funcionalidad:**
- Un controlador por recurso principal
- Usa subcarpetas para organizar (Admin/, Api/)
- Mantén métodos pequeños y enfocados

**Crear subcarpetas en views/ para organizar:**
- Agrupa vistas por funcionalidad
- Usa layouts para estructura común
- Crea componentes para elementos reutilizables

### ✅ **Nomenclatura**

**Controllers**: Usa PascalCase y termina en "Controller"
- `ServicioController` - Maneja operaciones de servicios
- `UserController` - Maneja operaciones de usuarios

**Models**: Usa PascalCase en singular
- `Servicio` - Representa la tabla "servicios"
- `User` - Representa la tabla "users"

**Views**: Usa kebab-case y organiza por funcionalidad
- `servicios.index` - Lista de servicios
- `users.show` - Detalle de usuario

**Routes**: Usa nombres descriptivos
- `servicios.index` - Ruta para listar servicios
- `users.store` - Ruta para crear usuarios

### ✅ **Archivos de Configuración**

**Nunca commitear .env:**
- El archivo `.env` contiene información sensible
- Usa `.env.example` como plantilla
- Cada desarrollador debe crear su propio `.env`

**Mantener config/ organizado:**
- Cada archivo de configuración tiene un propósito específico
- Documenta configuraciones complejas
- Usa variables de entorno cuando sea posible

**Documentar variables de entorno importantes:**
- Explica qué hace cada variable en `.env.example`
- Incluye valores de ejemplo
- Documenta configuraciones específicas del proyecto

## 🚀 **Comandos Útiles para Explorar**

Estos comandos te ayudarán a explorar y entender tu aplicación Laravel:

```bash
# Ver estructura de carpetas
tree -L 2
# Muestra la estructura de carpetas de tu proyecto de manera visual
# -L 2 significa mostrar solo 2 niveles de profundidad

# Explorar configuración
php artisan config:show
# Muestra todas las configuraciones de Laravel
# Útil para verificar qué valores están activos

# Ver rutas registradas
php artisan route:list
# Muestra todas las rutas de tu aplicación
# Incluye método HTTP, URL, nombre y controlador

# Ver servicios disponibles
php artisan list
# Muestra todos los comandos Artisan disponibles
# Incluye comandos integrados y personalizados
```

**Explicación de cada comando:**

**tree -L 2**: Te da una vista visual de la estructura de carpetas. Útil para entender la organización del proyecto.

**php artisan config:show**: Muestra todas las configuraciones activas de Laravel. Te ayuda a verificar qué valores están siendo usados.

**php artisan route:list**: Lista todas las rutas registradas en tu aplicación. Esencial para entender qué URLs están disponibles.

**php artisan list**: Muestra todos los comandos Artisan disponibles. Te ayuda a descubrir herramientas útiles de Laravel.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 

## 🎯 **Opciones de Commit en Español:**

### ✅ **Opción Recomendada (Más Descriptiva):**
```bash
git add .
git commit -m "feat: Mejorar documentación de estructura de Laravel 12

- Agregar explicaciones detalladas para cada carpeta y archivo
- Incluir descripción del flujo de funcionamiento completo
- Añadir explicaciones de archivos de configuración importantes
- Mejorar buenas prácticas con ejemplos específicos
- Hacer la documentación accesible para principiantes"
```

### ✅ **Opción Corta y Clara:**
```bash
git add .
git commit -m "feat: Mejorar documentación de estructura Laravel 12 con explicaciones detalladas"
```

### ✅ **Opción Simple:**
```bash
git add .
git commit -m "feat: Mejorar documentación estructura Laravel 12"
```

## 🚀 **Comandos para Subir a GitHub:**

```bash
# 1. Agregar todos los archivos
git add .

# 2. Hacer el commit (usa una de las opciones de arriba)
git commit -m "feat: Mejorar documentación de estructura Laravel 12 con explicaciones detalladas"

# 3. Agregar el repositorio remoto (si no lo has hecho)
git remote add origin https://github.com/CristianMorales1999/IngenieriaDeSoftwareAvanzada-CodigoLaravel.git

# 4. Subir al repositorio
git push -u origin main
```

## 🎯 **Prefijos de Commit en Español:**

Para futuros commits, puedes usar estos prefijos:
- **feat:** Nueva funcionalidad
- **fix:** Corrección de errores
- **docs:** Documentación
- **style:** Cambios de formato
- **refactor:** Refactorización
- **test:** Pruebas
- **chore:** Tareas de mantenimiento

## 🎯 **Recomendación:**

Te sugiero usar la **primera opción** porque:
- ✅ **Descriptiva**: Explica exactamente qué mejoras se hicieron
- ✅ **Profesional**: Muestra el alcance del trabajo de documentación
- ✅ **En español**: Como prefieres
- ✅ **Organizada**: Lista los elementos principales mejorados

¿Te parece bien esta opción o prefieres alguna de las otras? 