# 🔍 Consultas Avanzadas en Laravel 12

## 📋 **¿Qué son las Consultas Avanzadas?**

Las consultas avanzadas en Laravel te permiten optimizar el rendimiento, crear consultas complejas y manejar grandes volúmenes de datos de manera eficiente. Incluyen técnicas como Eager Loading, consultas complejas, paginación y sistemas de búsqueda. Son esenciales para aplicaciones que manejan muchos datos o requieren alto rendimiento.

**¿Por qué necesitas consultas avanzadas?**
- **Rendimiento**: Aplicaciones lentas frustran a los usuarios y aumentan costos de servidor
- **Escalabilidad**: Tu aplicación debe funcionar bien con más datos y usuarios
- **Experiencia de usuario**: Páginas que cargan rápido mantienen a los usuarios
- **Recursos**: Optimizar consultas reduce el uso de CPU, memoria y ancho de banda
- **Competitividad**: Las aplicaciones rápidas tienen ventaja en el mercado

### 🎯 **Características Principales**
- **Eager Loading**: Evitar el problema N+1 (cargar relaciones de manera eficiente)
- **Consultas Complejas**: Subconsultas, joins, agregaciones (funciones matemáticas)
- **Paginación**: Manejo eficiente de grandes datasets (dividir resultados en páginas)
- **Búsqueda**: Filtros dinámicos y búsqueda avanzada (búsqueda por texto, filtros)
- **Optimización**: Índices y consultas optimizadas (mejorar velocidad de consultas)
- **Cache**: Almacenar resultados frecuentes para mejorar rendimiento

## ⚡ **Eager Loading (Carga Ansiosa)**

### 📋 **¿Qué es el Problema N+1?**

El problema N+1 es uno de los errores más comunes en aplicaciones web. Ocurre cuando cargas una colección de modelos y luego accedes a sus relaciones, resultando en múltiples consultas innecesarias a la base de datos. Esto puede ralentizar significativamente tu aplicación.

**¿Por qué se llama N+1?**
- **1**: Una consulta para obtener la lista principal (ej: todos los servicios)
- **N**: Una consulta adicional por cada elemento para obtener sus relaciones
- **Total**: 1 + N consultas (si tienes 100 servicios, son 101 consultas!)

#### ❌ **Ejemplo del Problema N+1**

```php
// 1 consulta para obtener servicios
$servicios = Servicio::all();

// N consultas adicionales (una por cada servicio)
foreach ($servicios as $servicio) {
    echo $servicio->categoria->nombre; // Consulta adicional para cada servicio
    echo $servicio->usuario->name;     // Consulta adicional para cada servicio
}
// Total: 1 + N consultas (donde N = número de servicios)
// Si tienes 100 servicios: 1 + 100 = 101 consultas!
```

**¿Por qué es un problema?**
- **Rendimiento**: Muchas consultas innecesarias ralentizan la aplicación
- **Recursos**: Consume más memoria y CPU del servidor
- **Escalabilidad**: No escala bien con más datos
- **Experiencia de usuario**: Páginas que tardan mucho en cargar

**Ejemplo real del impacto:**
- 100 servicios = 101 consultas
- 1000 servicios = 1001 consultas
- 10000 servicios = 10001 consultas (¡imposible de manejar!)

#### ✅ **Solución con Eager Loading**

Eager Loading carga todas las relaciones necesarias en consultas separadas, pero eficientes:

```php
// 1 consulta principal + 2 consultas para relaciones
$servicios = Servicio::with(['categoria', 'usuario'])->get();

foreach ($servicios as $servicio) {
    echo $servicio->categoria->nombre; // Sin consulta adicional
    echo $servicio->usuario->name;     // Sin consulta adicional
}
// Total: 3 consultas (independiente del número de servicios)
// Si tienes 100 servicios: solo 3 consultas en lugar de 101!
```

**¿Cómo funciona Eager Loading?**

1. **Consulta principal**: Obtiene todos los servicios
2. **Consulta categorías**: Obtiene todas las categorías de los servicios
3. **Consulta usuarios**: Obtiene todos los usuarios de los servicios
4. **Laravel combina**: Los datos automáticamente en memoria

