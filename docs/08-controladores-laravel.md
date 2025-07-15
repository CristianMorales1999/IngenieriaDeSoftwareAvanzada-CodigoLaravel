# 📋 Controladores en Laravel 12

## 🎯 **¿Qué son los Controladores?**

Los controladores en Laravel son la capa que maneja la lógica de negocio entre las rutas y los modelos. Actúan como intermediarios que procesan las peticiones HTTP y devuelven las respuestas apropiadas. Son el "cerebro" de tu aplicación que decide qué hacer con cada petición del usuario.

**¿Por qué necesitas controladores?**
- **Organización**: Mantienen el código organizado y fácil de encontrar
- **Reutilización**: Puedes reutilizar lógica en múltiples rutas
- **Separación de responsabilidades**: Cada controlador maneja una funcionalidad específica
- **Mantenibilidad**: Código más limpio y fácil de mantener
- **Testing**: Facilita las pruebas unitarias de tu lógica de negocio

### 🎯 **Características Principales**

**Procesamiento de peticiones**: Reciben peticiones HTTP y las procesan según la lógica de tu aplicación. Es como tener un "portero" que decide qué hacer con cada visita.

**Lógica de negocio**: Contienen la lógica específica de tu aplicación (validaciones, cálculos, decisiones). Es donde implementas las reglas de tu negocio.

**Respuestas**: Devuelven respuestas apropiadas (vistas, JSON, redirecciones). Pueden devolver diferentes tipos de respuesta según la petición.

**Inyección de dependencias**: Laravel inyecta automáticamente las dependencias que necesitas. No necesitas crear manualmente las instancias de las clases.

**Route Model Binding**: Laravel automáticamente inyecta modelos basándose en los parámetros de la ruta. Por ejemplo, si tu ruta es `/services/{service}`, Laravel automáticamente busca el servicio y te lo pasa.

## 📁 **Estructura de Controladores**

### 🎯 **Ubicación y Organización**

Los controladores se organizan en la carpeta `app/Http/Controllers/` siguiendo una estructura lógica:

```
app/Http/Controllers/
├── Controller.php (Base)           # Controlador base con funcionalidades comunes
├── UserController.php              # Controlador para usuarios
├── ServiceController.php           # Controlador para servicios
├── Admin/                         # Subcarpeta para controladores de administración
│   ├── DashboardController.php     # Panel de control del admin
│   └── UserManagementController.php # Gestión de usuarios por admin
└── Api/                           # Subcarpeta para controladores de API
    ├── ServiceApiController.php    # API para servicios
    └── UserApiController.php       # API para usuarios
```

**Explicación de la organización:**

**Controller.php**: Clase base que otros controladores extienden. Contiene funcionalidades comunes como autenticación, autorización, etc.

**Subcarpetas**: Para organizar controladores por funcionalidad (Admin, Api, etc.). Ayuda a mantener el código organizado cuando tienes muchos controladores.

**Convención de nombres**: `NombreController.php` (PascalCase). Laravel usa esta convención para encontrar automáticamente los controladores.

**Separación de responsabilidades**: Controladores web vs API. Los controladores web devuelven vistas, los API devuelven JSON.

### 🏗️ **Estructura Básica de un Controlador**

