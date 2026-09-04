@extends('layouts.app-caisse')
@section('titre', 'Tableau de bord')

@section('contenu')
<style>
    .cs-ledger { background:#fff; border:1px solid var(--sage); border-radius:6px; display:flex; margin-bottom:1.5rem; overflow:hidden; }
    .cs-ledger .stat { flex:1; min-width:0; padding:1.2rem 1.4rem; border-right:1px solid var(--sage); }
    .cs-ledger .stat:last-child { border-right:none; }
    .cs-ledger .stat .label { font-size:.78rem; color:var(--ink-soft); margin-bottom:.35rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .cs-ledger .stat .value {
        font-size:1.35rem; font-weight:600; line-height:1.25;
        overflow-wrap:break-word; word-break:break-word;
    }
    .cs-ledger .stat.pos .value { color:var(--pos); }
    .cs-ledger .stat.neg .value { color:var(--neg); }

    .cs-panel { background:#fff; border:1px solid var(--sage); border-radius:6px; overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .cs-panel .panel-head { padding:1.1rem 1.5rem; border-bottom:1px solid var(--sage); font-weight:600; font-size:.95rem; }

    .cs-table { width:100%; border-collapse:collapse; min-width:620px; }
    .cs-table th { font-size:.75rem; color:var(--ink-soft); font-weight:500; padding:.75rem 1.5rem; border-bottom:1px solid var(--sage); text-align:left; }
    .cs-table td { padding:.8rem 1.5rem; border-bottom:1px solid var(--sage); font-size:.88rem; vertical-align:middle; }
    .cs-table tr:last-child td { border-bottom:none; }
    .cs-table tbody tr:hover { background:var(--cream); }

    .cs-table td.libelle {
        max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }

    .cs-badge { font-size:.72rem; padding:.2rem .6rem; border-radius:4px; font-weight:500; white-space:nowrap; }
    .cs-badge.entree { background:#E7F1EC; color:var(--pos); }
    .cs-badge.sortie { background:#F5E9E7; color:var(--neg); }

    td.amount { text-align:right; font-variant-numeric:tabular-nums; white-space:nowrap; font-weight:500; }
    td.amount.entree { color:var(--pos); }
    td.amount.sortie { color:var(--neg); }

    .cs-empty { padding:3rem 1.5rem; text-align:center; color:var(--ink-soft); }

    @media (max-width: 768px) {
        .cs-ledger { flex-direction:column; }
        .cs-ledger .stat { border-right:none; border-bottom:1px solid var(--sage); }
        .cs-ledger .stat:last-child { border-bottom:none; }
        .cs-ledger .stat .value { font-size:1.15rem; }
        .cs-table td.libelle { max-width:160px; }
    }
</style>

<div class="cs-ledger">
    <div class="stat">
        <div class="label">Total des entrées</div>
        <div class="value">{{ number_format($totalEntrees ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="stat">
        <div class="label">Total des sorties</div>
        <div class="value">{{ number_format($totalSorties ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="stat {{ ($solde ?? 0) >= 0 ? 'pos' : 'neg' }}">
        <div class="label">Solde de caisse</div>
        <div class="value">{{ number_format($solde ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="stat">
        <div class="label">Nombre d'opérations</div>
        <div class="value">{{ $nombreOperations ?? 0 }}</div>
    </div>
</div> 
<div style="display:grid; grid-template-columns: 1.6fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <div class="cs-panel">
        <div class="panel-head">Évolution sur 6 mois</div>
        <div style="padding:1.5rem;">
            <canvas id="graphEvolution" height="90"></canvas>
        </div>
    </div>
    <div class="cs-panel">
        <div class="panel-head">Sorties par catégorie (mois en cours)</div>
        <div style="padding:1.5rem;">
            <canvas id="graphRepartition" height="90"></canvas>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('graphEvolution'), {
    type: 'line',
    data: {
        labels: @json($evolutionLabels ?? []),
        datasets: [
            {
                label: 'Entrées',
                data: @json($evolutionEntrees ?? []),
                borderColor: '#2F6B4F',
                backgroundColor: 'rgba(47,107,79,0.08)',
                tension: .3, fill: true,
            },
            {
                label: 'Sorties',
                data: @json($evolutionSorties ?? []),
                borderColor: '#A6453D',
                backgroundColor: 'rgba(166,69,61,0.08)',
                tension: .3, fill: true,
            },
        ],
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: { y: { ticks: { callback: v => new Intl.NumberFormat('fr-FR').format(v) } } },
    },
});

new Chart(document.getElementById('graphRepartition'), {
    type: 'doughnut',
    data: {
        labels: @json(($repartitionSorties ?? collect())->keys()),
        datasets: [{
            data: @json(($repartitionSorties ?? collect())->values()),
            backgroundColor: ['#0F3D2E', '#B8923D', '#2F6B4F', '#A6453D', '#5B6660', '#7A5C2E'],
        }],
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
    },
});
</script>

<div class="cs-panel">
    <div class="panel-head">Dernières opérations</div>

    @if(($dernieresOperations ?? collect())->isEmpty())
        <div class="cs-empty">
            <p>Aucune opération enregistrée pour l'instant.</p>
            <a href="{{ route('entrees.create') }}" style="background:var(--forest); color:#fff; padding:.5rem 1.1rem; border-radius:4px; text-decoration:none; font-size:.85rem;">
                Enregistrer une opération
            </a>
        </div>
    @else
        <table class="cs-table">
            <thead>
                <tr><th>Type</th><th>Libellé</th><th class="text-end">Montant</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($dernieresOperations as $op)
                <tr>
                    <td><span class="cs-badge {{ $op->type }}">{{ $op->type === 'entree' ? 'Entrée' : 'Sortie' }}</span></td>
                    <td class="libelle" title="{{ $op->libelle }}">{{ $op->libelle }}</td>
                    <td class="amount {{ $op->type }}">
                        {{ $op->type === 'entree' ? '+' : '−' }}{{ number_format($op->montant, 0, ',', ' ') }} FCFA
                    </td>
                    <td>{{ \Carbon\Carbon::parse($op->date_operation)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection