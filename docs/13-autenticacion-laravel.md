# 🔐 Autenticación en Laravel 12

## 🎯 Introducción

Laravel proporciona un sistema de autenticación robusto y flexible que incluye registro, login, logout, verificación de email, reset de contraseñas y gestión de sesiones. Se integra perfectamente con el sistema de middleware para proteger rutas. Es como tener un "portero digital" que verifica la identidad de los usuarios antes de permitirles acceder a ciertas partes de tu aplicación.

## 🚀 Configuración Inicial

### 1. **Instalación de Breeze (Opcional)**
Laravel Breeze es un starter kit que proporciona un sistema de autenticación completo y listo para usar:

```bash
# Instalar Laravel Breeze (sistema de autenticación completo)
composer require laravel/breeze --dev

# Instalar con Blade
php artisan breeze:install blade

# Instalar con Vue
php artisan breeze:install vue

# Instalar con React
php artisan breeze:install react

# Instalar con API
php artisan breeze:install api
```

**Explicación de las opciones:**
- **blade**: Interfaz con Blade templates (HTML tradicional)
- **vue**: Interfaz con Vue.js (JavaScript framework)
- **react**: Interfaz con React (JavaScript framework)
- **api**: Solo API (para aplicaciones móviles/frontend separado)

### 2. **Configuración Manual**
Si prefieres crear tu propio sistema de autenticación desde cero:

```bash
# Crear controladores de autenticación
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/RegisterController
php artisan make:controller Auth/ForgotPasswordController
php artisan make:controller Auth/ResetPasswordController
php artisan make:controller Auth/EmailVerificationController
```

**Explicación de cada controlador:**
- **LoginController**: Maneja el inicio y cierre de sesión
- **RegisterController**: Maneja el registro de nuevos usuarios
- **ForgotPasswordController**: Maneja la solicitud de reset de contraseña
- **ResetPasswordController**: Maneja el cambio de contraseña
- **EmailVerificationController**: Maneja la verificación de email

## 👤 Sistema de Login/Register

### 1. **Modelo User**
El modelo User es el corazón del sistema de autenticación. Define qué campos puede llenar el usuario y incluye métodos para verificar roles:

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',           // Nombre del usuario
        'email',          // Email (único)
        'password',       // Contraseña (hasheada)
        'role',           // Rol del usuario (admin, customer, etc.)
        'avatar',         // Foto de perfil
        'phone',          // Teléfono
        'address',        // Dirección
        'is_active',      // Si la cuenta está activa
    ];

    protected $hidden = [
        'password',        // No mostrar en JSON/arrays
        'remember_token',  // Token de "recordarme"
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Fecha de verificación de email
        'password' => 'hashed',            // Hashear automáticamente
        'is_active' => 'boolean',          // Convertir a boolean
    ];

    // Relaciones con otros modelos
    public function services()
    {
        return $this->hasMany(Service::class); // Un usuario puede tener muchos servicios
    }

    public function orders()
    {
        return $this->hasMany(Order::class); // Un usuario puede tener muchos pedidos
    }

    // Métodos de autorización para verificar roles
    public function hasRole($role)
    {
        return $this->role === $role; // Verifica si tiene un rol específico
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles); // Verifica si tiene cualquiera de varios roles
    }

    public function isAdmin()
    {
        return $this->hasRole('admin'); // Verifica si es administrador
    }

    public function isCustomer()
    {
        return $this->hasRole('customer'); // Verifica si es cliente
    }
}
```

**Explicación de las características:**
- **MustVerifyEmail**: Requiere que el usuario verifique su email
- **HasApiTokens**: Permite autenticación por API con tokens
- **HasFactory**: Permite crear datos de prueba
- **Notifiable**: Permite enviar notificaciones al usuario
- **$fillable**: Campos que se pueden llenar masivamente (seguridad)
- **$hidden**: Campos que no se incluyen en JSON/arrays
- **$casts**: Conversión automática de tipos de datos

### 2. **Controlador de Login**
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Verificar si el usuario está activo
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
                ]);
            }

            // Redirigir según el rol
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

### 3. **Form Request para Login**
```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'remember' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
```

### 4. **Controlador de Registro**
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Rol por defecto
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
```

