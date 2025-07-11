# 🗄️ Eloquent ORM y Modelos en Laravel 12

## 📋 **¿Qué es Eloquent ORM?**

Eloquent es el ORM (Object-Relational Mapping) de Laravel que permite trabajar con la base de datos usando objetos PHP en lugar de escribir SQL directamente. Proporciona una interfaz elegante y fluida para interactuar con la base de datos.

### 🎯 **Características Principales**
- **Active Record Pattern**: Cada modelo representa una tabla
- **Relaciones**: Fácil definición de relaciones entre modelos
- **Query Builder**: Construcción fluida de consultas
- **Mutators/Accessors**: Transformación automática de datos
- **Scopes**: Consultas reutilizables
- **Mass Assignment**: Asignación masiva de datos

## 🏗️ **Estructura de un Modelo Eloquent**

### 📝 **Modelo Básico**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Eloquent automáticamente:
    // - Usa la tabla 'servicios' (plural del nombre del modelo)
    // - Usa 'id' como clave primaria
    // - Usa timestamps automáticos (created_at, updated_at)
}
```

### 🎯 **Modelo con Configuración Personalizada**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'servicios';

    /**
     * La clave primaria del modelo.
     */
    protected $primaryKey = 'id';

    /**
     * Indica si el modelo debe usar timestamps.
     */
    public $timestamps = true;

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_id',
        'activo'
    ];

    /**
     * Los atributos que deben ser ocultos para arrays.
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime'
    ];
}
```

## 🚀 **Creación de Modelos**

### 📋 **Comando Artisan**
```bash
# Crear modelo básico
php artisan make:model Servicio

# Crear modelo con migración
php artisan make:model Servicio -m

# Crear modelo con migración, factory y seeder
php artisan make:model Servicio -mfs

# Crear modelo con todo (migración, factory, seeder, controller)
php artisan make:model Servicio -mfsr
```

### 🎯 **Ejemplo: Modelo Servicio Completo**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_id',
        'usuario_id',
        'imagen',
        'activo',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime'
    ];

    protected $attributes = [
        'activo' => true
    ];

    /**
     * Obtener la categoría del servicio.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Obtener el usuario que creó el servicio.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener las reseñas del servicio.
     */
    public function reseñas(): HasMany
    {
        return $this->hasMany(Reseña::class);
    }

    /**
     * Scope para servicios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para servicios por categoría.
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Scope para servicios por rango de precio.
     */
    public function scopePorPrecio($query, $min, $max)
    {
        return $query->whereBetween('precio', [$min, $max]);
    }

    /**
     * Accessor para el precio formateado.
     */
    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->precio, 2);
    }

    /**
     * Mutator para el nombre (capitalizar).
     */
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucwords(strtolower($value));
    }

    /**
     * Accessor para el estado del servicio.
     */
    public function getEstadoAttribute()
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }
}
```

## 🔗 **Relaciones entre Modelos**

### 📋 **Relación Uno a Muchos (BelongsTo)**
```php
// En el modelo Servicio
public function categoria(): BelongsTo
{
    return $this->belongsTo(Categoria::class);
}

// En el modelo Categoria
public function servicios(): HasMany
{
    return $this->hasMany(Servicio::class);
}
```

### 📋 **Relación Muchos a Muchos (BelongsToMany)**
```php
// En el modelo Servicio
public function etiquetas(): BelongsToMany
{
    return $this->belongsToMany(Etiqueta::class);
}

// En el modelo Etiqueta
public function servicios(): BelongsToMany
{
    return $this->belongsToMany(Servicio::class);
}
```

### 📋 **Relación Uno a Uno (HasOne)**
```php
// En el modelo User
public function perfil(): HasOne
{
    return $this->hasOne(Perfil::class);
}

