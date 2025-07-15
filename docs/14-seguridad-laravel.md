# 🛡️ Seguridad en Laravel 12

## 🎯 Introducción

Laravel proporciona múltiples capas de seguridad para proteger aplicaciones web contra ataques comunes como CSRF, XSS, SQL injection, y ataques de fuerza bruta. La seguridad está integrada en el framework y se puede configurar fácilmente. Es como tener un "sistema de defensa" que protege tu aplicación de múltiples amenazas.

**¿Por qué es importante la seguridad?**
- **Protección de datos**: Evita que información sensible sea robada
- **Integridad del sistema**: Previene modificaciones no autorizadas
- **Confianza del usuario**: Los usuarios confían en aplicaciones seguras
- **Cumplimiento legal**: Muchas regulaciones requieren medidas de seguridad

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

**Explicación detallada de las configuraciones de seguridad:**

- **`driver`**: Define dónde se almacenan las sesiones. `file` las guarda en archivos, `database` en la base de datos, `redis` en memoria
- **`lifetime`**: Tiempo en minutos que dura una sesión antes de expirar automáticamente
- **`expire_on_close`**: Si es `true`, la sesión expira cuando el usuario cierra el navegador
- **`encrypt`**: Si es `true`, encripta los datos de sesión para mayor seguridad
- **`secure`**: Solo envía cookies por HTTPS (obligatorio en producción)
- **`http_only`**: Previene acceso por JavaScript (protege contra ataques XSS)
- **`same_site`**: Previene ataques CSRF entre sitios. `lax` permite algunos casos, `strict` es más seguro

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

**Explicación detallada de la protección CSRF:**

- **`@csrf`**: Directiva Blade que genera un token único para cada sesión del usuario
- **Verificación automática**: Laravel verifica automáticamente el token en cada petición POST
- **Prevención de ataques**: Impide que sitios maliciosos hagan peticiones en nombre del usuario autenticado
- **Transparente**: No necesitas manejar el token manualmente, Laravel lo hace todo
- **Funcionamiento**: El token se incluye como campo oculto en el formulario y se verifica contra el token almacenado en la sesión

### 3. **Excluir Rutas de CSRF**
En algunos casos necesitas excluir ciertas rutas de la verificación CSRF, como APIs o webhooks:

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

**Explicación de las exclusiones:**
- **`api/*`**: Excluye todas las rutas que empiecen con `/api/` (común para APIs REST)
- **`webhook/*`**: Excluye webhooks de servicios externos que no pueden enviar tokens CSRF
- **`payment/callback`**: Excluye callbacks de pasarelas de pago que no manejan tokens CSRF
- **⚠️ Precaución**: Solo excluye rutas cuando sea absolutamente necesario

### 4. **CSRF en AJAX**
Para peticiones AJAX, necesitas incluir el token CSRF en los headers:

```javascript
// Configuración global para AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// O en cada petición individual
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

**Explicación del CSRF en AJAX:**
- **`meta[name="csrf-token"]`**: Laravel incluye automáticamente este meta tag en el `<head>` de la página
- **`X-CSRF-TOKEN`**: Header personalizado que Laravel reconoce para verificar el token
- **`$.ajaxSetup`**: Configuración global que aplica el token a todas las peticiones AJAX
- **Funcionamiento**: El token se envía en el header en lugar de como campo del formulario

## ✅ Validación de Datos

### 1. **Validación en Controladores**
La validación es crucial para asegurar que solo datos válidos lleguen a tu aplicación:

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
        // Crear validador con reglas específicas
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
            // Mensajes personalizados para cada regla
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.unique' => 'Ya existe un servicio con este nombre.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'image.max' => 'La imagen no debe superar los 2MB.'
        ]);

        // Verificar si la validación falló
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)  // Pasar errores a la vista
                ->withInput();           // Mantener datos ingresados
        }

        // Crear el servicio con datos validados
        $service = Service::create($validator->validated());

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }
}
```

