# 🔧 Middleware en Laravel 12

## 🎯 **¿Qué es el Middleware?**

El middleware en Laravel actúa como una capa de filtrado que se ejecuta antes y después de las peticiones HTTP. Permite interceptar, modificar o rechazar peticiones basándose en ciertas condiciones. Es como un "portero" que verifica que las peticiones cumplan ciertos requisitos antes de llegar a tu aplicación.

**¿Por qué necesitas middleware?**
- **Seguridad**: Protege tu aplicación de peticiones maliciosas o no autorizadas
- **Autenticación**: Verifica que los usuarios estén logueados antes de acceder a ciertas páginas
- **Autorización**: Controla qué usuarios pueden acceder a qué funcionalidades
- **Logging**: Registra información sobre las peticiones para debugging y análisis
- **Rate Limiting**: Previene abuso de tu API limitando el número de peticiones
- **Optimización**: Cachea respuestas y optimiza el rendimiento

### 🎯 **Características Principales**

**Filtrado de peticiones**: El middleware puede interceptar peticiones antes de que lleguen a tu controlador. Es como un "filtro" que decide si la petición puede continuar o debe ser rechazada.

**Modificación de respuestas**: Puede modificar las respuestas antes de enviarlas al usuario. Útil para agregar headers, formatear respuestas, etc.

**Ejecución en cadena**: Múltiples middleware se ejecutan en secuencia. Cada uno puede pasar la petición al siguiente o detenerla.

**Flexibilidad**: Puedes aplicar middleware a rutas específicas, grupos de rutas, o toda la aplicación.

**Reutilización**: Un middleware puede usarse en múltiples rutas y aplicaciones.

## 📁 **Estructura de Middleware**

### 🎯 **Ubicación y Organización**

Los middleware se organizan en la carpeta `app/Http/Middleware/` y pueden incluir subcarpetas para mejor organización:

```
app/Http/Middleware/
├── Authenticate.php                    # Verifica si el usuario está autenticado
├── RedirectIfAuthenticated.php         # Redirige usuarios autenticados
├── TrustProxies.php                   # Confía en proxies (load balancers)
├── ValidateSignature.php               # Valida URLs firmadas
├── PreventRequestsDuringMaintenance.php # Bloquea peticiones en mantenimiento
├── CheckRole.php                      # Verifica roles de usuario
├── LogRequests.php                    # Registra todas las peticiones
├── RateLimit.php                      # Limita número de peticiones
└── Api/                               # Subcarpeta para middleware de API
    ├── ApiAuthentication.php          # Autenticación específica para API
    └── ApiRateLimit.php               # Rate limiting específico para API
```

**Explicación de la organización:**

**Middleware integrados**: Vienen con Laravel (Authenticate, TrustProxies, etc.). Son middleware básicos que Laravel incluye por defecto.

**Middleware personalizados**: Creados por el desarrollador (CheckRole, LogRequests, etc.). Son middleware específicos para tu aplicación.

**Subcarpetas**: Para organizar middleware por funcionalidad (Api/, Admin/, etc.). Ayuda a mantener el código organizado.

**Convención de nombres**: `NombreMiddleware.php` (PascalCase). Laravel automáticamente reconoce esta convención.

## 🚀 **Crear Middleware**

### 🎯 **Comando Artisan**

Los comandos para crear middleware son simples y pueden incluir subcarpetas:

```bash
php artisan make:middleware CheckRole
php artisan make:middleware LogRequests
php artisan make:middleware Api/ApiAuthentication
```

**Explicación de cada comando:**

**make:middleware CheckRole**: Crea un middleware para verificar roles de usuario. Se crea en `app/Http/Middleware/CheckRole.php`.

**make:middleware LogRequests**: Crea un middleware para registrar todas las peticiones. Útil para debugging y análisis.

**make:middleware Api/ApiAuthentication**: Crea un middleware específico para APIs en la subcarpeta Api/.

### 🏗️ **Estructura Básica**

