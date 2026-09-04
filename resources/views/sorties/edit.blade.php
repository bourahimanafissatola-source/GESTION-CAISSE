@extends('layouts.app-caisse')
@section('titre', 'Modifier une sortie')

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

    .current-file {
        display: flex; align-items: center; gap: .6rem;
        border: 1px solid var(--sage); border-radius: 4px;
        padding: .6rem .9rem; margin-bottom: .8rem; font-size: .85rem;
    }
    .current-file a { color: var(--forest); text-decoration: none; }
    .current-file i { color: var(--forest); }

    .upload-zone {
        border: 1.5px dashed var(--sage); border-radius: 6px; padding: 1.3rem;
        text-align: center; color: var(--ink-soft); cursor: pointer;
        transition: border-color .15s ease, background .15s ease;
    }
    .upload-zone:hover { border-color: var(--gold); background: var(--cream); }
    .upload-zone i { font-size: 1.4rem; display: block; margin-bottom: .4rem; color: var(--forest); }
    .upload-zone span { font-size: .8rem; }
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
    <h1>Modifier la sortie</h1>
    <p>Corriger les informations de cette dépense.</p>
</div>

<div class="form-card">
    <form action="{{ route('sorties.update', ['sorty' => $sortie->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <label>Catégorie</label>
            <select name="categorie_sortie_id" required>
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_sortie_id', $sortie->categorie_sortie_id) == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->libelle }}
                    </option>
                @endforeach
            </select>
            @error('categorie_sortie_id') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label>Libellé</label>
            <input type="text" name="libelle" value="{{ old('libelle', $sortie->libelle) }}" required>
            @error('libelle') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-row">
    <label>Montant <span class="hint">FCFA</span></label>
<input type="text" id="montant_affiche" inputmode="numeric" value="{{ number_format(old('montant', $sortie->montant), 0, ',', ' ') }}" autocomplete="off">
<input type="hidden" name="montant" id="montant_reel" value="{{ old('montant', $sortie->montant) }}">
    @error('montant') <div class="error-msg">{{ $message }}</div> @enderror
</div>
            <div class="form-row">
                <label>Date</label>
                <input type="date" name="date_sortie" value="{{ old('date_sortie', \Carbon\Carbon::parse($sortie->date_sortie)->format('Y-m-d')) }}" required>
                @error('date_sortie') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <label>Bénéficiaire</label>
            <input type="text" name="beneficiaire" value="{{ old('beneficiaire', $sortie->beneficiaire) }}">
        </div>

        <div class="form-row">
            <label>Justificatif <span class="hint">reçu ou décharge — photo ou PDF</span></label>

            @if($sortie->justificatif_path)
                <div class="current-file">
                    <i class="bi bi-file-earmark-check"></i>
                    <a href="{{ asset('storage/'.$sortie->justificatif_path) }}" target="_blank">Voir le justificatif actuel</a>
                </div>
            @endif

            <label class="upload-zone" for="justificatif">
                <i class="bi bi-cloud-arrow-up"></i>
                <span>{{ $sortie->justificatif_path ? 'Remplacer le justificatif' : 'Scanner ou téléverser un fichier' }}</span>
                <input type="file" name="justificatif" id="justificatif" accept="image/*,application/pdf" capture="environment">
            </label>
            <div id="fileName"></div>
            @error('justificatif') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label>Description <span class="hint">optionnel</span></label>
            <textarea name="description" rows="3">{{ old('description', $sortie->description) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer les modifications
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