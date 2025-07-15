# Fase 7: Testing y Optimización

## 🧪 Testing en Laravel

### Introducción al Testing

Laravel proporciona un framework de testing robusto que facilita la escritura de tests unitarios, de integración y de características. Los tests nos ayudan a:

- Verificar que el código funciona correctamente
- Detectar regresiones al hacer cambios
- Documentar el comportamiento esperado
- Mejorar la confianza en el código

**¿Por qué es importante el testing?**
- **Calidad del código**: Los tests verifican que cada parte funciona como esperamos
- **Prevención de errores**: Detectan problemas antes de que lleguen a producción
- **Refactoring seguro**: Permiten cambiar código sin romper funcionalidad existente
- **Documentación viva**: Los tests muestran cómo debe funcionar el código
- **Confianza del equipo**: Todos pueden hacer cambios sabiendo que los tests los respaldan

### Configuración Inicial

Laravel incluye PHPUnit como framework de testing por defecto. Estos comandos te permiten ejecutar diferentes tipos de tests:

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con coverage (muestra qué porcentaje del código está cubierto por tests)
php artisan test --coverage

# Ejecutar tests específicos (solo tests que contengan "UserTest" en el nombre)
php artisan test --filter=UserTest
```

**Explicación detallada de los comandos:**
- **`php artisan test`**: Ejecuta todos los tests en las carpetas `tests/Feature/` y `tests/Unit/`
  - **Feature/**: Tests que prueban funcionalidades completas
  - **Unit/**: Tests que prueban unidades específicas de código
- **`--coverage`**: Genera un reporte que muestra qué líneas de código están siendo probadas
  - **Cobertura**: Porcentaje del código que está siendo testeado
  - **Líneas no cubiertas**: Código que no tiene tests y podría tener errores
- **`--filter`**: Permite ejecutar solo tests específicos por nombre de clase o método
  - **Útil para**: Desarrollar tests específicos sin ejecutar todo el suite

### Estructura de Tests

Laravel organiza los tests en dos categorías principales para mantener una estructura clara:

```
tests/
├── Feature/          # Tests de integración (prueban múltiples componentes juntos)
│   ├── UserTest.php
│   ├── ServiceTest.php
│   └── AuthTest.php
├── Unit/             # Tests unitarios (prueban una sola unidad de código)
│   ├── UserTest.php
│   └── ServiceTest.php
└── TestCase.php      # Clase base que proporciona métodos útiles para todos los tests
```

**Explicación detallada de la estructura:**
- **Feature/**: Contiene tests que prueban funcionalidades completas (como un formulario completo, una API endpoint, etc.)
  - **Prueban**: Rutas, controladores, middleware, vistas en conjunto
  - **Simulan**: Peticiones HTTP reales
  - **Son más lentos**: Porque prueban múltiples componentes
- **Unit/**: Contiene tests que prueban una sola clase o método de forma aislada
  - **Prueban**: Una sola unidad de código (método, clase, función)
  - **Son más rápidos**: Porque no dependen de otros componentes
  - **Más específicos**: Identifican exactamente dónde está el problema
- **TestCase.php**: Clase base que extiende PHPUnit y agrega métodos específicos de Laravel
  - **Proporciona**: Métodos helper para testing
  - **Configura**: Base de datos de testing
  - **Extiende**: Funcionalidad de PHPUnit

## 🧪 Tests Unitarios

Los tests unitarios verifican que una unidad específica de código (método, clase) funciona correctamente de forma aislada. Son rápidos y nos ayudan a identificar problemas específicos en una sola parte del código.

**¿Cuándo usar tests unitarios?**
- **Lógica de negocio**: Probar métodos que contienen lógica compleja
- **Modelos**: Verificar que los modelos funcionan correctamente
- **Servicios**: Probar clases de servicio que contienen lógica reutilizable
- **Helpers**: Verificar funciones auxiliares

### Ejemplo: Test Unitario para Modelo User

Este ejemplo muestra cómo probar las operaciones básicas de un modelo User:

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos antes de cada test

    /** @test */
    public function it_can_create_a_user()
    {
        // Arrange: Preparar los datos de prueba
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123'
        ];

        // Act: Ejecutar la acción que queremos probar
        $user = User::create($userData);

        // Assert: Verificar que el resultado es el esperado
        $this->assertInstanceOf(User::class, $user); // Verifica que se creó un objeto User
        $this->assertEquals('Juan Pérez', $user->name); // Verifica que el nombre es correcto
        $this->assertEquals('juan@example.com', $user->email); // Verifica que el email es correcto
    }

    /** @test */
    public function it_can_update_user_name()
    {
        // Arrange: Crear un usuario usando factory
        $user = User::factory()->create(['name' => 'Juan Pérez']);
        
        // Act: Actualizar el nombre del usuario
        $user->update(['name' => 'Juan Carlos Pérez']);
        
        // Assert: Verificar que el nombre se actualizó correctamente
        // fresh() recarga el modelo desde la base de datos para obtener los datos actualizados
        $this->assertEquals('Juan Carlos Pérez', $user->fresh()->name);
    }

    /** @test */
    public function it_can_delete_user()
    {
        // Arrange: Crear un usuario y guardar su ID
        $user = User::factory()->create();
        $userId = $user->id;
        
        // Act: Eliminar el usuario
        $user->delete();
        
        // Assert: Verificar que el usuario ya no existe en la base de datos
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function it_validates_email_format()
    {
        // Arrange & Act: Intentar crear un usuario con email inválido
        // expectException() indica que esperamos que se lance una excepción
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        // Act: Intentar crear usuario con email inválido
        User::create([
            'name' => 'Test User',
            'email' => 'invalid-email', // Email sin formato válido
            'password' => 'password123'
        ]);
        
        // Assert: Si llegamos aquí, el test falló porque no se lanzó la excepción esperada
    }
}
```

