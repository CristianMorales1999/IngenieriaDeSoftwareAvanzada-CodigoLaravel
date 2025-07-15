# 🛣️ Rutas y Parámetros en Laravel 12

## 📋 **Conceptos Básicos de Rutas**

### 🎯 **¿Qué son las Rutas?**

Las rutas son la forma en que Laravel mapea las URLs a controladores y métodos específicos. Son el punto de entrada de todas las peticiones HTTP. Cuando un usuario visita una URL, Laravel busca en las rutas para saber qué controlador y método debe ejecutar. Es como un "directorio telefónico" que le dice a Laravel qué hacer cuando alguien visita una URL específica.

**¿Por qué son importantes las rutas?**
- **Navegación**: Definen qué páginas existen en tu aplicación
- **Seguridad**: Controlan quién puede acceder a qué funcionalidades
- **Organización**: Mantienen tu código organizado y fácil de encontrar
- **SEO**: Las URLs amigables mejoran el posicionamiento en buscadores

### 📁 **Archivos de Rutas**

Laravel organiza las rutas en diferentes archivos según su propósito. Esta organización te ayuda a mantener el código limpio y fácil de entender:

```
routes/
├── web.php      # Rutas web (con sesión, CSRF, middleware web)
├── api.php      # Rutas API (sin sesión, para aplicaciones móviles/frontend)
├── console.php  # Comandos Artisan (tareas de consola)
└── channels.php # Canales de broadcasting (WebSockets, eventos en tiempo real)
```

**Explicación detallada de cada archivo:**

**web.php**: Contiene rutas para navegadores web con autenticación y protección CSRF. Estas son las rutas que usan los usuarios normales de tu aplicación. Incluyen sesiones, cookies y protección contra ataques CSRF.

**api.php**: Contiene rutas para APIs sin sesiones, usando tokens de autenticación. Estas rutas son para aplicaciones móviles, frontend JavaScript, o integraciones con otros sistemas. No incluyen sesiones web.

**console.php**: Define comandos personalizados que puedes ejecutar con `php artisan`. Útil para tareas de mantenimiento, importación de datos, o automatización.

**channels.php**: Configura comunicación en tiempo real con WebSockets. Para funcionalidades como chat, notificaciones en tiempo real, o actualizaciones automáticas.

## 🚀 **Tipos de Rutas Básicas**

### 📝 **Rutas GET (Leer)**

Las rutas GET se usan para obtener información. Son las más comunes y se ejecutan cuando un usuario visita una página. Los datos se envían en la URL, no en el cuerpo de la petición:

```php
// Ruta simple - Cuando alguien visita /servicios, ejecuta el método 'index' del ServicioController
Route::get('/servicios', [ServicioController::class, 'index']);

// Ruta con nombre - Permite generar URLs usando route('servicios.index')
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

// Ruta que retorna vista directamente - Sin controlador, devuelve una vista
Route::get('/about', function () {
    return view('about'); // Devuelve la vista 'about.blade.php'
});
```

**Explicación detallada:**

**GET**: Es el método HTTP más común para obtener datos. Se usa cuando quieres mostrar información al usuario sin modificar nada en el servidor.

**ServicioController::class**: Es la clase del controlador que manejará la petición. Laravel automáticamente instancia esta clase.

**'index'**: Es el método que se ejecutará en el controlador. Este método debe existir en la clase ServicioController.

**->name()**: Asigna un nombre a la ruta para poder referenciarla después. Es útil para generar URLs dinámicamente sin hardcodearlas.

**function ()**: Closure (función anónima) que se ejecuta directamente. Útil para rutas simples que no necesitan un controlador completo.

### 📝 **Rutas POST (Crear)**

Las rutas POST se usan para enviar datos al servidor, generalmente desde formularios. Los datos se envían en el cuerpo de la petición HTTP, no en la URL:

```php
// Crear nuevo servicio - Cuando se envía un formulario a /servicios
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// Formulario de contacto - Procesa el formulario de contacto
Route::post('/contacto', [ContactoController::class, 'enviar']);
```

**Explicación detallada:**

**POST**: Se usa para enviar datos al servidor (formularios, APIs). Los datos se envían en el cuerpo de la petición HTTP, no en la URL, lo que los hace más seguros para información sensible.

**Datos en el cuerpo**: A diferencia de GET, los datos no aparecen en la URL. Esto es importante para contraseñas, información personal, etc.

**Formularios HTML**: Generalmente se usa con formularios HTML (`<form method="POST">`). Laravel automáticamente incluye un token CSRF para proteger contra ataques.

**Protección CSRF**: Laravel incluye protección CSRF automática para rutas POST. Esto previene ataques de Cross-Site Request Forgery.

### 📝 **Rutas PUT/PATCH (Actualizar)**

Las rutas PUT y PATCH se usan para actualizar datos existentes. PUT actualiza todo el recurso, PATCH solo partes específicas:

```php
// Actualizar servicio completo - PUT actualiza todos los campos del servicio
Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');

// Actualizar parcialmente - PATCH actualiza solo algunos campos
Route::patch('/servicios/{id}', [ServicioController::class, 'update']);
```

**Explicación detallada:**

**PUT**: Actualiza completamente un recurso (todos los campos). Se usa cuando quieres reemplazar todo el recurso.

**PATCH**: Actualiza parcialmente un recurso (solo algunos campos). Se usa cuando quieres modificar solo algunos campos específicos.

**{id}**: Es un parámetro dinámico que se reemplaza con el ID real. Laravel automáticamente pasa este valor al método del controlador.

**Simulación en HTML**: Los navegadores solo soportan GET y POST nativamente. Para PUT/PATCH, se simulan con `<input type="hidden" name="_method" value="PUT">`.

### 📝 **Rutas DELETE (Eliminar)**

Las rutas DELETE se usan para eliminar recursos. Son importantes para operaciones CRUD completas:

```php
// Eliminar servicio - Elimina el servicio con el ID especificado
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
```

**Explicación detallada:**

**DELETE**: Se usa para eliminar recursos de la base de datos. Es la operación más destructiva, por lo que debe usarse con precaución.

**{id}**: Parámetro dinámico que identifica qué recurso eliminar. Debe ser único para evitar eliminar el recurso incorrecto.

**Simulación en HTML**: Se simula con `<input type="hidden" name="_method" value="DELETE">` o usando JavaScript.

**Confirmación**: Es importante confirmar antes de eliminar (diálogos de confirmación) para evitar eliminaciones accidentales.

## 🎯 **Parámetros en Rutas**

### 📌 **Parámetros Básicos**

Los parámetros en rutas permiten capturar valores dinámicos de la URL. Se definen entre llaves `{}` y te permiten crear URLs dinámicas:

```php
// Parámetro simple - Captura el ID del servicio en la URL
Route::get('/servicios/{id}', [ServicioController::class, 'show']);
// URL: /servicios/123 → $id = "123"

// Múltiples parámetros - Captura categoría e ID
Route::get('/servicios/{categoria}/{id}', [ServicioController::class, 'show']);
// URL: /servicios/consultoria/123 → $categoria = "consultoria", $id = "123"

// Parámetros opcionales - El parámetro puede estar presente o no
Route::get('/servicios/{id?}', [ServicioController::class, 'show']);
// URL: /servicios/123 → $id = "123"
// URL: /servicios → $id = null
```

**Explicación detallada:**

**{id}**: Parámetro obligatorio (la ruta no funciona sin él). Si alguien visita `/servicios/` sin ID, Laravel devolverá un error 404.

**{id?}**: Parámetro opcional (la ruta funciona con o sin él). El signo de interrogación hace que el parámetro sea opcional.

**Orden de parámetros**: Los parámetros se pasan al controlador en el mismo orden que aparecen en la URL.

**Tipos de datos**: Por defecto, los parámetros son strings. Puedes convertirlos a otros tipos en el controlador.

### 🔍 **Acceso a Parámetros en Controladores**

Los parámetros de la ruta se reciben como argumentos en el método del controlador. El orden y nombre deben coincidir exactamente:

```php
// Método con un parámetro - Recibe el ID de la ruta /servicios/{id}
public function show($id)
{
    $servicio = Servicio::find($id); // Busca el servicio por ID
    return view('servicios.show', compact('servicio')); // Devuelve la vista con el servicio
}

// Método con múltiples parámetros - Recibe categoría e ID de /servicios/{categoria}/{id}
public function show($categoria, $id)
{
    $servicio = Servicio::where('categoria', $categoria) // Filtra por categoría
                       ->find($id); // Luego busca por ID
    return view('servicios.show', compact('servicio'));
}
```

**Explicación detallada:**

**Coincidencia de nombres**: Los nombres de los parámetros en la ruta deben coincidir exactamente con los argumentos del método del controlador.

**Orden de argumentos**: El orden de los argumentos debe coincidir con el orden en la ruta. Laravel pasa los parámetros en el orden que aparecen en la URL.

**Uso en consultas**: Puedes usar estos parámetros para consultar la base de datos, filtrar resultados, o cualquier lógica de negocio.

**Validación**: Es importante validar los parámetros antes de usarlos para evitar errores o problemas de seguridad.

## 🎯 **Tipos de Parámetros**

### 📝 **Parámetros de String (Por defecto)**

Los parámetros son strings por defecto. Pueden contener cualquier carácter válido en una URL:

```php
Route::get('/servicios/{nombre}', [ServicioController::class, 'show']);
// /servicios/consultoria-it → $nombre = "consultoria-it"
```

**Explicación:** Los parámetros de string pueden contener letras, números, guiones y otros caracteres URL-safe. Son útiles para nombres, títulos, o cualquier texto.

### 🔢 **Parámetros Numéricos**

Puedes restringir parámetros para que solo acepten números usando expresiones regulares:

```php
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+');
// /servicios/123 → $id = 123
```

**Explicación:** La expresión regular `[0-9]+` significa "uno o más dígitos del 0 al 9". Esto asegura que solo se acepten números válidos.

### 📧 **Parámetros de Email**

Para validar que un parámetro sea un email válido:

```php
Route::get('/usuarios/{email}', [UserController::class, 'show'])
    ->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}');
```

**Explicación:** Esta expresión regular valida el formato básico de un email. Incluye letras, números, puntos, guiones y el símbolo @.

### 🔤 **Parámetros Alfabéticos**

Para parámetros que solo deben contener letras:

```php
Route::get('/categorias/{nombre}', [CategoriaController::class, 'show'])
    ->where('nombre', '[a-zA-Z]+');
```

**Explicación:** `[a-zA-Z]+` significa "una o más letras (mayúsculas o minúsculas)". Útil para nombres de categorías, idiomas, etc.

## 🔧 **Expresiones Regulares para Validación**

### 📋 **Patrones Comunes**

Las expresiones regulares te permiten validar parámetros de manera precisa. Aquí tienes los patrones más comunes:

#### 🔢 **Números**

```php
// Solo números
->where('id', '[0-9]+')

// Números con decimales
->where('precio', '[0-9]+(\.[0-9]{1,2})?')

// Números de teléfono
->where('telefono', '[0-9]{9,10}')
```

**Explicación de cada patrón:**

**`[0-9]+`**: Uno o más dígitos del 0 al 9. Para IDs, códigos numéricos, etc.

**`[0-9]+(\.[0-9]{1,2})?`**: Números con decimales opcionales. El `?` hace que la parte decimal sea opcional.

**`[0-9]{9,10}`**: Exactamente 9 o 10 dígitos. Para números de teléfono.

#### 📧 **Emails**

```php
->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}')
```

**Explicación:** Esta expresión valida el formato básico de email. Incluye caracteres válidos antes del @, el dominio y la extensión.

#### 📅 **Fechas**

```php
// Formato YYYY-MM-DD
->where('fecha', '[0-9]{4}-[0-9]{2}-[0-9]{2}')

// Formato DD/MM/YYYY
->where('fecha', '[0-9]{2}/[0-9]{2}/[0-9]{4}')
```

**Explicación:** Estas expresiones validan formatos específicos de fecha. Es importante validar fechas para evitar errores en el procesamiento.

#### 🔤 **Slugs (URLs amigables)**

```php
->where('slug', '[a-z0-9-]+')
```

**Explicación:** Para URLs amigables que solo contienen letras minúsculas, números y guiones. Útil para SEO.

### 🎯 **Ejemplos Prácticos**

#### 📊 **CRUD de Servicios con Validación**

Un ejemplo completo de cómo usar validación de parámetros en un CRUD completo:

```php
// Listar servicios
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

// Mostrar servicio específico
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('servicios.show');

// Crear servicio (formulario)
Route::get('/servicios/crear', [ServicioController::class, 'create'])->name('servicios.create');

// Guardar servicio
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// Editar servicio (formulario)
Route::get('/servicios/{id}/editar', [ServicioController::class, 'edit'])
    ->where('id', '[0-9]+')
    ->name('servicios.edit');

// Actualizar servicio
Route::put('/servicios/{id}', [ServicioController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('servicios.update');

// Eliminar servicio
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])
    ->where('id', '[0-9]+')
    ->name('servicios.destroy');
```

**Explicación del CRUD completo:**

**index**: Lista todos los servicios (GET /servicios)
**show**: Muestra un servicio específico (GET /servicios/{id})
**create**: Muestra formulario de creación (GET /servicios/crear)
**store**: Guarda nuevo servicio (POST /servicios)
**edit**: Muestra formulario de edición (GET /servicios/{id}/editar)
**update**: Actualiza servicio existente (PUT /servicios/{id})
**destroy**: Elimina servicio (DELETE /servicios/{id})

## 🚀 **Route Resource (Rutas Automáticas)**

### 📋 **Generar todas las rutas CRUD**

Laravel proporciona una forma rápida de crear todas las rutas CRUD con un solo comando:

```php
Route::resource('servicios', ServicioController::class);
```

**Esto genera automáticamente todas estas rutas:**

```
GET    /servicios              → index()   → servicios.index
GET    /servicios/create       → create()  → servicios.create
POST   /servicios              → store()   → servicios.store
GET    /servicios/{id}         → show()    → servicios.show
GET    /servicios/{id}/edit    → edit()    → servicios.edit
PUT    /servicios/{id}         → update()  → servicios.update
DELETE /servicios/{id}         → destroy() → servicios.destroy
```

**Explicación de cada ruta generada:**

**index()**: Método para listar todos los recursos
**create()**: Método para mostrar formulario de creación
**store()**: Método para guardar nuevo recurso
**show()**: Método para mostrar un recurso específico
**edit()**: Método para mostrar formulario de edición
**update()**: Método para actualizar un recurso existente
**destroy()**: Método para eliminar un recurso

### 🎯 **Route Resource con Validación**

Puedes agregar validación a las rutas generadas automáticamente:

```php
Route::resource('servicios', ServicioController::class)
    ->where(['servicio' => '[0-9]+']);
```

**Explicación:** Esto aplica la validación `[0-9]+` a todos los parámetros `{servicio}` en las rutas generadas.

### 📝 **Excluir Rutas Específicas**

Si no necesitas todas las rutas CRUD, puedes excluir algunas:

```php
Route::resource('servicios', ServicioController::class)
    ->except(['destroy']); // Excluye la ruta de eliminar
```

**Explicación:** El método `except()` te permite especificar qué rutas NO quieres generar. Útil cuando no quieres que los usuarios eliminen recursos.

### 📝 **Incluir Solo Rutas Específicas**

También puedes generar solo las rutas que necesitas:

```php
Route::resource('servicios', ServicioController::class)
    ->only(['index', 'show']); // Solo listar y mostrar
```

**Explicación:** El método `only()` te permite especificar exactamente qué rutas quieres generar. Útil para APIs de solo lectura.

## 🔧 **Comandos Artisan para Rutas**

### 📋 **Ver Todas las Rutas**

Laravel proporciona comandos útiles para explorar y gestionar tus rutas:

```bash
php artisan route:list
```

**Explicación:** Este comando muestra todas las rutas registradas en tu aplicación, incluyendo método HTTP, URL, nombre y controlador.

### 📋 **Ver Rutas con Filtro**

Puedes filtrar las rutas para encontrar específicas:

```bash
# Ver solo rutas de servicios
php artisan route:list --name=servicios

# Ver rutas con método específico
php artisan route:list --method=GET
```

**Explicación:**

**--name=servicios**: Muestra solo rutas que contengan "servicios" en su nombre
**--method=GET**: Muestra solo rutas que usen el método HTTP GET

### 📋 **Ver Rutas en Formato JSON**

Para usar las rutas en scripts o herramientas externas:

```bash
php artisan route:list --json
```

**Explicación:** Devuelve las rutas en formato JSON, útil para procesamiento automático o integración con otras herramientas.

### 📋 **Ver Rutas con Detalles**

Para obtener información más detallada:

```bash
php artisan route:list --verbose
```

**Explicación:** Muestra información adicional como middleware aplicado, parámetros, etc.

### 📋 **Limpiar Cache de Rutas**

En producción, es importante cachear las rutas para mejor rendimiento:

```bash
php artisan route:clear
php artisan route:cache
```

**Explicación:**

**route:clear**: Limpia el cache de rutas (útil en desarrollo)
**route:cache**: Cachea las rutas para mejor rendimiento (útil en producción)

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura de Rutas**

Usar nombres consistentes y descriptivos para tus rutas:

```php
// ✅ Correcto
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// ❌ Incorrecto
Route::get('/servicios', [ServicioController::class, 'index'])->name('listar_servicios');
```

**Explicación:**

**Convención RESTful**: Usar nombres como `servicios.index`, `servicios.store`, etc. Sigue las convenciones REST.

**Consistencia**: Mantener el mismo patrón de nombres en toda la aplicación facilita el mantenimiento.

**Descriptivo**: Los nombres deben describir claramente qué hace la ruta.

### ✅ **Validación de Parámetros**

Siempre validar los parámetros de las rutas:

```php
// ✅ Siempre validar parámetros
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+');

// ✅ Usar middleware para validación compleja
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->middleware('auth');
```

**Explicación:**

**Validación básica**: Usar `->where()` para validar tipos de datos simples
**Middleware**: Usar middleware para validación más compleja como autenticación
**Seguridad**: La validación previene errores y ataques

### ✅ **Agrupación de Rutas**

Organizar rutas relacionadas en grupos:

```php
// Agrupar rutas relacionadas
Route::prefix('admin')->group(function () {
    Route::resource('servicios', AdminServicioController::class);
    Route::resource('usuarios', AdminUserController::class);
});

// Agrupar rutas con middleware
Route::middleware(['auth'])->group(function () {
    Route::resource('servicios', ServicioController::class);
});
```

**Explicación:**

**prefix()**: Agrega un prefijo a todas las rutas del grupo (ej: /admin/servicios)
**middleware()**: Aplica middleware a todas las rutas del grupo
**Organización**: Los grupos ayudan a mantener el código organizado y fácil de entender

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Sistema de Servicios Completo**

Un ejemplo completo de cómo organizar rutas en una aplicación real:

```php
// routes/web.php

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('servicios.show');

// Rutas de autenticación
Route::middleware(['auth'])->group(function () {
    Route::resource('servicios', ServicioController::class)
        ->except(['index', 'show']);
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('usuarios', AdminUserController::class);
    Route::resource('categorias', CategoriaController::class);
});
```

**Explicación detallada del sistema:**

**Rutas públicas**: Accesibles para todos los usuarios. Incluyen la página de inicio y visualización de servicios.

**Rutas de autenticación**: Requieren que el usuario esté logueado. Incluyen creación, edición y eliminación de servicios.

**Rutas de administración**: Requieren autenticación y permisos de administrador. Incluyen gestión de usuarios y categorías.

**Middleware**: `auth` verifica que el usuario esté autenticado, `admin` verifica permisos de administrador.

**Organización**: Las rutas están organizadas por nivel de acceso, facilitando el mantenimiento y la seguridad.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 