# 🎨 Blade Templates y Plantillas en Laravel 12

## 📋 **¿Qué es Blade?**

Blade es el motor de plantillas de Laravel que permite crear vistas dinámicas y reutilizables. Combina HTML con sintaxis PHP de manera elegante y segura. Es la forma en que Laravel renderiza las vistas que ven los usuarios.

### 🎯 **Características Principales**
- **Sintaxis intuitiva**: Fácil de aprender y usar, similar a HTML pero con funcionalidades PHP
- **Seguridad**: Escape automático de datos para prevenir ataques XSS
- **Reutilización**: Componentes y layouts que se pueden usar en múltiples páginas
- **Rendimiento**: Compilación a PHP puro para máxima velocidad
- **Herencia**: Sistema de herencia que permite crear layouts base y extenderlos

## 🏗️ **Estructura de Archivos Blade**

Laravel organiza las vistas Blade en una estructura clara y lógica. Todas las vistas se encuentran en la carpeta `resources/views/`:

```
resources/views/
├── layouts/           # Layouts principales (estructura base de las páginas)
│   ├── app.blade.php  # Layout principal para usuarios autenticados
│   └── guest.blade.php # Layout para usuarios no autenticados
├── components/        # Componentes reutilizables (elementos que se usan en múltiples páginas)
│   ├── header.blade.php # Encabezado de la página
│   ├── footer.blade.php # Pie de página
│   └── forms/         # Formularios reutilizables
├── pages/            # Páginas específicas (páginas únicas)
│   ├── home.blade.php # Página de inicio
│   └── about.blade.php # Página sobre nosotros
├── servicios/         # Vistas de servicios (CRUD completo)
│   ├── index.blade.php # Lista de servicios
│   ├── show.blade.php  # Detalle de un servicio
│   ├── create.blade.php # Formulario de creación
│   └── edit.blade.php  # Formulario de edición
└── partials/         # Fragmentos reutilizables (elementos pequeños)
    ├── nav.blade.php  # Navegación
    └── alerts.blade.php # Alertas y mensajes
```

**Explicación de cada carpeta:**
- **layouts/**: Contiene las plantillas base que definen la estructura HTML común
- **components/**: Elementos reutilizables como headers, footers, formularios
- **pages/**: Páginas específicas que no son parte de un CRUD
- **servicios/**: Vistas relacionadas con el módulo de servicios (siguiendo convenciones)
- **partials/**: Fragmentos pequeños que se incluyen en múltiples vistas

## 🚀 **Sintaxis Básica de Blade**

### 📝 **Echo de Variables**
Las variables en Blade se muestran usando la sintaxis `{{ }}`. Blade automáticamente escapa el HTML para prevenir ataques XSS:

```php
{{-- Mostrar variable - Escape automático de HTML para seguridad --}}
<h1>{{ $titulo }}</h1>

{{-- Mostrar variable sin escape HTML - Solo usar cuando confíes en el contenido --}}
{!! $contenido_html !!}

{{-- Mostrar variable con valor por defecto - Si $subtitulo es null, muestra el texto por defecto --}}
<h2>{{ $subtitulo ?? 'Sin subtítulo' }}</h2>

{{-- Mostrar variable si existe - Verifica que la variable esté definida antes de mostrarla --}}
@if(isset($descripcion))
    <p>{{ $descripcion }}</p>
@endif
```

**Explicación de las diferencias:**
- **`{{ }}`**: Escapa automáticamente el HTML (recomendado para datos del usuario)
- **`{!! !!}`**: No escapa HTML (solo usar para contenido que confías completamente)
- **`??`**: Operador de coalescencia nula (muestra valor por defecto si la variable es null)
- **`@if(isset())`**: Verifica que la variable exista antes de mostrarla

### 🔤 **Comentarios**
Los comentarios en Blade no aparecen en el HTML final, lo que los hace ideales para documentar el código:

```php
{{-- Este es un comentario Blade --}}
{{-- Los comentarios no aparecen en el HTML final --}}
{{-- Útil para explicar lógica compleja o recordar para qué sirve cada sección --}}
```

**Explicación:**
- Los comentarios Blade usan la sintaxis `{{-- --}}`
- No se incluyen en el HTML final que ve el usuario
- Son útiles para documentar lógica compleja o explicar el propósito de cada sección
- A diferencia de los comentarios HTML (`<!-- -->`), estos no se ven en el código fuente

## 🎯 **Estructuras de Control**

### 📋 **Condicionales**

#### 🔀 **@if, @elseif, @else**
Las estructuras condicionales en Blade permiten mostrar contenido diferente según las condiciones:

```php
@if($servicio->precio > 100)
    <span class="text-red-500">Servicio Premium</span>
@elseif($servicio->precio > 50)
    <span class="text-yellow-500">Servicio Estándar</span>
@else
    <span class="text-green-500">Servicio Básico</span>
@endif
```

**Explicación del flujo:**
1. **@if**: Verifica si el precio es mayor a 100 → Muestra "Premium" en rojo
2. **@elseif**: Si no es mayor a 100, verifica si es mayor a 50 → Muestra "Estándar" en amarillo
3. **@else**: Si no cumple ninguna condición anterior → Muestra "Básico" en verde
4. **@endif**: Cierra la estructura condicional

**Uso común:** Mostrar diferentes estilos o contenido según el valor de una variable

#### ✅ **@unless (Inverso de @if)**
`@unless` es lo contrario de `@if`. Se ejecuta cuando la condición es falsa:

```php
@unless($usuario->es_admin)
    <p>Acceso limitado</p>
@endunless
```

**Explicación:**
- **@unless**: Se ejecuta cuando `$usuario->es_admin` es `false`
- Es equivalente a `@if(!$usuario->es_admin)`
- Útil cuando quieres mostrar algo solo cuando una condición NO se cumple
- En este caso: "Si el usuario NO es admin, muestra 'Acceso limitado'"

#### 🔍 **@isset y @empty**
Estas directivas verifican el estado de las variables de manera específica:

```php
{{-- Verificar si variable existe - Muestra la descripción solo si existe --}}
@isset($servicio->descripcion)
    <p>{{ $servicio->descripcion }}</p>
@endisset

{{-- Verificar si variable está vacía - Muestra mensaje si no hay servicios --}}
@empty($servicios)
    <p>No hay servicios disponibles</p>
@endempty
```

**Explicación de las diferencias:**
- **@isset**: Verifica si la variable está definida (no es null)
- **@empty**: Verifica si la variable está vacía (null, array vacío, string vacío, etc.)
- **@isset** es más específico que `@if(isset())`
- **@empty** es más específico que `@if(empty())`

### 🔄 **Bucles**

#### 📋 **@foreach**
`@foreach` es el bucle más común en Blade. Itera sobre arrays y colecciones:

```php
@foreach($servicios as $servicio)
    <div class="servicio-card">
        <h3>{{ $servicio->nombre }}</h3>
        <p>{{ $servicio->descripcion }}</p>
        <span class="precio">${{ $servicio->precio }}</span>
    </div>
@endforeach
```

**Explicación:**
- **@foreach**: Itera sobre cada elemento de la colección `$servicios`
- **$servicio**: Variable que contiene cada elemento individual
- **@endforeach**: Cierra el bucle
- **Uso común**: Mostrar listas de elementos, tablas, tarjetas, etc.
- **Equivalente a**: `foreach($servicios as $servicio) { ... }` en PHP puro

#### 🔢 **@for**
`@for` es un bucle tradicional que itera un número específico de veces:

```php
@for($i = 1; $i <= 5; $i++)
    <div class="estrella">⭐</div>
@endfor
```

**Explicación:**
- **@for**: Bucle que se ejecuta desde 1 hasta 5
- **$i**: Variable contador que va de 1 a 5
- **@endfor**: Cierra el bucle
- **Resultado**: Muestra 5 estrellas (⭐ ⭐ ⭐ ⭐ ⭐)
- **Uso común**: Mostrar elementos repetitivos como estrellas de rating, puntos, etc.
- **Equivalente a**: `for($i = 1; $i <= 5; $i++) { ... }` en PHP puro

#### 🔄 **@while**
`@while` es un bucle que se ejecuta mientras una condición sea verdadera:

```php
@php $i = 0; @endphp
@while($i < count($servicios))
    <div>{{ $servicios[$i]->nombre }}</div>
    @php $i++; @endphp
@endwhile
```

**Explicación:**
- **@php**: Permite escribir código PHP puro dentro de Blade
- **$i = 0**: Inicializa el contador en 0
- **@while**: Se ejecuta mientras `$i` sea menor que el número de servicios
- **$servicios[$i]->nombre**: Accede al servicio por índice
- **$i++**: Incrementa el contador en cada iteración
- **@endwhile**: Cierra el bucle
- **Uso común**: Cuando necesitas control manual del índice (menos común que @foreach)

### 🎯 **Variables de Bucle**

#### 📊 **@foreach con $loop**
```php
@foreach($servicios as $servicio)
    <div class="servicio {{ $loop->first ? 'first' : '' }} {{ $loop->last ? 'last' : '' }}">
        <span class="numero">{{ $loop->iteration }}</span>
        <h3>{{ $servicio->nombre }}</h3>
        
        @if($loop->even)
            <div class="destacado">¡Oferta especial!</div>
        @endif
        
        @if($loop->remaining > 0)
            <hr>
        @endif
    </div>
@endforeach
```

**Propiedades disponibles:**
- `$loop->index` - Índice actual (0-based)
- `$loop->iteration` - Iteración actual (1-based)
- `$loop->first` - ¿Es el primer elemento?
- `$loop->last` - ¿Es el último elemento?
- `$loop->even` - ¿Es índice par?
- `$loop->odd` - ¿Es índice impar?
- `$loop->count` - Total de elementos
- `$loop->remaining` - Elementos restantes

## 🏗️ **Layouts y Herencia**

### 📋 **Layout Principal**
```php
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('components.header')
    
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>
    
    @include('components.footer')
</body>
</html>
```

### 📄 **Vista que Extiende el Layout**
```php
{{-- resources/views/servicios/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Servicios - Mi Aplicación')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Nuestros Servicios</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($servicios as $servicio)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $servicio->nombre }}</h3>
                <p class="text-gray-600 mb-4">{{ $servicio->descripcion }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-blue-600">
                        ${{ number_format($servicio->precio, 2) }}
                    </span>
                    <a href="{{ route('servicios.show', $servicio->id) }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Ver Detalles
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
```

## 🧩 **Componentes y Includes**

### 📋 **Include de Archivos**
```php
{{-- Incluir archivo completo --}}
@include('components.header')

{{-- Incluir con variables --}}
@include('components.alert', ['type' => 'success', 'message' => 'Operación exitosa'])

{{-- Incluir solo si existe --}}
@includeIf('components.custom-header')

{{-- Incluir con fallback --}}
@includeWhen($usuario->es_admin, 'components.admin-panel')
```

### 🎯 **Componentes Blade (Laravel 7+)**

#### 📝 **Componente Simple**
```php
{{-- resources/views/components/alert.blade.php --}}
<div class="alert alert-{{ $type ?? 'info' }} {{ $class ?? '' }}">
    {{ $slot }}
</div>
```

**Uso:**
```php
<x-alert type="success" class="mb-4">
    El servicio se ha creado exitosamente.
</x-alert>
```

#### 🎯 **Componente con Props**
```php
{{-- resources/views/components/servicio-card.blade.php --}}
@props(['servicio', 'showActions' => true])

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-semibold mb-2">{{ $servicio->nombre }}</h3>
    <p class="text-gray-600 mb-4">{{ $servicio->descripcion }}</p>
    
    @if($showActions)
        <div class="flex justify-between items-center">
            <span class="text-2xl font-bold text-blue-600">
                ${{ number_format($servicio->precio, 2) }}
            </span>
            <a href="{{ route('servicios.show', $servicio->id) }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Ver Detalles
            </a>
        </div>
    @endif
</div>
```

**Uso:**
```php
<x-servicio-card :servicio="$servicio" :show-actions="true" />
```

## 🎨 **Directivas Útiles**

### 📝 **@php - Código PHP Puro**
```php
@php
    $total = 0;
    foreach($servicios as $servicio) {
        $total += $servicio->precio;
    }
@endphp

<p>Total: ${{ number_format($total, 2) }}</p>
```

### 🔄 **@break y @continue**
```php
@foreach($servicios as $servicio)
    @if($servicio->precio > 1000)
        @continue
    @endif
    
    @if($servicio->id == 5)
        @break
    @endif
    
    <div>{{ $servicio->nombre }}</div>
@endforeach
```

### 📋 **@switch**
```php
@switch($servicio->categoria)
    @case('consultoria')
        <span class="badge badge-blue">Consultoría</span>
        @break
    @case('desarrollo')
        <span class="badge badge-green">Desarrollo</span>
        @break
    @default
        <span class="badge badge-gray">Otro</span>
@endswitch
```

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Sistema de Servicios con Blade**

#### 🏠 **Layout Principal**
```php
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Servicios App')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <x-header />
    
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <x-alert type="success" class="mb-4">
                {{ session('success') }}
            </x-alert>
        @endif
        
        @if(session('error'))
            <x-alert type="error" class="mb-4">
                {{ session('error') }}
            </x-alert>
        @endif
        
        @yield('content')
    </main>
    
    <x-footer />
</body>
</html>
```

#### 📋 **Componente Header**
```php
{{-- resources/views/components/header.blade.php --}}
<header class="bg-white shadow-md">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                ServiciosApp
            </a>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('servicios.index') }}" class="hover:text-blue-600">Servicios</a>
                
                @auth
                    <div class="relative">
                        <button class="flex items-center space-x-2 hover:text-blue-600">
                            <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full">
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                Dashboard
                            </a>
                            <a href="{{ route('servicios.create') }}" class="block px-4 py-2 hover:bg-gray-100">
                                Crear Servicio
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>
```

#### 📄 **Vista de Lista de Servicios**
```php
{{-- resources/views/servicios/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Nuestros Servicios</h1>
        
        @auth
            <a href="{{ route('servicios.create') }}" 
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                Crear Servicio
            </a>
        @endauth
    </div>
    
    @if($servicios->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($servicios as $servicio)
                <x-servicio-card :servicio="$servicio" />
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $servicios->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <h3 class="text-xl text-gray-600 mb-4">No hay servicios disponibles</h3>
            <p class="text-gray-500">Pronto agregaremos nuevos servicios.</p>
        </div>
    @endif
@endsection
```

## 🎯 **Buenas Prácticas**

### ✅ **Organización de Archivos**
- Usar **layouts/** para plantillas principales
- Crear **components/** para elementos reutilizables
- Organizar vistas por **funcionalidad** (servicios/, usuarios/)
- Usar **partials/** para fragmentos pequeños

### ✅ **Nomenclatura**
- **Layouts**: `app.blade.php`, `guest.blade.php`
- **Componentes**: `header.blade.php`, `servicio-card.blade.php`
- **Vistas**: `index.blade.php`, `show.blade.php`

### ✅ **Seguridad**
- **Siempre** usar `{{ }}` para escape automático
- Usar `{!! !!}` solo cuando confíes en el contenido
- Validar datos antes de mostrarlos

### ✅ **Rendimiento**
- Usar **@include** para fragmentos pequeños
- Usar **componentes** para lógica compleja
- Evitar **@php** innecesario
- Usar **@once** para código que debe ejecutarse una sola vez

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 