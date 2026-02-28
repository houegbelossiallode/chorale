<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use App\Models\EventImage;
use App\Models\Type;
use App\Services\SupabaseService;

class EventController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['images', 'type']);
        return view('admin.events.show', compact('event'));
    }

    public function create()
    {
        $types = Type::all();
        return view('admin.events.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'location' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'principal_image_index' => 'nullable|integer',
        ]);

        $event = Event::create(collect($validated)->except(['images', 'principal_image_index'])->toArray());

        // Gestion Multi-Images Supabase
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            Log::info('Event image upload: ' . count($files) . ' fichier(s) reçu(s)');

            foreach ($files as $index => $image) {
                $path = 'gallery/' . ($event->slug ?? $event->id) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                Log::info("Upload image #{$index}: {$path}");

                $imageUrl = $this->supabase->uploadFile('imgs', $path, $image);

                if ($imageUrl) {
                    EventImage::create([
                        'event_id' => $event->id,
                        'image_path' => $imageUrl,
                        'is_principal' => $request->principal_image_index == $index || (!$request->has('principal_image_index') && $index === 0),
                    ]);
                    Log::info("Image #{$index} enregistrée avec succès: {$imageUrl}");
                } else {
                    Log::error("Image #{$index} upload échoué pour le chemin: {$path}");
                }
            }
        } else {
            Log::warning('Aucun fichier image reçu dans la requête');
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement créé avec succès avec sa galerie d\'images.');
    }

    public function edit(Event $event)
    {
        $types = Type::all();
        return view('admin.events.edit', compact('event', 'types'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'location' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'principal_image_id' => 'nullable|exists:event_images,id',
            'principal_image_index' => 'nullable|integer',
        ]);

        $event->update(collect($validated)->except(['images', 'principal_image_index', 'principal_image_id'])->toArray());

        // Gestion du changement d'image principale parmi les existantes
        if ($request->filled('principal_image_id')) {
            EventImage::where('event_id', $event->id)->update(['is_principal' => false]);
            EventImage::where('id', $request->principal_image_id)->update(['is_principal' => true]);
        }

        // Ajout de nouvelles images à la galerie
        if ($request->hasFile('images')) {
            Log::info("Update Event #{$event->id}: " . count($request->file('images')) . " nouvelles images reçues");

            // Si on désigne une nouvelle image comme principale, on désactive les autres
            if ($request->has('principal_image_index')) {
                EventImage::where('event_id', $event->id)->update(['is_principal' => false]);
            }

            foreach ($request->file('images') as $index => $image) {
                $path = 'gallery/' . ($event->slug ?? $event->id) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                Log::info("Upload nouvelle image #{$index}: {$path}");

                $imageUrl = $this->supabase->uploadFile('imgs', $path, $image);

                if ($imageUrl) {
                    EventImage::create([
                        'event_id' => $event->id,
                        'image_path' => $imageUrl,
                        'is_principal' => $request->principal_image_index == $index,
                    ]);
                    Log::info("Image #{$index} enregistrée: {$imageUrl}");
                } else {
                    Log::error("Échec upload image #{$index} pour Event #{$event->id}");
                }
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Événement supprimé.');
    }

    public function deleteImage(EventImage $image)
    {
        $image->delete();
        return back()->with('success', 'Image supprimée de la galerie.');
    }
}
