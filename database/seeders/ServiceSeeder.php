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
                'title' => 'Servicio de Limpieza Profesional',
                'description' => 'Ofrecemos servicios de limpieza profesional para hogares y oficinas. Nuestro equipo está capacitado para realizar limpieza profunda, mantenimiento regular y servicios especializados. Incluye limpieza de alfombras, ventanas, cocinas y baños.',
                'image_path' => 'services/limpieza-profesional.jpg'
            ],
            [
                'title' => 'Mantenimiento de Jardines',
                'description' => 'Servicio completo de mantenimiento de jardines y áreas verdes. Realizamos poda, riego, fertilización, control de plagas y diseño de jardines. Mantenemos tu jardín hermoso y saludable durante todo el año.',
                'image_path' => 'services/mantenimiento-jardines.jpg'
            ],
            [
                'title' => 'Reparación de Electrodomésticos',
                'description' => 'Servicio técnico especializado en reparación de electrodomésticos. Reparamos refrigeradores, lavadoras, secadoras, hornos microondas y más. Garantía en todas nuestras reparaciones y servicio a domicilio.',
                'image_path' => 'services/reparacion-electrodomesticos.jpg'
            ],
            [
                'title' => 'Servicio de Plomería',
                'description' => 'Plomeros profesionales disponibles 24/7 para emergencias y servicios programados. Reparamos fugas, instalamos tuberías, desatascamos drenajes y realizamos mantenimiento preventivo. Servicio rápido y confiable.',
                'image_path' => 'services/plomeria.jpg'
            ],
            [
                'title' => 'Servicio de Electricidad',
                'description' => 'Electricistas certificados para instalaciones, reparaciones y mantenimiento eléctrico. Instalamos sistemas de iluminación, reparamos cortocircuitos, actualizamos paneles eléctricos y realizamos inspecciones de seguridad.',
                'image_path' => 'services/electricidad.jpg'
            ],
            [
                'title' => 'Servicio de Pintura',
                'description' => 'Servicio de pintura residencial y comercial. Pintamos interiores y exteriores, aplicamos texturas, realizamos trabajos de restauración y asesoramos en la selección de colores. Resultados profesionales garantizados.',
                'image_path' => 'services/pintura.jpg'
            ],
            [
                'title' => 'Servicio de Mudanzas',
                'description' => 'Servicio completo de mudanzas residenciales y comerciales. Empaquetamos, transportamos y desempacamos tus pertenencias con cuidado. Incluye seguro de carga y servicio de montaje de muebles.',
                'image_path' => 'services/mudanzas.jpg'
            ],
            [
                'title' => 'Servicio de Seguridad',
                'description' => 'Instalación y mantenimiento de sistemas de seguridad para hogares y negocios. Instalamos cámaras de vigilancia, alarmas, controles de acceso y sistemas de monitoreo 24/7. Protege tu propiedad con tecnología avanzada.',
                'image_path' => 'services/seguridad.jpg'
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
