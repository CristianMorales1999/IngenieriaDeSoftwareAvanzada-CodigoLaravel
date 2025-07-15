<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Aquí registras servicios personalizados que se pueden usar en toda la app.
     */
    public function register(): void
    {
        // Ejemplo: Registrar un servicio personalizado
        // $this->app->singleton('mi-servicio', function ($app) {
        //     return new MiServicio();
        // });
    }

    /**
     * Bootstrap any application services.
     * Aquí configuras comportamientos globales que se aplican a toda la app.
     */
    public function boot(): void
    {
        // Ejemplo: Validación personalizada para números de teléfono
        Validator::extend('telefono', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9+\-\s()]+$/', $value);
        }, 'El formato del número de teléfono no es válido.');

        // Ejemplo: Validación personalizada para contraseñas seguras
        Validator::extend('password_segura', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        }, 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo especial.');

        // Ejemplo: Configurar formato de fechas global
        // Carbon::serializeUsing(function ($carbon) {
        //     return $carbon->format('Y-m-d H:i:s');
        // });
    }
}
