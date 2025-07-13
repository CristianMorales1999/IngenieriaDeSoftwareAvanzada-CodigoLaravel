# 🛣️ Rutas y Parámetros en Laravel 12

## 📋 **Conceptos Básicos de Rutas**

### 🎯 **¿Qué son las Rutas?**
Las rutas son la forma en que Laravel mapea las URLs a controladores y métodos específicos. Son el punto de entrada de todas las peticiones HTTP. Cuando un usuario visita una URL, Laravel busca en las rutas para saber qué controlador y método debe ejecutar.

### 📁 **Archivos de Rutas**
```
routes/
├── web.php      # Rutas web (con sesión, CSRF, middleware web)
├── api.php      # Rutas API (sin sesión, para aplicaciones móviles/frontend)
├── console.php  # Comandos Artisan (tareas de consola)
└── channels.php # Canales de broadcasting (WebSockets, eventos en tiempo real)
```

**Explicación de cada archivo:**
- **web.php**: Contiene rutas para navegadores web con autenticación y protección CSRF
- **api.php**: Contiene rutas para APIs sin sesiones, usando tokens de autenticación
- **console.php**: Define comandos personalizados que puedes ejecutar con `php artisan`
- **channels.php**: Configura comunicación en tiempo real con WebSockets

## 🚀 **Tipos de Rutas Básicas**

### 📝 **Rutas GET (Leer)**
Las rutas GET se usan para obtener información. Son las más comunes y se ejecutan cuando un usuario visita una página:

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

**Explicación:**
- **GET** es el método HTTP más común para obtener datos
- **ServicioController::class** es la clase del controlador
- **'index'** es el método que se ejecutará en el controlador
- **->name()** asigna un nombre a la ruta para poder referenciarla después

### 📝 **Rutas POST (Crear)**
Las rutas POST se usan para enviar datos al servidor, generalmente desde formularios. Los datos se envían en el cuerpo de la petición HTTP:

```php
// Crear nuevo servicio - Cuando se envía un formulario a /servicios
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// Formulario de contacto - Procesa el formulario de contacto
Route::post('/contacto', [ContactoController::class, 'enviar']);
```

**Explicación:**
- **POST** se usa para enviar datos al servidor (formularios, APIs)
- Los datos se envían en el cuerpo de la petición HTTP (no en la URL)
- Generalmente se usa con formularios HTML (`<form method="POST">`)
- Laravel incluye protección CSRF automática para rutas POST

### 📝 **Rutas PUT/PATCH (Actualizar)**
Las rutas PUT y PATCH se usan para actualizar datos existentes. PUT actualiza todo el recurso, PATCH solo partes específicas:

```php
// Actualizar servicio completo - PUT actualiza todos los campos del servicio
Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');

// Actualizar parcialmente - PATCH actualiza solo algunos campos
Route::patch('/servicios/{id}', [ServicioController::class, 'update']);
```

**Explicación:**
- **PUT**: Actualiza completamente un recurso (todos los campos)
- **PATCH**: Actualiza parcialmente un recurso (solo algunos campos)
- **{id}**: Es un parámetro dinámico que se reemplaza con el ID real
- En HTML, se simulan con `<input type="hidden" name="_method" value="PUT">`

### 📝 **Rutas DELETE (Eliminar)**
Las rutas DELETE se usan para eliminar recursos. Son importantes para operaciones CRUD completas:

```php
// Eliminar servicio - Elimina el servicio con el ID especificado
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
```

**Explicación:**
- **DELETE**: Se usa para eliminar recursos de la base de datos
- **{id}**: Parámetro dinámico que identifica qué recurso eliminar
- En HTML, se simula con `<input type="hidden" name="_method" value="DELETE">`
- Es importante confirmar antes de eliminar (diálogos de confirmación)

## 🎯 **Parámetros en Rutas**

### 📌 **Parámetros Básicos**
Los parámetros en rutas permiten capturar valores dinámicos de la URL. Se definen entre llaves `{}`:

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

**Explicación:**
- **{id}**: Parámetro obligatorio (la ruta no funciona sin él)
- **{id?}**: Parámetro opcional (la ruta funciona con o sin él)
- Los parámetros se pasan como argumentos al método del controlador
- Los nombres de los parámetros deben coincidir con los argumentos del método

