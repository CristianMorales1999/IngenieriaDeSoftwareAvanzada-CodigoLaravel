# 📋 Controladores en Laravel 12

## 🎯 Introducción

Los controladores en Laravel son la capa que maneja la lógica de negocio entre las rutas y los modelos. Actúan como intermediarios que procesan las peticiones HTTP y devuelven las respuestas apropiadas.

## 📁 Estructura de Controladores

### Ubicación
```
app/Http/Controllers/
├── Controller.php (Base)
├── UserController.php
├── ServiceController.php
├── Admin/
│   ├── DashboardController.php
│   └── UserManagementController.php
└── Api/
    ├── ServiceApiController.php
    └── UserApiController.php
```

### Estructura Básica de un Controlador

```php
<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $services = Service::paginate(10);
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Lógica de validación y almacenamiento
        return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): View
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): View
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        // Lógica de actualización
        return redirect()->route('services.show', $service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return redirect()->route('services.index');
    }
}
```

## 🔧 Métodos CRUD Básicos

### 1. **Index** - Listar recursos
```php
public function index(): View
{
    $services = Service::with('category')
        ->where('active', true)
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('services.index', compact('services'));
}
```

### 2. **Create** - Mostrar formulario de creación
```php
public function create(): View
{
    $categories = Category::all();
    return view('services.create', compact('categories'));
}
```

### 3. **Store** - Guardar nuevo recurso
```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id'
    ]);

    $service = Service::create($validated);
    
    return redirect()
        ->route('services.show', $service)
        ->with('success', 'Servicio creado exitosamente');
}
```

### 4. **Show** - Mostrar recurso específico
```php
public function show(Service $service): View
{
    $service->load(['category', 'reviews']);
    return view('services.show', compact('service'));
}
```

### 5. **Edit** - Mostrar formulario de edición
```php
public function edit(Service $service): View
{
    $categories = Category::all();
    return view('services.edit', compact('service', 'categories'));
}
```

### 6. **Update** - Actualizar recurso
```php
public function update(Request $request, Service $service): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id'
    ]);

    $service->update($validated);
    
    return redirect()
        ->route('services.show', $service)
        ->with('success', 'Servicio actualizado exitosamente');
}
```

### 7. **Destroy** - Eliminar recurso
```php
public function destroy(Service $service): RedirectResponse
{
    $service->delete();
    
    return redirect()
        ->route('services.index')
        ->with('success', 'Servicio eliminado exitosamente');
}
```

## 🚀 Resource Controllers

Los Resource Controllers incluyen automáticamente todos los métodos CRUD.

### Crear Resource Controller
```bash
php artisan make:controller ServiceController --resource
```

### Rutas Resource
```php
// En routes/web.php
Route::resource('services', ServiceController::class);

// Rutas generadas:
// GET    /services           → index()
// GET    /services/create    → create()
// POST   /services           → store()
// GET    /services/{id}      → show()
// GET    /services/{id}/edit → edit()
// PUT    /services/{id}      → update()
// DELETE /services/{id}      → destroy()
```

### Resource Controller con Rutas Específicas
```php
// Solo ciertas rutas
Route::resource('services', ServiceController::class)->only([
    'index', 'show', 'store'
]);

// Excluir rutas
Route::resource('services', ServiceController::class)->except([
    'destroy'
]);

// Rutas anidadas
Route::resource('categories.services', ServiceController::class);
```

## 📡 API Controllers

### Crear API Controller
```bash
php artisan make:controller Api/ServiceApiController --api
```

### Estructura de API Controller
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
     */
    public function index(): JsonResponse
    {
        $services = Service::with('category')
            ->paginate(15);
        
        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created service.
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated());
        
        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'data' => new ServiceResource($service)
        ], 201);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): JsonResponse
    {
        return new ServiceResource($service);
    }

    /**
     * Update the specified service.
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $service->update($request->validated());
        
        return response()->json([
            'message' => 'Servicio actualizado exitosamente',
            'data' => new ServiceResource($service)
        ]);
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();
        
        return response()->json([
            'message' => 'Servicio eliminado exitosamente'
        ]);
    }
}
```

## 🎯 Patrones Avanzados

### 1. **Single Action Controllers**
```bash
php artisan make:controller ProcessPaymentController --invokable
```

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessPaymentController extends Controller
{
    public function __invoke(Request $request)
    {
        // Lógica de procesamiento de pago
        return response()->json(['status' => 'success']);
    }
}
```

### 2. **Controller con Inyección de Dependencias**
```php
<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private NotificationService $notificationService
    ) {}

    public function process(Request $request)
    {
        $result = $this->paymentService->process($request->all());
        $this->notificationService->send($result);
        
        return response()->json($result);
    }
}
```

### 3. **Controller con Traits**
```php
<?php

namespace App\Http\Controllers;

use App\Traits\HasSearch;
use App\Traits\HasPagination;

class ServiceController extends Controller
{
    use HasSearch, HasPagination;

    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->search, $this->searchScope())
            ->paginate($this->getPerPage($request));
        
        return view('services.index', compact('services'));
    }
}
```

## 📊 Respuestas del Controlador

### Respuestas JSON
```php
public function apiIndex(): JsonResponse
{
    $services = Service::paginate(10);
    
    return response()->json([
        'data' => $services->items(),
        'meta' => [
            'current_page' => $services->currentPage(),
            'total' => $services->total(),
            'per_page' => $services->perPage()
        ]
    ]);
}
```

### Respuestas con Códigos de Estado
```php
public function store(Request $request): JsonResponse
{
    try {
        $service = Service::create($request->validated());
        
        return response()->json([
            'message' => 'Servicio creado',
            'data' => $service
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al crear servicio',
            'error' => $e->getMessage()
        ], 500);
    }
}
```

### Respuestas con Headers Personalizados
```php
public function download(Service $service): Response
{
    $pdf = PDF::loadView('services.pdf', compact('service'));
    
    return $pdf->download('service-' . $service->id . '.pdf')
        ->header('Content-Type', 'application/pdf');
}
```

## 🔍 Mejores Prácticas

### 1. **Mantener Controladores Delgados**
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

// ✅ Bien - Usar servicios
public function store(StoreServiceRequest $request)
{
    $service = app(ServiceService::class)->create($request->validated());
    
    return redirect()->route('services.index')
        ->with('success', 'Servicio creado exitosamente');
}
```

### 2. **Usar Form Requests para Validación**
```php
// En lugar de validar en el controlador
public function store(StoreServiceRequest $request)
{
    $service = Service::create($request->validated());
    return redirect()->route('services.index');
}
```

### 3. **Implementar Resource Collections**
```php
public function index(): JsonResponse
{
    $services = Service::paginate(15);
    
    return ServiceResource::collection($services);
}
```

### 4. **Manejo de Errores**
```php
public function show(Service $service): View
{
    try {
        $service->load(['category', 'reviews']);
        return view('services.show', compact('service'));
    } catch (\Exception $e) {
        return redirect()->route('services.index')
            ->with('error', 'Error al cargar el servicio');
    }
}
```

## 📝 Comandos Útiles

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

## 🎯 Resumen

Los controladores en Laravel son fundamentales para:
- ✅ Manejar la lógica de negocio
- ✅ Procesar peticiones HTTP
- ✅ Devolver respuestas apropiadas
- ✅ Mantener el código organizado
- ✅ Seguir el patrón MVC

**Próximo paso:** Form Requests y Validación 