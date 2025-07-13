# 🗄️ Migraciones y Seeders en Laravel 12

## 📋 **¿Qué son las Migraciones?**

Las migraciones son como un "control de versiones" para tu base de datos. Te permiten crear, modificar y eliminar tablas de manera programática, manteniendo un historial de cambios y permitiendo que otros desarrolladores (o servidores) tengan la misma estructura de base de datos. Es como Git pero para la base de datos.

### 🎯 **Características Principales**
- **Control de versiones**: Historial de cambios en la BD (qué se cambió, cuándo, por quién)
- **Reproducibilidad**: Misma estructura en todos los entornos (desarrollo, staging, producción)
- **Rollback**: Revertir cambios si es necesario (deshacer migraciones)
- **Colaboración**: Múltiples desarrolladores sincronizados (todos tienen la misma BD)
- **Automatización**: Despliegue automático de cambios (no más scripts SQL manuales)
- **Seguridad**: Cambios controlados y documentados

## 🚀 **Creación de Migraciones**

### 📋 **Comandos Artisan**
Los comandos para crear migraciones siguen convenciones de nombres que describen la acción a realizar:

```bash
# Crear migración básica - Para crear una nueva tabla
php artisan make:migration create_servicios_table

# Crear migración para agregar columna - Para modificar una tabla existente
php artisan make:migration add_precio_to_servicios_table

# Crear migración para modificar columna - Para cambiar tipo o propiedades
php artisan make:migration modify_descripcion_in_servicios_table

# Crear migración para eliminar columna - Para quitar columnas no necesarias
php artisan make:migration remove_imagen_from_servicios_table

# Crear migración con modelo - Crea modelo y migración juntos
php artisan make:model Servicio -m
```

**Explicación de las convenciones de nombres:**
- **create_*_table**: Para crear nuevas tablas
- **add_*_to_*_table**: Para agregar columnas a tablas existentes
- **modify_*_in_*_table**: Para modificar columnas existentes
- **remove_*_from_*_table**: Para eliminar columnas
- **rename_*_table**: Para renombrar tablas

**Ventajas de seguir estas convenciones:**
- Nombres descriptivos que explican qué hace la migración
- Fácil de entender para otros desarrolladores
- Laravel puede inferir automáticamente algunas acciones

