<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * Los atributos que son asignables masivamente.
     * Estos campos pueden ser llenados usando el método create() o update().
     */
    protected $fillable = [
        'title',        // Título del servicio
        'description',  // Descripción detallada del servicio
        'image_path',   // Ruta de la imagen del servicio
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