**Explicación detallada de las reglas de validación:**

- **`required`**: El campo es obligatorio y no puede estar vacío
- **`string`**: El valor debe ser una cadena de texto
- **`max:255`**: Máximo 255 caracteres (límite común para campos de texto)
- **`unique:services,name`**: El valor debe ser único en la columna `name` de la tabla `services`
- **`min:10`**: Mínimo 10 caracteres
- **`numeric`**: El valor debe ser numérico
- **`exists:categories,id`**: El valor debe existir en la columna `id` de la tabla `categories`
- **`image`**: Debe ser una imagen válida
- **`mimes:jpeg,png,jpg`**: Solo permite estos formatos de imagen
- **`max:2048`**: Máximo 2MB (2048 KB)
- **`array`**: Debe ser un array
- **`tags.*`**: Regla aplicada a cada elemento del array `tags`
- **`boolean`**: Debe ser verdadero o falso

### 2. **Validación con Form Requests**
Los Form Requests son clases dedicadas para validación que hacen el código más limpio y reutilizable:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     * Aquí puedes agregar lógica de autorización
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Service::class);
    }

    /**
     * Define las reglas de validación
     */
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

    /**
     * Mensajes personalizados para cada regla de validación
     */
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

    /**
     * Nombres personalizados para los campos (aparecen en mensajes de error)
     */
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

**Explicación de los métodos del Form Request:**

- **`authorize()`**: Verifica si el usuario tiene permisos para hacer la petición
- **`rules()`**: Define todas las reglas de validación en un solo lugar
- **`messages()`**: Personaliza los mensajes de error para mejor experiencia de usuario
- **`attributes()`**: Define nombres amigables para los campos en los mensajes de error

### 3. **Validación Personalizada**
Puedes crear reglas de validación personalizadas para casos específicos:

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Valida que el número de teléfono tenga exactamente 10 dígitos
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar que sea exactamente 10 dígitos numéricos
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

**Explicación de la validación personalizada:**

- **`ValidationRule`**: Interfaz que implementan todas las reglas personalizadas
- **`validate()`**: Método que contiene la lógica de validación
- **`$attribute`**: Nombre del campo que se está validando
- **`$value`**: Valor del campo a validar
- **`$fail()`**: Función que se llama cuando la validación falla
- **`preg_match()`**: Expresión regular que verifica el formato del número

## 🧹 Sanitización de Datos