### 📝 **Estructura de una Migración**
Una migración tiene dos métodos principales: `up()` para aplicar cambios y `down()` para revertirlos:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     * Se ejecuta cuando haces php artisan migrate
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id(); // Clave primaria autoincremental
            $table->string('nombre'); // Campo de texto (VARCHAR)
            $table->text('descripcion'); // Campo de texto largo (TEXT)
            $table->decimal('precio', 8, 2); // Número decimal con 8 dígitos totales, 2 decimales
            $table->foreignId('categoria_id')->constrained(); // Clave foránea a tabla categorias
            $table->foreignId('usuario_id')->constrained(); // Clave foránea a tabla users
            $table->string('imagen')->nullable(); // Campo opcional (puede ser NULL)
            $table->boolean('activo')->default(true); // Campo booleano con valor por defecto
            $table->timestamp('fecha_inicio')->nullable(); // Fecha y hora opcional
            $table->timestamp('fecha_fin')->nullable(); // Fecha y hora opcional
            $table->timestamps(); // Crea created_at y updated_at automáticamente
        });
    }

    /**
     * Revertir las migraciones.
     * Se ejecuta cuando haces php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios'); // Elimina la tabla si existe
    }
};
```

**Explicación de los métodos:**
- **up()**: Define qué hacer cuando se ejecuta la migración (crear tabla, agregar columnas, etc.)
- **down()**: Define cómo revertir los cambios (eliminar tabla, quitar columnas, etc.)
- **Blueprint**: Clase que proporciona métodos para definir la estructura de la tabla
- **Schema**: Facade que maneja las operaciones de base de datos

## 🏗️ **Tipos de Columnas**

### 📝 **Columnas Básicas**
```php
// Enteros
$table->id();                    // BIGINT UNSIGNED AUTO_INCREMENT
$table->bigIncrements('id');     // BIGINT UNSIGNED AUTO_INCREMENT
$table->increments('id');        // INT UNSIGNED AUTO_INCREMENT
$table->integer('edad');         // INT
$table->bigInteger('telefono');  // BIGINT
$table->smallInteger('stock');   // SMALLINT
$table->tinyInteger('activo');   // TINYINT

// Texto
$table->string('nombre');        // VARCHAR(255)
$table->string('email', 100);    // VARCHAR(100)
$table->text('descripcion');     // TEXT
$table->longText('contenido');   // LONGTEXT
$table->char('codigo', 10);      // CHAR(10)

// Números decimales
$table->decimal('precio', 8, 2); // DECIMAL(8,2)
$table->float('rating', 3, 2);   // FLOAT(3,2)
$table->double('valor', 10, 4);  // DOUBLE(10,4)

// Fechas
$table->date('fecha_nacimiento');     // DATE
$table->datetime('fecha_creacion');   // DATETIME
$table->timestamp('ultimo_acceso');   // TIMESTAMP
$table->time('hora_inicio');          // TIME
$table->year('año');                  // YEAR

// Booleanos
$table->boolean('activo');        // TINYINT(1)
$table->boolean('es_premium');    // TINYINT(1)

// Otros
$table->json('configuracion');    // JSON
$table->binary('archivo');        // BLOB
$table->uuid('identificador');    // CHAR(36)
```

### 🎯 **Columnas con Modificadores**
```php
// Nullable
$table->string('apellido')->nullable();

// Valores por defecto
$table->boolean('activo')->default(true);
$table->string('estado')->default('pendiente');

// Índices
$table->string('email')->unique();
$table->string('codigo')->index();
$table->index(['categoria_id', 'activo']);

// Comentarios
$table->text('descripcion')->comment('Descripción detallada del servicio');

// Después de otra columna
$table->string('apellido')->after('nombre');

// Primera columna
$table->string('codigo')->first();
```

## 🔗 **Relaciones y Claves Foráneas**

### 📝 **Claves Foráneas Básicas**
```php
// Clave foránea simple
$table->foreignId('usuario_id')->constrained();

// Clave foránea con tabla específica
$table->foreignId('categoria_id')->constrained('categorias');

// Clave foránea con eliminación en cascada
$table->foreignId('usuario_id')->constrained()->onDelete('cascade');

// Clave foránea con actualización en cascada
$table->foreignId('categoria_id')->constrained()->onUpdate('cascade');

// Clave foránea con eliminación en null
$table->foreignId('usuario_id')->constrained()->onDelete('set null');
```

### 🎯 **Relaciones Complejas**
```php
// Relación muchos a muchos
Schema::create('servicio_etiqueta', function (Blueprint $table) {
    $table->id();
    $table->foreignId('servicio_id')->constrained()->onDelete('cascade');
    $table->foreignId('etiqueta_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    // Índice compuesto único
    $table->unique(['servicio_id', 'etiqueta_id']);
});

// Relación polimórfica
Schema::create('comentarios', function (Blueprint $table) {
    $table->id();
    $table->text('contenido');
    $table->morphs('comentable'); // Crea commentable_type y commentable_id
    $table->foreignId('usuario_id')->constrained();
    $table->timestamps();
});
```

## 📊 **Migración Completa: Sistema de Servicios**

### 🎯 **Migración de Categorías**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
            
            $table->index(['activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
```

### 🎯 **Migración de Servicios**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion');
            $table->text('descripcion_corta')->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->foreignId('categoria_id')->constrained()->onDelete('restrict');
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->string('imagen')->nullable();
            $table->json('galeria')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('destacado')->default(false);
            $table->integer('duracion_horas')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->integer('vistas')->default(0);
            $table->decimal('rating_promedio', 3, 2)->default(0);
            $table->integer('total_reseñas')->default(0);
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['activo', 'destacado']);
            $table->index(['categoria_id', 'activo']);
            $table->index(['precio', 'activo']);
            $table->index(['rating_promedio', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
```

### 🎯 **Migración de Reseñas**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseñas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->integer('puntuacion'); // 1-5 estrellas
            $table->text('comentario')->nullable();
            $table->boolean('verificado')->default(false);
            $table->boolean('recomendado')->default(true);
            $table->timestamps();
            
            // Un usuario solo puede reseñar un servicio una vez
            $table->unique(['servicio_id', 'usuario_id']);
            $table->index(['servicio_id', 'puntuacion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseñas');
    }
};
```

### 🎯 **Migración de Etiquetas (Muchos a Muchos)**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('color')->default('#3B82F6');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('etiqueta_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etiqueta_id')->constrained()->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['etiqueta_id', 'servicio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiqueta_servicio');
        Schema::dropIfExists('etiquetas');
    }
};
```

## 🔧 **Modificación de Estructuras**

### 📝 **Agregar Columnas**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Agregar columna después de precio
            $table->decimal('precio_oferta', 10, 2)->nullable()->after('precio');
            
            // Agregar columna al final
            $table->string('video_url')->nullable();
            
            // Agregar columna al inicio
            $table->string('codigo')->first();
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['precio_oferta', 'video_url', 'codigo']);
        });
    }
};
```

### 📝 **Modificar Columnas**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Cambiar tipo de columna
            $table->decimal('precio', 12, 2)->change();
            
            // Cambiar nombre de columna
            $table->renameColumn('descripcion', 'descripcion_completa');
            
            // Agregar nullable
            $table->string('imagen')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->decimal('precio', 8, 2)->change();
            $table->renameColumn('descripcion_completa', 'descripcion');
            $table->string('imagen')->change();
        });
    }
};
```

### 📝 **Eliminar Columnas**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['imagen_anterior', 'codigo_temporal']);
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('imagen_anterior')->nullable();
            $table->string('codigo_temporal')->nullable();
        });
    }
};
```

## 🌱 **Seeders (Datos de Prueba)**

### 📋 **Comandos Artisan**
```bash
# Crear seeder básico
php artisan make:seeder CategoriaSeeder

# Crear seeder de base de datos
php artisan make:seeder DatabaseSeeder

# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=CategoriaSeeder

# Ejecutar seeders en modo producción
php artisan db:seed --force
```

### 🎯 **Seeder de Categorías**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Desarrollo Web',
                'descripcion' => 'Servicios de desarrollo de sitios web y aplicaciones web',
                'imagen' => 'categorias/desarrollo-web.jpg',
                'orden' => 1
            ],
            [
                'nombre' => 'Consultoría IT',
                'descripcion' => 'Asesoramiento en tecnología y sistemas de información',
                'imagen' => 'categorias/consultoria-it.jpg',
                'orden' => 2
            ],
            [
                'nombre' => 'Diseño Gráfico',
                'descripcion' => 'Servicios de diseño gráfico y branding',
                'imagen' => 'categorias/diseno-grafico.jpg',
                'orden' => 3
            ],
            [
                'nombre' => 'Marketing Digital',
                'descripcion' => 'Estrategias de marketing digital y publicidad online',
                'imagen' => 'categorias/marketing-digital.jpg',
                'orden' => 4
            ],
            [
                'nombre' => 'Soporte Técnico',
                'descripcion' => 'Servicios de soporte técnico y mantenimiento',
                'imagen' => 'categorias/soporte-tecnico.jpg',
                'orden' => 5
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create([
                'nombre' => $categoria['nombre'],
                'slug' => Str::slug($categoria['nombre']),
                'descripcion' => $categoria['descripcion'],
                'imagen' => $categoria['imagen'],
                'orden' => $categoria['orden'],
                'activo' => true
            ]);
        }
    }
}
```

### 🎯 **Seeder de Servicios**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Str;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::all();
        $usuarios = User::all();

        if ($categorias->isEmpty() || $usuarios->isEmpty()) {
            $this->command->error('Debes ejecutar CategoriaSeeder y UserSeeder primero');
            return;
        }

        $servicios = [
            [
                'nombre' => 'Desarrollo de Sitio Web Corporativo',
                'descripcion' => 'Desarrollo completo de sitio web profesional para empresas con diseño responsive, SEO optimizado y panel de administración.',
                'precio' => 1500.00,
                'categoria' => 'Desarrollo Web',
                'duracion_horas' => 40,
                'destacado' => true
            ],
            [
                'nombre' => 'Consultoría en Transformación Digital',
                'descripcion' => 'Asesoramiento completo para la transformación digital de tu empresa, incluyendo análisis de procesos y recomendaciones tecnológicas.',
                'precio' => 2500.00,
                'categoria' => 'Consultoría IT',
                'duracion_horas' => 60,
                'destacado' => true
            ],
            [
                'nombre' => 'Diseño de Identidad Corporativa',
                'descripcion' => 'Creación completa de identidad visual corporativa: logo, colores, tipografías y manual de marca.',
                'precio' => 800.00,
                'categoria' => 'Diseño Gráfico',
                'duracion_horas' => 25,
                'destacado' => false
            ],
            [
                'nombre' => 'Campaña de Marketing Digital',
                'descripcion' => 'Estrategia completa de marketing digital: redes sociales, Google Ads, email marketing y análisis de resultados.',
                'precio' => 1200.00,
                'categoria' => 'Marketing Digital',
                'duracion_horas' => 30,
                'destacado' => true
            ],
            [
                'nombre' => 'Mantenimiento de Sistemas',
                'descripcion' => 'Servicio de mantenimiento preventivo y correctivo de sistemas informáticos y redes empresariales.',
                'precio' => 500.00,
                'categoria' => 'Soporte Técnico',
                'duracion_horas' => 20,
                'destacado' => false
            ]
        ];

        foreach ($servicios as $servicio) {
            $categoria = $categorias->where('nombre', $servicio['categoria'])->first();
            
            Servicio::create([
                'nombre' => $servicio['nombre'],
                'slug' => Str::slug($servicio['nombre']),
                'descripcion' => $servicio['descripcion'],
                'descripcion_corta' => Str::limit($servicio['descripcion'], 150),
                'precio' => $servicio['precio'],
                'categoria_id' => $categoria->id,
                'usuario_id' => $usuarios->random()->id,
                'duracion_horas' => $servicio['duracion_horas'],
                'destacado' => $servicio['destacado'],
                'activo' => true,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6)
            ]);
        }
    }
}
```

### 🎯 **DatabaseSeeder Principal**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Seeders en orden de dependencia
            UserSeeder::class,
            CategoriaSeeder::class,
            ServicioSeeder::class,
            ReseñaSeeder::class,
            EtiquetaSeeder::class,
        ]);
    }
}
```

## 🏭 **Factories (Datos Aleatorios)**

### 📝 **Factory de Categorías**
```php
<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        $nombre = $this->faker->unique()->words(2, true);
        
        return [
            'nombre' => ucwords($nombre),
            'slug' => Str::slug($nombre),
            'descripcion' => $this->faker->paragraph(),
            'imagen' => 'categorias/' . $this->faker->image('public/storage/categorias', 400, 300, null, false),
            'activo' => $this->faker->boolean(80),
            'orden' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Indica que la categoría está inactiva.
     */
    public function inactiva()
    {
        return $this->state(function (array $attributes) {
            return [
                'activo' => false,
            ];
        });
    }
}
```

### 📝 **Factory de Servicios**
```php
<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(4),
            'slug' => fn (array $attributes) => Str::slug($attributes['nombre']),
            'descripcion' => $this->faker->paragraphs(3, true),
            'descripcion_corta' => $this->faker->sentence(20),
            'precio' => $this->faker->randomFloat(2, 50, 2000),
            'precio_anterior' => $this->faker->optional(0.3)->randomFloat(2, 100, 2500),
            'categoria_id' => Categoria::factory(),
            'usuario_id' => User::factory(),
            'imagen' => $this->faker->optional(0.7)->image('public/storage/servicios', 800, 600, null, false),
            'galeria' => $this->faker->optional(0.5)->randomElements([
                'galeria/servicio1.jpg',
                'galeria/servicio2.jpg',
                'galeria/servicio3.jpg'
            ], $this->faker->numberBetween(1, 3)),
            'activo' => $this->faker->boolean(85),
            'destacado' => $this->faker->boolean(20),
            'duracion_horas' => $this->faker->optional()->numberBetween(5, 100),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'fecha_fin' => $this->faker->dateTimeBetween('now', '+1 year'),
            'vistas' => $this->faker->numberBetween(0, 1000),
            'rating_promedio' => $this->faker->randomFloat(2, 1, 5),
            'total_reseñas' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indica que el servicio está inactivo.
     */
    public function inactivo()
    {
        return $this->state(function (array $attributes) {
            return [
                'activo' => false,
            ];
        });
    }

    /**
     * Indica que el servicio es destacado.
     */
    public function destacado()
    {
        return $this->state(function (array $attributes) {
            return [
                'destacado' => true,
            ];
        });
    }

    /**
     * Indica que el servicio es premium (precio alto).
     */
    public function premium()
    {
        return $this->state(function (array $attributes) {
            return [
                'precio' => $this->faker->randomFloat(2, 1000, 5000),
                'destacado' => true,
            ];
        });
    }
}
```

## 🎯 **Comandos Útiles**

### 📋 **Ejecutar Migraciones**
```bash
# Ejecutar migraciones pendientes
php artisan migrate

