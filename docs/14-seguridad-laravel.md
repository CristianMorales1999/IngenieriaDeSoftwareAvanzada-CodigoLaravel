# 🛡️ Seguridad en Laravel 12

## 🎯 Introducción

Laravel proporciona múltiples capas de seguridad para proteger aplicaciones web contra ataques comunes como CSRF, XSS, SQL injection, y ataques de fuerza bruta. La seguridad está integrada en el framework y se puede configurar fácilmente. Es como tener un "sistema de defensa" que protege tu aplicación de múltiples amenazas.

## 🔒 CSRF Protection

### 1. **Configuración de CSRF**
El CSRF (Cross-Site Request Forgery) protege contra ataques donde un sitio malicioso intenta hacer peticiones en nombre del usuario. La configuración se maneja en el archivo de sesiones:

```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'file'),        // Driver de sesión (file, database, redis)
    'lifetime' => env('SESSION_LIFETIME', 120),       // Duración de la sesión en minutos
    'expire_on_close' => false,                       // Si expira al cerrar el navegador
    'encrypt' => false,                               // Si encriptar los datos de sesión
    'files' => storage_path('framework/sessions'),    // Ruta para archivos de sesión
    'connection' => env('SESSION_CONNECTION'),        // Conexión de BD para sesiones
    'table' => 'sessions',                           // Tabla para sesiones en BD
    'store' => env('SESSION_STORE'),                 // Store personalizado
    'lottery' => [2, 100],                          // Probabilidad de limpiar sesiones
    'cookie' => env(                                 // Nombre de la cookie de sesión
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => '/',                                   // Ruta de la cookie
    'domain' => env('SESSION_DOMAIN'),               // Dominio de la cookie
    'secure' => env('SESSION_SECURE_COOKIE'),        // Solo HTTPS
    'http_only' => true,                             // No accesible por JavaScript
    'same_site' => 'lax',                           // Política SameSite
];
```

**Explicación de las configuraciones de seguridad:**
- **secure**: Solo envía cookies por HTTPS (producción)
- **http_only**: Previene acceso por JavaScript (protege contra XSS)
- **same_site**: Previene ataques CSRF entre sitios
- **lifetime**: Controla cuánto tiempo dura la sesión

### 2. **Token CSRF en Formularios**
Laravel automáticamente incluye protección CSRF en todos los formularios. El token se genera automáticamente y se verifica en cada petición POST:

```php
{{-- Formulario con token CSRF --}}
<form method="POST" action="{{ route('services.store') }}">
    @csrf  {{-- Genera automáticamente el token CSRF --}}
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
    </div>
    
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
    </div>
    
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
        Crear Servicio
    </button>
</form>
```

**Explicación de la protección CSRF:**
- **@csrf**: Directiva Blade que genera un token único para cada sesión
- **Verificación automática**: Laravel verifica el token en cada petición POST
- **Prevención de ataques**: Impide que sitios maliciosos hagan peticiones en nombre del usuario
- **Transparente**: No necesitas manejar el token manualmente

### 3. **Excluir Rutas de CSRF**
```php
// app/Http/Middleware/VerifyCsrfToken.php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/*', // Excluir todas las rutas API
        'webhook/*', // Excluir webhooks
        'payment/callback', // Excluir callback de pagos
    ];
}
```

### 4. **CSRF en AJAX**
```javascript
// Configuración global para AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// O en cada petición
$.ajax({
    url: '/api/services',
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        name: 'Mi Servicio',
        description: 'Descripción del servicio'
    },
    success: function(response) {
        console.log('Servicio creado:', response);
    }
});
```

## ✅ Validación de Datos

### 1. **Validación en Controladores**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.unique' => 'Ya existe un servicio con este nombre.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'image.max' => 'La imagen no debe superar los 2MB.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $service = Service::create($validator->validated());

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }
}
```

### 2. **Validación con Form Requests**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Service::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|array|max:10',
            'tags.*' => 'string|max:50',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.unique' => 'Ya existe un servicio con este nombre.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'image.max' => 'La imagen no debe superar los 2MB.',
            'tags.max' => 'No puedes agregar más de 10 etiquetas.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del servicio',
            'description' => 'descripción',
            'price' => 'precio',
            'category_id' => 'categoría',
            'image' => 'imagen'
        ];
    }
}
```

### 3. **Validación Personalizada**
```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $fail('El número de teléfono debe tener exactamente 10 dígitos.');
        }
    }
}
```

```php
// Uso en Form Request
use App\Rules\ValidPhoneNumber;

public function rules(): array
{
    return [
        'phone' => ['required', new ValidPhoneNumber],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed']
    ];
}
```

## 🧹 Sanitización de Datos

### 1. **Sanitización en Middleware**
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
                // Remover HTML y espacios
                $sanitized[$key] = strip_tags(trim($value));
                
                // Sanitizar emails
                if ($key === 'email') {
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
                }
                
                // Sanitizar URLs
                if ($key === 'website' || $key === 'url') {
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_URL);
                }
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

