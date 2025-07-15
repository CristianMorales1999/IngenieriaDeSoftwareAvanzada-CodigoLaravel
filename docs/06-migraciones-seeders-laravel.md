# 🗄️ Migraciones y Seeders en Laravel 12

## 📋 **¿Qué son las Migraciones?**

Las migraciones son como un "control de versiones" para tu base de datos. Te permiten crear, modificar y eliminar tablas de manera programática, manteniendo un historial de cambios y permitiendo que otros desarrolladores (o servidores) tengan la misma estructura de base de datos. Es como Git pero para la base de datos: puedes avanzar, retroceder y compartir la estructura de tu base de datos de forma segura y ordenada.

**¿Por qué usar migraciones?**
- **Sincronización**: Todos los desarrolladores y entornos tienen la misma estructura de base de datos.
- **Automatización**: No necesitas escribir SQL manualmente ni preocuparte por errores de sintaxis.
- **Historial**: Puedes ver qué cambios se han hecho, cuándo y por quién.
- **Rollback**: Si algo sale mal, puedes revertir los cambios fácilmente.
- **Colaboración**: Facilita el trabajo en equipo y el despliegue en producción.

### 🎯 **Características Principales**
- **Control de versiones**: Historial de cambios en la BD (qué se cambió, cuándo, por quién)
- **Reproducibilidad**: Misma estructura en todos los entornos (desarrollo, staging, producción)
- **Rollback**: Revertir cambios si es necesario (deshacer migraciones)
- **Colaboración**: Múltiples desarrolladores sincronizados (todos tienen la misma BD)
- **Automatización**: Despliegue automático de cambios (no más scripts SQL manuales)
- **Seguridad**: Cambios controlados y documentados

## 🚀 **Creación de Migraciones**

### 📋 **Comandos Artisan**
Los comandos para crear migraciones siguen convenciones de nombres que describen la acción a realizar. Esto ayuda a que el propósito de cada migración sea claro para cualquier desarrollador:

```bash
# Crear migración básica - Para crear una nueva tabla
docstring
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
- **create_*_table**: Para crear nuevas tablas. Laravel infiere automáticamente el nombre de la tabla.
- **add_*_to_*_table**: Para agregar columnas a tablas existentes. Laravel sabe a qué tabla agregar la columna.
- **modify_*_in_*_table**: Para modificar columnas existentes. Útil para cambios de tipo o propiedades.
- **remove_*_from_*_table**: Para eliminar columnas. Deja claro qué columna se elimina y de qué tabla.
- **rename_*_table**: Para renombrar tablas.

**Ventajas de seguir estas convenciones:**
- Nombres descriptivos que explican qué hace la migración
- Fácil de entender para otros desarrolladores
- Laravel puede inferir automáticamente algunas acciones

### 📝 **Estructura de una Migración**
Una migración tiene dos métodos principales: `up()` para aplicar cambios y `down()` para revertirlos. Esto permite avanzar y retroceder en la estructura de la base de datos:

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
- **up()**: Define qué hacer cuando se ejecuta la migración (crear tabla, agregar columnas, etc.). Aquí describes cómo debe quedar la estructura de la tabla.
- **down()**: Define cómo revertir los cambios (eliminar tabla, quitar columnas, etc.). Es importante para poder deshacer cambios si algo sale mal.
- **Blueprint**: Clase que proporciona métodos para definir la estructura de la tabla (tipos de columnas, índices, claves foráneas, etc.).
- **Schema**: Facade que maneja las operaciones de base de datos (crear, modificar, eliminar tablas).

## 🏗️ **Tipos de Columnas**

### 📝 **Columnas Básicas**

Las migraciones permiten definir muchos tipos de columnas. Aquí tienes los más comunes y para qué se usan:

```php
// Enteros
$table->id();                    // BIGINT UNSIGNED AUTO_INCREMENT (clave primaria estándar)
$table->bigIncrements('id');     // BIGINT UNSIGNED AUTO_INCREMENT
$table->increments('id');        // INT UNSIGNED AUTO_INCREMENT
$table->integer('edad');         // INT (números enteros)
$table->bigInteger('telefono');  // BIGINT (números grandes)
$table->smallInteger('stock');   // SMALLINT (números pequeños)
$table->tinyInteger('activo');   // TINYINT (booleanos o flags)

// Texto
$table->string('nombre');        // VARCHAR(255) (texto corto)
$table->string('email', 100);    // VARCHAR(100) (texto corto con longitud específica)
$table->text('descripcion');     // TEXT (texto largo)
$table->longText('contenido');   // LONGTEXT (texto muy largo)
$table->char('codigo', 10);      // CHAR(10) (texto fijo)

// Números decimales
$table->decimal('precio', 8, 2); // DECIMAL(8,2) (precios, montos)
$table->float('rating', 3, 2);   // FLOAT(3,2) (decimales pequeños)
$table->double('valor', 10, 4);  // DOUBLE(10,4) (decimales grandes)

