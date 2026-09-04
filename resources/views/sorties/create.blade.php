@extends('layouts.app-caisse')
@section('titre', 'Nouvelle sortie')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }

    .form-card { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 2rem; max-width: 640px; }
    .form-row { margin-bottom: 1.3rem; }
    .form-row label { display: block; font-size: .82rem; font-weight: 500; color: var(--ink); margin-bottom: .4rem; }
    .form-row .hint { font-size: .75rem; color: var(--ink-soft); font-weight: 400; margin-left: .3rem; }
    .form-row input, .form-row select, .form-row textarea {
        width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .6rem .8rem;
        font-size: .9rem; font-family: inherit; color: var(--ink); background: #fff;
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus { outline: none; border-color: var(--forest); }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.3rem; }

    .upload-zone {
        border: 1.5px dashed var(--sage);
        border-radius: 6px;
        padding: 1.5rem;
        text-align: center;
        color: var(--ink-soft);
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease;
    }
    .upload-zone:hover { border-color: var(--gold); background: var(--cream); }
    .upload-zone i { font-size: 1.6rem; display: block; margin-bottom: .5rem; color: var(--forest); }
    .upload-zone span { font-size: .82rem; }
    .upload-zone input[type=file] { display: none; }
    #fileName { font-size: .78rem; color: var(--forest); margin-top: .5rem; }

    .form-actions { display: flex; gap: .8rem; margin-top: .5rem; padding-top: 1.3rem; border-top: 1px solid var(--sage); }
    .btn-primary {
        background: var(--forest); color: #fff; border: none; padding: .65rem 1.4rem; border-radius: 4px;
        font-size: .88rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: .5rem;
    }
    .btn-primary:hover { background: var(--gold); }
    .btn-cancel { color: var(--ink-soft); text-decoration: none; padding: .65rem 1.4rem; font-size: .88rem; border: 1px solid var(--sage); border-radius: 4px; }
    .btn-cancel:hover { background: var(--cream); }
    .error-msg { color: var(--neg); font-size: .78rem; margin-top: .3rem; }
</style>

<div class="page-head">
    <h1>Nouvelle sortie de caisse</h1>
    <p>Enregistrer une dépense avec son justificatif.</p>
</div>

<div class="form-card">
    <form action="{{ route('sorties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <label>Catégorie</label>
            <select name="categorie_sortie_id" required>
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_sortie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->libelle }}
                    </option>
                @endforeach
            </select>
            @error('categorie_sortie_id') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label>Libellé</label>
            <input type="text" name="libelle" placeholder="Exemple : Achat fournitures bureau" value="{{ old('libelle') }}" required>
            @error('libelle') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-row">
    <label>Montant <span class="hint">FCFA</span></label>
    <input type="text" id="montant_affiche" inputmode="numeric" placeholder="25 000" value="{{ old('montant') ? number_format(old('montant'), 0, ',', ' ') : '' }}" autocomplete="off">
    <input type="hidden" name="montant" id="montant_reel" value="{{ old('montant') }}">
    @error('montant') <div class="error-msg">{{ $message }}</div> @enderror
</div>
            <div class="form-row">
                <label>Date</label>
                <input type="date" name="date_sortie" value="{{ old('date_sortie', date('Y-m-d')) }}" required>
                @error('date_sortie') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <label>Bénéficiaire</label>
            <input type="text" name="beneficiaire" placeholder="Nom de la personne ou structure payée" value="{{ old('beneficiaire') }}">
        </div>

        <div class="form-row">
            <label>Justificatif <span class="hint">reçu ou décharge — photo ou PDF</span></label>
            <label class="upload-zone" for="justificatif">
                <i class="bi bi-cloud-arrow-up"></i>
                <span>Cliquer pour scanner ou téléverser un fichier</span>
                <input type="file" name="justificatif" id="justificatif" accept="image/*,application/pdf" capture="environment">
            </label>
            <div id="fileName"></div>
            @error('justificatif') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label>Description <span class="hint">optionnel</span></label>
            <textarea name="description" rows="3" placeholder="Précisions sur l'opération...">{{ old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer la sortie
            </button>
            <a href="{{ route('sorties.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<script>
document.getElementById('justificatif')?.addEventListener('change', function () {
    document.getElementById('fileName').textContent = this.files.length ? '📎 ' + this.files[0].name : '';
});
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