**Explicación detallada del patrón AAA (Arrange-Act-Assert):**

- **Arrange (Preparar)**: Configurar los datos y condiciones necesarias para el test
  - **Datos de entrada**: Crear arrays, objetos, o usar factories
  - **Configuración**: Establecer el estado inicial necesario
- **Act (Actuar)**: Ejecutar la acción específica que queremos probar
  - **Llamada al método**: Invocar la función o método que estamos testeando
  - **Una sola acción**: Cada test debe probar una sola cosa
- **Assert (Verificar)**: Comprobar que el resultado es el esperado
  - **Verificaciones múltiples**: Puedes hacer varias aserciones en un test
  - **Específicas**: Cada aserción debe verificar algo específico

### Tests para Servicios

Los tests para servicios verifican la lógica de negocio que está encapsulada en clases de servicio. Estas clases contienen la lógica compleja que no debería estar en los controladores.

**¿Qué son los servicios?**
- **Clases de lógica de negocio**: Contienen la lógica compleja de la aplicación
- **Reutilizables**: Pueden ser usados por múltiples controladores
- **Testeables**: Fáciles de probar de forma aislada
- **Mantenibles**: Código organizado y fácil de entender

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ServiceService;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceServiceTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos antes de cada test

    private ServiceService $serviceService; // Instancia del servicio que vamos a probar

    protected function setUp(): void
    {
        parent::setUp(); // Llama al setUp del padre
        $this->serviceService = new ServiceService(); // Crea una nueva instancia del servicio
    }

    /** @test */
    public function it_can_create_service()
    {
        // Arrange: Preparar los datos del servicio
        $serviceData = [
            'name' => 'Servicio de Limpieza',
            'description' => 'Limpieza profesional',
            'price' => 50.00,
            'duration' => 60
        ];

        // Act: Llamar al método del servicio que queremos probar
        $service = $this->serviceService->createService($serviceData);

        // Assert: Verificar que el servicio se creó correctamente
        $this->assertInstanceOf(Service::class, $service); // Verifica que es una instancia de Service
        $this->assertEquals('Servicio de Limpieza', $service->name); // Verifica el nombre
        $this->assertEquals(50.00, $service->price); // Verifica el precio
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

**Explicación de setUp() y RefreshDatabase:**

- **`setUp()`**: Método que se ejecuta antes de cada test
  - **Inicialización**: Configurar objetos y variables necesarias
  - **Reutilización**: Evita repetir código en cada test
- **`RefreshDatabase`**: Trait que limpia la base de datos antes de cada test
  - **Aislamiento**: Cada test empieza con una base de datos limpia
  - **Consistencia**: Evita que un test afecte a otro

## 🧪 Tests de Integración

Los tests de integración verifican que múltiples componentes trabajan juntos correctamente. A diferencia de los tests unitarios, estos prueban el flujo completo desde la petición HTTP hasta la respuesta, incluyendo rutas, middleware, controladores y vistas.

**¿Cuándo usar tests de integración?**
- **Flujos completos**: Probar el camino completo de una funcionalidad
- **APIs**: Verificar que los endpoints funcionan correctamente
- **Formularios**: Probar el envío y procesamiento de datos
- **Autenticación**: Verificar el flujo de login/logout

### Test de Controlador

Los tests de controlador verifican que las rutas y controladores funcionan correctamente en conjunto:

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
        // Act: Hacer una petición GET a la ruta /services
        $response = $this->get('/services');

        // Assert: Verificar que la respuesta es exitosa (código 200)
        $response->assertStatus(200);
        // Assert: Verificar que se está mostrando la vista correcta
        $response->assertViewIs('services.index');
    }

    /** @test */
    public function it_can_create_new_service()
    {
        // Arrange: Crear un usuario autenticado
        $user = User::factory()->create();
        
        // Arrange: Preparar los datos del servicio
        $serviceData = [
            'name' => 'Nuevo Servicio',
            'description' => 'Descripción del servicio',
            'price' => 100.00,
            'duration' => 90
        ];

        // Act: Simular que el usuario autenticado hace una petición POST
        // actingAs() simula que el usuario está logueado
        $response = $this->actingAs($user)
                        ->post('/services', $serviceData);

        // Assert: Verificar que después de crear, redirige a la lista de servicios
        $response->assertRedirect('/services');
        // Assert: Verificar que el servicio se guardó en la base de datos
        $this->assertDatabaseHas('services', [
            'name' => 'Nuevo Servicio',
            'price' => 100.00
        ]);
    }

    /** @test */
    public function it_validates_service_data()
    {
        // Arrange: Crear un usuario autenticado
        $user = User::factory()->create();
        
        // Arrange: Preparar datos inválidos para probar la validación
        $invalidData = [
            'name' => '', // Nombre vacío - debería fallar la validación
            'price' => 'invalid-price' // Precio inválido - debería fallar la validación
        ];

        // Act: Intentar crear un servicio con datos inválidos
        $response = $this->actingAs($user)
                        ->post('/services', $invalidData);

        // Assert: Verificar que se generaron errores de validación en la sesión
        $response->assertSessionHasErrors(['name', 'price']);
        // Assert: Verificar que NO se guardó nada en la base de datos
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

**Explicación de los métodos de testing HTTP:**

- **`$this->get('/ruta')`**: Simula una petición GET a la ruta especificada
- **`$this->post('/ruta', $datos)`**: Simula una petición POST con datos
- **`$this->put('/ruta', $datos)`**: Simula una petición PUT para actualizar
- **`$this->delete('/ruta')`**: Simula una petición DELETE para eliminar
- **`actingAs($user)`**: Simula que un usuario está autenticado
- **`assertStatus(200)`**: Verifica que la respuesta tiene el código HTTP correcto
- **`assertRedirect('/ruta')`**: Verifica que redirige a la ruta especificada
- **`assertViewIs('vista')`**: Verifica que se muestra la vista correcta

### Test de Rutas API

Los tests de API verifican que los endpoints REST funcionan correctamente y devuelven las respuestas JSON esperadas:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos antes de cada test

    /** @test */
    public function it_can_get_services_list()
    {
        // Arrange: Crear 3 servicios usando factory
        Service::factory()->count(3)->create();

        // Act: Hacer una petición GET a la API
        // getJson() simula una petición HTTP con header Accept: application/json
        $response = $this->getJson('/api/services');

        // Assert: Verificar que la respuesta es exitosa (200)
        $response->assertStatus(200)
                // Assert: Verificar que devuelve exactamente 3 elementos
                ->assertJsonCount(3)
                // Assert: Verificar que cada elemento tiene la estructura JSON esperada
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

**Explicación de los métodos de testing API:**

- **`getJson()`**: Simula petición GET con header `Accept: application/json`
- **`postJson()`**: Simula petición POST con datos JSON
- **`assertJsonCount(3)`**: Verifica que el JSON tiene exactamente 3 elementos
- **`assertJsonStructure()`**: Verifica que el JSON tiene la estructura esperada
- **`assertJson()`**: Verifica que el JSON contiene los datos esperados

## 🧪 Tests de Autenticación

Los tests de autenticación verifican que el sistema de login, registro y protección de rutas funciona correctamente.

**¿Por qué son importantes los tests de autenticación?**
- **Seguridad**: Verifican que las rutas protegidas están seguras
- **Flujos de usuario**: Prueban el proceso completo de login/registro
- **Middleware**: Verifican que los middleware funcionan correctamente
- **Experiencia de usuario**: Aseguran que los usuarios pueden acceder a lo que necesitan

### Test de Login/Register

Estos tests verifican el flujo completo de autenticación de usuarios:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos antes de cada test

    /** @test */
    public function user_can_register()
    {
        // Arrange: Preparar los datos del nuevo usuario
        $userData = [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123' // Confirmación requerida por Laravel
        ];

        // Act: Simular el envío del formulario de registro
        $response = $this->post('/register', $userData);

        // Assert: Verificar que después del registro exitoso, redirige al dashboard
        $response->assertRedirect('/dashboard');
        // Assert: Verificar que el usuario está autenticado después del registro
        $this->assertAuthenticated();
        // Assert: Verificar que el usuario se guardó en la base de datos
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com'
        ]);
    }

    /** @test */
    public function user_can_login()
    {
        // Arrange: Crear un usuario con contraseña encriptada
        // bcrypt() encripta la contraseña como lo haría Laravel
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Act: Simular el envío del formulario de login
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Assert: Verificar que después del login exitoso, redirige al dashboard
        $response->assertRedirect('/dashboard');
        // Assert: Verificar que el usuario está autenticado
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

**Explicación de los métodos de autenticación:**

- **`assertAuthenticated()`**: Verifica que hay un usuario autenticado
- **`assertGuest()`**: Verifica que no hay usuario autenticado
- **`bcrypt()`**: Encripta la contraseña como lo hace Laravel
- **`password_confirmation`**: Campo requerido por Laravel para confirmar contraseña

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

Las factories son clases que generan datos de prueba de forma consistente y realista. Laravel usa Faker para generar datos aleatorios pero realistas.

**¿Por qué usar factories?**
- **Datos realistas**: Generan datos que parecen reales
- **Consistencia**: Siempre generan datos válidos
- **Flexibilidad**: Permiten crear variaciones con estados
- **Rapidez**: Generan datos automáticamente sin escribir manualmente

### Factory Básica

Esta es la factory por defecto que Laravel crea para el modelo User:

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class; // Especifica qué modelo crea esta factory

    public function definition(): array
    {
        return [
            'name' => fake()->name(), // Genera un nombre aleatorio pero realista
            'email' => fake()->unique()->safeEmail(), // Email único y válido
            'email_verified_at' => now(), // Marca el email como verificado
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // Contraseña encriptada
            'remember_token' => Str::random(10), // Token aleatorio para "recordar" al usuario
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     * Los estados (states) permiten crear variaciones de la factory
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null, // Usuario con email no verificado
        ]);
    }
}
```

**Explicación de los métodos de Faker:**

- **`fake()->name()`**: Genera nombres realistas como "John Doe", "Jane Smith"
- **`fake()->unique()->safeEmail()`**: Genera emails únicos y válidos
- **`fake()->paragraph()`**: Genera párrafos de texto realista
- **`fake()->randomFloat(2, 10, 500)`**: Genera números decimales entre 10 y 500
- **`fake()->numberBetween(30, 240)`**: Genera números enteros entre 30 y 240

### Factory para Service

Esta factory crea datos de prueba para el modelo Service con diferentes estados:

```php
<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class; // Especifica que crea instancias de Service

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true), // Genera un nombre de 3 palabras
            'description' => fake()->paragraph(), // Genera un párrafo aleatorio
            'price' => fake()->randomFloat(2, 10, 500), // Precio entre $10 y $500
            'duration' => fake()->numberBetween(30, 240), // Duración entre 30 y 240 minutos
            'is_active' => true, // Por defecto activo
            'user_id' => \App\Models\User::factory(), // Crea automáticamente un usuario
        ];
    }

    /**
     * Servicio inactivo - Estado personalizado
     * Permite crear servicios que no están disponibles
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false, // Servicio desactivado
        ]);
    }

    /**
     * Servicio premium (precio alto) - Estado personalizado
     * Permite crear servicios de alta gama para testing
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 200, 1000), // Precio entre $200 y $1000
        ]);
    }

    /**
     * Servicio de corta duración - Estado personalizado
     * Permite crear servicios rápidos para testing
     */
    public function shortDuration(): static
    {
        return $this->state(fn (array $attributes) => [
            'duration' => fake()->numberBetween(15, 60), // Duración entre 15 y 60 minutos
        ]);
    }
}
```

**Explicación de los estados (states):**

- **Estados**: Permiten crear variaciones de la factory base
- **`inactive()`**: Crea servicios que no están disponibles
- **`premium()`**: Crea servicios de alta gama con precios altos
- **`shortDuration()`**: Crea servicios de corta duración
- **Reutilización**: Puedes combinar estados: `Service::factory()->premium()->inactive()`

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

**Explicación de los métodos de factory:**

- **`count(5)`**: Crea 5 instancias del modelo
- **`premium()`**: Aplica el estado premium
- **`has()`**: Crea relaciones automáticamente
- **`create()`**: Guarda en la base de datos
- **`make()`**: Crea sin guardar (útil para testing)

## 🚀 Optimización

La optimización es crucial para el rendimiento de la aplicación. Laravel proporciona varias técnicas para mejorar la velocidad y eficiencia.

**¿Por qué es importante la optimización?**
- **Experiencia de usuario**: Aplicaciones rápidas son más agradables de usar
- **Costos**: Menos recursos del servidor = menos costos
- **SEO**: Google favorece sitios rápidos
- **Escalabilidad**: Aplicaciones optimizadas manejan más usuarios

### Optimización de Consultas

El problema N+1 es común en Laravel. Ocurre cuando haces una consulta para obtener registros y luego otra consulta por cada registro para obtener datos relacionados:

```php
// ❌ N+1 Problem - Muy ineficiente
$users = User::all(); // 1 consulta para obtener usuarios
foreach ($users as $user) {
    echo $user->services->count(); // 1 consulta adicional POR CADA usuario
}
// Si hay 100 usuarios, esto genera 101 consultas!

