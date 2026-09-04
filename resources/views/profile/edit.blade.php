@extends('layouts.app-caisse')

@section('titre', 'Mon profil')

@section('contenu')

<style>
    .profile-page {
        max-width: 900px;
    }

    .profile-head {
        margin-bottom: 1.5rem;
    }

    .profile-head h1 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0 0 .25rem;
    }

    .profile-head p {
        color: var(--ink-soft);
        font-size: .85rem;
        margin: 0;
    }

    .profile-card {
        background: #fff;
        border: 1px solid var(--sage);
        border-radius: 6px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .profile-card-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--sage);
    }

    .profile-card-head h2 {
        margin: 0;
        font-size: .95rem;
        font-weight: 600;
    }

    .profile-card-head p {
        margin: .3rem 0 0;
        color: var(--ink-soft);
        font-size: .8rem;
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.1rem;
    }

    .form-group label {
        display: block;
        font-size: .8rem;
        color: var(--ink-soft);
        margin-bottom: .35rem;
    }

    .form-control {
        width: 100%;
        max-width: 500px;
        border: 1px solid var(--sage);
        border-radius: 4px;
        padding: .65rem .75rem;
        font-size: .88rem;
        color: var(--ink);
        background: #fff;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--forest);
    }

    .btn-primary {
        background: var(--forest);
        color: #fff;
        border: none;
        padding: .6rem 1.2rem;
        border-radius: 4px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--gold);
    }

    .btn-danger {
        background: var(--neg);
        color: #fff;
        border: none;
        padding: .6rem 1.2rem;
        border-radius: 4px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .success-message {
        color: var(--pos);
        font-size: .82rem;
        margin-left: .7rem;
    }

    .error-message {
        color: var(--neg);
        font-size: .78rem;
        margin-top: .3rem;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid var(--sage);
    }

    .profile-avatar {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: var(--forest);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .profile-info h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .profile-info p {
        margin: .2rem 0 0;
        color: var(--ink-soft);
        font-size: .8rem;
    }

    .delete-zone {
        border-top: 1px solid var(--sage);
        margin-top: 1.5rem;
        padding-top: 1.5rem;
    }
</style>

<div class="profile-page">

```
<div class="profile-head">
    <h1>Mon profil</h1>
    <p>Gérez vos informations personnelles et la sécurité de votre compte.</p>
</div>

{{-- INFORMATIONS DU COMPTE --}}
<div class="profile-card">

    <div class="profile-card-head">
        <h2>Informations personnelles</h2>
        <p>Modifiez votre nom et votre adresse email.</p>
    </div>

    <div class="profile-card-body">

        <div class="profile-info">

            <div class="profile-avatar">
                <i class="bi bi-person"></i>
            </div>

            <div>
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->email }}</p>
            </div>

        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name">Nom</label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-control"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                >

                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control"
                    value="{{ old('email', $user->email) }}"
                    required
                >

                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-check2"></i>
                Enregistrer
            </button>

            @if (session('status') === 'profile-updated')
                <span class="success-message">
                    Informations mises à jour.
                </span>
            @endif

        </form>

    </div>

</div>


{{-- MOT DE PASSE --}}
<div class="profile-card">

    <div class="profile-card-head">
        <h2>Modifier le mot de passe</h2>
        <p>Utilisez un mot de passe long et sécurisé pour protéger votre compte.</p>
    </div>

    <div class="profile-card-body">

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>

                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    class="form-control"
                    required
                >

                @error('current_password', 'updatePassword')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    required
                >

                @error('password', 'updatePassword')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>

                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="form-control"
                    required
                >

                @error('password_confirmation', 'updatePassword')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-shield-lock"></i>
                Modifier le mot de passe
            </button>

            @if (session('status') === 'password-updated')
                <span class="success-message">
                    Mot de passe mis à jour.
                </span>
            @endif

        </form>

    </div>

</div>


{{-- SUPPRESSION DU COMPTE --}}
<div class="profile-card">

    <div class="profile-card-head">
        <h2>Supprimer le compte</h2>
        <p>La suppression du compte est définitive.</p>
    </div>

    <div class="profile-card-body">

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label for="delete_password">
                    Confirmez votre mot de passe
                </label>

                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="form-control"
                    required
                >

                @error('password', 'userDeletion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-danger">
                <i class="bi bi-trash"></i>
                Supprimer mon compte
            </button>

        </form>

    </div>

</div>
```

</div>

@endsection
