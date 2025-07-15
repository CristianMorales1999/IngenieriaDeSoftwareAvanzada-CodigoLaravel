# 🔐 Autenticación en Laravel 12

## 🎯 **Introducción**

Laravel proporciona un sistema de autenticación robusto y flexible que incluye registro, login, logout, verificación de email, reset de contraseñas y gestión de sesiones. Se integra perfectamente con el sistema de middleware para proteger rutas. Es como tener un "portero digital" que verifica la identidad de los usuarios antes de permitirles acceder a ciertas partes de tu aplicación.

**¿Qué es la autenticación?**
La autenticación es el proceso de verificar la identidad de un usuario. Es como mostrar tu identificación antes de entrar a un lugar. En aplicaciones web, esto significa que los usuarios deben "probar" quiénes son antes de acceder a ciertas funciones.

**¿Por qué es importante?**
- **Seguridad**: Protege información sensible y funciones privadas
- **Personalización**: Permite mostrar contenido específico para cada usuario
- **Control de acceso**: Define quién puede hacer qué en la aplicación
- **Auditoría**: Registra quién realizó qué acciones

**Componentes del sistema de autenticación:**
- **Login/Logout**: Entrada y salida de usuarios
- **Registro**: Creación de nuevas cuentas
- **Verificación de email**: Confirmar que el email es real
- **Reset de contraseñas**: Recuperar acceso perdido
- **Roles y permisos**: Definir qué puede hacer cada usuario
- **Middleware**: Proteger rutas automáticamente

## 🚀 Configuración Inicial

### 1. **Instalación de Breeze (Opcional)**

Laravel Breeze es un starter kit que proporciona un sistema de autenticación completo y listo para usar. Es como tener un "kit de construcción" que incluye todo lo necesario para la autenticación:

```bash
# Instalar Laravel Breeze (sistema de autenticación completo)
composer require laravel/breeze --dev
# composer require: instala el paquete, --dev: solo para desarrollo (no en producción)

# Instalar con Blade (HTML tradicional)
php artisan breeze:install blade
# blade: usa templates Blade de Laravel (HTML + PHP), ideal para principiantes

# Instalar con Vue (JavaScript framework)
php artisan breeze:install vue
# vue: usa Vue.js para la interfaz, más interactivo y moderno

# Instalar con React (JavaScript framework)
php artisan breeze:install react
# react: usa React para la interfaz, muy popular para aplicaciones complejas

# Instalar con API (solo backend)
php artisan breeze:install api
# api: solo crea endpoints de API, ideal para aplicaciones móviles o frontend separado
```

**Explicación detallada de las opciones:**

**Blade (Recomendado para principiantes):**
- Usa templates HTML tradicionales con PHP
- Más fácil de entender y modificar
- No requiere conocimientos de JavaScript avanzado
- Ideal para aplicaciones web simples

**Vue.js:**
- Framework JavaScript progresivo
- Interfaz más interactiva y moderna
- Requiere conocimientos básicos de JavaScript
- Bueno para aplicaciones con mucha interactividad

**React:**
- Framework JavaScript muy popular
- Interfaz muy dinámica y moderna
- Requiere conocimientos sólidos de JavaScript
- Ideal para aplicaciones complejas

**API:**
- Solo crea endpoints de API (JSON)
- No incluye interfaz de usuario
- Perfecto para aplicaciones móviles
- Ideal cuando el frontend está separado

**¿Qué incluye Breeze?**
- Controladores de autenticación
- Vistas de login y registro
- Middleware de autenticación
- Rutas configuradas
- Validación de formularios
- Estilos CSS básicos

### 2. **Configuración Manual**

Si prefieres crear tu propio sistema de autenticación desde cero (más control pero más trabajo):

```bash
# Crear controladores de autenticación
php artisan make:controller Auth/LoginController
# Crea LoginController en app/Http/Controllers/Auth/ - maneja login/logout

php artisan make:controller Auth/RegisterController
# Crea RegisterController en app/Http/Controllers/Auth/ - maneja registro de usuarios

php artisan make:controller Auth/ForgotPasswordController
# Crea ForgotPasswordController - maneja "olvidé mi contraseña"

php artisan make:controller Auth/ResetPasswordController
# Crea ResetPasswordController - maneja el cambio de contraseña

php artisan make:controller Auth/EmailVerificationController
# Crea EmailVerificationController - maneja verificación de email
```

**Explicación detallada de cada controlador:**

**LoginController:**
- **Función**: Maneja el inicio y cierre de sesión de usuarios
- **Métodos típicos**: `showLoginForm()`, `login()`, `logout()`
- **Responsabilidades**: Validar credenciales, crear sesiones, cerrar sesiones
- **Ubicación**: `app/Http/Controllers/Auth/LoginController.php`

**RegisterController:**
- **Función**: Maneja el registro de nuevos usuarios
- **Métodos típicos**: `showRegistrationForm()`, `register()`
- **Responsabilidades**: Validar datos, crear usuarios, enviar emails de verificación
- **Ubicación**: `app/Http/Controllers/Auth/RegisterController.php`

**ForgotPasswordController:**
- **Función**: Maneja cuando un usuario olvida su contraseña
- **Métodos típicos**: `showLinkRequestForm()`, `sendResetLinkEmail()`
- **Responsabilidades**: Enviar emails con enlaces de reset
- **Ubicación**: `app/Http/Controllers/Auth/ForgotPasswordController.php`

**ResetPasswordController:**
- **Función**: Maneja el cambio de contraseña con el token recibido
- **Métodos típicos**: `showResetForm()`, `reset()`
- **Responsabilidades**: Validar token, cambiar contraseña
- **Ubicación**: `app/Http/Controllers/Auth/ResetPasswordController.php`

**EmailVerificationController:**
- **Función**: Maneja la verificación de email de nuevos usuarios
- **Métodos típicos**: `show()`, `verify()`, `resend()`
- **Responsabilidades**: Verificar emails, reenviar emails de verificación
- **Ubicación**: `app/Http/Controllers/Auth/EmailVerificationController.php`

**¿Cuándo usar configuración manual?**
- Cuando necesitas control total sobre el proceso
- Cuando tienes requisitos específicos de autenticación
- Cuando quieres aprender cómo funciona internamente
- Cuando Breeze no se adapta a tus necesidades

## 👤 Sistema de Login/Register

### 1. **Modelo User**

El modelo User es el corazón del sistema de autenticación. Define qué campos puede llenar el usuario y incluye métodos para verificar roles. Es como la "tarjeta de identidad" de cada usuario en tu aplicación:

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
    // HasApiTokens: permite autenticación por API, HasFactory: permite crear datos de prueba, Notifiable: permite enviar notificaciones

    protected $fillable = [
        'name',           // Nombre del usuario (se puede llenar masivamente)
        'email',          // Email único del usuario
        'password',       // Contraseña (se hashea automáticamente)
        'role',           // Rol del usuario (admin, customer, etc.)
        'avatar',         // Ruta de la foto de perfil
        'phone',          // Número de teléfono
        'address',        // Dirección del usuario
        'is_active',      // Si la cuenta está activa (true/false)
    ];
    // $fillable: define qué campos se pueden llenar con User::create() o $user->fill()

    protected $hidden = [
        'password',        // No incluir en JSON/arrays por seguridad
        'remember_token',  // Token para "recordarme" (no mostrar)
    ];
    // $hidden: campos que no se incluyen cuando se convierte el modelo a JSON

    protected $casts = [
        'email_verified_at' => 'datetime', // Convertir a objeto DateTime
        'password' => 'hashed',            // Hashear automáticamente la contraseña
        'is_active' => 'boolean',          // Convertir a true/false
    ];
    // $casts: conversión automática de tipos de datos

    // Relaciones con otros modelos (Eloquent ORM)
    public function services()
    {
        return $this->hasMany(Service::class); // Un usuario puede tener muchos servicios
        // hasMany: relación uno a muchos (un usuario, muchos servicios)
    }

    public function orders()
    {
        return $this->hasMany(Order::class); // Un usuario puede tener muchos pedidos
        // hasMany: relación uno a muchos (un usuario, muchos pedidos)
    }

    // Métodos de autorización para verificar roles
    public function hasRole($role)
    {
        return $this->role === $role; // Verifica si tiene un rol específico
        // Compara el rol del usuario con el rol proporcionado
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles); // Verifica si tiene cualquiera de varios roles
        // (array) $roles: convierte a array si es string, in_array: busca en el array
    }

    public function isAdmin()
    {
        return $this->hasRole('admin'); // Verifica si es administrador
        // Método conveniente para verificar rol de admin
    }

    public function isCustomer()
    {
        return $this->hasRole('customer'); // Verifica si es cliente
        // Método conveniente para verificar rol de customer
    }
}
```

**Explicación detallada de las características:**

**Traits utilizados:**
- **`HasApiTokens`**: Permite autenticación por API con tokens (Sanctum)
- **`HasFactory`**: Permite crear datos de prueba con factories
- **`Notifiable`**: Permite enviar notificaciones al usuario (emails, SMS, etc.)

**Interfaces implementadas:**
- **`MustVerifyEmail`**: Requiere que el usuario verifique su email antes de usar la aplicación

**Propiedades importantes:**
- **`$fillable`**: Campos que se pueden llenar masivamente (seguridad contra asignación masiva)
- **`$hidden`**: Campos que no se incluyen en JSON/arrays (protege información sensible)
- **`$casts`**: Conversión automática de tipos de datos (datetime, boolean, etc.)

**Relaciones Eloquent:**
- **`hasMany()`**: Relación uno a muchos (un usuario puede tener muchos servicios/pedidos)
- **`belongsTo()`**: Relación muchos a uno (un servicio pertenece a un usuario)
- **`hasOne()`**: Relación uno a uno (un usuario tiene un perfil)

**Métodos de autorización:**
- **`hasRole()`**: Verifica si el usuario tiene un rol específico
- **`hasAnyRole()`**: Verifica si el usuario tiene cualquiera de varios roles
- **`isAdmin()`**: Método conveniente para verificar si es administrador
- **`isCustomer()`**: Método conveniente para verificar si es cliente

**¿Por qué usar estos métodos?**
- **Seguridad**: Evita errores de tipeo al verificar roles
- **Legibilidad**: El código es más fácil de leer y entender
- **Mantenibilidad**: Cambios en roles se hacen en un solo lugar
- **Reutilización**: Los métodos se pueden usar en toda la aplicación

### 2. **Controlador de Login**

El controlador de login maneja todo el proceso de autenticación de usuarios. Es como el "portero" que verifica las credenciales y decide si puede entrar:

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
        // guest: solo usuarios NO autenticados pueden acceder, except('logout'): excepto el método logout
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Muestra el formulario de login
        // Retorna la vista resources/views/auth/login.blade.php
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        // only(): obtiene solo los campos email y password del formulario
        
        $remember = $request->boolean('remember');
        // boolean(): convierte el checkbox "recordarme" a true/false

        if (Auth::attempt($credentials, $remember)) {
            // Auth::attempt(): intenta autenticar con las credenciales
            // $remember: si es true, crea un token de "recordarme"
            
            $request->session()->regenerate();
            // regenerate(): crea una nueva sesión por seguridad (previene session fixation)

            // Verificar si el usuario está activo
            if (!Auth::user()->is_active) {
                // Auth::user(): obtiene el usuario autenticado actual
                Auth::logout(); // Cierra la sesión
                return back()->withErrors([
                    'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
                ]); // withErrors(): agrega errores a la sesión para mostrar en el formulario
            }

            // Redirigir según el rol
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
                // intended(): redirige a la URL que intentaba acceder antes del login
            }

            return redirect()->intended(RouteServiceProvider::HOME);
            // HOME: ruta por defecto después del login (ej: /dashboard)
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
        // back(): regresa a la página anterior, onlyInput(): mantiene solo el email en el formulario
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario

        $request->session()->invalidate();
        // invalidate(): invalida toda la sesión (borra todos los datos)

        $request->session()->regenerateToken();
        // regenerateToken(): crea un nuevo token CSRF por seguridad

        return redirect('/'); // Redirige a la página principal
    }
}
```

**Explicación detallada del controlador:**

**Constructor (`__construct`):**
- **`middleware('guest')`**: Solo usuarios NO autenticados pueden acceder a los métodos
- **`except('logout')`**: El método logout puede ser accedido por usuarios autenticados
- **¿Por qué?**: Un usuario no autenticado no puede hacer logout, pero un usuario autenticado sí

**Método `showLoginForm()`:**
- **Función**: Muestra el formulario de login
- **Retorna**: La vista `auth.login`
- **¿Cuándo se usa?**: Cuando el usuario visita `/login`

**Método `login()`:**
- **Parámetro**: `LoginRequest` (validación automática)
- **`$credentials`**: Extrae email y password del formulario
- **`$remember`**: Convierte checkbox a boolean
- **`Auth::attempt()`**: Intenta autenticar al usuario
- **`session()->regenerate()`**: Crea nueva sesión por seguridad
- **Verificación de cuenta activa**: Previene login de cuentas desactivadas
- **Redirección por rol**: Diferentes páginas según el tipo de usuario
- **Manejo de errores**: Muestra mensajes si las credenciales son incorrectas

**Método `logout()`:**
- **`Auth::logout()`**: Cierra la sesión del usuario
- **`session()->invalidate()`**: Borra todos los datos de la sesión
- **`session()->regenerateToken()`**: Crea nuevo token CSRF
- **Redirección**: Envía al usuario a la página principal

**Flujo de autenticación:**
1. Usuario visita `/login` → `showLoginForm()`
2. Usuario envía formulario → `login()`
3. Se validan credenciales → `Auth::attempt()`
4. Si son correctas → crear sesión y redirigir
5. Si son incorrectas → mostrar error y volver al formulario

**Seguridad implementada:**
- **Regeneración de sesión**: Previene session fixation
- **Verificación de cuenta activa**: Previene login de cuentas desactivadas
- **Regeneración de token CSRF**: Previene ataques CSRF
- **Middleware guest**: Previene acceso a usuarios ya autenticados

### 3. **Form Request para Login**

El Form Request es una clase especial que maneja la validación de formularios. Es como un "inspector" que verifica que los datos sean correctos antes de llegar al controlador:

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite que cualquier usuario pueda hacer login
        // Retorna true si el usuario está autorizado para hacer esta petición
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            // required: el campo es obligatorio, string: debe ser texto, email: debe ser formato de email válido
            
            'password' => 'required|string|min:8',
            // required: el campo es obligatorio, string: debe ser texto, min:8: mínimo 8 caracteres
            
            'remember' => 'boolean',
            // boolean: debe ser true/false (checkbox)
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio.',
            // Mensaje personalizado cuando el email está vacío
            
            'email.email' => 'El formato del email no es válido.',
            // Mensaje personalizado cuando el email no tiene formato válido
            
            'password.required' => 'La contraseña es obligatoria.',
            // Mensaje personalizado cuando la contraseña está vacía
            
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            // Mensaje personalizado cuando la contraseña es muy corta
        ];
    }
}
```

**Explicación detallada del Form Request:**

**Método `authorize()`:**
- **Función**: Determina si el usuario puede hacer esta petición
- **Retorna**: `true` (cualquiera puede intentar hacer login)
- **¿Cuándo retornar `false`?**: Cuando quieres restringir el acceso (ej: solo usuarios invitados)

**Método `rules()`:**
- **Función**: Define las reglas de validación para cada campo
- **`required`**: El campo no puede estar vacío
- **`string`**: El valor debe ser texto
- **`email`**: El valor debe tener formato de email válido
- **`min:8`**: El valor debe tener al menos 8 caracteres
- **`boolean`**: El valor debe ser true/false

**Método `messages()`:**
- **Función**: Define mensajes personalizados para cada error
- **Formato**: `'campo.regla' => 'mensaje personalizado'`
- **Ventaja**: Mensajes en español y más amigables para el usuario

**¿Cómo funciona?**
1. Usuario envía formulario → Laravel crea instancia de `LoginRequest`
2. Se ejecuta `authorize()` → Verifica si puede hacer la petición
3. Se ejecuta `rules()` → Valida los datos según las reglas
4. Si hay errores → Muestra mensajes personalizados
5. Si todo está bien → Pasa al controlador

**Ventajas de usar Form Request:**
- **Separación de responsabilidades**: Validación separada del controlador
- **Reutilización**: Se puede usar en múltiples controladores
- **Mensajes personalizados**: Mejor experiencia de usuario
- **Autorización**: Control de quién puede hacer qué
- **Lógica compleja**: Puedes agregar validaciones personalizadas

**Reglas de validación comunes:**
- **`required`**: Campo obligatorio
- **`string`**: Debe ser texto
- **`email`**: Formato de email válido
- **`min:X`**: Mínimo X caracteres
- **`max:X`**: Máximo X caracteres
- **`unique:table,column`**: Valor único en la base de datos
- **`confirmed`**: Debe coincidir con campo_confirmation
- **`boolean`**: Debe ser true/false

### 4. **Controlador de Registro**

El controlador de registro maneja la creación de nuevas cuentas de usuario. Es como el "oficina de registro" donde se crean nuevas identidades:

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
        $this->middleware('guest'); // Solo usuarios NO autenticados pueden registrarse
        // guest: middleware que verifica que el usuario NO esté autenticado
    }

    public function showRegistrationForm()
    {
        return view('auth.register'); // Muestra el formulario de registro
        // Retorna la vista resources/views/auth/register.blade.php
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,           // Nombre del usuario
            'email' => $request->email,         // Email único
            'password' => Hash::make($request->password), // Contraseña hasheada
            'role' => 'customer',               // Rol por defecto (cliente)
            'is_active' => true,                // Cuenta activa por defecto
        ]);
        // User::create(): crea un nuevo usuario en la base de datos
        // Hash::make(): convierte la contraseña en texto plano a hash seguro

        event(new Registered($user));
        // new Registered($user): dispara evento de registro (envía email de verificación)

        Auth::login($user);
        // Auth::login(): inicia sesión automáticamente después del registro

        return redirect(RouteServiceProvider::HOME);
        // redirect(): redirige al usuario a la página principal después del registro
    }
}
```

**Explicación detallada del controlador:**

**Constructor (`__construct`):**
- **`middleware('guest')`**: Solo usuarios NO autenticados pueden acceder
- **¿Por qué?**: Un usuario ya autenticado no necesita registrarse

**Método `showRegistrationForm()`:**
- **Función**: Muestra el formulario de registro
- **Retorna**: La vista `auth.register`
- **¿Cuándo se usa?**: Cuando el usuario visita `/register`

**Método `register()`:**
- **Parámetro**: `RegisterRequest` (validación automática)
- **`User::create()`**: Crea nuevo usuario en la base de datos
- **`Hash::make()`**: Convierte contraseña a hash seguro
- **`event(new Registered())`**: Dispara evento de registro
- **`Auth::login()`**: Inicia sesión automáticamente
- **Redirección**: Envía al usuario a la página principal

**Proceso de registro:**
1. Usuario visita `/register` → `showRegistrationForm()`
2. Usuario envía formulario → `register()`
3. Se validan datos → `RegisterRequest`
4. Se crea usuario → `User::create()`
5. Se dispara evento → `Registered` (envía email)
6. Se inicia sesión → `Auth::login()`
7. Se redirige → Página principal

**Seguridad implementada:**
- **Middleware guest**: Previene registro de usuarios ya autenticados
- **Hash de contraseña**: Almacena contraseña de forma segura
- **Validación**: Verifica datos antes de crear usuario
- **Evento de registro**: Permite acciones adicionales (email, logs, etc.)

**Campos del usuario:**
- **`name`**: Nombre completo del usuario
- **`email`**: Email único (validado en RegisterRequest)
- **`password`**: Contraseña hasheada (no se almacena en texto plano)
- **`role`**: Rol por defecto ('customer')
- **`is_active`**: Cuenta activa por defecto (true)

**¿Por qué usar Hash::make()?**
- **Seguridad**: Las contraseñas nunca se almacenan en texto plano
- **Estándar**: Usa algoritmos seguros (bcrypt por defecto)
- **Laravel**: Verifica automáticamente con `Hash::check()`
- **Recomendación**: Nunca almacenes contraseñas sin hashear

### 5. **Form Request para Registro**

El Form Request para registro es más estricto que el de login porque necesita validar la creación de una nueva cuenta. Es como un "inspector más exigente" para nuevos usuarios:

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cualquier usuario puede intentar registrarse
        // Retorna true si el usuario está autorizado para registrarse
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // required: obligatorio, string: debe ser texto, max:255: máximo 255 caracteres
            
            'email' => 'required|string|email|max:255|unique:users',
            // required: obligatorio, string: texto, email: formato válido, max:255: máximo caracteres, unique:users: debe ser único en tabla users
            
            'password' => ['required', 'confirmed', Password::defaults()],
            // required: obligatorio, confirmed: debe coincidir con password_confirmation, Password::defaults(): reglas de contraseña segura
            
            'terms' => 'required|accepted',
            // required: obligatorio, accepted: checkbox debe estar marcado
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            // Mensaje cuando el nombre está vacío
            
            'email.required' => 'El email es obligatorio.',
            // Mensaje cuando el email está vacío
            
            'email.email' => 'El formato del email no es válido.',
            // Mensaje cuando el email no tiene formato válido
            
            'email.unique' => 'Este email ya está registrado.',
            // Mensaje cuando el email ya existe en la base de datos
            
            'password.required' => 'La contraseña es obligatoria.',
            // Mensaje cuando la contraseña está vacía
            
            'password.confirmed' => 'Las contraseñas no coinciden.',
            // Mensaje cuando password y password_confirmation no coinciden
            
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            // Mensaje cuando la contraseña es muy corta
            
            'terms.required' => 'Debes aceptar los términos y condiciones.',
            // Mensaje cuando no se aceptan los términos
        ];
    }
}
```

**Explicación detallada del Form Request:**

**Método `authorize()`:**
- **Función**: Determina si el usuario puede registrarse
- **Retorna**: `true` (cualquiera puede intentar registrarse)
- **¿Cuándo retornar `false`?**: Si quieres restringir registros (ej: solo invitaciones)

**Método `rules()`:**
- **`name`**: Nombre completo del usuario
  - **`required`**: No puede estar vacío
  - **`string`**: Debe ser texto
  - **`max:255`**: Máximo 255 caracteres

- **`email`**: Email único del usuario
  - **`required`**: No puede estar vacío
  - **`string`**: Debe ser texto
  - **`email`**: Formato de email válido
  - **`max:255`**: Máximo 255 caracteres
  - **`unique:users`**: Debe ser único en la tabla users

- **`password`**: Contraseña segura
  - **`required`**: No puede estar vacía
  - **`confirmed`**: Debe coincidir con password_confirmation
  - **`Password::defaults()`**: Reglas de contraseña segura (mínimo 8 caracteres, etc.)

- **`terms`**: Aceptación de términos
  - **`required`**: No puede estar vacío
  - **`accepted`**: Checkbox debe estar marcado

**Método `messages()`:**
- **Función**: Define mensajes personalizados para cada error
- **Formato**: `'campo.regla' => 'mensaje personalizado'`
- **Ventaja**: Mensajes en español y específicos para registro

**Reglas de contraseña segura (`Password::defaults()`):**
- **Mínimo 8 caracteres**
- **Al menos una letra**
- **Al menos un número**
- **Al menos un símbolo especial**
- **No puede ser contraseña común**

**Validación `confirmed`:**
- Requiere campo `password_confirmation` en el formulario
- Ambos campos deben coincidir exactamente
- Previene errores de tipeo en contraseñas

**Validación `unique:users`:**
- Verifica que el email no exista en la tabla users
- Previene duplicados de cuentas
- Importante para mantener emails únicos

**¿Por qué estas validaciones?**
- **Seguridad**: Contraseñas fuertes y únicas
- **Integridad**: Emails únicos en la base de datos
- **Experiencia**: Mensajes claros para el usuario
- **Cumplimiento**: Aceptación de términos legales

## 🔧 **Middleware de Autenticación**

El middleware de autenticación actúa como un "guardián" que protege las rutas. Verifica si el usuario está autenticado antes de permitir el acceso. Es como un "portero" que revisa la identificación antes de entrar a una zona restringida.

### 1. **Middleware de Autenticación Básico**

Este middleware verifica si el usuario está autenticado y maneja tanto peticiones web como API:

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
            // Auth::check(): verifica si hay un usuario autenticado
            // Retorna true si el usuario está logueado, false si no
            
            if ($request->expectsJson()) {
                // expectsJson(): verifica si la petición espera respuesta JSON (API)
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
                // response()->json(): retorna respuesta JSON con código 401 (No autorizado)
            }
            
            return redirect()->route('login');
            // redirect()->route(): redirige a la ruta 'login' para peticiones web
        }

        return $next($request);
        // $next($request): continúa con la petición si el usuario está autenticado
    }
}
```

