<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Muestra la lista de todos los servicios.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Service::with('user');

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Ordenamiento
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $services = $query->paginate(12)->withQueryString();

        return view('services.index', compact('services'));
    }

    /**
     * Muestra un servicio específico.
     *
     * @param Service $service
     * @return \Illuminate\View\View
     */
    public function show(Service $service): \Illuminate\View\View
    {
        $service->load('user');
        
        return view('services.show', compact('service'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('services.create');
    }

    /**
     * Almacena un nuevo servicio.
     *
     * @param StoreServiceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreServiceRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        
        // Manejar la imagen si se proporcionó
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        // Crear el servicio con los datos validados
        $service = Service::create($validated);

        return redirect()->route('services.my')
            ->with('success', '¡Excelente! Tu servicio "' . $service->title . '" ha sido creado exitosamente y ya está disponible para la comunidad. Puedes editarlo o crear más servicios desde aquí.');
    }

    /**
     * Muestra el formulario para editar un servicio.
     *
     * @param Service $service
     * @return \Illuminate\View\View
     */
    public function edit(Service $service): \Illuminate\View\View
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        return view('services.edit', compact('service'));
    }

    /**
     * Actualiza un servicio existente.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateServiceRequest $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        $validated = $request->validated();
        
        // Manejar la imagen si se proporcionó
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            $service->deleteImage();
            
            // Guardar nueva imagen
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio.
     *
     * @param Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para eliminar este servicio.');
        }

        // Eliminar imagen si existe
        $service->deleteImage();
        
        $service->delete();

        return redirect()->route('services.my')
            ->with('success', 'Servicio eliminado exitosamente.');
    }

    /**
     * Muestra los servicios del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function myServices(): \Illuminate\View\View
    {
        $services = auth()->user()->services()
            ->latest()
            ->paginate(10);

        return view('services.my', compact('services'));
    }
} 