Un middleware típico tiene un método `handle()` que procesa la petición:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Procesa la petición entrante y verifica el rol del usuario
     * 
     * @param Request $request La petición HTTP entrante
     * @param Closure $next La función que continúa la cadena de middleware
     * @param string $role El rol requerido para acceder
     * @return Response La respuesta HTTP
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verifica si el usuario existe y tiene el rol requerido
        if (!$request->user() || !$request->user()->hasRole($role)) {
            // Si es una petición API, devuelve JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso denegado. Rol requerido: ' . $role
                ], 403);
            }
            
            // Si es una petición web, redirige con mensaje de error
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        // Si todo está bien, continúa con la petición
        return $next($request);
    }
}
```

**Explicación detallada del flujo:**

1. **Recibe la petición**: El middleware recibe la petición HTTP y los parámetros adicionales (como el rol requerido).

2. **Verifica condiciones**: Comprueba si el usuario existe y tiene el rol requerido usando `$request->user()->hasRole($role)`.

3. **Toma decisión**: Si el usuario no cumple los requisitos, rechaza la petición; si cumple, continúa.

4. **Continúa o rechaza**: Llama a `$next($request)` para continuar con el siguiente middleware o devuelve una respuesta de error.

5. **Respuesta apropiada**: Maneja APIs (JSON) y aplicaciones web (redirección) de manera diferente usando `$request->expectsJson()`.

## 🔐 **Middleware de Autenticación**

### 📋 **1. Middleware de Autenticación Básico**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Verifica si el usuario está autenticado
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si hay un usuario autenticado
        if (!Auth::check()) {
            // Si es una petición API, devuelve JSON con código 401
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }
            
            // Si es una petición web, redirige al login
            return redirect()->route('login');
        }

        // Si está autenticado, continúa
        return $next($request);
    }
}
```

**Explicación detallada:**

**Auth::check()**: Verifica si hay un usuario autenticado en la sesión actual.

**$request->expectsJson()**: Determina si la petición espera una respuesta JSON (API) o HTML (web).

**401**: Código HTTP para "Unauthorized" - el usuario no está autenticado.

**redirect()->route('login')**: Redirige a la ruta de login para aplicaciones web.

### 📧 **2. Middleware de Verificación de Email**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    /**
     * Verifica que el email del usuario esté verificado
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario existe y su email está verificado
        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu email debe ser verificado.'
                ], 409);
            }
            
            // Redirige a la página de verificación de email
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
```

**Explicación detallada:**

**$request->user()->hasVerifiedEmail()**: Verifica si el email del usuario ha sido verificado.

**409**: Código HTTP para "Conflict" - el email no está verificado.

**verification.notice**: Ruta que muestra la página de verificación de email.

### 🔑 **3. Middleware de Verificación de Contraseña**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RequirePassword
{
    /**
     * Verifica que el usuario haya confirmado su contraseña recientemente
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si la contraseña fue confirmada recientemente
        if (!$request->session()->has('auth.password_confirmed_at')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Confirmación de contraseña requerida'
                ], 409);
            }
            
            // Redirige a la página de confirmación de contraseña
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}
```

**Explicación detallada:**

**$request->session()->has('auth.password_confirmed_at')**: Verifica si la contraseña fue confirmada recientemente en la sesión.

**password.confirm**: Ruta que muestra el formulario de confirmación de contraseña.

**409**: Código HTTP para "Conflict" - se requiere confirmación de contraseña.

## 🎯 **Middleware Personalizado**

