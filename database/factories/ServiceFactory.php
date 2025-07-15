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
        // Lista de servicios comunes para generar títulos realistas
        // Usamos shuffle() para evitar repeticiones y tomamos elementos únicos
        static $availableServices = null;
        
        if ($availableServices === null) {
            $services = [
                'Limpieza Profesional',
                'Mantenimiento de Jardines',
                'Reparación de Electrodomésticos',
                'Servicio de Plomería',
                'Servicio de Electricidad',
                'Servicio de Pintura',
                'Servicio de Mudanzas',
                'Servicio de Seguridad',
                'Servicio de Carpintería',
                'Servicio de Albañilería',
                'Servicio de Cerrajería',
                'Servicio de Climatización',
                'Servicio de Informática',
                'Servicio de Fotografía',
                'Servicio de Catering',
                'Servicio de Transporte',
                'Servicio de Masajes',
                'Servicio de Peluquería',
                'Servicio de Tintorería',
                'Servicio de Veterinaria',
                'Servicio de Diseño Gráfico',
                'Servicio de Traducción',
                'Servicio de Contabilidad',
                'Servicio de Marketing Digital',
                'Servicio de Desarrollo Web',
                'Servicio de Consultoría Legal',
                'Servicio de Coaching Personal',
                'Servicio de Yoga y Meditación',
                'Servicio de Entrenamiento Personal',
                'Servicio de Nutrición',
                'Servicio de Psicología',
                'Servicio de Odontología',
                'Servicio de Fisioterapia',
                'Servicio de Podología',
                'Servicio de Optometría',
                'Servicio de Dermatología'
            ];
            
            shuffle($services); // Mezcla el array para evitar repeticiones
            $availableServices = $services;
        }
        
        // Toma el siguiente servicio disponible o genera uno único
        $title = array_shift($availableServices);
        if ($title === null) {
            // Si se agotaron los servicios predefinidos, genera uno único
            $title = 'Servicio de ' . fake()->unique()->words(2, true);
        }

        // Genera imagen aleatoriamente: 70% de probabilidad de tener imagen, 30% de ser null
        $imagePath = fake()->boolean(70) 
            ? 'services/' . fake()->slug() . '.' . fake()->randomElement(['jpg', 'png', 'webp'])
            : null;

        return [
            'title' => $title,
            'description' => fake()->paragraphs(3, true), // Genera 3 párrafos de descripción
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
