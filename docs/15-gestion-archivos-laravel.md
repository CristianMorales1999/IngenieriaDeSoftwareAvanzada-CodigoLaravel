# 📁 Gestión de Archivos en Laravel 12

## 🎯 Introducción

Laravel proporciona un sistema robusto de gestión de archivos que incluye subida, validación, optimización y almacenamiento de imágenes. Se integra con múltiples drivers de almacenamiento (local, S3, etc.) y ofrece herramientas para manipulación de imágenes. Es como tener un "gestor de archivos" que maneja todo el proceso desde la subida hasta el almacenamiento seguro.

**¿Por qué es importante la gestión de archivos?**
- **Experiencia de usuario**: Imágenes optimizadas cargan más rápido
- **Seguridad**: Validación y sanitización previenen archivos maliciosos
- **Escalabilidad**: Múltiples opciones de almacenamiento (local, nube)
- **Organización**: Estructura clara para diferentes tipos de archivos
- **Rendimiento**: Optimización automática mejora la velocidad del sitio

## 🚀 Configuración Inicial

### 1. **Configuración de Almacenamiento**
Laravel permite configurar múltiples "discos" de almacenamiento para diferentes tipos de archivos y ubicaciones. Es como tener diferentes "carpetas" organizadas para distintos propósitos:

```php
// config/filesystems.php
return [
    'default' => env('FILESYSTEM_DISK', 'local'), // Disco por defecto

    'disks' => [
        'local' => [
            'driver' => 'local',                    // Almacenamiento local
            'root' => storage_path('app'),          // Ruta en el servidor
        ],

        'public' => [
            'driver' => 'local',                    // Almacenamiento público
            'root' => storage_path('app/public'),   // Ruta accesible públicamente
            'url' => env('APP_URL').'/storage',     // URL para acceder
            'visibility' => 'public',               // Archivos públicos
        ],

        's3' => [
            'driver' => 's3',                       // Amazon S3
            'key' => env('AWS_ACCESS_KEY_ID'),      // Clave de acceso AWS
            'secret' => env('AWS_SECRET_ACCESS_KEY'), // Clave secreta AWS
            'region' => env('AWS_DEFAULT_REGION'),   // Región de AWS
            'bucket' => env('AWS_BUCKET'),           // Nombre del bucket
            'url' => env('AWS_URL'),                 // URL personalizada
            'endpoint' => env('AWS_ENDPOINT'),       // Endpoint personalizado
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        'images' => [
            'driver' => 'local',                    // Disco específico para imágenes
            'root' => storage_path('app/public/images'), // Ruta para imágenes
            'url' => env('APP_URL').'/storage/images',   // URL para imágenes
            'visibility' => 'public',                // Imágenes públicas
        ],
    ],
];
```

**Explicación detallada de los discos de almacenamiento:**

- **`default`**: Define qué disco se usa por defecto si no especificas uno
- **`local`**: Archivos privados del sistema (configuraciones, logs, datos sensibles)
  - **`driver`**: Tipo de almacenamiento (local = archivos en el servidor)
  - **`root`**: Ruta donde se guardan los archivos
- **`public`**: Archivos accesibles públicamente (imágenes, documentos, recursos)
  - **`url`**: URL base para acceder a los archivos desde el navegador
  - **`visibility`**: Los archivos son públicos y accesibles
- **`s3`**: Almacenamiento en la nube de Amazon (escalable, redundante)
  - **`key` y `secret`**: Credenciales de AWS para autenticación
  - **`region`**: Región geográfica del servidor AWS
  - **`bucket`**: Contenedor donde se almacenan los archivos
- **`images`**: Disco específico para imágenes (mejor organización)

### 2. **Crear Enlace Simbólico**
Para que los archivos en `storage/app/public` sean accesibles desde el navegador, necesitas crear un enlace simbólico:

```bash
# Crear enlace simbólico para acceso público
php artisan storage:link
```

**Explicación del enlace simbólico:**

- **¿Qué hace?**: Crea un enlace simbólico de `public/storage` a `storage/app/public`
- **¿Por qué?**: Permite acceder a archivos públicos desde URLs como `/storage/images/photo.jpg`
- **¿Cuándo?**: Solo se ejecuta una vez por proyecto
- **Funcionamiento**: Es como crear un "atajo" que conecta la carpeta pública con la de almacenamiento

