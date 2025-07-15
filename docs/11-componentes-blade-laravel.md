# 🧩 Componentes Blade en Laravel 12

## 📋 **¿Qué son los Componentes Blade?**

Los componentes Blade en Laravel son elementos reutilizables que encapsulan HTML, CSS y lógica de presentación en un solo lugar. Son como "piezas de LEGO" que puedes usar en múltiples páginas para mantener un diseño consistente y evitar duplicar código. Los componentes te permiten crear interfaces modulares, mantenibles y escalables.

**¿Por qué usar Componentes Blade?**
- **Reutilización**: Crea un componente una vez y úsalo en múltiples páginas
- **Mantenibilidad**: Cambios en un lugar se reflejan en toda la aplicación
- **Consistencia**: Mantén un diseño uniforme en toda tu aplicación
- **Organización**: Código más limpio y fácil de entender
- **Productividad**: Acelera el desarrollo al reutilizar elementos comunes
- **Testing**: Más fácil de probar componentes individuales

### 🎯 **Características Principales**

**Encapsulación**: Cada componente contiene su propio HTML, CSS y lógica. Es como tener una "caja negra" que recibe datos y produce HTML.

**Props**: Los componentes pueden recibir parámetros (props) que los hacen flexibles y reutilizables. Son como "configuraciones" que puedes cambiar cada vez que usas el componente.

**Slots**: Permiten pasar contenido HTML personalizado dentro del componente. Es como tener "espacios vacíos" que puedes llenar con contenido específico.

**Composición**: Puedes combinar componentes para crear interfaces complejas. Es como construir con bloques de construcción.

**Responsive**: Los componentes pueden ser responsive y adaptarse a diferentes tamaños de pantalla.

**Accesibilidad**: Puedes incluir atributos de accesibilidad directamente en los componentes.

## 🏗️ **Estructura de Componentes**

### 📁 **Organización de Carpetas**

Los componentes Blade se organizan en la carpeta `resources/views/components/` siguiendo una estructura lógica que facilita encontrar y mantener los componentes. Esta organización te ayuda a mantener tu código organizado y escalable:

```
resources/views/components/
├── ui/                           # Componentes de interfaz de usuario básicos
│   ├── button.blade.php          # Botones reutilizables con diferentes estilos
│   ├── input.blade.php           # Campos de entrada con validación
│   ├── card.blade.php            # Tarjetas/contenedores para contenido
│   ├── modal.blade.php           # Ventanas modales para diálogos
│   ├── badge.blade.php           # Etiquetas y badges
│   └── avatar.blade.php          # Avatares de usuario
├── layout/                       # Componentes de estructura de página
│   ├── header.blade.php          # Encabezado de página con navegación
│   ├── footer.blade.php          # Pie de página con enlaces
│   ├── sidebar.blade.php         # Barra lateral de navegación
│   ├── navigation.blade.php      # Navegación principal
│   └── breadcrumb.blade.php      # Migas de pan para navegación
├── forms/                        # Componentes específicos de formularios
│   ├── input-group.blade.php     # Grupos de campos de entrada
│   ├── select.blade.php          # Listas desplegables
│   ├── textarea.blade.php        # Áreas de texto multilínea
│   ├── checkbox.blade.php        # Casillas de verificación
│   ├── radio.blade.php           # Botones de opción
│   └── file-input.blade.php      # Campos de archivo
├── alerts/                       # Componentes de notificaciones
│   ├── success.blade.php         # Alertas de éxito (verde)
│   ├── error.blade.php           # Alertas de error (rojo)
│   ├── warning.blade.php         # Alertas de advertencia (amarillo)
│   └── info.blade.php            # Alertas informativas (azul)
├── tables/                       # Componentes de tablas
│   ├── table.blade.php           # Tabla básica
│   ├── table-row.blade.php       # Fila de tabla
│   └── table-cell.blade.php      # Celda de tabla
└── charts/                       # Componentes de gráficos
    ├── bar-chart.blade.php       # Gráfico de barras
    └── line-chart.blade.php      # Gráfico de líneas
```

**Explicación detallada de cada carpeta:**

**ui/**: Contiene componentes básicos de interfaz que se usan en toda la aplicación. Son como "átomos" que forman la base de tu diseño. Incluyen botones, inputs, cards, modales, etc.

**layout/**: Componentes que definen la estructura de las páginas. Son como el "esqueleto" de tu aplicación. Incluyen headers, footers, sidebars, navegación, etc.

**forms/**: Componentes específicos para formularios. Son especializados para la entrada de datos. Incluyen campos de entrada, selects, textareas, checkboxes, etc.

**alerts/**: Componentes para mostrar mensajes al usuario. Son importantes para la experiencia del usuario. Incluyen diferentes tipos de alertas según el tipo de mensaje.

**tables/**: Componentes para mostrar datos en formato tabular. Útiles para listas de datos, reportes, etc.

**charts/**: Componentes para visualización de datos. Útiles para dashboards y reportes.

**Convención de nombres**: Usa kebab-case para los nombres de archivos (`button.blade.php`, `input-group.blade.php`). Esto hace que los nombres sean legibles y consistentes.

**Subcarpetas**: Organiza componentes relacionados en subcarpetas para mantener el código organizado. Por ejemplo, todos los componentes de formularios van en `forms/`.

## 🚀 **Crear Componentes**

### 📋 **Comandos Artisan**

Laravel proporciona comandos Artisan para crear componentes de manera rápida y consistente. Estos comandos generan automáticamente la estructura necesaria para tu componente:

```bash
# Crear componente básico - Genera solo la clase PHP
php artisan make:component Button

# Crear componente en subcarpeta - Organiza componentes por funcionalidad
php artisan make:component ui/Button

# Crear componente con vista - Incluye archivo Blade para el HTML
php artisan make:component Alert --view

# Crear componente de formulario con vista - Especializado para formularios
php artisan make:component forms/InputGroup --view

# Crear componente anónimo - Solo archivo Blade (sin clase PHP)
# Crear manualmente: resources/views/components/button.blade.php
```

**Explicación detallada de cada comando:**

**make:component Button**: Crea un componente clásico con clase PHP. Útil para componentes con lógica compleja.

**make:component ui/Button**: Crea el componente en la subcarpeta `ui/`. Ayuda a organizar componentes por categoría.

**make:component Alert --view**: Crea un componente con clase PHP y archivo Blade. Útil para componentes que necesitan lógica y HTML personalizado.

**make:component forms/InputGroup --view**: Crea un componente especializado para formularios. Se organiza en la carpeta `forms/`.

**Componente anónimo**: Se crea manualmente como archivo Blade. Útil para componentes simples sin lógica compleja.

### 🎯 **Estructura de un Componente Clásico**

Un componente clásico tiene una clase PHP que maneja la lógica y una vista Blade. Es como tener un "controlador" específico para tu componente que maneja la lógica de presentación:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Constructor del componente.
     * Define las propiedades (props) que puede recibir el componente.
     */
    public function __construct(
        public string $type = 'button',      // Tipo de botón (button, submit, reset)
        public string $variant = 'primary',   // Variante de estilo (primary, secondary, danger)
        public bool $disabled = false,        // Si el botón está deshabilitado
        public ?string $size = null          // Tamaño del botón (sm, md, lg)
    ) {}

    /**
     * Método que define qué vista usar para renderizar el componente.
     * Laravel automáticamente busca la vista en resources/views/components/button.blade.php
     */
    public function render()
    {
        return view('components.button'); // Devuelve la vista del componente
    }

    /**
     * Método para generar clases CSS dinámicamente.
     * Útil para combinar clases según las props del componente.
     */
    public function getClasses(): string
    {
        $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
        
        $variantClasses = match($this->variant) {
            'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
            'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
            'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
            'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
            default => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500'
        };

        $sizeClasses = match($this->size) {
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-6 py-3 text-lg',
            default => 'px-4 py-2 text-base'
        };

        return $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;
    }
}
```

**Explicación detallada de cada parte:**

**namespace App\View\Components**: Todos los componentes van en este namespace. Laravel automáticamente registra componentes en este namespace.

**extends Component**: Hereda de la clase base Component de Laravel. Proporciona funcionalidad básica como props, slots, etc.

**Constructor con props públicas**: Las propiedades públicas se pueden usar directamente en la vista Blade. Laravel automáticamente las hace disponibles.

**render()**: Método que define qué vista usar. Laravel busca automáticamente en `resources/views/components/button.blade.php`.

**getClasses()**: Método personalizado para generar clases CSS dinámicamente. Útil para combinar clases según las props.

**Props con valores por defecto**: Cada prop tiene un valor por defecto, haciendo el componente flexible y fácil de usar.

### 🎨 **Vista del Componente**

La vista Blade contiene el HTML y la lógica de presentación del componente. Es donde defines cómo se ve tu componente y cómo responde a las props:

```php
{{-- resources/views/components/button.blade.php --}}
<button 
    type="{{ $type }}"                           {{-- Tipo del botón (button, submit, reset) --}}
    {{ $attributes->merge([                      {{-- Combina atributos HTML adicionales --}}
        'class' => $this->getClasses()          {{-- Clases CSS generadas dinámicamente --}}
    ]) }}
    @if($disabled) disabled @endif               {{-- Deshabilita el botón si es necesario --}}
>
    {{ $slot }}                                  {{-- Contenido que va dentro del botón --}}
</button>
```

**Explicación detallada de cada elemento:**

**`{{ $type }}`**: Usa el valor de la prop `type` del componente. Puede ser 'button', 'submit', o 'reset'.

**`$attributes->merge()`**: Combina atributos HTML adicionales que se pasan al componente. Permite pasar `class`, `id`, `data-*`, etc.

**`$this->getClasses()`**: Llama al método de la clase PHP para generar clases CSS dinámicamente según las props.

**`@if($disabled) disabled @endif`**: Condicional que agrega el atributo `disabled` si la prop `disabled` es `true`.

**`{{ $slot }}`**: Variable especial que contiene el contenido que se pasa entre las etiquetas del componente. Es como un "espacio vacío" que se llena con contenido personalizado.

**Flujo completo del componente:**

1. **Usuario usa el componente**: `<x-button variant="primary" size="lg">Guardar</x-button>`
2. **Laravel instancia la clase**: Crea una instancia de `Button` con las props
3. **Constructor recibe props**: `$variant = 'primary'`, `$size = 'lg'`
4. **Método render()**: Define qué vista usar (`components.button`)
5. **Vista se renderiza**: Usa las props para generar HTML dinámico
6. **HTML final**: `<button class="...">Guardar</button>`

**Ventajas de esta estructura:**

**Separación de responsabilidades**: La lógica va en la clase PHP, el HTML en la vista Blade.

**Reutilización**: Un componente se puede usar en múltiples lugares con diferentes props.

**Mantenibilidad**: Cambios en un lugar se reflejan en toda la aplicación.

**Flexibilidad**: Los atributos HTML adicionales se pueden pasar fácilmente.

## 🔧 **Props y Slots**

### 📝 **Props Básicos**

Las props son parámetros que puedes pasar a tus componentes para hacerlos flexibles y reutilizables. Son como "configuraciones" que cambian el comportamiento del componente:

```php
{{-- Componente con props estáticas --}}
<x-button type="submit" variant="success" size="lg">
    Guardar Cambios
</x-button>

{{-- Componente con props dinámicas (usando :) --}}
<x-input 
    name="email" 
    type="email" 
    placeholder="Ingresa tu email"
    :required="true"
    :value="$user->email"
/>

{{-- Componente con props condicionales --}}
<x-alert 
    type="error" 
    :dismissible="$showDismissButton"
    :title="$errorTitle"
>
    {{ $errorMessage }}
</x-alert>
```

**Explicación detallada de las props:**

**Props estáticas**: Se pasan como strings simples. Laravel las trata como texto literal.

**Props dinámicas**: Se pasan con `:` al inicio. Laravel evalúa la expresión PHP y pasa el resultado.

**Props booleanas**: Se pueden pasar como `:required="true"` o simplemente `required` (equivalente a `true`).

**Props con variables**: Puedes pasar variables PHP usando `:`. Laravel evalúa la variable y pasa su valor.

**Diferencia entre props estáticas y dinámicas:**

```php
{{-- Estática: pasa el string literal "true" --}}
<x-input required="true" />

{{-- Dinámica: pasa el valor booleano true --}}
<x-input :required="true" />

{{-- Dinámica con variable: pasa el valor de $isRequired --}}
<x-input :required="$isRequired" />
```

### 🎯 **Slots Nombrados**

Los slots nombrados te permiten pasar contenido HTML a diferentes partes del componente. Son como "espacios vacíos" que puedes llenar con contenido específico:

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
{{-- Uso del componente con slots nombrados --}}
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

**Explicación detallada de los slots:**

**`{{ $slot }}`**: Slot por defecto que contiene el contenido principal del componente. No necesita nombre.

**`{{ $header }}`**: Slot nombrado que contiene el encabezado del card. Se define con `<x-slot name="header">`.

**`{{ $footer }}`**: Slot nombrado que contiene el pie del card. Se define con `<x-slot name="footer">`.

**`@if(isset($header))`**: Verifica si se proporcionó contenido para el slot antes de mostrarlo.

**`<x-slot name="header">`**: Define contenido para el slot nombrado "header". El contenido va entre las etiquetas.

**Ventajas de los slots nombrados:**

**Flexibilidad**: Puedes pasar contenido HTML complejo a diferentes partes del componente.

**Reutilización**: Un componente puede tener múltiples "espacios" para contenido personalizado.

**Organización**: El contenido se organiza claramente en secciones (header, content, footer).

**Opcionalidad**: Los slots nombrados son opcionales. El componente funciona sin ellos.

### 🔧 **Props con Valores por Defecto**

Los valores por defecto hacen que tus componentes sean más fáciles de usar. Si no se proporciona una prop, el componente usa el valor por defecto:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Constructor con valores por defecto para todas las props.
     * Hace que el componente sea flexible y fácil de usar.
     */
    public function __construct(
        public string $type = 'info',        // Tipo de alerta (info, success, error, warning)
        public bool $dismissible = false,    // Si se puede cerrar la alerta
        public ?string $title = null         // Título opcional de la alerta
    ) {}

    /**
     * Método para generar clases CSS según el tipo de alerta.
     * Usa match() para un código más limpio y legible.
     */
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

    /**
     * Método que define qué vista usar para el componente.
     */
    public function render()
    {
        return view('components.alert');
    }
}
```

