# 🗄️ Eloquent ORM y Modelos en Laravel 12

## 📋 **¿Qué es Eloquent ORM?**

Eloquent es el ORM (Object-Relational Mapping) de Laravel que permite trabajar con la base de datos usando objetos PHP en lugar de escribir SQL directamente. Proporciona una interfaz elegante y fluida para interactuar con la base de datos. Es como tener un "traductor" entre PHP y la base de datos que convierte las filas de la base de datos en objetos PHP y viceversa.

**¿Por qué usar Eloquent ORM?**
- **Simplicidad**: No necesitas escribir SQL complejo, solo usas métodos PHP
- **Seguridad**: Previene ataques SQL injection automáticamente
- **Productividad**: Acelera el desarrollo con métodos intuitivos
- **Mantenibilidad**: Código más limpio y fácil de entender
- **Flexibilidad**: Fácil de extender y personalizar según tus necesidades

### 🎯 **Características Principales**

**Active Record Pattern**: Cada modelo representa una tabla de la base de datos. Es como decir "cada fila en la tabla es un objeto en PHP". Por ejemplo, cada fila en la tabla `servicios` se convierte en un objeto `Servicio`.

**Relaciones**: Fácil definición de relaciones entre modelos (uno a muchos, muchos a muchos, etc.). Te permite conectar modelos de manera intuitiva. Por ejemplo, un servicio pertenece a una categoría, y una categoría tiene muchos servicios.

**Query Builder**: Construcción fluida de consultas usando métodos PHP. En lugar de escribir SQL, usas métodos como `where()`, `orderBy()`, `limit()`. Es como construir una consulta pieza por pieza.

**Mutators/Accessors**: Transformación automática de datos al guardar/leer. Los mutators transforman datos antes de guardarlos, los accessors transforman datos después de leerlos. Por ejemplo, convertir un precio a formato de moneda.

**Scopes**: Consultas reutilizables que encapsulan lógica común. Son como "funciones" que puedes reutilizar en diferentes consultas. Por ejemplo, un scope para obtener solo servicios activos.

**Mass Assignment**: Asignación masiva de datos de manera segura. Te permite llenar múltiples campos de una vez, pero solo los campos que has permitido explícitamente.

**Eager Loading**: Carga eficiente de relaciones para evitar el problema N+1. Carga todas las relaciones necesarias en consultas separadas pero eficientes, en lugar de hacer una consulta por cada relación.

## 🏗️ **Estructura de un Modelo Eloquent**

### 📝 **Modelo Básico**

Un modelo Eloquent básico hereda de la clase `Model` y Laravel hace muchas cosas automáticamente. Es como tener un "esqueleto" que Laravel llena con funcionalidad automática:

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

**Explicación detallada de las convenciones automáticas:**

**Tabla**: Laravel busca una tabla llamada `servicios` (plural del nombre del modelo). Si tu modelo se llama `Servicio`, Laravel automáticamente busca la tabla `servicios`.

**Clave primaria**: Usa `id` como clave primaria por defecto. Si tu tabla usa una clave primaria diferente, puedes especificarla con `$primaryKey`.

**Timestamps**: Automáticamente maneja `created_at` y `updated_at`. Laravel actualiza estos campos automáticamente cuando creas o modificas un registro.

**Namespace**: Los modelos van en `App\Models\`. Esta es la ubicación estándar para todos los modelos de tu aplicación.

**Nombre**: El nombre del modelo debe ser singular y PascalCase. Por ejemplo, `Servicio` para la tabla `servicios`.

### 🎯 **Modelo con Configuración Personalizada**

Un modelo más completo con todas las configuraciones importantes para una aplicación real. Este modelo incluye todas las propiedades que necesitas para una aplicación profesional:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory; // Permite usar factories para generar datos de prueba

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'servicios'; // Especifica el nombre de la tabla

    /**
     * La clave primaria del modelo.
     */
    protected $primaryKey = 'id'; // Define la clave primaria

    /**
     * Indica si el modelo debe usar timestamps.
     */
    public $timestamps = true; // Habilita created_at y updated_at automáticos

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_id',
        'activo'
    ]; // Campos que se pueden llenar con create() o fill()

    /**
     * Los atributos que deben ser ocultos para arrays.
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ]; // Campos que no se incluyen en toArray() o toJson()

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'precio' => 'decimal:2', // Convierte a decimal con 2 decimales
        'activo' => 'boolean',    // Convierte a boolean (true/false)
        'fecha_inicio' => 'datetime', // Convierte a objeto Carbon
        'fecha_fin' => 'datetime'
    ];
}
```

