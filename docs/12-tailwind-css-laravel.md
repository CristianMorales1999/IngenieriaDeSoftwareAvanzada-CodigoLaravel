# 🎨 Tailwind CSS en Laravel 12

## 🎯 Introducción

Tailwind CSS es un framework CSS utility-first que permite crear diseños modernos y responsivos de manera rápida y eficiente. En Laravel, se integra perfectamente con Vite para un desarrollo optimizado.

## ⚙️ Configuración

### 1. **Instalación Inicial**
```bash
# Instalar dependencias
npm install

# Instalar Tailwind CSS
npm install -D tailwindcss postcss autoprefixer

# Inicializar configuración
npx tailwindcss init -p
```

### 2. **Configuración de Tailwind**
```javascript
// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          900: '#1e3a8a',
        },
        secondary: {
          50: '#f8fafc',
          100: '#f1f5f9',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          900: '#0f172a',
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
```

### 3. **Configuración de PostCSS**
```javascript
// postcss.config.js
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
```

### 4. **Archivo CSS Principal**
```css
/* resources/css/app.css */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Componentes personalizados */
@layer components {
  .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
  }
  
  .btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
  }
  
  .card {
    @apply bg-white rounded-lg shadow-md p-6;
  }
  
  .input-field {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
  }
}

/* Utilidades personalizadas */
@layer utilities {
  .text-shadow {
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .text-shadow-lg {
    text-shadow: 0 4px 8px rgba(0,0,0,0.12);
  }
}
```

## 🎨 Clases Útiles por Categoría

### 1. **Layout y Espaciado**
```html
<!-- Contenedores -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto">
<div class="w-full md:w-1/2 lg:w-1/3">

<!-- Espaciado -->
<div class="p-4 m-2"> <!-- padding y margin -->
<div class="px-6 py-4"> <!-- padding horizontal y vertical -->
<div class="space-y-4"> <!-- espacio entre elementos hijos -->
<div class="space-x-4"> <!-- espacio entre elementos inline -->

<!-- Flexbox -->
<div class="flex items-center justify-between">
<div class="flex flex-col space-y-4">
<div class="flex-1"> <!-- flex-grow -->
<div class="flex-shrink-0"> <!-- flex-shrink -->

<!-- Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="grid grid-cols-12 gap-4">
```

### 2. **Tipografía**
```html
<!-- Tamaños de texto -->
<h1 class="text-4xl font-bold">Título Principal</h1>
<h2 class="text-2xl font-semibold">Subtítulo</h2>
<p class="text-base">Texto normal</p>
<span class="text-sm text-gray-600">Texto pequeño</span>

<!-- Pesos de fuente -->
<p class="font-light">Texto ligero</p>
<p class="font-normal">Texto normal</p>
<p class="font-medium">Texto medio</p>
<p class="font-semibold">Texto semi-negrita</p>
<p class="font-bold">Texto negrita</p>

<!-- Colores de texto -->
<p class="text-gray-900">Texto oscuro</p>
<p class="text-gray-600">Texto medio</p>
<p class="text-gray-400">Texto claro</p>
<p class="text-blue-600">Texto azul</p>
<p class="text-green-600">Texto verde</p>
<p class="text-red-600">Texto rojo</p>

<!-- Alineación -->
<p class="text-left">Izquierda</p>
<p class="text-center">Centro</p>
<p class="text-right">Derecha</p>
<p class="text-justify">Justificado</p>
```

### 3. **Colores y Fondos**
```html
<!-- Colores de fondo -->
<div class="bg-white">Fondo blanco</div>
<div class="bg-gray-100">Fondo gris claro</div>
<div class="bg-blue-500">Fondo azul</div>
<div class="bg-gradient-to-r from-blue-500 to-purple-600">Gradiente</div>

<!-- Bordes -->
<div class="border border-gray-300">Borde gris</div>
<div class="border-2 border-blue-500">Borde azul grueso</div>
<div class="border-l-4 border-green-500">Borde izquierdo verde</div>

<!-- Sombras -->
<div class="shadow-sm">Sombra pequeña</div>
<div class="shadow">Sombra normal</div>
<div class="shadow-lg">Sombra grande</div>
<div class="shadow-xl">Sombra extra grande</div>
```

### 4. **Formularios**
```html
<!-- Input básico -->
<input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

<!-- Input con label -->
<div class="space-y-1">
  <label class="block text-sm font-medium text-gray-700">Email</label>
  <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Select -->
<select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
  <option>Opción 1</option>
  <option>Opción 2</option>
</select>

<!-- Textarea -->
<textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="4"></textarea>

<!-- Checkbox -->
<div class="flex items-center">
  <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
  <label class="ml-2 block text-sm text-gray-900">Acepto los términos</label>
</div>

<!-- Radio -->
<div class="flex items-center">
  <input type="radio" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
  <label class="ml-2 block text-sm text-gray-900">Opción 1</label>
</div>
```

### 5. **Botones**
```html
<!-- Botón primario -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Primario
</button>

<!-- Botón secundario -->
<button class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Secundario
</button>

<!-- Botón outline -->
<button class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Outline
</button>

<!-- Botón pequeño -->
<button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded transition-colors duration-200">
  Botón Pequeño
</button>

<!-- Botón grande -->
<button class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-medium py-3 px-6 rounded-lg transition-colors duration-200">
  Botón Grande
</button>
```

## 📱 Responsive Design

