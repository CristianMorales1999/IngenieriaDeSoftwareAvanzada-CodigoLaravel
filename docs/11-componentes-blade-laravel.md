# 🧩 Componentes Blade en Laravel 12

## 🎯 Introducción

Los componentes Blade en Laravel son elementos reutilizables que encapsulan HTML, CSS y lógica de presentación. Permiten crear interfaces modulares, mantenibles y consistentes.

## 📁 Estructura de Componentes

### Ubicación
```
resources/views/components/
├── ui/
│   ├── button.blade.php
│   ├── input.blade.php
│   ├── card.blade.php
│   └── modal.blade.php
├── layout/
│   ├── header.blade.php
│   ├── footer.blade.php
│   ├── sidebar.blade.php
│   └── navigation.blade.php
├── forms/
│   ├── input-group.blade.php
│   ├── select.blade.php
│   └── textarea.blade.php
└── alerts/
    ├── success.blade.php
    ├── error.blade.php
    └── warning.blade.php
```

## 🚀 Crear Componentes

### Comando Artisan
```bash
php artisan make:component Button
php artisan make:component ui/Button
php artisan make:component Alert --view
php artisan make:component forms/InputGroup --view
```

### Estructura de un Componente Clásico
```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $type = 'button',
        public string $variant = 'primary',
        public bool $disabled = false,
        public ?string $size = null
    ) {}

    public function render()
    {
        return view('components.button');
    }
}
```

### Vista del Componente
```php
{{-- resources/views/components/button.blade.php --}}
<button 
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $this->getClasses()
    ]) }}
    @if($disabled) disabled @endif
>
    {{ $slot }}
</button>
```

## 🔧 Props y Slots

### 1. **Props Básicos**
```php
{{-- Componente con props --}}
<x-button type="submit" variant="success" size="lg">
    Guardar Cambios
</x-button>

{{-- Componente con props dinámicas --}}
<x-input 
    name="email" 
    type="email" 
    placeholder="Ingresa tu email"
    :required="true"
/>
```

### 2. **Slots Nombrados**
```php
{{-- resources/views/components/card.blade.php --}}
<div class="bg-white rounded-lg shadow-md p-6">
    @if(isset($header))
        <div class="border-b border-gray-200 pb-4 mb-4">
            {{ $header }}
        </div>
    @endif
    
    <div class="content">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="border-t border-gray-200 pt-4 mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
```

```php
{{-- Uso del componente con slots --}}
<x-card>
    <x-slot name="header">
        <h3 class="text-lg font-semibold text-gray-900">
            Información del Servicio
        </h3>
    </x-slot>
    
    <p class="text-gray-600">
        Contenido principal del card...
    </p>
    
    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-button variant="secondary">Cancelar</x-button>
            <x-button variant="primary">Guardar</x-button>
        </div>
    </x-slot>
</x-card>
```

### 3. **Props con Valores por Defecto**
```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public function __construct(
        public string $type = 'info',
        public bool $dismissible = false,
        public ?string $title = null
    ) {}

    public function getClasses(): string
    {
        $baseClasses = 'p-4 rounded-lg border';
        
        return match($this->type) {
            'success' => $baseClasses . ' bg-green-50 border-green-200 text-green-800',
            'error' => $baseClasses . ' bg-red-50 border-red-200 text-red-800',
            'warning' => $baseClasses . ' bg-yellow-50 border-yellow-200 text-yellow-800',
            'info' => $baseClasses . ' bg-blue-50 border-blue-200 text-blue-800',
            default => $baseClasses . ' bg-gray-50 border-gray-200 text-gray-800'
        };
    }

    public function render()
    {
        return view('components.alert');
    }
}
```

```php
{{-- resources/views/components/alert.blade.php --}}
<div class="{{ $getClasses() }}" role="alert">
    @if($title)
        <h4 class="font-semibold mb-2">{{ $title }}</h4>
    @endif
    
    <div class="flex items-start">
        <div class="flex-1">
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button 
                type="button" 
                class="ml-3 text-sm font-medium hover:opacity-75"
                onclick="this.parentElement.parentElement.remove()"
            >
                ×
            </button>
        @endif
    </div>
</div>
```

## 🎯 Componentes Anónimos

### 1. **Componente Anónimo Básico**
```php
{{-- resources/views/components/ui/button.blade.php --}}
@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false
])

@php
$classes = match($variant) {
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    default => 'bg-gray-600 hover:bg-gray-700 text-white'
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
    default => 'px-4 py-2 text-base'
};

$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $baseClasses . ' ' . $classes . ' ' . $sizeClasses
    ]) }}
    @if($disabled) disabled @endif
>
    {{ $slot }}
</button>
```

