<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\Sortie;
use App\Exports\OperationsExport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->user()->role !== 'administrateur') {
                    abort(403, 'Accès réservé aux administrateurs.');
                }
                return $next($request);
            }),
        ];
    }

    protected function getOperations(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));
        $type = $request->input('type');

        $entrees = Entree::with('categorie')
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->get()
            ->map(function ($entree) {
                $entree->type = 'entree';
                $entree->date = $entree->date_operation;
                $entree->categorie_nom = $entree->categorie->libelle ?? '';
                return $entree;
            });

        $sorties = Sortie::with('categorie')
            ->whereBetween('date_sortie', [$dateDebut, $dateFin])
            ->get()
            ->map(function ($sortie) {
                $sortie->type = 'sortie';
                $sortie->date = $sortie->date_sortie;
                $sortie->categorie_nom = $sortie->categorie->libelle ?? '';
                return $sortie;
            });

        if ($type === 'entree') {
            $sorties = collect();
        }
        if ($type === 'sortie') {
            $entrees = collect();
        }

        $operations = $entrees->concat($sorties)->sortByDesc('date')->values();

        $totalEntrees = $entrees->sum('montant');
        $totalSorties = $sorties->where('statut', 'validee')->sum('montant');

        return compact('operations', 'totalEntrees', 'totalSorties', 'dateDebut', 'dateFin', 'type');
    }

    public function index(Request $request)
    {
        return view('rapports.index', $this->getOperations($request));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $data = $this->getOperations($request);

        \App\Models\ActivityLog::log('export_rapport', auth()->user()->name . ' a exporté un rapport en ' . strtoupper($format) . ' (' . $data['dateDebut'] . ' au ' . $data['dateFin'] . ').');

        $nomFichier = 'releve-caisse_' . $data['dateDebut'] . '_' . $data['dateFin'];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('rapports.pdf', $data);
            return $pdf->download($nomFichier . '.pdf');
        }

        return Excel::download(new OperationsExport($data['operations']), $nomFichier . '.xlsx');
    }
}