**Ventajas:**
- **Rendimiento**: Mucho más rápido que N+1
- **Escalabilidad**: Funciona bien con grandes volúmenes de datos
- **Simplicidad**: Laravel maneja la combinación automáticamente
- **Flexibilidad**: Puedes cargar solo las relaciones que necesitas

### 🎯 **Tipos de Eager Loading**

#### 📝 **Eager Loading Básico**

```php
// Cargar una relación
$servicios = Servicio::with('categoria')->get();

// Cargar múltiples relaciones
$servicios = Servicio::with(['categoria', 'usuario', 'reseñas'])->get();

// Cargar relación anidada
$servicios = Servicio::with('reseñas.usuario')->get();

// Cargar múltiples relaciones anidadas
$servicios = Servicio::with([
    'categoria',
    'usuario',
    'reseñas.usuario',
    'etiquetas'
])->get();
```

**Explicación de cada tipo:**

**with('categoria')**: Carga la relación categoría para todos los servicios. Útil cuando necesitas mostrar el nombre de la categoría de cada servicio.

**with(['categoria', 'usuario', 'reseñas'])**: Carga múltiples relaciones a la vez. Más eficiente que cargar relaciones por separado.

**with('reseñas.usuario')**: Carga relaciones anidadas. Carga las reseñas y también los usuarios de esas reseñas.

**Relaciones anidadas múltiples**: Carga varias relaciones y sus sub-relaciones. Útil para vistas complejas que necesitan muchos datos relacionados.

#### 🎯 **Eager Loading con Condiciones**

```php
// Cargar relación con condiciones
$servicios = Servicio::with(['categoria' => function($query) {
    $query->where('activo', true);
}])->get();

// Cargar relación con múltiples condiciones
$servicios = Servicio::with(['reseñas' => function($query) {
    $query->where('puntuacion', '>=', 4)
          ->where('verificado', true)
          ->orderBy('created_at', 'desc');
}])->get();

// Cargar solo ciertos campos de la relación
$servicios = Servicio::with(['categoria:id,nombre,slug'])->get();
```

**Explicación de cada técnica:**

**with condiciones**: Carga solo las relaciones que cumplen ciertas condiciones. Por ejemplo, solo categorías activas.

**múltiples condiciones**: Aplica varios filtros a la relación. Útil para obtener solo reseñas positivas y verificadas.

**campos específicos**: Carga solo los campos que necesitas de la relación. Mejora el rendimiento al transferir menos datos.

#### 📊 **Eager Loading con Count**

```php
// Contar relaciones sin cargarlas
$servicios = Servicio::withCount('reseñas')->get();

foreach ($servicios as $servicio) {
    echo $servicio->reseñas_count; // Número de reseñas
}

// Contar múltiples relaciones
$servicios = Servicio::withCount([
    'reseñas',
    'reseñas as reseñas_verificadas' => function($query) {
        $query->where('verificado', true);
    }
])->get();

// Contar con condiciones
$servicios = Servicio::withCount([
    'reseñas',
    'reseñas as reseñas_positivas' => function($query) {
        $query->where('puntuacion', '>=', 4);
    }
])->get();
```

**Explicación de withCount:**

**withCount('reseñas')**: Cuenta cuántas reseñas tiene cada servicio sin cargar los datos completos. Agrega un campo `reseñas_count` a cada servicio.

**múltiples counts**: Cuenta diferentes tipos de relaciones. Por ejemplo, total de reseñas y reseñas verificadas.

**count con condiciones**: Cuenta solo las relaciones que cumplen ciertas condiciones. Útil para estadísticas específicas.

#### 🔍 **Eager Loading con Exists**

```php
// Verificar si existe relación sin cargarla
$servicios = Servicio::withExists('reseñas')->get();

foreach ($servicios as $servicio) {
    if ($servicio->reseñas_exists) {
        echo "Este servicio tiene reseñas";
    }
}

// Verificar con condiciones
$servicios = Servicio::withExists([
    'reseñas',
    'reseñas as tiene_reseñas_verificadas' => function($query) {
        $query->where('verificado', true);
    }
])->get();
```

**Explicación de withExists:**

**withExists('reseñas')**: Verifica si cada servicio tiene reseñas sin cargar los datos. Agrega un campo `reseñas_exists` (true/false).

**exists con condiciones**: Verifica si existen relaciones que cumplen ciertas condiciones. Útil para mostrar badges o indicadores.

