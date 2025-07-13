# 🔧 Comandos Artisan en Laravel 12

## 📋 **¿Qué es Artisan?**

Artisan es la interfaz de línea de comandos incluida con Laravel. Proporciona comandos útiles para el desarrollo y mantenimiento de aplicaciones Laravel. Es como tener un asistente de desarrollo que automatiza tareas repetitivas.

### 🎯 **Características Principales**
- **Comandos integrados**: Funcionalidades básicas de Laravel (crear archivos, ejecutar migraciones, etc.)
- **Comandos personalizados**: Crear tus propios comandos para automatizar tareas específicas
- **Autocompletado**: Ayuda en la terminal con sugerencias de comandos
- **Ayuda integrada**: Documentación de cada comando con `--help`
- **Productividad**: Acelera el desarrollo automatizando tareas repetitivas

## 🚀 **Comandos Básicos de Artisan**

### 📋 **Ver Todos los Comandos Disponibles**
```bash
php artisan list
```
**Explicación:** Muestra todos los comandos Artisan disponibles en tu aplicación Laravel, incluyendo comandos integrados y personalizados.

### 📋 **Obtener Ayuda de un Comando**
```bash
php artisan help make:controller
php artisan make:controller --help
```
**Explicación:** Muestra la documentación completa de un comando específico, incluyendo opciones, argumentos y ejemplos de uso.

### 📋 **Ver Información de la Aplicación**
```bash
php artisan about
```
**Explicación:** Muestra información detallada sobre tu aplicación Laravel: versión, entorno, configuración de base de datos, etc.

## 🏗️ **Comandos de Generación (make:)**

### 🎮 **Controladores**
Los controladores manejan las peticiones HTTP y contienen la lógica de negocio de tu aplicación:

```bash
# Crear controlador básico - Solo la clase vacía
php artisan make:controller ServicioController

# Crear controlador con métodos CRUD - Incluye index, create, store, show, edit, update, destroy
php artisan make:controller ServicioController --resource

# Crear controlador con métodos específicos - Incluye métodos CRUD y relación con modelo
php artisan make:controller ServicioController --resource --model=Servicio

# Crear controlador API - Optimizado para APIs (sin métodos create/edit)
php artisan make:controller Api/ServicioController --api
```

