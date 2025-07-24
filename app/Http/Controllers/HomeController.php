<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\AutoReplyContactFormMail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Muestra la página principal (welcome).
     * Incluye servicios destacados y estadísticas.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        // Obtener servicios destacados (los más recientes)
        $featuredServices = Service::with('user')
            ->latest()
            ->take(6)
            ->get();

        // Estadísticas para mostrar en el hero
        $stats = [
            'professionals' => Service::distinct('user_id')->count(),
            'services' => Service::count(),
            'satisfaction' => 98, // Porcentaje de satisfacción (ejemplo)
        ];

        return view('welcome', compact('featuredServices', 'stats'));
    }

    /**
     * Muestra la página "Nosotros".
     *
     * @return \Illuminate\View\View
     */
    public function about(): \Illuminate\View\View
    {
        return view('about');
    }

    /**
     * Muestra la página de contacto.
     *
     * @return \Illuminate\View\View
     */
    public function contact(): \Illuminate\View\View
    {
        return view('contact');
    }

    /**
     * Procesa el formulario de contacto.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contactStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validar el formulario de contacto
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'subject.required' => 'El asunto es obligatorio.',
            'message.required' => 'El mensaje es obligatorio.',
            'message.max' => 'El mensaje no puede tener más de 1000 caracteres.',
        ]);

        // 1. Email AL EQUIPO (desde el cliente)
        Mail::to('info@servipro.com')
            ->send(new ContactFormMail($validated));

        // 2. Email AL CLIENTE (desde ServiPro)
        Mail::to($validated['email'])
            ->send(new AutoReplyContactFormMail($validated));
        
        // Por ahora, solo redirigimos con un mensaje de éxito
        return redirect()->back()->with('success', '¡Mensaje enviado exitosamente! Te responderemos pronto.');
    }
} 