Un controlador típico en Laravel contiene métodos que corresponden a las operaciones CRUD (Create, Read, Update, Delete). Cada método tiene una responsabilidad específica:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra una lista de todos los servicios
     * 
     * @return View Vista con la lista de servicios
     */
    public function index(): View
    {
        $services = Service::paginate(10); // Obtiene servicios con paginación
        return view('services.index', compact('services')); // Devuelve la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     * Muestra el formulario para crear un nuevo servicio
     * 
     * @return View Vista del formulario de creación
     */
    public function create(): View
    {
        return view('services.create'); // Devuelve la vista del formulario
    }

    /**
     * Store a newly created resource in storage.
     * Guarda un nuevo servicio en la base de datos
     * 
     * @param Request $request Datos de la petición
     * @return RedirectResponse Redirección después de guardar
     */
    public function store(Request $request): RedirectResponse
    {
        // Lógica de validación y almacenamiento
        return redirect()->route('services.index'); // Redirige después de guardar
    }

    /**
     * Display the specified resource.
     * Muestra un servicio específico
     * 
     * @param Service $service Servicio inyectado automáticamente
     * @return View Vista con los detalles del servicio
     */
    public function show(Service $service): View
    {
        return view('services.show', compact('service')); // Devuelve la vista con el servicio
    }

    /**
     * Show the form for editing the specified resource.
     * Muestra el formulario para editar un servicio
     * 
     * @param Service $service Servicio a editar
     * @return View Vista del formulario de edición
     */
    public function edit(Service $service): View
    {
        return view('services.edit', compact('service')); // Devuelve la vista de edición
    }

    /**
     * Update the specified resource in storage.
     * Actualiza un servicio existente
     * 
     * @param Request $request Datos de la petición
     * @param Service $service Servicio a actualizar
     * @return RedirectResponse Redirección después de actualizar
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        // Lógica de actualización
        return redirect()->route('services.show', $service); // Redirige al servicio actualizado
    }

    /**
     * Remove the specified resource from storage.
     * Elimina un servicio
     * 
     * @param Service $service Servicio a eliminar
     * @return RedirectResponse Redirección después de eliminar
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete(); // Elimina el servicio de la base de datos
        return redirect()->route('services.index'); // Redirige a la lista
    }
}
```

**Explicación detallada de cada método CRUD:**

**index()**: Lista todos los recursos (GET /services). Es como mostrar una "lista de productos" en una tienda. Obtiene todos los servicios y los muestra en una página.

**create()**: Muestra formulario de creación (GET /services/create). Es como mostrar un "formulario en blanco" para agregar un nuevo producto. No guarda nada, solo muestra el formulario.

**store()**: Guarda nuevo recurso (POST /services). Es como "procesar el formulario" y guardar el nuevo producto en la base de datos. Recibe los datos del formulario y los guarda.

**show()**: Muestra un recurso específico (GET /services/{id}). Es como mostrar los "detalles de un producto específico". Muestra información detallada de un servicio en particular.

**edit()**: Muestra formulario de edición (GET /services/{id}/edit). Es como mostrar un "formulario pre-llenado" con los datos del producto para editarlo. No actualiza nada, solo muestra el formulario.

**update()**: Actualiza un recurso (PUT/PATCH /services/{id}). Es como "procesar el formulario de edición" y actualizar el producto en la base de datos. Recibe los datos modificados y los guarda.

**destroy()**: Elimina un recurso (DELETE /services/{id}). Es como "eliminar un producto" de la base de datos. Borra permanentemente el servicio.

## 🔧 **Métodos CRUD Básicos**

### 📋 **1. Index - Listar recursos**

El método `index()` es el más común y se usa para mostrar una lista de todos los recursos:

```php
public function index(): View
{
    $services = Service::with('category')  // Carga la relación categoría para evitar N+1
        ->where('active', true)            // Solo servicios activos
        ->orderBy('created_at', 'desc')    // Ordena por fecha de creación (más recientes primero)
        ->paginate(15);                    // Divide en páginas de 15 elementos
    
    return view('services.index', compact('services')); // Pasa los datos a la vista
}
```

**Explicación detallada:**

**with('category')**: Carga la relación categoría para evitar el problema N+1. Sin esto, Laravel haría una consulta adicional por cada servicio para obtener su categoría.

**where('active', true)**: Filtra solo los servicios que están activos. Útil para no mostrar servicios desactivados o borrados lógicamente.

**orderBy('created_at', 'desc')**: Ordena los servicios por fecha de creación, mostrando los más recientes primero. El 'desc' significa descendente (más nuevo a más viejo).

**paginate(15)**: Divide los resultados en páginas de 15 elementos. Esto es importante para el rendimiento cuando tienes muchos servicios.

**compact('services')**: Crea un array con la variable 'services' para pasarla a la vista. Es equivalente a `['services' => $services]`.

### 📝 **2. Create - Mostrar formulario de creación**

El método `create()` muestra el formulario para crear un nuevo recurso:

```php
public function create(): View
{
    $categories = Category::all(); // Obtiene todas las categorías para el formulario
    return view('services.create', compact('categories')); // Pasa las categorías a la vista
}
```

**Explicación detallada:**

**Category::all()**: Obtiene todas las categorías de la base de datos. Se usan para llenar un dropdown o select en el formulario.

**compact('categories')**: Pasa las categorías a la vista para que el usuario pueda seleccionar una categoría al crear el servicio.

**Vista create**: La vista `services.create` contiene el formulario HTML que el usuario llenará para crear un nuevo servicio.

### 💾 **3. Store - Guardar nuevo recurso**

El método `store()` procesa el formulario y guarda el nuevo recurso en la base de datos:

```php
public function store(Request $request): RedirectResponse
{
    // Validar los datos de entrada
    $validated = $request->validate([
        'name' => 'required|string|max:255',           // Nombre requerido, string, máximo 255 caracteres
        'description' => 'required|string',             // Descripción requerida
        'price' => 'required|numeric|min:0',           // Precio requerido, numérico, mínimo 0
        'category_id' => 'required|exists:categories,id' // ID de categoría debe existir en la tabla categories
    ]);

    // Crear el servicio con los datos validados
    $service = Service::create($validated);
    
    // Redirigir al servicio creado con mensaje de éxito
    return redirect()
        ->route('services.show', $service)  // Redirige a la página del servicio creado
        ->with('success', 'Servicio creado exitosamente'); // Mensaje que se mostrará al usuario
}
```

**Explicación detallada de la validación:**

**required**: El campo es obligatorio. Si no se proporciona, la validación fallará.

**string**: El valor debe ser una cadena de texto.

**max:255**: La longitud máxima es 255 caracteres. Útil para evitar textos muy largos.

**numeric**: El valor debe ser un número.

**min:0**: El valor mínimo es 0. Útil para precios que no pueden ser negativos.

**exists:categories,id**: El valor debe existir en la tabla 'categories' en la columna 'id'. Previene IDs inválidos.

**Explicación del flujo:**

1. **Validación**: Se validan los datos de entrada según las reglas definidas
2. **Creación**: Se crea el servicio con los datos validados
3. **Redirección**: Se redirige al usuario a la página del servicio creado
4. **Mensaje**: Se muestra un mensaje de éxito al usuario

### 👁️ **4. Show - Mostrar recurso específico**

El método `show()` muestra los detalles de un recurso específico:

```php
public function show(Service $service): View
{
    $service->load(['category', 'reviews']); // Carga las relaciones necesarias
    return view('services.show', compact('service')); // Pasa el servicio a la vista
}
```

**Explicación detallada:**

**Service $service**: Laravel automáticamente busca el servicio basándose en el ID de la URL. Esto se llama "Route Model Binding".

**load(['category', 'reviews'])**: Carga las relaciones categoría y reseñas para evitar consultas adicionales. Más eficiente que cargar las relaciones en la vista.

**compact('service')**: Pasa el servicio a la vista para mostrar sus detalles.

### ✏️ **5. Edit - Mostrar formulario de edición**

El método `edit()` muestra el formulario para editar un recurso existente:

```php
public function edit(Service $service): View
{
    $categories = Category::all(); // Obtiene todas las categorías para el dropdown
    return view('services.edit', compact('service', 'categories')); // Pasa el servicio y categorías
}
```

**Explicación detallada:**

**Service $service**: Laravel automáticamente inyecta el servicio basándose en el ID de la URL.

**Category::all()**: Obtiene todas las categorías para que el usuario pueda cambiar la categoría del servicio.

**compact('service', 'categories')**: Pasa tanto el servicio (para pre-llenar el formulario) como las categorías (para el dropdown).

### 🔄 **6. Update - Actualizar recurso**

El método `update()` procesa el formulario de edición y actualiza el recurso:

```php
public function update(Request $request, Service $service): RedirectResponse
{
    // Validar los datos de entrada (mismas reglas que store)
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id'
    ]);

    // Actualizar el servicio con los datos validados
    $service->update($validated);
    
    // Redirigir al servicio actualizado con mensaje de éxito
    return redirect()
        ->route('services.show', $service)  // Redirige a la página del servicio actualizado
        ->with('success', 'Servicio actualizado exitosamente'); // Mensaje de éxito
}
```

**Explicación detallada:**

**Request $request**: Contiene los datos del formulario de edición.

**Service $service**: El servicio que se va a actualizar (inyectado automáticamente).

**$service->update($validated)**: Actualiza el servicio con los datos validados. Laravel automáticamente actualiza solo los campos que han cambiado.

**Redirección**: Se redirige al usuario a la página del servicio actualizado para que vea los cambios.

### 🗑️ **7. Destroy - Eliminar recurso**

El método `destroy()` elimina un recurso de la base de datos:

```php
public function destroy(Service $service): RedirectResponse
{
    $service->delete(); // Elimina el servicio de la base de datos
    
    // Redirigir a la lista con mensaje de éxito
    return redirect()
        ->route('services.index')  // Redirige a la lista de servicios
        ->with('success', 'Servicio eliminado exitosamente'); // Mensaje de confirmación
}
```

**Explicación detallada:**

**Service $service**: El servicio que se va a eliminar (inyectado automáticamente).

**$service->delete()**: Elimina el servicio de la base de datos. Laravel automáticamente maneja las relaciones y restricciones.

**Redirección**: Se redirige al usuario a la lista de servicios para que vea que el servicio ya no está ahí.

## 🚀 **Resource Controllers**

Los Resource Controllers incluyen automáticamente todos los métodos CRUD. Son una forma rápida de crear controladores completos.

### 🎯 **Crear Resource Controller**

```bash
php artisan make:controller ServiceController --resource
```

**Explicación:** Este comando crea automáticamente un controlador con todos los métodos CRUD (index, create, store, show, edit, update, destroy).

### 🛣️ **Rutas Resource**

```php
// En routes/web.php
Route::resource('services', ServiceController::class);

// Rutas generadas automáticamente:
// GET    /services           → index()   → Lista todos los servicios
// GET    /services/create    → create()  → Muestra formulario de creación
// POST   /services           → store()   → Guarda nuevo servicio
// GET    /services/{id}      → show()    → Muestra servicio específico
// GET    /services/{id}/edit → edit()    → Muestra formulario de edición
// PUT    /services/{id}      → update()  → Actualiza servicio
// DELETE /services/{id}      → destroy() → Elimina servicio
```

**Explicación de cada ruta:**

**GET /services**: Lista todos los servicios (página principal de servicios)
**GET /services/create**: Muestra formulario para crear nuevo servicio
**POST /services**: Procesa el formulario y guarda el nuevo servicio
**GET /services/{id}**: Muestra los detalles de un servicio específico
**GET /services/{id}/edit**: Muestra formulario para editar un servicio
**PUT /services/{id}**: Procesa el formulario y actualiza el servicio
**DELETE /services/{id}**: Elimina un servicio

### 🎯 **Resource Controller con Rutas Específicas**

```php
// Solo ciertas rutas (útil cuando no necesitas todas las operaciones CRUD)
Route::resource('services', ServiceController::class)->only([
    'index', 'show', 'store'  // Solo listar, mostrar y crear
]);

// Excluir rutas (útil cuando no quieres que los usuarios eliminen recursos)
Route::resource('services', ServiceController::class)->except([
    'destroy'  // No permite eliminar servicios
]);

// Rutas anidadas (útil cuando los recursos pertenecen a otro recurso)
Route::resource('categories.services', ServiceController::class);
// Genera rutas como: /categories/{category}/services
```

**Explicación de cada opción:**

**only()**: Especifica exactamente qué rutas quieres generar. Útil para APIs de solo lectura o cuando no necesitas todas las operaciones.

**except()**: Excluye rutas específicas. Útil cuando no quieres que los usuarios eliminen recursos o cuando ciertas operaciones no aplican.

**rutas anidadas**: Útil cuando los recursos pertenecen a otro recurso. Por ejemplo, servicios que pertenecen a categorías.

## 📡 **API Controllers**

Los API Controllers están optimizados para devolver respuestas JSON en lugar de vistas HTML.

### 🎯 **Crear API Controller**

```bash
php artisan make:controller Api/ServiceApiController --api
```

**Explicación:** El flag `--api` crea un controlador optimizado para APIs, sin los métodos `create()` y `edit()` ya que las APIs no muestran formularios HTML.

### 🏗️ **Estructura de API Controller**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\JsonResponse;

class ServiceApiController extends Controller
{
    /**
     * Display a listing of services.
     * Lista todos los servicios en formato JSON
     */
    public function index(): JsonResponse
    {
        $services = Service::with('category')  // Carga la relación categoría
            ->paginate(15);                    // Paginación para APIs
        
        return ServiceResource::collection($services); // Devuelve JSON formateado
    }

    /**
     * Store a newly created service.
     * Crea un nuevo servicio y devuelve JSON
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated()); // Crea el servicio
        
        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'data' => new ServiceResource($service) // Formatea la respuesta
        ], 201); // Código 201 = Created
    }

    /**
     * Display the specified service.
     * Muestra un servicio específico en formato JSON
     */
    public function show(Service $service): JsonResponse
    {
        return new ServiceResource($service); // Devuelve JSON formateado
    }

    /**
     * Update the specified service.
     * Actualiza un servicio y devuelve JSON
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $service->update($request->validated()); // Actualiza el servicio
        
        return response()->json([
            'message' => 'Servicio actualizado exitosamente',
            'data' => new ServiceResource($service) // Formatea la respuesta
        ]);
    }

    /**
     * Remove the specified service.
     * Elimina un servicio y devuelve JSON
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete(); // Elimina el servicio
        
        return response()->json([
            'message' => 'Servicio eliminado exitosamente'
        ]);
    }
}
```

**Explicación detallada de cada método:**

**index()**: Devuelve una lista de servicios en formato JSON. Útil para aplicaciones móviles o frontend JavaScript.

**store()**: Crea un nuevo servicio y devuelve confirmación en JSON. El código 201 indica que se creó exitosamente.

**show()**: Devuelve los detalles de un servicio específico en JSON. Útil para mostrar detalles en aplicaciones móviles.

**update()**: Actualiza un servicio y devuelve confirmación en JSON. Incluye los datos actualizados en la respuesta.

**destroy()**: Elimina un servicio y devuelve confirmación en JSON. Útil para confirmar que la eliminación fue exitosa.

**ServiceResource**: Clase que formatea los datos del servicio para la API. Controla qué campos se incluyen en la respuesta JSON.

## 🎯 **Patrones Avanzados**

### 📋 **1. Single Action Controllers**

Los Single Action Controllers tienen solo un método `__invoke()`. Útiles para acciones simples y específicas:

```bash
php artisan make:controller ProcessPaymentController --invokable
```

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProcessPaymentController extends Controller
{
    /**
     * Procesa un pago - único método del controlador
     * 
     * @param Request $request Datos del pago
     * @return JsonResponse Respuesta JSON
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Lógica de procesamiento de pago
        $paymentData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'card_number' => 'required|string',
            'expiry_date' => 'required|string'
        ]);

        // Procesar el pago aquí...
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pago procesado exitosamente'
        ]);
    }
}
```

**Explicación:**

**__invoke()**: Método especial que se ejecuta cuando se llama al controlador. Útil para acciones simples que no necesitan múltiples métodos.

**--invokable**: Flag que crea un controlador con solo el método `__invoke()`.

**Uso**: `Route::post('/process-payment', ProcessPaymentController::class);`

### 🔧 **2. Controller con Inyección de Dependencias**

Los controladores pueden recibir dependencias automáticamente a través del constructor:

```php
<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * Constructor con inyección de dependencias
     * 
     * @param PaymentService $paymentService Servicio de pagos
     * @param NotificationService $notificationService Servicio de notificaciones
     */
    public function __construct(
        private PaymentService $paymentService,
        private NotificationService $notificationService
    ) {}

    /**
     * Procesa un pago usando servicios inyectados
     */
    public function process(Request $request): JsonResponse
    {
        $result = $this->paymentService->process($request->all()); // Usa el servicio de pagos
        $this->notificationService->send($result); // Envía notificación
        
        return response()->json($result);
    }
}
```

**Explicación de la inyección de dependencias:**

**private PaymentService $paymentService**: Laravel automáticamente crea una instancia de PaymentService y la inyecta en el controlador.

**private NotificationService $notificationService**: Similar al anterior, Laravel inyecta automáticamente esta dependencia.

**Ventajas**: 
- Código más limpio y testeable
- Separación de responsabilidades
- Reutilización de servicios
- Testing más fácil (puedes mockear los servicios)

### 🧩 **3. Controller con Traits**

Los Traits te permiten compartir funcionalidad entre múltiples controladores:

```php
<?php