// Fechas
$table->date('fecha_nacimiento');     // DATE (solo fecha)
$table->datetime('fecha_creacion');   // DATETIME (fecha y hora)
$table->timestamp('ultimo_acceso');   // TIMESTAMP (fecha y hora, para logs)
$table->time('hora_inicio');          // TIME (solo hora)
$table->year('año');                  // YEAR (año)

// Booleanos
$table->boolean('activo');        // TINYINT(1) (true/false)
$table->boolean('es_premium');    // TINYINT(1) (true/false)

// Otros
$table->json('configuracion');    // JSON (datos estructurados)
$table->binary('archivo');        // BLOB (archivos binarios)
$table->uuid('identificador');    // CHAR(36) (identificadores únicos)
```

**Explicación de cada tipo:**
- Usa `id()` para claves primarias estándar.
- Usa `string()` para texto corto, `text()` para descripciones largas.
- Usa `decimal()` para precios y montos, especificando la precisión.
- Usa `boolean()` para campos verdadero/falso.
- Usa `json()` para guardar configuraciones o datos complejos.

### 🎯 **Columnas con Modificadores**

Puedes agregar modificadores a las columnas para controlar su comportamiento:

```php
// Nullable
$table->string('apellido')->nullable(); // Permite valores NULL

// Valores por defecto
$table->boolean('activo')->default(true); // Valor por defecto true
$table->string('estado')->default('pendiente'); // Valor por defecto 'pendiente'

// Índices
$table->string('email')->unique(); // Índice único (no se repite)
$table->string('codigo')->index(); // Índice normal (mejora búsquedas)
$table->index(['categoria_id', 'activo']); // Índice compuesto

// Comentarios
$table->text('descripcion')->comment('Descripción detallada del servicio');

// Después de otra columna
$table->string('apellido')->after('nombre'); // Coloca la columna después de 'nombre'

// Primera columna
$table->string('codigo')->first(); // Coloca la columna al inicio
```

**¿Por qué usar modificadores?**
- **nullable()**: Permite que el campo no tenga valor (NULL).
- **default()**: Define un valor por defecto si no se especifica.
- **unique()**: Garantiza que el valor no se repita en la tabla.
- **index()**: Mejora la velocidad de búsqueda en ese campo.
- **comment()**: Documenta el propósito del campo.

## 🔗 **Relaciones y Claves Foráneas**

Las claves foráneas permiten conectar tablas y mantener la integridad referencial. Así, si borras una categoría, puedes decidir qué pasa con los servicios relacionados.

### 📝 **Claves Foráneas Básicas**

```php
// Clave foránea simple
$table->foreignId('usuario_id')->constrained(); // Relaciona con tabla 'users' por convención

// Clave foránea con tabla específica
$table->foreignId('categoria_id')->constrained('categorias'); // Relaciona con tabla 'categorias'

// Clave foránea con eliminación en cascada
$table->foreignId('usuario_id')->constrained()->onDelete('cascade'); // Si se borra el usuario, se borran sus servicios

// Clave foránea con actualización en cascada
$table->foreignId('categoria_id')->constrained()->onUpdate('cascade'); // Si cambia el id de la categoría, se actualiza aquí

