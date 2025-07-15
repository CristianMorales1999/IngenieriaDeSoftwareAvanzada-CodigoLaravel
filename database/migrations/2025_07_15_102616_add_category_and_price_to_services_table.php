<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Categoría del servicio
            $table->string('category')->nullable()->after('description');
            
            // Precio del servicio
            $table->decimal('price', 10, 2)->nullable()->after('category');
            
            // Ubicación del servicio (opcional)
            $table->string('location')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'price', 'location']);
        });
    }
};
