<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     * Estos campos pueden ser llenados usando el método create() o update().
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',        // Título del servicio
        'description',  // Descripción detallada del servicio
        'category',     // Categoría del servicio
        'price',        // Precio del servicio
        'location',     // Ubicación del servicio
        'image_path',   // Ruta de la imagen del servicio
        'user_id',      // ID del usuario que creó el servicio
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * Esto permite trabajar con tipos específicos en lugar de strings.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Los atributos que se añaden automáticamente al modelo.
     * Estos son campos calculados que no existen en la base de datos.
     *
     * @var array<string>
     */
    protected $appends = [
        'excerpt',
        'image_url',
        'has_image',
    ];

    /**
     * Relación con el usuario que creó el servicio.
     * Un servicio pertenece a un usuario.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar servicios por título.
     * Permite buscar servicios que contengan el texto especificado.
     *
     * @param Builder $query
     * @param string $title
     * @return Builder
     */
    public function scopeSearchByTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'like', "%{$title}%");
    }

    /**
     * Scope para filtrar servicios que tienen imagen.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithImage(Builder $query): Builder
    {
        return $query->whereNotNull('image_path');
    }

    /**
     * Scope para filtrar servicios que no tienen imagen.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutImage(Builder $query): Builder
    {
        return $query->whereNull('image_path');
    }

    /**
     * Scope para ordenar servicios por fecha de creación (más recientes primero).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para ordenar servicios por título alfabéticamente.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAlphabetical(Builder $query): Builder
    {
        return $query->orderBy('title', 'asc');
    }

    /**
     * Obtiene un extracto de la descripción (primeros 150 caracteres).
     * Es un accessor que se añade automáticamente al modelo.
     *
     * @return string
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit($this->description, 150, '...');
    }

    /**
     * Obtiene la URL completa de la imagen.
     * Es un accessor que se añade automáticamente al modelo.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // Si la imagen está en storage, devuelve la URL completa
        if (Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }

        // Si es una URL externa, devuélvela tal como está
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        return null;
    }

    /**
     * Verifica si el servicio tiene una imagen.
     * Es un accessor que se añade automáticamente al modelo.
     *
     * @return bool
     */
    public function getHasImageAttribute(): bool
    {
        return !empty($this->image_path);
    }

    /**
     * Verifica si el servicio tiene una imagen válida.
     *
     * @return bool
     */
    public function hasValidImage(): bool
    {
        if (!$this->image_path) {
            return false;
        }

        // Si es una URL externa, asumimos que es válida
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return true;
        }

        // Si está en storage, verificamos que el archivo existe
        return Storage::disk('public')->exists($this->image_path);
    }

    /**
     * Elimina la imagen del servicio si existe.
     *
     * @return bool
     */
    public function deleteImage(): bool
    {
        if ($this->image_path && !filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($this->image_path);
        }

        return $this->update(['image_path' => null]);
    }

    /**
     * Obtiene el nombre del usuario que creó el servicio.
     *
     * @return string
     */
    public function getCreatorNameAttribute(): string
    {
        return $this->user?->name ?? 'Usuario desconocido';
    }

    /**
     * Obtiene la fecha de creación formateada.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Obtiene la fecha de actualización formateada.
     *
     * @return string
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    /**
     * Verifica si el servicio fue creado recientemente (últimos 7 días).
     *
     * @return bool
     */
    public function isRecent(): bool
    {
        return $this->created_at->isAfter(now()->subDays(7));
    }

    /**
     * Obtiene el tiempo transcurrido desde la creación en formato legible.
     *
     * @return string
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
