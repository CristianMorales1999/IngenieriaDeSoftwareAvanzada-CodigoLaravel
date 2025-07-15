<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios existentes o crear algunos si no existen
        $users = User::all();
        
        if ($users->isEmpty()) {
            // Crear algunos usuarios si no existen
            $users = User::factory(5)->create();
        }

        // Crear servicios de ejemplo
        $services = [
            [
                'title' => 'Desarrollo Web Profesional',
                'description' => 'Desarrollo de sitios web modernos y responsivos usando las últimas tecnologías. Incluye diseño UI/UX, desarrollo frontend y backend, integración de bases de datos y optimización SEO.',
                'category' => 'Desarrollo Web',
                'price' => 1500.00,
                'location' => 'Remoto',
                'image_path' => null
            ],
            [
                'title' => 'Diseño de Logos y Branding',
                'description' => 'Creación de logos profesionales, identidad visual completa y materiales de marca. Diseños únicos y memorables que reflejan la personalidad de tu empresa.',
                'category' => 'Diseño Gráfico',
                'price' => 300.00,
                'location' => 'Remoto',
                'image_path' => null
            ],
            [
                'title' => 'Consultoría de Marketing Digital',
                'description' => 'Estrategias de marketing digital personalizadas para aumentar tu presencia online. Incluye SEO, redes sociales, publicidad digital y análisis de métricas.',
                'category' => 'Marketing Digital',
                'price' => 800.00,
                'location' => 'Madrid, España',
                'image_path' => null
            ],
            [
                'title' => 'Clases de Programación',
                'description' => 'Clases particulares de programación para principiantes y avanzados. Aprende JavaScript, Python, PHP, Laravel y más. Metodología práctica y personalizada.',
                'category' => 'Educación',
                'price' => 50.00,
                'location' => 'Barcelona, España',
                'image_path' => null
            ],
            [
                'title' => 'Consultoría Empresarial',
                'description' => 'Asesoramiento estratégico para empresas en crecimiento. Análisis de mercado, planificación financiera, optimización de procesos y desarrollo de estrategias.',
                'category' => 'Consultoría',
                'price' => 1200.00,
                'location' => 'Valencia, España',
                'image_path' => null
            ],
            [
                'title' => 'Diseño de Interfaces Web',
                'description' => 'Diseño de interfaces de usuario modernas y funcionales. Creación de wireframes, prototipos interactivos y diseño visual para aplicaciones web y móviles.',
                'category' => 'Diseño Gráfico',
                'price' => 600.00,
                'location' => 'Remoto',
                'image_path' => null
            ],
            [
                'title' => 'Mantenimiento de Sitios Web',
                'description' => 'Servicio de mantenimiento continuo para sitios web. Actualizaciones de seguridad, optimización de rendimiento, respaldos y soporte técnico.',
                'category' => 'Desarrollo Web',
                'price' => 200.00,
                'location' => 'Remoto',
                'image_path' => null
            ],
            [
                'title' => 'Gestión de Redes Sociales',
                'description' => 'Gestión completa de redes sociales para empresas. Creación de contenido, programación de publicaciones, interacción con seguidores y análisis de resultados.',
                'category' => 'Marketing Digital',
                'price' => 400.00,
                'location' => 'Remoto',
                'image_path' => null
            ]
        ];

        // Insertar cada servicio en la base de datos asignando un usuario aleatorio
        foreach ($services as $service) {
            Service::create([
                ...$service,
                'user_id' => $users->random()->id, // Asignar usuario aleatorio
            ]);
        }

        // Crear servicios adicionales usando el factory
        Service::factory(20)->create(); // Crear 20 servicios adicionales con usuarios aleatorios
    }
}