### 1. **Sanitización en Middleware**
La sanitización limpia los datos de entrada para eliminar contenido malicioso:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Procesa la petición y sanitiza todos los datos de entrada
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener todos los datos de la petición
        $input = $request->all();
        
        // Sanitizar todos los datos de entrada
        $sanitized = $this->sanitize($input);
        
        // Reemplazar los datos originales con los sanitizados
        $request->merge($sanitized);
        
        return $next($request);
    }
    
    /**
     * Sanitiza recursivamente todos los datos
     */
    private function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remover HTML y espacios en blanco
                $sanitized[$key] = strip_tags(trim($value));
                
                // Sanitizar emails específicamente
                if ($key === 'email') {
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
                }
                
                // Sanitizar URLs específicamente
                if ($key === 'website' || $key === 'url') {
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_URL);
                }
            } elseif (is_array($value)) {
                // Sanitizar arrays recursivamente
                $sanitized[$key] = $this->sanitize($value);
            } else {
                // Mantener otros tipos de datos sin cambios
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
```

**Explicación de la sanitización:**

- **`strip_tags()`**: Elimina todas las etiquetas HTML del texto
- **`trim()`**: Elimina espacios en blanco al inicio y final
- **`filter_var()`**: Filtra datos según el tipo especificado
- **`FILTER_SANITIZE_EMAIL`**: Limpia emails de caracteres inválidos
- **`FILTER_SANITIZE_URL`**: Limpia URLs de caracteres peligrosos
- **Recursividad**: Procesa arrays anidados automáticamente

### 2. **Sanitización en Form Requests**
Puedes sanitizar datos antes de la validación en Form Requests:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Sanitiza los datos antes de la validación
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => strip_tags($this->name),                    // Remover HTML del nombre
            'description' => strip_tags($this->description),      // Remover HTML de la descripción
            'price' => (float) $this->price,                     // Convertir a número decimal
            'is_active' => (bool) $this->is_active,              // Convertir a booleano
            'tags' => array_map('strip_tags', $this->tags ?? []) // Sanitizar cada etiqueta
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

**Explicación de `prepareForValidation()`:**

- **Se ejecuta antes**: Se llama antes de que se ejecuten las reglas de validación
- **`strip_tags()`**: Elimina HTML malicioso de campos de texto
- **`(float)`**: Convierte el precio a número decimal
- **`(bool)`**: Convierte el campo activo a booleano
- **`array_map()`**: Aplica `strip_tags` a cada elemento del array de etiquetas

### 3. **Sanitización de HTML**
Para contenido que debe permitir HTML, usa HTMLPurifier:

```php
<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Sanitiza HTML permitiendo solo etiquetas específicas
     */
    public static function sanitize($html, $allowedTags = [])
    {
        // Crear configuración por defecto
        $config = \HTMLPurifier_Config::createDefault();
        
        // Configurar etiquetas permitidas si se especifican
        if (!empty($allowedTags)) {
            $config->set('HTML.Allowed', implode(',', $allowedTags));
        }
        
        // Crear instancia del purificador
        $purifier = new \HTMLPurifier($config);
        
        // Sanitizar y retornar HTML limpio
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

    // Sanitizar contenido HTML permitiendo solo etiquetas seguras
    $sanitizedContent = HtmlSanitizer::sanitize($validated['content'], [
        'p', 'br', 'strong', 'em', 'ul', 'ol', 'li'
    ]);

    $service = Service::create([
        'content' => $sanitizedContent
    ]);

    return redirect()->route('services.show', $service);
}
```

**Explicación de HTMLPurifier:**

- **HTMLPurifier**: Biblioteca que limpia HTML de código malicioso
- **`HTML.Allowed`**: Define qué etiquetas HTML están permitidas
- **Etiquetas seguras**: Solo permite etiquetas de formato básico, no scripts
- **Protección XSS**: Elimina automáticamente código JavaScript malicioso

## 🚫 Rate Limiting

### 1. **Rate Limiting Básico**
El rate limiting previene ataques de fuerza bruta limitando el número de peticiones:

```php
// routes/web.php
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/api/services', [ServiceController::class, 'index']);
    Route::post('/api/services', [ServiceController::class, 'store']);
});

// Rate limiting específico para login (más restrictivo)
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 intentos por minuto
```

**Explicación del rate limiting:**

- **`throttle:60,1`**: Permite 60 peticiones por minuto (1 minuto = 60 segundos)
- **`throttle:5,1`**: Permite solo 5 intentos de login por minuto (más seguro)
- **Protección**: Previene ataques de fuerza bruta y spam
- **Headers**: Laravel incluye headers con información del rate limit

### 2. **Rate Limiting Personalizado**
Puedes crear middleware personalizado para rate limiting más específico:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CustomRateLimit
{
    /**
     * Aplica rate limiting personalizado a la petición
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        // Crear clave única para identificar la petición
        $key = $this->resolveRequestSignature($request);
        
        // Verificar si se han excedido los intentos
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }
        
        // Incrementar contador de intentos
        RateLimiter::hit($key, $decayMinutes * 60);
        
        // Procesar la petición
        $response = $next($request);
        
        // Agregar headers con información del rate limit
        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
    
    /**
     * Crea una firma única para identificar la petición
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(implode('|', [
            $request->ip(),                    // IP del usuario
            $request->userAgent(),             // Navegador/dispositivo
            $request->user()?->id ?? 'guest'   // ID del usuario o 'guest'
        ]));
    }
    
    /**
     * Construye respuesta cuando se excede el límite
     */
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        // Calcular tiempo de espera
        $retryAfter = RateLimiter::availableIn($key);
        
        return response()->json([
            'message' => 'Demasiadas peticiones. Intenta de nuevo en ' . $retryAfter . ' segundos.',
            'retry_after' => $retryAfter
        ], 429); // Código HTTP para "Too Many Requests"
    }
    
    /**
     * Agrega headers con información del rate limit
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        return $response->header('X-RateLimit-Limit', $maxAttempts)
            ->header('X-RateLimit-Remaining', $remainingAttempts);
    }
    
    /**
     * Calcula intentos restantes
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return RateLimiter::remaining($key, $maxAttempts);
    }
}
```

