<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear usuarios adicionales
        User::factory(10)->create();

        // Crear servicios usando el factory (esto automáticamente asignará usuarios)
        Service::factory(30)->create();

        // Ejecutar el seeder de servicios específicos
        $this->call([
            ServiceSeeder::class,
        ]);
    }
}
