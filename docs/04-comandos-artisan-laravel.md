# 🔧 Comandos Artisan en Laravel 12

## 📋 **¿Qué es Artisan?**

Artisan es la interfaz de línea de comandos incluida con Laravel. Proporciona comandos útiles para el desarrollo y mantenimiento de aplicaciones Laravel. Es como tener un asistente de desarrollo que automatiza tareas repetitivas. Artisan es tu "herramienta de trabajo" que te permite crear archivos, ejecutar tareas y gestionar tu aplicación desde la terminal.

**¿Por qué usar Artisan?**
- **Automatización**: Automatiza tareas repetitivas que harías manualmente
- **Consistencia**: Todos los archivos generados siguen las convenciones de Laravel
- **Productividad**: Acelera el desarrollo al generar código base automáticamente
- **Mantenimiento**: Facilita tareas de mantenimiento y administración del servidor
- **Despliegue**: Útil para tareas de despliegue y configuración en producción

### 🎯 **Características Principales**

**Comandos integrados**: Funcionalidades básicas de Laravel (crear archivos, ejecutar migraciones, etc.). Laravel viene con muchos comandos útiles ya incluidos.

**Comandos personalizados**: Crear tus propios comandos para automatizar tareas específicas. Puedes crear comandos para tareas únicas de tu aplicación.

**Autocompletado**: Ayuda en la terminal con sugerencias de comandos. Tu terminal te sugiere comandos mientras escribes.

**Ayuda integrada**: Documentación de cada comando con `--help`. Cada comando tiene su propia documentación detallada.

**Productividad**: Acelera el desarrollo automatizando tareas repetitivas. En lugar de crear archivos manualmente, Artisan los genera por ti.

## 🚀 **Comandos Básicos de Artisan**

### 📋 **Ver Todos los Comandos Disponibles**

Este comando te muestra todos los comandos que tienes disponibles en tu aplicación Laravel:

```bash
php artisan list
```

**Explicación detallada:** Muestra todos los comandos Artisan disponibles en tu aplicación Laravel, incluyendo comandos integrados y personalizados. Es como ver un "menú" de todas las herramientas que tienes disponibles. Los comandos están organizados por categorías como "make", "migrate", "route", etc.

**Cuándo usarlo:** Cuando quieres explorar qué comandos tienes disponibles o cuando olvidas el nombre exacto de un comando.

### 📋 **Obtener Ayuda de un Comando**

Cuando necesitas entender cómo usar un comando específico:

```bash
php artisan help make:controller
php artisan make:controller --help
```

**Explicación detallada:** Muestra la documentación completa de un comando específico, incluyendo opciones, argumentos y ejemplos de uso. Es como tener un "manual de instrucciones" para cada comando.

**Información que muestra:**
- Descripción del comando
- Argumentos requeridos y opcionales
- Opciones disponibles
- Ejemplos de uso
- Comandos relacionados

### 📋 **Ver Información de la Aplicación**

Para obtener información detallada sobre tu aplicación Laravel:

```bash
php artisan about
```

**Explicación detallada:** Muestra información detallada sobre tu aplicación Laravel: versión, entorno, configuración de base de datos, etc. Es útil para diagnosticar problemas o verificar la configuración de tu aplicación.

**Información que muestra:**
- Versión de Laravel
- Entorno actual (local, production, etc.)
- Configuración de base de datos
- Versión de PHP
- Extensiones PHP instaladas
- Configuración de cache

## 🏗️ **Comandos de Generación (make:)**

### 🎮 **Controladores**

Los controladores manejan las peticiones HTTP y contienen la lógica de negocio de tu aplicación. Son como el "cerebro" que decide qué hacer cuando alguien visita una página:

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

**Explicación detallada de las opciones:**

**--resource**: Genera todos los métodos CRUD automáticamente. Crea los 7 métodos estándar: index, create, store, show, edit, update, destroy. Es como tener un controlador completo listo para usar.

**--model=Servicio**: Incluye inyección de dependencias del modelo. Laravel automáticamente inyecta el modelo Servicio en los métodos que lo necesitan, como show, edit, update, destroy.

**--api**: Genera métodos optimizados para APIs (sin vistas). No incluye los métodos create y edit porque las APIs no muestran formularios HTML.

**Api/**: Crea el controlador en la subcarpeta Api/ para organizar mejor. Es útil para separar controladores web de controladores API.

### 📊 **Modelos**

Los modelos representan las tablas de la base de datos y contienen la lógica de interacción con los datos. Son como el "puente" entre tu código PHP y la base de datos:

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

**Explicación detallada de las opciones:**

**-m**: Crea una migración para definir la estructura de la tabla. La migración es como un "script" que crea la tabla en la base de datos con las columnas que necesitas.

**-f**: Crea una factory para generar datos de prueba. Las factories te permiten crear datos falsos pero realistas para testing y desarrollo.

**-s**: Crea un seeder para poblar la base de datos con datos iniciales. Los seeders insertan datos de ejemplo en tu base de datos.

**-r**: Crea un controlador resource para manejar operaciones CRUD. Genera automáticamente un controlador con todos los métodos necesarios.

**-mfsr**: Combina todas las opciones anteriores (migración + factory + seeder + resource controller). Es como crear todo lo necesario para un modelo completo de una vez.

### 🗄️ **Migraciones**

Las migraciones son como un "control de versiones" para tu base de datos. Permiten crear, modificar y eliminar tablas de manera programática. Son como "scripts" que definen los cambios en tu base de datos:

```bash
# Crear migración básica - Para crear una nueva tabla
php artisan make:migration create_servicios_table

# Crear migración para agregar columna - Para modificar una tabla existente
php artisan make:migration add_precio_to_servicios_table

# Crear migración para modificar columna - Para cambiar el tipo o propiedades de una columna
php artisan make:migration modify_descripcion_in_servicios_table
```

**Explicación detallada de las convenciones de nombres:**

**create_*_table**: Para crear nuevas tablas. Laravel automáticamente entiende que quieres crear una tabla nueva.

**add_*_to_*_table**: Para agregar columnas a tablas existentes. Laravel puede inferir automáticamente qué tabla modificar.

**modify_*_in_*_table**: Para modificar columnas existentes. Útil para cambiar el tipo de datos o propiedades de una columna.

**remove_*_from_*_table**: Para eliminar columnas. Para quitar columnas que ya no necesitas.

**rename_*_table**: Para renombrar tablas. Para cambiar el nombre de una tabla existente.

**Ventajas de las migraciones:**

**Control de versiones**: Mantienes un historial de todos los cambios en tu base de datos. Puedes ver qué cambió, cuándo y por qué.

**Reproducibilidad**: Misma estructura en todos los entornos (desarrollo, staging, producción). Todos los desarrolladores tienen la misma base de datos.

**Posibilidad de revertir**: Puedes deshacer cambios si algo sale mal. Las migraciones tienen un método `down()` para revertir cambios.

**Colaboración**: Múltiples desarrolladores pueden trabajar sin conflictos. Cada uno ejecuta las mismas migraciones.

### 🌱 **Seeders**

Los seeders insertan datos de ejemplo en tu base de datos. Son útiles para tener datos de prueba o datos iniciales de la aplicación:

```bash
# Crear seeder básico
php artisan make:seeder ServicioSeeder

# Crear seeder de base de datos
php artisan make:seeder DatabaseSeeder
```

**Explicación detallada:**

**ServicioSeeder**: Crea un seeder específico para el modelo Servicio. Contiene la lógica para insertar servicios de ejemplo.

**DatabaseSeeder**: Es el seeder principal que ejecuta todos los demás seeders. Es como el "coordinador" de todos los seeders.

### 🏭 **Factories**

Las factories generan datos de prueba automáticamente. Son útiles para crear datos realistas para testing:

```bash
# Crear factory para modelo
php artisan make:factory ServicioFactory

# Crear factory con modelo específico
php artisan make:factory ServicioFactory --model=Servicio
```

**Explicación detallada:**

**ServicioFactory**: Crea una factory que genera datos de prueba para el modelo Servicio. Puede crear nombres, descripciones y precios realistas.

**--model=Servicio**: Asocia la factory directamente con el modelo Servicio. Esto facilita el uso de la factory.

### 🎨 **Vistas**

Las vistas son las plantillas HTML que ven los usuarios. Artisan puede crear archivos de vista básicos:

```bash
# Crear vista
php artisan make:view servicios.index

# Crear múltiples vistas
php artisan make:view servicios.index servicios.show servicios.create
```

**Explicación detallada:**

**servicios.index**: Crea la vista para listar todos los servicios. Se crea en `resources/views/servicios/index.blade.php`.

**múltiples vistas**: Puedes crear varias vistas a la vez separándolas con espacios.

### 🧩 **Componentes**

Los componentes son elementos reutilizables en Blade. Son como "piezas de LEGO" que puedes usar en múltiples páginas:

```bash
# Crear componente Blade
php artisan make:component ServicioCard

# Crear componente con vista
php artisan make:component ServicioCard --view

# Crear componente anónimo
php artisan make:component ServicioCard --anonymous
```

**Explicación detallada:**

**ServicioCard**: Crea un componente para mostrar una tarjeta de servicio. Puedes usarlo en múltiples páginas.

**--view**: Crea también un archivo de vista para el componente. Útil para componentes complejos.

**--anonymous**: Crea un componente anónimo (sin clase PHP). Más simple para componentes básicos.

### 🔐 **Middleware**

Los middleware son filtros que se ejecutan antes o después de las peticiones HTTP. Pueden verificar autenticación, validar datos, etc.:

```bash
# Crear middleware
php artisan make:middleware CheckAdmin

# Crear middleware con alias
php artisan make:middleware CheckAdmin --alias=admin
```

**Explicación detallada:**

**CheckAdmin**: Crea un middleware que verifica si el usuario es administrador. Se ejecuta antes de mostrar ciertas páginas.

**--alias=admin**: Registra el middleware con el alias 'admin'. Permite usarlo fácilmente en las rutas.

### 📝 **Requests (Form Requests)**

Los Form Requests encapsulan la validación de formularios. Te permiten centralizar las reglas de validación:

```bash
# Crear form request para validación
php artisan make:request StoreServicioRequest

# Crear form request para actualización
php artisan make:request UpdateServicioRequest
```

**Explicación detallada:**

**StoreServicioRequest**: Para validar formularios de creación de servicios. Contiene reglas como "nombre requerido", "precio numérico", etc.

**UpdateServicioRequest**: Para validar formularios de actualización. Similar al anterior pero puede tener reglas diferentes.

### 🎯 **Comandos Personalizados**

Puedes crear tus propios comandos para automatizar tareas específicas de tu aplicación:

```bash
# Crear comando personalizado
php artisan make:command CrearServicioCommand

# Crear comando con argumentos
php artisan make:command CrearServicioCommand --command=servicio:crear
```

**Explicación detallada:**

**CrearServicioCommand**: Crea un comando personalizado para crear servicios desde la terminal.

**--command=servicio:crear**: Define el nombre del comando que usarás para ejecutarlo.

## 🗄️ **Comandos de Base de Datos**

### 📊 **Migraciones**

Los comandos de migración te permiten gestionar la estructura de tu base de datos:

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

**Explicación detallada de cada comando:**

**migrate**: Ejecuta todas las migraciones pendientes. Crea las tablas y columnas que aún no existen en tu base de datos.

**migrate --force**: Ejecuta migraciones en modo producción sin confirmación. Útil para despliegues automáticos.

**migrate:rollback**: Revierte la última migración ejecutada. Deshace el último cambio en la base de datos.

**migrate:reset**: Revierte todas las migraciones. Elimina todas las tablas y las vuelve a crear desde cero.

**migrate:refresh**: Combina reset y migrate. Útil para "reiniciar" completamente tu base de datos.

**migrate:refresh --seed**: Después de refrescar, ejecuta los seeders para poblar la base de datos con datos de ejemplo.

**migrate:status**: Muestra qué migraciones se han ejecutado y cuáles están pendientes.

**migrate --path**: Ejecuta una migración específica. Útil para migraciones que están en subcarpetas.

### 🌱 **Seeders**

Los seeders insertan datos de ejemplo en tu base de datos:

```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=ServicioSeeder

# Ejecutar seeders en modo producción
php artisan db:seed --force
```

**Explicación detallada:**

**db:seed**: Ejecuta todos los seeders registrados en DatabaseSeeder. Pobla tu base de datos con datos de ejemplo.

**db:seed --class=ServicioSeeder**: Ejecuta solo el seeder específico. Útil cuando solo quieres insertar ciertos datos.

**db:seed --force**: Ejecuta seeders sin confirmación. Útil para despliegues automáticos.

### 🏭 **Factories**

Las factories generan datos de prueba automáticamente. Se usan principalmente en Tinker:

```bash
# Crear modelo usando factory
php artisan tinker
>>> Servicio::factory()->create();

# Crear múltiples modelos
>>> Servicio::factory()->count(10)->create();

# Crear modelo con datos específicos
>>> Servicio::factory()->create(['nombre' => 'Servicio Personalizado']);
```

**Explicación detallada:**

**Servicio::factory()->create()**: Crea un servicio con datos aleatorios pero realistas.

**count(10)**: Crea 10 servicios de una vez. Útil para generar muchos datos de prueba.

**create(['nombre' => 'Servicio Personalizado'])**: Crea un servicio con datos específicos, pero mantiene los demás campos aleatorios.

## 🛣️ **Comandos de Rutas**

### 📋 **Listar Rutas**

Estos comandos te ayudan a explorar y gestionar las rutas de tu aplicación:

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

**Explicación detallada:**

**route:list**: Muestra todas las rutas registradas en tu aplicación. Incluye método HTTP, URL, nombre y controlador.

**--name=servicios**: Filtra rutas que contengan "servicios" en su nombre. Útil para encontrar rutas específicas.

**--method=GET**: Muestra solo rutas que usen el método HTTP GET. Útil para explorar rutas de lectura.

**--json**: Devuelve las rutas en formato JSON. Útil para procesamiento automático o integración con otras herramientas.

**--verbose**: Muestra información adicional como middleware aplicado, parámetros, etc.

### 🔧 **Cache de Rutas**

El cache de rutas mejora el rendimiento en producción:

```bash
# Limpiar cache de rutas
php artisan route:clear

# Cachear rutas para producción
php artisan route:cache

# Ver rutas cacheadas
php artisan route:list --cached
```

**Explicación detallada:**

**route:clear**: Limpia el cache de rutas. Útil en desarrollo cuando cambias las rutas.

**route:cache**: Cachea las rutas para mejor rendimiento. Importante en producción.

**route:list --cached**: Muestra solo las rutas que están cacheadas.

## ⚙️ **Comandos de Configuración**

### 🔧 **Cache**

El sistema de cache mejora el rendimiento de tu aplicación:

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

**Explicación detallada:**

**cache:clear**: Limpia todo el cache de la aplicación. Útil cuando cambias configuraciones.

**config:cache**: Cachea la configuración para mejor rendimiento. Importante en producción.

**config:clear**: Limpia el cache de configuración. Útil cuando cambias archivos de configuración.

**route:cache**: Cachea las rutas para mejor rendimiento. Importante en producción.

**route:clear**: Limpia el cache de rutas. Útil en desarrollo.

**view:cache**: Cachea las vistas Blade compiladas. Mejora el rendimiento en producción.

**view:clear**: Limpia el cache de vistas. Útil cuando cambias archivos Blade.

**optimize**: Optimiza la aplicación para producción. Combina varios comandos de cache.

### 📋 **Configuración**

Estos comandos te ayudan a explorar la configuración de tu aplicación:

```bash
# Ver configuración de la aplicación
php artisan config:show

# Ver configuración específica
php artisan config:show app

# Limpiar cache de configuración
php artisan config:clear
```

**Explicación detallada:**

**config:show**: Muestra todas las configuraciones activas de Laravel. Útil para verificar qué valores están siendo usados.

**config:show app**: Muestra solo la configuración de la aplicación. Útil para explorar configuraciones específicas.

**config:clear**: Limpia el cache de configuración. Útil cuando cambias archivos de configuración.

## 🔐 **Comandos de Autenticación**

### 👤 **Autenticación Básica**

Laravel Breeze proporciona un sistema de autenticación completo:

```bash
# Instalar Laravel Breeze
composer require laravel/breeze --dev
php artisan breeze:install

# Instalar Laravel Breeze con API
php artisan breeze:install api

# Instalar Laravel Breeze con Inertia
php artisan breeze:install inertia
```

**Explicación detallada:**

**breeze:install**: Instala un sistema de autenticación completo con login, registro, recuperación de contraseña, etc.

**breeze:install api**: Instala autenticación para APIs con tokens. Útil para aplicaciones móviles o frontend separado.

**breeze:install inertia**: Instala autenticación con Inertia.js. Útil para aplicaciones SPA con Laravel.

### 🔑 **Generación de Claves**

Las claves son importantes para la seguridad de tu aplicación:

```bash
# Generar clave de aplicación
php artisan key:generate

# Generar clave de API
php artisan key:generate --show
```

**Explicación detallada:**

**key:generate**: Genera la clave de aplicación que se usa para encriptar datos. Esencial para la seguridad.

**key:generate --show**: Muestra la clave actual sin cambiarla. Útil para verificar la configuración.

## 🧪 **Comandos de Testing**

### 🧪 **Tests**

Laravel incluye un sistema de testing completo:

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

**Explicación detallada:**

**test**: Ejecuta todos los tests de tu aplicación. Verifica que todo funcione correctamente.

**test --filter=ServicioTest**: Ejecuta solo los tests que contengan "ServicioTest" en su nombre.

**test --coverage**: Ejecuta tests y genera un reporte de cobertura. Muestra qué porcentaje del código está siendo probado.

**test --parallel**: Ejecuta tests en paralelo para mayor velocidad. Útil para suites de tests grandes.

### 🏭 **Factories para Testing**

Las factories son esenciales para crear datos de prueba:

```bash
# Crear factory para testing
php artisan make:factory ServicioFactory --model=Servicio
```

**Explicación detallada:**

**make:factory**: Crea una factory que genera datos de prueba realistas para el modelo Servicio.

**--model=Servicio**: Asocia la factory directamente con el modelo. Facilita el uso en tests.

## 🎯 **Comandos Útiles para Desarrollo**

### 📊 **Tinker (REPL)**

Tinker es un REPL (Read-Eval-Print Loop) que te permite interactuar con tu aplicación:

```bash
# Abrir Tinker para interactuar con la aplicación
php artisan tinker

# Ejemplos en Tinker:
>>> Servicio::all();
>>> Servicio::find(1);
>>> Servicio::where('precio', '>', 100)->get();
```

**Explicación detallada:**

**tinker**: Abre una consola interactiva donde puedes ejecutar código PHP y acceder a tu aplicación Laravel.

**Servicio::all()**: Obtiene todos los servicios de la base de datos.

**Servicio::find(1)**: Busca el servicio con ID 1.

**Servicio::where('precio', '>', 100)->get()**: Busca servicios con precio mayor a 100.

### 📋 **Listar Información**

Estos comandos te proporcionan información útil sobre tu aplicación:

```bash
# Ver información del entorno
php artisan env

# Ver información de la aplicación
php artisan about

# Ver información de la base de datos
php artisan db:show
```

**Explicación detallada:**

**env**: Muestra información sobre el entorno actual (local, production, etc.).

**about**: Muestra información detallada sobre la aplicación Laravel.

**db:show**: Muestra información sobre la conexión a la base de datos.

### 🔧 **Mantenimiento**

Estos comandos te ayudan a gestionar el estado de tu aplicación:

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

**Explicación detallada:**

**down**: Pone la aplicación en modo mantenimiento. Los usuarios ven una página de mantenimiento.

**down --message**: Personaliza el mensaje que ven los usuarios durante el mantenimiento.

**down --retry**: Define cuántos segundos esperar antes de reintentar la conexión.

**up**: Levanta la aplicación del modo mantenimiento. Los usuarios pueden acceder normalmente.

## 🎯 **Ejemplos Prácticos Completos**

### 📊 **Crear CRUD Completo de Servicios**

Un ejemplo completo de cómo crear un CRUD completo usando Artisan:

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

**Explicación detallada del proceso:**

**Paso 1**: Crea el modelo Servicio con todo lo necesario (migración, factory, seeder, controlador resource).

**Paso 2**: Crea las vistas para el CRUD (listar, mostrar, crear, editar).

**Paso 3**: Crea form requests para validar los datos de entrada.

**Paso 4**: Crea un componente reutilizable para mostrar las tarjetas de servicios.

**Paso 5**: Ejecuta las migraciones para crear la tabla en la base de datos.

**Paso 6**: Pobla la base de datos con datos de ejemplo para testing.

### 🔐 **Configurar Autenticación**

Configurar un sistema de autenticación completo:

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

**Explicación detallada del proceso:**

**Paso 1**: Instala Laravel Breeze que proporciona login, registro, recuperación de contraseña, etc.

**Paso 2**: Instala las dependencias de Node.js necesarias para el frontend.

**Paso 3**: Compila los assets CSS y JavaScript.

**Paso 4**: Ejecuta las migraciones para crear las tablas de usuarios.

**Paso 5**: Genera la clave de aplicación necesaria para la seguridad.

### 🧪 **Configurar Testing**

Configurar un entorno de testing completo:

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

**Explicación detallada del proceso:**

**Paso 1**: Crea un test específico para el modelo Servicio.

**Paso 2**: Crea una factory para generar datos de prueba realistas.

**Paso 3**: Ejecuta todos los tests para verificar que todo funciona.

**Paso 4**: Ejecuta solo los tests relacionados con servicios.

## 🎯 **Comandos Personalizados Útiles**

### 📊 **Comando para Crear Servicio**

Un ejemplo de cómo crear un comando personalizado:

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

**Explicación detallada:**

**protected $signature**: Define cómo se llama el comando y qué argumentos acepta.

**protected $description**: Descripción del comando que aparece en `php artisan list`.

**handle()**: Método que se ejecuta cuando se llama el comando. Contiene la lógica principal.

**$this->argument()**: Obtiene los argumentos pasados al comando.

**$this->info()**: Muestra un mensaje de éxito al usuario.

## 🎯 **Buenas Prácticas**

### ✅ **Nomenclatura de Comandos**

**Usar kebab-case para nombres de comandos**: Los nombres de comandos deben usar guiones en lugar de espacios o guiones bajos. Ejemplo: `servicio:crear` en lugar de `servicio_crear`.

**Usar verbos descriptivos**: Los nombres deben describir claramente qué hace el comando. Ejemplo: `crear`, `eliminar`, `actualizar`, `generar`.

**Usar namespace para organizar comandos**: Agrupa comandos relacionados usando dos puntos. Ejemplo: `servicio:crear`, `servicio:eliminar`, `usuario:crear`.

### ✅ **Argumentos y Opciones**

**Usar argumentos para datos obligatorios**: Los argumentos son para información que siempre se necesita. Ejemplo: `{nombre}` para el nombre del servicio.

**Usar opciones para datos opcionales**: Las opciones son para información que puede ser opcional. Ejemplo: `--descripcion` para una descripción opcional.

**Proporcionar valores por defecto cuando sea posible**: Facilita el uso del comando. Ejemplo: `--activo=true` como valor por defecto.

### ✅ **Feedback al Usuario**

**Usar `$this->info()` para mensajes de éxito**: Muestra mensajes en verde para indicar que todo salió bien.

**Usar `$this->error()` para mensajes de error**: Muestra mensajes en rojo para indicar que algo salió mal.

**Usar `$this->warn()` para advertencias**: Muestra mensajes en amarillo para indicar posibles problemas.

**Usar `$this->line()` para información general**: Muestra mensajes en color normal para información general.

### ✅ **Validación**

**Validar argumentos y opciones**: Siempre verifica que los datos de entrada sean válidos antes de procesarlos.

**Proporcionar mensajes de error claros**: Los mensajes de error deben explicar qué salió mal y cómo solucionarlo.

**Usar confirmaciones para acciones destructivas**: Pregunta al usuario antes de eliminar datos importantes.

---

*Esta documentación se actualizará conforme avancemos en el proyecto.* 