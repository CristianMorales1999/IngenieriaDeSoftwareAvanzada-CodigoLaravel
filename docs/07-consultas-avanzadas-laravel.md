# 🔍 Consultas Avanzadas en Laravel 12

## 📋 **¿Qué son las Consultas Avanzadas?**

Las consultas avanzadas en Laravel te permiten optimizar el rendimiento, crear consultas complejas y manejar grandes volúmenes de datos de manera eficiente. Incluyen técnicas como Eager Loading, consultas complejas, paginación y sistemas de búsqueda. Son esenciales para aplicaciones que manejan muchos datos o requieren alto rendimiento.

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

#### 📊 **Eager Loading con Agregaciones**
```php
// Cargar con agregaciones
$servicios = Servicio::with(['reseñas' => function($query) {
    $query->select('servicio_id', 'puntuacion')
          ->selectRaw('AVG(puntuacion) as promedio_puntuacion')
          ->groupBy('servicio_id');
}])->get();
```

## 🔍 **Consultas Complejas**

### 📝 **Subconsultas**
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

### 🔗 **Joins Complejos**
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

### 📊 **Agregaciones Avanzadas**
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

### 🎯 **Consultas con Raw SQL**
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

## 📄 **Paginación Avanzada**

### 📝 **Paginación Básica**
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

### 🎯 **Paginación con Cursor**
```php
// Paginación con cursor (para grandes datasets)
$servicios = Servicio::where('activo', true)
    ->orderBy('id')
    ->cursorPaginate(12);
```

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

### 🎯 **Búsqueda con Scout (Laravel Scout)**
```php
// Búsqueda con Scout (requiere instalación)
$servicios = Servicio::search($buscar)
    ->where('activo', true)
    ->paginate(12);
```

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

## 🎯 **Buenas Prácticas**

### ✅ **Eager Loading**
- **Siempre** usar Eager Loading para relaciones
- **Cargar** solo campos necesarios
- **Usar** withCount para contar relaciones
- **Evitar** N+1 queries

### ✅ **Consultas Complejas**
- **Usar** índices apropiados
- **Optimizar** consultas con EXPLAIN
- **Limitar** resultados cuando sea posible
- **Usar** chunk para grandes datasets

### ✅ **Paginación**
- **Usar** withQueryString() para mantener filtros
- **Implementar** paginación simple para grandes datasets
- **Cachear** resultados cuando sea posible
- **Optimizar** consultas de count

### ✅ **Búsqueda**
- **Implementar** filtros dinámicos
- **Usar** índices full-text para búsqueda
- **Validar** parámetros de entrada
- **Cachear** resultados frecuentes

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 