**Explicación detallada de cada propiedad:**

**$table**: Especifica el nombre exacto de la tabla (útil si no sigue convenciones). Si tu tabla se llama diferente al plural del modelo, puedes especificarlo aquí.

**$primaryKey**: Define la clave primaria (útil si no es 'id'). Si tu tabla usa una clave primaria diferente, como `servicio_id`, puedes especificarlo aquí.

**$timestamps**: Controla si se usan timestamps automáticos. Si es `false`, Laravel no manejará automáticamente `created_at` y `updated_at`.

**$fillable**: Lista de campos que se pueden asignar masivamente (seguridad). Solo estos campos se pueden llenar con `create()` o `fill()`. Previene asignación masiva no deseada.

**$hidden**: Campos que se ocultan al convertir a array/JSON. Útil para campos sensibles como contraseñas o tokens.

**$casts**: Convierte tipos de datos automáticamente. Por ejemplo, convierte strings a boolean, números a decimal, etc.

## 🚀 **Creación de Modelos**

### 📋 **Comando Artisan**

Laravel proporciona comandos Artisan para crear modelos con diferentes configuraciones:

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

**Explicación detallada de las opciones:**

**make:model Servicio**: Crea solo la clase del modelo. Útil cuando ya tienes la tabla creada o quieres crear el modelo manualmente.

**-m**: Crea una migración junto con el modelo. Útil cuando quieres crear la tabla al mismo tiempo que el modelo.

**-f**: Crea una factory para el modelo. Las factories te permiten generar datos de prueba realistas.

**-s**: Crea un seeder para el modelo. Los seeders insertan datos de ejemplo en la base de datos.

**-r**: Crea un controlador resource para el modelo. Incluye todos los métodos CRUD básicos.

### 🎯 **Ejemplo: Modelo Servicio Completo**

Un modelo completo que incluye relaciones, scopes, accessors y mutators. Este es un ejemplo real de cómo se vería un modelo en una aplicación profesional:

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

**Explicación detallada de cada sección:**

**use HasFactory**: Permite usar factories para generar datos de prueba. Es un trait que proporciona funcionalidad de factories.

**$fillable**: Lista de campos que se pueden llenar masivamente. Solo estos campos se pueden asignar con `create()` o `fill()`.

**$casts**: Convierte tipos de datos automáticamente. Por ejemplo, `precio` se convierte a decimal con 2 decimales.

**$attributes**: Define valores por defecto para los campos. En este caso, `activo` será `true` por defecto.

**Relaciones**: Métodos que definen las conexiones entre modelos. Cada relación tiene un tipo específico (BelongsTo, HasMany, etc.).

**Scopes**: Métodos que encapsulan consultas reutilizables. Te permiten crear consultas personalizadas que puedes reutilizar.

**Accessors**: Métodos que transforman datos después de leerlos de la base de datos. Se ejecutan automáticamente cuando accedes a un atributo.

**Mutators**: Métodos que transforman datos antes de guardarlos en la base de datos. Se ejecutan automáticamente cuando asignas un valor.

## 🔗 **Relaciones entre Modelos**

### 📋 **Relación Uno a Muchos (BelongsTo)**

Esta relación se usa cuando un modelo "pertenece a" otro modelo. Por ejemplo, un servicio pertenece a una categoría:

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

**Explicación detallada:**

**belongsTo()**: Define que este modelo pertenece a otro modelo. Se usa en el modelo "hijo" (el que tiene la clave foránea).

**hasMany()**: Define que este modelo tiene muchos de otro modelo. Se usa en el modelo "padre" (el que no tiene la clave foránea).

**Uso**: `$servicio->categoria` obtiene la categoría del servicio, `$categoria->servicios` obtiene todos los servicios de la categoría.

### 📋 **Relación Muchos a Muchos (BelongsToMany)**