**Explicación detallada del middleware:**

**Método `handle()`:**
- **Función**: Procesa cada petición HTTP
- **Parámetros**: `$request` (datos de la petición), `$next` (función para continuar)
- **Retorna**: Respuesta HTTP (JSON o redirección)

**`Auth::check()`:**
- **Función**: Verifica si hay un usuario autenticado
- **Retorna**: `true` si está logueado, `false` si no
- **¿Cómo funciona?**: Revisa la sesión actual del usuario

**`$request->expectsJson()`:**
- **Función**: Detecta si la petición espera respuesta JSON
- **Retorna**: `true` para APIs, `false` para páginas web
- **¿Por qué?**: Permite respuestas diferentes para web y API

**Respuestas del middleware:**
- **Usuario autenticado**: Continúa con la petición (`$next($request)`)
- **Usuario no autenticado + API**: Respuesta JSON con código 401
- **Usuario no autenticado + Web**: Redirección a página de login

**Flujo del middleware:**
1. Petición llega al middleware
2. Se verifica si el usuario está autenticado
3. Si está autenticado → continúa la petición
4. Si no está autenticado → redirige o retorna error

**¿Cuándo se ejecuta?**
- Antes de llegar al controlador
- En cada petición a rutas protegidas
- Se puede aplicar a rutas individuales o grupos

**Ventajas del middleware:**
- **Reutilización**: Se puede usar en múltiples rutas
- **Centralización**: Lógica de autenticación en un lugar
- **Flexibilidad**: Diferentes respuestas para web y API
- **Seguridad**: Protección automática de rutas

### 2. **Middleware de Verificación de Email**

Este middleware verifica que el usuario haya confirmado su email antes de acceder a ciertas funcionalidades. Es como un "segundo nivel de verificación" después del login:

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
            // $request->user(): obtiene el usuario autenticado actual
            // hasVerifiedEmail(): verifica si el email ha sido confirmado
            // Si no hay usuario O el email no está verificado
            
            if ($request->expectsJson()) {
                // Si es petición de API
                return response()->json([
                    'message' => 'Tu email debe ser verificado.'
                ], 409);
                // Código 409: Conflict (el email no está verificado)
            }
            
            return redirect()->route('verification.notice');
            // Redirige a la página que explica que debe verificar su email
        }

        return $next($request);
        // Si el email está verificado, continúa con la petición
    }
}
```

**Explicación detallada del middleware:**

**Método `handle()`:**
- **Función**: Verifica que el email esté confirmado
- **Condición**: `!$request->user() || !$request->user()->hasVerifiedEmail()`
- **¿Por qué dos condiciones?**: Por si no hay usuario autenticado

**`$request->user()`:**
- **Función**: Obtiene el usuario autenticado actual
- **Retorna**: Modelo User o null si no está autenticado
- **¿Cuándo es null?**: Si el middleware se ejecuta sin autenticación

**`hasVerifiedEmail()`:**
- **Función**: Verifica si el email ha sido confirmado
- **Retorna**: `true` si está verificado, `false` si no
- **¿Cómo funciona?**: Revisa el campo `email_verified_at` en la base de datos

**Respuestas del middleware:**
- **Email verificado**: Continúa con la petición
- **Email no verificado + API**: Respuesta JSON con código 409
- **Email no verificado + Web**: Redirección a página de verificación

**Código de estado 409:**
- **Significado**: Conflict (conflicto)
- **Uso**: Cuando el recurso existe pero no está en el estado correcto
- **Aplicación**: Email existe pero no está verificado

**Flujo de verificación de email:**
1. Usuario se registra → Se envía email de verificación
2. Usuario hace clic en el enlace → Se marca email como verificado
3. `email_verified_at` se actualiza en la base de datos
4. `hasVerifiedEmail()` retorna `true`

**¿Cuándo usar este middleware?**
- **Funcionalidades críticas**: Pagos, configuraciones importantes
- **Datos sensibles**: Información personal, documentos
- **Acciones importantes**: Crear contenido, hacer transacciones
- **Cumplimiento legal**: Cuando se requiere verificación de email

**Ventajas de la verificación de email:**
- **Seguridad**: Confirma que el email es real
- **Comunicación**: Permite enviar notificaciones importantes
- **Recuperación**: Facilita reset de contraseñas
- **Confianza**: Usuarios verificados son más confiables

### 3. **Middleware de Verificación de Contraseña**

Este middleware requiere que el usuario confirme su contraseña antes de acceder a funcionalidades sensibles. Es como un "segundo factor de autenticación" para acciones importantes:

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
            // session()->has(): verifica si existe una clave en la sesión
            // 'auth.password_confirmed_at': marca de tiempo cuando se confirmó la contraseña
            // Si no existe esta marca, significa que no se ha confirmado la contraseña
            
            if ($request->expectsJson()) {
                // Si es petición de API
                return response()->json([
                    'message' => 'Confirmación de contraseña requerida'
                ], 409);
                // Código 409: Conflict (se requiere confirmación adicional)
            }
            
            return redirect()->route('password.confirm');
            // Redirige a la página donde debe confirmar su contraseña
        }

        return $next($request);
        // Si la contraseña está confirmada, continúa con la petición
    }
}
```

**Explicación detallada del middleware:**

**Método `handle()`:**
- **Función**: Verifica que la contraseña haya sido confirmada recientemente
- **Condición**: `!$request->session()->has('auth.password_confirmed_at')`
- **¿Por qué?**: Para acciones sensibles que requieren verificación adicional

**`$request->session()->has()`:**
- **Función**: Verifica si existe una clave en la sesión
- **Parámetro**: `'auth.password_confirmed_at'`
- **Retorna**: `true` si existe, `false` si no
- **¿Qué contiene?**: Timestamp de cuando se confirmó la contraseña

**`auth.password_confirmed_at`:**
- **Función**: Marca de tiempo de confirmación de contraseña
- **Formato**: Timestamp (ej: 1640995200)
- **¿Cuándo se crea?**: Cuando el usuario confirma su contraseña
- **¿Cuándo se borra?**: Al cerrar sesión o después de cierto tiempo

**Respuestas del middleware:**
- **Contraseña confirmada**: Continúa con la petición
- **Contraseña no confirmada + API**: Respuesta JSON con código 409
- **Contraseña no confirmada + Web**: Redirección a confirmación

**Flujo de confirmación de contraseña:**
1. Usuario intenta acceder a funcionalidad sensible
2. Middleware detecta que no ha confirmado contraseña
3. Se redirige a página de confirmación
4. Usuario ingresa su contraseña
5. Se crea `auth.password_confirmed_at` en la sesión
6. Usuario puede acceder a la funcionalidad

**¿Cuándo usar este middleware?**
- **Configuraciones de cuenta**: Cambiar email, contraseña
- **Acciones financieras**: Pagos, transferencias
- **Datos sensibles**: Información personal, documentos
- **Acciones destructivas**: Eliminar cuenta, datos
- **Acceso administrativo**: Panel de administración

**Ventajas de la confirmación de contraseña:**
- **Seguridad adicional**: Doble verificación para acciones sensibles
- **Prevención de errores**: Evita acciones accidentales
- **Auditoría**: Registra cuándo se confirmó la contraseña
- **Flexibilidad**: Se puede configurar el tiempo de validez

**Configuración de tiempo:**
```php
// En el controlador de confirmación
$request->session()->put('auth.password_confirmed_at', time());

// Verificar si ha expirado (ej: 1 hora)
$confirmedAt = $request->session()->get('auth.password_confirmed_at');
if (time() - $confirmedAt > 3600) {
    // Ha expirado, requerir nueva confirmación
}
```

## 👥 **Roles y Permisos**

Los roles y permisos definen qué puede hacer cada usuario en la aplicación. Es como un "sistema de niveles de acceso" donde diferentes usuarios tienen diferentes capacidades.

### 1. **Middleware de Roles**