namespace App\Http\Controllers;

use App\Traits\HasSearch;
use App\Traits\HasPagination;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use HasSearch, HasPagination; // Usa los traits para funcionalidad compartida

    /**
     * Lista servicios con búsqueda y paginación
     */
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->search, $this->searchScope()) // Usa trait de búsqueda
            ->paginate($this->getPerPage($request)); // Usa trait de paginación
        
        return view('services.index', compact('services'));
    }
}
```

**Explicación de los Traits:**

**use HasSearch, HasPagination**: Importa funcionalidad de otros traits. Es como "copiar y pegar" métodos de otras clases.

**$this->searchScope()**: Método del trait HasSearch que implementa la lógica de búsqueda.

**$this->getPerPage($request)**: Método del trait HasPagination que determina cuántos elementos mostrar por página.

**Ventajas**:
- Reutilización de código
- Controladores más limpios
- Funcionalidad compartida entre controladores
- Fácil mantenimiento

## 📊 **Respuestas del Controlador**

### 📋 **Respuestas JSON**

```php
public function apiIndex(): JsonResponse
{
    $services = Service::paginate(10); // Obtiene servicios paginados
    
    return response()->json([
        'data' => $services->items(), // Los servicios de la página actual
        'meta' => [
            'current_page' => $services->currentPage(), // Página actual
            'total' => $services->total(), // Total de servicios
            'per_page' => $services->perPage() // Servicios por página
        ]
    ]);
}
```

**Explicación de la respuesta JSON:**

**response()->json()**: Crea una respuesta HTTP con contenido JSON.

**$services->items()**: Obtiene solo los elementos de la página actual (sin información de paginación).

**meta**: Información adicional sobre la paginación. Útil para que el frontend sepa cuántas páginas hay.

### 🔢 **Respuestas con Códigos de Estado**

```php
public function store(Request $request): JsonResponse
{
    try {
        $service = Service::create($request->validated()); // Intenta crear el servicio
        
        return response()->json([
            'message' => 'Servicio creado',
            'data' => $service
        ], 201); // Código 201 = Created
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al crear servicio',
            'error' => $e->getMessage()
        ], 500); // Código 500 = Internal Server Error
    }
}
```

**Explicación de los códigos de estado:**

**201**: Created - El recurso se creó exitosamente
**200**: OK - La petición fue exitosa
**400**: Bad Request - Error en los datos enviados
**404**: Not Found - El recurso no existe
**500**: Internal Server Error - Error del servidor

### 📄 **Respuestas con Headers Personalizados**

```php
public function download(Service $service): Response
{
    $pdf = PDF::loadView('services.pdf', compact('service')); // Genera PDF
    
    return $pdf->download('service-' . $service->id . '.pdf') // Descarga el archivo
        ->header('Content-Type', 'application/pdf'); // Define el tipo de contenido
}
```

**Explicación:**

**PDF::loadView()**: Genera un PDF basándose en una vista Blade.

**download()**: Fuerza la descarga del archivo en lugar de mostrarlo en el navegador.

**header()**: Define headers HTTP personalizados. Útil para especificar el tipo de contenido.

## 🔍 **Mejores Prácticas**

### ✅ **1. Mantener Controladores Delgados**

Los controladores deben ser delgados y enfocarse solo en manejar la petición HTTP:

```php
// ❌ Mal - Lógica de negocio en el controlador
public function store(Request $request)
{
    $data = $request->validate([...]);
    $service = Service::create($data);
    
    // Lógica compleja aquí...
    if ($service->price > 1000) {
        // Lógica adicional...
    }
    
    return redirect()->route('services.index');
}