### 1. **Breakpoints**
```html
<!-- Breakpoints de Tailwind -->
<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
  <!-- w-full en móvil, 1/2 en tablet, 1/3 en desktop, 1/4 en xl -->
</div>

<!-- Ocultar/Mostrar elementos -->
<div class="hidden md:block">Visible solo en tablet y desktop</div>
<div class="block md:hidden">Visible solo en móvil</div>
<div class="hidden lg:block">Visible solo en desktop</div>
```

### 2. **Grid Responsive**
```html
<!-- Grid que se adapta -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
  <div class="card">Item 1</div>
  <div class="card">Item 2</div>
  <div class="card">Item 3</div>
  <div class="card">Item 4</div>
</div>

<!-- Grid con columnas automáticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <div class="bg-white p-6 rounded-lg shadow">Card 1</div>
  <div class="bg-white p-6 rounded-lg shadow">Card 2</div>
  <div class="bg-white p-6 rounded-lg shadow">Card 3</div>
</div>
```

### 3. **Navegación Responsive**
```html
<!-- Navegación que se adapta -->
<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <h1 class="text-xl font-bold text-gray-900">Logo</h1>
      </div>
      
      <!-- Menú desktop -->
      <div class="hidden md:flex md:items-center md:space-x-6">
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Servicios</a>
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contacto</a>
      </div>
      
      <!-- Botón móvil -->
      <div class="md:hidden">
        <button class="text-gray-600 hover:text-gray-900">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</nav>
```

## 🧩 Componentes Personalizados

### 1. **Card Component**
```html
<!-- resources/views/components/ui/card.blade.php -->
@props(['title' => null, 'subtitle' => null, 'image' => null])

<div class="bg-white rounded-lg shadow-md overflow-hidden">
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

### 2. **Alert Component**
```html
<!-- resources/views/components/ui/alert.blade.php -->
@props(['type' => 'info', 'dismissible' => false])

@php
$classes = match($type) {
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    default => 'bg-gray-50 border-gray-200 text-gray-800'
};
@endphp

<div class="p-4 rounded-lg border {{ $classes }}" role="alert">
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

### 3. **Modal Component**
```html
<!-- resources/views/components/ui/modal.blade.php -->
@props(['id' => null, 'title' => null])

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
      class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
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

## 🎨 Animaciones y Transiciones

### 1. **Transiciones Básicas**
```html
<!-- Transición de color -->
<button class="bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200">
  Botón con transición
</button>

<!-- Transición de escala -->
<div class="transform hover:scale-105 transition-transform duration-200">
  <img src="image.jpg" alt="Imagen" class="w-full h-64 object-cover rounded-lg">
</div>

<!-- Transición de opacidad -->
<div class="opacity-75 hover:opacity-100 transition-opacity duration-200">
  Elemento con opacidad
</div>
```

### 2. **Animaciones Personalizadas**
```css
/* En tu archivo CSS */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
  from { transform: translateX(-100%); }
  to { transform: translateX(0); }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

.animate-slide-in {
  animation: slideIn 0.3s ease-out;
}
```

```html
<!-- Uso de animaciones -->
<div class="animate-fade-in">
  Contenido que aparece con fade
</div>

<div class="animate-slide-in">
  Contenido que se desliza
</div>
```

## 📱 Ejemplos de Layouts

### 1. **Dashboard Layout**
```html
<div class="min-h-screen bg-gray-100">
  <!-- Header -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
        </div>
        <div class="flex items-center space-x-4">
          <button class="text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12a1 1 0 00-2 0v12z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stats Cards -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                  <dd class="text-lg font-medium text-gray-900">1,234</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
```

### 2. **Landing Page Layout**
```html
<div class="min-h-screen bg-white">
  <!-- Navigation -->
  <nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-gray-900">MiServicio</h1>
        </div>
        <div class="hidden md:flex md:items-center md:space-x-6">
          <a href="#" class="text-gray-600 hover:text-gray-900">Inicio</a>
          <a href="#" class="text-gray-600 hover:text-gray-900">Servicios</a>
          <a href="#" class="text-gray-600 hover:text-gray-900">Contacto</a>
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Registrarse
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="relative bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto">
      <div class="relative z-10 pb-8 bg-gray-50 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
        <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
          <div class="sm:text-center lg:text-left">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
              <span class="block xl:inline">Servicios profesionales</span>
              <span class="block text-blue-600 xl:inline">para tu negocio</span>
            </h1>
            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
              Ofrecemos soluciones integrales para hacer crecer tu empresa con la mejor tecnología y atención personalizada.
            </p>
            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
              <div class="rounded-md shadow">
                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                  Comenzar
                </a>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-3">
                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                  Saber más
                </a>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</div>
```

## 📝 Comandos Útiles

```bash
# Instalar Tailwind CSS
npm install -D tailwindcss postcss autoprefixer

# Inicializar configuración
npx tailwindcss init -p

# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build

# Instalar plugins adicionales
npm install -D @tailwindcss/forms @tailwindcss/typography @tailwindcss/aspect-ratio
```

## 🎯 Resumen

Tailwind CSS en Laravel proporciona:
- ✅ Framework utility-first para desarrollo rápido
- ✅ Configuración personalizable
- ✅ Componentes reutilizables
- ✅ Diseño responsive nativo
- ✅ Animaciones y transiciones
- ✅ Integración perfecta con Vite

**Próximo paso:** Implementación práctica de la Fase 4 