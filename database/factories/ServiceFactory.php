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
            'Servicio de Veterinaria'
        ];

        return [
            'title' => 'Servicio de ' . fake()->randomElement($services),
            'description' => fake()->paragraphs(3, true), // Genera 3 párrafos de descripción
            'image_path' => 'services/' . fake()->slug() . '.jpg', // Genera ruta de imagen única
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
     * Indica que el servicio tiene una descripción corta.
     */
    public function shortDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => fake()->sentence(10), // Descripción de una sola oración
        ]);
    }
}