**Explicación detallada del rate limiting personalizado:**

- **`resolveRequestSignature()`**: Crea una clave única combinando IP, user agent y usuario
- **`RateLimiter::tooManyAttempts()`**: Verifica si se excedió el límite
- **`RateLimiter::hit()`**: Incrementa el contador de intentos
- **`RateLimiter::availableIn()`**: Calcula cuánto tiempo debe esperar el usuario
- **Headers personalizados**: Informan al cliente sobre límites y intentos restantes
- **Código 429**: Respuesta estándar para "demasiadas peticiones"

### 3. **Rate Limiting por Usuario**
Puedes aplicar rate limiting específico por usuario:

```php
// En el controlador
public function store(Request $request)
{
    // Crear clave específica para el usuario
    $key = 'create_service:' . $request->user()->id;
    
    // Verificar límite de creación de servicios
    if (RateLimiter::tooManyAttempts($key, 10)) {
        return response()->json([
            'message' => 'Has creado demasiados servicios. Intenta de nuevo más tarde.'
        ], 429);
    }
    
    // Incrementar contador (1 hora de duración)
    RateLimiter::hit($key, 3600); // 3600 segundos = 1 hora
    
    // Lógica de creación del servicio
    $service = Service::create($request->validated());
    
    return response()->json($service, 201);
}
```

**Explicación del rate limiting por usuario:**

- **Clave específica**: `create_service:{user_id}` identifica al usuario específico
- **Límite personalizado**: 10 servicios por hora por usuario
- **Duración**: 1 hora (3600 segundos) antes de resetear el contador
- **Prevención de spam**: Evita que usuarios creen demasiados servicios

## 🔐 Headers de Seguridad

### 1. **Middleware de Headers de Seguridad**
Los headers de seguridad protegen contra varios tipos de ataques:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Agrega headers de seguridad a todas las respuestas
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Prevenir clickjacking (evita que tu sitio sea embebido en iframes)
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Prevenir MIME type sniffing (evita que el navegador adivine el tipo de archivo)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // XSS Protection (protección básica contra XSS)
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer Policy (controla qué información de referencia se envía)
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy (define qué recursos puede cargar la página)
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:;");
        
        // Permissions Policy (controla qué APIs del navegador puede usar la página)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        return $response;
    }
}
```

**Explicación detallada de cada header:**

- **`X-Frame-Options: DENY`**: Impide que tu sitio sea embebido en iframes de otros dominios
- **`X-Content-Type-Options: nosniff`**: Evita que el navegador "adivine" el tipo de archivo
- **`X-XSS-Protection: 1; mode=block`**: Activa protección básica contra XSS en navegadores antiguos
- **`Referrer-Policy`**: Controla qué información de la página anterior se envía
- **`Content-Security-Policy`**: Define qué recursos (scripts, estilos, imágenes) puede cargar la página
- **`Permissions-Policy`**: Controla acceso a APIs del navegador como geolocalización, micrófono, cámara

### 2. **Configuración en AppServiceProvider**
Puedes configurar headers globalmente en el AppServiceProvider:

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

**Explicación de la configuración global:**

- **`afterResolving`**: Se ejecuta después de que Laravel resuelve la petición
- **Headers globales**: Se aplican a todas las peticiones automáticamente
- **Configuración centralizada**: Todos los headers se configuran en un solo lugar

## 🔍 Logging de Seguridad

### 1. **Middleware de Logging de Seguridad**
Registrar eventos de seguridad ayuda a detectar y responder a amenazas:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityLogging
{
    /**
     * Registra eventos de seguridad durante el procesamiento de peticiones
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // Convertir a milisegundos
        
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
    
    /**
     * Detecta peticiones sospechosas basadas en patrones conocidos
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        // Patrones comunes de ataques
        $suspiciousPatterns = [
            '/<script/i',           // Scripts HTML
            '/javascript:/i',       // Protocolo JavaScript
            '/onload=/i',           // Eventos JavaScript
            '/onerror=/i',          // Eventos JavaScript
            '/union\s+select/i',    // SQL Injection
            '/drop\s+table/i',      // SQL Injection
            '/insert\s+into/i'      // SQL Injection
        ];
        
        // Convertir todos los datos de entrada a JSON para buscar patrones
        $input = json_encode($request->all());
        
        // Verificar cada patrón sospechoso
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}
```