### 🔍 **Acceso a Parámetros en Controladores**
Los parámetros de la ruta se reciben como argumentos en el método del controlador. El orden y nombre deben coincidir:

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

**Explicación:**
- Los parámetros de la ruta se pasan automáticamente al método del controlador
- El orden de los argumentos debe coincidir con el orden en la ruta
- Los nombres de los parámetros deben coincidir exactamente
- Puedes usar estos parámetros para consultar la base de datos

## 🎯 **Tipos de Parámetros**

### 📝 **Parámetros de String (Por defecto)**
```php
Route::get('/servicios/{nombre}', [ServicioController::class, 'show']);
// /servicios/consultoria-it → $nombre = "consultoria-it"
```

### 🔢 **Parámetros Numéricos**
```php
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+');
// /servicios/123 → $id = 123
```

### 📧 **Parámetros de Email**
```php
Route::get('/usuarios/{email}', [UserController::class, 'show'])
    ->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}');
```

### 🔤 **Parámetros Alfabéticos**
```php
Route::get('/categorias/{nombre}', [CategoriaController::class, 'show'])
    ->where('nombre', '[a-zA-Z]+');
```

## 🔧 **Expresiones Regulares para Validación**

### 📋 **Patrones Comunes**

#### 🔢 **Números**
```php
// Solo números
->where('id', '[0-9]+')

// Números con decimales
->where('precio', '[0-9]+(\.[0-9]{1,2})?')

// Números de teléfono
->where('telefono', '[0-9]{9,10}')
```

#### 📧 **Emails**
```php
->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}')
```

#### 📅 **Fechas**
```php
// Formato YYYY-MM-DD
->where('fecha', '[0-9]{4}-[0-9]{2}-[0-9]{2}')

// Formato DD/MM/YYYY
->where('fecha', '[0-9]{2}/[0-9]{2}/[0-9]{4}')
```

#### 🔤 **Slugs (URLs amigables)**
```php
->where('slug', '[a-z0-9-]+')
```

### 🎯 **Ejemplos Prácticos**

#### 📊 **CRUD de Servicios con Validación**
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

## 🚀 **Route Resource (Rutas Automáticas)**

### 📋 **Generar todas las rutas CRUD**
```php
Route::resource('servicios', ServicioController::class);
```

**Esto genera automáticamente:**
```
GET    /servicios              → index()   → servicios.index
GET    /servicios/create       → create()  → servicios.create
POST   /servicios              → store()   → servicios.store
GET    /servicios/{id}         → show()    → servicios.show
GET    /servicios/{id}/edit    → edit()    → servicios.edit
PUT    /servicios/{id}         → update()  → servicios.update
DELETE /servicios/{id}         → destroy() → servicios.destroy
```

### 🎯 **Route Resource con Validación**
```php
Route::resource('servicios', ServicioController::class)
    ->where(['servicio' => '[0-9]+']);
```

### 📝 **Excluir Rutas Específicas**
```php
Route::resource('servicios', ServicioController::class)
    ->except(['destroy']); // Excluye la ruta de eliminar
```

### 📝 **Incluir Solo Rutas Específicas**
```php
Route::resource('servicios', ServicioController::class)
    ->only(['index', 'show']); // Solo listar y mostrar
```

## 🔧 **Comandos Artisan para Rutas**

### 📋 **Ver Todas las Rutas**
```bash
php artisan route:list
```

### 📋 **Ver Rutas con Filtro**
```bash
# Ver solo rutas de servicios
php artisan route:list --name=servicios

# Ver rutas con método específico
php artisan route:list --method=GET
```

### 📋 **Ver Rutas en Formato JSON**
```bash
php artisan route:list --json
```

### 📋 **Ver Rutas con Detalles**
```bash
php artisan route:list --verbose
```

### 📋 **Limpiar Cache de Rutas**
```bash
php artisan route:clear
php artisan route:cache
```

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura de Rutas**
```php
// ✅ Correcto
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// ❌ Incorrecto
Route::get('/servicios', [ServicioController::class, 'index'])->name('listar_servicios');
```

### ✅ **Validación de Parámetros**
```php
// ✅ Siempre validar parámetros
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->where('id', '[0-9]+');

// ✅ Usar middleware para validación compleja
Route::get('/servicios/{id}', [ServicioController::class, 'show'])
    ->middleware('auth');
```

### ✅ **Agrupación de Rutas**
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

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Sistema de Servicios Completo**
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

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 