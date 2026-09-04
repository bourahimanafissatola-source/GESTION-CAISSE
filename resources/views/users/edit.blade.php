@extends('layouts.app-caisse')
@section('titre', 'Modifier l\'utilisateur')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .form-card { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 2rem; max-width: 560px; }
    .form-row { margin-bottom: 1.3rem; }
    .form-row label { display: block; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
    .form-row .hint { font-size: .75rem; color: var(--ink-soft); font-weight: 400; margin-left: .3rem; }
    .form-row input {
        width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .6rem .8rem;
        font-size: .9rem; font-family: inherit; color: var(--ink);
    }
    .form-row input:focus { outline: none; border-color: var(--forest); }
    .role-options { display: grid; gap: .6rem; }
    .role-option { border: 1px solid var(--sage); border-radius: 4px; padding: .7rem .9rem; display: flex; align-items: flex-start; gap: .6rem; cursor: pointer; font-size: .85rem; }
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
    <h1>Modifier l'utilisateur</h1>
    <p>{{ $user->name }}</p>
</div>

<div class="form-card">
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <label>Nom complet</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-row">
            <label>Nouveau mot de passe <span class="hint">laisser vide pour ne pas changer</span></label>
            <input type="password" name="password">
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="form-row">
            <label>Profil d'accès</label>
            <div class="role-options">
                @foreach(['operateur' => ['Opérateur','Ajout uniquement — ne peut ni modifier ni supprimer'], 'agent_confirme' => ['Agent confirmé','Ajout et modification — ne peut pas supprimer'], 'superviseur' => ['Superviseur','Ajout, modification et suppression, validation des sorties'], 'administrateur' => ['Administrateur','Accès total, gestion des utilisateurs et de la configuration']] as $value => $labels)
                <label class="role-option">
                    <input type="radio" name="role" value="{{ $value }}" {{ old('role', $user->role) === $value ? 'checked' : '' }}>
                    <div><div class="r-title">{{ $labels[0] }}</div><div class="r-desc">{{ $labels[1] }}</div></div>
                </label>
                @endforeach
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary"><i class="bi bi-check-lg"></i> Enregistrer</button>
            <a href="{{ route('users.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection