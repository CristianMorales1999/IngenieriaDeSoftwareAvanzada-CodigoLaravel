# 🎨 Blade Templates y Plantillas en Laravel 12

## 📋 **¿Qué es Blade?**

Blade es el motor de plantillas de Laravel que permite crear vistas dinámicas y reutilizables. Combina HTML con sintaxis PHP de manera elegante y segura.

### 🎯 **Características Principales**
- **Sintaxis intuitiva**: Fácil de aprender y usar
- **Seguridad**: Escape automático de datos
- **Reutilización**: Componentes y layouts
- **Rendimiento**: Compilación a PHP puro

## 🏗️ **Estructura de Archivos Blade**

```
resources/views/
├── layouts/           # Layouts principales
│   ├── app.blade.php
│   └── guest.blade.php
├── components/        # Componentes reutilizables
│   ├── header.blade.php
│   ├── footer.blade.php
│   └── forms/
├── pages/            # Páginas específicas
│   ├── home.blade.php
│   └── about.blade.php
├── servicios/         # Vistas de servicios
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── partials/         # Fragmentos reutilizables
    ├── nav.blade.php
    └── alerts.blade.php
```

## 🚀 **Sintaxis Básica de Blade**

### 📝 **Echo de Variables**
```php
{{-- Mostrar variable --}}
<h1>{{ $titulo }}</h1>

{{-- Mostrar variable sin escape HTML --}}
{!! $contenido_html !!}

{{-- Mostrar variable con valor por defecto --}}
<h2>{{ $subtitulo ?? 'Sin subtítulo' }}</h2>

{{-- Mostrar variable si existe --}}
@if(isset($descripcion))
    <p>{{ $descripcion }}</p>
@endif
```

### 🔤 **Comentarios**
```php
{{-- Este es un comentario Blade --}}
{{-- Los comentarios no aparecen en el HTML final --}}
```

## 🎯 **Estructuras de Control**

### 📋 **Condicionales**

#### 🔀 **@if, @elseif, @else**
```php
@if($servicio->precio > 100)
    <span class="text-red-500">Servicio Premium</span>
@elseif($servicio->precio > 50)
    <span class="text-yellow-500">Servicio Estándar</span>
@else
    <span class="text-green-500">Servicio Básico</span>
@endif
```

#### ✅ **@unless (Inverso de @if)**
```php
@unless($usuario->es_admin)
    <p>Acceso limitado</p>
@endunless
```

#### 🔍 **@isset y @empty**
```php
{{-- Verificar si variable existe --}}
@isset($servicio->descripcion)
    <p>{{ $servicio->descripcion }}</p>
@endisset

{{-- Verificar si variable está vacía --}}
@empty($servicios)
    <p>No hay servicios disponibles</p>
@endempty
```

### 🔄 **Bucles**

#### 📋 **@foreach**
```php
@foreach($servicios as $servicio)
    <div class="servicio-card">
        <h3>{{ $servicio->nombre }}</h3>
        <p>{{ $servicio->descripcion }}</p>
        <span class="precio">${{ $servicio->precio }}</span>
    </div>
@endforeach
```

#### 🔢 **@for**
```php
@for($i = 1; $i <= 5; $i++)
    <div class="estrella">⭐</div>
@endfor
```

#### 🔄 **@while**
```php
@php $i = 0; @endphp
@while($i < count($servicios))
    <div>{{ $servicios[$i]->nombre }}</div>
    @php $i++; @endphp
@endwhile
```

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