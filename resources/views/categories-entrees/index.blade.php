@extends('layouts.app-caisse')
@section('titre', 'Catégories des entrées')

@section('contenu')
<style>
    .page-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .btn-new { background: var(--forest); color: #fff; text-decoration: none; padding: .55rem 1.1rem; border-radius: 4px; font-size: .85rem; font-weight: 500; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-new:hover { background: var(--gold); color: #fff; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; }
    .panel-toolbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .panel-toolbar .panel-title { font-weight: 600; font-size: .95rem; }
    .panel-toolbar .count { font-size: .78rem; color: var(--ink-soft); margin-left: .5rem; font-weight: 400; }
    .search-box { position: relative; width: 260px; }
    .search-box input { width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .5rem .8rem .5rem 2.1rem; font-size: .85rem; }
    .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--ink-soft); }

    .cs-table { width: 100%; border-collapse: collapse; }
    .cs-table th { font-size: .75rem; color: var(--ink-soft); font-weight: 500; text-align: left; padding: .75rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .cs-table td { padding: .8rem 1.5rem; border-bottom: 1px solid var(--sage); font-size: .88rem; vertical-align: middle; }
    .cs-table tr:last-child td { border-bottom: none; }
    .cs-table tbody tr:hover { background: var(--cream); }
    .cs-table td.num { color: var(--ink-soft); width: 40px; }
    .actions a, .actions button { color: var(--ink-soft); text-decoration: none; margin-right: .6rem; background: none; border: none; padding: 0; cursor: pointer; font-size: .95rem; }
    .actions a.edit:hover { color: var(--forest); }
    .actions .delete:hover { color: var(--neg); }

    .cs-empty { padding: 3.5rem 1.5rem; text-align: center; color: var(--ink-soft); }
    .cs-empty .icon { font-size: 2.2rem; color: var(--sage); margin-bottom: .8rem; }
    .cs-empty p.title { font-weight: 500; color: var(--ink); margin: 0 0 .3rem; }
    .cs-empty p.sub { font-size: .85rem; margin: 0 0 1.2rem; }
</style>

<div class="page-head">
    <div>
        <h1>Catégories des entrées</h1>
        <p>Gérez les différentes catégories de recettes de la caisse.</p>
    </div>
    <a href="{{ route('categories-entrees.create') }}" class="btn-new">
        <i class="bi bi-plus-lg"></i> Nouvelle catégorie
    </a>
</div>

<div class="cs-panel">
    <div class="panel-toolbar">
        <div>
            <span class="panel-title">Liste des catégories</span>
            <span class="count">{{ ($categories ?? collect())->count() }} catégorie(s)</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="rechercheCategories" placeholder="Rechercher...">
        </div>
    </div>

    @if(($categories ?? collect())->isEmpty())
        <div class="cs-empty">
            <i class="bi bi-folder2-open icon"></i>
            <p class="title">Aucune catégorie</p>
            <p class="sub">Commencez par créer votre première catégorie d'entrée.</p>
            <a href="{{ route('categories-entrees.create') }}" class="btn-new">
                <i class="bi bi-plus-lg"></i> Créer une catégorie
            </a>
        </div>
    @else
        <table class="cs-table" id="tableCategories">
            <thead>
                <tr><th>#</th><th>Libellé</th><th>Description</th><th>Date de création</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($categories as $i => $categorie)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $categorie->libelle }}</td>
                    <td>{{ $categorie->description ?? '—' }}</td>
                    <td>{{ $categorie->created_at->format('d/m/Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('categories-entrees.edit', $categorie->id) }}" class="edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('categories-entrees.destroy', $categorie->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette catégorie ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
document.getElementById('rechercheCategories')?.addEventListener('input', function () {
    const filtre = this.value.toLowerCase();
    document.querySelectorAll('#tableCategories tbody tr').forEach(function (ligne) {
        ligne.style.display = ligne.textContent.toLowerCase().includes(filtre) ? '' : 'none';
    });
});
</script>
@endsection