// ✅ Eager Loading - Solución eficiente
$users = User::with('services')->get(); // 2 consultas totales
foreach ($users as $user) {
    echo $user->services->count(); // Sin consultas adicionales
}
// Solo 2 consultas: una para usuarios, otra para servicios

// ✅ Eager Loading con Count - Aún más eficiente
$users = User::withCount('services')->get(); // 1 consulta con COUNT
foreach ($users as $user) {
    echo $user->services_count; // Count pre-calculado en la consulta principal
}
// Solo 1 consulta con COUNT incluido
```

**Explicación del problema N+1:**

- **Problema**: Consultas innecesarias que ralentizan la aplicación
- **Causa**: Cargar relaciones de forma perezosa (lazy loading)
- **Solución**: Eager loading con `with()` o `withCount()`
- **Impacto**: Puede reducir consultas de 101 a solo 2

### Optimización de Base de Datos

Los índices mejoran significativamente la velocidad de las consultas. Debes agregar índices en las columnas que usas frecuentemente en WHERE, ORDER BY y JOIN:

```php
// Índices en migraciones - Mejoran la velocidad de consultas
public function up(): void
{
    Schema::create('services', function (Blueprint $table) {
        $table->id(); // Índice primario automático
        $table->string('name');
        $table->text('description');
        $table->decimal('price', 8, 2);
        $table->integer('duration');
        $table->foreignId('user_id')->constrained(); // Índice de clave foránea automático
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        // Índices compuestos para consultas complejas
        $table->index(['user_id', 'is_active']); // Para filtrar servicios activos de un usuario
        $table->index('price'); // Para ordenar por precio
        $table->index('name'); // Para búsquedas por nombre
    });
}
```

**Explicación de los índices:**

- **Índice primario**: Automático en la columna `id`
- **Índice de clave foránea**: Automático en `user_id`
- **Índice compuesto**: Para consultas que filtran por múltiples columnas
- **Índice simple**: Para una sola columna
- **Impacto**: Puede mejorar consultas de segundos a milisegundos

### Cache

El cache almacena datos frecuentemente accedidos en memoria para acceso rápido:

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

**Explicación del cache:**

- **`Cache::remember()`**: Guarda datos por un tiempo específico
- **Primer parámetro**: Clave única para identificar los datos
- **Segundo parámetro**: Tiempo en segundos (3600 = 1 hora)
- **Tercer parámetro**: Función que genera los datos si no están en cache
- **Beneficio**: Evita consultas costosas repetidas

### Optimización de Imágenes

La optimización de imágenes reduce el tamaño de archivo manteniendo buena calidad:

```php
// Intervention Image para optimización
use Intervention\Image\Facades\Image;

