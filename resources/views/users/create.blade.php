@extends('layouts.app-caisse')
@section('titre', 'Nouvel utilisateur')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .form-card { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 2rem; max-width: 560px; }
    .form-row { margin-bottom: 1.3rem; }
    .form-row label { display: block; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
    .form-row .hint { font-size: .75rem; color: var(--ink-soft); font-weight: 400; margin-left: .3rem; }
    .form-row input, .form-row select {
        width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .6rem .8rem;
        font-size: .9rem; font-family: inherit; color: var(--ink);
    }
    .form-row input:focus, .form-row select:focus { outline: none; border-color: var(--forest); }
    .role-options { display: grid; gap: .6rem; }
    .role-option {
        border: 1px solid var(--sage); border-radius: 4px; padding: .7rem .9rem;
        display: flex; align-items: flex-start; gap: .6rem; cursor: pointer; font-size: .85rem;
    }
    .role-option:hover { border-color: var(--forest); }
    .role-option input { width: auto; margin-top: .2rem; }
    .role-option .r-title { font-weight: 500; }
    .role-option .r-desc { color: var(--ink-soft); font-size: .78rem; }
    .form-actions { display: flex; gap: .8rem; margin-top: .5rem; padding-top: 1.3rem; border-top: 1px solid var(--sage); }
    .btn-primary { background: var(--forest); color: #fff; border: none; padding: .65rem 1.4rem; border-radius: 4px; font-size: .88rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-primary:hover { background: var(--gold); }
    .btn-cancel { color: var(--ink-soft); text-decoration: none; padding: .65rem 1.4rem; font-size: .88rem; border: 1px solid var(--sage); border-radius: 4px; }
    .btn-cancel:hover { background: var(--cream); }
    .error-msg { color: var(--neg); font-size: .78rem; margin-top: .3rem; }
</style>

<div class="page-head">
    <h1>Nouvel utilisateur</h1>
    <p>Créer un compte d'accès à l'application.</p>
</div>

<div class="form-card">
 <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
    @csrf
    <div class="form-row">
        <label>Nom complet</label>
        <input type="text" name="name" value="{{ old('name') }}" autocomplete="off" required>
        @error('name') <div class="error-msg">{{ $message }}</div> @enderror
    </div>
    <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" autocomplete="new-email" required>
        @error('email') <div class="error-msg">{{ $message }}</div> @enderror
    </div>
        <div class="form-row">
            <label>Mot de passe</label>
            <input type="password" name="password" autocomplete="new-password" required>
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-row">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <div class="form-row">
            <label>Profil d'accès</label>
            <div class="role-options">
                <label class="role-option">
                    <input type="radio" name="role" value="operateur" {{ old('role') === 'operateur' ? 'checked' : '' }} required>
                    <div><div class="r-title">Opérateur</div><div class="r-desc">Ajout uniquement — ne peut ni modifier ni supprimer</div></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="agent_confirme" {{ old('role') === 'agent_confirme' ? 'checked' : '' }}>
                    <div><div class="r-title">Agent confirmé</div><div class="r-desc">Ajout et modification — ne peut pas supprimer</div></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="superviseur" {{ old('role') === 'superviseur' ? 'checked' : '' }}>
                    <div><div class="r-title">Superviseur</div><div class="r-desc">Ajout, modification et suppression, validation des sorties</div></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="administrateur" {{ old('role') === 'administrateur' ? 'checked' : '' }}>
                    <div><div class="r-title">Administrateur</div><div class="r-desc">Accès total, gestion des utilisateurs et de la configuration</div></div>
                </label>
            </div>
            @error('role') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Créer l'utilisateur</button>
            <a href="{{ route('users.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection