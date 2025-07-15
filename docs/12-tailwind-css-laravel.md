# 🎨 Tailwind CSS en Laravel 12

## 📋 **¿Qué es Tailwind CSS?**

Tailwind CSS es un framework CSS utility-first que permite crear diseños modernos y responsivos de manera rápida y eficiente. En lugar de escribir CSS personalizado, usas clases predefinidas que puedes combinar para crear cualquier diseño. En Laravel, se integra perfectamente con Vite para un desarrollo optimizado y un rendimiento excepcional.

**¿Por qué usar Tailwind CSS?**
- **Desarrollo rápido**: No necesitas escribir CSS personalizado, solo combinas clases
- **Consistencia**: Todas las clases siguen un sistema de diseño coherente
- **Responsive por defecto**: Las clases responsive están integradas desde el inicio
- **Tamaño optimizado**: Solo incluye las clases que realmente usas
- **Mantenibilidad**: Cambios en un lugar se reflejan en toda la aplicación
- **Productividad**: Acelera el desarrollo con clases intuitivas y predecibles

### 🎯 **Características Principales**

**Utility-First**: Cada clase hace una cosa específica. En lugar de escribir CSS personalizado, combinas clases como `flex`, `pt-4`, `text-center`, `rotate-90`.

**Responsive Design**: Clases responsive integradas como `md:flex`, `lg:text-xl` que se aplican automáticamente en diferentes tamaños de pantalla.

**Dark Mode**: Soporte nativo para modo oscuro con clases como `dark:bg-gray-900`, `dark:text-white`.

**Customizable**: Puedes personalizar colores, espaciados, fuentes y más en el archivo de configuración.

**Purge CSS**: Automáticamente elimina clases no utilizadas en producción para reducir el tamaño del archivo CSS.

**Componentes**: Puedes crear componentes personalizados usando `@apply` para reutilizar combinaciones de clases.

## ⚙️ **Configuración**

### 📦 **Instalación Inicial**

La instalación de Tailwind CSS en Laravel es sencilla y se hace a través de npm. Laravel ya viene con Vite configurado, lo que hace que la integración sea muy fluida:

```bash
# Instalar todas las dependencias del proyecto
npm install

# Instalar Tailwind CSS y sus dependencias
npm install -D tailwindcss postcss autoprefixer

# Inicializar configuración de Tailwind y PostCSS
npx tailwindcss init -p
```

**Explicación detallada de cada comando:**

**`npm install`**: Instala todas las dependencias listadas en `package.json`. Incluye Vite, Alpine.js y otras herramientas de desarrollo.

**`npm install -D tailwindcss postcss autoprefixer`**: Instala las dependencias de desarrollo necesarias para Tailwind CSS:
- **tailwindcss**: El framework CSS principal con todas las utilidades
- **postcss**: Procesador CSS que optimiza y transforma el código CSS
- **autoprefixer**: Agrega automáticamente prefijos de navegador para compatibilidad

**`npx tailwindcss init -p`**: Crea los archivos de configuración:
- **tailwind.config.js**: Configuración principal de Tailwind CSS
- **postcss.config.js**: Configuración de PostCSS para procesamiento

**¿Por qué usar `-D`?**: Las dependencias de desarrollo solo se instalan en el entorno de desarrollo, no en producción. Esto reduce el tamaño del proyecto en producción.

**¿Por qué PostCSS?**: PostCSS procesa el CSS y aplica optimizaciones como autoprefixer, que agrega prefijos como `-webkit-`, `-moz-` automáticamente.

### 🎨 **Configuración de Tailwind**

El archivo `tailwind.config.js` es el corazón de la configuración de Tailwind CSS. Define qué archivos procesar, personaliza el tema y agrega funcionalidades adicionales:

```javascript
// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
  // Archivos que Tailwind debe escanear para generar CSS
  content: [
    "./resources/**/*.blade.php",        // Archivos Blade de Laravel
    "./resources/**/*.js",               // Archivos JavaScript
    "./resources/**/*.vue",              // Archivos Vue (si usas Vue)
    "./app/View/Components/**/*.php",    // Componentes Blade
    "./resources/views/**/*.blade.php",  // Vistas Blade
  ],
  
  // Configuración del tema (colores, fuentes, espaciados, etc.)
  theme: {
    extend: {
      // Paleta de colores personalizada
      colors: {
        primary: {
          50: '#eff6ff',   // Azul muy claro (hover states)
          100: '#dbeafe',  // Azul claro (backgrounds)
          500: '#3b82f6',  // Azul principal (botones, links)
          600: '#2563eb',  // Azul oscuro (hover de botones)
          700: '#1d4ed8',  // Azul más oscuro (active states)
          900: '#1e3a8a',  // Azul muy oscuro (textos importantes)
        },
        secondary: {
          50: '#f8fafc',   // Gris muy claro
          100: '#f1f5f9',  // Gris claro
          500: '#64748b',  // Gris principal
          600: '#475569',  // Gris oscuro
          700: '#334155',  // Gris más oscuro
          900: '#0f172a',  // Gris muy oscuro
        }
      },
      
      // Fuentes personalizadas
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Fuente principal del sitio
        mono: ['Fira Code', 'monospace'], // Fuente para código
      },
      
      // Espaciados personalizados
      spacing: {
        '18': '4.5rem',   // Espaciado personalizado (72px)
        '88': '22rem',    // Espaciado personalizado (352px)
        '128': '32rem',   // Espaciado personalizado (512px)
      },
      
      // Animaciones personalizadas
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',    // Animación de aparición
        'slide-up': 'slideUp 0.3s ease-out',     // Animación de deslizamiento
        'bounce-slow': 'bounce 2s infinite',     // Bounce más lento
      },
      
      // Keyframes para animaciones personalizadas
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },     // Inicio: completamente transparente
          '100%': { opacity: '1' },   // Final: completamente visible
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' }, // Inicio: abajo y transparente
          '100%': { transform: 'translateY(0)', opacity: '1' },  // Final: posición normal y visible
        },
      },
    },
  },
  
  // Plugins que agregan funcionalidades adicionales
  plugins: [
    require('@tailwindcss/forms'),      // Estilos mejorados para formularios
    require('@tailwindcss/typography'), // Estilos para contenido de texto rico
    require('@tailwindcss/aspect-ratio'), // Control de proporciones de imágenes
  ],
}
```

**Explicación detallada de cada sección:**

**`content`**: Define qué archivos debe escanear Tailwind para encontrar clases CSS utilizadas. Solo las clases que realmente usas se incluyen en el CSS final (Purge CSS).

**`theme.extend`**: Extiende el tema por defecto de Tailwind sin sobrescribirlo completamente. Esto te permite agregar personalizaciones manteniendo las utilidades por defecto.

**`colors`**: Define tu paleta de colores personalizada. Cada color tiene variantes del 50 al 900 para diferentes usos (fondos, textos, hover, etc.).

**`fontFamily`**: Define las fuentes que usarás en tu aplicación. `sans` es la fuente por defecto para texto normal.

**`spacing`**: Agrega espaciados personalizados que puedes usar con clases como `p-18`, `m-88`, etc.

**`animation` y `keyframes`**: Define animaciones personalizadas que puedes usar con clases como `animate-fade-in`.

**`plugins`**: Agregan funcionalidades adicionales como estilos de formularios mejorados y control de proporciones.

**Ventajas de esta configuración:**

**Optimización**: Solo incluye las clases que realmente usas, reduciendo el tamaño del CSS.

**Consistencia**: Define una paleta de colores coherente en toda la aplicación.

**Flexibilidad**: Puedes agregar cualquier personalización que necesites.

**Mantenibilidad**: Cambios en un lugar se reflejan en toda la aplicación.

### ⚙️ **Configuración de PostCSS**

PostCSS es un procesador CSS que optimiza y transforma tu código CSS. La configuración es simple pero importante:

```javascript
// postcss.config.js
export default {
  plugins: {
    tailwindcss: {},    // Procesa las directivas de Tailwind
    autoprefixer: {},   // Agrega prefijos de navegador automáticamente
  },
}
```

**Explicación detallada:**

**`tailwindcss`**: Procesa las directivas `@tailwind` y `@apply` de Tailwind CSS, convirtiéndolas en CSS válido.

**`autoprefixer`**: Agrega automáticamente prefijos de navegador como `-webkit-`, `-moz-`, `-ms-` para compatibilidad con navegadores antiguos.

**¿Por qué PostCSS?**: PostCSS actúa como un "pipeline" que procesa tu CSS antes de que llegue al navegador, aplicando optimizaciones y transformaciones.

### 📝 **Archivo CSS Principal**

El archivo `resources/css/app.css` es donde importas Tailwind CSS y defines tus componentes personalizados:

```css
/* resources/css/app.css */

/* Importar las capas de Tailwind CSS */
@import 'tailwindcss/base';      /* Estilos base y reset */
@import 'tailwindcss/components'; /* Componentes y clases de componentes */
@import 'tailwindcss/utilities';  /* Clases de utilidades */

/* Componentes personalizados usando @layer components */
@layer components {
  /* Botón primario reutilizable */
  .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
  }
  
  /* Botón secundario reutilizable */
  .btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
  }
  
  /* Card reutilizable */
  .card {
    @apply bg-white rounded-lg shadow-md p-6;
  }
  
  /* Campo de input reutilizable */
  .input-field {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
  }
  
  /* Badge para etiquetas */
  .badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
  }
  
  .badge-primary {
    @apply bg-blue-100 text-blue-800;
  }
  
  .badge-success {
    @apply bg-green-100 text-green-800;
  }
  
  .badge-warning {
    @apply bg-yellow-100 text-yellow-800;
  }
  
  .badge-error {
    @apply bg-red-100 text-red-800;
  }
}

/* Utilidades personalizadas usando @layer utilities */
@layer utilities {
  /* Sombra de texto personalizada */
  .text-shadow {
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  /* Sombra de texto más grande */
  .text-shadow-lg {
    text-shadow: 0 4px 8px rgba(0,0,0,0.12);
  }
  
  /* Scrollbar personalizada */
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  /* Gradiente de texto */
  .text-gradient {
    @apply bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600;
  }
}
```

**Explicación detallada de cada sección:**

**`@import 'tailwindcss/base'`**: Importa los estilos base de Tailwind, incluyendo un reset CSS y estilos básicos para elementos HTML.

**`@import 'tailwindcss/components'`**: Importa las clases de componentes de Tailwind y cualquier componente personalizado que definas.

**`@import 'tailwindcss/utilities'`**: Importa todas las clases de utilidades de Tailwind (colores, espaciados, flexbox, etc.).

**`@layer components`**: Define componentes personalizados que puedes reutilizar en toda tu aplicación. Usa `@apply` para combinar clases de Tailwind.

**`@layer utilities`**: Define utilidades personalizadas que no están incluidas en Tailwind por defecto.

**Ventajas de usar `@layer`:**

**Organización**: Mantiene tu código organizado y separa componentes de utilidades.

**Especificidad**: Evita problemas de especificidad CSS al usar las capas correctas.

**Optimización**: Tailwind puede optimizar mejor el CSS cuando usa las capas correctas.

**Mantenibilidad**: Es más fácil encontrar y modificar componentes específicos.

## 🎨 **Clases Útiles por Categoría**

### 🏗️ **Layout y Espaciado**

Las clases de layout y espaciado son fundamentales para crear la estructura de tus páginas. Te permiten controlar el ancho, alto, padding, margin y posicionamiento de los elementos:

```html
<!-- Contenedores principales -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
  <!-- container: ancho máximo, mx-auto: centrado, px-4: padding horizontal -->
</div>

<div class="max-w-7xl mx-auto">
  <!-- max-w-7xl: ancho máximo específico, mx-auto: centrado -->
</div>

<div class="w-full md:w-1/2 lg:w-1/3">
  <!-- w-full: ancho completo en móvil, md:w-1/2: mitad en tablet, lg:w-1/3: un tercio en desktop -->
</div>

<!-- Espaciado (padding y margin) -->
<div class="p-4 m-2">
  <!-- p-4: padding en todos los lados (16px), m-2: margin en todos los lados (8px) -->
</div>

<div class="px-6 py-4">
  <!-- px-6: padding horizontal (24px), py-4: padding vertical (16px) -->
</div>

<div class="space-y-4">
  <!-- space-y-4: espacio vertical entre elementos hijos (16px) -->
</div>

<div class="space-x-4">
  <!-- space-x-4: espacio horizontal entre elementos inline (16px) -->
</div>

<!-- Flexbox para layouts flexibles -->
<div class="flex items-center justify-between">
  <!-- flex: display flex, items-center: centrado vertical, justify-between: espacio entre elementos -->
</div>

<div class="flex flex-col space-y-4">
  <!-- flex-col: dirección vertical, space-y-4: espacio entre elementos -->
</div>

<div class="flex-1">
  <!-- flex-1: flex-grow:1, el elemento toma el espacio disponible -->
</div>

<div class="flex-shrink-0">
  <!-- flex-shrink-0: el elemento no se encoge -->
</div>

<!-- Grid para layouts en cuadrícula -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <!-- grid: display grid, grid-cols-1: 1 columna en móvil, md:grid-cols-2: 2 columnas en tablet, lg:grid-cols-3: 3 columnas en desktop, gap-6: espacio entre elementos -->
</div>

<div class="grid grid-cols-12 gap-4">
  <!-- grid-cols-12: 12 columnas (sistema de 12 columnas), gap-4: espacio entre elementos -->
</div>
```

**Explicación detallada de las clases más importantes:**

**`container`**: Define un ancho máximo y centra el contenido. Útil para layouts principales.

**`mx-auto`**: Centra horizontalmente un elemento usando margin auto.

**`w-full`**: Hace que un elemento tome el ancho completo de su contenedor.

**`p-4`**: Agrega padding de 16px en todos los lados (padding = 4 * 0.25rem = 1rem = 16px).

**`space-y-4`**: Agrega espacio vertical entre elementos hijos. Muy útil para listas y formularios.

**`flex`**: Habilita flexbox para layouts flexibles y responsivos.

**`grid`**: Habilita CSS Grid para layouts en cuadrícula más complejos.

**`gap-6`**: Define el espacio entre elementos en grid o flexbox (24px).

### 📝 **Tipografía**

Las clases de tipografía te permiten controlar el tamaño, peso, color y alineación del texto. Son esenciales para crear jerarquías visuales y mejorar la legibilidad:

```html
<!-- Tamaños de texto (jerarquía visual) -->
<h1 class="text-4xl font-bold">Título Principal</h1>
<!-- text-4xl: tamaño muy grande (36px), font-bold: peso negrita para jerarquía -->

<h2 class="text-2xl font-semibold">Subtítulo</h2>
<!-- text-2xl: tamaño grande (24px), font-semibold: peso semi-negrita -->

<p class="text-base">Texto normal</p>
<!-- text-base: tamaño base (16px), peso normal -->

<span class="text-sm text-gray-600">Texto pequeño</span>
<!-- text-sm: tamaño pequeño (14px), text-gray-600: color gris para texto secundario -->

<!-- Pesos de fuente (font-weight) -->
<p class="font-light">Texto ligero (300)</p>
<!-- font-light: peso ligero, útil para textos decorativos -->

<p class="font-normal">Texto normal (400)</p>
<!-- font-normal: peso normal, estándar para texto de lectura -->

<p class="font-medium">Texto medio (500)</p>
<!-- font-medium: peso medio, útil para énfasis sutil -->

<p class="font-semibold">Texto semi-negrita (600)</p>
<!-- font-semibold: peso semi-negrita, útil para subtítulos -->

<p class="font-bold">Texto negrita (700)</p>
<!-- font-bold: peso negrita, útil para títulos y énfasis fuerte -->

<!-- Colores de texto (jerarquía y legibilidad) -->
<p class="text-gray-900">Texto oscuro (principal)</p>
<!-- text-gray-900: color muy oscuro para texto principal -->

<p class="text-gray-600">Texto medio (secundario)</p>
<!-- text-gray-600: color medio para texto secundario -->

<p class="text-gray-400">Texto claro (terciario)</p>
<!-- text-gray-400: color claro para texto de menor importancia -->

<p class="text-blue-600">Texto azul (enlaces)</p>
<!-- text-blue-600: color azul para enlaces y elementos interactivos -->

<p class="text-green-600">Texto verde (éxito)</p>
<!-- text-green-600: color verde para mensajes de éxito -->

<p class="text-red-600">Texto rojo (error)</p>
<!-- text-red-600: color rojo para mensajes de error -->

<!-- Alineación de texto -->
<p class="text-left">Izquierda (por defecto)</p>
<!-- text-left: alineación a la izquierda -->

<p class="text-center">Centro</p>
<!-- text-center: alineación centrada, útil para títulos -->

<p class="text-right">Derecha</p>
<!-- text-right: alineación a la derecha -->

<p class="text-justify">Justificado</p>
<!-- text-justify: texto justificado, útil para párrafos largos -->

<!-- Transformaciones de texto -->
<p class="uppercase">TEXTO EN MAYÚSCULAS</p>
<!-- uppercase: convierte a mayúsculas -->

<p class="lowercase">texto en minúsculas</p>
<!-- lowercase: convierte a minúsculas -->

<p class="capitalize">Primera Letra De Cada Palabra</p>
<!-- capitalize: primera letra de cada palabra en mayúscula -->

<!-- Decoración de texto -->
<p class="underline">Texto subrayado</p>
<!-- underline: agrega subrayado -->

<p class="line-through">Texto tachado</p>
<!-- line-through: agrega línea a través del texto -->

<p class="no-underline">Sin subrayado</p>
<!-- no-underline: remueve subrayado (útil para enlaces) -->
```

**Explicación detallada de las clases más importantes:**

**Tamaños de texto**: `text-xs` (12px), `text-sm` (14px), `text-base` (16px), `text-lg` (18px), `text-xl` (20px), `text-2xl` (24px), `text-3xl` (30px), `text-4xl` (36px), etc.

**Pesos de fuente**: `font-light` (300), `font-normal` (400), `font-medium` (500), `font-semibold` (600), `font-bold` (700), `font-extrabold` (800).

**Colores de texto**: Usa la escala de grises (`text-gray-100` a `text-gray-900`) para crear jerarquías visuales, y colores semánticos para estados específicos.

**Alineación**: `text-left`, `text-center`, `text-right`, `text-justify` para controlar la alineación del texto.

**Transformaciones**: `uppercase`, `lowercase`, `capitalize` para cambiar el caso del texto.

**Decoración**: `underline`, `line-through`, `no-underline` para agregar o remover decoraciones de texto.

### 🎨 **Colores y Fondos**

Las clases de colores y fondos te permiten crear la apariencia visual de tus elementos. Incluyen fondos, bordes, sombras y gradientes para crear profundidad y jerarquía visual:

```html
<!-- Colores de fondo (background-color) -->
<div class="bg-white">Fondo blanco</div>
<!-- bg-white: fondo blanco, útil para cards y contenido principal -->

<div class="bg-gray-100">Fondo gris claro</div>
<!-- bg-gray-100: fondo gris muy claro, útil para fondos de página -->

<div class="bg-blue-500">Fondo azul</div>
<!-- bg-blue-500: fondo azul, útil para botones y elementos destacados -->

<div class="bg-gradient-to-r from-blue-500 to-purple-600">Gradiente</div>
<!-- bg-gradient-to-r: gradiente de izquierda a derecha, from-blue-500: color inicial, to-purple-600: color final -->

<!-- Bordes (border) -->
<div class="border border-gray-300">Borde gris</div>
<!-- border: borde de 1px, border-gray-300: color gris claro -->

<div class="border-2 border-blue-500">Borde azul grueso</div>
<!-- border-2: borde de 2px, border-blue-500: color azul -->

<div class="border-l-4 border-green-500">Borde izquierdo verde</div>
<!-- border-l-4: borde izquierdo de 4px, border-green-500: color verde -->

<div class="border-t border-b border-gray-200">Borde superior e inferior</div>
<!-- border-t: borde superior, border-b: borde inferior -->

<!-- Sombras (box-shadow) -->
<div class="shadow-sm">Sombra pequeña</div>
<!-- shadow-sm: sombra muy sutil, útil para elevación mínima -->

<div class="shadow">Sombra normal</div>
<!-- shadow: sombra estándar, útil para cards y elementos elevados -->

<div class="shadow-lg">Sombra grande</div>
<!-- shadow-lg: sombra más pronunciada, útil para modales y elementos importantes -->

<div class="shadow-xl">Sombra extra grande</div>
<!-- shadow-xl: sombra muy pronunciada, útil para elementos que flotan -->

<div class="shadow-2xl">Sombra máxima</div>
<!-- shadow-2xl: sombra máxima, útil para elementos que deben destacar mucho -->

<!-- Bordes redondeados (border-radius) -->
<div class="rounded">Bordes redondeados</div>
<!-- rounded: bordes redondeados estándar (4px) -->

<div class="rounded-lg">Bordes redondeados grandes</div>
<!-- rounded-lg: bordes redondeados grandes (8px) -->

<div class="rounded-full">Bordes completamente redondeados</div>
<!-- rounded-full: bordes completamente redondeados, útil para avatares y botones circulares -->

<!-- Opacidad -->
<div class="bg-blue-500 bg-opacity-50">Fondo azul semi-transparente</div>
<!-- bg-opacity-50: 50% de opacidad -->

<div class="bg-black bg-opacity-25">Fondo negro semi-transparente</div>
<!-- bg-opacity-25: 25% de opacidad -->

<!-- Gradientes -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600">Gradiente horizontal</div>
<!-- bg-gradient-to-r: gradiente de izquierda a derecha -->

<div class="bg-gradient-to-b from-blue-500 to-purple-600">Gradiente vertical</div>
<!-- bg-gradient-to-b: gradiente de arriba a abajo -->

<div class="bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500">Gradiente diagonal</div>
<!-- bg-gradient-to-tr: gradiente diagonal, via-purple-500: color intermedio -->
```

**Explicación detallada de las clases más importantes:**

**Colores de fondo**: `bg-white`, `bg-gray-100`, `bg-blue-500`, etc. Usa la escala de colores de Tailwind (50-900) para diferentes intensidades.

**Bordes**: `border` (1px), `border-2` (2px), `border-4` (4px), etc. Puedes especificar el lado: `border-t`, `border-r`, `border-b`, `border-l`.

**Sombras**: `shadow-sm` (sutil), `shadow` (normal), `shadow-lg` (grande), `shadow-xl` (extra grande), `shadow-2xl` (máxima).

**Bordes redondeados**: `rounded` (4px), `rounded-lg` (8px), `rounded-xl` (12px), `rounded-2xl` (16px), `rounded-full` (50%).

**Gradientes**: `bg-gradient-to-r` (horizontal), `bg-gradient-to-b` (vertical), `bg-gradient-to-tr` (diagonal), etc.

**Opacidad**: `bg-opacity-25`, `bg-opacity-50`, `bg-opacity-75` para controlar la transparencia.

### 📝 **Formularios**

Las clases de formularios te permiten crear interfaces de usuario consistentes y accesibles. Incluyen estilos para inputs, selects, textareas, checkboxes y radio buttons:

```html
<!-- Input básico con estilos completos -->
<input type="text" 
       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
       placeholder="Ingresa tu nombre">
<!-- w-full: ancho completo, px-3 py-2: padding horizontal y vertical, border: borde, focus:outline-none: remueve outline por defecto, focus:ring-2: anillo de focus, focus:border-blue-500: borde azul en focus -->

<!-- Input con label estructurado -->
<div class="space-y-1">
  <label class="block text-sm font-medium text-gray-700">Email</label>
  <!-- block: display block, text-sm: tamaño pequeño, font-medium: peso medio, text-gray-700: color gris oscuro -->
  
  <input type="email" 
         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
         placeholder="ejemplo@email.com">
</div>

<!-- Select dropdown -->
<select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
  <option value="">Selecciona una opción</option>
  <option value="opcion1">Opción 1</option>
  <option value="opcion2">Opción 2</option>
  <option value="opcion3">Opción 3</option>
</select>

<!-- Textarea para texto largo -->
<textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
          rows="4" 
          placeholder="Escribe tu mensaje aquí..."></textarea>

<!-- Checkbox con label -->
<div class="flex items-center">
  <input type="checkbox" 
         class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
  <!-- h-4 w-4: tamaño 16x16px, text-blue-600: color azul cuando está marcado, focus:ring-blue-500: anillo azul en focus -->
  
  <label class="ml-2 block text-sm text-gray-900">Acepto los términos y condiciones</label>
  <!-- ml-2: margin izquierdo, block: display block, text-sm: tamaño pequeño -->
</div>

<!-- Radio button con label -->
<div class="flex items-center">
  <input type="radio" 
         name="opcion" 
         value="opcion1"
         class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
  
  <label class="ml-2 block text-sm text-gray-900">Opción 1</label>
</div>

<!-- Grupo de radio buttons -->
<div class="space-y-2">
  <div class="flex items-center">
    <input type="radio" name="categoria" value="tecnologia" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
    <label class="ml-2 block text-sm text-gray-900">Tecnología</label>
  </div>
  
  <div class="flex items-center">
    <input type="radio" name="categoria" value="diseno" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
    <label class="ml-2 block text-sm text-gray-900">Diseño</label>
  </div>
  
  <div class="flex items-center">
    <input type="radio" name="categoria" value="marketing" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
    <label class="ml-2 block text-sm text-gray-900">Marketing</label>
  </div>
</div>

<!-- Input con icono -->
<div class="relative">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
  </div>
  <!-- absolute inset-y-0 left-0: posiciona el icono, pl-3: padding izquierdo para el texto, pointer-events-none: no interfiere con el input -->
  
  <input type="text" 
         class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
         placeholder="Buscar...">
  <!-- pl-10: padding izquierdo para dejar espacio al icono -->
</div>

<!-- Input con validación visual -->
<div class="space-y-1">
  <label class="block text-sm font-medium text-gray-700">Contraseña</label>
  <input type="password" 
         class="w-full px-3 py-2 border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
         placeholder="Ingresa tu contraseña">
  <!-- border-red-300: borde rojo para indicar error, focus:ring-red-500: anillo rojo en focus -->
  
  <p class="text-sm text-red-600">La contraseña debe tener al menos 8 caracteres</p>
  <!-- text-red-600: texto rojo para mensaje de error -->
</div>
```

**Explicación detallada de las clases más importantes:**

**Estructura básica**: `w-full` para ancho completo, `px-3 py-2` para padding, `border border-gray-300` para borde.

**Estados de focus**: `focus:outline-none` remueve el outline por defecto, `focus:ring-2 focus:ring-blue-500` agrega un anillo de focus.

**Labels**: `block text-sm font-medium text-gray-700` para labels consistentes y legibles.

**Checkboxes y radios**: `h-4 w-4` para tamaño estándar, `text-blue-600` para el color cuando está marcado.

**Validación**: Usa `border-red-300` y `text-red-600` para indicar errores, `border-green-300` y `text-green-600` para éxito.

**Iconos**: Usa `absolute inset-y-0 left-0` para posicionar iconos dentro de inputs.

**Accesibilidad**: Siempre incluye `label` asociado con `for` o envuelve el input en el label.

### 🔘 **Botones**

Las clases de botones te permiten crear elementos interactivos consistentes y accesibles. Incluyen diferentes variantes, tamaños y estados para cubrir todas las necesidades de tu interfaz:

```html
<!-- Botón primario (acción principal) -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Primario
</button>
<!-- bg-blue-600: fondo azul, hover:bg-blue-700: azul más oscuro en hover, text-white: texto blanco, font-medium: peso medio, py-2 px-4: padding, rounded-lg: bordes redondeados, transition-colors duration-200: transición suave -->

<!-- Botón secundario (acción secundaria) -->
<button class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Secundario
</button>
<!-- bg-gray-600: fondo gris, hover:bg-gray-700: gris más oscuro en hover -->

<!-- Botón outline (acción alternativa) -->
<button class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Botón Outline
</button>
<!-- border border-blue-600: borde azul, text-blue-600: texto azul, hover:bg-blue-600 hover:text-white: fondo azul y texto blanco en hover -->

<!-- Botón de éxito (acción positiva) -->
<button class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Guardar Cambios
</button>
<!-- bg-green-600: fondo verde para acciones positivas -->

<!-- Botón de peligro (acción destructiva) -->
<button class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
  Eliminar
</button>
<!-- bg-red-600: fondo rojo para acciones destructivas -->

<!-- Botón pequeño (para espacios limitados) -->
<button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded transition-colors duration-200">
  Botón Pequeño
</button>
<!-- text-sm: texto pequeño, py-1 px-3: padding reducido, rounded: bordes menos redondeados -->

<!-- Botón grande (para acciones importantes) -->
<button class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-medium py-3 px-6 rounded-lg transition-colors duration-200">
  Botón Grande
</button>
<!-- text-lg: texto grande, py-3 px-6: padding aumentado -->

<!-- Botón con icono -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center space-x-2">
  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
  </svg>
  <span>Agregar Item</span>
</button>
<!-- flex items-center space-x-2: layout flexbox para alinear icono y texto -->

<!-- Botón deshabilitado -->
<button class="bg-gray-400 text-gray-600 font-medium py-2 px-4 rounded-lg cursor-not-allowed" disabled>
  Botón Deshabilitado
</button>
<!-- bg-gray-400: fondo gris claro, text-gray-600: texto gris, cursor-not-allowed: cursor de no permitido -->

<!-- Botón de carga -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center space-x-2" disabled>
  <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
  </svg>
  <span>Guardando...</span>
</button>
<!-- animate-spin: animación de rotación para el spinner -->

<!-- Grupo de botones -->
<div class="flex space-x-2">
  <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-l-lg transition-colors duration-200">
    Anterior
  </button>
  <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 border-l border-blue-500 transition-colors duration-200">
    Siguiente
  </button>
</div>
<!-- flex space-x-2: layout horizontal con espacio, rounded-l-lg: bordes redondeados solo a la izquierda, border-l: borde izquierdo para separar -->

<!-- Botón con estado de carga -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 relative">
  <span class="opacity-100">Enviar</span>
  <span class="absolute inset-0 flex items-center justify-center opacity-0">
    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
  </span>
</button>
<!-- relative: posicionamiento relativo, absolute inset-0: posicionamiento absoluto para el spinner -->
```

**Explicación detallada de las clases más importantes:**

**Colores semánticos**: `bg-blue-600` (primario), `bg-gray-600` (secundario), `bg-green-600` (éxito), `bg-red-600` (peligro).

**Estados de hover**: `hover:bg-blue-700` para cambiar el color en hover.

**Transiciones**: `transition-colors duration-200` para animaciones suaves.

**Tamaños**: `text-sm` (pequeño), `text-base` (normal), `text-lg` (grande).

**Padding**: `py-1 px-3` (pequeño), `py-2 px-4` (normal), `py-3 px-6` (grande).

**Bordes redondeados**: `rounded` (4px), `rounded-lg` (8px), `rounded-xl` (12px).

**Estados deshabilitados**: `bg-gray-400 text-gray-600 cursor-not-allowed` para botones no disponibles.

**Iconos**: Usa `flex items-center space-x-2` para alinear iconos con texto.

**Accesibilidad**: Siempre incluye texto descriptivo y usa `disabled` para botones no disponibles.

## 📱 **Responsive Design**

### 🎯 **Breakpoints**

El diseño responsive es fundamental en las aplicaciones modernas. Tailwind CSS proporciona un sistema de breakpoints intuitivo que te permite crear interfaces que se adaptan a diferentes tamaños de pantalla:

```html
<!-- Breakpoints de Tailwind CSS -->
<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
  <!-- w-full: ancho completo en móvil (< 768px) -->
  <!-- md:w-1/2: mitad del ancho en tablet (≥ 768px) -->
  <!-- lg:w-1/3: un tercio del ancho en desktop (≥ 1024px) -->
  <!-- xl:w-1/4: un cuarto del ancho en pantallas grandes (≥ 1280px) -->
</div>

<!-- Ocultar/Mostrar elementos según el tamaño de pantalla -->
<div class="hidden md:block">
  <!-- hidden: oculto por defecto, md:block: visible en tablet y desktop -->
  Visible solo en tablet y desktop
</div>

<div class="block md:hidden">
  <!-- block: visible por defecto, md:hidden: oculto en tablet y desktop -->
  Visible solo en móvil
</div>

<div class="hidden lg:block">
  <!-- hidden: oculto por defecto, lg:block: visible solo en desktop -->
  Visible solo en desktop
</div>

<!-- Texto que cambia de tamaño -->
<h1 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl">
  <!-- text-2xl: tamaño en móvil, md:text-3xl: más grande en tablet, etc. -->
  Título Responsive
</h1>

<!-- Espaciado que se adapta -->
<div class="p-4 md:p-6 lg:p-8">
  <!-- p-4: padding en móvil, md:p-6: más padding en tablet, lg:p-8: aún más en desktop -->
  Contenido con espaciado adaptativo
</div>

<!-- Grid que se adapta -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
  <!-- grid-cols-1: 1 columna en móvil, md:grid-cols-2: 2 columnas en tablet, etc. -->
  <div class="bg-blue-100 p-4">Item 1</div>
  <div class="bg-blue-100 p-4">Item 2</div>
  <div class="bg-blue-100 p-4">Item 3</div>
  <div class="bg-blue-100 p-4">Item 4</div>
</div>

<!-- Navegación responsive -->
<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo siempre visible -->
      <div class="flex items-center">
        <h1 class="text-xl font-bold text-gray-900">Logo</h1>
      </div>
      
      <!-- Menú desktop (oculto en móvil) -->
      <div class="hidden md:flex md:items-center md:space-x-6">
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Servicios</a>
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contacto</a>
      </div>
      
      <!-- Botón móvil (visible solo en móvil) -->
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

**Explicación detallada de los breakpoints:**

**`sm`**: 640px y superior (tablets pequeñas)
**`md`**: 768px y superior (tablets)
**`lg`**: 1024px y superior (desktops)
**`xl`**: 1280px y superior (pantallas grandes)
**`2xl`**: 1536px y superior (pantallas extra grandes)

**Estrategias responsive comunes:**

**Mobile-first**: Empieza con el diseño móvil y agrega complejidad para pantallas más grandes.

**Ocultar/Mostrar**: Usa `hidden` y `block` para mostrar contenido diferente según el tamaño.

**Grid adaptativo**: Cambia el número de columnas según el tamaño de pantalla.

**Texto adaptativo**: Ajusta el tamaño del texto para mejor legibilidad en cada dispositivo.

**Espaciado adaptativo**: Aumenta el padding y margin en pantallas más grandes.

### 🏗️ **Grid Responsive**

El sistema de grid de Tailwind CSS te permite crear layouts complejos que se adaptan automáticamente a diferentes tamaños de pantalla. Es especialmente útil para crear galerías, dashboards y listas de productos:

```html
<!-- Grid que se adapta automáticamente -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
  <!-- grid-cols-1: 1 columna en móvil (< 640px) -->
  <!-- sm:grid-cols-2: 2 columnas en tablets pequeñas (≥ 640px) -->
  <!-- lg:grid-cols-3: 3 columnas en desktop (≥ 1024px) -->
  <!-- xl:grid-cols-4: 4 columnas en pantallas grandes (≥ 1280px) -->
  <!-- gap-4: espacio de 16px entre elementos -->
  
  <div class="bg-white p-4 rounded-lg shadow">Item 1</div>
  <div class="bg-white p-4 rounded-lg shadow">Item 2</div>
  <div class="bg-white p-4 rounded-lg shadow">Item 3</div>
  <div class="bg-white p-4 rounded-lg shadow">Item 4</div>
</div>

<!-- Grid con columnas automáticas y espaciado variable -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
  <!-- gap-4: espacio pequeño en móvil, md:gap-6: más espacio en tablet, lg:gap-8: aún más en desktop -->
  
  <div class="bg-white p-6 rounded-lg shadow">Card 1</div>
  <div class="bg-white p-6 rounded-lg shadow">Card 2</div>
  <div class="bg-white p-6 rounded-lg shadow">Card 3</div>
</div>

<!-- Grid con elementos que ocupan múltiples columnas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <!-- Elemento que ocupa 2 columnas en desktop -->
  <div class="md:col-span-2 bg-blue-100 p-6 rounded-lg">
    <!-- md:col-span-2: ocupa 2 columnas en tablet y desktop -->
    Contenido destacado que ocupa más espacio
  </div>
  
  <!-- Elementos normales -->
  <div class="bg-white p-6 rounded-lg shadow">Sidebar 1</div>
  <div class="bg-white p-6 rounded-lg shadow">Sidebar 2</div>
</div>

<!-- Grid con elementos de altura automática -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-fr">
  <!-- auto-rows-fr: todas las filas tienen la misma altura -->
  
  <div class="bg-white p-6 rounded-lg shadow flex flex-col">
    <h3 class="text-lg font-semibold mb-2">Título 1</h3>
    <p class="text-gray-600 flex-grow">Descripción del contenido que puede ser más larga o más corta.</p>
    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Acción</button>
  </div>
  
  <div class="bg-white p-6 rounded-lg shadow flex flex-col">
    <h3 class="text-lg font-semibold mb-2">Título 2</h3>
    <p class="text-gray-600 flex-grow">Otra descripción.</p>
    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Acción</button>
  </div>
  
  <div class="bg-white p-6 rounded-lg shadow flex flex-col">
    <h3 class="text-lg font-semibold mb-2">Título 3</h3>
    <p class="text-gray-600 flex-grow">Una descripción más larga que las anteriores para demostrar cómo se ajusta el contenido.</p>
    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Acción</button>
  </div>
</div>

<!-- Grid con elementos que se reordenan -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
  <!-- Sidebar que aparece primero en móvil, pero a la izquierda en desktop -->
  <div class="lg:col-span-1 bg-gray-100 p-6 rounded-lg order-2 lg:order-1">
    <!-- order-2: segundo en móvil, lg:order-1: primero en desktop -->
    Sidebar
  </div>
  
  <!-- Contenido principal -->
  <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow order-1 lg:order-2">
    <!-- order-1: primero en móvil, lg:order-2: segundo en desktop -->
    Contenido principal
  </div>
</div>

<!-- Grid con elementos de diferentes tamaños -->
<div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-4">
  <!-- Elemento que ocupa 2 columnas en desktop -->
  <div class="md:col-span-2 lg:col-span-3 bg-yellow-100 p-4 rounded">
    <!-- md:col-span-2: 2 columnas en tablet, lg:col-span-3: 3 columnas en desktop -->
    Elemento pequeño
  </div>
  
  <!-- Elemento que ocupa 4 columnas en desktop -->
  <div class="md:col-span-4 lg:col-span-6 bg-green-100 p-4 rounded">
    <!-- md:col-span-4: 4 columnas en tablet, lg:col-span-6: 6 columnas en desktop -->
    Elemento mediano
  </div>
  
  <!-- Elemento que ocupa 3 columnas en desktop -->
  <div class="md:col-span-6 lg:col-span-3 bg-blue-100 p-4 rounded">
    <!-- md:col-span-6: 6 columnas en tablet, lg:col-span-3: 3 columnas en desktop -->
    Elemento grande
  </div>
</div>
```

**Explicación detallada de las clases de grid:**

**`grid`**: Habilita CSS Grid para el contenedor.

**`grid-cols-1`**: 1 columna por defecto (móvil).

**`sm:grid-cols-2`**: 2 columnas en pantallas pequeñas (≥ 640px).

**`lg:grid-cols-3`**: 3 columnas en desktop (≥ 1024px).

**`xl:grid-cols-4`**: 4 columnas en pantallas grandes (≥ 1280px).

**`gap-4`**: Espacio de 16px entre elementos del grid.

**`col-span-2`**: El elemento ocupa 2 columnas.

**`order-1`**: Controla el orden de los elementos.

**`auto-rows-fr`**: Todas las filas tienen la misma altura.

**Ventajas del grid responsive:**

**Adaptabilidad**: Se ajusta automáticamente a cualquier tamaño de pantalla.

**Flexibilidad**: Puedes controlar exactamente cuántas columnas quieres en cada breakpoint.

**Reordenamiento**: Puedes cambiar el orden de los elementos según el tamaño de pantalla.

**Espaciado adaptativo**: Puedes ajustar el gap según el tamaño de pantalla.

### 🧭 **Navegación Responsive**

La navegación responsive es crucial para la experiencia de usuario. Tailwind CSS te permite crear menús que se adaptan perfectamente a diferentes dispositivos, desde un menú hamburguesa en móvil hasta un menú completo en desktop:

```html
<!-- Navegación responsive completa -->
<nav class="bg-white shadow-lg">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo siempre visible -->
      <div class="flex-shrink-0 flex items-center">
        <h1 class="text-xl font-bold text-gray-900">MiServicio</h1>
        <!-- text-xl: tamaño grande para el logo, font-bold: peso negrita para destacar -->
      </div>
      
      <!-- Menú desktop (oculto en móvil) -->
      <div class="hidden md:flex md:items-center md:space-x-8">
        <!-- hidden: oculto por defecto, md:flex: visible en tablet y desktop -->
        <!-- md:items-center: centrado vertical, md:space-x-8: espacio horizontal entre elementos -->
        
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
          Inicio
        </a>
        <!-- text-gray-600: color gris, hover:text-gray-900: color más oscuro en hover, transition-colors: transición suave -->
        
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
          Servicios
        </a>
        
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
          Acerca de
        </a>
        
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
          Contacto
        </a>
        
        <!-- Botón de acción en desktop -->
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
          Registrarse
        </button>
      </div>
      
      <!-- Botón móvil (visible solo en móvil) -->
      <div class="md:hidden flex items-center">
        <!-- md:hidden: oculto en tablet y desktop, flex items-center: centrado vertical -->
        
        <button class="text-gray-600 hover:text-gray-900 p-2 rounded-md transition-colors duration-200">
          <!-- p-2: padding para área de toque más grande en móvil -->
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Menú móvil (oculto por defecto, se muestra con JavaScript) -->
    <div class="md:hidden hidden" id="mobile-menu">
      <!-- md:hidden: oculto en tablet y desktop, hidden: oculto por defecto -->
      
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <!-- px-2: padding horizontal, pt-2 pb-3: padding vertical, space-y-1: espacio vertical entre elementos -->
        
        <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
          Inicio
        </a>
        <!-- block: display block para ocupar toda la línea, text-base: tamaño base para móvil -->
        
        <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
          Servicios
        </a>
        
        <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
          Acerca de
        </a>
        
        <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
          Contacto
        </a>
        
        <!-- Botón de acción en móvil -->
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-base font-medium transition-colors duration-200">
          Registrarse
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Navegación con dropdown -->
<nav class="bg-white shadow-lg">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <h1 class="text-xl font-bold text-gray-900">MiServicio</h1>
      </div>
      
      <!-- Menú desktop con dropdown -->
      <div class="hidden md:flex md:items-center md:space-x-8">
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
          Inicio
        </a>
        
        <!-- Dropdown -->
        <div class="relative group">
          <!-- relative: posicionamiento relativo para el dropdown, group: grupo para hover -->
          
          <button class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
            Servicios
            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          
          <!-- Dropdown menu -->
          <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
            <!-- absolute left-0: posicionamiento absoluto, mt-2: margin top, w-48: ancho fijo, opacity-0 invisible: oculto por defecto, group-hover: visible en hover del grupo -->
            
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              Desarrollo Web
            </a>
            
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              Diseño UI/UX
            </a>
            
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              Consultoría IT
            </a>
          </div>
        </div>
        
        <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
          Contacto
        </a>
      </div>
      
      <!-- Botón móvil -->
      <div class="md:hidden">
        <button class="text-gray-600 hover:text-gray-900 p-2 rounded-md">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</nav>
```

**Explicación detallada de las clases de navegación:**

**Estructura básica**: `bg-white shadow-lg` para el fondo y sombra, `max-w-7xl mx-auto` para centrar y limitar el ancho.

**Layout**: `flex justify-between h-16` para distribuir logo y menú horizontalmente.

**Responsive**: `hidden md:flex` para ocultar en móvil y mostrar en desktop, `md:hidden` para lo contrario.

**Estados de hover**: `hover:text-gray-900` para cambiar color en hover, `transition-colors duration-200` para transición suave.

**Dropdown**: `relative group` para el contenedor, `absolute` para posicionar el menú, `group-hover:opacity-100` para mostrar en hover.

**Accesibilidad**: Usa `button` para elementos interactivos y `a` para enlaces reales.

**JavaScript necesario**: Para el menú móvil, necesitas JavaScript para alternar la clase `hidden` del menú móvil.

## 🧩 **Componentes Personalizados**

### 🃏 **Card Component**

Los componentes personalizados te permiten crear elementos reutilizables que mantienen consistencia en toda tu aplicación. El componente Card es uno de los más útiles para mostrar información estructurada:

```html
<!-- resources/views/components/ui/card.blade.php -->
@props(['title' => null, 'subtitle' => null, 'image' => null])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
  <!-- bg-white: fondo blanco, rounded-lg: bordes redondeados, shadow-md: sombra media, overflow-hidden: oculta contenido que se sale, hover:shadow-lg: sombra más grande en hover, transition-shadow: transición suave -->
  
  @if($image)
    <div class="aspect-w-16 aspect-h-9">
      <!-- aspect-w-16 aspect-h-9: mantiene proporción 16:9 para la imagen -->
      <img src="{{ $image }}" 
           alt="{{ $title }}" 
           class="w-full h-48 object-cover">
      <!-- w-full: ancho completo, h-48: altura fija, object-cover: mantiene proporción y cubre todo el espacio -->
    </div>
  @endif
  
  <div class="p-6">
    <!-- p-6: padding de 24px en todos los lados -->
    
    @if($title)
      <h3 class="text-lg font-semibold text-gray-900 mb-2">
        <!-- text-lg: tamaño grande, font-semibold: peso semi-negrita, text-gray-900: color muy oscuro, mb-2: margin bottom -->
        {{ $title }}
      </h3>
    @endif
    
    @if($subtitle)
      <p class="text-sm text-gray-600 mb-4">
        <!-- text-sm: tamaño pequeño, text-gray-600: color gris medio, mb-4: margin bottom -->
        {{ $subtitle }}
      </p>
    @endif
    
    <div class="content">
      <!-- Contenido principal del card -->
      {{ $slot }}
    </div>
    
    @if(isset($footer))
      <div class="mt-4 pt-4 border-t border-gray-200">
        <!-- mt-4: margin top, pt-4: padding top, border-t: borde superior, border-gray-200: color gris claro -->
        {{ $footer }}
      </div>
    @endif
  </div>
</div>
```

**Uso del componente Card:**

```html
<!-- Card básico -->
<x-ui.card>
  <p>Contenido del card</p>
</x-ui.card>

<!-- Card con título -->
<x-ui.card title="Título del Card">
  <p>Contenido del card con título</p>
</x-ui.card>

<!-- Card con imagen y título -->
<x-ui.card 
  title="Servicio de Desarrollo Web" 
  subtitle="Creamos sitios web modernos y responsivos"
  image="/images/web-development.jpg">
  
  <p class="text-gray-600 mb-4">
    Ofrecemos servicios completos de desarrollo web, desde el diseño hasta la implementación.
  </p>
  
  <x-slot name="footer">
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
      Ver Detalles
    </button>
  </x-slot>
</x-ui.card>

<!-- Card con diferentes variantes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <x-ui.card title="Plan Básico" subtitle="$99/mes">
    <ul class="space-y-2 text-sm text-gray-600">
      <li>✓ 5 páginas</li>
      <li>✓ Diseño responsive</li>
      <li>✓ Soporte básico</li>
    </ul>
    
    <x-slot name="footer">
      <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
        Seleccionar Plan
      </button>
    </x-slot>
  </x-ui.card>
  
  <x-ui.card title="Plan Profesional" subtitle="$199/mes">
    <ul class="space-y-2 text-sm text-gray-600">
      <li>✓ 15 páginas</li>
      <li>✓ Diseño personalizado</li>
      <li>✓ SEO optimizado</li>
      <li>✓ Soporte prioritario</li>
    </ul>
    
    <x-slot name="footer">
      <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">
        Seleccionar Plan
      </button>
    </x-slot>
  </x-ui.card>
  
  <x-ui.card title="Plan Enterprise" subtitle="$399/mes">
    <ul class="space-y-2 text-sm text-gray-600">
      <li>✓ Páginas ilimitadas</li>
      <li>✓ Diseño premium</li>
      <li>✓ Integración avanzada</li>
      <li>✓ Soporte 24/7</li>
    </ul>
    
    <x-slot name="footer">
      <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded">
        Seleccionar Plan
      </button>
    </x-slot>
  </x-ui.card>
</div>
```

**Explicación detallada del componente:**

**Props**: `title`, `subtitle`, `image` son propiedades opcionales que puedes pasar al componente.

**@props**: Define las propiedades que el componente puede recibir con valores por defecto.

**$slot**: Es donde va el contenido principal que pones entre las etiquetas del componente.

**$footer**: Es un slot nombrado que te permite agregar contenido en la parte inferior del card.

**Clases de Tailwind utilizadas:**

**Estructura**: `bg-white rounded-lg shadow-md` para el contenedor principal.

**Imagen**: `w-full h-48 object-cover` para imágenes que mantienen proporción.

**Tipografía**: `text-lg font-semibold` para títulos, `text-sm text-gray-600` para subtítulos.

**Espaciado**: `p-6` para padding interno, `mb-2`, `mb-4` para espaciado entre elementos.

**Interactividad**: `hover:shadow-lg transition-shadow duration-200` para efectos de hover.

**Ventajas de usar componentes:**

**Reutilización**: Puedes usar el mismo componente en múltiples lugares.

**Consistencia**: Todos los cards tendrán la misma apariencia y comportamiento.

**Mantenibilidad**: Cambios en un lugar se reflejan en toda la aplicación.

**Flexibilidad**: Los props te permiten personalizar cada instancia del componente.

### ⚠️ **Alert Component**

El componente Alert es esencial para mostrar mensajes importantes al usuario. Puede ser de diferentes tipos (éxito, error, advertencia, información) y puede ser descartable:

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
  <!-- p-4: padding de 16px, rounded-lg: bordes redondeados, border: borde, role="alert": accesibilidad -->
  
  <div class="flex items-start">
    <!-- flex: layout flexbox, items-start: alineación al inicio vertical -->
    
    <div class="flex-1">
      <!-- flex-1: toma el espacio disponible -->
      {{ $slot }}
    </div>
    
    @if($dismissible)
      <button 
        type="button" 
        class="ml-3 text-sm font-medium hover:opacity-75 transition-opacity duration-200"
        onclick="this.parentElement.parentElement.remove()"
        aria-label="Cerrar alerta"
      >
        <!-- ml-3: margin izquierdo, text-sm: texto pequeño, font-medium: peso medio, hover:opacity-75: opacidad en hover, transition-opacity: transición suave, aria-label: accesibilidad -->
        ×
      </button>
    @endif
  </div>
</div>
```

