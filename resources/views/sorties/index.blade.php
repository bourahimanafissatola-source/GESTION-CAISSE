@extends('layouts.app-caisse')
@section('titre', 'Sorties')

@section('contenu')
<style>
    .page-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .btn-new { background: var(--forest); color: #fff; text-decoration: none; padding: .55rem 1.1rem; border-radius: 4px; font-size: .85rem; font-weight: 500; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-new:hover { background: var(--gold); color: #fff; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; }
    .panel-toolbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .search-box { position: relative; width: 280px; }
    .search-box input { width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .5rem .8rem .5rem 2.1rem; font-size: .85rem; }
    .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--ink-soft); }

    .cs-table { width: 100%; border-collapse: collapse; }
    .cs-table th { font-size: .75rem; color: var(--ink-soft); font-weight: 500; text-align: left; padding: .8rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .cs-table td { padding: .85rem 1.5rem; border-bottom: 1px solid var(--sage); font-size: .88rem; vertical-align: middle; }
    .cs-table tr:last-child td { border-bottom: none; }
    .cs-table tbody tr:hover { background: var(--cream); }
    .amount { text-align: right; font-variant-numeric: tabular-nums; color: var(--neg); font-weight: 500; }
    .cat-pill { background: #F5E9E7; color: var(--neg); font-size: .75rem; padding: .2rem .6rem; border-radius: 4px; }

    .statut-badge { font-size: .72rem; padding: .2rem .6rem; border-radius: 4px; font-weight: 500; }
    .statut-badge.en_attente { background: #FBF3E2; color: #93701C; }
    .statut-badge.validee { background: #E7F1EC; color: var(--pos); }
    .statut-badge.rejetee { background: #F5E9E7; color: var(--neg); }

    .justif-link { color: var(--forest); font-size: 1rem; }
    .justif-none { color: var(--ink-soft); font-size: .8rem; }

    .actions a { color: var(--ink-soft); text-decoration: none; margin-right: .6rem; font-size: .95rem; }
    .actions a.edit:hover { color: var(--forest); }
    .actions a.delete:hover { color: var(--neg); }
    .cs-empty { padding: 3rem 1.5rem; text-align: center; color: var(--ink-soft); }
    .actions .valid-btn, .actions .reject-btn {
    background: none; border: none; padding: 0; cursor: pointer; font-size: .95rem; margin-right: .6rem;
}
.actions .valid-btn { color: var(--pos); }
.actions .valid-btn:hover { color: #1F4D38; }
.actions .reject-btn { color: var(--neg); }
.actions .reject-btn:hover { color: #7A2E28; }
</style>

<div class="page-head">
    <div>
        <h1>Sorties de caisse</h1>
        <p>Historique des dépenses enregistrées.</p>
    </div>
    <a href="{{ route('sorties.create') }}" class="btn-new">
        <i class="bi bi-plus-lg"></i> Nouvelle sortie
    </a>
</div>

<div class="cs-panel">
    <div class="panel-toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="rechercheSorties" placeholder="Rechercher...">
        </div>
        <span style="font-size:.8rem; color: var(--ink-soft);">{{ $sorties->count() ?? 0 }} opération(s)</span>
    </div>

    @if(($sorties ?? collect())->isEmpty())
        <div class="cs-empty">
            <p>Aucune sortie enregistrée pour l'instant.</p>
            <a href="{{ route('sorties.create') }}" class="btn-new">
                <i class="bi bi-plus-lg"></i> Enregistrer une sortie
            </a>
        </div>
    @else
        <table class="cs-table" id="tableSorties">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Catégorie</th>
                    <th>Bénéficiaire</th>
                    <th class="text-end">Montant</th>
                    <th>Statut</th>
                     <th>Utilisateur</th>
                    <th>Justificatif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sorties as $sortie)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sortie->date_sortie)->format('d/m/Y') }}</td>
                    <td>{{ $sortie->libelle }}</td>
                    <td><span class="cat-pill">{{ $sortie->categorie->libelle ?? '—' }}</span></td>
                    <td>{{ $sortie->beneficiaire ?? '—' }}</td>
                    <td class="amount">−{{ number_format($sortie->montant, 0, ',', ' ') }} FCFA</td>
                    <td><span class="statut-badge {{ $sortie->statut }}">{{ ucfirst(str_replace('_',' ', $sortie->statut)) }}</span></td>
                     <td>{{ $entree->user->name ?? '—' }}</td>
                    <td>
                        @if($sortie->justificatif_path)
                            <a href="{{ asset('storage/'.$sortie->justificatif_path) }}" target="_blank" class="justif-link" title="Voir le justificatif">
                                <i class="bi bi-file-earmark-check"></i>
                            </a>
                        @else
                            <span class="justif-none">—</span>
                        @endif
                    </td>
                      <td class="actions">
    @if($sortie->statut === 'en_attente' && in_array(auth()->user()->role, ['administrateur', 'superviseur']))
        <form action="{{ route('sorties.valider', $sortie->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Valider cette sortie ?');">
            @csrf @method('PATCH')
            <button type="submit" class="valid-btn" title="Valider"><i class="bi bi-check-circle"></i></button>
        </form>
        <form action="{{ route('sorties.rejeter', $sortie->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Rejeter cette sortie ?');">
            @csrf @method('PATCH')
            <button type="submit" class="reject-btn" title="Rejeter"><i class="bi bi-x-circle"></i></button>
        </form>
    @endif
    <a href="{{ route('sorties.edit', $sortie->id) }}" class="edit"><i class="bi bi-pencil"></i></a>
    <form action="{{ route('sorties.destroy', $sortie->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette sortie ?');">
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
document.getElementById('rechercheSorties')?.addEventListener('input', function () {
    const filtre = this.value.toLowerCase();
    document.querySelectorAll('#tableSorties tbody tr').forEach(function (ligne) {
        ligne.style.display = ligne.textContent.toLowerCase().includes(filtre) ? '' : 'none';
    });
});
</script>
@endsection