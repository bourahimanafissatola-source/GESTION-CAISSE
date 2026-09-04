@extends('layouts.app-caisse')
@section('titre', 'Nouvelle catégorie d\'entrée')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .form-card { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 2rem; max-width: 560px; }
    .form-row { margin-bottom: 1.3rem; }
    .form-row label { display: block; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
    .form-row .hint { font-size: .75rem; color: var(--ink-soft); font-weight: 400; margin-left: .3rem; }
    .form-row input, .form-row textarea {
        width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .6rem .8rem;
        font-size: .9rem; font-family: inherit; color: var(--ink);
    }
    .form-row input:focus, .form-row textarea:focus { outline: none; border-color: var(--forest); }
    .form-actions { display: flex; gap: .8rem; margin-top: .5rem; padding-top: 1.3rem; border-top: 1px solid var(--sage); }
    .btn-primary { background: var(--forest); color: #fff; border: none; padding: .65rem 1.4rem; border-radius: 4px; font-size: .88rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-primary:hover { background: var(--gold); }
    .btn-cancel { color: var(--ink-soft); text-decoration: none; padding: .65rem 1.4rem; font-size: .88rem; border: 1px solid var(--sage); border-radius: 4px; }
    .btn-cancel:hover { background: var(--cream); }
    .error-msg { color: var(--neg); font-size: .78rem; margin-top: .3rem; }
</style>

<div class="page-head">
    <h1>Nouvelle catégorie d'entrée</h1>
    <p>Ajouter un type de recette disponible à la saisie.</p>
</div>

<div class="form-card">
    <form action="{{ route('categories-entrees.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <label>Libellé</label>
            <input type="text" name="libelle" placeholder="Exemple : Vente" value="{{ old('libelle') }}" required>
            @error('libelle') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-row">
            <label>Description <span class="hint">optionnel</span></label>
            <textarea name="description" rows="4" placeholder="Précision sur cette catégorie...">{{ old('description') }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Créer la catégorie</button>
            <a href="{{ route('categories-entrees.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection