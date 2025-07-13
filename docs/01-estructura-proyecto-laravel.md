# 📁 Estructura de Carpetas de Laravel 12

## 🏗️ **Estructura General del Proyecto**

Laravel sigue una estructura de carpetas bien organizada que facilita el desarrollo y mantenimiento de aplicaciones. Cada carpeta tiene un propósito específico:

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

## 📂 **Carpetas Principales Explicadas**

### 🎯 **app/ - Lógica de la Aplicación**

Esta es la carpeta más importante de Laravel, contiene toda la lógica de negocio de tu aplicación:

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

**Funciones principales:**
- **Controllers**: Manejan las peticiones HTTP y devuelven respuestas
- **Models**: Interactúan con la base de datos usando Eloquent ORM
- **Middleware**: Filtran peticiones HTTP antes de llegar a los controladores
- **Services**: Contienen lógica de negocio compleja que no debe estar en controladores

### 🌐 **public/ - Punto de Entrada**

Esta carpeta es el punto de entrada público de tu aplicación. Solo los archivos en esta carpeta son accesibles directamente desde el navegador:

```
public/
├── index.php             # Punto de entrada principal (todas las peticiones pasan por aquí)
├── favicon.ico          # Icono del sitio (aparece en la pestaña del navegador)
├── robots.txt           # Configuración para crawlers (buscadores como Google)
└── assets/              # Archivos públicos (CSS, JS, imágenes compilados)
```

**Importante:** Todas las peticiones web pasan por `public/index.php`. Este archivo es el único que debe ser accesible públicamente en tu servidor web.

### 🎨 **resources/ - Recursos de Frontend**

Esta carpeta contiene todos los archivos de frontend que necesitan ser compilados antes de ser utilizados:

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

**Nota:** Los archivos en esta carpeta no son accesibles directamente desde el navegador. Deben ser compilados y movidos a la carpeta `public/`.

### 🗄️ **database/ - Base de Datos**

Esta carpeta contiene todo lo relacionado con la base de datos, desde la estructura hasta los datos de prueba:

```
database/
├── factories/            # Factories para datos de prueba (generación automática de datos)
├── migrations/           # Migraciones de base de datos (control de versiones de la BD)
├── seeders/              # Seeders para poblar BD (datos iniciales de la aplicación)
└── .gitignore           # Archivos ignorados por Git (configuración local)
```

**Importante:** Las migraciones son como un "control de versiones" para tu base de datos. Te permiten crear, modificar y eliminar tablas de manera programática.

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

**Nota:** Los valores en estos archivos pueden ser sobrescritos por variables de entorno en el archivo `.env`.

### 🛣️ **routes/ - Definición de Rutas**

Esta carpeta contiene todos los archivos que definen las rutas de tu aplicación. Las rutas son las URLs que los usuarios pueden visitar:

```
routes/
├── web.php              # Rutas web (con sesión, CSRF, middleware web)
├── api.php              # Rutas API (sin sesión, para aplicaciones móviles/frontend)
├── console.php          # Comandos Artisan (tareas de consola)
└── channels.php         # Canales de broadcasting (WebSockets, eventos en tiempo real)
```

**Explicación:**
- **web.php**: Rutas para navegadores web (con autenticación, sesiones, CSRF)
- **api.php**: Rutas para APIs (sin sesiones, con tokens de autenticación)
- **console.php**: Comandos personalizados que puedes ejecutar con `php artisan`
- **channels.php**: Configuración para comunicación en tiempo real

## 🔄 **Flujo de Funcionamiento Básico**

Cuando un usuario visita tu aplicación Laravel, sigue este flujo paso a paso:

### 1. **Petición HTTP llega**
```
Usuario → public/index.php
```
**Explicación:** El navegador envía una petición HTTP que llega al archivo `index.php` en la carpeta `public/`.

### 2. **Bootstrap de la aplicación**
```
index.php → bootstrap/app.php
```
**Explicación:** Laravel inicia la aplicación cargando el archivo `bootstrap/app.php` que configura el contenedor de servicios.

### 3. **Carga de configuración**
```
app.php → config/ → .env
```
**Explicación:** Laravel carga todas las configuraciones desde los archivos en `config/` y las variables de entorno en `.env`.

### 4. **Enrutamiento**
```
Router → routes/web.php → Controller
```
**Explicación:** Laravel busca en los archivos de rutas para encontrar qué controlador debe manejar la petición.

