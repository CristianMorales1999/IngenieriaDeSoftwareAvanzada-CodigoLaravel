# Fase 7: Testing y Optimización

## 🧪 Testing en Laravel

### Introducción al Testing

Laravel proporciona un framework de testing robusto que facilita la escritura de tests unitarios, de integración y de características. Los tests nos ayudan a:

- Verificar que el código funciona correctamente
- Detectar regresiones al hacer cambios
- Documentar el comportamiento esperado
- Mejorar la confianza en el código

### Configuración Inicial

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con coverage
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter=UserTest
```

### Estructura de Tests

```
tests/
├── Feature/          # Tests de integración
│   ├── UserTest.php
│   ├── ServiceTest.php
│   └── AuthTest.php
├── Unit/             # Tests unitarios
│   ├── UserTest.php
│   └── ServiceTest.php
└── TestCase.php      # Clase base para tests
```

## 🧪 Tests Unitarios

Los tests unitarios verifican que una unidad específica de código (método, clase) funciona correctamente de forma aislada.

### Ejemplo: Test Unitario para Modelo User

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123'
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Juan Pérez', $user->name);
        $this->assertEquals('juan@example.com', $user->email);
    }

    /** @test */
    public function it_can_update_user_name()
    {
        $user = User::factory()->create(['name' => 'Juan Pérez']);
        
        $user->update(['name' => 'Juan Carlos Pérez']);
        
        $this->assertEquals('Juan Carlos Pérez', $user->fresh()->name);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();
        
        $userId = $user->id;
        $user->delete();
        
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::create([
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);
    }
}
```

### Tests para Servicios

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ServiceService;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceServiceTest extends TestCase
{
    use RefreshDatabase;

    private ServiceService $serviceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceService = new ServiceService();
    }

    /** @test */
    public function it_can_create_service()
    {
        $serviceData = [
            'name' => 'Servicio de Limpieza',
            'description' => 'Limpieza profesional',
            'price' => 50.00,
            'duration' => 60
        ];

        $service = $this->serviceService->createService($serviceData);

        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('Servicio de Limpieza', $service->name);
        $this->assertEquals(50.00, $service->price);
    }

    /** @test */
    public function it_can_update_service()
    {
        $service = Service::factory()->create(['price' => 50.00]);
        
        $updatedData = ['price' => 75.00];
        $updatedService = $this->serviceService->updateService($service, $updatedData);
        
        $this->assertEquals(75.00, $updatedService->price);
    }

    /** @test */
    public function it_can_delete_service()
    {
        $service = Service::factory()->create();
        
        $this->serviceService->deleteService($service);
        
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
```

## 🧪 Tests de Integración

Los tests de integración verifican que múltiples componentes trabajan juntos correctamente.

### Test de Controlador

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_services_index()
    {
        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertViewIs('services.index');
    }

    /** @test */
    public function it_can_create_new_service()
    {
        $user = User::factory()->create();
        
        $serviceData = [
            'name' => 'Nuevo Servicio',
            'description' => 'Descripción del servicio',
            'price' => 100.00,
            'duration' => 90
        ];

        $response = $this->actingAs($user)
                        ->post('/services', $serviceData);

        $response->assertRedirect('/services');
        $this->assertDatabaseHas('services', [
            'name' => 'Nuevo Servicio',
            'price' => 100.00
        ]);
    }

    /** @test */
    public function it_validates_service_data()
    {
        $user = User::factory()->create();
        
        $invalidData = [
            'name' => '', // Nombre vacío
            'price' => 'invalid-price' // Precio inválido
        ];

        $response = $this->actingAs($user)
                        ->post('/services', $invalidData);

        $response->assertSessionHasErrors(['name', 'price']);
        $this->assertDatabaseMissing('services', $invalidData);
    }

    /** @test */
    public function it_can_update_service()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['name' => 'Servicio Original']);
        
        $updateData = ['name' => 'Servicio Actualizado'];

        $response = $this->actingAs($user)
                        ->put("/services/{$service->id}", $updateData);

        $response->assertRedirect('/services');
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Servicio Actualizado'
        ]);
    }

    /** @test */
    public function it_can_delete_service()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $response = $this->actingAs($user)
                        ->delete("/services/{$service->id}");

        $response->assertRedirect('/services');
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
```

### Test de Rutas API

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_services_list()
    {
        Service::factory()->count(3)->create();

        $response = $this->getJson('/api/services');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => ['id', 'name', 'description', 'price', 'duration']
                ]);
    }

    /** @test */
    public function it_can_get_single_service()
    {
        $service = Service::factory()->create();

        $response = $this->getJson("/api/services/{$service->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $service->id,
                    'name' => $service->name
                ]);
    }

    /** @test */
    public function it_can_create_service_via_api()
    {
        $user = User::factory()->create();
        
        $serviceData = [
            'name' => 'API Service',
            'description' => 'Service created via API',
            'price' => 150.00,
            'duration' => 120
        ];

        $response = $this->actingAs($user)
                        ->postJson('/api/services', $serviceData);

        $response->assertStatus(201)
                ->assertJson([
                    'name' => 'API Service',
                    'price' => 150.00
                ]);
    }
}
```

## 🧪 Tests de Autenticación

### Test de Login/Register

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $userData = [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com'
        ]);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_access_protected_routes()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
```