# Ejecutar migraciones en modo producción
php artisan migrate --force

# Ver estado de migraciones
php artisan migrate:status

# Revertir última migración
php artisan migrate:rollback

# Revertir todas las migraciones
php artisan migrate:reset

# Revertir y ejecutar todas las migraciones
php artisan migrate:refresh

# Revertir, ejecutar migraciones y seeders
php artisan migrate:refresh --seed
```

### 📋 **Ejecutar Seeders**
```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=CategoriaSeeder

# Ejecutar seeders en modo producción
php artisan db:seed --force
```

### 📋 **Factories en Tinker**
```bash
# Abrir Tinker
php artisan tinker

# Crear categoría
>>> Categoria::factory()->create();

# Crear 10 servicios
>>> Servicio::factory()->count(10)->create();

# Crear servicio con categoría específica
>>> Servicio::factory()->for(Categoria::find(1))->create();

# Crear servicio premium
>>> Servicio::factory()->premium()->create();
```

## 🎯 **Buenas Prácticas**

### ✅ **Migraciones**
- **Siempre** hacer rollback posible
- **Usar** nombres descriptivos para migraciones
- **Agregar** índices para consultas frecuentes
- **Validar** integridad referencial
- **Documentar** cambios complejos

### ✅ **Seeders**
- **Ordenar** por dependencias
- **Usar** factories cuando sea posible
- **Crear** datos realistas
- **Validar** datos antes de insertar
- **Usar** transacciones para grandes volúmenes

### ✅ **Factories**
- **Crear** estados útiles (inactivo, premium, etc.)
- **Usar** datos realistas
- **Relacionar** con otros modelos
- **Optimizar** para grandes volúmenes
- **Documentar** estados especiales

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 