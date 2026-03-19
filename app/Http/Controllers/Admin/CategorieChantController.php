<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieChant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieChantController extends Controller
{
    public function index()
    {
        $categories = CategorieChant::withCount('chants')->orderBy('name')->get();
        return view('admin.categories_chants.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categorie_chants,name',
        ]);

        CategorieChant::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, CategorieChant $categoriesChant)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categorie_chants,name,' . $categoriesChant->id,
        ]);

        $categoriesChant->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(CategorieChant $categoriesChant)
    {
        if ($categoriesChant->chants()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des chants.');
        }

        $categoriesChant->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