### Test de Middleware

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function auth_middleware_redirects_guests()
    {
        $response = $this->get('/services/create');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function auth_middleware_allows_authenticated_users()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/services/create');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_middleware_redirects_authenticated_users()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/login');
        
        $response->assertRedirect('/dashboard');
    }
}
```

## 🏭 Factories para Testing

### Factory Básica

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

### Factory para Service

```php
<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'duration' => fake()->numberBetween(30, 240), // 30 min a 4 horas
            'is_active' => true,
            'user_id' => \App\Models\User::factory(),
        ];
    }

    /**
     * Servicio inactivo
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Servicio premium (precio alto)
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 200, 1000),
        ]);
    }

    /**
     * Servicio de corta duración
     */
    public function shortDuration(): static
    {
        return $this->state(fn (array $attributes) => [
            'duration' => fake()->numberBetween(15, 60),
        ]);
    }
}
```

### Uso de Factories en Tests

```php
/** @test */
public function it_can_create_multiple_services()
{
    // Crear 5 servicios
    $services = Service::factory()->count(5)->create();
    
    $this->assertCount(5, $services);
    $this->assertDatabaseCount('services', 5);
}

/** @test */
public function it_can_create_service_with_specific_state()
{
    // Crear servicio premium
    $premiumService = Service::factory()->premium()->create();
    
    $this->assertGreaterThanOrEqual(200, $premiumService->price);
}

/** @test */
public function it_can_create_user_with_services()
{
    // Crear usuario con 3 servicios
    $user = User::factory()
        ->has(Service::factory()->count(3))
        ->create();
    
    $this->assertCount(3, $user->services);
}
```

## 🚀 Optimización

### Optimización de Consultas

```php
// ❌ N+1 Problem
$users = User::all();
foreach ($users as $user) {
    echo $user->services->count(); // Consulta adicional por cada usuario
}

// ✅ Eager Loading
$users = User::with('services')->get();
foreach ($users as $user) {
    echo $user->services->count(); // Sin consultas adicionales
}

// ✅ Eager Loading con Count
$users = User::withCount('services')->get();
foreach ($users as $user) {
    echo $user->services_count; // Count pre-calculado
}
```

### Optimización de Base de Datos

```php
// Índices en migraciones
public function up(): void
{
    Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->decimal('price', 8, 2);
        $table->integer('duration');
        $table->foreignId('user_id')->constrained();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        // Índices para optimizar consultas
        $table->index(['user_id', 'is_active']);
        $table->index('price');
        $table->index('name');
    });
}
```

### Cache

```php
// Cache de consultas frecuentes
public function getPopularServices()
{
    return Cache::remember('popular_services', 3600, function () {
        return Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();
    });
}

// Cache de vistas
public function index()
{
    $services = Service::with('user')->paginate(12);
    
    return view('services.index', compact('services'))
        ->render();
}
```

### Optimización de Imágenes

```php
// Intervention Image para optimización
use Intervention\Image\Facades\Image;

public function store(Request $request)
{
    $image = $request->file('image');
    
    // Optimizar imagen
    $optimizedImage = Image::make($image)
        ->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->encode('jpg', 80);
    
    $path = $image->store('services', 'public');
    
    // Guardar imagen optimizada
    Storage::disk('public')->put($path, $optimizedImage);
}
```

## 📊 Métricas y Monitoreo

### Logging de Performance

```php
// Middleware para medir tiempo de respuesta
class PerformanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        Log::info('Request Performance', [
            'url' => $request->url(),
            'method' => $request->method(),
            'duration' => $duration,
            'memory' => memory_get_peak_usage(true)
        ]);
        
        return $response;
    }
}
```

### Health Checks

```php
// Artisan command para health check
class HealthCheck extends Command
{
    protected $signature = 'app:health-check';
    protected $description = 'Verificar salud de la aplicación';

    public function handle()
    {
        $this->info('🔍 Verificando salud de la aplicación...');
        
        // Verificar base de datos
        try {
            DB::connection()->getPdo();
            $this->info('✅ Base de datos: OK');
        } catch (\Exception $e) {
            $this->error('❌ Base de datos: ERROR');
        }
        
        // Verificar cache
        try {
            Cache::store()->has('health_check');
            $this->info('✅ Cache: OK');
        } catch (\Exception $e) {
            $this->error('❌ Cache: ERROR');
        }
        
        // Verificar storage
        try {
            Storage::disk('public')->exists('test');
            $this->info('✅ Storage: OK');
        } catch (\Exception $e) {
            $this->error('❌ Storage: ERROR');
        }
    }
}
```

## 🧪 Comandos Útiles para Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con coverage
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter=UserTest

# Ejecutar tests en paralelo
php artisan test --parallel

# Ejecutar tests con verbose
php artisan test -v

# Ejecutar tests y generar reporte HTML
php artisan test --coverage-html coverage/

# Ejecutar tests de una carpeta específica
php artisan test tests/Feature/

# Ejecutar tests unitarios
php artisan test tests/Unit/

# Ejecutar tests con datos falsos
php artisan test --env=testing
```

## 📋 Checklist de Testing

- [ ] Tests unitarios para modelos
- [ ] Tests de integración para controladores
- [ ] Tests de autenticación
- [ ] Tests de API
- [ ] Tests de middleware
- [ ] Tests de validación
- [ ] Tests de factories
- [ ] Tests de edge cases
- [ ] Tests de performance
- [ ] Coverage mínimo del 80%

## 🎯 Mejores Prácticas

1. **Nombres descriptivos**: Usar nombres que describan qué se está probando
2. **Arrange-Act-Assert**: Estructurar tests en 3 partes claras
3. **Tests independientes**: Cada test debe poder ejecutarse de forma aislada
4. **Datos de prueba**: Usar factories y seeders para datos consistentes
5. **Mocks y stubs**: Usar cuando sea necesario para aislar unidades
6. **Assertions específicas**: Ser específico en las aserciones
7. **Cleanup**: Limpiar datos después de cada test
8. **Performance**: Mantener tests rápidos y eficientes

Esta documentación cubre los aspectos fundamentales de testing y optimización en Laravel, proporcionando ejemplos prácticos y mejores prácticas para garantizar la calidad del código. 