// ✅ Bien - Usar servicios para lógica de negocio
public function store(StoreServiceRequest $request)
{
    $service = app(ServiceService::class)->create($request->validated()); // Usa un servicio
    
    return redirect()->route('services.index')
        ->with('success', 'Servicio creado exitosamente');
}
```

**Explicación de las mejores prácticas:**

**Controladores delgados**: Los controladores solo deben manejar la petición HTTP, no contener lógica de negocio compleja.

**Usar servicios**: Mueve la lógica de negocio a clases de servicio separadas. Esto hace el código más testeable y reutilizable.

**Form Requests**: Usa clases de validación separadas en lugar de validar en el controlador.

### ✅ **2. Usar Form Requests para Validación**

```php
// En lugar de validar en el controlador
public function store(StoreServiceRequest $request) // Usa Form Request
{
    $service = Service::create($request->validated()); // Datos ya validados
    return redirect()->route('services.index');
}
```

**Explicación:**

**StoreServiceRequest**: Clase separada que maneja la validación. Contiene todas las reglas de validación.

**$request->validated()**: Obtiene solo los datos que pasaron la validación. Más seguro que `$request->all()`.

### ✅ **3. Implementar Resource Collections**

```php
public function index(): JsonResponse
{
    $services = Service::paginate(15); // Obtiene servicios paginados
    
    return ServiceResource::collection($services); // Formatea la respuesta
}
```

**Explicación:**

**ServiceResource::collection()**: Formatea automáticamente la colección de servicios para la API. Controla qué campos se incluyen en la respuesta JSON.

### ✅ **4. Manejo de Errores**

```php
public function show(Service $service): View
{
    try {
        $service->load(['category', 'reviews']); // Carga relaciones
        return view('services.show', compact('service')); // Muestra la vista
    } catch (\Exception $e) {
        return redirect()->route('services.index') // Redirige en caso de error
            ->with('error', 'Error al cargar el servicio'); // Mensaje de error
    }
}
```

**Explicación:**

**try-catch**: Captura errores que puedan ocurrir al cargar el servicio o sus relaciones.

**with('error')**: Pasa un mensaje de error a la sesión para mostrar al usuario.

## 📝 **Comandos Útiles**

```bash
# Crear controlador básico
php artisan make:controller ServiceController