### 🎯 **Eager Loading Avanzado**

#### 📝 **Cargar Relaciones Condicionalmente**

```php
// Cargar relación solo si se necesita
$servicios = Servicio::when($request->has('with_categoria'), function($query) {
    $query->with('categoria');
})->get();

// Cargar múltiples relaciones condicionalmente
$servicios = Servicio::when($request->has('with_relations'), function($query) {
    $query->with(['categoria', 'usuario', 'reseñas']);
})->get();
```

**Explicación:**

**when()**: Carga relaciones solo cuando se cumple una condición. Útil para optimizar consultas según los parámetros de la petición.

**condiciones dinámicas**: Permite cargar relaciones según los filtros o parámetros del usuario. Mejora la flexibilidad de la API.

#### 📊 **Eager Loading con Agregaciones**

```php
// Cargar con agregaciones
$servicios = Servicio::with(['reseñas' => function($query) {
    $query->select('servicio_id', 'puntuacion')
          ->selectRaw('AVG(puntuacion) as promedio_puntuacion')
          ->groupBy('servicio_id');
}])->get();
```

**Explicación:**

**agregaciones en relaciones**: Calcula estadísticas (promedios, sumas, etc.) en las relaciones cargadas. Útil para mostrar métricas sin consultas adicionales.

## 🔍 **Consultas Complejas**

### 📝 **Subconsultas**

Las subconsultas te permiten usar el resultado de una consulta dentro de otra consulta. Son útiles para comparaciones y cálculos complejos:

```php
// Subconsulta en where
$servicios = Servicio::where('precio', '>', function($query) {
    $query->selectRaw('AVG(precio)')
          ->from('servicios');
})->get();

// Subconsulta en select
$servicios = Servicio::select('*')
    ->addSelect([
        'precio_promedio' => Servicio::selectRaw('AVG(precio)')
    ])
    ->get();

// Subconsulta con relación
$servicios = Servicio::whereHas('reseñas', function($query) {
    $query->where('puntuacion', '>', function($subQuery) {
        $subQuery->selectRaw('AVG(puntuacion)')
                 ->from('reseñas');
    });
})->get();
```

**Explicación de cada tipo:**

**subconsulta en where**: Compara un campo con el resultado de otra consulta. Por ejemplo, servicios con precio mayor al promedio.

**subconsulta en select**: Agrega un campo calculado a cada registro. Útil para mostrar comparaciones o estadísticas.

**subconsulta con relación**: Filtra registros basándose en condiciones complejas de sus relaciones. Útil para búsquedas avanzadas.

### 🔗 **Joins Complejos**

Los joins te permiten combinar datos de múltiples tablas en una sola consulta:

```php
// Join básico
$servicios = Servicio::join('categorias', 'servicios.categoria_id', '=', 'categorias.id')
    ->select('servicios.*', 'categorias.nombre as categoria_nombre')
    ->get();

// Join con múltiples condiciones
$servicios = Servicio::join('categorias', function($join) {
    $join->on('servicios.categoria_id', '=', 'categorias.id')
         ->where('categorias.activo', true);
})
->join('users', 'servicios.usuario_id', '=', 'users.id')
->select('servicios.*', 'categorias.nombre as categoria_nombre', 'users.name as usuario_nombre')
->get();

// Left Join
$servicios = Servicio::leftJoin('reseñas', 'servicios.id', '=', 'reseñas.servicio_id')
    ->select('servicios.*', DB::raw('COUNT(reseñas.id) as total_reseñas'))
    ->groupBy('servicios.id')
    ->get();
```

**Explicación de cada tipo:**

**join básico**: Combina servicios con sus categorías. Útil cuando necesitas datos de ambas tablas.

**join con condiciones**: Aplica filtros adicionales en el join. Por ejemplo, solo categorías activas.

**left join**: Incluye todos los servicios, incluso los que no tienen reseñas. Útil para contar relaciones opcionales.

### 📊 **Agregaciones Avanzadas**

Las agregaciones te permiten calcular estadísticas y métricas de tus datos:

```php
// Agregaciones básicas
$estadisticas = Servicio::selectRaw('
    COUNT(*) as total_servicios,
    AVG(precio) as precio_promedio,
    MIN(precio) as precio_minimo,
    MAX(precio) as precio_maximo,
    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as servicios_activos
')->first();

// Agregaciones por categoría
$estadisticas_por_categoria = Servicio::join('categorias', 'servicios.categoria_id', '=', 'categorias.id')
    ->select('categorias.nombre', 'categorias.slug')
    ->selectRaw('COUNT(servicios.id) as total_servicios')
    ->selectRaw('AVG(servicios.precio) as precio_promedio')
    ->selectRaw('SUM(CASE WHEN servicios.activo = 1 THEN 1 ELSE 0 END) as servicios_activos')
    ->groupBy('categorias.id', 'categorias.nombre', 'categorias.slug')
    ->get();

// Agregaciones con condiciones
$estadisticas_activos = Servicio::where('activo', true)
    ->selectRaw('
        COUNT(*) as total,
        AVG(precio) as precio_promedio,
        AVG(rating_promedio) as rating_promedio
    ')
    ->first();
```

**Explicación de cada agregación:**

**agregaciones básicas**: Calcula estadísticas generales de todos los servicios. Útil para dashboards y reportes.

**agregaciones por categoría**: Agrupa estadísticas por categoría. Útil para análisis comparativos.

**agregaciones con condiciones**: Calcula estadísticas solo para registros que cumplen ciertas condiciones. Útil para análisis específicos.

### 🎯 **Consultas con Raw SQL**

A veces necesitas usar SQL puro para consultas muy complejas o específicas:

```php
// Consulta raw completa
$servicios = DB::select('
    SELECT s.*, c.nombre as categoria_nombre, u.name as usuario_nombre
    FROM servicios s
    JOIN categorias c ON s.categoria_id = c.id
    JOIN users u ON s.usuario_id = u.id
    WHERE s.activo = 1
    ORDER BY s.created_at DESC
');

// Raw en where
$servicios = Servicio::whereRaw('precio > (SELECT AVG(precio) FROM servicios)')->get();

// Raw en select
$servicios = Servicio::select('*')
    ->selectRaw('(SELECT COUNT(*) FROM reseñas WHERE reseñas.servicio_id = servicios.id) as total_reseñas')
    ->get();
```

**Explicación de cada tipo:**

**consulta raw completa**: Usa SQL puro cuando necesitas máxima flexibilidad o rendimiento. Útil para consultas muy complejas.

**raw en where**: Usa SQL puro solo en la cláusula WHERE. Útil para condiciones complejas que no se pueden expresar con Eloquent.

**raw en select**: Usa SQL puro para calcular campos adicionales. Útil para agregaciones complejas.

## 📄 **Paginación Avanzada**

### 📝 **Paginación Básica**

La paginación divide grandes conjuntos de datos en páginas manejables:

```php
// Paginación simple
$servicios = Servicio::paginate(12);

// Paginación con relaciones
$servicios = Servicio::with(['categoria', 'usuario'])
    ->where('activo', true)
    ->orderBy('created_at', 'desc')
    ->paginate(12);

// Paginación simple (sin count)
$servicios = Servicio::simplePaginate(12);
```

**Explicación de cada tipo:**

**paginate(12)**: Divide los resultados en páginas de 12 elementos. Incluye enlaces de navegación y información de páginas totales.

**paginate con relaciones**: Combina paginación con Eager Loading. Eficiente para grandes conjuntos de datos con relaciones.

**simplePaginate(12)**: Paginación sin contar el total de registros. Más rápida para grandes datasets.

### 🎯 **Paginación con Filtros**

```php
// Paginación con parámetros de búsqueda
$servicios = Servicio::query()
    ->when($request->categoria_id, function($query, $categoriaId) {
        $query->where('categoria_id', $categoriaId);
    })
    ->when($request->precio_min, function($query, $precioMin) {
        $query->where('precio', '>=', $precioMin);
    })
    ->when($request->precio_max, function($query, $precioMax) {
        $query->where('precio', '<=', $precioMax);
    })
    ->when($request->buscar, function($query, $buscar) {
        $query->where('nombre', 'LIKE', "%{$buscar}%")
              ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
    })
    ->where('activo', true)
    ->with(['categoria', 'usuario'])
    ->orderBy('destacado', 'desc')
    ->orderBy('created_at', 'desc')
    ->paginate(12)
    ->withQueryString(); // Mantener parámetros en URLs
```

