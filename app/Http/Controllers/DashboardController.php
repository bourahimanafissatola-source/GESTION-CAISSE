<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\Sortie;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEntrees = Entree::sum('montant');
        $totalSorties = Sortie::sum('montant');
        $solde = $totalEntrees - $totalSorties;
        $nombreOperations = Entree::count() + Sortie::count();

        // Les 5 dernières entrées
        $entrees = Entree::latest('date_operation')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type' => 'entree',
                    'libelle' => $item->libelle,
                    'montant' => $item->montant,
                    'date_operation' => $item->date_operation,
                ];
            });

        // Les 5 dernières sorties
        $sorties = Sortie::latest('date_sortie')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type' => 'sortie',
                    'libelle' => $item->libelle,
                    'montant' => $item->montant,
                    'date_operation' => $item->date_sortie,
                ];
            });

        $dernieresOperations = $entrees
            ->concat($sorties)
            ->sortByDesc('date_operation')
            ->take(5)
            ->values();

        // Évolution sur 6 mois
        $mois = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));

        $evolutionLabels = $mois->map(fn($m) => ucfirst($m->translatedFormat('M Y')))->toArray();

        $evolutionEntrees = $mois->map(function ($m) {
            return (float) Entree::whereYear('date_operation', $m->year)
                ->whereMonth('date_operation', $m->month)
                ->sum('montant');
        })->toArray();

        $evolutionSorties = $mois->map(function ($m) {
            return (float) Sortie::whereYear('date_sortie', $m->year)
                ->whereMonth('date_sortie', $m->month)
                ->sum('montant');
        })->toArray();

        // Répartition des sorties par catégorie (mois en cours)
        $repartitionSorties = Sortie::with('categorie')
            ->whereMonth('date_sortie', now()->month)
            ->whereYear('date_sortie', now()->year)
            ->get()
            ->groupBy(fn($s) => $s->categorie->libelle ?? 'Autre')
            ->map(fn($groupe) => $groupe->sum('montant'));

        return view('dashboard', compact(
            'totalEntrees',
            'totalSorties',
            'solde',
            'nombreOperations',
            'dernieresOperations',
            'evolutionLabels',
            'evolutionEntrees',
            'evolutionSorties',
            'repartitionSorties'
        ));
    }
}