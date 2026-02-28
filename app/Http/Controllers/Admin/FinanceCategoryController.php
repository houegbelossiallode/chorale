<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieFinanciere;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategorieFinanciere::all();
        return view('admin.finance.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'type' => 'required|in:recette,depense',
        ]);

        CategorieFinanciere::create($validated);

        return back()->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, CategorieFinanciere $category)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'type' => 'required|in:recette,depense',
        ]);

        $category->update($validated);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(CategorieFinanciere $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