**Explicación de cada parte:**

**when()**: Aplica filtros solo si el parámetro existe. Evita errores cuando los parámetros están vacíos.

**withQueryString()**: Mantiene los parámetros de búsqueda en las URLs de paginación. Útil para filtros persistentes.

**ordenamiento múltiple**: Ordena primero por destacado, luego por fecha. Útil para mostrar contenido prioritario.

### 📊 **Paginación Personalizada**

```php
// Paginación con datos adicionales
$servicios = Servicio::with(['categoria', 'usuario'])
    ->where('activo', true)
    ->paginate(12);

// Agregar datos adicionales a la paginación
$servicios->getCollection()->transform(function ($servicio) {
    $servicio->precio_formateado = '$' . number_format($servicio->precio, 2);
    $servicio->fecha_formateada = $servicio->created_at->format('d/m/Y');
    return $servicio;
});

// Paginación con estadísticas
$estadisticas = [
    'total_servicios' => Servicio::count(),
    'servicios_activos' => Servicio::where('activo', true)->count(),
    'precio_promedio' => Servicio::where('activo', true)->avg('precio')
];

return view('servicios.index', compact('servicios', 'estadisticas'));
```

**Explicación de cada técnica:**

**transform()**: Modifica cada elemento de la colección antes de mostrarlo. Útil para formatear datos.

**estadísticas adicionales**: Proporciona contexto adicional a la vista. Útil para mostrar métricas generales.

### 🎯 **Paginación con Cursor**

```php
// Paginación con cursor (para grandes datasets)
$servicios = Servicio::where('activo', true)
    ->orderBy('id')
    ->cursorPaginate(12);
```

**Explicación:**

**cursorPaginate()**: Usa un cursor en lugar de offset para paginación. Más eficiente para grandes datasets porque no necesita contar registros.

## 🔍 **Sistemas de Búsqueda**

### 📝 **Búsqueda Básica**

```php
// Búsqueda simple
$servicios = Servicio::where('nombre', 'LIKE', "%{$buscar}%")
    ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
    ->get();

// Búsqueda con múltiples campos
$servicios = Servicio::where(function($query) use ($buscar) {
    $query->where('nombre', 'LIKE', "%{$buscar}%")
          ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
          ->orWhere('descripcion_corta', 'LIKE', "%{$buscar}%");
})->get();
```

**Explicación de cada técnica:**

**búsqueda simple**: Busca en un campo específico. Útil para búsquedas básicas.

**búsqueda múltiple**: Busca en varios campos a la vez. Útil para búsquedas más completas.

### 🎯 **Búsqueda Avanzada**

```php
// Búsqueda con filtros múltiples
public function buscar(Request $request)
{
    $query = Servicio::query();

    // Búsqueda por texto
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $query->where(function($q) use ($buscar) {
            $q->where('nombre', 'LIKE', "%{$buscar}%")
              ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
              ->orWhere('descripcion_corta', 'LIKE', "%{$buscar}%");
        });
    }

    // Filtro por categoría
    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    // Filtro por precio
    if ($request->filled('precio_min')) {
        $query->where('precio', '>=', $request->precio_min);
    }
    if ($request->filled('precio_max')) {
        $query->where('precio', '<=', $request->precio_max);
    }

    // Filtro por rating
    if ($request->filled('rating_min')) {
        $query->where('rating_promedio', '>=', $request->rating_min);
    }

    // Filtro por estado
    if ($request->filled('activo')) {
        $query->where('activo', $request->activo);
    }

    // Filtro por destacado
    if ($request->filled('destacado')) {
        $query->where('destacado', $request->destacado);
    }

    // Ordenamiento
    $orden = $request->get('orden', 'created_at');
    $direccion = $request->get('direccion', 'desc');
    $query->orderBy($orden, $direccion);

    // Cargar relaciones
    $query->with(['categoria', 'usuario']);

    // Solo servicios activos
    $query->where('activo', true);

    return $query->paginate(12)->withQueryString();
}
```

**Explicación de cada filtro:**

**búsqueda por texto**: Busca en múltiples campos de texto. Útil para búsquedas generales.

**filtro por categoría**: Filtra por categoría específica. Útil para navegación por categorías.

