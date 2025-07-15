# 📝 Form Requests y Validación en Laravel 12

## 🎯 **¿Qué son los Form Requests?**

Los Form Requests en Laravel son clases especiales que encapsulan la lógica de validación, autorización y sanitización de datos. Permiten mantener los controladores limpios y reutilizar reglas de validación. Son como "guardianes" que verifican que los datos que llegan a tu aplicación sean correctos y seguros.

**¿Por qué necesitas Form Requests?**
- **Seguridad**: Previenen datos maliciosos o incorrectos de entrar a tu aplicación
- **Organización**: Mantienen la validación separada de la lógica de negocio
- **Reutilización**: Puedes usar las mismas reglas de validación en múltiples lugares
- **Mantenibilidad**: Código más limpio y fácil de mantener
- **Experiencia de usuario**: Proporcionan mensajes de error claros y útiles

### 🎯 **Características Principales**

**Validación centralizada**: Todas las reglas de validación están en un solo lugar, no dispersas en controladores. Es como tener un "manual de reglas" para cada tipo de formulario.

**Autorización integrada**: Puedes verificar si el usuario tiene permisos para realizar la acción. Es como tener un "portero" que verifica credenciales antes de permitir el acceso.

**Mensajes personalizados**: Puedes definir mensajes de error en español y específicos para tu aplicación. Los usuarios entienden mejor qué salió mal.

**Sanitización automática**: Limpia y formatea los datos antes de procesarlos. Previene inyección de código malicioso.

**Validación condicional**: Las reglas pueden cambiar según el contexto o los datos enviados. Útil para formularios complejos.

## 📁 **Estructura de Form Requests**

### 🎯 **Ubicación y Organización**

Los Form Requests se organizan en la carpeta `app/Http/Requests/` siguiendo convenciones de nombres:

```
app/Http/Requests/
├── StoreServiceRequest.php      # Validación para crear servicios
├── UpdateServiceRequest.php     # Validación para actualizar servicios
├── StoreUserRequest.php         # Validación para crear usuarios
├── UpdateUserRequest.php        # Validación para actualizar usuarios
└── Api/                        # Subcarpeta para validaciones de API
    ├── ServiceApiRequest.php    # Validación para API de servicios
    └── UserApiRequest.php       # Validación para API de usuarios
```

**Explicación de las convenciones:**

**Store*Request**: Para validar datos al crear nuevos recursos. Por ejemplo, `StoreServiceRequest` valida los datos cuando se crea un nuevo servicio.

**Update*Request**: Para validar datos al actualizar recursos existentes. Por ejemplo, `UpdateServiceRequest` valida los datos cuando se edita un servicio existente.