**Uso del componente Alert:**

```html
<!-- Alert de información (por defecto) -->
<x-ui.alert>
  Este es un mensaje informativo para el usuario.
</x-ui.alert>

<!-- Alert de éxito -->
<x-ui.alert type="success">
  <div class="flex items-center">
    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>
    Los cambios se han guardado exitosamente.
  </div>
</x-ui.alert>

<!-- Alert de error -->
<x-ui.alert type="error">
  <div class="flex items-center">
    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
    </svg>
    Ha ocurrido un error al procesar tu solicitud.
  </div>
</x-ui.alert>

<!-- Alert de advertencia -->
<x-ui.alert type="warning">
  <div class="flex items-center">
    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
    </svg>
    Tu sesión expirará en 5 minutos.
  </div>
</x-ui.alert>

<!-- Alert descartable -->
<x-ui.alert type="info" dismissible>
  <div class="flex items-center">
    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
    </svg>
    Este mensaje se puede cerrar haciendo clic en la X.
  </div>
</x-ui.alert>

<!-- Alert con contenido complejo -->
<x-ui.alert type="success">
  <div class="flex items-start">
    <svg class="h-5 w-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>
    <div>
      <h4 class="font-medium">Operación exitosa</h4>
      <p class="text-sm mt-1">
        El archivo se ha subido correctamente. Puedes verlo en tu biblioteca de documentos.
      </p>
      <div class="mt-3">
        <button class="text-sm font-medium hover:underline">
          Ver archivo
        </button>
      </div>
    </div>
  </div>
</x-ui.alert>
```

**Explicación detallada del componente:**

**Props**: `type` define el tipo de alerta (success, error, warning, info), `dismissible` permite cerrar la alerta.

**match()**: Usa la nueva sintaxis de PHP 8 para asignar clases según el tipo de alerta.

**Colores semánticos**: Cada tipo tiene su propio color para transmitir el significado visualmente.

**Accesibilidad**: `role="alert"` y `aria-label` mejoran la experiencia para usuarios con discapacidades.

**JavaScript**: El botón de cerrar usa JavaScript simple para remover el elemento del DOM.

**Clases de Tailwind utilizadas:**

**Colores de fondo**: `bg-green-50`, `bg-red-50`, `bg-yellow-50`, `bg-blue-50` para diferentes tipos.

**Bordes**: `border-green-200`, `border-red-200`, etc. para bordes que coinciden con el fondo.

**Texto**: `text-green-800`, `text-red-800`, etc. para texto que contrasta bien con el fondo.

**Layout**: `flex items-start` para alinear icono y texto correctamente.

**Interactividad**: `hover:opacity-75` para el botón de cerrar.

**Ventajas del componente Alert:**

**Consistencia**: Todos los mensajes tienen la misma estructura y apariencia.

**Semántica**: Los colores transmiten el significado del mensaje.

**Accesibilidad**: Incluye atributos ARIA para lectores de pantalla.

**Flexibilidad**: Puede contener cualquier tipo de contenido (texto, iconos, botones).

### 🪟 **Modal Component**

El componente Modal es perfecto para mostrar contenido importante sin perder el contexto de la página. Usa Alpine.js para la interactividad y incluye animaciones suaves:

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
  <!-- x-data: inicializa Alpine.js con variable show en false, x-show: controla visibilidad, @keydown.escape: cierra con ESC, fixed inset-0: cubre toda la pantalla, z-50: alto z-index para estar por encima de todo -->
  
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- flex items-end: centrado vertical al final en móvil, justify-center: centrado horizontal, min-h-screen: altura mínima completa, pt-4 px-4 pb-20: padding para evitar que el modal toque los bordes -->
    
    <!-- Overlay de fondo (fondo oscuro semi-transparente) -->
    <div 
      x-show="show"
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
      @click="show = false"
    ></div>
    <!-- fixed inset-0: cubre toda la pantalla, bg-gray-500 bg-opacity-75: fondo gris semi-transparente, @click: cierra al hacer clic en el overlay, x-transition: animaciones de entrada y salida -->

    <!-- Espaciador para centrado vertical en desktop -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <!-- hidden: oculto en móvil, sm:inline-block: visible en desktop, sm:align-middle: centrado vertical, aria-hidden: accesibilidad -->

    <!-- Contenido del modal -->
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
      <!-- inline-block: display inline-block, align-bottom: alineación al fondo en móvil, bg-white: fondo blanco, rounded-lg: bordes redondeados, shadow-xl: sombra grande, transform: habilita transformaciones, sm:max-w-lg: ancho máximo en desktop -->
      
      @if($title)
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <!-- px-4 pt-5 pb-4: padding en móvil, sm:p-6: padding en desktop -->
          
          <div class="sm:flex sm:items-start">
            <!-- sm:flex: flexbox en desktop, sm:items-start: alineación al inicio -->
            
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <!-- mt-3: margin top en móvil, text-center: texto centrado en móvil, sm:mt-0: sin margin en desktop, sm:text-left: texto izquierda en desktop -->
              
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                <!-- text-lg: tamaño grande, leading-6: line-height, font-medium: peso medio, text-gray-900: color muy oscuro -->
                {{ $title }}
              </h3>
              
              <div class="mt-2">
                <!-- mt-2: margin top pequeño -->
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
          <!-- bg-gray-50: fondo gris muy claro, px-4 py-3: padding en móvil, sm:px-6: padding horizontal en desktop, sm:flex: flexbox en desktop, sm:flex-row-reverse: orden inverso en desktop -->
          {{ $footer }}
        </div>
      @endif
    </div>
  </div>
</div>
```

**Uso del componente Modal:**

```html
<!-- Modal básico -->
<div x-data="{ showModal: false }">
  <!-- x-data: inicializa Alpine.js con variable showModal en false -->
  
  <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded">
    <!-- @click: evento de clic que cambia showModal a true -->
    Abrir Modal
  </button>
  
  <x-ui.modal x-show="showModal" @click.away="showModal = false">
    <!-- x-show: muestra el modal cuando showModal es true, @click.away: cierra al hacer clic fuera del modal -->
    
    <div class="p-6">
      <!-- p-6: padding de 24px -->
      <h3 class="text-lg font-medium text-gray-900 mb-4">Título del Modal</h3>
      <p class="text-gray-600">
        Este es el contenido del modal. Puede contener cualquier tipo de contenido.
      </p>
    </div>
    
    <x-slot name="footer">
      <!-- x-slot name="footer": slot nombrado para el pie del modal -->
      <button @click="showModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
        Cancelar
      </button>
      <button @click="showModal = false" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Confirmar
      </button>
    </x-slot>
  </x-ui.modal>
</div>

<!-- Modal con formulario -->
<div x-data="{ showFormModal: false }">
  <button @click="showFormModal = true" class="bg-green-600 text-white px-4 py-2 rounded">
    Crear Servicio
  </button>
  
  <x-ui.modal x-show="showFormModal" @click.away="showFormModal = false">
    <div class="p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nuevo Servicio</h3>
      
      <form class="space-y-4">
        <!-- space-y-4: espacio vertical de 16px entre elementos -->
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Servicio</label>
          <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
          <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
          <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </form>
    </div>
    
    <x-slot name="footer">
      <button @click="showFormModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
        Cancelar
      </button>
      <button @click="showFormModal = false" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        Crear Servicio
      </button>
    </x-slot>
  </x-ui.modal>
</div>

<!-- Modal de confirmación -->
<div x-data="{ showConfirmModal: false }">
  <button @click="showConfirmModal = true" class="bg-red-600 text-white px-4 py-2 rounded">
    Eliminar Servicio
  </button>
  
  <x-ui.modal x-show="showConfirmModal" @click.away="showConfirmModal = false">
    <div class="p-6">
      <div class="flex items-center">
        <!-- flex items-center: alinea icono y texto verticalmente -->
        <svg class="h-6 w-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.502 1.732 2.5z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
      </div>
      
      <p class="mt-2 text-gray-600">
        ¿Estás seguro de que quieres eliminar este servicio? Esta acción no se puede deshacer.
      </p>
    </div>
    
    <x-slot name="footer">
      <button @click="showConfirmModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
        Cancelar
      </button>
      <button @click="showConfirmModal = false" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
        Eliminar
      </button>
    </x-slot>
  </x-ui.modal>
</div>
```

**Explicación detallada del componente:**

**Alpine.js**: Usa `x-data`, `x-show`, `@click` para la interactividad sin JavaScript adicional. Alpine.js es una librería ligera que te permite agregar interactividad directamente en el HTML.

**Overlay**: Fondo semi-transparente que cubre toda la pantalla y cierra el modal al hacer clic. Esto crea el efecto de "foco" en el modal.

**Animaciones**: Transiciones suaves de entrada y salida usando `x-transition`. Las animaciones hacen que la experiencia sea más fluida y profesional.

**Responsive**: Se adapta a móvil (desde abajo) y desktop (centrado). En móvil aparece desde abajo, en desktop aparece centrado.

**Accesibilidad**: Se cierra con la tecla ESC y mantiene el foco dentro del modal. Esto es importante para usuarios que navegan con teclado.

**Clases de Tailwind utilizadas:**

**Posicionamiento**: `fixed inset-0` para cubrir toda la pantalla, `z-50` para estar por encima de todo.

**Overlay**: `bg-gray-500 bg-opacity-75` para fondo semi-transparente.

**Modal**: `bg-white rounded-lg shadow-xl` para el contenedor principal.

**Animaciones**: `transition-all duration-300` para transiciones suaves.

**Responsive**: `sm:max-w-lg` para ancho máximo en desktop.

**Ventajas del componente Modal:**

**Reutilizable**: Puede contener cualquier tipo de contenido (formularios, confirmaciones, información).

**Accesible**: Incluye navegación por teclado y lectores de pantalla.

**Responsive**: Se adapta perfectamente a todos los dispositivos.

**Interactivo**: Usa Alpine.js para funcionalidad sin JavaScript complejo.

## 🎨 **Animaciones y Transiciones**

Las animaciones y transiciones hacen que tu interfaz se sienta más viva y responsiva. Tailwind CSS proporciona clases para crear efectos suaves y profesionales que mejoran la experiencia del usuario.

### 🎭 **Transiciones Básicas**

Las transiciones son cambios suaves entre estados (normal, hover, focus, etc.). Hacen que los cambios se vean naturales y no abruptos:

```html
<!-- Transición de color (cambio suave de color) -->
<button class="bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200">
  <!-- bg-blue-600: color inicial, hover:bg-blue-700: color en hover, transition-colors: transición solo de colores, duration-200: duración de 200ms -->
  Botón con transición
