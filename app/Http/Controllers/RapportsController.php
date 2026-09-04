<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\Sortie;
use Illuminate\Http\Request;

class RapportsController extends Controller
{
    public function index(Request $request)
    {
        $dateDebut = $request->input(
            'date_debut',
            now()->startOfMonth()->format('Y-m-d')
        );

        $dateFin = $request->input(
            'date_fin',
            now()->format('Y-m-d')
        );

        $type = $request->input('type');

        // ENTRÉES
        $entrees = Entree::whereBetween('date_operation', [
                $dateDebut,
                $dateFin
            ])
            ->get()
            ->map(function ($entree) {
                $entree->type = 'entree';
                $entree->date = $entree->date_operation;
                $entree->categorie = $entree->categorie_entree_id;
                return $entree;
            });

        // SORTIES
        $sorties = Sortie::whereBetween('date_sortie', [
                $dateDebut,
                $dateFin
            ])
            ->get()
            ->map(function ($sortie) {
                $sortie->type = 'sortie';
                $sortie->date = $sortie->date_sortie;
                $sortie->categorie = $sortie->categorie_sortie_id;
                return $sortie;
            });

        // FILTRE PAR TYPE
        if ($type === 'entree') {
            $sorties = collect();
        }

        if ($type === 'sortie') {
            $entrees = collect();
        }

        // FUSION DES OPÉRATIONS
        $operations = $entrees
            ->concat($sorties)
            ->sortByDesc('date')
            ->values();

        // TOTAUX
        $totalEntrees = $entrees->sum('montant');
        $totalSorties = $sorties->sum('montant');

        return view('rapports.index', compact(
            'operations',
            'totalEntrees',
            'totalSorties',
            'dateDebut',
            'dateFin',
            'type'
        ));
    }

    public function export(Request $request)
    {
        return back()->with(
            'error',
            'L’export sera activé après la mise en place du module Excel/PDF.'
        );
    }
}