### 👥 **1. Middleware de Verificación de Roles**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Verifica que el usuario tenga el rol requerido
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @param string $role El rol requerido
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verifica si el usuario existe y tiene el rol requerido
        if (!$request->user() || !$request->user()->hasRole($role)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso denegado. Rol requerido: ' . $role
                ], 403);
            }
            
            // Redirige con mensaje de error
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
```

**Explicación detallada:**

**$request->user()->hasRole($role)**: Verifica si el usuario tiene el rol específico. Esta función debe estar implementada en tu modelo User.

**string $role**: Parámetro que se pasa al middleware desde la ruta (ej: `middleware('role:admin')`).

**403**: Código HTTP para "Forbidden" - el usuario no tiene permisos.

**->with('error', '...')**: Agrega un mensaje de error a la sesión para mostrar en la siguiente página.

### 📝 **2. Middleware de Logging**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Registra información detallada de cada petición
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Registra el tiempo de inicio
        $startTime = microtime(true);
        
        // Continúa con la petición
        $response = $next($request);
        
        // Calcula la duración de la petición
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // en milisegundos
        
        // Registra información detallada de la petición
        Log::info('Request processed', [
            'method' => $request->method(),           // GET, POST, PUT, DELETE
            'url' => $request->fullUrl(),            // URL completa
            'ip' => $request->ip(),                  // IP del cliente
            'user_agent' => $request->userAgent(),   // Navegador del cliente
            'duration' => round($duration, 2) . 'ms', // Tiempo de procesamiento
            'status' => $response->getStatusCode()   // Código de respuesta HTTP
        ]);
        
        return $response;
    }
}
```

**Explicación detallada:**

**microtime(true)**: Obtiene el tiempo actual en microsegundos para medir la duración.

**$next($request)**: Ejecuta la petición y obtiene la respuesta.

**Log::info()**: Registra información en los logs de Laravel con nivel "info".

**$request->method()**: Obtiene el método HTTP (GET, POST, etc.).

**$request->fullUrl()**: Obtiene la URL completa incluyendo parámetros.

**$request->ip()**: Obtiene la dirección IP del cliente.

**$request->userAgent()**: Obtiene información del navegador del cliente.