**Explicación detallada de los valores por defecto:**

**`public string $type = 'info'`**: Si no se especifica el tipo, usa 'info' por defecto. Hace que el componente sea más fácil de usar.

**`public bool $dismissible = false`**: Por defecto, las alertas no se pueden cerrar. Solo se pueden cerrar si se especifica explícitamente.

**`public ?string $title = null`**: El título es opcional. Si no se proporciona, es `null` y no se muestra.

**`match()`**: Estructura moderna de PHP para manejar múltiples condiciones. Más limpia que `switch` o `if/else`.

**Uso del componente con diferentes configuraciones:**

```php
{{-- Uso básico (usa valores por defecto) --}}
<x-alert>
    Mensaje informativo
</x-alert>

{{-- Uso con tipo específico --}}
<x-alert type="success">
    Operación completada exitosamente
</x-alert>

{{-- Uso con título y dismissible --}}
<x-alert type="error" title="Error de Validación" :dismissible="true">
    Hay errores en el formulario
</x-alert>
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
                aria-label="Cerrar alerta"
            >
                ×
            </button>
        @endif
    </div>
</div>
```

**Explicación detallada de la vista del componente:**

**`{{ $getClasses() }}`**: Llama al método de la clase PHP para obtener las clases CSS según el tipo de alerta.

**`role="alert"`**: Atributo de accesibilidad que indica a los lectores de pantalla que este es un mensaje de alerta.

**`@if($title)`**: Solo muestra el título si se proporcionó uno. Evita mostrar elementos vacíos.

**`{{ $slot }}`**: Contiene el mensaje principal de la alerta. Es el contenido que se pasa entre las etiquetas del componente.

**`@if($dismissible)`**: Solo muestra el botón de cerrar si la alerta es dismissible.

**`onclick="this.parentElement.parentElement.remove()"`**: JavaScript simple que elimina la alerta del DOM cuando se hace clic en el botón.

**`aria-label="Cerrar alerta"`**: Atributo de accesibilidad que describe la función del botón para lectores de pantalla.

**Ventajas de esta implementación:**

**Accesibilidad**: Incluye atributos ARIA para usuarios con discapacidades.

**Flexibilidad**: El componente funciona con o sin título y con o sin botón de cerrar.

**Simplicidad**: JavaScript mínimo y directo para la funcionalidad de cerrar.

**Consistencia**: Todas las alertas tienen la misma estructura pero diferentes estilos.

## 🎯 **Componentes Anónimos**

Los componentes anónimos son más simples que los componentes clásicos. No tienen una clase PHP separada, toda la lógica va en el archivo Blade. Son ideales para componentes simples sin lógica compleja.

### 📝 **Componente Anónimo Básico**

Un componente anónimo se define completamente en el archivo Blade usando la directiva `@props`:

```php
{{-- resources/views/components/ui/button.blade.php --}}
@props([
    'type' => 'button',      // Tipo de botón (button, submit, reset)
    'variant' => 'primary',   // Variante de estilo (primary, secondary, success, danger)
    'size' => 'md',          // Tamaño del botón (sm, md, lg)
    'disabled' => false       // Si el botón está deshabilitado
])

@php
// Generar clases CSS según la variante
$classes = match($variant) {
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    default => 'bg-gray-600 hover:bg-gray-700 text-white'
};

// Generar clases CSS según el tamaño
$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
    default => 'px-4 py-2 text-base'
};

// Clases base que siempre se aplican
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

**Explicación detallada de cada parte:**

**`@props([...])`**: Define las props que puede recibir el componente con sus valores por defecto. Es como el constructor de un componente clásico.

**`@php ... @endphp`**: Bloque de código PHP que se ejecuta antes de renderizar el HTML. Útil para cálculos y lógica.

**`match($variant)`**: Estructura moderna de PHP para manejar múltiples condiciones. Más limpia que `switch` o `if/else`.

**`$attributes->merge()`**: Combina las clases generadas con cualquier clase adicional que se pase al componente.

**`{{ $slot }}`**: Contiene el contenido que se pasa entre las etiquetas del componente.

**Ventajas de los componentes anónimos:**

**Simplicidad**: Todo está en un archivo, más fácil de entender y mantener.

**Rendimiento**: No hay overhead de instanciar una clase PHP.

**Flexibilidad**: Puedes usar lógica PHP directamente en el archivo Blade.

**Organización**: Perfectos para componentes simples sin lógica compleja.

### 🎯 **Componente Anónimo con Slots**

Los componentes anónimos también pueden usar slots nombrados para mayor flexibilidad. Esto te permite crear componentes complejos sin necesidad de una clase PHP:

```php
{{-- resources/views/components/ui/card.blade.php --}}
@props([
    'title' => null,      // Título opcional del card
    'subtitle' => null,   // Subtítulo opcional del card
    'image' => null       // URL de imagen opcional
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

**Explicación detallada de cada parte:**

**`@props([...])`**: Define las props con valores por defecto. Todas son opcionales, haciendo el componente muy flexible.

**`$attributes->merge()`**: Combina las clases CSS con cualquier clase adicional que se pase al componente.

**`@if($image)`**: Solo muestra la imagen si se proporcionó una URL. Evita mostrar elementos vacíos.

**`alt="{{ $title }}"`**: Usa el título como texto alternativo para la imagen. Mejora la accesibilidad.

**`{{ $slot }}`**: Contiene el contenido principal del card. Es el contenido que se pasa entre las etiquetas.

**`@if(isset($footer))`**: Verifica si se proporcionó un slot nombrado "footer" antes de mostrarlo.

**Uso del componente con diferentes configuraciones:**

```php
{{-- Card básico --}}
<x-card>
    <p>Contenido del card</p>
</x-card>

{{-- Card con título --}}
<x-card title="Mi Título">
    <p>Contenido del card</p>
</x-card>

{{-- Card completo --}}
<x-card title="Servicio Premium" subtitle="Descripción del servicio" image="/images/service.jpg">
    <p>Detalles del servicio...</p>
    <x-slot name="footer">
        <x-button>Ver Detalles</x-button>
    </x-slot>
</x-card>
```

### 🔧 **Componente de Formulario**

Los componentes de formulario son especializados para la entrada de datos. Incluyen validación, manejo de errores y accesibilidad:

```php
{{-- resources/views/components/forms/input-group.blade.php --}}
@props([
    'label' => null,        // Etiqueta del campo
    'name' => null,         // Nombre del campo (para name e id)
    'type' => 'text',       // Tipo de input (text, email, password, etc.)
    'placeholder' => null,  // Texto de placeholder
    'required' => false,    // Si el campo es obligatorio
    'error' => null         // Mensaje de error (si existe)
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500" aria-label="Campo obligatorio">*</span>
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
        @if($error) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    >
    
    @if($error)
        <p id="{{ $name }}-error" class="text-sm text-red-600" role="alert">
            {{ $error }}
        </p>
    @endif
</div>
```

**Explicación detallada de cada parte:**

**`@props([...])`**: Define todas las props necesarias para un campo de formulario con valores por defecto.

**`for="{{ $name }}"`**: Conecta la etiqueta con el input usando el mismo ID. Mejora la accesibilidad.

**`<span class="text-red-500">*</span>`**: Indica visualmente que el campo es obligatorio.

**`aria-label="Campo obligatorio"`**: Proporciona contexto adicional para lectores de pantalla.

**`($error ? ' border-red-300' : '')`**: Agrega borde rojo si hay error de validación.

**`aria-invalid="true"`**: Indica a lectores de pantalla que el campo tiene un error.

**`aria-describedby="{{ $name }}-error"`**: Conecta el input con el mensaje de error para accesibilidad.

**`role="alert"`**: Indica que el mensaje de error es importante para lectores de pantalla.

**Uso del componente en formularios:**

```php
{{-- Campo básico --}}
<x-input-group 
    label="Email" 
    name="email" 
    type="email" 
    placeholder="tu@email.com"
    :required="true"
/>

{{-- Campo con error --}}
<x-input-group 
    label="Contraseña" 
    name="password" 
    type="password"
    :required="true"
    error="La contraseña debe tener al menos 8 caracteres"
/>

{{-- Campo con valor --}}
<x-input-group 
    label="Nombre" 
    name="name" 
    :value="$user->name"
    :required="true"
/>
```

## 🔄 **Reutilización de Componentes**

Los componentes están diseñados para ser reutilizables. Una vez que creas un componente, puedes usarlo en múltiples lugares con diferentes configuraciones. Esto te permite mantener consistencia en tu aplicación y acelerar el desarrollo.

### 🧭 **Componente de Navegación**

Un componente de navegación que se adapta según si el usuario está autenticado o no:

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

**Explicación detallada de cada parte:**

**`@props(['user' => null])`**: El componente puede recibir un usuario opcional. Si no se proporciona, es `null`.

**`route('home')`**: Genera la URL para la página de inicio usando el sistema de rutas de Laravel.

**`request()->routeIs('services.*')`**: Verifica si la ruta actual coincide con el patrón 'services.*'. Útil para resaltar la página activa.

**`@auth`**: Directiva de Blade que solo muestra el contenido si el usuario está autenticado.

**`@else`**: Contenido alternativo si el usuario NO está autenticado.

**`x-user-dropdown`**: Componente personalizado para mostrar el menú del usuario.

**`x-button`**: Componente de botón reutilizable con diferentes variantes y tamaños.

**Ventajas de esta implementación:**

**Responsive**: Se adapta a diferentes tamaños de pantalla con clases de Tailwind CSS.

**Condicional**: Muestra diferentes opciones según el estado de autenticación.

**Reutilizable**: Se puede usar en cualquier página de la aplicación.

**Consistente**: Mantiene la misma navegación en toda la aplicación.

### 👤 **Componente de Dropdown de Usuario**

Un componente dropdown que muestra las opciones del usuario autenticado. Usa Alpine.js para la interactividad:

```php
{{-- resources/views/components/layout/user-dropdown.blade.php --}}
@props(['user'])

<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open"
        class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        aria-expanded="false"
        aria-haspopup="true"
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
        role="menu"
        aria-orientation="vertical"
    >
        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
            Mi Perfil
        </a>
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
            Dashboard
        </a>
        <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
            Configuración
        </a>
        <hr class="my-1">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                Cerrar Sesión
            </button>
        </form>
    </div>
</div>
```

**Explicación detallada de cada parte:**

**`x-data="{ open: false }"`**: Inicializa Alpine.js con una variable `open` que controla si el dropdown está abierto.

**`@click="open = !open"`**: Alterna el estado del dropdown cuando se hace clic en el botón.

**`@click.away="open = false"`**: Cierra el dropdown cuando se hace clic fuera de él.

**`x-show="open"`**: Solo muestra el dropdown cuando `open` es `true`.

**`$user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name)`**: Usa el avatar del usuario o genera uno automáticamente si no tiene.

**`@csrf`**: Token de seguridad para el formulario de logout.

**`role="menu"` y `role="menuitem"`**: Atributos de accesibilidad para lectores de pantalla.

**`aria-expanded` y `aria-haspopup`**: Atributos de accesibilidad para el botón del dropdown.

**Ventajas de esta implementación:**

**Interactividad**: Usa Alpine.js para funcionalidad JavaScript mínima y directa.

**Accesibilidad**: Incluye atributos ARIA para usuarios con discapacidades.

**Responsive**: Se adapta a diferentes tamaños de pantalla.

**Seguridad**: Incluye token CSRF para el formulario de logout.

### 📊 **Componente de Tabla**

Un componente de tabla reutilizable que maneja headers dinámicos y filas con datos:

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

**Explicación detallada de cada parte:**

**`@props(['headers' => [], 'striped' => true])`**: Define las props del componente. `headers` es un array de nombres de columnas, `striped` controla si las filas alternan colores.

**`overflow-x-auto`**: Permite scroll horizontal si la tabla es muy ancha.

**`divide-y divide-gray-200`**: Agrega líneas divisorias entre filas y columnas.

**`@foreach($headers as $header)`**: Itera sobre los headers para crear las columnas de la tabla.

**`$loop->even && $striped`**: Aplica color alternado solo si `striped` es `true` y es una fila par.

**`whitespace-nowrap`**: Evita que el texto se divida en múltiples líneas.

**Ventajas de esta implementación:**

**Flexibilidad**: Los headers se pueden cambiar dinámicamente según los datos.

**Reutilización**: Se puede usar para cualquier tipo de tabla con diferentes datos.

**Responsive**: Incluye scroll horizontal para pantallas pequeñas.

**Consistencia**: Mantiene el mismo estilo en todas las tablas de la aplicación.

## 🎨 **Componentes Avanzados**

Los componentes avanzados incluyen funcionalidades complejas como modales, paginación y otros elementos interactivos. Estos componentes combinan múltiples tecnologías para crear experiencias de usuario ricas.

### 🪟 **Componente Modal**

Un modal completo con animaciones, accesibilidad y diferentes tamaños:

```php
{{-- resources/views/components/ui/modal.blade.php --}}
@props([
    'id' => null,           // ID único del modal
    'title' => null,        // Título opcional del modal
    'maxWidth' => '2xl'     // Tamaño máximo del modal
])

@php
// Generar clases CSS según el tamaño máximo
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
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay de fondo -->
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

        <!-- Espaciador para centrado vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Contenido del modal -->
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
                            <h3 id="{{ $id }}-title" class="text-lg leading-6 font-medium text-gray-900">
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

**Explicación detallada de cada parte:**

**`x-data="{ show: false }"`**: Inicializa Alpine.js con el estado del modal (cerrado por defecto).

**`@keydown.escape.window="show = false"`**: Cierra el modal cuando se presiona la tecla Escape.

**`role="dialog"` y `aria-modal="true"`**: Atributos de accesibilidad para lectores de pantalla.

**`@click="show = false"`**: Cierra el modal cuando se hace clic en el overlay.

**`x-transition`**: Animaciones suaves de entrada y salida usando Alpine.js.

**`match($maxWidth)`**: Genera clases CSS según el tamaño máximo del modal.

**`@if($title)`**: Solo muestra el título si se proporcionó uno.

**`@if(isset($footer))`**: Solo muestra el footer si se proporcionó un slot nombrado.

**Uso del componente modal:**

```php
{{-- Modal básico --}}
<x-modal id="confirm-delete" title="Confirmar Eliminación">
    <p>¿Estás seguro de que quieres eliminar este elemento?</p>
    
    <x-slot name="footer">
        <x-button variant="secondary" @click="show = false">Cancelar</x-button>
        <x-button variant="danger">Eliminar</x-button>
    </x-slot>
</x-modal>

{{-- Modal sin título --}}
<x-modal id="image-preview" maxWidth="lg">
    <img src="/images/preview.jpg" alt="Vista previa" class="w-full">
</x-modal>
```

### 📄 **Componente de Paginación**

Un componente de paginación completo que maneja diferentes tamaños de pantalla y muestra información detallada:

```php
{{-- resources/views/components/ui/pagination.blade.php --}}
@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between" role="navigation" aria-label="Paginación">
        <!-- Paginación móvil (solo Anterior/Siguiente) -->
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

        <!-- Paginación desktop (completa) -->
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
                    {{-- Elementos de paginación (números de página) --}}
                    {{ $slot }}
                </span>
            </div>
        </div>
    </nav>
@endif
```

**Explicación detallada de cada parte:**

**`@props(['paginator'])`**: Recibe un objeto paginator de Laravel que contiene toda la información de paginación.

**`@if ($paginator->hasPages())`**: Solo muestra la paginación si hay más de una página.

**`role="navigation"` y `aria-label="Paginación"`**: Atributos de accesibilidad para lectores de pantalla.

**`sm:hidden` y `hidden sm:flex`**: Clases responsive que muestran diferentes versiones según el tamaño de pantalla.

**`$paginator->onFirstPage()`**: Verifica si estamos en la primera página para deshabilitar el botón "Anterior".

**`$paginator->hasMorePages()`**: Verifica si hay más páginas para habilitar el botón "Siguiente".

**`$paginator->firstItem()` y `$paginator->lastItem()`**: Muestra el rango de elementos actual.

**`{{ $slot }}`**: Contiene los números de página que se generan automáticamente por Laravel.

**Uso del componente de paginación:**

```php
{{-- En el controlador --}}
$servicios = Servicio::paginate(10);

{{-- En la vista --}}
<div class="mt-6">
    {{ $servicios->links('components.ui.pagination') }}
</div>
```

**Ventajas de esta implementación:**

**Responsive**: Se adapta automáticamente a móviles y desktop.

**Accesibilidad**: Incluye atributos ARIA para usuarios con discapacidades.

**Información clara**: Muestra cuántos elementos se están viendo y el total.

**Consistencia**: Mantiene el mismo estilo en toda la aplicación.

## 📝 **Comandos Útiles**

Laravel proporciona comandos Artisan para crear y gestionar componentes de manera eficiente:

```bash
# Crear componente básico - Solo la clase PHP
php artisan make:component Button

# Crear componente con vista - Incluye archivo Blade
php artisan make:component Alert --view

# Crear componente anónimo - Solo archivo Blade (crear manualmente)
# Crear archivo en resources/views/components/button.blade.php

# Crear componente en subdirectorio - Organizar por categoría
php artisan make:component ui/Button

# Crear componente de formulario - Especializado para formularios
php artisan make:component forms/InputGroup --view

# Crear componente con props específicas - Más control
php artisan make:component Modal --view --force

# Listar componentes existentes - Ver qué componentes tienes
php artisan list --name=component
```

**Explicación detallada de cada comando:**

**make:component Button**: Crea un componente clásico con clase PHP. Útil para componentes con lógica compleja.

**make:component Alert --view**: Crea un componente con clase PHP y archivo Blade. Útil para componentes que necesitan HTML personalizado.

**Componente anónimo**: Se crea manualmente como archivo Blade. No hay comando específico, pero es más simple.

**make:component ui/Button**: Crea el componente en la subcarpeta `ui/`. Ayuda a organizar componentes por funcionalidad.

**make:component forms/InputGroup --view**: Crea un componente especializado para formularios. Se organiza en la carpeta `forms/`.

**--force**: Sobrescribe el componente si ya existe. Útil para actualizar componentes existentes.

## 🎯 **Buenas Prácticas**

### ✅ **Organización de Componentes**

**Usar subcarpetas para organizar**: Agrupa componentes relacionados en carpetas como `ui/`, `forms/`, `layout/`.

**Nomenclatura consistente**: Usa kebab-case para nombres de archivos (`button.blade.php`, `input-group.blade.php`).

**Separar componentes por complejidad**: Usa componentes clásicos para lógica compleja, anónimos para casos simples.

**Crear componentes reutilizables**: Diseña componentes que se puedan usar en múltiples lugares con diferentes props.

### ✅ **Props y Slots**

**Usar valores por defecto**: Hace que los componentes sean más fáciles de usar y menos propensos a errores.

**Validar props cuando sea necesario**: Verifica que las props tengan valores válidos antes de usarlas.

**Usar slots nombrados para contenido complejo**: Permite mayor flexibilidad en la estructura del componente.

**Documentar props importantes**: Comenta las props que no son obvias para facilitar el uso del componente.

### ✅ **Accesibilidad**

**Incluir atributos ARIA**: Agrega `role`, `aria-label`, `aria-describedby` cuando sea apropiado.

**Usar etiquetas semánticas**: Usa `<button>`, `<nav>`, `<main>` en lugar de `<div>` cuando sea apropiado.

**Proporcionar texto alternativo**: Incluye `alt` en imágenes y `aria-label` en botones sin texto.

**Manejar navegación por teclado**: Asegúrate de que los componentes sean navegables con teclado.

### ✅ **Rendimiento**

**Evitar lógica compleja en componentes anónimos**: Mueve lógica compleja a componentes clásicos.

**Usar lazy loading para componentes pesados**: Carga componentes solo cuando sean necesarios.

**Minimizar el uso de JavaScript**: Usa Alpine.js para interactividad simple, frameworks más grandes para casos complejos.

**Cachear componentes cuando sea apropiado**: Usa `@cache` para componentes que no cambian frecuentemente.

### ✅ **Mantenibilidad**

**Mantener componentes pequeños**: Un componente debe hacer una cosa bien. Divide componentes grandes en componentes más pequeños.

**Usar nombres descriptivos**: Los nombres de componentes deben describir claramente su función.

**Comentar código complejo**: Explica lógica compleja o no obvia en los componentes.

**Crear documentación**: Documenta cómo usar cada componente, especialmente los más complejos.

### ✅ **Testing**

**Probar componentes individualmente**: Crea tests específicos para cada componente.

**Probar diferentes props**: Verifica que el componente funcione con diferentes combinaciones de props.

**Probar accesibilidad**: Usa herramientas como axe-core para verificar accesibilidad.

**Probar responsividad**: Verifica que los componentes se vean bien en diferentes tamaños de pantalla.

## 🎯 **Resumen**

Los componentes Blade en Laravel proporcionan una forma poderosa y flexible de crear interfaces de usuario reutilizables:

- ✅ **Reutilización de código HTML**: Evita duplicar código en múltiples vistas
- ✅ **Encapsulación de lógica de presentación**: Mantiene la lógica de UI organizada
- ✅ **Props y slots flexibles**: Permite personalización sin duplicar componentes
- ✅ **Componentes anónimos para casos simples**: Solución rápida para componentes básicos
- ✅ **Mejor organización del código**: Estructura clara y fácil de mantener
- ✅ **Consistencia en la interfaz**: Mantiene el mismo diseño en toda la aplicación
- ✅ **Accesibilidad integrada**: Facilita crear interfaces accesibles
- ✅ **Rendimiento optimizado**: Componentes eficientes y bien estructurados

**Próximo paso:** Tailwind CSS para estilos y diseño responsivo 