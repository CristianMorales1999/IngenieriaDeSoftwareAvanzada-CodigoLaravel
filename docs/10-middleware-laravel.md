# 🔧 Middleware en Laravel 12

## 🎯 Introducción

El middleware en Laravel actúa como una capa de filtrado que se ejecuta antes y después de las peticiones HTTP. Permite interceptar, modificar o rechazar peticiones basándose en ciertas condiciones. Es como un "portero" que verifica que las peticiones cumplan ciertos requisitos antes de llegar a tu aplicación.

## 📁 Estructura de Middleware

### Ubicación
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
- **Middleware integrados**: Vienen con Laravel (Authenticate, TrustProxies, etc.)
- **Middleware personalizados**: Creados por el desarrollador (CheckRole, LogRequests, etc.)
- **Subcarpetas**: Para organizar middleware por funcionalidad (Api/, Admin/, etc.)
- **Convención de nombres**: `NombreMiddleware.php` (PascalCase)

## 🚀 Crear Middleware

### Comando Artisan
Los comandos para crear middleware son simples y pueden incluir subcarpetas:

```bash
php artisan make:middleware CheckRole
php artisan make:middleware LogRequests
php artisan make:middleware Api/ApiAuthentication
```

### Estructura Básica
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

**Explicación del flujo:**
1. **Recibe la petición**: El middleware recibe la petición HTTP
2. **Verifica condiciones**: Comprueba si el usuario tiene el rol requerido
3. **Toma decisión**: Si no cumple, rechaza la petición; si cumple, continúa
4. **Continúa o rechaza**: Llama a `$next($request)` para continuar o devuelve respuesta de error
5. **Respuesta apropiada**: Maneja APIs (JSON) y web (redirección) de manera diferente

## 🔐 Middleware de Autenticación

### 1. **Middleware de Autenticación Básico**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }
            
            return redirect()->route('login');
        }

        return $next($request);
    }
}
```

### 2. **Middleware de Verificación de Email**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu email debe ser verificado.'
                ], 409);
            }
            
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
```

### 3. **Middleware de Verificación de Contraseña**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RequirePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('auth.password_confirmed_at')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Confirmación de contraseña requerida'
                ], 409);
            }
            
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}
```

## 🎯 Middleware Personalizado

### 1. **Middleware de Verificación de Roles**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso denegado. Rol requerido: ' . $role
                ], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
```

### 2. **Middleware de Logging**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // en milisegundos
        
        Log::info('Request processed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'duration' => round($duration, 2) . 'ms',
            'status' => $response->getStatusCode()
        ]);
        
        return $response;
    }
}
```

### 3. **Middleware de Rate Limiting**
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
    public function handle(Request $request, Closure $next, int $maxAttempts = 60): Response
    {
        $key = $request->ip();
        
        if (FacadesRateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Demasiadas peticiones. Intenta de nuevo más tarde.'
            ], 429);
        }
        
        FacadesRateLimiter::hit($key);
        
        $response = $next($request);
        
        return $response->header('X-RateLimit-Limit', $maxAttempts)
            ->header('X-RateLimit-Remaining', FacadesRateLimiter::remaining($key, $maxAttempts));
    }
}
```

### 4. **Middleware de Mantenimiento**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isDownForMaintenance()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'El sitio está en mantenimiento. Vuelve más tarde.'
                ], 503);
            }
            
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
```

### 5. **Middleware de CORS**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        
        return $response;
    }
}
```

## 🔧 Middleware con Parámetros

### 1. **Middleware con Múltiples Parámetros**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission, string $resource = null): Response
    {
        $user = $request->user();
        
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

### 2. **Middleware con Configuración**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiRateLimit
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = 'api:' . $request->ip();
        
        if (FacadesRateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Límite de peticiones excedido. Intenta de nuevo en ' . $decayMinutes . ' minutos.'
            ], 429);
        }
        
        FacadesRateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
}
```

## 📊 Grupos de Middleware

### 1. **Middleware para Web**
```php
// En bootstrap/app.php o app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\LogRequests::class,
    ],
    
    'api' => [
        \App\Http\Middleware\Api\ApiAuthentication::class,
        \App\Http\Middleware\Api\ApiRateLimit::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
    
    'admin' => [
        'auth',
        'verified',
        \App\Http\Middleware\CheckRole::class . ':admin',
        \App\Http\Middleware\LogRequests::class,
    ],
    
    'customer' => [
        'auth',
        'verified',
        \App\Http\Middleware\CheckRole::class . ':customer',
    ],
];
```

### 2. **Middleware Alias**
```php
// En bootstrap/app.php o app/Http/Kernel.php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'signed' => \App\Http\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \App\Http\Middleware\CheckPermission::class,
    'maintenance' => \App\Http\Middleware\MaintenanceMode::class,
    'cors' => \App\Http\Middleware\Cors::class,
];
```

## 🛣️ Aplicación de Middleware

### 1. **En Rutas Individuales**
```php
// routes/web.php
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin']);

Route::post('/api/services', [ServiceController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:60,1']);
```

### 2. **En Grupos de Rutas**
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

### 3. **En Controladores**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('log')->only(['store', 'update', 'destroy']);
        $this->middleware('throttle:10,1')->only(['index']);
    }

    public function index()
    {
        return view('admin.index');
    }

    public function store(Request $request)
    {
        // Lógica del controlador
    }
}
```

## 🔍 Middleware Avanzado

### 1. **Middleware con Respuesta Personalizada**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiResponseFormat
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($request->expectsJson()) {
            $data = $response->getData();
            
            $formattedResponse = [
                'success' => $response->getStatusCode() < 400,
                'data' => $data,
                'timestamp' => now()->toISOString(),
                'path' => $request->path()
            ];
            
            $response->setData($formattedResponse);
        }
        
        return $response;
    }
}
```

### 2. **Middleware de Cache**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle(Request $request, Closure $next, int $minutes = 60): Response
    {
        if ($request->isMethod('GET')) {
            $cacheKey = 'response_' . sha1($request->fullUrl());
            
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            
            $response = $next($request);
            
            Cache::put($cacheKey, $response, $minutes * 60);
            
            return $response;
        }
        
        return $next($request);
    }
}
```

### 3. **Middleware de Sanitización**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        // Sanitizar entrada
        $sanitized = $this->sanitize($input);
        
        $request->merge($sanitized);
        
        return $next($request);
    }
    
    private function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = strip_tags(trim($value));
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
```

## 📝 Comandos Útiles

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

## 🎯 Resumen

El middleware en Laravel proporciona:
- ✅ Filtrado de peticiones HTTP
- ✅ Autenticación y autorización
- ✅ Rate limiting y seguridad
- ✅ Logging y monitoreo
- ✅ Cache y optimización
- ✅ Sanitización de datos

**Próximo paso:** Implementación práctica de la Fase 3 