Esta relación se usa cuando dos modelos pueden tener múltiples conexiones entre sí. Por ejemplo, servicios y etiquetas:

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

**Explicación detallada:**

**belongsToMany()**: Define una relación muchos a muchos. Laravel automáticamente busca una tabla pivot (intermedia) basada en los nombres de los modelos.

**Tabla pivot**: Laravel busca una tabla llamada `etiqueta_servicio` (nombres de modelos en orden alfabético, separados por guión bajo).

**Uso**: `$servicio->etiquetas` obtiene todas las etiquetas del servicio, `$etiqueta->servicios` obtiene todos los servicios con esa etiqueta.

### 📋 **Relación Uno a Uno (HasOne)**

Esta relación se usa cuando un modelo tiene exactamente uno de otro modelo. Por ejemplo, un usuario tiene un perfil:

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

**Explicación detallada:**

**hasOne()**: Define que este modelo tiene exactamente uno de otro modelo. Se usa en el modelo "padre".

**belongsTo()**: Define que este modelo pertenece a otro modelo. Se usa en el modelo "hijo".

**Uso**: `$user->perfil` obtiene el perfil del usuario, `$perfil->usuario` obtiene el usuario del perfil.

### 📋 **Relación Uno a Muchos (HasMany)**

Esta relación se usa cuando un modelo tiene muchos de otro modelo. Por ejemplo, un usuario tiene muchos servicios:

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

**Explicación detallada:**

**hasMany()**: Define que este modelo tiene muchos de otro modelo. Se usa en el modelo "padre".

**belongsTo()**: Define que este modelo pertenece a otro modelo. Se usa en el modelo "hijo".

**Uso**: `$user->servicios` obtiene todos los servicios del usuario, `$servicio->usuario` obtiene el usuario que creó el servicio.

## 🔍 **Consultas Eloquent**

### 📝 **Consultas Básicas**

Las consultas básicas te permiten obtener datos de manera simple y directa:

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

**Explicación detallada de cada método:**

**all()**: Obtiene todos los registros de la tabla. Útil para listas pequeñas, pero evítalo para tablas grandes.

**find()**: Busca un registro por su clave primaria. Devuelve `null` si no encuentra el registro.

**findOrFail()**: Busca un registro por su clave primaria. Lanza una excepción si no encuentra el registro. Útil para APIs.

**first()**: Obtiene el primer registro de la tabla. Útil cuando solo necesitas un registro.

**where()->first()**: Obtiene el primer registro que cumpla una condición. Útil para búsquedas específicas.

**count()**: Cuenta el número total de registros. Más eficiente que `all()->count()`.

**exists()**: Verifica si existe al menos un registro que cumpla la condición. Más eficiente que `count() > 0`.

### 🔍 **Consultas con Where**

Las consultas con `where` te permiten filtrar datos según condiciones específicas:

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

**Explicación detallada de cada tipo de consulta:**

**where básico**: Filtra por una condición simple. El primer parámetro es el nombre de la columna, el segundo es el valor.

**where múltiple**: Encadena múltiples condiciones con AND. Todas las condiciones deben cumplirse.

**orWhere**: Agrega una condición OR. Al menos una de las condiciones debe cumplirse.

**operadores**: Usa operadores de comparación como `>=`, `<=`, `!=`, etc.

**LIKE**: Busca texto que contenga un patrón. `%` significa "cualquier texto".

**whereIn**: Busca registros donde el valor esté en una lista específica.

### 📊 **Consultas con Relaciones**

Las consultas con relaciones te permiten cargar datos relacionados de manera eficiente:

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

**Explicación detallada de cada tipo de consulta:**

**with()**: Carga relaciones de manera eficiente (Eager Loading). Evita el problema N+1 al hacer consultas separadas.

**with múltiple**: Carga múltiples relaciones a la vez. Más eficiente que cargar relaciones por separado.

**with con condiciones**: Carga relaciones pero filtra los datos relacionados. Útil para cargar solo datos específicos.

**whereHas()**: Filtra registros basándose en las relaciones. Solo obtiene registros que tengan relaciones que cumplan la condición.

**whereHas con condiciones**: Filtra registros basándose en condiciones específicas de las relaciones.

### 📋 **Ordenamiento y Límites**

