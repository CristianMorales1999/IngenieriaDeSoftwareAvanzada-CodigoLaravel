<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que son asignables masivamente.
     * Estos campos pueden ser llenados usando el método create() o update().
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'address',
        'image_path',
        'password',
    ];

    /**
     * Los atributos que deben estar ocultos para la serialización.
     * Estos campos no se incluirán en las respuestas JSON.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * Esto permite trabajar con tipos específicos en lugar de strings.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Los atributos que se añaden automáticamente al modelo.
     * Estos son campos calculados que no existen en la base de datos.
     *
     * @var array<string>
     */
    protected $appends = [
        'full_name',
        'contact_info',
        'image_url',
        'has_valid_image',
    ];

    /**
     * Relación con los servicios que ha creado el usuario.
     * Un usuario puede tener muchos servicios.
     *
     * @return HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Verifica si el usuario tiene un número móvil registrado.
     *
     * @return bool
     */
    public function hasMobile(): bool
    {
        return !empty($this->mobile);
    }

    /**
     * Verifica si el usuario tiene una dirección registrada.
     *
     * @return bool
     */
    public function hasAddress(): bool
    {
        return !empty($this->address);
    }

    /**
     * Obtiene el nombre completo del usuario.
     * Si no hay nombre, devuelve el email.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name ?: $this->email;
    }

    /**
     * Obtiene la información de contacto del usuario.
     * Incluye email y móvil si está disponible.
     *
     * @return array<string, string>
     */
    public function getContactInfoAttribute(): array
    {
        $contact = ['email' => $this->email];
        
        if ($this->hasMobile()) {
            $contact['mobile'] = $this->mobile;
        }
        
        return $contact;
    }

    /**
     * Actualiza la contraseña del usuario con hash automático.
     *
     * @param string $password
     * @return bool
     */
    public function updatePassword(string $password): bool
    {
        return $this->update(['password' => Hash::make($password)]);
    }

    /**
     * Verifica si la contraseña proporcionada coincide con la del usuario.
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Verifica si el usuario tiene una imagen de perfil válida.
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
     * Accessor para hasValidImage (para usar en vistas).
     *
     * @return bool
     */
    public function getHasValidImageAttribute(): bool
    {
        return $this->hasValidImage();
    }

    /**
     * Obtiene la URL de la imagen de perfil del usuario.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->hasValidImage()) {
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return asset('storage/' . $this->image_path);
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * Elimina la imagen de perfil del usuario si existe.
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
     * Guarda una nueva imagen de perfil.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return bool
     */
    public function saveImage($image): bool
    {
        // Eliminar imagen anterior si existe
        $this->deleteImage();

        // Guardar nueva imagen
        $path = $image->store('users/avatars', 'public');
        
        return $this->update(['image_path' => $path]);
    }
}
