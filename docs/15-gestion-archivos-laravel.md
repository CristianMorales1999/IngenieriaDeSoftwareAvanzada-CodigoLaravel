# 📁 Gestión de Archivos en Laravel 12

## 🎯 Introducción

Laravel proporciona un sistema robusto de gestión de archivos que incluye subida, validación, optimización y almacenamiento de imágenes. Se integra con múltiples drivers de almacenamiento (local, S3, etc.) y ofrece herramientas para manipulación de imágenes. Es como tener un "gestor de archivos" que maneja todo el proceso desde la subida hasta el almacenamiento seguro.

## 🚀 Configuración Inicial

### 1. **Configuración de Almacenamiento**
Laravel permite configurar múltiples "discos" de almacenamiento para diferentes tipos de archivos y ubicaciones:

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

**Explicación de los discos:**
- **local**: Archivos privados del sistema (configuraciones, logs)
- **public**: Archivos accesibles públicamente (imágenes, documentos)
- **s3**: Almacenamiento en la nube (escalable, redundante)
- **images**: Disco específico para imágenes (organización)

### 2. **Crear Enlace Simbólico**
Para que los archivos en `storage/app/public` sean accesibles desde el navegador:

```bash
# Crear enlace simbólico para acceso público
php artisan storage:link
```

**Explicación:**
- Crea un enlace simbólico de `public/storage` a `storage/app/public`
- Permite acceder a archivos públicos desde URLs como `/storage/images/photo.jpg`
- Solo se ejecuta una vez por proyecto

### 3. **Instalar Intervención Image**
Para manipular y optimizar imágenes automáticamente:

```bash
# Instalar para manipulación de imágenes
composer require intervention/image
```

**Explicación:**
- **Intervention Image**: Biblioteca PHP para manipulación de imágenes
- **Funcionalidades**: Redimensionar, recortar, optimizar, aplicar filtros
- **Formatos**: JPEG, PNG, GIF, WebP, etc.
- **Optimización**: Reduce tamaño de archivo manteniendo calidad

## 📤 Subida de Archivos

### 1. **Formulario de Subida**
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

### 2. **Controlador de Subida**
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
        $data = $request->validated();
        
        // Procesar imagen si se subió
        if ($request->hasFile('image')) {
            $imagePath = $this->processImage($request->file('image'));
            $data['image_path'] = $imagePath;
        }
        
        $service = Service::create($data);
        
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }
    
    private function processImage($file)
    {
        // Generar nombre único
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Crear imagen con Intervention
        $image = Image::make($file);
        
        // Redimensionar si es muy grande
        if ($image->width() > 1200 || $image->height() > 800) {
            $image->resize(1200, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Optimizar calidad
        $image->encode('jpg', 85);
        
        // Guardar en storage
        $path = 'images/services/' . $fileName;
        Storage::disk('public')->put($path, $image);
        
        return $path;
    }
}
```

### 3. **Form Request para Validación**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=100,min_height=100',
        ];
    }

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

## ✅ Validación de Imágenes

### 1. **Validación Básica**
```php
// Validación en el controlador
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
]);
```

### 2. **Validación Avanzada**
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

### 3. **Validación Personalizada**
```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Intervention\Image\Facades\Image;

class ValidImage implements ValidationRule
{
    public function __construct(
        private int $maxWidth = 2000,
        private int $maxHeight = 2000,
        private int $minWidth = 100,
        private int $minHeight = 100
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !$value->isValid()) {
            $fail('El archivo de imagen no es válido.');
            return;
        }

        try {
            $image = Image::make($value);
            
            // Verificar dimensiones
            if ($image->width() < $this->minWidth || $image->height() < $this->minHeight) {
                $fail("La imagen debe tener al menos {$this->minWidth}x{$this->minHeight} píxeles.");
            }
            
            if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
                $fail("La imagen no debe superar {$this->maxWidth}x{$this->maxHeight} píxeles.");
            }
            
            // Verificar que sea una imagen real
            if (!$image->mime() || !in_array($image->mime(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                $fail('El archivo debe ser una imagen válida (JPEG, PNG).');
            }
            
        } catch (\Exception $e) {
            $fail('No se pudo procesar la imagen.');
        }
    }
}
```

### 4. **Validación con JavaScript**
```javascript
// resources/js/image-validation.js
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validar tamaño
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('La imagen no debe superar los 2MB.');
                this.value = '';
                return;
            }
            
            // Validar tipo
            if (!file.type.startsWith('image/')) {
                alert('El archivo debe ser una imagen.');
                this.value = '';
                return;
            }
            
            // Validar dimensiones
            const img = new Image();
            img.onload = function() {
                if (this.width < 100 || this.height < 100) {
                    alert('La imagen debe tener al menos 100x100 píxeles.');
                    imageInput.value = '';
                }
            };
            img.src = URL.createObjectURL(file);
        }
    });
});
```

## 🎨 Optimización de Imágenes

### 1. **Optimización Básica**
```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function optimize($file, $path, $options = [])
    {
        $defaults = [
            'width' => 800,
            'height' => 600,
            'quality' => 85,
            'format' => 'jpg'
        ];
        
        $options = array_merge($defaults, $options);
        
        $image = Image::make($file);
        
        // Redimensionar manteniendo proporción
        $image->resize($options['width'], $options['height'], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Optimizar calidad
        $image->encode($options['format'], $options['quality']);
        
        // Guardar
        $fileName = time() . '_' . uniqid() . '.' . $options['format'];
        $fullPath = $path . '/' . $fileName;
        
        Storage::disk('public')->put($fullPath, $image);
        
        return $fullPath;
    }
}
```

### 2. **Optimización con Múltiples Tamaños**
```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    public function createThumbnails($file, $basePath)
    {
        $sizes = [
            'thumbnail' => [150, 150],
            'small' => [300, 300],
            'medium' => [600, 600],
            'large' => [1200, 1200]
        ];
        
        $paths = [];
        
        foreach ($sizes as $size => $dimensions) {
            $image = Image::make($file);
            
            // Redimensionar
            $image->fit($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->upsize();
            });
            
            // Optimizar
            $image->encode('jpg', 85);
            
            // Guardar
            $fileName = time() . '_' . uniqid() . "_{$size}.jpg";
            $path = $basePath . '/' . $fileName;
            
            Storage::disk('public')->put($path, $image);
            $paths[$size] = $path;
        }
        
        return $paths;
    }
    
    public function createResponsiveImages($file, $basePath)
    {
        $sizes = [
            'xs' => [320, 240],
            'sm' => [640, 480],
            'md' => [768, 576],
            'lg' => [1024, 768],
            'xl' => [1280, 960]
        ];
        
        $paths = [];
        
        foreach ($sizes as $breakpoint => $dimensions) {
            $image = Image::make($file);
            
            // Redimensionar
            $image->resize($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Optimizar
            $image->encode('webp', 85);
            
            // Guardar
            $fileName = time() . '_' . uniqid() . "_{$breakpoint}.webp";
            $path = $basePath . '/' . $fileName;
            
            Storage::disk('public')->put($path, $image);
            $paths[$breakpoint] = $path;
        }
        
        return $paths;
    }
}
```

### 3. **Optimización con Watermark**
```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class WatermarkService
{
    public function addWatermark($imagePath, $watermarkPath = null)
    {
        $image = Image::make(storage_path('app/public/' . $imagePath));
        
        if (!$watermarkPath) {
            $watermarkPath = public_path('images/watermark.png');
        }
        
        if (file_exists($watermarkPath)) {
            $watermark = Image::make($watermarkPath);
            
            // Posicionar watermark en la esquina inferior derecha
            $image->insert($watermark, 'bottom-right', 10, 10);
        }
        
        // Guardar imagen con watermark
        $image->encode('jpg', 85);
        Storage::disk('public')->put($imagePath, $image);
        
        return $imagePath;
    }
}
```

## 💾 Almacenamiento

### 1. **Almacenamiento Local**
```php
// Guardar archivo
Storage::disk('local')->put('images/service.jpg', $fileContents);

// Guardar archivo público
Storage::disk('public')->put('images/service.jpg', $fileContents);

// Obtener URL
$url = Storage::disk('public')->url('images/service.jpg');
```

### 2. **Almacenamiento en S3**
```php
// Configurar S3 en .env
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
AWS_USE_PATH_STYLE_ENDPOINT=false

// Guardar en S3
Storage::disk('s3')->put('images/service.jpg', $fileContents);

// Obtener URL de S3
$url = Storage::disk('s3')->url('images/service.jpg');
```

### 3. **Servicio de Almacenamiento**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileStorageService
{
    public function storeImage(UploadedFile $file, string $path = 'images'): string
    {
        $fileName = $this->generateFileName($file);
        $fullPath = $path . '/' . $fileName;
        
        // Guardar archivo
        Storage::disk('public')->put($fullPath, file_get_contents($file));
        
        return $fullPath;
    }
    
    public function storeOptimizedImage(UploadedFile $file, string $path = 'images'): string
    {
        $image = Image::make($file);
        
        // Optimizar
        $image->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $image->encode('jpg', 85);
        
        $fileName = $this->generateFileName($file, 'jpg');
        $fullPath = $path . '/' . $fileName;
        
        Storage::disk('public')->put($fullPath, $image);
        
        return $fullPath;
    }
    
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
    
    private function generateFileName(UploadedFile $file, string $extension = null): string
    {
        $extension = $extension ?: $file->getClientOriginalExtension();
        return time() . '_' . uniqid() . '.' . $extension;
    }
}
```

## 🖼️ Mostrar Imágenes

### 1. **Helper para URLs de Imágenes**
```php
<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function url($path, $disk = 'public')
    {
        if (!$path) {
            return asset('images/placeholder.jpg');
        }
        
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        
        return asset('images/placeholder.jpg');
    }
    
    public static function thumbnail($path, $size = 'thumbnail')
    {
        if (!$path) {
            return asset('images/placeholder.jpg');
        }
        
        // Buscar thumbnail si existe
        $thumbnailPath = str_replace('.jpg', "_{$size}.jpg", $path);
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }
        
        return self::url($path);
    }
}
```

### 2. **Componente Blade para Imágenes**
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

### 3. **Uso en Vistas**
```php
{{-- Mostrar imagen básica --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" class="w-full h-64 object-cover rounded-lg" />

{{-- Mostrar imagen con lazy loading --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" lazy class="w-full h-64 object-cover rounded-lg" />

{{-- Mostrar imagen responsive --}}
<x-ui.image src="{{ $service->image_path }}" alt="{{ $service->name }}" responsive class="w-full h-64 object-cover rounded-lg" />
```

## 📝 Comandos Útiles

```bash
# Crear enlace simbólico
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

## 🎯 Resumen

La gestión de archivos en Laravel proporciona:
- ✅ Subida segura de archivos
- ✅ Validación robusta de imágenes
- ✅ Optimización automática
- ✅ Múltiples drivers de almacenamiento
- ✅ Generación de thumbnails
- ✅ Soporte para imágenes responsive
- ✅ Integración con S3 y otros servicios

**Próximo paso:** Testing y optimización 