# Crear resource controller
php artisan make:controller ServiceController --resource

# Crear API controller
php artisan make:controller Api/ServiceController --api

# Crear single action controller
php artisan make:controller ProcessPaymentController --invokable

# Crear controlador con modelo
php artisan make:controller ServiceController --model=Service

# Crear controlador con resource y modelo
php artisan make:controller ServiceController --resource --model=Service
```

**Explicación de cada comando:**

**make:controller**: Crea un controlador básico con métodos vacíos.

**--resource**: Agrega todos los métodos CRUD automáticamente.

**--api**: Crea un controlador optimizado para APIs (sin create/edit).

**--invokable**: Crea un controlador con solo el método `__invoke()`.

**--model=Service**: Incluye inyección de dependencias del modelo Service.

## 🎯 **Resumen**

Los controladores en Laravel son fundamentales para:

- ✅ **Manejar la lógica de negocio**: Procesan las peticiones y toman decisiones
- ✅ **Procesar peticiones HTTP**: Reciben y validan datos de entrada
- ✅ **Devolver respuestas apropiadas**: Vistas, JSON, redirecciones
- ✅ **Mantener el código organizado**: Cada controlador tiene una responsabilidad específica
- ✅ **Seguir el patrón MVC**: Conectan las rutas con los modelos y vistas

**Próximo paso:** Form Requests y Validación 