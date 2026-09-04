<?php

namespace App\Http\Controllers;

use App\Models\CategorieSortie;
use Illuminate\Http\Request;

class CategorieSortieController extends Controller
{
    public function index()
    {
        $categories = CategorieSortie::orderBy('libelle')->paginate(10);

        return view('categories-sorties.index', compact('categories'));
    }

    public function create()
    {
        return view('categories-sorties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|max:255',
            'description' => 'nullable'
        ]);

        CategorieSortie::create($request->all());

        return redirect()->route('categories-sorties.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(CategorieSortie $categories_sortie)
    {
        return view('categories-sorties.edit', [
            'categorie' => $categories_sortie
        ]);
    }

    public function update(Request $request, CategorieSortie $categories_sortie)
    {
        $request->validate([
            'libelle' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $categories_sortie->update($request->all());

        return redirect()->route('categories-sorties.index')
            ->with('success', 'Catégorie modifiée.');
    }

    public function destroy(CategorieSortie $categories_sortie)
    {
        $categories_sortie->delete();

        return redirect()->route('categories-sorties.index')
            ->with('success', 'Catégorie supprimée.');
    }
}