public function store(Request $request)
{
    $image = $request->file('image');
    
    // Optimizar imagen
    $optimizedImage = Image::make($image)
        ->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio(); // Mantener proporción
            $constraint->upsize();      // No agrandar si es pequeña
        })
        ->encode('jpg', 80); // Calidad 80% (buen balance calidad/tamaño)
    
    $path = $image->store('services', 'public');
    
    // Guardar imagen optimizada
    Storage::disk('public')->put($path, $optimizedImage);
}
```

**Explicación de la optimización de imágenes:**

- **`resize()`**: Redimensiona la imagen a dimensiones específicas
- **`aspectRatio()`**: Mantiene la proporción original
- **`upsize()`**: Evita agrandar imágenes pequeñas
- **`encode('jpg', 80)`**: Convierte a JPEG con 80% de calidad
- **Beneficio**: Reduce tamaño de archivo significativamente

## 📊 Métricas y Monitoreo

### Logging de Performance

Registrar métricas de rendimiento ayuda a identificar cuellos de botella:

```php
// Middleware para medir tiempo de respuesta
class PerformanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true); // Tiempo de inicio
        
        $response = $next($request); // Procesar la petición
        
        $duration = microtime(true) - $start; // Calcular duración
        
        Log::info('Request Performance', [
            'url' => $request->url(),
            'method' => $request->method(),
            'duration' => $duration, // Tiempo en segundos
            'memory' => memory_get_peak_usage(true) // Memoria máxima usada
        ]);
        
        return $response;
    }
}
```

**Explicación del logging de performance:**

- **`microtime(true)`**: Obtiene tiempo actual con precisión de microsegundos
- **`memory_get_peak_usage(true)`**: Obtiene el pico de memoria usado en bytes
- **Logging**: Registra métricas para análisis posterior
- **Identificación**: Ayuda a encontrar rutas lentas

### Health Checks

Los health checks verifican que todos los servicios están funcionando:

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

**Explicación de health checks:**

- **Base de datos**: Verifica que se puede conectar y hacer consultas
- **Cache**: Verifica que el sistema de cache funciona
- **Storage**: Verifica que se puede acceder a archivos
- **Monitoreo**: Útil para detectar problemas automáticamente

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

**Explicación de los comandos:**

- **`--coverage`**: Muestra qué porcentaje del código está cubierto por tests
- **`--filter`**: Ejecuta solo tests que coincidan con el patrón
- **`--parallel`**: Ejecuta tests en paralelo (más rápido)
- **`-v`**: Modo verbose, muestra más detalles
- **`--coverage-html`**: Genera reporte visual de cobertura

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

**Explicación de las mejores prácticas:**

- **Nombres descriptivos**: `test_user_can_create_service` es mejor que `test_create()`
- **Arrange-Act-Assert**: Estructura clara que facilita entender el test
- **Tests independientes**: No deben depender de otros tests
- **Factories**: Generan datos consistentes y realistas
- **Mocks**: Simulan dependencias externas para aislar el código
- **Assertions específicas**: `assertEquals(5, $count)` es mejor que `assertTrue($count > 0)`
- **Cleanup**: `RefreshDatabase` limpia automáticamente
- **Performance**: Tests rápidos se ejecutan más frecuentemente

Esta documentación cubre los aspectos fundamentales de testing y optimización en Laravel, proporcionando ejemplos prácticos y mejores prácticas para garantizar la calidad del código. 