Este middleware verifica que el usuario tenga un rol específico antes de permitir el acceso. Es como un "guardián especializado" que solo permite ciertos tipos de usuarios:

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
            // $request->user(): obtiene el usuario autenticado
            // hasRole($role): verifica si el usuario tiene el rol especificado
            // Si no hay usuario O no tiene el rol requerido
            
            if ($request->expectsJson()) {
                // Si es petición de API
                return response()->json([
                    'message' => 'Acceso denegado. Rol requerido: ' . $role
                ], 403);
                // Código 403: Forbidden (prohibido)
            }
            
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder a esta página.');
            // redirect()->route(): redirige a la página principal
            // with('error'): agrega mensaje de error a la sesión
        }

        return $next($request);
        // Si tiene el rol requerido, continúa con la petición
    }
}
```

**Explicación detallada del middleware:**

**Método `handle()`:**
- **Función**: Verifica que el usuario tenga un rol específico
- **Parámetros**: `$request`, `$next`, `$role` (rol requerido)
- **Condición**: `!$request->user() || !$request->user()->hasRole($role)`

**`$role` (parámetro):**
- **Función**: Rol requerido para acceder a la ruta
- **Ejemplos**: `'admin'`, `'customer'`, `'moderator'`
- **¿Cómo se pasa?**: En la definición de la ruta

**`hasRole($role)`:**
- **Función**: Verifica si el usuario tiene el rol especificado
- **Retorna**: `true` si tiene el rol, `false` si no
- **¿Dónde se define?**: En el modelo User

**Respuestas del middleware:**
- **Usuario con rol correcto**: Continúa con la petición
- **Usuario sin rol + API**: Respuesta JSON con código 403
- **Usuario sin rol + Web**: Redirección con mensaje de error

**Código de estado 403:**
- **Significado**: Forbidden (prohibido)
- **Uso**: Cuando el usuario no tiene permisos para el recurso
- **Diferencia con 401**: 401 = no autenticado, 403 = no autorizado

**Ejemplo de uso en rutas:**
```php
// Ruta que requiere rol de administrador
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('role:admin');

// Ruta que requiere rol de cliente
Route::get('/customer/profile', [CustomerController::class, 'profile'])
    ->middleware('role:customer');
```

**Flujo del middleware:**
1. Petición llega al middleware
2. Se verifica si hay usuario autenticado
3. Se verifica si el usuario tiene el rol requerido
4. Si tiene el rol → continúa la petición
5. Si no tiene el rol → redirige o retorna error

**Ventajas del middleware de roles:**
- **Control granular**: Diferentes niveles de acceso
- **Seguridad**: Previene acceso no autorizado
- **Flexibilidad**: Se puede usar en cualquier ruta
- **Mantenibilidad**: Cambios de roles en un solo lugar

**Tipos de roles comunes:**
- **`admin`**: Acceso completo a la aplicación
- **`customer`**: Usuario regular con acceso limitado
- **`moderator`**: Usuario con permisos intermedios
- **`guest`**: Usuario no autenticado

### 2. **Middleware de Permisos**

Este middleware verifica que el usuario tenga un permiso específico antes de permitir una acción. Es como un "sistema de permisos granulares" que controla acciones específicas:

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
            // $request->user(): obtiene el usuario autenticado
            // can($permission): verifica si el usuario tiene el permiso especificado
            // Si no hay usuario O no tiene el permiso requerido
            
            if ($request->expectsJson()) {
                // Si es petición de API
                return response()->json([
                    'message' => 'Permiso denegado: ' . $permission
                ], 403);
                // Código 403: Forbidden (permiso denegado)
            }
            
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para realizar esta acción.');
            // redirect()->route(): redirige a la página principal
            // with('error'): agrega mensaje de error a la sesión
        }

        return $next($request);
        // Si tiene el permiso requerido, continúa con la petición
    }
}
```

**Explicación detallada del middleware:**

**Método `handle()`:**
- **Función**: Verifica que el usuario tenga un permiso específico
- **Parámetros**: `$request`, `$next`, `$permission` (permiso requerido)
- **Condición**: `!$request->user() || !$request->user()->can($permission)`

**`$permission` (parámetro):**
- **Función**: Permiso requerido para realizar la acción
- **Ejemplos**: `'create services'`, `'edit users'`, `'delete orders'`
- **¿Cómo se pasa?**: En la definición de la ruta

**`can($permission)`:**
- **Función**: Verifica si el usuario tiene el permiso especificado
- **Retorna**: `true` si tiene el permiso, `false` si no
- **¿Dónde se define?**: En el modelo User o usando Spatie Permission

**Diferencia entre Roles y Permisos:**
- **Roles**: Grupos de permisos (ej: admin, customer)
- **Permisos**: Acciones específicas (ej: create, edit, delete)
- **Relación**: Un rol puede tener múltiples permisos

**Ejemplo de uso en rutas:**
```php
// Ruta que requiere permiso para crear servicios
Route::post('/services', [ServiceController::class, 'store'])
    ->middleware('permission:create services');

// Ruta que requiere permiso para editar usuarios
Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('permission:edit users');

// Ruta que requiere múltiples permisos
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])
    ->middleware('permission:delete services');
```

**Flujo del middleware:**
1. Petición llega al middleware
2. Se verifica si hay usuario autenticado
3. Se verifica si el usuario tiene el permiso requerido
4. Si tiene el permiso → continúa la petición
5. Si no tiene el permiso → redirige o retorna error

**Ventajas del middleware de permisos:**
- **Control fino**: Permisos específicos para cada acción
- **Flexibilidad**: Un usuario puede tener algunos permisos pero no otros
- **Escalabilidad**: Fácil agregar nuevos permisos
- **Auditoría**: Registro claro de qué puede hacer cada usuario

**Tipos de permisos comunes:**
- **`create`**: Crear nuevos recursos
- **`read`**: Leer/ver recursos
- **`update`**: Modificar recursos existentes
- **`delete`**: Eliminar recursos
- **`manage`**: Gestionar completamente un recurso

**Ejemplo de permisos específicos:**
- **`create services`**: Crear nuevos servicios
- **`edit own services`**: Editar solo sus propios servicios
- **`delete any service`**: Eliminar cualquier servicio
- **`view users`**: Ver lista de usuarios
- **`manage users`**: Gestionar usuarios (crear, editar, eliminar)

### 3. **Sistema de Permisos con Spatie**

Spatie Permission es una librería popular que proporciona un sistema completo de roles y permisos. Es como tener un "sistema de permisos profesional" listo para usar:

**Instalación y configuración:**

```bash
# Instalar Spatie Permission
composer require spatie/laravel-permission
# composer require: instala el paquete de Spatie para roles y permisos
```

```bash
# Publicar migraciones
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
# vendor:publish: copia los archivos de configuración y migraciones del paquete
# --provider: especifica qué paquete publicar
```

```bash
# Ejecutar migraciones
php artisan migrate
# migrate: crea las tablas roles, permissions, role_has_permissions, model_has_roles, model_has_permissions
```

**Configurar el modelo User:**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles; // Agrega funcionalidad de roles y permisos al modelo
    // HasRoles: trait que proporciona métodos para manejar roles y permisos

    // ... resto del modelo
}
```

**Crear roles y permisos:**

```php
// Crear roles y permisos
php artisan tinker
# tinker: consola interactiva de Laravel para ejecutar código PHP

// Crear roles
Role::create(['name' => 'admin']);
Role::create(['name' => 'customer']);
Role::create(['name' => 'moderator']);
// Role::create(): crea un nuevo rol en la base de datos

// Crear permisos
Permission::create(['name' => 'create services']);
Permission::create(['name' => 'edit services']);
Permission::create(['name' => 'delete services']);
Permission::create(['name' => 'view users']);
Permission::create(['name' => 'edit users']);
// Permission::create(): crea un nuevo permiso en la base de datos

// Asignar permisos a roles
$adminRole = Role::findByName('admin');
$adminRole->givePermissionTo([
    'create services',
    'edit services', 
    'delete services',
    'view users',
    'edit users'
]);
// givePermissionTo(): asigna múltiples permisos a un rol

