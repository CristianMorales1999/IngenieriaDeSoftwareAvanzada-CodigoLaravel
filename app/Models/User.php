<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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
}
