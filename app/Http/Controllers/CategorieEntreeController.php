<?php

namespace App\Http\Controllers;

use App\Models\CategorieEntree;
use Illuminate\Http\Request;

class CategorieEntreeController extends Controller
{
    public function index()
    {
        $categories = CategorieEntree::latest()->paginate(10);

        return view('categories-entrees.index', compact('categories'));
    }

    public function create()
    {
        return view('categories-entrees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        CategorieEntree::create($validated);

        return redirect()
            ->route('categories-entrees.index')
            ->with('success', 'Catégorie d’entrée ajoutée avec succès.');
    }

    public function edit(CategorieEntree $categories_entree)
    {
        return view('categories-entrees.edit', [
            'categorie' => $categories_entree
        ]);
    }

    public function update(Request $request, CategorieEntree $categories_entree)
    {
        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $categories_entree->update($validated);

        return redirect()
            ->route('categories-entrees.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(CategorieEntree $categories_entree)
    {
        $categories_entree->delete();

        return redirect()
            ->route('categories-entrees.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}