### 3. **Instalar Intervención Image**
Para manipular y optimizar imágenes automáticamente, necesitas instalar esta biblioteca:

```bash
# Instalar para manipulación de imágenes
composer require intervention/image
```

**Explicación de Intervention Image:**

- **¿Qué es?**: Biblioteca PHP especializada en manipulación de imágenes
- **Funcionalidades**: Redimensionar, recortar, optimizar, aplicar filtros, convertir formatos
- **Formatos soportados**: JPEG, PNG, GIF, WebP, TIFF, etc.
- **Optimización**: Reduce el tamaño de archivo manteniendo buena calidad visual
- **Ventajas**: API simple, alto rendimiento, muchas opciones de configuración

## 📤 Subida de Archivos

### 1. **Formulario de Subida**
El formulario HTML debe incluir el atributo `enctype="multipart/form-data"` para permitir la subida de archivos:

```php
{{-- resources/views/services/create.blade.php --}}
<form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Servicio</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>
    
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
    </div>
    
    <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Imagen del Servicio</label>
        <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <p class="mt-1 text-sm text-gray-500">PNG, JPG, JPEG hasta 2MB</p>
    </div>
    
    <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Precio</label>
        <input type="number" name="price" id="price" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>
    
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
        Crear Servicio
    </button>
</form>
```

**Explicación detallada del formulario:**

- **`enctype="multipart/form-data"`**: Atributo obligatorio para subir archivos (permite enviar datos binarios)
- **`accept="image/*"`**: Restringe la selección solo a archivos de imagen en el navegador
- **`type="file"`**: Crea el botón de selección de archivo
- **Estilos Tailwind**: Clases CSS que dan formato al botón de archivo
- **Texto informativo**: Ayuda al usuario sobre qué formatos y tamaños acepta

### 2. **Controlador de Subida**
El controlador procesa la subida del archivo, lo valida, optimiza y guarda:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ServiceController extends Controller
{
    public function store(StoreServiceRequest $request)
    {
        // Obtener datos validados del Form Request
        $data = $request->validated();
        
        // Procesar imagen si se subió
        if ($request->hasFile('image')) {
            // Llamar método privado para procesar la imagen
            $imagePath = $this->processImage($request->file('image'));
            $data['image_path'] = $imagePath; // Agregar ruta de la imagen a los datos
        }
        
        // Crear el servicio en la base de datos
        $service = Service::create($data);
        
        // Redirigir con mensaje de éxito
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }
    
    /**
     * Procesa y optimiza la imagen subida
     */
    private function processImage($file)
    {
        // Generar nombre único para evitar conflictos
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Crear instancia de imagen con Intervention Image
        $image = Image::make($file);
        
        // Redimensionar si la imagen es muy grande
        if ($image->width() > 1200 || $image->height() > 800) {
            $image->resize(1200, 800, function ($constraint) {
                $constraint->aspectRatio(); // Mantener proporción original
                $constraint->upsize();      // No agrandar si es más pequeña
            });
        }
        
        // Optimizar calidad (85% es un buen balance entre calidad y tamaño)
        $image->encode('jpg', 85);
        
        // Guardar en storage público
        $path = 'images/services/' . $fileName;
        Storage::disk('public')->put($path, $image);
        
        return $path; // Retornar ruta para guardar en la base de datos
    }
}
```

**Explicación detallada del controlador:**

- **`$request->validated()`**: Obtiene solo los datos que pasaron la validación
- **`$request->hasFile('image')`**: Verifica si se subió un archivo con ese nombre
- **`$request->file('image')`**: Obtiene el archivo subido
- **`time() . '_' . uniqid()`**: Genera nombre único combinando timestamp y ID único
- **`Image::make($file)`**: Crea instancia de imagen para manipulación
- **`aspectRatio()`**: Mantiene la proporción original de la imagen
- **`upsize()`**: Evita agrandar imágenes pequeñas
- **`encode('jpg', 85)`**: Convierte a JPEG con 85% de calidad
- **`Storage::disk('public')->put()`**: Guarda el archivo en el disco público

### 3. **Form Request para Validación**
Los Form Requests centralizan la validación y hacen el código más limpio:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios autenticados
    }

    /**
     * Define las reglas de validación para todos los campos
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=100,min_height=100',
        ];
    }

    /**
     * Mensajes personalizados para cada regla de validación
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.unique' => 'Ya existe un servicio con este nombre.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPEG, PNG o JPG.',
            'image.max' => 'La imagen no debe superar los 2MB.',
            'image.dimensions' => 'La imagen debe tener al menos 100x100 píxeles.',
        ];
    }
}
```

**Explicación de las reglas de validación de imagen:**

- **`nullable`**: El campo es opcional (no obligatorio)
- **`image`**: Verifica que sea un archivo de imagen válido
- **`mimes:jpeg,png,jpg`**: Solo permite estos formatos específicos
- **`max:2048`**: Máximo 2MB (2048 KB)
- **`dimensions:min_width=100,min_height=100`**: Dimensiones mínimas requeridas

## ✅ Validación de Imágenes

### 1. **Validación Básica**
Validación simple para verificar que sea una imagen válida:

```php
// Validación en el controlador
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
]);
```

**Explicación de las reglas básicas:**
- **`required`**: El campo es obligatorio
- **`image`**: Verifica que sea un archivo de imagen válido
- **`mimes:jpeg,png,jpg`**: Solo permite estos formatos
- **`max:2048`**: Máximo 2MB de tamaño

### 2. **Validación Avanzada**
Validación más estricta con dimensiones y proporciones específicas:

```php
// Validación con dimensiones y proporciones
$request->validate([
    'image' => [
        'required',
        'image',
        'mimes:jpeg,png,jpg',
        'max:2048',
        'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000,ratio=16/9'
    ]
]);
```

**Explicación de las reglas avanzadas:**
- **`dimensions:min_width=100,min_height=100`**: Dimensiones mínimas
- **`max_width=2000,max_height=2000`**: Dimensiones máximas
- **`ratio=16/9`**: Proporción específica (formato panorámico)

### 3. **Validación Personalizada**
Crear reglas de validación personalizadas para casos específicos:

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Intervention\Image\Facades\Image;

class ValidImage implements ValidationRule
{
    /**
     * Constructor con parámetros configurables
     */
    public function __construct(
        private int $maxWidth = 2000,
        private int $maxHeight = 2000,
        private int $minWidth = 100,
        private int $minHeight = 100
    ) {}

    /**
     * Ejecuta la validación personalizada
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar que el archivo existe y es válido
        if (!$value || !$value->isValid()) {
            $fail('El archivo de imagen no es válido.');
            return;
        }

        try {
            // Crear instancia de imagen para análisis
            $image = Image::make($value);
            
            // Verificar dimensiones mínimas
            if ($image->width() < $this->minWidth || $image->height() < $this->minHeight) {
                $fail("La imagen debe tener al menos {$this->minWidth}x{$this->minHeight} píxeles.");
            }
            
            // Verificar dimensiones máximas
            if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
                $fail("La imagen no debe superar {$this->maxWidth}x{$this->maxHeight} píxeles.");
            }
            
            // Verificar que sea una imagen real (no un archivo renombrado)
            if (!$image->mime() || !in_array($image->mime(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                $fail('El archivo debe ser una imagen válida (JPEG, PNG).');
            }
            
        } catch (\Exception $e) {
            // Si hay error al procesar la imagen
            $fail('No se pudo procesar la imagen.');
        }
    }
}
```

**Explicación de la validación personalizada:**

- **Constructor**: Permite configurar dimensiones mínimas y máximas
- **`$value->isValid()`**: Verifica que el archivo se subió correctamente
- **`Image::make($value)`**: Crea instancia para analizar la imagen
- **`$image->width()` y `$image->height()`**: Obtiene dimensiones reales
- **`$image->mime()`**: Obtiene el tipo MIME real del archivo
- **`in_array()`**: Verifica que sea un tipo de imagen válido
- **Try-catch**: Maneja errores si la imagen está corrupta

### 4. **Validación con JavaScript**
Validación en el lado del cliente para mejor experiencia de usuario:

```javascript
// resources/js/image-validation.js
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validar tamaño del archivo (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('La imagen no debe superar los 2MB.');
                this.value = ''; // Limpiar selección
                return;
            }
            
            // Validar tipo de archivo
            if (!file.type.startsWith('image/')) {
                alert('El archivo debe ser una imagen.');
                this.value = '';
                return;
            }
            
            // Validar dimensiones de la imagen
            const img = new Image();
            img.onload = function() {
                if (this.width < 100 || this.height < 100) {
                    alert('La imagen debe tener al menos 100x100 píxeles.');
                    imageInput.value = '';
                }
            };
            img.src = URL.createObjectURL(file); // Crear URL temporal para la imagen
        }
    });
});
```

**Explicación de la validación JavaScript:**

- **`DOMContentLoaded`**: Se ejecuta cuando la página está completamente cargada
- **`addEventListener('change')`**: Se activa cuando el usuario selecciona un archivo
- **`file.size`**: Tamaño del archivo en bytes
- **`file.type`**: Tipo MIME del archivo
- **`new Image()`**: Crea objeto de imagen para analizar dimensiones
- **`URL.createObjectURL()`**: Crea URL temporal para acceder al archivo
- **`this.value = ''`**: Limpia la selección si hay error

## 🎨 Optimización de Imágenes

### 1. **Optimización Básica**
Servicio para optimizar imágenes con configuraciones por defecto:

```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Optimiza una imagen con configuraciones personalizables
     */
    public function optimize($file, $path, $options = [])
    {
        // Configuraciones por defecto
        $defaults = [
            'width' => 800,      // Ancho máximo por defecto
            'height' => 600,     // Alto máximo por defecto
            'quality' => 85,     // Calidad JPEG (0-100)
            'format' => 'jpg'    // Formato de salida
        ];
        
        // Combinar opciones personalizadas con las por defecto
        $options = array_merge($defaults, $options);
        
        // Crear instancia de imagen
        $image = Image::make($file);
        
        // Redimensionar manteniendo proporción
        $image->resize($options['width'], $options['height'], function ($constraint) {
            $constraint->aspectRatio(); // Mantener proporción original
            $constraint->upsize();      // No agrandar si es más pequeña
        });
        
        // Optimizar calidad y formato
        $image->encode($options['format'], $options['quality']);
        
        // Generar nombre único y guardar
        $fileName = time() . '_' . uniqid() . '.' . $options['format'];
        $fullPath = $path . '/' . $fileName;
        
        Storage::disk('public')->put($fullPath, $image);
        
        return $fullPath; // Retornar ruta del archivo guardado
    }
}
```

**Explicación de la optimización básica:**

- **`array_merge()`**: Combina configuraciones por defecto con las personalizadas
- **`resize()`**: Redimensiona la imagen a las dimensiones especificadas
- **`aspectRatio()`**: Mantiene la proporción original (evita distorsión)
- **`upsize()`**: Evita agrandar imágenes que ya son pequeñas
- **`encode()`**: Convierte al formato especificado con la calidad dada
- **`time() . '_' . uniqid()`**: Genera nombre único para evitar conflictos

### 2. **Optimización con Múltiples Tamaños**
Crear diferentes versiones de la misma imagen para diferentes usos:

```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    /**
     * Crea thumbnails en diferentes tamaños
     */
    public function createThumbnails($file, $basePath)
    {
        // Definir diferentes tamaños para diferentes usos
        $sizes = [
            'thumbnail' => [150, 150],  // Para listas y grids
            'small' => [300, 300],      // Para tarjetas
            'medium' => [600, 600],     // Para vistas detalladas
            'large' => [1200, 1200]     // Para vistas completas
        ];
        
        $paths = []; // Array para almacenar todas las rutas
        
        foreach ($sizes as $size => $dimensions) {
            // Crear nueva instancia para cada tamaño
            $image = Image::make($file);
            
            // Redimensionar usando fit() para recortar si es necesario
            $image->fit($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->upsize(); // No agrandar si es más pequeña
            });
            
            // Optimizar calidad
            $image->encode('jpg', 85);
            
            // Generar nombre con sufijo del tamaño
            $fileName = time() . '_' . uniqid() . "_{$size}.jpg";
            $path = $basePath . '/' . $fileName;
            
            // Guardar en storage
            Storage::disk('public')->put($path, $image);
            $paths[$size] = $path; // Guardar ruta en el array
        }
        
        return $paths; // Retornar todas las rutas creadas
    }
    
    /**
     * Crea imágenes responsive para diferentes dispositivos
     */
    public function createResponsiveImages($file, $basePath)
    {
        // Tamaños para diferentes breakpoints de CSS
        $sizes = [
            'xs' => [320, 240],   // Móviles pequeños
            'sm' => [640, 480],   // Móviles grandes
            'md' => [768, 576],   // Tablets
            'lg' => [1024, 768],  // Laptops
            'xl' => [1280, 960]   // Desktops
        ];
        
        $paths = [];
        
        foreach ($sizes as $breakpoint => $dimensions) {
            $image = Image::make($file);
            
            // Redimensionar manteniendo proporción
            $image->resize($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Usar formato WebP para mejor compresión
            $image->encode('webp', 85);
            
            // Generar nombre con breakpoint
            $fileName = time() . '_' . uniqid() . "_{$breakpoint}.webp";
            $path = $basePath . '/' . $fileName;
            
            Storage::disk('public')->put($path, $image);
            $paths[$breakpoint] = $path;
        }
        
        return $paths;
    }
}
```

**Explicación de la optimización múltiple:**

- **`createThumbnails()`**: Crea versiones en diferentes tamaños para diferentes usos
- **`fit()`**: Recorta la imagen para ajustarse exactamente a las dimensiones
- **`createResponsiveImages()`**: Crea versiones para diferentes tamaños de pantalla
- **`resize()`**: Mantiene proporción sin recortar
- **`WebP`**: Formato moderno con mejor compresión que JPEG
- **Breakpoints**: Tamaños correspondientes a media queries de CSS

### 3. **Optimización con Watermark**
Agregar marca de agua a las imágenes para protección de derechos:

```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class WatermarkService
{
    /**
     * Agrega marca de agua a una imagen
     */
    public function addWatermark($imagePath, $watermarkPath = null)
    {
        // Cargar la imagen original
        $image = Image::make(storage_path('app/public/' . $imagePath));
        
        // Usar marca de agua por defecto si no se especifica
        if (!$watermarkPath) {
            $watermarkPath = public_path('images/watermark.png');
        }
        
        // Verificar que existe la marca de agua
        if (file_exists($watermarkPath)) {
            // Cargar la imagen de marca de agua
            $watermark = Image::make($watermarkPath);
            
            // Insertar marca de agua en la esquina inferior derecha
            // Parámetros: imagen, posición, margen horizontal, margen vertical
            $image->insert($watermark, 'bottom-right', 10, 10);
        }
        
        // Guardar imagen con marca de agua
        $image->encode('jpg', 85);
        Storage::disk('public')->put($imagePath, $image);
        
        return $imagePath;
    }
}
```

**Explicación del watermark:**

- **`storage_path('app/public/' . $imagePath)`**: Ruta completa al archivo en storage
- **`public_path('images/watermark.png')`**: Ruta a la imagen de marca de agua
- **`file_exists()`**: Verifica que existe el archivo de marca de agua
- **`insert()`**: Agrega una imagen sobre otra en posición específica
- **Posiciones disponibles**: 'top-left', 'top', 'top-right', 'left', 'center', 'right', 'bottom-left', 'bottom', 'bottom-right'

## 💾 Almacenamiento

### 1. **Almacenamiento Local**
Operaciones básicas con archivos en el servidor local:

```php
// Guardar archivo en disco local (privado)
Storage::disk('local')->put('images/service.jpg', $fileContents);