### 🚦 **3. Middleware de Rate Limiting**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimit
{
    /**
     * Limita el número de peticiones por IP
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @param int $maxAttempts Número máximo de intentos
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60): Response
    {
        // Usa la IP del cliente como clave para el rate limiting
        $key = $request->ip();
        
        // Verifica si se han excedido los intentos máximos
        if (FacadesRateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Demasiadas peticiones. Intenta de nuevo más tarde.'
            ], 429);
        }
        
        // Incrementa el contador de intentos
        FacadesRateLimiter::hit($key);
        
        // Continúa con la petición
        $response = $next($request);
        
        // Agrega headers con información del rate limiting
        return $response->header('X-RateLimit-Limit', $maxAttempts)
            ->header('X-RateLimit-Remaining', FacadesRateLimiter::remaining($key, $maxAttempts));
    }
}
```

**Explicación detallada:**

**$request->ip()**: Obtiene la IP del cliente para usar como clave única.

**FacadesRateLimiter::tooManyAttempts()**: Verifica si se han excedido los intentos máximos.

**429**: Código HTTP para "Too Many Requests" - se han excedido los límites.

**FacadesRateLimiter::hit()**: Incrementa el contador de intentos para esta IP.

**X-RateLimit-Limit**: Header que indica el límite máximo de peticiones.

**X-RateLimit-Remaining**: Header que indica cuántas peticiones quedan disponibles.

### 🔧 **4. Middleware de Mantenimiento**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    /**
     * Verifica si la aplicación está en modo mantenimiento
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si la aplicación está en modo mantenimiento
        if (app()->isDownForMaintenance()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'El sitio está en mantenimiento. Vuelve más tarde.'
                ], 503);
            }
            
            // Muestra la página de mantenimiento
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
```

**Explicación detallada:**

**app()->isDownForMaintenance()**: Verifica si la aplicación está en modo mantenimiento.

**503**: Código HTTP para "Service Unavailable" - el servicio no está disponible temporalmente.

**response()->view('errors.maintenance', [], 503)**: Muestra una vista personalizada de mantenimiento.

### 🌐 **5. Middleware de CORS**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Agrega headers CORS para permitir peticiones desde otros dominios
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Continúa con la petición
        $response = $next($request);
        
        // Agrega headers CORS
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        
        return $response;
    }
}
```

**Explicación detallada:**

**Access-Control-Allow-Origin**: Permite peticiones desde cualquier dominio (*) o dominios específicos.

**Access-Control-Allow-Methods**: Especifica qué métodos HTTP están permitidos.

**Access-Control-Allow-Headers**: Especifica qué headers están permitidos en las peticiones.

## 🔧 **Middleware con Parámetros**

### 📋 **1. Middleware con Múltiples Parámetros**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Verifica que el usuario tenga el permiso requerido
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @param string $permission El permiso requerido
     * @param string $resource El recurso específico (opcional)
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next, string $permission, string $resource = null): Response
    {
        $user = $request->user();
        
        // Verifica si el usuario tiene el permiso requerido
        if (!$user || !$user->can($permission, $resource)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Permiso denegado: ' . $permission
                ], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
```

**Explicación detallada:**

**string $permission**: Parámetro obligatorio que especifica el permiso requerido.

**string $resource = null**: Parámetro opcional que especifica el recurso específico.

**$user->can($permission, $resource)**: Verifica si el usuario tiene el permiso específico (usando políticas de Laravel).

**Uso**: `middleware('permission:edit,posts')` - verifica el permiso "edit" en el recurso "posts".

### ⚙️ **2. Middleware con Configuración**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiRateLimit
{
    /**
     * Rate limiting específico para APIs
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @param int $maxAttempts Número máximo de intentos
     * @param int $decayMinutes Minutos para resetear el contador
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        // Usa un prefijo específico para APIs
        $key = 'api:' . $request->ip();
        
        // Verifica si se han excedido los intentos
        if (FacadesRateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Límite de peticiones excedido. Intenta de nuevo en ' . $decayMinutes . ' minutos.'
            ], 429);
        }
        
        // Incrementa el contador con tiempo de expiración
        FacadesRateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
}
```

**Explicación detallada:**

**'api:' . $request->ip()**: Prefijo específico para distinguir rate limiting de APIs del general.

**$decayMinutes * 60**: Convierte minutos a segundos para el tiempo de expiración.

**Uso**: `middleware('api.rate:30,5')` - 30 intentos máximo, reset cada 5 minutos.

## 📊 **Grupos de Middleware**

### 🌐 **1. Middleware para Web**

```php
// En bootstrap/app.php o app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,           // Encripta cookies
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Agrega cookies a la respuesta
        \Illuminate\Session\Middleware\StartSession::class,   // Inicia la sesión
        \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Comparte errores de sesión
        \App\Http\Middleware\VerifyCsrfToken::class,         // Verifica token CSRF
        \Illuminate\Routing\Middleware\SubstituteBindings::class, // Inyección de dependencias
        \App\Http\Middleware\LogRequests::class,             // Registra peticiones
    ],
    
    'api' => [
        \App\Http\Middleware\Api\ApiAuthentication::class,   // Autenticación específica para API
        \App\Http\Middleware\Api\ApiRateLimit::class,        // Rate limiting para API
        \Illuminate\Routing\Middleware\SubstituteBindings::class, // Inyección de dependencias
    ],
    
    'admin' => [
        'auth',                                              // Verifica autenticación
        'verified',                                          // Verifica email verificado
        \App\Http\Middleware\CheckRole::class . ':admin',    // Verifica rol admin
        \App\Http\Middleware\LogRequests::class,             // Registra peticiones
    ],
    
    'customer' => [
        'auth',                                              // Verifica autenticación
        'verified',                                          // Verifica email verificado
        \App\Http\Middleware\CheckRole::class . ':customer', // Verifica rol customer
    ],
];
```

**Explicación detallada de cada grupo:**

**web**: Middleware para aplicaciones web con sesiones, cookies, CSRF, etc.

**api**: Middleware optimizado para APIs sin sesiones, con rate limiting específico.

**admin**: Middleware para rutas de administración que requieren autenticación y rol admin.

**customer**: Middleware para rutas de clientes que requieren autenticación y rol customer.

### 🏷️ **2. Middleware Alias**

```php
// En bootstrap/app.php o app/Http/Kernel.php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,                    // Autenticación básica
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Autenticación HTTP básica
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // Autenticación de sesión
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // Headers de cache
    'can' => \Illuminate\Auth\Middleware\Authorize::class,                 // Autorización con políticas
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,       // Solo para usuarios no autenticados
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Confirmación de contraseña
    'signed' => \App\Http\Middleware\ValidateSignature::class,            // Validación de URLs firmadas
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Rate limiting integrado
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Verificación de email
    'role' => \App\Http\Middleware\CheckRole::class,                      // Verificación de roles
    'permission' => \App\Http\Middleware\CheckPermission::class,          // Verificación de permisos
    'maintenance' => \App\Http\Middleware\MaintenanceMode::class,         // Modo mantenimiento
    'cors' => \App\Http\Middleware\Cors::class,                          // Headers CORS
];
```

**Explicación de los alias más importantes:**

**auth**: Verifica que el usuario esté autenticado.

**guest**: Solo permite acceso a usuarios NO autenticados.

**verified**: Verifica que el email del usuario esté verificado.

**throttle**: Rate limiting integrado de Laravel.

**role**: Verifica roles de usuario (personalizado).

**permission**: Verifica permisos específicos (personalizado).

## 🛣️ **Aplicación de Middleware**

### 🎯 **1. En Rutas Individuales**

```php
// routes/web.php
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin']);

Route::post('/api/services', [ServiceController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:60,1']);
```

**Explicación detallada:**

**['auth', 'role:admin']**: Aplica autenticación y verificación de rol admin.

**auth:sanctum**: Autenticación específica para APIs usando Sanctum.

**throttle:60,1**: Rate limiting de 60 intentos por minuto.

### 📋 **2. En Grupos de Rutas**

```php
// Rutas para administradores
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::put('/admin/users/{user}', [UserController::class, 'update']);
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
});

// Rutas para clientes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/orders', [OrderController::class, 'index']);
});

// Rutas API
Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('api')->group(function () {
    Route::apiResource('services', ServiceApiController::class);
    Route::apiResource('orders', OrderApiController::class);
});
```

**Explicación detallada:**

**Route::middleware()->group()**: Aplica middleware a todas las rutas dentro del grupo.

**prefix('api')**: Agrega el prefijo 'api' a todas las URLs del grupo.

**apiResource()**: Crea rutas CRUD completas para APIs.

### 🎮 **3. En Controladores**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Constructor del controlador
     * Aplica middleware a todos los métodos del controlador
     */
    public function __construct()
    {
        // Middleware aplicado a todos los métodos
        $this->middleware(['auth', 'role:admin']);
        
        // Middleware aplicado solo a métodos específicos
        $this->middleware('log')->only(['store', 'update', 'destroy']);
        
        // Middleware con parámetros
        $this->middleware('throttle:10,1')->only(['index']);
    }

    /**
     * Método index - listar recursos
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Método store - crear recurso
     */
    public function store(Request $request)
    {
        // Lógica del controlador
    }
}
```

**Explicación detallada:**

**$this->middleware(['auth', 'role:admin'])**: Aplica middleware a todos los métodos.

**->only(['store', 'update', 'destroy'])**: Aplica middleware solo a métodos específicos.

**->except(['index'])**: Aplica middleware a todos los métodos excepto los especificados.

## 🔍 **Middleware Avanzado**

### 📊 **1. Middleware con Respuesta Personalizada**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiResponseFormat
{
    /**
     * Formatea todas las respuestas de API con estructura consistente
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Continúa con la petición
        $response = $next($request);
        
        // Solo formatea respuestas JSON
        if ($request->expectsJson()) {
            $data = $response->getData();
            
            // Estructura consistente para todas las respuestas
            $formattedResponse = [
                'success' => $response->getStatusCode() < 400,  // true si es exitosa
                'data' => $data,                               // Datos de la respuesta
                'timestamp' => now()->toISOString(),           // Timestamp de la respuesta
                'path' => $request->path()                     // Ruta de la petición
            ];
            
            // Actualiza la respuesta con el formato personalizado
            $response->setData($formattedResponse);
        }
        
        return $response;
    }
}
```