**Explicación detallada del logging de seguridad:**

- **`microtime(true)`**: Obtiene tiempo actual con precisión de microsegundos
- **`Log::warning()`**: Registra eventos de advertencia (peticiones sospechosas)
- **`Log::info()`**: Registra eventos informativos (fallos de autenticación)
- **Patrones sospechosos**: Detecta código malicioso común
- **Información útil**: IP, user agent, URL, método, usuario, duración
- **Detección temprana**: Ayuda a identificar ataques en progreso

### 2. **Configuración de Logging**
Configura un canal específico para logs de seguridad:

```php
// config/logging.php
'channels' => [
    'security' => [
        'driver' => 'daily',                                    // Rotación diaria de archivos
        'path' => storage_path('logs/security.log'),           // Archivo específico para seguridad
        'level' => env('LOG_LEVEL', 'debug'),                  // Nivel de logging
        'days' => 14,                                          // Mantener logs por 14 días
    ],
],
```

**Explicación de la configuración de logging:**

- **`daily`**: Crea un nuevo archivo cada día (security-2024-01-15.log)
- **`storage_path('logs/security.log')`**: Ubicación específica para logs de seguridad
- **`level`**: Nivel mínimo de logging (debug, info, warning, error)
- **`days`**: Mantiene archivos por 14 días, luego los elimina automáticamente

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

**Explicación de los comandos:**

- **`make:middleware`**: Crea archivos de middleware con estructura básica
- **`config:clear`**: Limpia cache de configuración (útil después de cambios)
- **`route:clear`**: Limpia cache de rutas
- **`config:show`**: Muestra configuración actual de un archivo específico
- **`composer require`**: Instala dependencias adicionales para seguridad

## 🎯 Resumen

La seguridad en Laravel proporciona una protección completa y robusta:

### ✅ **Protecciones Implementadas:**
- **CSRF Protection**: Protección automática contra ataques cross-site request forgery
- **Validación Robusta**: Verificación exhaustiva de datos de entrada
- **Sanitización**: Limpieza automática de datos maliciosos
- **Rate Limiting**: Prevención de ataques de fuerza bruta
- **Headers de Seguridad**: Protección contra clickjacking, XSS, y otros ataques
- **Logging de Seguridad**: Monitoreo y detección de amenazas
- **Prevención de Ataques Comunes**: Protección integrada contra vulnerabilidades conocidas

### 🔧 **Características Clave:**
- **Configuración Automática**: Muchas protecciones funcionan sin configuración
- **Personalizable**: Puedes ajustar cada aspecto según tus necesidades
- **Transparente**: No interfiere con el desarrollo normal
- **Completa**: Cubre todos los aspectos de seguridad web moderna

### 🚀 **Próximo Paso:**
Implementación práctica de la **Fase 5** con todas estas medidas de seguridad integradas en el CRUD de servicios. 