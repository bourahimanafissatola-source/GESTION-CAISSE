@extends('layouts.app-caisse')
@section('titre', 'Nouvelle entrée')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }

    .form-card {
        background: #fff;
        border: 1px solid var(--sage);
        border-radius: 6px;
        padding: 2rem;
        max-width: 640px;
    }
    .form-row { margin-bottom: 1.3rem; }
    .form-row label {
        display: block;
        font-size: .82rem;
        font-weight: 500;
        color: var(--ink);
        margin-bottom: .4rem;
    }
    .form-row .hint {
        font-size: .75rem;
        color: var(--ink-soft);
        font-weight: 400;
        margin-left: .3rem;
    }
    .form-row input,
    .form-row select,
    .form-row textarea {
        width: 100%;
        border: 1px solid var(--sage);
        border-radius: 4px;
        padding: .6rem .8rem;
        font-size: .9rem;
        font-family: inherit;
        color: var(--ink);
        background: #fff;
        transition: border-color .15s ease;
    }
    .form-row input:focus,
    .form-row select:focus,
    .form-row textarea:focus {
        outline: none;
        border-color: var(--forest);
    }
    .form-row textarea { resize: vertical; }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.3rem;
    }

    .form-actions {
        display: flex;
        gap: .8rem;
        margin-top: .5rem;
        padding-top: 1.3rem;
        border-top: 1px solid var(--sage);
    }
    .btn-primary {
        background: var(--forest);
        color: #fff;
        border: none;
        padding: .65rem 1.4rem;
        border-radius: 4px;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .btn-primary:hover { background: var(--gold); }
    .btn-cancel {
        color: var(--ink-soft);
        text-decoration: none;
        padding: .65rem 1.4rem;
        font-size: .88rem;
        border: 1px solid var(--sage);
        border-radius: 4px;
    }
    .btn-cancel:hover { background: var(--cream); }

    .error-msg { color: var(--neg); font-size: .78rem; margin-top: .3rem; }
</style>

<div class="page-head">
    <h1>Nouvelle entrée de caisse</h1>
    <p>Enregistrer une recette encaissée.</p>
</div>

<div class="form-card">
    <form action="{{ route('entrees.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <label>Catégorie</label>
            <select name="categorie_entree_id" required>
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_entree_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->libelle }}
                    </option>
                @endforeach
            </select>
            @error('categorie_entree_id') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label>Libellé</label>
            <input type="text" name="libelle" placeholder="Exemple : Vente...." value="{{ old('libelle') }}" required>
            @error('libelle') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

                <div class="form-row">
    <label>Montant <span class="hint">FCFA</span></label>
    <input type="text" id="montant_affiche" inputmode="numeric" placeholder="25 000" value="{{ old('montant') ? number_format(old('montant'), 0, ',', ' ') : '' }}" autocomplete="off">
    <input type="hidden" name="montant" id="montant_reel" value="{{ old('montant') }}">
    @error('montant') <div class="error-msg">{{ $message }}</div> @enderror
</div>
            <div class="form-row">
                <label>Date</label>
                <input type="date" name="date_operation" value="{{ old('date_operation', date('Y-m-d')) }}" required>
                @error('date_operation') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <label>Description <span class="hint">optionnel</span></label>
            <textarea name="description" rows="4" placeholder="Précisions sur l'opération...">{{ old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer l'entrée
            </button>
            <a href="{{ route('entrees.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
<script>
    (function () {
        const affiche = document.getElementById('montant_affiche');
        const reel = document.getElementById('montant_reel');

        affiche.addEventListener('input', function () {
            let chiffres = this.value.replace(/\D/g, '');
            reel.value = chiffres;
            this.value = chiffres ? new Intl.NumberFormat('fr-FR').format(chiffres) : '';
        });
    })();
</script>
@endsection