### 2. **Componente Anónimo con Slots**
```php
{{-- resources/views/components/ui/card.blade.php --}}
@props([
    'title' => null,
    'subtitle' => null,
    'image' => null
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden']) }}>
    @if($image)
        <div class="aspect-w-16 aspect-h-9">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">
        </div>
    @endif
    
    <div class="p-6">
        @if($title)
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ $title }}
            </h3>
        @endif
        
        @if($subtitle)
            <p class="text-sm text-gray-600 mb-4">
                {{ $subtitle }}
            </p>
        @endif
        
        <div class="content">
            {{ $slot }}
        </div>
        
        @if(isset($footer))
            <div class="mt-4 pt-4 border-t border-gray-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
```

### 3. **Componente de Formulario**
```php
{{-- resources/views/components/forms/input-group.blade.php --}}
@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => null,
    'required' => false,
    'error' => null
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm' . 
            ($error ? ' border-red-300' : '')
        ]) }}
        @if($required) required @endif
    >
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
```

## 🔄 Reutilización de Componentes

### 1. **Componente de Navegación**
```php
{{-- resources/views/components/layout/navigation.blade.php --}}
@props(['user' => null])

<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                        MiServicio
                    </a>
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <x-nav-link href="{{ route('services.index') }}" :active="request()->routeIs('services.*')">
                        Servicios
                    </x-nav-link>
                    <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                        Nosotros
                    </x-nav-link>
                    <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                        Contacto
                    </x-nav-link>
                </div>
            </div>
            
            <div class="flex items-center">
                @auth
                    <x-user-dropdown :user="$user" />
                @else
                    <div class="space-x-4">
                        <x-button href="{{ route('login') }}" variant="secondary" size="sm">
                            Iniciar Sesión
                        </x-button>
                        <x-button href="{{ route('register') }}" size="sm">
                            Registrarse
                        </x-button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
```

### 2. **Componente de Dropdown de Usuario**
```php
{{-- resources/views/components/layout/user-dropdown.blade.php --}}
@props(['user'])

<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open"
        class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <img 
            class="h-8 w-8 rounded-full" 
            src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
            alt="{{ $user->name }}"
        >
        <span class="text-gray-700">{{ $user->name }}</span>
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <div 
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
    >
        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Mi Perfil
        </a>
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Dashboard
        </a>
        <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Configuración
        </a>
        <hr class="my-1">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Cerrar Sesión
            </button>
        </form>
    </div>
</div>
```

### 3. **Componente de Tabla**
```php
{{-- resources/views/components/ui/table.blade.php --}}
@props(['headers' => [], 'striped' => true])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>
```

```php
{{-- Uso del componente tabla --}}
<x-table :headers="['Nombre', 'Email', 'Rol', 'Acciones']">
    @foreach($users as $user)
        <tr class="{{ $loop->even && $striped ? 'bg-gray-50' : '' }}">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $user->name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $user->email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $user->role }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <x-button size="sm" variant="secondary">Editar</x-button>
            </td>
        </tr>
    @endforeach
</x-table>
```

## 🎨 Componentes Avanzados

### 1. **Componente Modal**
```php
{{-- resources/views/components/ui/modal.blade.php --}}
@props([
    'id' => null,
    'title' => null,
    'maxWidth' => '2xl'
])

@php
$maxWidthClass = match($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    default => 'sm:max-w-2xl'
};
@endphp

<div 
    x-data="{ show: false }"
    x-show="show"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $maxWidthClass }} sm:w-full"
        >
            @if($title)
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $title }}
                            </h3>
                            <div class="mt-2">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{ $slot }}
            @endif

            @if(isset($footer))
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
```

### 2. **Componente de Paginación**
```php
{{-- resources/views/components/ui/pagination.blade.php --}}
@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    Siguiente
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Siguiente
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Pagination Elements --}}
                    {{ $slot }}
                </span>
            </div>
        </div>
    </nav>
@endif
```

## 📝 Comandos Útiles

```bash
# Crear componente básico
php artisan make:component Button

# Crear componente con vista
php artisan make:component Alert --view

# Crear componente anónimo (manual)
# Crear archivo en resources/views/components/

# Crear componente en subdirectorio
php artisan make:component ui/Button

# Crear componente de formulario
php artisan make:component forms/InputGroup --view
```

## 🎯 Resumen

Los componentes Blade en Laravel proporcionan:
- ✅ Reutilización de código HTML
- ✅ Encapsulación de lógica de presentación
- ✅ Props y slots flexibles
- ✅ Componentes anónimos para casos simples
- ✅ Mejor organización del código
- ✅ Consistencia en la interfaz

**Próximo paso:** Tailwind CSS 