**Api/**: Subcarpeta para validaciones específicas de APIs. Las APIs pueden tener reglas diferentes a las aplicaciones web.

**Nombres descriptivos**: Los nombres indican claramente qué validan. Facilita encontrar el Form Request correcto.

## 🚀 **Crear Form Request**

### 🎯 **Comando Artisan**

Los comandos para crear Form Requests son simples y siguen convenciones:

```bash
php artisan make:request StoreServiceRequest
php artisan make:request UpdateServiceRequest
php artisan make:request Api/ServiceApiRequest
```

**Explicación de cada comando:**

**make:request StoreServiceRequest**: Crea un Form Request para validar datos al crear nuevos servicios.

**make:request UpdateServiceRequest**: Crea un Form Request para validar datos al actualizar servicios existentes.

**make:request Api/ServiceApiRequest**: Crea un Form Request específico para APIs de servicios.

### 🏗️ **Estructura Básica**

Un Form Request completo incluye validación, autorización, mensajes personalizados y manejo de errores:

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
     * Verifica si el usuario tiene permisos para crear servicios
     * 
     * @return bool True si el usuario está autorizado, false si no
     */
    public function authorize(): bool
    {
        return true; // O lógica de autorización más compleja
    }

    /**
     * Get the validation rules that apply to the request.
     * Define las reglas de validación para cada campo
     * 
     * @return array Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name', // Nombre obligatorio, único
            'description' => 'required|string|min:10', // Descripción obligatoria, mínimo 10 caracteres
            'price' => 'required|numeric|min:0|max:999999.99', // Precio obligatorio, numérico, rango válido
            'category_id' => 'required|exists:categories,id', // Categoría obligatoria, debe existir
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Imagen opcional, tipos específicos
            'tags' => 'nullable|array', // Tags opcional, debe ser array
            'tags.*' => 'string|max:50', // Cada tag debe ser string, máximo 50 caracteres
            'is_active' => 'boolean' // Campo booleano opcional
        ];
    }

    /**
     * Get custom messages for validator errors.
     * Mensajes personalizados en español para errores de validación
     * 
     * @return array Array con mensajes personalizados
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
     * Nombres personalizados para los campos en mensajes de error
     * 
     * @return array Array con nombres personalizados de campos
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
     * Validación personalizada adicional después de las reglas básicas
     * 
     * @param Validator $validator Instancia del validador
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
     * Maneja errores de validación para APIs y web
     * 
     * @param Validator $validator Instancia del validador con errores
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            // Para APIs: devuelve JSON con errores
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        // Para web: redirige con errores (comportamiento por defecto)
        parent::failedValidation($validator);
    }
}
```

**Explicación detallada de cada método:**

**authorize()**: Verifica si el usuario tiene permisos para realizar la acción. Retorna `true` si está autorizado, `false` si no. Es como un "portero" que verifica credenciales.

**rules()**: Define las reglas de validación para cada campo. Cada regla tiene un propósito específico (required, string, max, etc.). Es como definir las "reglas del juego" para los datos.

**messages()**: Personaliza los mensajes de error que verá el usuario. En lugar de mensajes genéricos en inglés, puedes mostrar mensajes claros en español.

**attributes()**: Define nombres amigables para los campos. En lugar de mostrar "name" en el error, muestra "nombre del servicio".

**withValidator()**: Agrega validación personalizada después de las reglas básicas. Útil para lógica compleja que no se puede expresar con reglas simples.

**failedValidation()**: Maneja los errores de validación de manera diferente según el contexto (API vs web). Las APIs devuelven JSON, las aplicaciones web redirigen.

## 🔧 **Reglas de Validación Básicas**

### 📝 **Validación de Texto**

```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',           // Nombre obligatorio, texto, máximo 255 caracteres
        'description' => 'required|string|min:10|max:1000', // Descripción obligatoria, entre 10 y 1000 caracteres
        'email' => 'required|email|unique:users,email', // Email obligatorio, formato válido, único en la tabla
        'phone' => 'nullable|regex:/^[0-9]{10}$/',    // Teléfono opcional, formato específico (10 dígitos)
        'website' => 'nullable|url',                   // Sitio web opcional, formato URL válido
        'bio' => 'nullable|string|max:500'             // Biografía opcional, texto, máximo 500 caracteres
    ];
}
```

**Explicación de cada regla de texto:**

**required**: El campo es obligatorio. Si no se proporciona, la validación fallará.

**string**: El valor debe ser una cadena de texto. No acepta números, arrays, etc.

**max:255**: La longitud máxima es 255 caracteres. Útil para evitar textos muy largos.

**min:10**: La longitud mínima es 10 caracteres. Útil para asegurar contenido significativo.

**email**: Verifica que el formato sea un email válido (contiene @, dominio, etc.).

**unique:users,email**: El valor debe ser único en la tabla 'users' en la columna 'email'.

**nullable**: El campo es opcional. Puede estar vacío o no enviarse.

**regex:/^[0-9]{10}$/**: Usa una expresión regular para validar formato específico (10 dígitos).

**url**: Verifica que el formato sea una URL válida.

### 🔢 **Validación Numérica**

```php
public function rules(): array
{
    return [
        'price' => 'required|numeric|min:0|max:999999.99', // Precio obligatorio, numérico, entre 0 y 999999.99
        'quantity' => 'required|integer|min:1|max:1000',   // Cantidad obligatoria, entero, entre 1 y 1000
        'rating' => 'nullable|numeric|between:1,5',        // Rating opcional, numérico, entre 1 y 5
        'discount' => 'nullable|numeric|min:0|max:100',    // Descuento opcional, entre 0% y 100%
        'weight' => 'nullable|numeric|min:0.01'            // Peso opcional, mínimo 0.01
    ];
}
```

**Explicación de cada regla numérica:**

**numeric**: El valor debe ser un número (entero o decimal).

**integer**: El valor debe ser un número entero (sin decimales).

**min:0**: El valor mínimo es 0. Útil para precios, cantidades, etc.

**max:999999.99**: El valor máximo es 999999.99. Previene valores absurdamente altos.

**between:1,5**: El valor debe estar entre 1 y 5 (inclusive). Útil para ratings.

**min:0.01**: El valor mínimo es 0.01. Útil para pesos, medidas, etc.

### 📅 **Validación de Fechas**

```php
public function rules(): array
{
    return [
        'birth_date' => 'required|date|before:today',           // Fecha de nacimiento, debe ser anterior a hoy
        'expiry_date' => 'required|date|after:today',           // Fecha de expiración, debe ser posterior a hoy
        'start_date' => 'required|date|after_or_equal:today',   // Fecha de inicio, puede ser hoy o después
        'end_date' => 'required|date|after:start_date',         // Fecha de fin, debe ser después de la fecha de inicio
        'published_at' => 'nullable|date'                       // Fecha de publicación opcional
    ];
}
```

**Explicación de cada regla de fecha:**

**date**: El valor debe ser una fecha válida en formato Y-m-d, d/m/Y, etc.

**before:today**: La fecha debe ser anterior a hoy. Útil para fechas de nacimiento.

**after:today**: La fecha debe ser posterior a hoy. Útil para fechas de expiración.

**after_or_equal:today**: La fecha puede ser hoy o después. Útil para fechas de inicio.

**after:start_date**: La fecha debe ser después de otro campo. Útil para rangos de fechas.

### 📁 **Validación de Archivos**

```php
public function rules(): array
{
    return [
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Imagen opcional, tipos específicos, máximo 2MB
        'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // Documento opcional, tipos específicos, máximo 5MB
        'video' => 'nullable|file|mimes:mp4,avi,mov|max:10240',   // Video opcional, tipos específicos, máximo 10MB
        'logo' => 'nullable|image|dimensions:min_width=100,min_height=100' // Logo con dimensiones mínimas
    ];
}
```

**Explicación de cada regla de archivo:**

**image**: El archivo debe ser una imagen (jpeg, png, gif, etc.).

**file**: El archivo puede ser de cualquier tipo (documentos, videos, etc.).

**mimes:jpeg,png,jpg**: Solo acepta estos tipos de archivo específicos.

**max:2048**: El tamaño máximo es 2048 kilobytes (2MB).

**dimensions:min_width=100,min_height=100**: La imagen debe tener dimensiones mínimas.

### 📋 **Validación de Arrays**

```php
public function rules(): array
{
    return [
        'tags' => 'nullable|array|max:10',                    // Tags opcional, array, máximo 10 elementos
        'tags.*' => 'string|max:50',                          // Cada tag debe ser string, máximo 50 caracteres
        'colors' => 'nullable|array',                         // Colores opcional, array
        'colors.*' => 'string|in:red,blue,green,yellow',     // Cada color debe estar en la lista permitida
        'sizes' => 'nullable|array',                          // Tallas opcional, array
        'sizes.*' => 'string|in:XS,S,M,L,XL,XXL'             // Cada talla debe estar en la lista permitida
    ];
}
```

**Explicación de cada regla de array:**

**array**: El valor debe ser un array (lista de elementos).

**max:10**: El array puede tener máximo 10 elementos.

**tags.***: La regla se aplica a cada elemento del array 'tags'.

**in:red,blue,green,yellow**: El valor debe estar en la lista especificada.

## 🎯 **Reglas Personalizadas**

### 📝 **1. Reglas Personalizadas con Closures**

Las closures te permiten crear validación personalizada inline:

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

**Explicación de las closures:**

**function ($attribute, $value, $fail)**: Closure que recibe el nombre del campo, su valor y una función para agregar errores.

**$attribute**: Nombre del campo que se está validando (ej: 'email').

**$value**: Valor del campo que se está validando.

**$fail()**: Función para agregar un mensaje de error si la validación falla.

**str_contains()**: Verifica si el email contiene '@company.com'.

**preg_match()**: Usa expresión regular para validar formato específico.

### 🏗️ **2. Reglas Personalizadas con Clases**

Para validación compleja, puedes crear clases de reglas personalizadas:

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
    /**
     * Valida que el número de teléfono tenga el formato correcto
     * 
     * @param string $attribute Nombre del campo
     * @param mixed $value Valor del campo
     * @param Closure $fail Función para agregar errores
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $fail('El número de teléfono debe tener exactamente 10 dígitos.');
        }
    }
}
```

**Uso en el Form Request:**

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

**Explicación de las clases de reglas:**

**ValidationRule**: Interfaz que define el contrato para reglas personalizadas.

**validate()**: Método que contiene la lógica de validación personalizada.

**new ValidPhoneNumber**: Instancia de la regla personalizada. Laravel la ejecutará automáticamente.

### 🔄 **3. Reglas Condicionales**

Las reglas pueden cambiar según el contexto o los datos enviados:

```php
public function rules(): array
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8'
    ];

    // Reglas condicionales según el método HTTP
    if ($this->isMethod('PUT')) {
        $rules['email'] = 'required|email|unique:users,email,' . $this->user->id;
        $rules['password'] = 'nullable|string|min:8';
    }

    // Reglas condicionales según campos enviados
    if ($this->has('newsletter')) {
        $rules['newsletter'] = 'boolean';
    }

    return $rules;
}
```

**Explicación de las reglas condicionales:**

**$this->isMethod('PUT')**: Verifica si la petición es PUT (actualización).

**unique:users,email,' . $this->user->id**: Excluye el usuario actual de la validación unique. Útil para actualizaciones.

**$this->has('newsletter')**: Verifica si se envió el campo 'newsletter'.

**nullable**: Hace el campo opcional en lugar de requerido.

## 📝 **Mensajes de Error Personalizados**

### 📋 **Mensajes Básicos**

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

**Explicación de los mensajes:**

**'name.required'**: Mensaje específico para cuando el campo 'name' no cumple la regla 'required'.

**'email.unique'**: Mensaje específico para cuando el email ya existe en la base de datos.

**'price.min'**: Mensaje específico para cuando el precio es menor al mínimo permitido.

### 🏷️ **Mensajes con Atributos Personalizados**

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

**Explicación de los atributos:**

**'name' => 'nombre del servicio'**: En lugar de mostrar "name" en el error, muestra "nombre del servicio".

**'category_id' => 'categoría'**: En lugar de mostrar "category_id", muestra "categoría".

### 🔄 **Mensajes Dinámicos**

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

**Explicación de los placeholders:**

**:attribute**: Se reemplaza con el nombre personalizado del campo.

**:min**: Se reemplaza con el valor mínimo permitido.

**:max**: Se reemplaza con el valor máximo permitido.

## 🔍 **Validación Avanzada**

### 🗄️ **1. Validación con Base de Datos**

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

**Explicación de cada regla de base de datos:**

**unique:users,email,' . $this->user?->id**: Verifica que el email sea único, excluyendo el usuario actual (útil para actualizaciones).

**exists:categories,id**: Verifica que el category_id exista en la tabla categories.

**exists:services,id,active,1**: Verifica que el service_id exista y que el campo 'active' sea 1.

**exists:users,id,role,customer**: Verifica que el user_id exista y que el campo 'role' sea 'customer'.

### 📋 **2. Validación de Arrays Complejos**

```php
public function rules(): array
{
    return [
        'services' => 'required|array|min:1',                    // Array de servicios, mínimo 1 elemento
        'services.*.id' => 'required|exists:services,id',        // ID de cada servicio debe existir
        'services.*.quantity' => 'required|integer|min:1',       // Cantidad de cada servicio
        'services.*.price' => 'required|numeric|min:0',          // Precio de cada servicio
        'customer' => 'required|array',                          // Array con datos del cliente
        'customer.name' => 'required|string|max:255',            // Nombre del cliente
        'customer.email' => 'required|email',                    // Email del cliente
        'customer.phone' => 'nullable|string|max:20'             // Teléfono del cliente (opcional)
    ];
}
```

**Explicación de la validación de arrays:**

**services**: Array que contiene múltiples servicios.

**services.*.id**: La regla se aplica al campo 'id' de cada elemento del array 'services'.

**customer.name**: Valida el campo 'name' dentro del array 'customer'.

### 🔄 **3. Validación Condicional Compleja**

```php
public function rules(): array
{
    $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:basic,premium,vip'
    ];

    // Reglas según el tipo de servicio
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

**Explicación de la validación condicional:**

**switch ($this->type)**: Cambia las reglas según el valor del campo 'type'.

**case 'premium'**: Si el tipo es premium, agrega reglas específicas para features.

**case 'vip'**: Si el tipo es vip, agrega reglas específicas para priority y support_level.

## ⚡ **Validación en Tiempo Real**

### 🎯 **1. Validación con JavaScript**

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

**Explicación de la validación JavaScript:**

**addEventListener('blur')**: Se ejecuta cuando el usuario sale del campo (pierde el foco).

**addEventListener('input')**: Se ejecuta cada vez que el usuario escribe en el campo.

**showError()**: Muestra el mensaje de error debajo del campo.

**clearError()**: Elimina el mensaje de error cuando el campo es válido.

### 🌐 **2. Validación con AJAX**

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

**Explicación de la validación AJAX:**

**fetch()**: Hace una petición HTTP al servidor para validar el email.

**X-CSRF-TOKEN**: Token de seguridad para prevenir ataques CSRF.

**data.valid**: Respuesta del servidor indicando si el email es válido.

### 🛣️ **3. Ruta de Validación**

```php
// routes/web.php
Route::post('/api/validate-email', function (Request $request) {
    $email = $request->email;
    $userId = $request->user_id;

    $query = User::where('email', $email);
    
    if ($userId) {
        $query->where('id', '!=', $userId); // Excluye el usuario actual en edición
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists // true si el email es único, false si ya existe
    ]);
});
```

**Explicación de la ruta de validación:**

**User::where('email', $email)**: Busca usuarios con el email proporcionado.

**$query->where('id', '!=', $userId)**: Excluye el usuario actual en caso de edición.

**$exists**: Verifica si existe algún usuario con ese email.

**'valid' => !$exists**: Devuelve true si el email es único, false si ya existe.

## 🎯 **Uso en Controladores**

### 📋 **Controlador con Form Request**

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Crear un nuevo servicio
     * 
     * @param StoreServiceRequest $request Datos validados del formulario
     * @return RedirectResponse Redirección después de crear
     */
    public function store(StoreServiceRequest $request)
    {
        // Los datos ya están validados automáticamente
        $service = Service::create($request->validated());
        
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente');
    }

    /**
     * Actualizar un servicio existente
     * 
     * @param UpdateServiceRequest $request Datos validados del formulario
     * @param Service $service Servicio a actualizar
     * @return RedirectResponse Redirección después de actualizar
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        
        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente');
    }
}
```

**Explicación del controlador:**

**StoreServiceRequest $request**: Laravel automáticamente valida los datos usando el Form Request antes de ejecutar el método.

**$request->validated()**: Obtiene solo los datos que pasaron la validación. Más seguro que `$request->all()`.

**No necesitas validar manualmente**: El Form Request se encarga de toda la validación automáticamente.

### 📡 **API Controller con Form Request**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceApiRequest;
use App\Models\Service;
use App\Http\Resources\ServiceResource;

class ServiceApiController extends Controller
{
    /**
     * Crear un nuevo servicio via API
     * 
     * @param ServiceApiRequest $request Datos validados
     * @return JsonResponse Respuesta JSON
     */
    public function store(ServiceApiRequest $request)
    {
        $service = Service::create($request->validated());
        
        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'data' => new ServiceResource($service)
        ], 201);
    }

    /**
     * Actualizar un servicio via API
     * 
     * @param ServiceApiRequest $request Datos validados
     * @param Service $service Servicio a actualizar
     * @return JsonResponse Respuesta JSON
     */
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

