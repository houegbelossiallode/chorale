<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FichierChant;
use App\Models\Chant;
use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Str;

class FichierChantController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function store(Request $request)
    {
        $request->validate([
            'chant_id' => 'required|exists:chants,id',
            'type' => 'required|in:partition,audio,video,youtube',
            'pupitre_id' => 'nullable|exists:pupitres,id',
            'file' => 'sometimes|nullable|file',
            'url' => 'nullable|url',
        ]);

        \Log::info('FichierChant store called', $request->except('file'));
        \Log::info('Has file: ' . ($request->hasFile('file') ? 'yes' : 'no'));

        // Validation conditionnelle
        if (in_array($request->type, ['partition', 'audio', 'video'])) {
            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                \Log::warning('FichierChant: fichier manquant ou invalide');
                return back()->withErrors(['file' => 'Un fichier valide est requis pour ce type de ressource.'])->withInput();
            }
        }
        if ($request->type === 'youtube' && !$request->url) {
            return back()->withErrors(['url' => 'Une URL YouTube est requise.'])->withInput();
        }

        $chant = Chant::findOrFail($request->chant_id);
        $filePath = '';

        if ($request->type === 'youtube') {
            // Cas YouTube : on stocke directement l'URL, pas d'upload
            $filePath = $request->url;
            \Log::info('FichierChant: YouTube URL = ' . $filePath);
        } else {
            $file = $request->file('file');
            $folder = $request->type . 's';
            $path = "chants/{$chant->id}/{$folder}/" . Str::slug($chant->title) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            \Log::info('FichierChant: Uploading to Supabase path = ' . $path);
            $filePath = $this->supabase->uploadFile('imgs', $path, $file);
            \Log::info('FichierChant: Upload result = ' . ($filePath ?? 'NULL'));
        }

        if ($filePath) {
            $record = FichierChant::create([
                'chant_id' => $chant->id,
                'pupitre_id' => $request->pupitre_id ?: null,
                'type' => $request->type,
                'file_path' => $filePath,
            ]);
            \Log::info('FichierChant created: id=' . $record->id);

            if ($request->type === 'partition' && !$chant->file_path) {
                $chant->update(['file_path' => $filePath]);
            }

            return back()->with('success', 'Ressource ajoutée avec succès.');
        }

        \Log::error('FichierChant: filePath vide, rien enregistré');
        return back()->with('error', 'Échec de l\'upload. Vérifiez que le fichier est correct et que Supabase est accessible.');
    }

    public function destroy(FichierChant $fichierChant)
    {
        if ($fichierChant->type !== 'youtube') {
            // Extraire le chemin relatif à partir de l'URL publique
            // L'URL ressemble à : https://xxx.supabase.co/storage/v1/object/public/imgs/path/to/file
            $urlPrefix = $this->supabase->url . '/storage/v1/object/public/imgs/';
            $filePath = str_replace($urlPrefix, '', $fichierChant->file_path);

            $this->supabase->deleteFile('imgs', $filePath);
        }

        $fichierChant->delete();
        return back()->with('success', 'Ressource supprimée.');
    }
}
