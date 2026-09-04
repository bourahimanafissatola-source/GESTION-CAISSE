<?php

namespace App\Http\Controllers;

use App\Models\Sortie;
use App\Models\CategorieSortie;
use Illuminate\Http\Request;

class SortieController extends Controller
{
    // Liste des sorties
    public function index()
{
    $sorties = Sortie::with(['categorie', 'user'])
        ->orderBy('date_sortie', 'desc')
        ->paginate(10);

    return view('sorties.index', compact('sorties'));
}

    // Formulaire d'ajout
    public function create()
    {
        $categories = CategorieSortie::orderBy('libelle')->get();

        return view('sorties.create', compact('categories'));
    }

    // Enregistrer une sortie
public function store(Request $request)
{
    $data = $request->validate([
        'categorie_sortie_id' => 'required|exists:categories_sorties,id',
        'libelle' => 'required|string|max:255',
        'montant' => 'required|numeric|min:0',
        'date_sortie' => 'required|date',
        'beneficiaire' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'justificatif' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    // Calcul du solde actuel
    $totalEntrees = \App\Models\Entree::sum('montant');
    $totalSorties = Sortie::sum('montant');
    $solde = $totalEntrees - $totalSorties;

    if ($data['montant'] > $solde) {
        return back()
            ->withInput()
            ->withErrors(['montant' => 'Solde insuffisant. Solde disponible : ' . number_format($solde, 0, ',', ' ') . ' FCFA.']);
    }

    if ($request->hasFile('justificatif')) {
        $data['justificatif_path'] = $request->file('justificatif')->store('justificatifs', 'public');
    }

    $data['user_id'] = auth()->id();
    $data['statut'] = 'en_attente';

    Sortie::create($data);
    // dans store(), avant le redirect
\App\Models\ActivityLog::log('sortie_creee', auth()->user()->name . ' a enregistré une sortie de ' . number_format($data['montant'], 0, ',', ' ') . ' FCFA (' . $data['libelle'] . ')');

    return redirect()->route('sorties.index')->with('success', 'Sortie enregistrée.');
}

    // Formulaire de modification
      public function edit(Sortie $sorty)
{
    $categories = CategorieSortie::orderBy('libelle')->get();

    return view('sorties.edit', [
        'sortie' => $sorty,
        'categories' => $categories,
    ]);
}

    // Mise à jour
public function update(Request $request, Sortie $sortie)
{
    $data = $request->validate([
        'categorie_sortie_id' => 'required|exists:categories_sorties,id',
        'libelle' => 'required|max:255',
        'montant' => 'required|numeric|min:1',
        'date_sortie' => 'required|date',
        'beneficiaire' => 'nullable|max:255',
        'description' => 'nullable',
        'justificatif' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $totalEntrees = \App\Models\Entree::sum('montant');
    $totalSortiesAutres = Sortie::where('id', '!=', $sortie->id)->sum('montant');
    $solde = $totalEntrees - $totalSortiesAutres;

    if ($data['montant'] > $solde) {
        return back()
            ->withInput()
            ->withErrors(['montant' => 'Solde insuffisant. Solde disponible : ' . number_format($solde, 0, ',', ' ') . ' FCFA.']);
    }

    if ($request->hasFile('justificatif')) {
        $data['justificatif_path'] = $request->file('justificatif')->store('justificatifs', 'public');
    }

    $sortie->update($data);
    // dans update()
\App\Models\ActivityLog::log('sortie_modifiee', auth()->user()->name . ' a modifié la sortie "' . $sortie->libelle . '"');

    return redirect()->route('sorties.index')->with('success', 'Sortie modifiée avec succès.');
}

    // Suppression
    public function destroy(Sortie $sortie)
    {
        $sortie->delete();
        // dans destroy()
\App\Models\ActivityLog::log('sortie_supprimee', auth()->user()->name . ' a supprimé la sortie "' . $sortie->libelle . '"');

        return redirect()->route('sorties.index')
            ->with('success', 'Sortie supprimée.');
    }
    public function valider(Sortie $sortie)
{
    if (!in_array(auth()->user()->role, ['administrateur', 'superviseur'])) {
        abort(403, 'Action non autorisée.');
    }

    $sortie->update(['statut' => 'validee']);
    // dans valider()
\App\Models\ActivityLog::log('sortie_validee', auth()->user()->name . ' a validé la sortie "' . $sortie->libelle . '"');
    return redirect()->route('sorties.index')->with('success', 'Sortie validée.');
}

public function rejeter(Sortie $sortie)
{
    if (!in_array(auth()->user()->role, ['administrateur', 'superviseur'])) {
        abort(403, 'Action non autorisée.');
    }

    $sortie->update(['statut' => 'rejetee']);
    // dans rejeter()
\App\Models\ActivityLog::log('sortie_rejetee', auth()->user()->name . ' a rejeté la sortie "' . $sortie->libelle . '"');

    return redirect()->route('sorties.index')->with('success', 'Sortie rejetée.');
}
}