### 5. **Procesamiento**
```
Controller → Model → Database
```
**Explicación:** El controlador ejecuta la lógica de negocio, interactúa con los modelos y estos acceden a la base de datos.

### 6. **Respuesta**
```
View → Response → Usuario
```
**Explicación:** El controlador devuelve una vista (HTML) o respuesta JSON que se envía de vuelta al usuario.

## 🎯 **Ejemplo Práctico: Flujo Completo**

Veamos un ejemplo completo de cómo funciona Laravel cuando un usuario visita la página de servicios:

```php
// 1. Usuario visita: /servicios
// El navegador envía una petición GET a la URL /servicios

// 2. routes/web.php
Route::get('/servicios', [ServicioController::class, 'index']);
// Laravel encuentra esta ruta y sabe que debe llamar al método 'index' del ServicioController

// 3. app/Http/Controllers/ServicioController.php
public function index()
{
    $servicios = Servicio::all(); // Obtiene todos los servicios de la base de datos
    return view('servicios.index', compact('servicios')); // Devuelve la vista con los datos
}

// 4. app/Models/Servicio.php
class Servicio extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio']; // Campos que se pueden llenar masivamente
}

// 5. resources/views/servicios/index.blade.php
@extends('layouts.app') // Extiende el layout principal
@section('content')
    @foreach($servicios as $servicio) // Itera sobre cada servicio
        <div>{{ $servicio->nombre }}</div> // Muestra el nombre de cada servicio
    @endforeach
@endsection
```

**Explicación del flujo:**
1. **Usuario visita la URL** → Laravel recibe la petición
2. **Rutas** → Laravel encuentra qué controlador debe manejar la petición
3. **Controlador** → Ejecuta la lógica de negocio y obtiene datos
4. **Modelo** → Interactúa con la base de datos usando Eloquent
5. **Vista** → Renderiza el HTML final que ve el usuario

## 🔧 **Archivos de Configuración Importantes**

### **.env - Variables de Entorno**
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

### **composer.json - Dependencias PHP**
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

### **package.json - Dependencias Frontend**
```json
{
    "devDependencies": {
        "tailwindcss": "^3.4.0",
        "vite": "^5.0.0"
    }
}
```

## 🎯 **Buenas Prácticas**

### ✅ **Organización de Carpetas**
- Mantener **app/** limpio y organizado
- Usar **Services/** para lógica de negocio compleja
- Separar **Controllers/** por funcionalidad
- Crear **subcarpetas** en views/ para organizar

### ✅ **Nomenclatura**
- **Controllers**: `ServicioController`, `UserController`
- **Models**: `Servicio`, `User`
- **Views**: `servicios.index`, `users.show`
- **Routes**: `servicios.index`, `users.store`

### ✅ **Archivos de Configuración**
- **Nunca** commitear `.env` (usar `.env.example`)
- Mantener **config/** organizado
- Documentar **variables de entorno** importantes

## 🚀 **Comandos Útiles para Explorar**

```bash
# Ver estructura de carpetas
tree -L 2

# Explorar configuración
php artisan config:show

# Ver rutas registradas
php artisan route:list

# Ver servicios disponibles
php artisan list
```

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 

## 🎯 **Opciones de Commit en Español:**

### ✅ **Opción Recomendada (Más Descriptiva):**
```bash
<code_block_to_apply_changes_from>
```

### ✅ **Opción Corta y Clara:**
```bash
git add .
git commit -m "feat: Configuración inicial del proyecto Laravel 12 con documentación

- Agregar documentación completa de Laravel 12 (4 archivos)
- Configurar estructura del proyecto para CRUD de servicios
- Incluir guías de rutas, plantillas Blade y comandos Artisan
- Configurar entorno de desarrollo y principios de usabilidad"
```

### ✅ **Opción Simple:**
```bash
git add .
git commit -m "feat: Configuración inicial del proyecto Laravel 12 con documentación"
```

## 🚀 **Comandos para Subir a GitHub:**

```bash
# 1. Agregar todos los archivos
git add .

# 2. Hacer el commit (usa una de las opciones de arriba)
git commit -m "feat: Configuración inicial del proyecto Laravel 12 con documentación completa"

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
- ✅ **Descriptiva**: Explica exactamente qué se agregó
- ✅ **Profesional**: Muestra el alcance del trabajo
- ✅ **En español**: Como prefieres
- ✅ **Organizada**: Lista los elementos principales

¿Te parece bien esta opción o prefieres alguna de las otras? 