// En el modelo Perfil
public function usuario(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### 📋 **Relación Uno a Muchos (HasMany)**
```php
// En el modelo User
public function servicios(): HasMany
{
    return $this->hasMany(Servicio::class);
}

// En el modelo Servicio
public function usuario(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## 🔍 **Consultas Eloquent**

### 📝 **Consultas Básicas**
```php
// Obtener todos los servicios
$servicios = Servicio::all();

// Obtener servicio por ID
$servicio = Servicio::find(1);

// Obtener servicio o lanzar excepción
$servicio = Servicio::findOrFail(1);

// Obtener primer servicio
$servicio = Servicio::first();

// Obtener primer servicio que cumpla condición
$servicio = Servicio::where('precio', '>', 100)->first();

// Contar servicios
$total = Servicio::count();

// Verificar si existe
$existe = Servicio::where('nombre', 'Consultoría')->exists();
```

### 🔍 **Consultas con Where**
```php
// Where básico
$servicios = Servicio::where('activo', true)->get();

// Where con múltiples condiciones
$servicios = Servicio::where('activo', true)
    ->where('precio', '>', 50)
    ->get();

// Where con OR
$servicios = Servicio::where('activo', true)
    ->orWhere('precio', '>', 100)
    ->get();

// Where con operadores
$servicios = Servicio::where('precio', '>=', 50)
    ->where('precio', '<=', 200)
    ->get();

// Where con LIKE
$servicios = Servicio::where('nombre', 'LIKE', '%consultoría%')->get();

// Where con IN
$servicios = Servicio::whereIn('categoria_id', [1, 2, 3])->get();
```

### 📊 **Consultas con Relaciones**
```php
// Cargar relación (Eager Loading)
$servicios = Servicio::with('categoria')->get();

// Cargar múltiples relaciones
$servicios = Servicio::with(['categoria', 'usuario', 'reseñas'])->get();

// Cargar relación con condiciones
$servicios = Servicio::with(['categoria' => function($query) {
    $query->where('activo', true);
}])->get();

// Consultar por relación
$servicios = Servicio::whereHas('categoria', function($query) {
    $query->where('nombre', 'Tecnología');
})->get();

// Consultar por relación con condiciones
$servicios = Servicio::whereHas('reseñas', function($query) {
    $query->where('puntuacion', '>=', 4);
})->get();
```

### 📋 **Ordenamiento y Límites**
```php
// Ordenar por precio ascendente
$servicios = Servicio::orderBy('precio', 'asc')->get();

// Ordenar por precio descendente
$servicios = Servicio::orderBy('precio', 'desc')->get();

// Ordenar por múltiples campos
$servicios = Servicio::orderBy('categoria_id')
    ->orderBy('precio', 'desc')
    ->get();

// Limitar resultados
$servicios = Servicio::limit(10)->get();

// Obtener con offset
$servicios = Servicio::offset(10)->limit(10)->get();
```

### 📄 **Paginación**
```php
// Paginación básica
$servicios = Servicio::paginate(10);

// Paginación con relaciones
$servicios = Servicio::with('categoria')->paginate(10);

// Paginación simple
$servicios = Servicio::simplePaginate(10);

// Paginación manual
$servicios = Servicio::paginate(10, ['*'], 'page', 2);
```

## 🎯 **Scopes (Consultas Reutilizables)**

### 📝 **Scopes Locales**
```php
// En el modelo Servicio
public function scopeActivos($query)
{
    return $query->where('activo', true);
}

public function scopePorCategoria($query, $categoriaId)
{
    return $query->where('categoria_id', $categoriaId);
}

public function scopePorPrecio($query, $min, $max)
{
    return $query->whereBetween('precio', [$min, $max]);
}

public function scopeBuscar($query, $termino)
{
    return $query->where('nombre', 'LIKE', "%{$termino}%")
                 ->orWhere('descripcion', 'LIKE', "%{$termino}%");
}

// Uso de scopes
$serviciosActivos = Servicio::activos()->get();
$serviciosTecnologia = Servicio::porCategoria(1)->get();
$serviciosCaros = Servicio::porPrecio(100, 500)->get();
$serviciosBusqueda = Servicio::buscar('consultoría')->get();
```

### 📝 **Scopes Globales**
```php
// En el modelo Servicio
protected static function booted()
{
    static::addGlobalScope('activos', function ($query) {
        $query->where('activo', true);
    });
}

// Excluir scope global
$todosLosServicios = Servicio::withoutGlobalScope('activos')->get();
```

## 🎨 **Accessors y Mutators**

### 📝 **Accessors (Getters)**
```php
// Accessor para precio formateado
public function getPrecioFormateadoAttribute()
{
    return '$' . number_format($this->precio, 2);
}

// Accessor para estado
public function getEstadoAttribute()
{
    return $this->activo ? 'Activo' : 'Inactivo';
}

// Accessor para descripción corta
public function getDescripcionCortaAttribute()
{
    return Str::limit($this->descripcion, 100);
}

// Uso de accessors
$servicio = Servicio::find(1);
echo $servicio->precio_formateado; // $150.00
echo $servicio->estado; // Activo
echo $servicio->descripcion_corta; // Descripción truncada
```

### 📝 **Mutators (Setters)**
```php
// Mutator para nombre (capitalizar)
public function setNombreAttribute($value)
{
    $this->attributes['nombre'] = ucwords(strtolower($value));
}

// Mutator para precio (convertir a decimal)
public function setPrecioAttribute($value)
{
    $this->attributes['precio'] = (float) $value;
}

// Mutator para descripción (limpiar HTML)
public function setDescripcionAttribute($value)
{
    $this->attributes['descripcion'] = strip_tags($value);
}

// Uso de mutators
$servicio = new Servicio();
$servicio->nombre = 'consultoría it'; // Se guarda como "Consultoría It"
$servicio->precio = '150.50'; // Se guarda como 150.50
$servicio->descripcion = '<p>Descripción</p>'; // Se guarda sin HTML
```

## 🏭 **Factories y Datos de Prueba**

### 📝 **Factory Básico**
```php
// database/factories/ServicioFactory.php
<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'precio' => $this->faker->randomFloat(2, 10, 1000),
            'categoria_id' => Categoria::factory(),
            'usuario_id' => User::factory(),
            'activo' => $this->faker->boolean(80), // 80% probabilidad de ser true
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'fecha_fin' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }

    /**
     * Indica que el servicio está inactivo.
     */
    public function inactivo()
    {
        return $this->state(function (array $attributes) {
            return [
                'activo' => false,
            ];
        });
    }

    /**
     * Indica que el servicio es premium.
     */
    public function premium()
    {
        return $this->state(function (array $attributes) {
            return [
                'precio' => $this->faker->randomFloat(2, 500, 2000),
            ];
        });
    }
}
```

### 📝 **Uso de Factories**
```php
// Crear un servicio
$servicio = Servicio::factory()->create();