Estas consultas te permiten controlar el orden y la cantidad de resultados:

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

**Explicación detallada de cada método:**

**orderBy()**: Ordena los resultados por una columna específica. `asc` para ascendente, `desc` para descendente.

**orderBy múltiple**: Ordena por múltiples campos. El orden importa: primero ordena por categoría, luego por precio.

**limit()**: Limita el número de resultados. Útil para paginación o cuando solo necesitas algunos registros.

**offset()**: Salta un número específico de registros. Útil para paginación o cuando quieres empezar desde un punto específico.

### 📄 **Paginación**

La paginación te permite dividir grandes conjuntos de datos en páginas manejables:

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

**Explicación detallada de cada tipo de paginación:**

**paginate()**: Paginación completa con información de páginas totales. Incluye enlaces de navegación.

**paginate con relaciones**: Combina paginación con Eager Loading. Eficiente para grandes conjuntos de datos.

**simplePaginate()**: Paginación simple sin contar el total de registros. Más rápida para grandes datasets.

**paginate manual**: Control manual de la paginación. Útil cuando necesitas control específico.

## 🎯 **Scopes (Consultas Reutilizables)**

### 📝 **Scopes Locales**

Los scopes locales son métodos que encapsulan consultas reutilizables. Te permiten crear consultas personalizadas que puedes reutilizar:

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

**Explicación detallada de cada scope:**

**scopeActivos**: Filtra solo servicios activos. Útil para mostrar solo servicios disponibles.

**scopePorCategoria**: Filtra servicios por categoría específica. Útil para filtros de navegación.

**scopePorPrecio**: Filtra servicios por rango de precio. Útil para filtros de búsqueda.

**scopeBuscar**: Busca servicios por nombre o descripción. Útil para funcionalidad de búsqueda.

**Uso de scopes**: Los scopes se usan como métodos normales en las consultas. Puedes encadenarlos y combinarlos.

### 📝 **Scopes Globales**

Los scopes globales se aplican automáticamente a todas las consultas del modelo. Útiles para filtros que siempre deben aplicarse:

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

**Explicación detallada:**

**booted()**: Método que se ejecuta cuando el modelo se inicializa. Es donde registras scopes globales.

**addGlobalScope()**: Agrega un scope que se aplica automáticamente a todas las consultas.

**withoutGlobalScope()**: Excluye un scope global específico. Útil cuando necesitas acceder a todos los registros.

## 🎨 **Accessors y Mutators**

### 📝 **Accessors (Getters)**

Los accessors transforman datos después de leerlos de la base de datos. Te permiten formatear o calcular valores automáticamente:

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

**Explicación detallada de cada accessor:**

**getPrecioFormateadoAttribute()**: Convierte el precio a formato de moneda. Se ejecuta automáticamente cuando accedes a `$servicio->precio_formateado`.

**getEstadoAttribute()**: Convierte el valor booleano a texto legible. Útil para mostrar el estado de manera amigable.

**getDescripcionCortaAttribute()**: Trunca la descripción a 100 caracteres. Útil para listas donde no quieres mostrar toda la descripción.

**Convención de nombres**: Los accessors siguen el patrón `get{AttributeName}Attribute()`. El nombre del atributo se convierte a snake_case.

### 📝 **Mutators (Setters)**

Los mutators transforman datos antes de guardarlos en la base de datos. Te permiten limpiar, formatear o validar datos automáticamente:

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

**Explicación detallada de cada mutator:**

**setNombreAttribute()**: Capitaliza el nombre automáticamente. Convierte "consultoría it" en "Consultoría It".

**setPrecioAttribute()**: Convierte el precio a float. Asegura que siempre sea un número decimal.

**setDescripcionAttribute()**: Limpia HTML de la descripción. Previene inyección de HTML malicioso.

**Convención de nombres**: Los mutators siguen el patrón `set{AttributeName}Attribute()`. El nombre del atributo se convierte a snake_case.

## 🏭 **Factories y Datos de Prueba**

### 📝 **Factory Básico**

Las factories te permiten generar datos de prueba realistas automáticamente. Son útiles para testing y desarrollo:

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

**Explicación detallada de cada parte:**

**protected $model**: Especifica qué modelo usa esta factory. Laravel usa esto para crear instancias del modelo correcto.

**definition()**: Define los datos por defecto que se generarán. Cada campo tiene un valor generado automáticamente.

**$this->faker**: Proporciona datos falsos pero realistas. Incluye métodos para generar nombres, fechas, números, etc.

**Categoria::factory()**: Crea automáticamente una categoría relacionada. Útil para mantener la integridad referencial.

**Estados (inactivo, premium)**: Te permiten crear variaciones de los datos. Útil para testing diferentes escenarios.

### 📝 **Uso de Factories**

Las factories se usan principalmente en tests y seeders para generar datos de prueba:

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

**Explicación detallada de cada uso:**

**factory()->create()**: Crea un servicio con datos aleatorios y lo guarda en la base de datos.

**count(10)**: Crea 10 servicios de una vez. Útil para generar muchos datos de prueba.

**create(['nombre' => '...'])**: Crea un servicio con datos específicos, pero mantiene los demás campos aleatorios.

**inactivo()->create()**: Usa el estado "inactivo" para crear un servicio inactivo.

**premium()->create()**: Usa el estado "premium" para crear un servicio con precio alto.

**for(Categoria::factory())**: Crea automáticamente una categoría relacionada. Útil para mantener relaciones.

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Controlador de Servicios**

Un controlador completo que usa todas las funcionalidades de Eloquent:

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

**Explicación detallada de cada método:**

**index()**: Usa scopes y filtros para crear consultas dinámicas. Combina Eager Loading con paginación para rendimiento óptimo.

**create()**: Obtiene categorías para el formulario de creación. Usa `Categoria::all()` para obtener todas las categorías.

**store()**: Usa `create()` para crear un nuevo servicio. Los mutators se ejecutan automáticamente.

**show()**: Usa `load()` para cargar relaciones específicas. Más eficiente que `with()` cuando ya tienes el modelo.

**edit()**: Obtiene categorías para el formulario de edición. Similar al método create.

**update()**: Usa `update()` para modificar el servicio existente. Los mutators se ejecutan automáticamente.

**destroy()**: Usa `delete()` para eliminar el servicio. Laravel maneja automáticamente las relaciones.

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura**

**Modelos**: Singular y PascalCase (`Servicio`, `Categoria`) - Los modelos representan una entidad individual.

**Tablas**: Plural y snake_case (`servicios`, `categorias`) - Las tablas contienen múltiples entidades.

**Relaciones**: Nombres descriptivos (`categoria`, `usuario`, `reseñas`) - Los nombres deben describir claramente la relación.

### ✅ **Configuración**

**Siempre definir `$fillable` o `$guarded`**: Previene asignación masiva no deseada. `$fillable` es más seguro que `$guarded`.

**Usar `$casts` para tipos de datos**: Asegura que los datos tengan el tipo correcto. Útil para boolean, datetime, json, etc.

**Definir relaciones explícitamente**: Hace el código más legible y mantenible. Evita confusiones sobre las conexiones entre modelos.

**Crear scopes para consultas reutilizables**: Encapsula lógica común en métodos reutilizables. Mejora la legibilidad y mantenibilidad.

### ✅ **Rendimiento**

**Usar Eager Loading para evitar N+1 queries**: Carga todas las relaciones necesarias en consultas separadas. Evita hacer una consulta por cada relación.

**Crear índices en la base de datos**: Mejora la velocidad de las consultas. Especialmente importante para campos usados en WHERE y ORDER BY.

**Usar scopes para consultas comunes**: Encapsula consultas frecuentes en scopes. Mejora la reutilización y mantenibilidad.

**Limitar resultados con paginación**: Evita cargar grandes conjuntos de datos en memoria. Mejora el rendimiento de la aplicación.

### ✅ **Seguridad**

**Validar datos antes de guardar**: Asegúrate de que los datos sean válidos antes de insertarlos en la base de datos.

**Usar `$fillable` en lugar de `$guarded`**: Es más explícito y seguro. Solo permite los campos que específicamente has listado.

**Sanitizar datos con mutators**: Limpia y formatea datos automáticamente. Previene inyección de código malicioso.

**Proteger campos sensibles con `$hidden`**: Oculta campos sensibles cuando conviertes modelos a array o JSON.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 