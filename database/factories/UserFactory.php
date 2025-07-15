<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * El estado actual del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'mobile' => fake()->optional(0.8)->phoneNumber(),
            'address' => fake()->optional(0.7)->address(),
            'image_path' => fake()->optional(0.6)->imageUrl(200, 200, 'people'),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indica que el email del modelo no ha sido verificado.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Estado para usuarios sin imagen de perfil.
     */
    public function withoutImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => null,
        ]);
    }

    /**
     * Estado para usuarios con imagen de perfil.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => fake()->imageUrl(200, 200, 'people'),
        ]);
    }

    /**
     * Estado para usuarios con información completa.
     */
    public function complete(): static
    {
        return $this->state(fn (array $attributes) => [
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'image_path' => fake()->imageUrl(200, 200, 'people'),
        ]);
    }
}