// Crear múltiples servicios
$servicios = Servicio::factory()->count(10)->create();

// Crear servicio con datos específicos
$servicio = Servicio::factory()->create([
    'nombre' => 'Consultoría Personalizada',
    'precio' => 200.00
]);

// Crear servicio inactivo
$servicioInactivo = Servicio::factory()->inactivo()->create();

// Crear servicio premium
$servicioPremium = Servicio::factory()->premium()->create();

// Crear servicio con relaciones
$servicio = Servicio::factory()
    ->for(Categoria::factory())
    ->for(User::factory())
    ->create();
```

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Controlador de Servicios**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Mostrar lista de servicios.
     */
    public function index(Request $request)
    {
        $query = Servicio::with(['categoria', 'usuario']);

        // Filtros
        if ($request->has('categoria')) {
            $query->porCategoria($request->categoria);
        }

        if ($request->has('precio_min') && $request->has('precio_max')) {
            $query->porPrecio($request->precio_min, $request->precio_max);
        }

        if ($request->has('buscar')) {
            $query->buscar($request->buscar);
        }

        // Solo servicios activos
        $query->activos();

        // Ordenamiento
        $orden = $request->get('orden', 'nombre');
        $direccion = $request->get('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        // Paginación
        $servicios = $query->paginate(12);

        $categorias = Categoria::all();

        return view('servicios.index', compact('servicios', 'categorias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('servicios.create', compact('categorias'));
    }

    /**
     * Guardar nuevo servicio.
     */
    public function store(Request $request)
    {
        $servicio = Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_id' => $request->categoria_id,
            'usuario_id' => auth()->id(),
            'activo' => true
        ]);

        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Servicio creado exitosamente');
    }

    /**
     * Mostrar servicio específico.
     */
    public function show(Servicio $servicio)
    {
        $servicio->load(['categoria', 'usuario', 'reseñas.usuario']);
        
        return view('servicios.show', compact('servicio'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Servicio $servicio)
    {
        $categorias = Categoria::all();
        return view('servicios.edit', compact('servicio', 'categorias'));
    }

    /**
     * Actualizar servicio.
     */
    public function update(Request $request, Servicio $servicio)
    {
        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_id' => $request->categoria_id,
            'activo' => $request->has('activo')
        ]);

        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Servicio actualizado exitosamente');
    }

    /**
     * Eliminar servicio.
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado exitosamente');
    }
}
```

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura**
- **Modelos**: Singular y PascalCase (`Servicio`, `Categoria`)
- **Tablas**: Plural y snake_case (`servicios`, `categorias`)
- **Relaciones**: Nombres descriptivos (`categoria`, `usuario`, `reseñas`)

### ✅ **Configuración**
- **Siempre** definir `$fillable` o `$guarded`
- **Usar** `$casts` para tipos de datos
- **Definir** relaciones explícitamente
- **Crear** scopes para consultas reutilizables

### ✅ **Rendimiento**
- **Usar** Eager Loading para evitar N+1 queries
- **Crear** índices en la base de datos
- **Usar** scopes para consultas comunes
- **Limitar** resultados con paginación

### ✅ **Seguridad**
- **Validar** datos antes de guardar
- **Usar** `$fillable` en lugar de `$guarded`
- **Sanitizar** datos con mutators
- **Proteger** campos sensibles con `$hidden`

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 