**Explicación del API Controller:**

**ServiceApiRequest**: Form Request específico para APIs con reglas y mensajes optimizados para JSON.

**201**: Código HTTP para "Created" cuando se crea un nuevo recurso.

**ServiceResource**: Clase que formatea los datos del servicio para la respuesta JSON.

## 🔧 **Configuración Avanzada**

### 🔐 **1. Form Request con Autorización**

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

**Explicación de la autorización:**

**$this->user()->can('create', Service::class)**: Verifica si el usuario tiene permiso para crear servicios usando políticas de Laravel.

**$this->route('service')**: Obtiene el servicio de la ruta (útil para actualizaciones).

**$this->user()->can('update', $service)**: Verifica si el usuario puede actualizar el servicio específico.

### 🧹 **2. Sanitización de Datos**

```php
protected function prepareForValidation(): void
{
    $this->merge([
        'name' => strip_tags($this->name),           // Elimina HTML del nombre
        'description' => strip_tags($this->description), // Elimina HTML de la descripción
        'price' => (float) $this->price,             // Convierte a float
        'is_active' => (bool) $this->is_active       // Convierte a boolean
    ]);
}
```

**Explicación de la sanitización:**

**strip_tags()**: Elimina etiquetas HTML para prevenir inyección de código.

**(float)**: Convierte el precio a número decimal.

