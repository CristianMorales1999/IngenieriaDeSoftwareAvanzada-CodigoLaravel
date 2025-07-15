<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Categorías disponibles
        $categories = [
            'Desarrollo Web',
            'Diseño Gráfico',
            'Marketing Digital',
            'Consultoría',
            'Educación',
            'Otros'
        ];

        // Títulos de servicios por categoría
        $serviceTitles = [
            'Desarrollo Web' => [
                'Desarrollo de Sitios Web',
                'Aplicaciones Web',
                'E-commerce',
                'Mantenimiento Web',
                'Optimización SEO',
                'Integración de APIs'
            ],
            'Diseño Gráfico' => [
                'Diseño de Logos',
                'Branding Corporativo',
                'Diseño de Interfaces',
                'Ilustraciones',
                'Material Publicitario',
                'Diseño de Empaques'
            ],
            'Marketing Digital' => [
                'Gestión de Redes Sociales',
                'Publicidad Digital',
                'Email Marketing',
                'Content Marketing',
                'Analytics y Reportes',
                'SEO y SEM'
            ],
            'Consultoría' => [
                'Consultoría Empresarial',
                'Consultoría Tecnológica',
                'Consultoría Financiera',
                'Consultoría de Marketing',
                'Consultoría Legal',
                'Consultoría de Recursos Humanos'
            ],
            'Educación' => [
                'Clases de Programación',
                'Tutorías Académicas',
                'Cursos Online',
                'Capacitación Empresarial',
                'Idiomas',
                'Música y Arte'
            ],
            'Otros' => [
                'Servicio de Limpieza',
                'Servicio de Plomería',
                'Servicio de Electricidad',
                'Servicio de Pintura',
                'Servicio de Mudanzas',
                'Servicio de Seguridad'
            ]
        ];

        // Seleccionar categoría aleatoria
        $category = fake()->randomElement($categories);
        
        // Seleccionar título basado en la categoría
        $title = fake()->randomElement($serviceTitles[$category]);

        // Genera imagen aleatoriamente: 70% de probabilidad de tener imagen, 30% de ser null
        $imagePath = fake()->boolean(70) 
            ? 'services/' . fake()->slug() . '.' . fake()->randomElement(['jpg', 'png', 'webp'])
            : null;

        return [
            'title' => $title,
            'description' => fake()->paragraphs(3, true), // Genera 3 párrafos de descripción
            'category' => $category,
            'price' => fake()->randomFloat(2, 50, 2000), // Precio entre $50 y $2000
            'location' => fake()->randomElement(['Remoto', 'Madrid, España', 'Barcelona, España', 'Valencia, España', 'Sevilla, España']),
            'image_path' => $imagePath,
            'user_id' => \App\Models\User::factory(), // Asigna un usuario aleatorio
        ];
    }

    /**
     * Indica que el servicio no tiene imagen.
     */
    public function withoutImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => null,
        ]);
    }

    /**
     * Indica que el servicio tiene una imagen.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => 'services/' . fake()->slug() . '.' . fake()->randomElement(['jpg', 'png', 'webp']),
        ]);
    }

    /**
     * Indica que el servicio tiene una descripción corta.
     */
    public function shortDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => fake()->sentence(10), // Descripción de una sola oración
        ]);
    }

    /**
     * Indica que el servicio tiene una descripción larga.
     */
    public function longDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => fake()->paragraphs(5, true), // Descripción de 5 párrafos
        ]);
    }
}
