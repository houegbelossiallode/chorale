<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionFinanciere;
use App\Models\CategorieFinanciere;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TransactionFinanciere::with('categorie')->latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('categorie_id') && $request->categorie_id != '') {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->appends($request->all());
        $categories = CategorieFinanciere::all();

        $totalRecettes = TransactionFinanciere::where('type', 'recette')->sum('montant');
        $totalDepenses = TransactionFinanciere::where('type', 'depense')->sum('montant');
        $caisse = \App\Models\Caisse::where('nom', 'Caisse Principale')->first();
        $solde = $caisse ? $caisse->solde : ($totalRecettes - $totalDepenses);

        return view('admin.finance.transactions.index', compact('transactions', 'categories', 'solde', 'totalRecettes', 'totalDepenses'));
    }

    public function create()
    {
        $categories = CategorieFinanciere::all();
        return view('admin.finance.transactions.create', compact('categories'));
    }

    public function edit(TransactionFinanciere $transaction)
    {
        $categories = CategorieFinanciere::all();
        return view('admin.finance.transactions.edit', compact('transaction', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:recette,depense',
            'categorie_id' => 'required|exists:categorie_financieres,id',
            'montant' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('justificatif')) {
            \Illuminate\Support\Facades\Log::info('Début upload justificatif caisse', ['filename' => $request->file('justificatif')->getClientOriginalName()]);
            $supabase = app(\App\Services\SupabaseService::class);
            $file = $request->file('justificatif');
            $path = 'finance/' . time() . '_' . $file->getClientOriginalName();

            $uploadedPath = $supabase->uploadFile('imgs', $path, $file);

            if ($uploadedPath) {
                $validated['justificatif_path'] = $uploadedPath;
                \Illuminate\Support\Facades\Log::info('Upload réussi', ['path' => $uploadedPath]);
            } else {
                \Illuminate\Support\Facades\Log::error('Échec de l\'upload Supabase pour la transaction');
            }
        }

        $transactionService = app(\App\Services\TransactionService::class);
        $transactionService->recordTransaction($validated);

        return redirect()->route('admin.finance.transactions.index')->with('success', 'Transaction enregistrée et caisse mise à jour.');
    }

    public function update(Request $request, TransactionFinanciere $transaction)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:recette,depense',
            'categorie_id' => 'required|exists:categorie_financieres,id',
            'montant' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('justificatif')) {
            $supabase = app(\App\Services\SupabaseService::class);
            // On peut supprimer l'ancien justificatif si nécessaire.
            if ($transaction->justificatif_path) {
                $oldPath = str_replace($supabase->url . '/storage/v1/object/public/imgs/', '', $transaction->justificatif_path);
                $supabase->deleteFile('imgs', $oldPath);
            }

            $file = $request->file('justificatif');
            $path = 'finance/' . time() . '_' . $file->getClientOriginalName();
            $validated['justificatif_path'] = $supabase->uploadFile('imgs', $path, $file);
        }

        $transactionService = app(\App\Services\TransactionService::class);
        $transactionService->updateTransaction($transaction, $validated);

        return redirect()->route('admin.finance.transactions.index')->with('success', 'Transaction mise à jour et caisse ajustée.');
    }

    public function destroy(TransactionFinanciere $transaction)
    {
        $transactionService = app(\App\Services\TransactionService::class);
        $transactionService->deleteTransaction($transaction);

        return back()->with('success', 'Transaction supprimée et solde caisse ajusté.');
    }

    public function downloadJustificatif(TransactionFinanciere $transaction)
    {
        if (!$transaction->justificatif_path) {
            return back()->with('error', 'Aucun justificatif disponible pour cette transaction.');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get($transaction->justificatif_path);

            if (!$response->successful()) {
                throw new \Exception('Impossible de récupérer le fichier sur le stockage distant.');
            }

            // Générer un nom de fichier propre
            $extension = pathinfo(parse_url($transaction->justificatif_path, PHP_URL_PATH), PATHINFO_EXTENSION);
            $filename = 'Justificatif_Transaction_' . $transaction->id . '_' . now()->format('Ymd_His') . '.' . $extension;
            $contentType = $response->header('Content-Type') ?: 'application/octet-stream';

            return response($response->body(), 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors du téléchargement du justificatif (Transaction ID: ' . $transaction->id . '): ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du fichier justificatif.');
        }
    }
}