</button>

<!-- Transición de escala (cambio suave de tamaño) -->
<div class="transform hover:scale-105 transition-transform duration-200">
  <!-- transform: habilita transformaciones, hover:scale-105: escala 105% en hover, transition-transform: transición de transformaciones -->
  <img src="image.jpg" alt="Imagen" class="w-full h-64 object-cover rounded-lg">
</div>

<!-- Transición de opacidad (cambio suave de transparencia) -->
<div class="opacity-75 hover:opacity-100 transition-opacity duration-200">
  <!-- opacity-75: 75% de opacidad inicial, hover:opacity-100: 100% en hover, transition-opacity: transición de opacidad -->
  Elemento con opacidad
</div>

<!-- Transición de sombra (cambio suave de sombra) -->
<div class="shadow-md hover:shadow-lg transition-shadow duration-300">
  <!-- shadow-md: sombra media inicial, hover:shadow-lg: sombra grande en hover, transition-shadow: transición de sombras -->
  Card con sombra animada
</div>

<!-- Transición de rotación -->
<div class="transform hover:rotate-12 transition-transform duration-300">
  <!-- hover:rotate-12: rota 12 grados en hover, transition-transform: transición de transformaciones -->
  <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
  </svg>
</div>
```

**Explicación detallada de las clases de transición:**

**`transition-colors`**: Transición suave solo para cambios de color (background, text, border).

**`transition-transform`**: Transición suave para transformaciones (scale, rotate, translate).

**`transition-opacity`**: Transición suave para cambios de opacidad.

**`transition-shadow`**: Transición suave para cambios de sombra.

**`transition-all`**: Transición suave para todos los cambios.

**Duración**: `duration-75` (75ms), `duration-100` (100ms), `duration-200` (200ms), `duration-300` (300ms), `duration-500` (500ms), `duration-700` (700ms), `duration-1000` (1000ms).

**Timing function**: `ease-linear`, `ease-in`, `ease-out`, `ease-in-out`.

### 🎬 **Animaciones Personalizadas**

Las animaciones personalizadas te permiten crear efectos más complejos y específicos para tu aplicación:

```css
/* En tu archivo resources/css/app.css */
@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

@keyframes slideIn {
  from { 
    transform: translateX(-100%); 
  }
  to { 
    transform: translateX(0); 
  }
}

