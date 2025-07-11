# 🛣️ Rutas y Parámetros en Laravel 12

## 📋 **Conceptos Básicos de Rutas**

### 🎯 **¿Qué son las Rutas?**
Las rutas son la forma en que Laravel mapea las URLs a controladores y métodos específicos. Son el punto de entrada de todas las peticiones HTTP.

### 📁 **Archivos de Rutas**
```
routes/
├── web.php      # Rutas web (con sesión, CSRF)
├── api.php      # Rutas API (sin sesión)
├── console.php  # Comandos Artisan
└── channels.php # Canales de broadcasting
```

## 🚀 **Tipos de Rutas Básicas**

### 📝 **Rutas GET (Leer)**
```php
// Ruta simple
Route::get('/servicios', [ServicioController::class, 'index']);

// Ruta con nombre
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

// Ruta que retorna vista directamente
Route::get('/about', function () {
    return view('about');
});
```

### 📝 **Rutas POST (Crear)**
```php
// Crear nuevo servicio
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');

// Formulario de contacto
Route::post('/contacto', [ContactoController::class, 'enviar']);
```

### 📝 **Rutas PUT/PATCH (Actualizar)**
```php
// Actualizar servicio completo
Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');

// Actualizar parcialmente
Route::patch('/servicios/{id}', [ServicioController::class, 'update']);
```

### 📝 **Rutas DELETE (Eliminar)**
```php
// Eliminar servicio
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
```

## 🎯 **Parámetros en Rutas**

### 📌 **Parámetros Básicos**
```php
// Parámetro simple
Route::get('/servicios/{id}', [ServicioController::class, 'show']);

// Múltiples parámetros
Route::get('/servicios/{categoria}/{id}', [ServicioController::class, 'show']);

// Parámetros opcionales
Route::get('/servicios/{id?}', [ServicioController::class, 'show']);
```

### 🔍 **Acceso a Parámetros en Controladores**
```php
public function show($id)
{
    $servicio = Servicio::find($id);
    return view('servicios.show', compact('servicio'));
}

// Múltiples parámetros
public function show($categoria, $id)
{
    $servicio = Servicio::where('categoria', $categoria)
                       ->find($id);
    return view('servicios.show', compact('servicio'));
}
```

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