// Clave foránea con eliminación en null
$table->foreignId('usuario_id')->constrained()->onDelete('set null'); // Si se borra el usuario, el campo queda en null
```

**Explicación:**
- **constrained()**: Laravel infiere la tabla y la columna a relacionar.
- **onDelete('cascade')**: Borra los registros hijos si se borra el padre.
- **onUpdate('cascade')**: Actualiza los registros hijos si cambia el id del padre.
- **onDelete('set null')**: Deja el campo en null si se borra el padre.

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

**Explicación:**
- **muchos a muchos**: Se usa una tabla intermedia con claves foráneas a ambas tablas.
- **polimórfica**: Permite que un comentario pertenezca a diferentes modelos (servicio, reseña, etc.).
- **unique()**: Evita duplicados en la relación.

## 📊 **Migración Completa: Sistema de Servicios**

A continuación, ejemplos completos de migraciones para un sistema real:

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

**Explicación:**
- Crea la tabla `categorias` con campos para nombre, slug, descripción, imagen, estado y orden.
- Usa índices para optimizar búsquedas por estado y orden.

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

**Explicación:**
- Crea la tabla `servicios` con campos para nombre, slug, descripciones, precios, claves foráneas, imagen, galería, estado, fechas, vistas y rating.
- Usa múltiples índices para optimizar búsquedas y filtros frecuentes.
- Define claves foráneas con diferentes reglas de borrado.

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

**Explicación:**
- Crea la tabla `reseñas` para almacenar opiniones de usuarios sobre servicios.
- Usa claves foráneas y restricciones para evitar duplicados.

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

**Explicación:**
- Crea la tabla de etiquetas y la tabla intermedia para la relación muchos a muchos con servicios.
- Usa claves foráneas y restricciones para mantener la integridad.

## 🔧 **Modificación de Estructuras**

A medida que tu aplicación crece, puedes necesitar modificar tablas existentes. Laravel lo hace fácil y seguro:

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

**Explicación:**
- Usa `Schema::table` para modificar una tabla existente.
- Puedes agregar columnas en cualquier posición.
- El método `down()` elimina las columnas agregadas.

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

**Explicación:**
- Usa `change()` para modificar el tipo o propiedades de una columna.
- Usa `renameColumn()` para cambiar el nombre de una columna.
- El método `down()` revierte los cambios.

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

**Explicación:**
- Usa `dropColumn()` para eliminar columnas.
- El método `down()` las vuelve a crear si necesitas revertir.

## 🌱 **Seeders (Datos de Prueba)**

Los seeders permiten poblar la base de datos con datos de ejemplo o iniciales. Son útiles para testing, desarrollo y para tener datos realistas en tu aplicación.

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

**Explicación:**
- Usa `make:seeder` para crear un seeder.
- Usa `db:seed` para ejecutar todos los seeders registrados en `DatabaseSeeder`.
- Usa `--class` para ejecutar un seeder específico.
- Usa `--force` para ejecutar en producción (requiere confirmación).

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

**Explicación:**
- Crea varias categorías con datos realistas.
- Usa `Str::slug()` para generar slugs amigables para URLs.
- Usa un array para definir los datos y un bucle para insertarlos.

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

**Explicación:**
- Crea servicios de ejemplo, relacionándolos con categorías y usuarios existentes.
- Usa `Str::slug()` y `Str::limit()` para generar slugs y descripciones cortas.
- Usa datos realistas y relaciones para poblar la base de datos.

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

**Explicación:**
- Ejecuta todos los seeders en el orden correcto para mantener la integridad referencial.
- Puedes agregar o quitar seeders según tus necesidades.

## 🏭 **Factories (Datos Aleatorios)**

Las factories permiten generar datos aleatorios y realistas para testing y desarrollo. Se usan junto con seeders y tests.

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

**Explicación:**
- Usa Faker para generar nombres, descripciones e imágenes aleatorias.
- Permite crear categorías activas o inactivas según el estado.

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

**Explicación:**
- Genera servicios con datos aleatorios y relaciones a categorías y usuarios.
- Permite crear servicios con diferentes estados (inactivo, destacado, premium).

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

**Explicación:**
- Usa `migrate` para aplicar todos los cambios pendientes.
- Usa `rollback` para deshacer la última migración.
- Usa `refresh` para reiniciar la base de datos desde cero.
- Usa `--seed` para poblar la base de datos después de migrar.

### 📋 **Ejecutar Seeders**

```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=CategoriaSeeder

# Ejecutar seeders en modo producción
php artisan db:seed --force
```

**Explicación:**
- Usa `db:seed` para poblar la base de datos con datos de ejemplo.
- Usa `--class` para ejecutar un seeder específico.
- Usa `--force` para ejecutar en producción.

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

**Explicación:**
- Usa Tinker para probar factories y seeders de forma interactiva.
- Puedes crear datos de prueba rápidamente para testing y desarrollo.

## 🎯 **Buenas Prácticas**

### ✅ **Migraciones**
- **Siempre** hacer rollback posible: El método `down()` debe revertir todos los cambios hechos en `up()`.
- **Usar** nombres descriptivos para migraciones: Facilita entender el propósito de cada migración.
- **Agregar** índices para consultas frecuentes: Mejora el rendimiento de la base de datos.
- **Validar** integridad referencial: Usa claves foráneas y restricciones para evitar datos huérfanos.
- **Documentar** cambios complejos: Usa comentarios en el código para explicar decisiones importantes.

### ✅ **Seeders**
- **Ordenar** por dependencias: Ejecuta seeders en el orden correcto para evitar errores de claves foráneas.
- **Usar** factories cuando sea posible: Genera datos realistas y variados.
- **Crear** datos realistas: Usa Faker y datos similares a los reales.
- **Validar** datos antes de insertar: Asegúrate de que los datos cumplen las reglas del modelo.
- **Usar** transacciones para grandes volúmenes: Evita inconsistencias si ocurre un error.

### ✅ **Factories**
- **Crear** estados útiles (inactivo, premium, etc.): Facilita testing de diferentes escenarios.
- **Usar** datos realistas: Mejora la calidad de las pruebas.
- **Relacionar** con otros modelos: Mantiene la integridad referencial.
- **Optimizar** para grandes volúmenes: Usa métodos eficientes para crear muchos registros.
- **Documentar** estados especiales: Explica para qué sirve cada estado en la factory.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 