// Guardar archivo en disco público (accesible desde web)
Storage::disk('public')->put('images/service.jpg', $fileContents);

// Obtener URL pública del archivo
$url = Storage::disk('public')->url('images/service.jpg');
```

**Explicación del almacenamiento local:**

- **`Storage::disk('local')`**: Accede al disco local (archivos privados)
- **`Storage::disk('public')`**: Accede al disco público (archivos accesibles)
- **`put()`**: Guarda contenido en la ruta especificada
- **`url()`**: Genera URL pública para acceder al archivo

### 2. **Almacenamiento en S3**
Configurar y usar Amazon S3 para almacenamiento en la nube:

```php
// Configurar S3 en .env
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
AWS_USE_PATH_STYLE_ENDPOINT=false

// Guardar archivo en S3
Storage::disk('s3')->put('images/service.jpg', $fileContents);

// Obtener URL de S3
$url = Storage::disk('s3')->url('images/service.jpg');
```

**Explicación del almacenamiento S3:**

- **Variables de entorno**: Credenciales y configuración de AWS
- **`AWS_ACCESS_KEY_ID`**: Clave de acceso para autenticación
- **`AWS_SECRET_ACCESS_KEY`**: Clave secreta para autenticación
- **`AWS_DEFAULT_REGION`**: Región geográfica del servidor
- **`AWS_BUCKET`**: Nombre del contenedor de archivos
- **Ventajas**: Escalable, redundante, alta disponibilidad

### 3. **Servicio de Almacenamiento**
Clase centralizada para manejar todas las operaciones de archivos:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class FileStorageService
{
    /**
     * Guarda una imagen sin optimización
     */
    public function storeImage(UploadedFile $file, string $path = 'images'): string
    {
        // Generar nombre único
        $fileName = $this->generateFileName($file);
        $fullPath = $path . '/' . $fileName;
        
        // Guardar archivo tal como está
        Storage::disk('public')->put($fullPath, file_get_contents($file));
        
        return $fullPath;
    }
    
    /**
     * Guarda una imagen optimizada
     */
    public function storeOptimizedImage(UploadedFile $file, string $path = 'images'): string
    {
        // Crear instancia de imagen para optimización
        $image = Image::make($file);
        
        // Optimizar: redimensionar y comprimir
        $image->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Convertir a JPEG con 85% de calidad
        $image->encode('jpg', 85);
        
        // Generar nombre y guardar
        $fileName = $this->generateFileName($file, 'jpg');
        $fullPath = $path . '/' . $fileName;
        
        Storage::disk('public')->put($fullPath, $image);
        
        return $fullPath;
    }
    
    /**
     * Elimina una imagen del storage
     */
    public function deleteImage(string $path): bool
    {
        // Verificar que el archivo existe antes de eliminar
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false; // Retornar false si no existe
    }
    
    /**
     * Genera nombre único para archivos
     */
    private function generateFileName(UploadedFile $file, string $extension = null): string
    {
        // Usar extensión del archivo original o la especificada
        $extension = $extension ?: $file->getClientOriginalExtension();
        return time() . '_' . uniqid() . '.' . $extension;
    }
}
```

