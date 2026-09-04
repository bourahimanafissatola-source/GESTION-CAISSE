<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe — Gestion de caisse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --forest: #0F3D2E; --forest-dark: #0A2B20; --gold: #B8923D;
            --cream: #F7F5F0; --ink: #1C1E1B; --ink-soft: #5B6660; --sage: #E4E9E1; --neg: #A6453D;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; color: var(--ink); }
        .auth-split { display: flex; min-height: 100vh; }
        .auth-brand {
            width: 44%; background: linear-gradient(160deg, var(--forest) 0%, var(--forest-dark) 100%);
            color: #EDEFE9; padding: 3rem; display: flex; flex-direction: column; justify-content: space-between;
        }
        .auth-brand h1 { font-size: 1.5rem; font-weight: 600; margin: 0 0 .3rem; }
        .auth-brand p.tagline { color: rgba(237,239,233,.6); font-size: .85rem; margin: 0; }
        .auth-brand .quote { font-size: 1.05rem; line-height: 1.6; max-width: 380px; color: rgba(237,239,233,.9); }
        .auth-brand .quote .mark { color: var(--gold); font-size: 1.8rem; line-height: 0; vertical-align: -.4rem; }
        .auth-brand .footnote { font-size: .75rem; color: rgba(237,239,233,.45); }
        .auth-form-side { flex: 1; background: var(--cream); display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-card { width: 100%; max-width: 380px; }
        .auth-card h2 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .3rem; }
        .auth-card p.sub { color: var(--ink-soft); font-size: .87rem; margin: 0 0 1.6rem; }
        .form-row { margin-bottom: 1.1rem; }
        .form-row label { display: block; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
        .form-row input {
            width: 100%; border: 1px solid var(--sage); border-radius: 4px;
            padding: .65rem .85rem; font-size: .9rem; font-family: inherit; background: #fff;
        }
        .form-row input:focus { outline: none; border-color: var(--forest); }
        .btn-primary {
            width: 100%; background: var(--forest); color: #fff; border: none;
            padding: .75rem; border-radius: 4px; font-size: .92rem; font-weight: 500; cursor: pointer; margin-top: .3rem;
        }
        .btn-primary:hover { background: var(--gold); }
        .error-box { background: #F5E9E7; color: var(--neg); padding: .8rem 1rem; border-radius: 4px; font-size: .8rem; margin-bottom: 1.2rem; }
        .error-box ul { margin: 0; padding-left: 1.1rem; }
    </style>
</head>
<body>
<div class="auth-split">
    <div class="auth-brand">
        <div>
            <h1>Gestion de caisse</h1>
            <p class="tagline">Version PRO 2026</p>
        </div>
        <div class="quote">
            <span class="mark">“</span>Choisissez un nouveau mot de passe pour retrouver votre accès.
        </div>
        <div class="footnote">Accès réservé aux utilisateurs autorisés.</div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <h2>Nouveau mot de passe</h2>
            <p class="sub">Choisissez un mot de passe sécurisé pour votre compte.</p>

            @if ($errors->any())
                <div class="error-box">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-row">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                </div>

                <div class="form-row">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" required autocomplete="new-password">
                </div>

                <div class="form-row">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-primary">Réinitialiser le mot de passe</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>