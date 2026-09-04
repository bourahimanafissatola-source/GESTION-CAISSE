<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\CategorieEntree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntreeController extends Controller
{
  public function index()
{
    $entrees = Entree::with(['categorie', 'user'])
        ->orderBy('date_operation', 'desc')
        ->paginate(10);

    return view('entrees.index', compact('entrees'));
}

    public function create()
    {
        $categories = CategorieEntree::orderBy('libelle')->get();

        return view('entrees.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categorie_entree_id' => 'required|exists:categories_entrees,id',
            'libelle' => 'required|max:255',
            'montant' => 'required|numeric|min:1',
            'date_operation' => 'required|date',
            'description' => 'nullable'
        ]);

        Entree::create([
            'categorie_entree_id' => $request->categorie_entree_id,
            'user_id' => Auth::id(),
            'libelle' => $request->libelle,
            'montant' => $request->montant,
            'date_operation' => $request->date_operation,
            'description' => $request->description,
        ]);
        \App\Models\ActivityLog::log('entree_creee', auth()->user()->name . ' a enregistré une entrée de ' . number_format($data['montant'] ?? $request->montant, 0, ',', ' ') . ' FCFA (' . ($request->libelle) . ')');

        return redirect()->route('entrees.index')
            ->with('success','Entrée enregistrée avec succès.');
    }

    public function edit(Entree $entree)
    {
        $categories = CategorieEntree::orderBy('libelle')->get();

        return view('entrees.edit', compact('entree','categories'));
    }

    public function update(Request $request, Entree $entree)
    {
        $request->validate([
            'categorie_entree_id' => 'required|exists:categories_entrees,id',
            'libelle' => 'required|max:255',
            'montant' => 'required|numeric|min:1',
            'date_operation' => 'required|date',
            'description' => 'nullable'
        ]);

        $entree->update($request->all());
        \App\Models\ActivityLog::log('entree_modifiee', auth()->user()->name . ' a modifié l\'entrée "' . $entree->libelle . '"');

        return redirect()->route('entrees.index')
            ->with('success','Entrée modifiée.');
    }

    public function destroy(Entree $entree)
    {
        $entree->delete();
        \App\Models\ActivityLog::log('entree_supprimee', auth()->user()->name . ' a supprimé l\'entrée "' . $entree->libelle . '"');

        return back()->with('success','Entrée supprimée.');
    }
}