@keyframes bounce {
  0%, 20%, 53%, 80%, 100% {
    transform: translate3d(0,0,0);
  }
  40%, 43% {
    transform: translate3d(0, -30px, 0);
  }
  70% {
    transform: translate3d(0, -15px, 0);
  }
  90% {
    transform: translate3d(0, -4px, 0);
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

/* Clases personalizadas para usar las animaciones */
.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

.animate-slide-in {
  animation: slideIn 0.3s ease-out;
}

.animate-bounce-slow {
  animation: bounce 2s infinite;
}

.animate-pulse-slow {
  animation: pulse 2s infinite;
}

/* Animación para loading */
.animate-spin-slow {
  animation: spin 3s linear infinite;
}

/* Animación para notificaciones */
.animate-slide-down {
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
```

```html
<!-- Uso de animaciones personalizadas -->
<div class="animate-fade-in">
  <!-- animate-fade-in: aparece con efecto fade desde abajo -->
  Contenido que aparece con fade
</div>

<div class="animate-slide-in">
  <!-- animate-slide-in: se desliza desde la izquierda -->
  Contenido que se desliza
</div>

<!-- Animación de loading -->
<div class="flex items-center justify-center">
  <div class="animate-spin-slow h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
  <!-- animate-spin-slow: rotación lenta, border-t-transparent: borde superior transparente para efecto de loading -->
  <span class="ml-2">Cargando...</span>
</div>

<!-- Animación de notificación -->
<div class="animate-slide-down bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
  <!-- animate-slide-down: se desliza desde arriba -->
  ¡Operación exitosa!
</div>

<!-- Animación de pulso para elementos importantes -->
<div class="animate-pulse-slow bg-yellow-100 p-4 rounded-lg">
  <!-- animate-pulse-slow: pulso lento para llamar la atención -->
  <p class="text-yellow-800 font-medium">¡Nueva funcionalidad disponible!</p>
</div>
```

**Explicación detallada de las animaciones:**

**`@keyframes`**: Define los pasos de la animación. `from` es el estado inicial, `to` es el estado final.

**`animation`**: Propiedad CSS que combina nombre, duración, timing function, delay, iteration count y direction.

**`ease-out`**: La animación comienza rápido y termina lento, creando un efecto natural.

**`infinite`**: La animación se repite indefinidamente.

**Casos de uso comunes:**

**`fadeIn`**: Para elementos que aparecen en la página (modales, notificaciones).

**`slideIn`**: Para elementos que entran desde un lado (menús, paneles).

**`bounce`**: Para llamar la atención (notificaciones importantes, botones de acción).

**`pulse`**: Para elementos que necesitan destacar (nuevas funcionalidades, alertas).

**`spin`**: Para indicar carga o procesamiento (loaders, spinners).

## 📱 **Ejemplos de Layouts**

Los layouts son la estructura base de tus páginas. Te muestro ejemplos prácticos de layouts comunes que puedes usar como punto de partida para tus proyectos.

### 🏠 **Dashboard Layout**

Un dashboard es el panel de control principal donde los usuarios ven estadísticas, métricas y acceden a las funciones principales:

```html
<div class="min-h-screen bg-gray-100">
  <!-- min-h-screen: altura mínima de toda la pantalla, bg-gray-100: fondo gris muy claro -->
  
  <!-- Header (encabezado fijo) -->
  <header class="bg-white shadow">
    <!-- bg-white: fondo blanco, shadow: sombra sutil para separar del contenido -->
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- max-w-7xl: ancho máximo, mx-auto: centrado, px-4: padding horizontal responsive -->
      
      <div class="flex justify-between h-16">
        <!-- flex justify-between: distribuye elementos horizontalmente, h-16: altura fija de 64px -->
        
        <div class="flex items-center">
          <!-- flex items-center: centrado vertical -->
          <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
          <!-- text-xl: tamaño grande, font-semibold: peso semi-negrita, text-gray-900: color muy oscuro -->
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- space-x-4: espacio horizontal de 16px entre elementos -->
          
          <button class="text-gray-600 hover:text-gray-900">
            <!-- text-gray-600: color gris, hover:text-gray-900: color más oscuro en hover -->
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12a1 1 0 00-2 0v12z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content (contenido principal) -->
  <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- max-w-7xl: ancho máximo, mx-auto: centrado, py-6: padding vertical, sm:px-6: padding horizontal responsive -->
    
    <div class="px-4 py-6 sm:px-0">
      <!-- px-4: padding horizontal en móvil, py-6: padding vertical, sm:px-0: sin padding horizontal en desktop -->
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- grid: display grid, grid-cols-1: 1 columna en móvil, md:grid-cols-2: 2 columnas en tablet, lg:grid-cols-3: 3 columnas en desktop, gap-6: espacio entre elementos -->
        
        <!-- Stats Cards (tarjetas de estadísticas) -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <!-- bg-white: fondo blanco, overflow-hidden: oculta contenido que se sale, shadow: sombra, rounded-lg: bordes redondeados -->
          
          <div class="p-5">
            <!-- p-5: padding de 20px en todos los lados -->
            
            <div class="flex items-center">
              <!-- flex items-center: layout flexbox con centrado vertical -->
              
              <div class="flex-shrink-0">
                <!-- flex-shrink-0: el icono no se encoge -->
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
              
              <div class="ml-5 w-0 flex-1">
                <!-- ml-5: margin izquierdo, w-0 flex-1: toma el espacio disponible -->
                
                <dl>
                  <!-- dl: definition list para datos estructurados -->
                  
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                  <!-- dt: definition term, text-sm: tamaño pequeño, font-medium: peso medio, text-gray-500: color gris, truncate: corta texto largo -->
                  
                  <dd class="text-lg font-medium text-gray-900">1,234</dd>
                  <!-- dd: definition description, text-lg: tamaño grande, font-medium: peso medio, text-gray-900: color muy oscuro -->
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Más tarjetas de estadísticas -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Ingresos</dt>
                  <dd class="text-lg font-medium text-gray-900">$45,678</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Ventas</dt>
                  <dd class="text-lg font-medium text-gray-900">892</dd>
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

**Explicación detallada del layout Dashboard:**

**Estructura**: `min-h-screen` asegura que el layout ocupe al menos toda la altura de la pantalla.

**Header**: Fijo en la parte superior con navegación y controles principales.

**Main Content**: Área principal con grid responsivo para mostrar estadísticas y métricas.

**Cards**: Cada tarjeta muestra una métrica importante con icono, título y valor.

**Responsive**: El grid se adapta de 1 columna en móvil a 3 columnas en desktop.

### 🎯 **Landing Page Layout**

Una landing page es la página de entrada que presenta tu servicio o producto a los visitantes:

```html
<div class="min-h-screen bg-white">
  <!-- min-h-screen: altura mínima completa, bg-white: fondo blanco -->
  
  <!-- Navigation (navegación) -->
  <nav class="bg-white shadow-sm">
    <!-- bg-white: fondo blanco, shadow-sm: sombra muy sutil -->
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- max-w-7xl: ancho máximo, mx-auto: centrado, px-4: padding responsive -->
      
      <div class="flex justify-between h-16">
        <!-- flex justify-between: distribuye logo y menú, h-16: altura fija -->
        
        <div class="flex items-center">
          <!-- flex items-center: centrado vertical -->
          <h1 class="text-2xl font-bold text-gray-900">MiServicio</h1>
          <!-- text-2xl: tamaño muy grande, font-bold: peso negrita, text-gray-900: color muy oscuro -->
        </div>
        
        <div class="hidden md:flex md:items-center md:space-x-6">
          <!-- hidden: oculto en móvil, md:flex: visible en desktop, md:items-center: centrado vertical, md:space-x-6: espacio horizontal -->
          
          <a href="#" class="text-gray-600 hover:text-gray-900">Inicio</a>
          <!-- text-gray-600: color gris, hover:text-gray-900: color más oscuro en hover -->
          
          <a href="#" class="text-gray-600 hover:text-gray-900">Servicios</a>
          <a href="#" class="text-gray-600 hover:text-gray-900">Contacto</a>
          
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <!-- bg-blue-600: fondo azul, hover:bg-blue-700: azul más oscuro en hover, text-white: texto blanco, px-4 py-2: padding, rounded-lg: bordes redondeados -->
            Registrarse
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section (sección principal) -->
  <div class="relative bg-gray-50 overflow-hidden">
    <!-- relative: posicionamiento relativo, bg-gray-50: fondo gris muy claro, overflow-hidden: oculta contenido que se sale -->
    
    <div class="max-w-7xl mx-auto">
      <!-- max-w-7xl: ancho máximo, mx-auto: centrado -->
      
      <div class="relative z-10 pb-8 bg-gray-50 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
        <!-- relative z-10: posicionamiento relativo con z-index alto, pb-8: padding bottom responsive, lg:max-w-2xl: ancho máximo en desktop -->
        
        <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
          <!-- mt-10: margin top responsive, mx-auto: centrado, max-w-7xl: ancho máximo, px-4: padding horizontal responsive -->
          
          <div class="sm:text-center lg:text-left">
            <!-- sm:text-center: texto centrado en tablet, lg:text-left: texto izquierda en desktop -->
            
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
              <!-- text-4xl: tamaño muy grande, tracking-tight: espaciado de letras ajustado, font-extrabold: peso extra negrita, sm:text-5xl: más grande en tablet, md:text-6xl: aún más grande en desktop -->
              
              <span class="block xl:inline">Servicios profesionales</span>
              <!-- block: display block, xl:inline: inline en pantallas extra grandes -->
              
              <span class="block text-blue-600 xl:inline">para tu negocio</span>
              <!-- block: display block, text-blue-600: color azul, xl:inline: inline en pantallas extra grandes -->
            </h1>
            
            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
              <!-- mt-3: margin top, text-base: tamaño base, text-gray-500: color gris, sm:mt-5: más margin en tablet, sm:text-lg: texto más grande en tablet, sm:max-w-xl: ancho máximo en tablet, sm:mx-auto: centrado en tablet, md:text-xl: texto más grande en desktop, lg:mx-0: sin centrado en desktop -->
              
              Ofrecemos soluciones integrales para hacer crecer tu empresa con la mejor tecnología y atención personalizada.
            </p>
            
            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
              <!-- mt-5: margin top, sm:mt-8: más margin en tablet, sm:flex: flexbox en tablet, sm:justify-center: centrado en tablet, lg:justify-start: alineado a la izquierda en desktop -->
              
              <div class="rounded-md shadow">
                <!-- rounded-md: bordes redondeados, shadow: sombra -->
                
                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                  <!-- w-full: ancho completo, flex items-center justify-center: centrado, px-8 py-3: padding, border border-transparent: borde transparente, text-base: tamaño base, font-medium: peso medio, rounded-md: bordes redondeados, text-white: texto blanco, bg-blue-600: fondo azul, hover:bg-blue-700: azul más oscuro en hover, md:py-4: más padding vertical en desktop, md:text-lg: texto más grande en desktop, md:px-10: más padding horizontal en desktop -->
                  
                  Comenzar
                </a>
              </div>
              
              <div class="mt-3 sm:mt-0 sm:ml-3">
                <!-- mt-3: margin top en móvil, sm:mt-0: sin margin en tablet, sm:ml-3: margin izquierdo en tablet -->
                
                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                  <!-- text-blue-700: texto azul, bg-blue-100: fondo azul muy claro, hover:bg-blue-200: azul claro en hover -->
                  
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

**Explicación detallada del layout Landing Page:**

**Navegación**: Simple y clara con logo, enlaces principales y botón de acción.

**Hero Section**: Sección principal que captura la atención con título impactante y llamadas a la acción.

**Responsive**: El diseño se adapta perfectamente de móvil a desktop.

**Jerarquía visual**: Títulos grandes, subtítulos claros y botones de acción prominentes.

**Espaciado**: Uso consistente de márgenes y padding para crear ritmo visual.

## 📝 **Comandos Útiles**

Estos comandos te ayudarán a trabajar eficientemente con Tailwind CSS en tu proyecto Laravel:

```bash
# Instalar Tailwind CSS y dependencias
npm install -D tailwindcss postcss autoprefixer
# -D: instala como dependencia de desarrollo (no se incluye en producción)
# tailwindcss: el framework CSS principal
# postcss: procesador CSS para optimizaciones
# autoprefixer: agrega prefijos de navegador automáticamente

# Inicializar archivos de configuración
npx tailwindcss init -p
# npx: ejecuta el paquete sin instalarlo globalmente
# init: crea tailwind.config.js
# -p: también crea postcss.config.js

# Compilar assets en modo desarrollo (con hot reload)
npm run dev
# Ejecuta Vite en modo desarrollo con recarga automática
# Útil durante el desarrollo para ver cambios inmediatamente

# Compilar assets para producción (optimizado)
npm run build
# Crea archivos CSS y JS optimizados para producción
# Incluye minificación y purging de clases no utilizadas

# Instalar plugins adicionales de Tailwind
npm install -D @tailwindcss/forms @tailwindcss/typography @tailwindcss/aspect-ratio
# @tailwindcss/forms: estilos mejorados para formularios
# @tailwindcss/typography: estilos para contenido de texto rico
# @tailwindcss/aspect-ratio: control de proporciones de imágenes

# Verificar configuración de Tailwind
npx tailwindcss --help
# Muestra todas las opciones disponibles del CLI de Tailwind

# Generar CSS purgado manualmente
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
# -i: archivo de entrada, -o: archivo de salida, --watch: observa cambios

# Analizar uso de clases en tu proyecto
npx tailwindcss --content "./resources/**/*.blade.php" --output "./public/css/analysis.css"
# Genera un archivo CSS con todas las clases encontradas para análisis
```

**Explicación detallada de cada comando:**

**`npm install -D`**: Instala dependencias solo para desarrollo. Esto reduce el tamaño del proyecto en producción.

**`npx tailwindcss init -p`**: Crea los archivos de configuración necesarios. El `-p` crea tanto `tailwind.config.js` como `postcss.config.js`.

**`npm run dev`**: Ejecuta Vite en modo desarrollo con recarga automática. Perfecto para desarrollo.

**`npm run build`**: Crea archivos optimizados para producción. Incluye minificación y eliminación de clases no utilizadas.

**Plugins adicionales**: Agregan funcionalidades específicas como estilos de formularios mejorados y control de proporciones.

## 🎯 **Resumen y Mejores Prácticas**

Tailwind CSS en Laravel proporciona una experiencia de desarrollo excepcional con las siguientes características:

### ✅ **Ventajas Principales**

**Framework utility-first**: Desarrolla más rápido combinando clases predefinidas en lugar de escribir CSS personalizado.

**Configuración personalizable**: Adapta colores, fuentes, espaciados y más a tu marca en `tailwind.config.js`.

**Componentes reutilizables**: Crea componentes Blade que mantienen consistencia en toda tu aplicación.

**Diseño responsive nativo**: Las clases responsive están integradas desde el inicio (`sm:`, `md:`, `lg:`, `xl:`).

**Animaciones y transiciones**: Efectos suaves y profesionales que mejoran la experiencia del usuario.

**Integración perfecta con Vite**: Optimización automática y hot reload durante el desarrollo.

### 🚀 **Mejores Prácticas**

**1. Organización de clases:**
```html
<!-- Orden recomendado: Layout > Spacing > Sizing > Typography > Backgrounds > Borders > Effects > Interactivity -->
<div class="flex items-center justify-between p-4 w-full text-lg bg-white border border-gray-200 shadow-md hover:shadow-lg">
```

**2. Componentes personalizados:**
```css
/* Usa @layer components para crear clases reutilizables */
@layer components {
  .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
  }
}
```

**3. Responsive design:**
```html
<!-- Siempre empieza con móvil y agrega complejidad para pantallas más grandes -->
<div class="w-full md:w-1/2 lg:w-1/3">
```

**4. Accesibilidad:**
```html
<!-- Incluye estados de focus y atributos ARIA -->
<button class="focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Cerrar modal">
```

**5. Optimización:**
```javascript
// Configura content en tailwind.config.js para purging
content: [
  "./resources/**/*.blade.php",
  "./resources/**/*.js",
  "./app/View/Components/**/*.php",
]
```

### 📚 **Recursos Adicionales**

**Documentación oficial:**
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Laravel Documentation](https://laravel.com/docs)
- [Vite Documentation](https://vitejs.dev/guide/)

**Herramientas útiles:**
- [Tailwind CSS IntelliSense](https://marketplace.visualstudio.com/items?itemName=bradlc.vscode-tailwindcss) - Autocompletado en VS Code
- [Tailwind CSS Play](https://play.tailwindcss.com/) - Editor online para experimentar
- [Tailwind UI](https://tailwindui.com/) - Componentes premium (de pago)

**Comunidad:**
- [Tailwind CSS Discord](https://discord.gg/7NF8GNe)
- [Laravel Discord](https://discord.gg/laravel)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/tailwindcss)

### 🎨 **Próximos Pasos**

**Implementación práctica:**
1. Configura Tailwind CSS en tu proyecto Laravel
2. Crea componentes personalizados para tu aplicación
3. Implementa layouts responsive
4. Agrega animaciones y transiciones
5. Optimiza para producción

**Exploración avanzada:**
- Dark mode con `dark:` classes
- Custom animations con `@keyframes`
- Advanced responsive patterns
- Performance optimization techniques

**Integración con Laravel:**
- Blade components con Tailwind
- Form styling con `@tailwindcss/forms`
- Typography with `@tailwindcss/typography`
- Alpine.js for interactivity

¡Con Tailwind CSS y Laravel tienes una combinación poderosa para crear interfaces modernas, responsivas y mantenibles! 