$customerRole = Role::findByName('customer');
$customerRole->givePermissionTo(['create services']);
// Los clientes solo pueden crear servicios
```

**Explicación detallada del sistema:**

**¿Qué es Spatie Permission?**
- **Librería**: Sistema completo de roles y permisos para Laravel
- **Funcionalidades**: Crear, asignar, verificar roles y permisos
- **Ventajas**: Probado, mantenido, bien documentado
- **Flexibilidad**: Soporta roles y permisos granulares

**Tablas creadas por las migraciones:**
- **`roles`**: Almacena los roles disponibles
- **`permissions`**: Almacena los permisos disponibles
- **`role_has_permissions`**: Relación muchos a muchos entre roles y permisos
- **`model_has_roles`**: Relación muchos a muchos entre usuarios y roles
- **`model_has_permissions`**: Relación muchos a muchos entre usuarios y permisos

**Métodos proporcionados por HasRoles:**
- **`hasRole($role)`**: Verifica si tiene un rol específico
- **`hasPermissionTo($permission)`**: Verifica si tiene un permiso específico
- **`assignRole($role)`**: Asigna un rol al usuario
- **`removeRole($role)`**: Remueve un rol del usuario
- **`syncRoles($roles)`**: Sincroniza roles (reemplaza todos)
- **`givePermissionTo($permission)`**: Asigna un permiso al usuario

**Ejemplo de uso en el código:**
```php
// Verificar roles
if ($user->hasRole('admin')) {
    // El usuario es administrador
}

// Verificar permisos
if ($user->hasPermissionTo('create services')) {
    // El usuario puede crear servicios
}

// Asignar roles
$user->assignRole('customer');

// Asignar permisos
$user->givePermissionTo('edit own services');

// Verificar múltiples roles
if ($user->hasAnyRole(['admin', 'moderator'])) {
    // El usuario es admin o moderador
}

// Verificar múltiples permisos
if ($user->hasAllPermissions(['create services', 'edit services'])) {
    // El usuario tiene ambos permisos
}
```

**Ventajas de usar Spatie Permission:**
- **Sistema completo**: Roles, permisos, y relaciones
- **Flexibilidad**: Permisos granulares y roles
- **Performance**: Caché automático de permisos
- **Mantenimiento**: Librería activamente mantenida
- **Documentación**: Excelente documentación y ejemplos

**Casos de uso comunes:**
- **Roles**: admin, customer, moderator, guest
- **Permisos**: create, read, update, delete para diferentes recursos
- **Permisos específicos**: edit own posts, view analytics, manage users
- **Permisos condicionales**: Solo en horario de trabajo, solo en ciertas ubicaciones

## 🛡️ **Protección de Rutas**

La protección de rutas es el proceso de controlar quién puede acceder a qué páginas o funcionalidades. Es como tener "puertas con diferentes niveles de seguridad" en tu aplicación.

### 1. **Rutas Protegidas por Autenticación**

Este ejemplo muestra cómo organizar las rutas según el nivel de autenticación requerido:

```php
// routes/web.php

// Rutas públicas (cualquiera puede acceder)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
// Estas rutas no requieren autenticación - cualquier visitante puede verlas

// Rutas de autenticación (solo usuarios NO autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
// guest: middleware que solo permite acceso a usuarios NO autenticados
// ¿Por qué? Un usuario ya logueado no necesita ver login/register

// Rutas protegidas (solo usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rutas que requieren verificación de email
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('services', ServiceController::class)->except(['index', 'show']);
    });
    // verified: middleware que requiere email verificado
    // resource: crea rutas CRUD completas (create, store, edit, update, destroy)
    // except: excluye las rutas index y show (ya están en rutas públicas)
});
```

**Explicación detallada de la organización:**

**Rutas públicas:**
- **Función**: Páginas que cualquier visitante puede ver
- **Ejemplos**: Página principal, lista de servicios, ver servicio específico
- **¿Por qué públicas?**: Para mostrar información sin requerir registro

**Rutas de autenticación:**
- **Función**: Páginas para login y registro
- **Middleware**: `guest` (solo usuarios NO autenticados)
- **¿Por qué guest?**: Un usuario ya logueado no necesita estas páginas

**Rutas protegidas:**
- **Función**: Páginas que requieren estar logueado
- **Middleware**: `auth` (solo usuarios autenticados)
- **Ejemplos**: Perfil, logout, dashboard

**Rutas con verificación de email:**
- **Función**: Páginas que requieren email verificado
- **Middleware**: `verified` (email confirmado)
- **Ejemplos**: Dashboard, crear/editar servicios

**Ventajas de esta organización:**
- **Claridad**: Fácil entender qué rutas requieren qué nivel de autenticación
- **Seguridad**: Protección automática de rutas sensibles
- **Mantenibilidad**: Cambios de seguridad en un solo lugar
- **Escalabilidad**: Fácil agregar nuevos niveles de protección

**Flujo de acceso:**
1. **Visitante**: Solo puede acceder a rutas públicas
2. **Usuario registrado**: Puede acceder a rutas protegidas
3. **Usuario verificado**: Puede acceder a todas las funcionalidades

**Middleware utilizados:**
- **`guest`**: Solo usuarios NO autenticados
- **`auth`**: Solo usuarios autenticados
- **`verified`**: Solo usuarios con email verificado
- **`role:admin`**: Solo usuarios con rol específico
- **`permission:create`**: Solo usuarios con permiso específico

### 2. **Rutas Protegidas por Roles**

Este ejemplo muestra cómo organizar las rutas según el rol del usuario. Es como tener "secciones separadas" para diferentes tipos de usuarios:

```php
// Rutas para administradores
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});
// auth: requiere autenticación, role:admin: requiere rol de administrador
// prefix('admin'): todas las rutas empiezan con /admin
// name('admin.'): todos los nombres de ruta empiezan con admin.

// Rutas para clientes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', OrderController::class);
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile');
});
// auth: requiere autenticación, role:customer: requiere rol de cliente
// prefix('customer'): todas las rutas empiezan con /customer
// name('customer.'): todos los nombres de ruta empiezan con customer.
```

**Explicación detallada de la organización:**

**Rutas de administradores:**
- **Middleware**: `['auth', 'role:admin']` (autenticado + rol admin)
- **Prefijo**: `/admin` (todas las URLs empiezan con /admin)
- **Nombre**: `admin.` (todos los nombres empiezan con admin.)
- **Funcionalidades**: Dashboard, gestión de usuarios, categorías, reportes

**Rutas de clientes:**
- **Middleware**: `['auth', 'role:customer']` (autenticado + rol customer)
- **Prefijo**: `/customer` (todas las URLs empiezan con /customer)
- **Nombre**: `customer.` (todos los nombres empiezan con customer.)
- **Funcionalidades**: Dashboard, gestión de pedidos, perfil

**URLs resultantes:**
- **Admin**: `/admin/dashboard`, `/admin/users`, `/admin/reports`
- **Customer**: `/customer/dashboard`, `/customer/orders`, `/customer/profile`

**Nombres de rutas resultantes:**
- **Admin**: `admin.dashboard`, `admin.users.index`, `admin.reports`
- **Customer**: `customer.dashboard`, `customer.orders.index`, `customer.profile`

**Ventajas de esta organización:**
- **Separación clara**: Cada rol tiene su propia sección
- **Seguridad**: Acceso controlado por rol
- **Organización**: URLs y nombres descriptivos
- **Mantenibilidad**: Fácil agregar nuevas funcionalidades por rol

**Flujo de acceso por rol:**
1. **Usuario se autentica** → Se verifica su rol
2. **Si es admin** → Accede a rutas /admin/*
3. **Si es customer** → Accede a rutas /customer/*
4. **Si no tiene rol** → No puede acceder a ninguna sección

**Middleware múltiple:**
- **`['auth', 'role:admin']`**: Array de middleware
- **Orden de ejecución**: Primero `auth`, luego `role:admin`
- **Si falla auth**: No se ejecuta `role:admin`
- **Si falla role**: Usuario autenticado pero sin rol correcto

**Prefijos y nombres:**
- **`prefix()`**: Agrega prefijo a todas las URLs del grupo
- **`name()`**: Agrega prefijo a todos los nombres de ruta
- **Ventaja**: Evita conflictos de nombres y URLs más organizadas

### 3. **Rutas Protegidas por Permisos**

Este ejemplo muestra cómo proteger rutas específicas con permisos granulares. Es como tener "candados específicos" para cada acción:

```php
// Rutas con permisos específicos
Route::middleware(['auth', 'permission:create services'])->group(function () {
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
});
// auth: requiere autenticación, permission:create services: requiere permiso específico
// Solo usuarios con permiso 'create services' pueden crear servicios