**(bool)**: Convierte el campo a booleano (true/false).

**merge()**: Combina los datos sanitizados con los datos originales.

### 🔍 **3. Validación Personalizada con After Hook**

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

**Explicación de la validación personalizada:**

**$validator->after()**: Ejecuta validación adicional después de las reglas básicas.

**$this->price > 10000**: Validación personalizada basada en múltiples campos.

**$this->has('start_date')**: Verifica si se envió el campo start_date.

**$validator->errors()->add()**: Agrega un error personalizado al validador.

## 📝 **Comandos Útiles**

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

**Explicación de cada comando:**

**make:request StoreServiceRequest**: Crea un Form Request para validar datos al crear servicios.

**make:request Api/ServiceApiRequest**: Crea un Form Request específico para APIs.

**make:rule ValidPhoneNumber**: Crea una regla personalizada para validar números de teléfono.

**make:rule ValidCompanyEmail**: Crea una regla personalizada para validar emails corporativos.

## 🎯 **Resumen**

Los Form Requests en Laravel proporcionan:

- ✅ **Validación centralizada y reutilizable**: Todas las reglas en un solo lugar
- ✅ **Mensajes de error personalizados**: Mensajes claros en español
- ✅ **Autorización integrada**: Verificación de permisos automática
- ✅ **Sanitización de datos**: Limpieza automática de datos de entrada
- ✅ **Validación condicional**: Reglas que cambian según el contexto
- ✅ **Mejor organización del código**: Controladores más limpios y mantenibles

**Próximo paso:** Middleware 