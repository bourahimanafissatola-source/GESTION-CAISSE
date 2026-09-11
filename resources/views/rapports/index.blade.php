@extends('layouts.app-caisse')
@section('titre', 'Rapports')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }

    .filter-bar {
        background: #fff; border: 1px solid var(--sage); border-radius: 6px;
        padding: 1.2rem 1.5rem; display: flex; align-items: flex-end; gap: 1.2rem; margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .filter-bar .f-group label { display: block; font-size: .75rem; color: var(--ink-soft); margin-bottom: .3rem; }
    .filter-bar input, .filter-bar select {
        border: 1px solid var(--sage); border-radius: 4px; padding: .5rem .7rem; font-size: .85rem;
    }
    .btn-primary { background: var(--forest); color: #fff; border: none; padding: .55rem 1.2rem; border-radius: 4px; font-size: .85rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-primary:hover { background: var(--gold); }
    .btn-outline {
        border: 1px solid var(--sage); color: var(--ink); background: #fff; padding: .55rem 1.1rem;
        border-radius: 4px; font-size: .85rem; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn-outline:hover { border-color: var(--forest); color: var(--forest); }

    .cs-ledger { background: #fff; border: 1px solid var(--sage); border-radius: 6px; display: flex; margin-bottom: 1.5rem; }
    .cs-ledger .stat { flex: 1; padding: 1.2rem 1.5rem; border-right: 1px solid var(--sage); }
    .cs-ledger .stat:last-child { border-right: none; }
    .cs-ledger .stat .label { font-size: .78rem; color: var(--ink-soft); margin-bottom: .3rem; }
    .cs-ledger .stat .value { font-size: 1.4rem; font-weight: 600; }
    .cs-ledger .stat.pos .value { color: var(--pos); }
    .cs-ledger .stat.neg .value { color: var(--neg); }

    .export-row { display: flex; gap: .8rem; margin-bottom: 1.5rem; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; }
    .panel-head { padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--sage); font-weight: 600; font-size: .95rem; }
    .cs-table { width: 100%; border-collapse: collapse; }
    .cs-table th { font-size: .75rem; color: var(--ink-soft); font-weight: 500; text-align: left; padding: .75rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .cs-table td { padding: .8rem 1.5rem; border-bottom: 1px solid var(--sage); font-size: .88rem; }
    .cs-table tr:last-child td { border-bottom: none; }
    .amount { text-align: right; font-variant-numeric: tabular-nums; }
    .amount.entree { color: var(--pos); }
    .amount.sortie { color: var(--neg); }
    .cs-empty { padding: 2.5rem 1.5rem; text-align: center; color: var(--ink-soft); }
</style>

<div class="page-head">
    <h1>Rapports</h1>
    <p>Consulter et exporter le relevé de caisse sur une période.</p>
</div>

<form method="GET" action="{{ route('rapports.index') }}" class="filter-bar">
    <div class="f-group">
        <label>Du</label>
        <input type="date" name="date_debut" value="{{ request('date_debut', now()->startOfMonth()->format('Y-m-d')) }}">
    </div>
    <div class="f-group">
        <label>Au</label>
        <input type="date" name="date_fin" value="{{ request('date_fin', now()->format('Y-m-d')) }}">
    </div>
    <div class="f-group">
        <label>Type</label>
        <select name="type">
            <option value="">Toutes les opérations</option>
            <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>Entrées uniquement</option>
            <option value="sortie" {{ request('type') === 'sortie' ? 'selected' : '' }}>Sorties uniquement</option>
        </select>
    </div>
    <button type="submit" class="btn-primary"><i class="bi bi-funnel"></i> Filtrer</button>
</form>

<div class="cs-ledger">
    <div class="stat pos">
        <div class="label">Total entrées (période)</div>
        <div class="value">{{ number_format($totalEntrees ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="stat neg">
        <div class="label">Total sorties (période)</div>
        <div class="value">{{ number_format($totalSorties ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="stat">
        <div class="label">Solde net</div>
        <div class="value">{{ number_format(($totalEntrees ?? 0) - ($totalSorties ?? 0), 0, ',', ' ') }} FCFA</div>
    </div>
</div>

<div class="export-row">
    <a href="{{ route('rapports.export', ['format' => 'xlsx'] + request()->query()) }}" class="btn-outline">
        <i class="bi bi-file-earmark-spreadsheet"></i> Exporter en Excel
    </a>
    <a href="{{ route('rapports.export', ['format' => 'pdf'] + request()->query()) }}" class="btn-outline">
        <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
    </a>
</div>

<div class="cs-panel">
    <div class="panel-head">Détail des opérations</div>
    @if(($operations ?? collect())->isEmpty())
        <div class="cs-empty">Aucune opération sur cette période.</div>
    @else
        <table class="cs-table">
            <thead>
                <tr><th>Date</th><th>Type</th><th>Libellé</th><th>Catégorie</th><th class="text-end">Montant</th></tr>
            </thead>
            <tbody>
                @foreach($operations as $op)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($op->date)->format('d/m/Y') }}</td>
                    <td>{{ $op->type === 'entree' ? 'Entrée' : 'Sortie' }}</td>
                    <td>{{ $op->libelle }}</td>
                    <td>{{ $op->categorie_nom }}</td>
                    <td class="amount {{ $op->type }}">
                        {{ $op->type === 'entree' ? '+' : '−' }}{{ number_format($op->montant, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection