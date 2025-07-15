<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@servipro.com',
            'password' => Hash::make('password123'),
            'mobile' => '+34 600 123 456',
            'address' => 'Calle Principal 123, Madrid',
        ]);

        // Crear usuario de prueba
        User::create([
            'name' => 'Usuario Prueba',
            'email' => 'usuario@servipro.com',
            'password' => Hash::make('password123'),
            'mobile' => '+34 600 789 012',
            'address' => 'Avenida Central 456, Barcelona',
        ]);

        // Crear algunos usuarios adicionales
        User::create([
            'name' => 'María García',
            'email' => 'maria@servipro.com',
            'password' => Hash::make('password123'),
            'mobile' => '+34 600 345 678',
            'address' => 'Plaza Mayor 789, Valencia',
        ]);

        User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@servipro.com',
            'password' => Hash::make('password123'),
            'mobile' => '+34 600 901 234',
            'address' => 'Calle Nueva 321, Sevilla',
        ]);

        $this->command->info('Usuarios de prueba creados exitosamente.');
        $this->command->info('Credenciales de acceso:');
        $this->command->info('- admin@servipro.com / password123');
        $this->command->info('- usuario@servipro.com / password123');
        $this->command->info('- maria@servipro.com / password123');
        $this->command->info('- carlos@servipro.com / password123');
    }
} 