Route::middleware(['auth', 'permission:edit services'])->group(function () {
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
});
// permission:edit services: requiere permiso para editar servicios
// Solo usuarios con permiso 'edit services' pueden modificar servicios

Route::middleware(['auth', 'permission:delete services'])->group(function () {
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});
// permission:delete services: requiere permiso para eliminar servicios
// Solo usuarios con permiso 'delete services' pueden eliminar servicios
```

**Explicación detallada de la organización:**

**Permisos granulares:**
- **`create services`**: Solo crear nuevos servicios
- **`edit services`**: Solo modificar servicios existentes
- **`delete services`**: Solo eliminar servicios
- **Ventaja**: Control fino sobre cada acción

**Rutas por acción CRUD:**
- **CREATE**: `/services/create` (formulario) y `/services` (POST)
- **READ**: No protegida (pública para todos)
- **UPDATE**: `/services/{service}/edit` (formulario) y `/services/{service}` (PUT)
- **DELETE**: `/services/{service}` (DELETE)

**Flujo de verificación:**
1. **Usuario autenticado** → Se verifica con `auth`
2. **Permiso específico** → Se verifica con `permission:action resource`
3. **Si tiene permiso** → Puede acceder a la ruta
4. **Si no tiene permiso** → Acceso denegado

**Ventajas de permisos granulares:**
- **Control preciso**: Cada acción tiene su propio permiso
- **Flexibilidad**: Usuarios pueden tener algunos permisos pero no otros
- **Seguridad**: Acceso mínimo necesario para cada función
- **Auditoría**: Registro claro de qué puede hacer cada usuario

**Ejemplos de combinaciones de permisos:**
- **Editor**: `edit services` (puede editar pero no crear/eliminar)
- **Creador**: `create services` (puede crear pero no editar/eliminar)
- **Moderador**: `edit services`, `delete services` (puede editar y eliminar)
- **Administrador**: Todos los permisos

**Middleware de permisos:**
- **`permission:create services`**: Verifica permiso específico
- **`permission:edit services`**: Verifica permiso específico
- **`permission:delete services`**: Verifica permiso específico
- **Múltiples permisos**: `permission:create services|edit services`

**Casos de uso comunes:**
- **Solo crear**: Usuarios que pueden agregar contenido pero no modificarlo
- **Solo editar**: Moderadores que pueden corregir pero no eliminar
- **Solo eliminar**: Usuarios de limpieza que pueden eliminar contenido obsoleto
- **Combinaciones**: Diferentes niveles de acceso según responsabilidades

## 🔐 **Autenticación API con Sanctum**

Laravel Sanctum es el sistema oficial de Laravel para autenticación de APIs. Es como tener un "sistema de llaves" que permite a aplicaciones externas acceder a tu API de forma segura.

### 1. **Configuración de Sanctum**

Sanctum proporciona autenticación por tokens para APIs, aplicaciones móviles y SPAs (Single Page Applications):

```bash
# Instalar Sanctum
composer require laravel/sanctum
# composer require: instala el paquete oficial de Laravel para autenticación API
```

```bash
# Publicar configuración
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# vendor:publish: copia archivos de configuración del paquete
# --provider: especifica qué paquete publicar
```

```bash
# Ejecutar migraciones
php artisan migrate
# migrate: crea la tabla personal_access_tokens para almacenar tokens
```

**Explicación detallada de Sanctum:**

**¿Qué es Sanctum?**
- **Sistema oficial**: Desarrollado y mantenido por el equipo de Laravel
- **Autenticación API**: Permite autenticación por tokens
- **Múltiples clientes**: Soporta aplicaciones móviles, SPAs, APIs
- **Seguridad**: Tokens seguros con expiración y revocación

**Tabla creada:**
- **`personal_access_tokens`**: Almacena tokens de acceso personal
- **Campos**: id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at

**Configuración automática:**
- **Middleware**: Se registra automáticamente
- **Rutas**: Se configuran automáticamente
- **Modelo User**: Se agrega trait HasApiTokens automáticamente

**Casos de uso:**
- **APIs públicas**: Para aplicaciones móviles
- **SPAs**: Para aplicaciones de una sola página
- **Microservicios**: Para comunicación entre servicios
- **Webhooks**: Para notificaciones externas

### 2. **Controlador de Autenticación API**

Este controlador maneja la autenticación para APIs. Es como un "portero especializado" para aplicaciones que se comunican con tu backend:

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
        // where(): busca usuario por email, first(): obtiene el primer resultado

        if (!$user || !Hash::check($request->password, $user->password)) {
            // !$user: si no existe el usuario
            // Hash::check(): verifica si la contraseña coincide con el hash
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
            // ValidationException: lanza excepción de validación con mensaje personalizado
        }

        if (!$user->is_active) {
            // Verifica si la cuenta está activa
            return response()->json([
                'message' => 'Tu cuenta ha sido desactivada.'
            ], 403);
            // response()->json(): retorna respuesta JSON con código 403 (Forbidden)
        }

        $token = $user->createToken($request->device_name ?? 'api-token')->plainTextToken;
        // createToken(): crea un token de acceso para el usuario
        // $request->device_name: nombre del dispositivo (opcional)
        // plainTextToken: obtiene el token en texto plano

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
        // Retorna usuario, token y tipo de token
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // validate(): valida los datos de entrada

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
        ]);
        // User::create(): crea nuevo usuario en la base de datos

        $token = $user->createToken('api-token')->plainTextToken;
        // Crea token automáticamente después del registro

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
        // Código 201: Created (recurso creado exitosamente)
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // currentAccessToken(): obtiene el token actual del usuario
        // delete(): elimina el token (revoca acceso)

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
        // Retorna información del usuario autenticado
    }
}
```

**Explicación detallada del controlador:**

**Método `login()`:**
- **Función**: Autentica usuario y retorna token
- **Proceso**: Busca usuario → Verifica contraseña → Verifica cuenta activa → Crea token
- **Retorna**: Usuario, token y tipo de token
- **Errores**: Credenciales incorrectas, cuenta desactivada

**Método `register()`:**
- **Función**: Registra nuevo usuario y retorna token
- **Proceso**: Valida datos → Crea usuario → Crea token
- **Retorna**: Usuario, token y código 201
- **Ventaja**: Login automático después del registro

**Método `logout()`:**
- **Función**: Revoca el token actual
- **Proceso**: Obtiene token actual → Lo elimina
- **Retorna**: Mensaje de confirmación
- **Seguridad**: El token ya no puede ser usado

**Método `user()`:**
- **Función**: Retorna información del usuario autenticado
- **Uso**: Para verificar estado de autenticación
- **Retorna**: Datos del usuario actual

**Flujo de autenticación API:**
1. **Cliente envía credenciales** → POST /api/login
2. **Servidor valida** → Verifica email y contraseña
3. **Servidor crea token** → Token único para el usuario
4. **Servidor retorna token** → Cliente lo almacena
5. **Cliente usa token** → En header Authorization: Bearer {token}
6. **Servidor valida token** → En cada petición posterior

**Ventajas de autenticación por token:**
- **Sin estado**: No requiere sesiones en el servidor
- **Escalabilidad**: Funciona con múltiples servidores
- **Flexibilidad**: Tokens pueden tener diferentes permisos
- **Seguridad**: Tokens pueden expirar y ser revocados

**Tipos de respuesta:**
- **200 OK**: Operación exitosa
- **201 Created**: Recurso creado
- **401 Unauthorized**: Credenciales incorrectas
- **403 Forbidden**: Cuenta desactivada
- **422 Unprocessable Entity**: Errores de validación

### 3. **Rutas API Protegidas**

Este ejemplo muestra cómo organizar las rutas de API con diferentes niveles de protección. Es como tener "puertas de seguridad" para diferentes tipos de acceso:

```php
// routes/api.php

// Rutas públicas (no requieren autenticación)
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
// Estas rutas son públicas porque necesitamos que los usuarios puedan autenticarse

// Rutas protegidas (requieren token válido)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
    
    // Rutas de servicios
    Route::apiResource('services', ServiceApiController::class);
    Route::apiResource('orders', OrderApiController::class);
});
// auth:sanctum: middleware que verifica el token de Sanctum
// apiResource: crea rutas RESTful para API (sin create/edit)
```

**Explicación detallada de la organización:**

**Rutas públicas:**
- **Función**: Endpoints que no requieren autenticación
- **Ejemplos**: Login, registro, información pública
- **¿Por qué públicas?**: Los usuarios necesitan poder autenticarse sin token