**Explicación detallada:**

**$response->getData()**: Obtiene los datos de la respuesta JSON.

**$response->getStatusCode()**: Obtiene el código de estado HTTP.

**$response->setData()**: Actualiza los datos de la respuesta JSON.

**now()->toISOString()**: Obtiene el timestamp actual en formato ISO.

### 💾 **2. Middleware de Cache**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Cachea respuestas GET para mejorar el rendimiento
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @param int $minutes Minutos para cachear la respuesta
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next, int $minutes = 60): Response
    {
        // Solo cachea peticiones GET
        if ($request->isMethod('GET')) {
            // Crea una clave única para el cache
            $cacheKey = 'response_' . sha1($request->fullUrl());
            
            // Verifica si existe en cache
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            
            // Continúa con la petición
            $response = $next($request);
            
            // Guarda la respuesta en cache
            Cache::put($cacheKey, $response, $minutes * 60);
            
            return $response;
        }
        
        return $next($request);
    }
}
```

**Explicación detallada:**

**$request->isMethod('GET')**: Verifica si la petición es GET (solo cacheamos GET).

**sha1($request->fullUrl())**: Crea un hash único de la URL para usar como clave de cache.

**Cache::has($cacheKey)**: Verifica si la respuesta existe en cache.

**Cache::put($cacheKey, $response, $minutes * 60)**: Guarda la respuesta en cache por el tiempo especificado.

### 🧹 **3. Middleware de Sanitización**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Sanitiza todos los datos de entrada para prevenir XSS
     * 
     * @param Request $request La petición HTTP
     * @param Closure $next Función para continuar
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtiene todos los datos de entrada
        $input = $request->all();
        
        // Sanitiza la entrada
        $sanitized = $this->sanitize($input);
        
        // Reemplaza los datos originales con los sanitizados
        $request->merge($sanitized);
        
        return $next($request);
    }
    
    /**
     * Sanitiza recursivamente un array de datos
     * 
     * @param array $data Datos a sanitizar
     * @return array Datos sanitizados
     */
    private function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Elimina HTML y espacios en blanco
                $sanitized[$key] = strip_tags(trim($value));
            } elseif (is_array($value)) {
                // Sanitiza arrays recursivamente
                $sanitized[$key] = $this->sanitize($value);
            } else {
                // Mantiene otros tipos de datos sin cambios
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
```