**Explicación de las opciones:**
- **--resource**: Genera todos los métodos CRUD automáticamente
- **--model=Servicio**: Incluye inyección de dependencias del modelo
- **--api**: Genera métodos optimizados para APIs (sin vistas)
- **Api/**: Crea el controlador en la subcarpeta Api/ para organizar mejor

### 📊 **Modelos**
Los modelos representan las tablas de la base de datos y contienen la lógica de interacción con los datos:

```bash
# Crear modelo básico - Solo la clase del modelo
php artisan make:model Servicio

# Crear modelo con migración - Incluye archivo de migración para crear la tabla
php artisan make:model Servicio -m

# Crear modelo con migración, factory y seeder - Todo lo necesario para datos de prueba
php artisan make:model Servicio -mfs

# Crear modelo con todo - Incluye migración, factory, seeder y controlador
php artisan make:model Servicio -mfsr
```

**Explicación de las opciones:**
- **-m**: Crea una migración para definir la estructura de la tabla
- **-f**: Crea una factory para generar datos de prueba
- **-s**: Crea un seeder para poblar la base de datos con datos iniciales
- **-r**: Crea un controlador resource para manejar operaciones CRUD
- **-mfsr**: Combina todas las opciones anteriores (migración + factory + seeder + resource controller)

### 🗄️ **Migraciones**
Las migraciones son como un "control de versiones" para tu base de datos. Permiten crear, modificar y eliminar tablas de manera programática:

```bash
# Crear migración básica - Para crear una nueva tabla
php artisan make:migration create_servicios_table

# Crear migración para agregar columna - Para modificar una tabla existente
php artisan make:migration add_precio_to_servicios_table

# Crear migración para modificar columna - Para cambiar el tipo o propiedades de una columna
php artisan make:migration modify_descripcion_in_servicios_table
```

**Explicación de las convenciones de nombres:**
- **create_*_table**: Para crear nuevas tablas
- **add_*_to_*_table**: Para agregar columnas a tablas existentes
- **modify_*_in_*_table**: Para modificar columnas existentes
- **remove_*_from_*_table**: Para eliminar columnas
- **rename_*_table**: Para renombrar tablas

**Ventajas de las migraciones:**
- Control de versiones de la base de datos
- Reproducibilidad en diferentes entornos
- Posibilidad de revertir cambios
- Colaboración entre desarrolladores

### 🌱 **Seeders**
```bash
# Crear seeder básico
php artisan make:seeder ServicioSeeder

# Crear seeder de base de datos
php artisan make:seeder DatabaseSeeder
```

### 🏭 **Factories**
```bash
# Crear factory para modelo
php artisan make:factory ServicioFactory

# Crear factory con modelo específico
php artisan make:factory ServicioFactory --model=Servicio
```

### 🎨 **Vistas**
```bash
# Crear vista
php artisan make:view servicios.index

# Crear múltiples vistas
php artisan make:view servicios.index servicios.show servicios.create
```

### 🧩 **Componentes**
```bash
# Crear componente Blade
php artisan make:component ServicioCard

# Crear componente con vista
php artisan make:component ServicioCard --view

# Crear componente anónimo
php artisan make:component ServicioCard --anonymous
```

### 🔐 **Middleware**
```bash
# Crear middleware
php artisan make:middleware CheckAdmin

# Crear middleware con alias
php artisan make:middleware CheckAdmin --alias=admin
```

### 📝 **Requests (Form Requests)**
```bash
# Crear form request para validación
php artisan make:request StoreServicioRequest

# Crear form request para actualización
php artisan make:request UpdateServicioRequest
```

### 🎯 **Comandos Personalizados**
```bash
# Crear comando personalizado
php artisan make:command CrearServicioCommand

# Crear comando con argumentos
php artisan make:command CrearServicioCommand --command=servicio:crear
```

## 🗄️ **Comandos de Base de Datos**

### 📊 **Migraciones**
```bash
# Ejecutar migraciones pendientes
php artisan migrate

# Ejecutar migraciones en modo producción
php artisan migrate --force

# Revertir última migración
php artisan migrate:rollback

# Revertir todas las migraciones
php artisan migrate:reset

# Revertir y ejecutar todas las migraciones
php artisan migrate:refresh

# Revertir, ejecutar migraciones y seeders
php artisan migrate:refresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Ejecutar migración específica
php artisan migrate --path=/database/migrations/2024_01_01_000001_create_servicios_table.php
```

### 🌱 **Seeders**
```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=ServicioSeeder

# Ejecutar seeders en modo producción
php artisan db:seed --force
```

### 🏭 **Factories**
```bash
# Crear modelo usando factory
php artisan tinker
>>> Servicio::factory()->create();

# Crear múltiples modelos
>>> Servicio::factory()->count(10)->create();

# Crear modelo con datos específicos
>>> Servicio::factory()->create(['nombre' => 'Servicio Personalizado']);
```

## 🛣️ **Comandos de Rutas**

### 📋 **Listar Rutas**
```bash
# Ver todas las rutas
php artisan route:list

# Ver rutas con filtro
php artisan route:list --name=servicios

# Ver rutas por método
php artisan route:list --method=GET

# Ver rutas en formato JSON
php artisan route:list --json

# Ver rutas con detalles
php artisan route:list --verbose
```

### 🔧 **Cache de Rutas**
```bash
# Limpiar cache de rutas
php artisan route:clear

# Cachear rutas para producción
php artisan route:cache

# Ver rutas cacheadas
php artisan route:list --cached
```

## ⚙️ **Comandos de Configuración**

### 🔧 **Cache**
```bash
# Limpiar todo el cache
php artisan cache:clear

# Cachear configuración
php artisan config:cache

# Limpiar cache de configuración
php artisan config:clear

# Cachear rutas
php artisan route:cache

# Limpiar cache de rutas
php artisan route:clear

# Cachear vistas
php artisan view:cache

# Limpiar cache de vistas
php artisan view:clear

# Optimizar para producción
php artisan optimize
```

### 📋 **Configuración**
```bash
# Ver configuración de la aplicación
php artisan config:show

# Ver configuración específica
php artisan config:show app

# Limpiar cache de configuración
php artisan config:clear
```

## 🔐 **Comandos de Autenticación**

### 👤 **Autenticación Básica**
```bash
# Instalar Laravel Breeze
composer require laravel/breeze --dev
php artisan breeze:install

# Instalar Laravel Breeze con API
php artisan breeze:install api

# Instalar Laravel Breeze con Inertia
php artisan breeze:install inertia
```

### 🔑 **Generación de Claves**
```bash
# Generar clave de aplicación
php artisan key:generate

# Generar clave de API
php artisan key:generate --show
```

## 🧪 **Comandos de Testing**

### 🧪 **Tests**
```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=ServicioTest

# Ejecutar tests con coverage
php artisan test --coverage

# Ejecutar tests en paralelo
php artisan test --parallel
```

### 🏭 **Factories para Testing**
```bash
# Crear factory para testing
php artisan make:factory ServicioFactory --model=Servicio
```

## 🎯 **Comandos Útiles para Desarrollo**

### 📊 **Tinker (REPL)**
```bash
# Abrir Tinker para interactuar con la aplicación
php artisan tinker

# Ejemplos en Tinker:
>>> Servicio::all();
>>> Servicio::find(1);
>>> Servicio::where('precio', '>', 100)->get();
```

### 📋 **Listar Información**
```bash
# Ver información del entorno
php artisan env

# Ver información de la aplicación
php artisan about

# Ver información de la base de datos
php artisan db:show
```

### 🔧 **Mantenimiento**
```bash
# Poner aplicación en modo mantenimiento
php artisan down

# Poner aplicación en modo mantenimiento con mensaje
php artisan down --message="Actualizando sistema"

# Poner aplicación en modo mantenimiento con tiempo
php artisan down --retry=60

# Levantar aplicación del modo mantenimiento
php artisan up
```

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Crear CRUD Completo de Servicios**
```bash
# 1. Crear modelo con migración, factory, seeder y controlador
php artisan make:model Servicio -mfsr

# 2. Crear vistas
php artisan make:view servicios.index
php artisan make:view servicios.show
php artisan make:view servicios.create
php artisan make:view servicios.edit

# 3. Crear form requests para validación
php artisan make:request StoreServicioRequest
php artisan make:request UpdateServicioRequest

# 4. Crear componente para tarjeta de servicio
php artisan make:component ServicioCard --view

# 5. Ejecutar migraciones
php artisan migrate

# 6. Poblar base de datos con datos de prueba
php artisan db:seed --class=ServicioSeeder
```

### 🔐 **Configurar Autenticación**
```bash
# 1. Instalar Laravel Breeze
composer require laravel/breeze --dev
php artisan breeze:install

# 2. Instalar dependencias frontend
npm install

# 3. Compilar assets
npm run dev

# 4. Ejecutar migraciones
php artisan migrate

# 5. Generar clave de aplicación
php artisan key:generate
```

### 🧪 **Configurar Testing**
```bash
# 1. Crear test para servicios
php artisan make:test ServicioTest

# 2. Crear factory para testing
php artisan make:factory ServicioFactory --model=Servicio

# 3. Ejecutar tests
php artisan test

# 4. Ejecutar tests específicos
php artisan test --filter=ServicioTest
```

## 🎯 **Comandos Personalizados Útiles**

### 📊 **Comando para Crear Servicio**
```bash
# Crear comando personalizado
php artisan make:command CrearServicioCommand

# Implementar en app/Console/Commands/CrearServicioCommand.php
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Servicio;

class CrearServicioCommand extends Command
{
    protected $signature = 'servicio:crear {nombre} {precio}';
    protected $description = 'Crear un nuevo servicio';

    public function handle()
    {
        $nombre = $this->argument('nombre');
        $precio = $this->argument('precio');

        $servicio = Servicio::create([
            'nombre' => $nombre,
            'precio' => $precio,
            'descripcion' => 'Servicio creado por comando'
        ]);

        $this->info("Servicio '{$servicio->nombre}' creado exitosamente!");
    }
}
```

**Uso:**
```bash
php artisan servicio:crear "Consultoría IT" 150.00
```

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura de Comandos**
- Usar **kebab-case** para nombres de comandos
- Usar **verbos** descriptivos (crear, eliminar, actualizar)
- Usar **namespace** para organizar comandos

### ✅ **Argumentos y Opciones**
- Usar **argumentos** para datos obligatorios
- Usar **opciones** para datos opcionales
- Proporcionar **valores por defecto** cuando sea posible

### ✅ **Feedback al Usuario**
- Usar `$this->info()` para mensajes de éxito
- Usar `$this->error()` para mensajes de error
- Usar `$this->warn()` para advertencias
- Usar `$this->line()` para información general

### ✅ **Validación**
- Validar argumentos y opciones
- Proporcionar mensajes de error claros
- Usar confirmaciones para acciones destructivas

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 