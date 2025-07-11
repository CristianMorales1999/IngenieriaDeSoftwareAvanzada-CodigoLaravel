# Proyecto Laravel - Ingeniería de Software Avanzada

## 📋 Descripción del Proyecto

Este es un proyecto Laravel desarrollado para el curso de Ingeniería de Software Avanzada. El proyecto está centrado en la implementación de un **CRUD de servicios** con el objetivo principal de aprender Laravel 12 en el camino del desarrollo.

**Objetivos del proyecto:**
- Aprender Laravel 12 desde cero
- Implementar operaciones CRUD completas
- Utilizar JavaScript puro para interactividad
- Aplicar Tailwind CSS para el diseño
- Gestionar servicios de manera eficiente
- Dominar conceptos avanzados de Laravel 12
- **Aplicar principios de usabilidad** en toda la interfaz

## 🚀 Tecnologías Utilizadas

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates
- **Base de Datos**: MySQL
- **Estilos**: Tailwind CSS
- **JavaScript**: JavaScript puro (Vanilla JavaScript)
- **Autenticación**: Por definir

## 📁 Estructura del Proyecto

```
IngenieriaDeSoftwareAvanzada-CodigoLaravel/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
└── storage/
```

## 🛠️ Requisitos del Sistema

- PHP >= 8.2
- Composer
- Node.js y NPM
- Base de datos (MySQL/PostgreSQL)

## ⚙️ Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/CristianMorales1999/IngenieriaDeSoftwareAvanzada-CodigoLaravel
cd IngenieriaDeSoftwareAvanzada-CodigoLaravel
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos
Editar el archivo `.env` con las credenciales de tu base de datos MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
```

### 6. Iniciar el servidor de desarrollo
```bash
php artisan serve
```

### 7. Instalar y configurar Tailwind CSS
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 8. Configurar Tailwind CSS
Editar `tailwind.config.js`:
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

### 9. Configurar CSS principal
En `resources/css/app.css`:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 10. Compilar assets (en otra terminal)
```bash
npm run dev
```

## 🎯 Funcionalidades Implementadas

### ✅ Completado
- [x] Proyecto Laravel 12 inicializado
- [x] Estructura básica del proyecto
- [x] Configuración inicial de base de datos MySQL
- [x] README inicial creado

### 🔄 En Desarrollo
- [ ] Configuración inicial del proyecto
- [ ] Configuración de Tailwind CSS
- [ ] Componentes Blade (Header/Footer)
- [ ] Página principal para usuarios guest
- [ ] Sistema de autenticación básico

### 📋 Pendiente
- [ ] CRUD completo de servicios
- [ ] Gestión de imágenes
- [ ] Formularios avanzados
- [ ] Middleware personalizado
- [ ] Testing completo
- [ ] Optimización y documentación

## 🧪 Testing

Para ejecutar las pruebas:
```bash
php artisan test
```

## 📝 Comandos Útiles

### Artisan Commands
```bash
# Crear un nuevo controlador
php artisan make:controller NombreController

# Crear un nuevo modelo
php artisan make:model NombreModelo

# Crear una nueva migración
php artisan make:migration create_nombre_tabla_table

# Crear un nuevo seeder
php artisan make:seeder NombreSeeder

# Ejecutar seeders
php artisan db:seed
```

### Desarrollo
```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan optimize
```

## 🔄 Control de Versiones

### Convenciones de Commits
- `feat:` Nueva funcionalidad
- `fix:` Corrección de errores
- `docs:` Documentación
- `style:` Cambios de formato
- `refactor:` Refactorización de código
- `test:` Agregar o modificar tests
- `chore:` Tareas de mantenimiento

## 📚 Documentación

### 📖 **Documentación Oficial**
- [Documentación de Laravel](https://laravel.com/docs)
- [Laravel Eloquent](https://laravel.com/docs/eloquent)
- [Laravel Blade](https://laravel.com/docs/blade)
- [Laravel Authentication](https://laravel.com/docs/authentication)
- [Laravel File Storage](https://laravel.com/docs/filesystem)
- [Laravel Validation](https://laravel.com/docs/validation)

### 📋 **Documentación del Proyecto**

#### ✅ **Fase 1: Fundamentos Básicos**
- [📁 Estructura de Carpetas y Funcionamiento Básico](docs/01-estructura-proyecto-laravel.md)
- [🛣️ Rutas y Parámetros](docs/02-rutas-parametros-laravel.md)
- [🎨 Blade Templates y Plantillas](docs/03-blade-plantillas-laravel.md)
- [🔧 Comandos Artisan](docs/04-comandos-artisan-laravel.md)

#### ✅ **Fase 2: Base de Datos y Modelos**
- [🗄️ Eloquent ORM y Modelos](docs/05-eloquent-orm-modelos-laravel.md)
- [🗄️ Migraciones y Seeders](docs/06-migraciones-seeders-laravel.md)
- [🔍 Consultas Avanzadas](docs/07-consultas-avanzadas-laravel.md)

#### ✅ **Fase 3: Controladores y Lógica de Negocio**
- [📋 Controladores](docs/08-controladores-laravel.md)
- [📝 Form Requests y Validación](docs/09-form-requests-validacion-laravel.md)
- [🔧 Middleware](docs/10-middleware-laravel.md)

#### ✅ **Fase 4: Frontend y Componentes**
- [🧩 Componentes Blade](docs/11-componentes-blade-laravel.md)
- [🎨 Tailwind CSS](docs/12-tailwind-css-laravel.md)

#### ✅ **Fase 5: Autenticación y Seguridad**
- [🔐 Autenticación](docs/13-autenticacion-laravel.md)
- [🛡️ Seguridad](docs/14-seguridad-laravel.md)

## 👥 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📚 Temas a Desarrollar

### 🔧 **Configuración y Entorno**
- [ ] **Composer**: Gestión de dependencias y paquetes
- [ ] **Variables de entorno**: Configuración de .env
- [ ] **Configuración inicial**: Setup completo del proyecto

### 🗄️ **Base de Datos y ORM**
- [ ] **Eloquent ORM**: Relaciones y consultas
- [ ] **Migraciones**: Estructura de tablas
- [ ] **Seeders**: Datos de prueba
- [ ] **Modelos**: Definición de entidades

### 🎮 **Controladores y Lógica de Negocio**
- [ ] **Controladores**: Lógica de aplicación
- [ ] **Route Resource**: Rutas automáticas CRUD
- [ ] **Validaciones**: Reglas de validación
- [ ] **Middleware**: Filtros de peticiones

### 🎨 **Vistas y Frontend**
- [ ] **Componentes Blade**: Header, Footer, Navegación
- [ ] **Página principal (Guest)**: Landing page para visitantes
- [ ] **Blade Templates**: Sistema de plantillas
- [ ] **Tailwind CSS**: Estilos y diseño
- [ ] **JavaScript puro**: Interactividad
- [ ] **Formularios**: Crear, editar, reutilizar

### 🔐 **Autenticación y Seguridad**
- [ ] **Página principal (Guest)**: Landing page sin autenticación
- [ ] **Login/Register**: Sistema de usuarios
- [ ] **Middleware de autenticación**: Protección de rutas
- [ ] **Sesiones**: Gestión de sesiones
- [ ] **Autorización**: Roles y permisos
- [ ] **Avatar y perfil**: Gestión de información de usuario

### 📁 **Gestión de Archivos**
- [ ] **Subida de imágenes**: Upload de archivos
- [ ] **Validación de imágenes**: Tipos y tamaños
- [ ] **Mostrar imágenes**: Visualización
- [ ] **Actualizar/eliminar imágenes**: CRUD de archivos

### 🏠 **Página Principal (Guest)**
- [ ] **Landing page**: Diseño atractivo y moderno
- [ ] **Componentes Blade**: Header y Footer reutilizables
- [ ] **Header dinámico**: Cambia según estado de autenticación
- [ ] **Navegación principal**: Menú de navegación
- [ ] **Sección de servicios**: Muestra de servicios destacados
- [ ] **Formulario de contacto**: Para usuarios no registrados
- [ ] **Call-to-action**: Botones de registro/login
- [ ] **Footer informativo**: Información de contacto y enlaces

### 📊 **CRUD de Servicios**
- [ ] **Dashboard**: Panel de control para usuarios autenticados
- [ ] **Crear servicios**: Formularios de creación
- [ ] **Leer servicios**: Listado y detalles
- [ ] **Actualizar servicios**: Formularios de edición
- [ ] **Eliminar servicios**: Confirmación y eliminación
- [ ] **Paginación**: Navegación de resultados
- [ ] **Búsqueda y filtros**: Funcionalidades avanzadas

### 🧩 **Componentes y Navegación**
- [ ] **Header dinámico**: Cambia según autenticación
- [ ] **Footer reutilizable**: Información consistente
- [ ] **Avatar con dropdown**: Navegación de usuario
- [ ] **Menú desplegable**: Opciones de usuario
- [ ] **Navegación responsive**: Mobile-friendly
- [ ] **Breadcrumbs**: Navegación contextual

### 📞 **Formularios de Contacto**
- [ ] **Formulario de contacto**: Captura de datos
- [ ] **Validación de formularios**: Reglas específicas
- [ ] **Envío de emails**: Notificaciones
- [ ] **Confirmación**: Mensajes de éxito/error

### 🧪 **Testing y Calidad**
- [ ] **Tests unitarios**: Pruebas de componentes
- [ ] **Tests de integración**: Pruebas de funcionalidades
- [ ] **Tests de autenticación**: Pruebas de seguridad
- [ ] **Optimización**: Rendimiento y consultas

### 🔄 **Funcionalidades Avanzadas**
- [ ] **Reutilización de formularios**: Componentes Blade
- [ ] **Validaciones personalizadas**: Reglas específicas
- [ ] **Mensajes flash**: Notificaciones temporales
- [ ] **Optimización de consultas**: Eager loading
- [ ] **Cache**: Almacenamiento temporal
- [ ] **Logs**: Registro de actividades

## 🎯 Principios de Usabilidad

### 📱 **Diseño Responsivo**
- [ ] **Mobile-first**: Diseño optimizado para dispositivos móviles
- [ ] **Breakpoints**: Adaptación a diferentes tamaños de pantalla
- [ ] **Touch-friendly**: Elementos táctiles apropiados

### 🎨 **Interfaz Intuitiva**
- [ ] **Navegación clara**: Menús y breadcrumbs intuitivos
- [ ] **Jerarquía visual**: Uso de colores, tipografía y espaciado
- [ ] **Consistencia**: Patrones de diseño uniformes
- [ ] **Feedback visual**: Confirmaciones y estados de carga

### ⚡ **Rendimiento y Velocidad**
- [ ] **Carga rápida**: Optimización de assets y consultas
- [ ] **Lazy loading**: Carga progresiva de contenido
- [ ] **Cache inteligente**: Almacenamiento temporal eficiente
- [ ] **Compresión**: Optimización de imágenes y archivos

### 🔍 **Accesibilidad**
- [ ] **Contraste adecuado**: Colores accesibles para todos
- [ ] **Navegación por teclado**: Funcionalidad sin mouse
- [ ] **Textos alternativos**: Descripciones para imágenes
- [ ] **Estructura semántica**: HTML semántico correcto

### 📝 **Formularios Usables**
- [ ] **Validación en tiempo real**: Feedback inmediato
- [ ] **Mensajes de error claros**: Explicaciones específicas
- [ ] **Campos obligatorios marcados**: Indicadores visuales
- [ ] **Autocompletado**: Sugerencias inteligentes
- [ ] **Guardado automático**: Prevención de pérdida de datos

### 🎯 **Experiencia de Usuario**
- [ ] **Onboarding**: Guía para nuevos usuarios
- [ ] **Landing page atractiva**: Primera impresión positiva
- [ ] **Dashboard intuitivo**: Panel de control organizado
- [ ] **Navegación por avatar**: Acceso rápido a opciones
- [ ] **Búsqueda avanzada**: Filtros y ordenamiento
- [ ] **Paginación intuitiva**: Navegación de resultados
- [ ] **Confirmaciones**: Diálogos de confirmación
- [ ] **Estados de carga**: Indicadores de progreso

### 📊 **Analytics y Mejora Continua**
- [ ] **Tracking de eventos**: Análisis de comportamiento
- [ ] **A/B Testing**: Pruebas de diferentes diseños
- [ ] **Feedback de usuarios**: Sistema de comentarios
- [ ] **Métricas de rendimiento**: Monitoreo de velocidad

## 📞 Contacto

- **Desarrollador**: Cristian Morales
- **Email**: cm9225064@gmail.com
- **Teléfono**: 949177350
- **Proyecto**: CRUD de Servicios - Laravel 12
- **Repositorio**: https://github.com/CristianMorales1999/IngenieriaDeSoftwareAvanzada-CodigoLaravel

---

## 📅 Historial de Cambios

### [Fecha Actual] - Versión Inicial
- ✅ Proyecto Laravel 12 inicializado
- ✅ Estructura básica configurada
- ✅ README inicial creado
- ✅ Configuración para MySQL, Tailwind CSS y JavaScript puro
- ✅ Definición del proyecto CRUD de servicios
- ✅ Datos de contacto del desarrollador
- ✅ Plan de desarrollo completo con todos los temas organizados
- ✅ Principios de usabilidad definidos
- ✅ Link del repositorio agregado
- ✅ Página principal para guest planificada
- ✅ Sistema de componentes y navegación definido
- ✅ Documentación completa creada (14 archivos de docs)
- ✅ Fase 1 completada: Fundamentos básicos (estructura, rutas, Blade, comandos)
- ✅ Fase 2 completada: Base de datos y modelos (Eloquent, migraciones, seeders, consultas)
- ✅ Fase 3 completada: Controladores y lógica de negocio (controladores, validación, middleware)
- ✅ Fase 4 completada: Frontend y componentes (componentes Blade, Tailwind CSS)
- ✅ Fase 5 completada: Autenticación y seguridad (login/register, roles/permisos, CSRF, validación, rate limiting)

---

*Este README se actualizará constantemente conforme el proyecto evolucione.*