### 5. **Form Request para Registro**
```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email no es válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'terms.required' => 'Debes aceptar los términos y condiciones.',
        ];
    }
}
```

## 🔧 Middleware de Autenticación

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

## 👥 Roles y Permisos

### 1. **Middleware de Roles**
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

### 2. **Middleware de Permisos**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user() || !$request->user()->can($permission)) {
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

### 3. **Sistema de Permisos con Spatie**
```bash
# Instalar Spatie Permission
composer require spatie/laravel-permission
```

```bash
# Publicar migraciones
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

```bash
# Ejecutar migraciones
php artisan migrate
```

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ... resto del modelo
}
```

```php
// Crear roles y permisos
php artisan tinker

// Crear roles
Role::create(['name' => 'admin']);
Role::create(['name' => 'customer']);
Role::create(['name' => 'moderator']);

// Crear permisos
Permission::create(['name' => 'create services']);
Permission::create(['name' => 'edit services']);
Permission::create(['name' => 'delete services']);
Permission::create(['name' => 'view users']);
Permission::create(['name' => 'edit users']);

// Asignar permisos a roles
$adminRole = Role::findByName('admin');
$adminRole->givePermissionTo([
    'create services',
    'edit services', 
    'delete services',
    'view users',
    'edit users'
]);

$customerRole = Role::findByName('customer');
$customerRole->givePermissionTo(['create services']);
```

## 🛡️ Protección de Rutas

### 1. **Rutas Protegidas por Autenticación**
```php
// routes/web.php

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rutas que requieren verificación de email
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('services', ServiceController::class)->except(['index', 'show']);
    });
});
```

### 2. **Rutas Protegidas por Roles**
```php
// Rutas para administradores
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// Rutas para clientes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', OrderController::class);
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile');
});
```

### 3. **Rutas Protegidas por Permisos**
```php
// Rutas con permisos específicos
Route::middleware(['auth', 'permission:create services'])->group(function () {
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
});

Route::middleware(['auth', 'permission:edit services'])->group(function () {
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
});

Route::middleware(['auth', 'permission:delete services'])->group(function () {
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});
```

## 🔐 Autenticación API con Sanctum

### 1. **Configuración de Sanctum**
```bash
# Instalar Sanctum
composer require laravel/sanctum

# Publicar configuración
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Ejecutar migraciones
php artisan migrate
```

### 2. **Controlador de Autenticación API**
```php
<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function login(ApiLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Tu cuenta ha sido desactivada.'
            ], 403);
        }

        $token = $user->createToken($request->device_name ?? 'api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
```

### 3. **Rutas API Protegidas**
```php
// routes/api.php

// Rutas públicas
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
    
    // Rutas de servicios
    Route::apiResource('services', ServiceApiController::class);
    Route::apiResource('orders', OrderApiController::class);
});
```

## 📝 Comandos Útiles

```bash
# Instalar Breeze
composer require laravel/breeze --dev
php artisan breeze:install blade

# Crear controladores de autenticación
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/RegisterController

# Crear middleware personalizado
php artisan make:middleware CheckRole
php artisan make:middleware CheckPermission

# Instalar Spatie Permission
composer require spatie/laravel-permission

# Instalar Sanctum
composer require laravel/sanctum

# Limpiar cache de rutas
php artisan route:clear

# Listar rutas con middleware
php artisan route:list --middleware
```

## 🎯 Resumen

La autenticación en Laravel proporciona:
- ✅ Sistema completo de login/register
- ✅ Middleware de autenticación y autorización
- ✅ Roles y permisos flexibles
- ✅ Protección de rutas por roles y permisos
- ✅ Autenticación API con Sanctum
- ✅ Verificación de email y reset de contraseñas
- ✅ Gestión de sesiones segura

**Próximo paso:** Seguridad 