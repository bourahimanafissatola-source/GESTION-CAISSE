@extends('layouts.app-caisse')
@section('titre', 'Entrées')

@section('contenu')
<style>
    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }
    .page-head h1 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0 0 .25rem;
    }
    .page-head p {
        color: var(--ink-soft);
        font-size: .85rem;
        margin: 0;
    }
    .btn-new {
        background: var(--forest);
        color: #fff;
        text-decoration: none;
        padding: .55rem 1.1rem;
        border-radius: 4px;
        font-size: .85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .btn-new:hover { background: var(--gold); color: #fff; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; }
    .panel-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--sage);
    }
    .search-box {
        position: relative;
        width: 280px;
    }
    .search-box input {
        width: 100%;
        border: 1px solid var(--sage);
        border-radius: 4px;
        padding: .5rem .8rem .5rem 2.1rem;
        font-size: .85rem;
    }
    .search-box i {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-soft);
    }

    .cs-table { width: 100%; border-collapse: collapse; }
    .cs-table th {
        font-size: .75rem;
        color: var(--ink-soft);
        font-weight: 500;
        text-align: left;
        padding: .8rem 1.5rem;
        border-bottom: 1px solid var(--sage);
    }
    .cs-table td {
        padding: .85rem 1.5rem;
        border-bottom: 1px solid var(--sage);
        font-size: .88rem;
        vertical-align: middle;
    }
    .cs-table tr:last-child td { border-bottom: none; }
    .cs-table tbody tr:hover { background: var(--cream); }
    .amount { text-align: right; font-variant-numeric: tabular-nums; color: var(--pos); font-weight: 500; }
    .cat-pill {
        background: #EEF3F0;
        color: var(--forest);
        font-size: .75rem;
        padding: .2rem .6rem;
        border-radius: 4px;
    }
    .actions a {
        color: var(--ink-soft);
        text-decoration: none;
        margin-right: .6rem;
        font-size: .95rem;
    }
    .actions a.edit:hover { color: var(--forest); }
    .actions a.delete:hover { color: var(--neg); }

    .cs-empty { padding: 3rem 1.5rem; text-align: center; color: var(--ink-soft); }
</style>

<div class="page-head">
    <div>
        <h1>Entrées de caisse</h1>
        <p>Historique des recettes enregistrées.</p>
    </div>
    <a href="{{ route('entrees.create') }}" class="btn-new">
        <i class="bi bi-plus-lg"></i> Nouvelle entrée
    </a>
</div>

<div class="cs-panel">
    <div class="panel-toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="rechercheEntrees" placeholder="Rechercher...">
        </div>
        <span style="font-size:.8rem; color: var(--ink-soft);">{{ $entrees->count() ?? 0 }} opération(s)</span>
    </div>

    @if(($entrees ?? collect())->isEmpty())
        <div class="cs-empty">
            <p>Aucune entrée enregistrée pour l'instant.</p>
            <a href="{{ route('entrees.create') }}" class="btn-new">
                <i class="bi bi-plus-lg"></i> Enregistrer une entrée
            </a>
        </div>
    @else
        <table class="cs-table" id="tableEntrees">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Catégorie</th>
                    <th class="text-end">Montant</th>
                    <th>Utilisateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entrees as $entree)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($entree->date_operation)->format('d/m/Y') }}</td>
                    <td>{{ $entree->libelle }}</td>
                    <td><span class="cat-pill">{{ $entree->categorie->libelle ?? '—' }}</span></td>
                    <td class="amount">+{{ number_format($entree->montant, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $entree->user->name ?? '—' }}</td>
                    <td class="actions">
                        <a href="{{ route('entrees.edit', $entree->id) }}" class="edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('entrees.destroy', $entree->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette entrée ?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;" class="delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
document.getElementById('rechercheEntrees')?.addEventListener('input', function () {
    const filtre = this.value.toLowerCase();
    document.querySelectorAll('#tableEntrees tbody tr').forEach(function (ligne) {
        ligne.style.display = ligne.textContent.toLowerCase().includes(filtre) ? '' : 'none';
    });
});
</script>
@endsection