### 2. **Sanitización en Form Requests**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => strip_tags($this->name),
            'description' => strip_tags($this->description),
            'price' => (float) $this->price,
            'is_active' => (bool) $this->is_active,
            'tags' => array_map('strip_tags', $this->tags ?? [])
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }
}
```

### 3. **Sanitización de HTML**
```php
<?php

namespace App\Services;

class HtmlSanitizer
{
    public static function sanitize($html, $allowedTags = [])
    {
        $config = \HTMLPurifier_Config::createDefault();
        
        if (!empty($allowedTags)) {
            $config->set('HTML.Allowed', implode(',', $allowedTags));
        }
        
        $purifier = new \HTMLPurifier($config);
        
        return $purifier->purify($html);
    }
}
```

```php
// Uso en el controlador
use App\Services\HtmlSanitizer;

public function store(Request $request)
{
    $validated = $request->validate([
        'content' => 'required|string'
    ]);

    // Sanitizar contenido HTML
    $sanitizedContent = HtmlSanitizer::sanitize($validated['content'], [
        'p', 'br', 'strong', 'em', 'ul', 'ol', 'li'
    ]);

    $service = Service::create([
        'content' => $sanitizedContent
    ]);

    return redirect()->route('services.show', $service);
}
```

## 🚫 Rate Limiting

### 1. **Rate Limiting Básico**
```php
// routes/web.php
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/api/services', [ServiceController::class, 'index']);
    Route::post('/api/services', [ServiceController::class, 'store']);
});

// Rate limiting específico para login
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 intentos por minuto
```

### 2. **Rate Limiting Personalizado**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CustomRateLimit
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
    
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(implode('|', [
            $request->ip(),
            $request->userAgent(),
            $request->user()?->id ?? 'guest'
        ]));
    }
    
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        $retryAfter = RateLimiter::availableIn($key);
        
        return response()->json([
            'message' => 'Demasiadas peticiones. Intenta de nuevo en ' . $retryAfter . ' segundos.',
            'retry_after' => $retryAfter
        ], 429);
    }
    
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        return $response->header('X-RateLimit-Limit', $maxAttempts)
            ->header('X-RateLimit-Remaining', $remainingAttempts);
    }
    
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return RateLimiter::remaining($key, $maxAttempts);
    }
}
```

### 3. **Rate Limiting por Usuario**
```php
// En el controlador
public function store(Request $request)
{
    $key = 'create_service:' . $request->user()->id;
    
    if (RateLimiter::tooManyAttempts($key, 10)) {
        return response()->json([
            'message' => 'Has creado demasiados servicios. Intenta de nuevo más tarde.'
        ], 429);
    }
    
    RateLimiter::hit($key, 3600); // 1 hora
    
    // Lógica de creación del servicio
    $service = Service::create($request->validated());
    
    return response()->json($service, 201);
}
```

## 🔐 Headers de Seguridad

### 1. **Middleware de Headers de Seguridad**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Prevenir clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Prevenir MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:;");
        
        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        return $response;
    }
}
```

### 2. **Configuración en AppServiceProvider**
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Agregar headers de seguridad globalmente
        $this->app->afterResolving('Illuminate\Http\Request', function (Request $request) {
            $request->headers->set('X-Frame-Options', 'DENY');
            $request->headers->set('X-Content-Type-Options', 'nosniff');
            $request->headers->set('X-XSS-Protection', '1; mode=block');
        });
    }
}
```

## 🔍 Logging de Seguridad

### 1. **Middleware de Logging de Seguridad**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityLogging
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;
        
        // Log de peticiones sospechosas
        if ($this->isSuspiciousRequest($request)) {
            Log::warning('Suspicious request detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => $request->user()?->id,
                'duration' => round($duration, 2) . 'ms'
            ]);
        }
        
        // Log de errores de autenticación
        if ($response->getStatusCode() === 401 || $response->getStatusCode() === 403) {
            Log::info('Authentication/Authorization failure', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_id' => $request->user()?->id,
                'status_code' => $response->getStatusCode()
            ]);
        }
        
        return $response;
    }
    
    private function isSuspiciousRequest(Request $request): bool
    {
        // Detectar patrones sospechosos
        $suspiciousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/union\s+select/i',
            '/drop\s+table/i',
            '/insert\s+into/i'
        ];
        
        $input = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}
```

### 2. **Configuración de Logging**
```php
// config/logging.php
'channels' => [
    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],
```

## 📝 Comandos Útiles

```bash
# Crear middleware de seguridad
php artisan make:middleware SecurityHeaders
php artisan make:middleware SecurityLogging

# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de rutas
php artisan route:clear

# Verificar configuración de seguridad
php artisan config:show session
php artisan config:show app

# Instalar HTMLPurifier para sanitización
composer require ezyang/htmlpurifier
```

## 🎯 Resumen

La seguridad en Laravel proporciona:
- ✅ Protección CSRF automática
- ✅ Validación robusta de datos
- ✅ Sanitización de entrada
- ✅ Rate limiting configurable
- ✅ Headers de seguridad
- ✅ Logging de eventos de seguridad
- ✅ Prevención de ataques comunes

**Próximo paso:** Implementación práctica de la Fase 5 