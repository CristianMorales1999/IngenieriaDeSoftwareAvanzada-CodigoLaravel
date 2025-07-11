# 📁 Estructura de Carpetas de Laravel 12

## 🏗️ **Estructura General del Proyecto**

```
IngenieriaDeSoftwareAvanzada-CodigoLaravel/
├── app/                    # Lógica principal de la aplicación
├── bootstrap/              # Archivos de arranque
├── config/                 # Archivos de configuración
├── database/               # Migraciones, seeders y factories
├── public/                 # Punto de entrada y assets públicos
├── resources/              # Vistas, assets sin compilar
├── routes/                 # Definición de rutas
├── storage/                # Archivos generados por la aplicación
├── tests/                  # Tests automatizados
├── vendor/                 # Dependencias de Composer
├── .env                    # Variables de entorno
├── .env.example           # Ejemplo de variables de entorno
├── artisan                # Comando CLI de Laravel
├── composer.json          # Dependencias de PHP
├── package.json           # Dependencias de Node.js
└── vite.config.js         # Configuración de Vite
```

## 📂 **Carpetas Principales Explicadas**

### 🎯 **app/ - Lógica de la Aplicación**
```
app/
├── Console/               # Comandos Artisan personalizados
├── Exceptions/            # Manejo de excepciones
├── Http/                  # Lógica HTTP
│   ├── Controllers/       # Controladores
│   ├── Middleware/        # Middleware personalizado
│   └── Requests/          # Form Requests (validación)
├── Models/                # Modelos Eloquent
├── Providers/             # Service Providers
└── Services/              # Servicios de negocio
```

**Funciones principales:**
- **Controllers**: Manejan las peticiones HTTP
- **Models**: Interactúan con la base de datos
- **Middleware**: Filtran peticiones HTTP
- **Services**: Lógica de negocio reutilizable

### 🌐 **public/ - Punto de Entrada**
```
public/
├── index.php             # Punto de entrada principal
├── favicon.ico          # Icono del sitio
├── robots.txt           # Configuración para crawlers
└── assets/              # Archivos públicos (CSS, JS, imágenes)
```

**Importante:** Todas las peticiones web pasan por `public/index.php`

### 🎨 **resources/ - Recursos de Frontend**
```
resources/
├── css/                  # Archivos CSS sin compilar
├── js/                   # Archivos JavaScript
├── views/                # Plantillas Blade
│   ├── components/       # Componentes Blade
│   ├── layouts/          # Layouts principales
│   └── pages/            # Vistas de páginas
├── lang/                 # Archivos de idioma
└── sass/                 # Archivos Sass (si se usa)
```

### 🗄️ **database/ - Base de Datos**
```
database/
├── factories/            # Factories para datos de prueba
├── migrations/           # Migraciones de base de datos
├── seeders/              # Seeders para poblar BD
└── .gitignore           # Archivos ignorados por Git
```

### ⚙️ **config/ - Configuración**
```
config/
├── app.php              # Configuración general
├── auth.php             # Configuración de autenticación
├── database.php         # Configuración de BD
├── filesystems.php      # Configuración de archivos
├── mail.php             # Configuración de email
├── queue.php            # Configuración de colas
├── session.php          # Configuración de sesiones
└── cache.php            # Configuración de cache
```

### 🛣️ **routes/ - Definición de Rutas**
```
routes/
├── web.php              # Rutas web (con sesión)
├── api.php              # Rutas API
├── console.php          # Comandos Artisan
└── channels.php         # Canales de broadcasting
```

## 🔄 **Flujo de Funcionamiento Básico**

### 1. **Petición HTTP llega**
```
Usuario → public/index.php
```

### 2. **Bootstrap de la aplicación**
```
index.php → bootstrap/app.php
```

### 3. **Carga de configuración**
```
app.php → config/ → .env
```

### 4. **Enrutamiento**
```
Router → routes/web.php → Controller
```

### 5. **Procesamiento**
```
Controller → Model → Database
```

### 6. **Respuesta**
```
View → Response → Usuario
```

## 🎯 **Ejemplo Práctico: Flujo Completo**

```php
// 1. Usuario visita: /servicios

// 2. routes/web.php
Route::get('/servicios', [ServicioController::class, 'index']);

// 3. app/Http/Controllers/ServicioController.php
public function index()
{
    $servicios = Servicio::all();
    return view('servicios.index', compact('servicios'));
}

// 4. app/Models/Servicio.php
class Servicio extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio'];
}

// 5. resources/views/servicios/index.blade.php
@extends('layouts.app')
@section('content')
    @foreach($servicios as $servicio)
        <div>{{ $servicio->nombre }}</div>
    @endforeach
@endsection
```

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