**Rutas protegidas:**
- **Función**: Endpoints que requieren token válido
- **Middleware**: `auth:sanctum` (verifica token de Sanctum)
- **Ejemplos**: Logout, perfil de usuario, operaciones CRUD

**Middleware `auth:sanctum`:**
- **Función**: Verifica que el token de Sanctum sea válido
- **Proceso**: Lee header Authorization → Valida token → Obtiene usuario
- **Si es válido**: Continúa con la petición
- **Si no es válido**: Retorna 401 Unauthorized

**`apiResource()` vs `resource()`:**
- **`resource()`**: Crea rutas web completas (create, edit, show, etc.)
- **`apiResource()`**: Crea rutas API (index, store, show, update, destroy)
- **Diferencia**: API no necesita formularios de create/edit

**Rutas creadas por `apiResource('services')`:**
- **GET /api/services** → `index()` (lista todos)
- **POST /api/services** → `store()` (crea nuevo)
- **GET /api/services/{id}** → `show()` (muestra uno)
- **PUT/PATCH /api/services/{id}** → `update()` (actualiza)
- **DELETE /api/services/{id}** → `destroy()` (elimina)

**Flujo de autenticación API:**
1. **Cliente hace POST /api/login** → Obtiene token
2. **Cliente incluye token en headers** → Authorization: Bearer {token}
3. **Middleware verifica token** → Si es válido, continúa
4. **Controlador procesa petición** → Retorna respuesta JSON

**Headers requeridos:**
```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Ejemplo de petición autenticada:**
```javascript
fetch('/api/services', {
    method: 'GET',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Ventajas de esta organización:**
- **Seguridad**: Rutas sensibles protegidas por tokens
- **Flexibilidad**: Diferentes niveles de acceso
- **Escalabilidad**: Fácil agregar nuevas rutas protegidas
- **Mantenibilidad**: Lógica de autenticación centralizada

**Casos de uso comunes:**
- **Aplicaciones móviles**: Usan tokens para autenticación
- **SPAs**: JavaScript apps que consumen API
- **Microservicios**: Comunicación entre servicios
- **Webhooks**: Notificaciones externas con tokens

## 📝 **Comandos Útiles**

Estos comandos te ayudarán a trabajar eficientemente con el sistema de autenticación en Laravel:

```bash
# Instalar Breeze (sistema de autenticación completo)
composer require laravel/breeze --dev
php artisan breeze:install blade
# composer require: instala el paquete, --dev: solo para desarrollo
# breeze:install: instala el sistema completo con Blade templates

# Crear controladores de autenticación manualmente
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/RegisterController
# make:controller: crea controladores en app/Http/Controllers/Auth/

# Crear middleware personalizado
php artisan make:middleware CheckRole
php artisan make:middleware CheckPermission
# make:middleware: crea middleware en app/Http/Middleware/

# Instalar Spatie Permission (sistema de roles y permisos)
composer require spatie/laravel-permission
# Instala el paquete más popular para roles y permisos

# Instalar Sanctum (autenticación API)
composer require laravel/sanctum
# Instala el sistema oficial de Laravel para autenticación API

# Limpiar cache de rutas
php artisan route:clear
# Limpia el cache de rutas cuando agregas nuevas rutas

# Listar rutas con middleware
php artisan route:list --middleware
# Muestra todas las rutas y sus middleware asociados
```

**Explicación detallada de los comandos:**

**Instalación de Breeze:**
- **`composer require laravel/breeze --dev`**: Instala el paquete solo para desarrollo
- **`php artisan breeze:install blade`**: Instala con templates Blade (HTML tradicional)
- **¿Qué incluye?**: Controladores, vistas, rutas, middleware, validación

**Creación de controladores:**
- **`php artisan make:controller Auth/LoginController`**: Crea controlador en subdirectorio Auth
- **Ubicación**: `app/Http/Controllers/Auth/LoginController.php`
- **Ventaja**: Organización clara por funcionalidad

**Creación de middleware:**
- **`php artisan make:middleware CheckRole`**: Crea middleware personalizado
- **Ubicación**: `app/Http/Middleware/CheckRole.php`
- **Registro**: Se debe registrar en `app/Http/Kernel.php`

**Instalación de paquetes:**
- **Spatie Permission**: Sistema completo de roles y permisos
- **Sanctum**: Autenticación API oficial de Laravel
- **Ventaja**: Paquetes probados y mantenidos por la comunidad

**Comandos de mantenimiento:**
- **`route:clear`**: Limpia cache cuando agregas nuevas rutas
- **`route:list`**: Muestra todas las rutas registradas
- **`--middleware`**: Filtra rutas por middleware

**Comandos adicionales útiles:**
```bash
# Crear Form Request para validación
php artisan make:request Auth/LoginRequest

# Crear modelo User (si no existe)
php artisan make:model User -m

# Ejecutar migraciones
php artisan migrate

# Crear seeder para usuarios de prueba
php artisan make:seeder UserSeeder

# Ejecutar seeder
php artisan db:seed --class=UserSeeder

# Limpiar todos los caches
php artisan optimize:clear

# Verificar configuración de autenticación
php artisan config:show auth
```

## 🎯 **Resumen**

La autenticación en Laravel proporciona un sistema completo y robusto para manejar usuarios:

### ✅ **Características Principales**

**Sistema completo de autenticación:**
- **Login/Logout**: Entrada y salida de usuarios
- **Registro**: Creación de nuevas cuentas
- **Verificación de email**: Confirmación de emails
- **Reset de contraseñas**: Recuperación de acceso perdido
- **Gestión de sesiones**: Manejo seguro de sesiones

**Middleware de autorización:**
- **Autenticación básica**: Verificar si el usuario está logueado
- **Verificación de email**: Requerir email confirmado
- **Confirmación de contraseña**: Doble verificación para acciones sensibles
- **Roles y permisos**: Control granular de acceso

**Protección de rutas:**
- **Por autenticación**: Rutas que requieren estar logueado
- **Por roles**: Rutas específicas para ciertos tipos de usuarios
- **Por permisos**: Rutas que requieren permisos específicos
- **Flexibilidad**: Combinación de múltiples middleware

**Autenticación API:**
- **Sanctum**: Sistema oficial para APIs
- **Tokens seguros**: Autenticación sin estado
- **Escalabilidad**: Funciona con múltiples servidores
- **Flexibilidad**: Soporte para móviles, SPAs, microservicios

### 🚀 **Ventajas del Sistema**

**Seguridad:**
- **Protección CSRF**: Automática en formularios
- **Hash de contraseñas**: Almacenamiento seguro
- **Regeneración de sesiones**: Previene session fixation
- **Tokens seguros**: Para autenticación API

**Flexibilidad:**
- **Múltiples opciones**: Breeze, manual, Sanctum
- **Roles personalizados**: Sistema adaptable a necesidades
- **Permisos granulares**: Control fino de acceso
- **Middleware personalizable**: Lógica específica por aplicación

**Facilidad de uso:**
- **Configuración automática**: Laravel hace la mayor parte del trabajo
- **Comandos Artisan**: Herramientas para desarrollo rápido
- **Documentación completa**: Guías y ejemplos detallados
- **Comunidad activa**: Soporte y paquetes adicionales

### 📚 **Próximos Pasos**

**Implementación práctica:**
1. **Configurar autenticación**: Elegir entre Breeze o manual
2. **Definir roles y permisos**: Estructurar sistema de acceso
3. **Proteger rutas**: Aplicar middleware apropiados
4. **Configurar API**: Si se requiere autenticación API
5. **Personalizar vistas**: Adaptar interfaz a necesidades

**Exploración avanzada:**
- **OAuth/OpenID**: Autenticación con proveedores externos
- **Autenticación de dos factores**: Seguridad adicional
- **Auditoría de acceso**: Registro de acciones de usuarios
- **Rate limiting**: Protección contra ataques de fuerza bruta

**Integración con el proyecto:**
- **Sistema de servicios**: Autenticación para CRUD de servicios
- **Roles específicos**: Admin, proveedor, cliente
- **Permisos granulares**: Crear, editar, eliminar servicios
- **API para móviles**: Si se requiere aplicación móvil

¡Con este sistema de autenticación tienes una base sólida y segura para tu aplicación de servicios! 