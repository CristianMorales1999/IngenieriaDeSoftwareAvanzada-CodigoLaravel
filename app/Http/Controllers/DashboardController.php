<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware auth para que solo usuarios autenticados puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el dashboard del usuario con estadísticas y servicios recientes.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $user = auth()->user();
        
        // Obtener estadísticas del usuario
        $stats = [
            'total_services' => $user->services()->count(),
            'recent_services' => $user->services()->where('created_at', '>=', now()->subDays(7))->count(),
            'services_with_images' => $user->services()->whereNotNull('image_path')->count(),
        ];

        // Obtener servicios recientes del usuario
        $recentServices = $user->services()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentServices'));
    }
} 