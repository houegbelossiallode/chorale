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

        $transactions = $query->paginate(20);
        $categories = CategorieFinanciere::all();

        $totalRecettes = TransactionFinanciere::where('type', 'recette')->sum('montant');
        $totalDepenses = TransactionFinanciere::where('type', 'depense')->sum('montant');
        $solde = \App\Models\Caisse::where('nom', 'Caisse Principale')->first()->solde ?? ($totalRecettes - $totalDepenses);

        return view('admin.finance.transactions.index', compact('transactions', 'categories', 'solde', 'totalRecettes', 'totalDepenses'));
    }

    public function create()
    {
        $categories = CategorieFinanciere::all();
        return view('admin.finance.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:recette,depense',
            'categorie_id' => 'required|exists:categorie_financieres,id',
            'montant' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
        ]);

        $transactionService = app(\App\Services\TransactionService::class);
        $transactionService->recordTransaction($validated);

        return redirect()->route('admin.finance.transactions.index')->with('success', 'Transaction enregistrée et caisse mise à jour.');
    }

    public function destroy(TransactionFinanciere $transaction)
    {
        $transactionService = app(\App\Services\TransactionService::class);
        $transactionService->deleteTransaction($transaction);

        return back()->with('success', 'Transaction supprimée et solde caisse ajusté.');
    }
}
