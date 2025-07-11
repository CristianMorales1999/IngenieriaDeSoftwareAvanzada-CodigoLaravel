# 📝 Form Requests y Validación en Laravel 12

## 🎯 Introducción

Los Form Requests en Laravel son clases especiales que encapsulan la lógica de validación, autorización y sanitización de datos. Permiten mantener los controladores limpios y reutilizar reglas de validación.

## 📁 Estructura de Form Requests

### Ubicación
```
app/Http/Requests/
├── StoreServiceRequest.php
├── UpdateServiceRequest.php
├── StoreUserRequest.php
├── UpdateUserRequest.php
└── Api/
    ├── ServiceApiRequest.php
    └── UserApiRequest.php
```

## 🚀 Crear Form Request

### Comando Artisan
```bash
php artisan make:request StoreServiceRequest
php artisan make:request UpdateServiceRequest
php artisan make:request Api/ServiceApiRequest
```

### Estructura Básica
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // O lógica de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string|min:10',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.unique' => 'Ya existe un servicio con este nombre.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'image.max' => 'La imagen no debe superar los 2MB.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre del servicio',
            'description' => 'descripción',
            'price' => 'precio',
            'category_id' => 'categoría',
            'image' => 'imagen'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->price > 10000) {
                $validator->errors()->add('price', 'El precio no puede superar $10,000.');
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
```

## 🔧 Reglas de Validación Básicas

### Validación de Texto
```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'required|string|min:10|max:1000',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|regex:/^[0-9]{10}$/',
        'website' => 'nullable|url',
        'bio' => 'nullable|string|max:500'
    ];
}
```

### Validación Numérica
```php
public function rules(): array
{
    return [
        'price' => 'required|numeric|min:0|max:999999.99',
        'quantity' => 'required|integer|min:1|max:1000',
        'rating' => 'nullable|numeric|between:1,5',
        'discount' => 'nullable|numeric|min:0|max:100',
        'weight' => 'nullable|numeric|min:0.01'
    ];
}
```

### Validación de Fechas
```php
public function rules(): array
{
    return [
        'birth_date' => 'required|date|before:today',
        'expiry_date' => 'required|date|after:today',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'published_at' => 'nullable|date'
    ];
}
```

### Validación de Archivos
```php
public function rules(): array
{
    return [
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'video' => 'nullable|file|mimes:mp4,avi,mov|max:10240',
        'logo' => 'nullable|image|dimensions:min_width=100,min_height=100'
    ];
}
```

### Validación de Arrays
```php
public function rules(): array
{
    return [
        'tags' => 'nullable|array|max:10',
        'tags.*' => 'string|max:50',
        'colors' => 'nullable|array',
        'colors.*' => 'string|in:red,blue,green,yellow',
        'sizes' => 'nullable|array',
        'sizes.*' => 'string|in:XS,S,M,L,XL,XXL'
    ];
}
```

## 🎯 Reglas Personalizadas

### 1. **Reglas Personalizadas con Closures**
```php
public function rules(): array
{
    return [
        'email' => [
            'required',
            'email',
            function ($attribute, $value, $fail) {
                if (!str_contains($value, '@company.com')) {
                    $fail('El email debe ser del dominio @company.com');
                }
            }
        ],
        'phone' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^[0-9]{10}$/', $value)) {
                    $fail('El teléfono debe tener 10 dígitos.');
                }
            }
        ]
    ];
}
```

### 2. **Reglas Personalizadas con Clases**
```bash
php artisan make:rule ValidPhoneNumber
```

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $fail('El número de teléfono debe tener exactamente 10 dígitos.');
        }
    }
}
```

```php
// En el Form Request
use App\Rules\ValidPhoneNumber;

public function rules(): array
{
    return [
        'phone' => ['required', new ValidPhoneNumber],
        'email' => ['required', 'email', new ValidCompanyEmail]
    ];
}
```

### 3. **Reglas Condicionales**
```php
public function rules(): array
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8'
    ];

    // Reglas condicionales
    if ($this->isMethod('PUT')) {
        $rules['email'] = 'required|email|unique:users,email,' . $this->user->id;
        $rules['password'] = 'nullable|string|min:8';
    }

    if ($this->has('newsletter')) {
        $rules['newsletter'] = 'boolean';
    }

    return $rules;
}
```

## 📝 Mensajes de Error Personalizados

### Mensajes Básicos
```php
public function messages(): array
{
    return [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El formato del email no es válido.',
        'email.unique' => 'Este email ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'price.numeric' => 'El precio debe ser un número.',
        'price.min' => 'El precio debe ser mayor a 0.',
        'category_id.exists' => 'La categoría seleccionada no existe.',
        'image.image' => 'El archivo debe ser una imagen.',
        'image.max' => 'La imagen no debe superar los 2MB.'
    ];
}
```

### Mensajes con Atributos Personalizados
```php
public function attributes(): array
{
    return [
        'name' => 'nombre del servicio',
        'description' => 'descripción del servicio',
        'price' => 'precio del servicio',
        'category_id' => 'categoría',
        'image' => 'imagen del servicio',
        'tags' => 'etiquetas',
        'is_active' => 'estado activo'
    ];
}
```

### Mensajes Dinámicos
```php
public function messages(): array
{
    return [
        'name.required' => 'El campo :attribute es obligatorio.',
        'email.unique' => 'El :attribute ya está en uso.',
        'price.min' => 'El :attribute debe ser al menos :min.',
        'image.max' => 'El :attribute no debe ser mayor a :max kilobytes.',
        'category_id.exists' => 'La :attribute seleccionada no es válida.'
    ];
}
```

## 🔍 Validación Avanzada

### 1. **Validación con Base de Datos**
```php
public function rules(): array
{
    return [
        'email' => 'required|email|unique:users,email,' . $this->user?->id,
        'category_id' => 'required|exists:categories,id',
        'service_id' => 'required|exists:services,id,active,1',
        'user_id' => 'required|exists:users,id,role,customer'
    ];
}
```

### 2. **Validación de Arrays Complejos**
```php
public function rules(): array
{
    return [
        'services' => 'required|array|min:1',
        'services.*.id' => 'required|exists:services,id',
        'services.*.quantity' => 'required|integer|min:1',
        'services.*.price' => 'required|numeric|min:0',
        'customer' => 'required|array',
        'customer.name' => 'required|string|max:255',
        'customer.email' => 'required|email',
        'customer.phone' => 'nullable|string|max:20'
    ];
}
```

### 3. **Validación Condicional Compleja**
```php
public function rules(): array
{
    $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:basic,premium,vip'
    ];

    // Reglas según el tipo
    switch ($this->type) {
        case 'premium':
            $rules['features'] = 'required|array|min:1';
            $rules['features.*'] = 'string|in:feature1,feature2,feature3';
            break;
        case 'vip':
            $rules['priority'] = 'required|boolean';
            $rules['support_level'] = 'required|in:basic,premium,dedicated';
            break;
    }

    return $rules;
}
```

## ⚡ Validación en Tiempo Real

### 1. **Validación con JavaScript**
```javascript
// resources/js/validation.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('service-form');
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');

    // Validación en tiempo real del nombre
    nameInput.addEventListener('blur', function() {
        const name = this.value;
        if (name.length < 3) {
            showError(this, 'El nombre debe tener al menos 3 caracteres');
        } else {
            clearError(this);
        }
    });

    // Validación en tiempo real del precio
    priceInput.addEventListener('input', function() {
        const price = parseFloat(this.value);
        if (price < 0) {
            showError(this, 'El precio debe ser mayor a 0');
        } else {
            clearError(this);
        }
    });

    function showError(input, message) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
        } else {
            const div = document.createElement('div');
            div.className = 'error-message text-red-500 text-sm mt-1';
            div.textContent = message;
            input.parentNode.appendChild(div);
        }
        input.classList.add('border-red-500');
    }

    function clearError(input) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('border-red-500');
    }
});
```

### 2. **Validación con AJAX**
```javascript
// Validación de email único
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const userId = this.dataset.userId; // Para edición

    fetch('/api/validate-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email, user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.valid) {
            showError(this, 'Este email ya está registrado');
        } else {
            clearError(this);
        }
    });
});
```

### 3. **Ruta de Validación**
```php
// routes/web.php
Route::post('/api/validate-email', function (Request $request) {
    $email = $request->email;
    $userId = $request->user_id;

    $query = User::where('email', $email);
    
    if ($userId) {
        $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);
});
```

## 🎯 Uso en Controladores

### Controlador con Form Request
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    public function store(StoreServiceRequest $request)
    {
        // Los datos ya están validados
        $service = Service::create($request->validated());
        
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente');
    }
}
```

### API Controller con Form Request
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceApiRequest;
use App\Models\Service;
use App\Http\Resources\ServiceResource;

class ServiceApiController extends Controller
{
    public function store(ServiceApiRequest $request)
    {
        $service = Service::create($request->validated());
        
        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'data' => new ServiceResource($service)
        ], 201);
    }

    public function update(ServiceApiRequest $request, Service $service)
    {
        $service->update($request->validated());
        
        return response()->json([
            'message' => 'Servicio actualizado exitosamente',
            'data' => new ServiceResource($service)
        ]);
    }
}
```

## 🔧 Configuración Avanzada

### 1. **Form Request con Autorización**
```php
public function authorize(): bool
{
    // Verificar si el usuario puede crear servicios
    return $this->user()->can('create', Service::class);
}

// O con lógica más compleja
public function authorize(): bool
{
    $service = $this->route('service');
    
    if ($service) {
        return $this->user()->can('update', $service);
    }
    
    return $this->user()->can('create', Service::class);
}
```

### 2. **Sanitización de Datos**
```php
protected function prepareForValidation(): void
{
    $this->merge([
        'name' => strip_tags($this->name),
        'description' => strip_tags($this->description),
        'price' => (float) $this->price,
        'is_active' => (bool) $this->is_active
    ]);
}
```

### 3. **Validación Personalizada con After Hook**
```php
public function withValidator(Validator $validator): void
{
    $validator->after(function ($validator) {
        // Validación personalizada después de las reglas básicas
        if ($this->price > 10000 && $this->type === 'basic') {
            $validator->errors()->add('price', 'Los servicios básicos no pueden costar más de $10,000.');
        }

        // Validación de disponibilidad
        if ($this->has('start_date') && $this->has('end_date')) {
            if ($this->start_date >= $this->end_date) {
                $validator->errors()->add('end_date', 'La fecha de fin debe ser posterior a la fecha de inicio.');
            }
        }
    });
}
```

## 📝 Comandos Útiles

```bash
# Crear Form Request básico
php artisan make:request StoreServiceRequest

# Crear Form Request para API
php artisan make:request Api/ServiceApiRequest

# Crear regla personalizada
php artisan make:rule ValidPhoneNumber

# Crear regla de validación
php artisan make:rule ValidCompanyEmail
```

## 🎯 Resumen

Los Form Requests en Laravel proporcionan:
- ✅ Validación centralizada y reutilizable
- ✅ Mensajes de error personalizados
- ✅ Autorización integrada
- ✅ Sanitización de datos
- ✅ Validación condicional
- ✅ Mejor organización del código

**Próximo paso:** Middleware 