**Explicación del servicio de almacenamiento:**

- **`storeImage()`**: Guarda archivo sin modificar (más rápido)
- **`storeOptimizedImage()`**: Optimiza antes de guardar (mejor rendimiento)
- **`deleteImage()`**: Elimina archivo de forma segura
- **`generateFileName()`**: Método privado para crear nombres únicos
- **`file_get_contents()`**: Lee el contenido del archivo subido
- **`exists()`**: Verifica que el archivo existe antes de eliminarlo

## 🖼️ Mostrar Imágenes

### 1. **Helper para URLs de Imágenes**
Clase helper para manejar URLs de imágenes de forma consistente:

```php
<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Genera URL para una imagen
     */
    public static function url($path, $disk = 'public')
    {
        // Si no hay ruta, mostrar imagen placeholder
        if (!$path) {
            return asset('images/placeholder.jpg');
        }
        
        // Verificar que el archivo existe en storage
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        
        // Si no existe, mostrar placeholder
        return asset('images/placeholder.jpg');
    }
    
    /**
     * Genera URL para thumbnail de una imagen
     */
    public static function thumbnail($path, $size = 'thumbnail')
    {
        // Si no hay ruta, mostrar placeholder
        if (!$path) {
            return asset('images/placeholder.jpg');
        }
        
        // Buscar thumbnail con sufijo del tamaño
        $thumbnailPath = str_replace('.jpg', "_{$size}.jpg", $path);
        
        // Si existe el thumbnail, usarlo
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }
        
        // Si no existe, usar imagen original
        return self::url($path);
    }
}
```

**Explicación del helper de imágenes:**

- **`asset()`**: Genera URL para archivos en la carpeta `public`
- **`Storage::disk($disk)->exists()`**: Verifica que el archivo existe
- **`Storage::disk($disk)->url()`**: Genera URL pública del archivo
- **`str_replace()`**: Reemplaza extensión para buscar thumbnail
- **Fallback**: Siempre muestra algo, nunca URLs rotas

### 2. **Componente Blade para Imágenes**
Componente reutilizable para mostrar imágenes con opciones avanzadas:

```php
{{-- resources/views/components/ui/image.blade.php --}}
@props([
    'src' => null,
    'alt' => '',
    'class' => '',
    'lazy' => false,
    'responsive' => false
])

@php
    // Generar URL de la imagen usando el helper
    $imageUrl = $src ? \App\Helpers\ImageHelper::url($src) : asset('images/placeholder.jpg');
@endphp

<img 
    src="{{ $imageUrl }}"
    alt="{{ $alt }}"
    {{ $attributes->merge(['class' => $class]) }}
    @if($lazy) loading="lazy" @endif
    @if($responsive) 
        srcset="{{ \App\Helpers\ImageHelper::thumbnail($src, 'xs') }} 320w,
                {{ \App\Helpers\ImageHelper::thumbnail($src, 'sm') }} 640w,
                {{ \App\Helpers\ImageHelper::thumbnail($src, 'md') }} 768w,
                {{ \App\Helpers\ImageHelper::thumbnail($src, 'lg') }} 1024w,
                {{ \App\Helpers\ImageHelper::thumbnail($src, 'xl') }} 1280w"
        sizes="(max-width: 320px) 280px,
               (max-width: 640px) 600px,
               (max-width: 768px) 720px,
               (max-width: 1024px) 960px,
               1200px"
    @endif
>
```

**Explicación del componente de imagen:**

- **`@props`**: Define las propiedades que acepta el componente
- **`$attributes->merge()`**: Combina clases CSS personalizadas con las por defecto
- **`loading="lazy"`**: Carga la imagen solo cuando está cerca del viewport
- **`srcset`**: Define diferentes versiones de la imagen para diferentes tamaños
- **`sizes`**: Define qué tamaño usar en cada breakpoint
- **Responsive**: El navegador elige automáticamente la mejor imagen

### 3. **Uso en Vistas**
Ejemplos de cómo usar el componente de imagen:

```php
{{-- Mostrar imagen básica --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" class="w-full h-64 object-cover rounded-lg" />

{{-- Mostrar imagen con lazy loading --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" lazy class="w-full h-64 object-cover rounded-lg" />

{{-- Mostrar imagen responsive --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" responsive class="w-full h-64 object-cover rounded-lg" />
```

**Explicación de los usos:**

- **Imagen básica**: Carga inmediata, tamaño fijo
- **Lazy loading**: Mejora rendimiento en páginas con muchas imágenes
- **Responsive**: Se adapta automáticamente al tamaño de pantalla
- **Clases Tailwind**: `w-full` (ancho completo), `h-64` (alto fijo), `object-cover` (cubrir contenedor)

## 📝 Comandos Útiles

```bash
# Crear enlace simbólico para acceso público
php artisan storage:link

# Limpiar archivos temporales
php artisan storage:clear

# Instalar Intervention Image
composer require intervention/image

# Publicar configuración de Intervention Image
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"

# Crear directorios de almacenamiento
mkdir -p storage/app/public/images/services
mkdir -p storage/app/public/images/users
mkdir -p storage/app/public/images/temp
```

**Explicación de los comandos:**

- **`storage:link`**: Crea enlace simbólico para acceso público (solo una vez)
- **`storage:clear`**: Limpia archivos temporales y cache
- **`composer require`**: Instala la biblioteca de manipulación de imágenes
- **`vendor:publish`**: Publica archivos de configuración de la biblioteca
- **`mkdir -p`**: Crea directorios con estructura completa

## 🎯 Resumen

La gestión de archivos en Laravel proporciona un sistema completo y robusto:

### ✅ **Funcionalidades Implementadas:**
- **Subida Segura**: Validación y sanitización de archivos
- **Validación Robusta**: Verificación de tipo, tamaño y dimensiones
- **Optimización Automática**: Redimensionamiento y compresión automática
- **Múltiples Drivers**: Soporte para almacenamiento local y en la nube
- **Generación de Thumbnails**: Versiones optimizadas para diferentes usos
- **Imágenes Responsive**: Adaptación automática a diferentes dispositivos
- **Integración S3**: Almacenamiento escalable en la nube

### 🔧 **Características Clave:**
- **Configuración Flexible**: Múltiples discos para diferentes propósitos
- **Optimización Inteligente**: Balance entre calidad y tamaño
- **Componentes Reutilizables**: Fácil implementación en vistas
- **Fallbacks Seguros**: Siempre muestra algo, nunca URLs rotas
- **Lazy Loading**: Mejora rendimiento en páginas con muchas imágenes

### 🚀 **Próximo Paso:**
Implementación práctica de la **Fase 5** con gestión completa de archivos integrada en el CRUD de servicios. 