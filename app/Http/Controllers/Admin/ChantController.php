<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\FichierChant;
use App\Models\Pupitre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\SupabaseService;

class ChantController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    public function index()
    {
        $chants = Chant::with(['fichiers'])->get();
        return view('admin.chants.index', compact('chants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pupitres = Pupitre::all();
        return view('admin.chants.create', compact('pupitres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'composer' => 'nullable|string|max:255',
            'parole' => 'nullable|string',
            'partition' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $chant = Chant::create($request->only(['title', 'composer', 'parole']));

        if ($request->hasFile('partition')) {
            $file = $request->file('partition');
            $path = 'partitions/' . Str::slug($chant->title) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $imageUrl = $this->supabase->uploadFile('imgs', $path, $file);

            if ($imageUrl) {
                $chant->update(['file_path' => $imageUrl]);

                // FichierChant::create([
                //     'chant_id' => $chant->id,
                //     'type' => 'partition',
                //     'file_path' => $imageUrl
                // ]);
            }
        }

        return redirect()->route('admin.chants.index')->with('success', 'Chant ajouté au répertoire.');
    }

    /**
     * Display the specified resource (show page).
     */
    public function show(Chant $chant)
    {
        $chant->load(['fichiers.pupitre']);
        $pupitres = \App\Models\Pupitre::all();
        return view('admin.chants.show', compact('chant', 'pupitres'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chant $chant)
    {
        $pupitres = Pupitre::all();
        $chant->load('fichiers');
        return view('admin.chants.edit', compact('chant', 'pupitres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chant $chant)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'composer' => 'nullable|string|max:255',
            'parole' => 'nullable|string',
        ]);

        $chant->update($request->only(['title', 'composer', 'parole']));

        if ($request->hasFile('partition')) {
            $file = $request->file('partition');
            $path = 'partitions/' . Str::slug($chant->title) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $imageUrl = $this->supabase->uploadFile('imgs', $path, $file);

            if ($imageUrl) {
                $chant->update(['file_path' => $imageUrl]);

                // Pour l'instant on ajoute, on pourra gérer le remplacement plus tard
                // FichierChant::create([
                //     'chant_id' => $chant->id,
                //     'type' => 'partition',
                //     'file_path' => $imageUrl
                // ]);
            }
        }

        return redirect()->route('admin.chants.index')->with('success', 'Chant mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chant $chant)
    {
        foreach ($chant->fichiers as $fichier) {
            Storage::disk('public')->delete($fichier->file_path);
            $fichier->delete();
        }
        $chant->delete();

        return redirect()->route('admin.chants.index')->with('success', 'Chant supprimé.');
    }
}