**filtro por precio**: Permite rangos de precio. Útil para filtros de precio.

**filtro por rating**: Filtra por calificación mínima. Útil para mostrar solo servicios bien calificados.

**filtro por estado**: Filtra por estado activo/inactivo. Útil para administración.

**filtro por destacado**: Muestra solo servicios destacados. Útil para promociones.

### 📊 **Búsqueda con Full-Text Search**

```php
// Búsqueda full-text (MySQL)
$servicios = Servicio::whereRaw('MATCH(nombre, descripcion) AGAINST(? IN BOOLEAN MODE)', [$buscar])
    ->get();

// Búsqueda con relevancia
$servicios = Servicio::select('*')
    ->selectRaw('MATCH(nombre, descripcion) AGAINST(? IN BOOLEAN MODE) as relevancia', [$buscar])
    ->whereRaw('MATCH(nombre, descripcion) AGAINST(? IN BOOLEAN MODE)', [$buscar])
    ->orderBy('relevancia', 'desc')
    ->get();
```

**Explicación:**

**full-text search**: Usa índices full-text de MySQL para búsquedas más rápidas y precisas. Útil para grandes volúmenes de texto.

**búsqueda con relevancia**: Ordena resultados por relevancia. Útil para mostrar los resultados más relevantes primero.

### 🎯 **Búsqueda con Scout (Laravel Scout)**

```php
// Búsqueda con Scout (requiere instalación)
$servicios = Servicio::search($buscar)
    ->where('activo', true)
    ->paginate(12);
```

**Explicación:**

**Laravel Scout**: Proporciona búsqueda full-text para Eloquent usando drivers como Algolia, Elasticsearch, etc. Útil para búsquedas avanzadas y escalables.

## 🎯 **Optimización de Consultas**

### 📝 **Índices Recomendados**

```php
// En las migraciones
Schema::create('servicios', function (Blueprint $table) {
    // ... columnas ...
    
    // Índices para búsqueda
    $table->index(['activo', 'destacado']);
    $table->index(['categoria_id', 'activo']);
    $table->index(['precio', 'activo']);
    $table->index(['rating_promedio', 'activo']);
    
    // Índice full-text para búsqueda
    $table->fullText(['nombre', 'descripcion']);
});
```

**Explicación de cada índice:**

**índices compuestos**: Mejoran consultas que filtran por múltiples campos. Por ejemplo, servicios activos y destacados.

**índices por categoría**: Optimizan consultas que filtran por categoría y estado.

**índices por precio**: Optimizan consultas de rango de precio.

**índices por rating**: Optimizan consultas que filtran por calificación.

**índice full-text**: Optimiza búsquedas de texto en campos específicos.

### 🎯 **Consultas Optimizadas**

```php
// Seleccionar solo campos necesarios
$servicios = Servicio::select('id', 'nombre', 'precio', 'categoria_id')
    ->with(['categoria:id,nombre'])
    ->get();

// Usar chunk para grandes datasets
Servicio::chunk(1000, function ($servicios) {
    foreach ($servicios as $servicio) {
        // Procesar cada servicio
    }
});

// Usar cursor para grandes datasets
foreach (Servicio::cursor() as $servicio) {
    // Procesar cada servicio
}
```

**Explicación de cada técnica:**

**select específico**: Carga solo los campos que necesitas. Reduce el uso de memoria y ancho de banda.

**chunk()**: Procesa grandes datasets en lotes. Útil para importaciones o procesamiento masivo.

**cursor()**: Procesa registros uno por uno sin cargar todo en memoria. Útil para datasets muy grandes.

### 📊 **Cache de Consultas**

```php
// Cache de consultas
$servicios = Cache::remember('servicios_activos', 3600, function () {
    return Servicio::where('activo', true)
        ->with(['categoria', 'usuario'])
        ->get();
});

// Cache con tags
$servicios = Cache::tags(['servicios', 'activos'])->remember('lista', 3600, function () {
    return Servicio::where('activo', true)->get();
});
```

**Explicación:**

**Cache::remember()**: Almacena el resultado de la consulta por un tiempo específico. Útil para datos que no cambian frecuentemente.