**Explicación detallada:**

**$request->all()**: Obtiene todos los datos de entrada (GET, POST, etc.).

**strip_tags()**: Elimina todas las etiquetas HTML para prevenir XSS.

**trim()**: Elimina espacios en blanco al inicio y final.

**$request->merge()**: Reemplaza los datos originales con los sanitizados.

**is_string()**: Verifica si el valor es una cadena de texto.

**is_array()**: Verifica si el valor es un array para sanitización recursiva.

## 📝 **Comandos Útiles**

```bash
# Crear middleware básico
php artisan make:middleware CheckRole

# Crear middleware para API
php artisan make:middleware Api/ApiAuthentication

# Listar middleware registrados
php artisan route:list --middleware

# Limpiar cache de rutas
php artisan route:clear

# Limpiar cache de configuración
php artisan config:clear
```

**Explicación de cada comando:**

**make:middleware CheckRole**: Crea un middleware para verificar roles de usuario.

**make:middleware Api/ApiAuthentication**: Crea un middleware específico para APIs en la subcarpeta Api/.

**route:list --middleware**: Muestra todas las rutas con el middleware aplicado.

**route:clear**: Limpia el cache de rutas (útil cuando cambias middleware).

**config:clear**: Limpia el cache de configuración (útil cuando cambias grupos de middleware).

## 🎯 **Resumen**

El middleware en Laravel proporciona:

- ✅ **Filtrado de peticiones HTTP**: Intercepta y filtra peticiones antes de procesarlas
- ✅ **Autenticación y autorización**: Verifica usuarios y permisos automáticamente
- ✅ **Rate limiting y seguridad**: Previene abuso y ataques a tu aplicación
- ✅ **Logging y monitoreo**: Registra información detallada de las peticiones
- ✅ **Cache y optimización**: Mejora el rendimiento cacheando respuestas
- ✅ **Sanitización de datos**: Limpia datos de entrada para prevenir ataques

**Próximo paso:** Implementación práctica de la Fase 3 