**Cache con tags**: Permite invalidar cache por grupos. Útil para cachear diferentes tipos de datos por separado.

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Controlador de Búsqueda Avanzada**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servicio::query();

        // Filtros
        $this->aplicarFiltros($query, $request);

        // Ordenamiento
        $this->aplicarOrdenamiento($query, $request);

        // Cargar relaciones
        $query->with(['categoria', 'usuario']);

        // Solo servicios activos
        $query->where('activo', true);

        // Paginación
        $servicios = $query->paginate(12)->withQueryString();

        // Estadísticas
        $estadisticas = $this->obtenerEstadisticas();

        // Categorías para filtros
        $categorias = Cache::remember('categorias_activas', 3600, function () {
            return Categoria::where('activo', true)->get();
        });

        return view('servicios.index', compact('servicios', 'estadisticas', 'categorias'));
    }

    private function aplicarFiltros($query, Request $request)
    {
        // Búsqueda por texto
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion_corta', 'LIKE', "%{$buscar}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por precio
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Filtro por rating
        if ($request->filled('rating_min')) {
            $query->where('rating_promedio', '>=', $request->rating_min);
        }

        // Filtro por destacado
        if ($request->filled('destacado')) {
            $query->where('destacado', $request->destacado);
        }
    }

    private function aplicarOrdenamiento($query, Request $request)
    {
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('direccion', 'desc');

        // Validar campos de ordenamiento
        $camposPermitidos = ['nombre', 'precio', 'rating_promedio', 'created_at', 'destacado'];
        
        if (in_array($orden, $camposPermitidos)) {
            $query->orderBy($orden, $direccion);
        } else {
            $query->orderBy('destacado', 'desc')
                  ->orderBy('created_at', 'desc');
        }
    }

    private function obtenerEstadisticas()
    {
        return Cache::remember('estadisticas_servicios', 3600, function () {
            return [
                'total_servicios' => Servicio::count(),
                'servicios_activos' => Servicio::where('activo', true)->count(),
                'precio_promedio' => Servicio::where('activo', true)->avg('precio'),
                'rating_promedio' => Servicio::where('activo', true)->avg('rating_promedio'),
                'servicios_destacados' => Servicio::where('activo', true)->where('destacado', true)->count()
            ];
        });
    }
}
```

**Explicación del controlador:**

**métodos separados**: Divide la lógica en métodos pequeños y reutilizables. Mejora la legibilidad y mantenibilidad.

**validación de parámetros**: Valida los parámetros de entrada para evitar errores. Útil para prevenir inyección SQL.

**cache de estadísticas**: Almacena estadísticas en cache para mejorar rendimiento. Útil para datos que no cambian frecuentemente.

## 🎯 **Buenas Prácticas**

### ✅ **Eager Loading**
- **Siempre** usar Eager Loading para relaciones: Evita el problema N+1 y mejora significativamente el rendimiento.
- **Cargar** solo campos necesarios: Reduce el uso de memoria y ancho de banda.
- **Usar** withCount para contar relaciones: Más eficiente que cargar relaciones solo para contarlas.
- **Evitar** N+1 queries: Usa herramientas como Laravel Debugbar para detectar problemas N+1.

### ✅ **Consultas Complejas**
- **Usar** índices apropiados: Crea índices para campos usados frecuentemente en WHERE y ORDER BY.
- **Optimizar** consultas con EXPLAIN: Analiza el plan de ejecución de consultas complejas.
- **Limitar** resultados cuando sea posible: Usa LIMIT para evitar cargar datos innecesarios.
- **Usar** chunk para grandes datasets: Procesa grandes volúmenes de datos en lotes.

### ✅ **Paginación**
- **Usar** withQueryString() para mantener filtros: Los usuarios no pierden sus filtros al navegar.
- **Implementar** paginación simple para grandes datasets: Más rápida que paginación completa.
- **Cachear** resultados cuando sea posible: Reduce la carga en la base de datos.
- **Optimizar** consultas de count: Usa índices y cache para consultas de conteo.

### ✅ **Búsqueda**
- **Implementar** filtros dinámicos: Permite a los usuarios refinar sus búsquedas.
- **Usar** índices full-text para búsqueda: Mejora significativamente el rendimiento de búsquedas de texto.
- **Validar** parámetros de entrada: Previene errores y ataques de inyección.
- **Cachear** resultados frecuentes: Reduce la carga en la base de datos para búsquedas populares.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 