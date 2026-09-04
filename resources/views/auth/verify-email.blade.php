<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'email — Gestion de caisse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --forest: #0F3D2E; --forest-dark: #0A2B20; --gold: #B8923D;
            --cream: #F7F5F0; --ink: #1C1E1B; --ink-soft: #5B6660; --sage: #E4E9E1;
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
        .auth-card { width: 100%; max-width: 400px; text-align: center; }
        .auth-card .icon { font-size: 2.2rem; color: var(--forest); margin-bottom: 1rem; }
        .auth-card h2 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .6rem; }
        .auth-card p.sub { color: var(--ink-soft); font-size: .87rem; margin: 0 0 1.6rem; line-height: 1.6; }
        .status-msg { background: #E7F1EC; color: #2F6B4F; padding: .7rem 1rem; border-radius: 4px; font-size: .82rem; margin-bottom: 1.2rem; }
        .btn-primary {
            width: 100%; background: var(--forest); color: #fff; border: none;
            padding: .75rem; border-radius: 4px; font-size: .92rem; font-weight: 500; cursor: pointer; margin-bottom: .8rem;
        }
        .btn-primary:hover { background: var(--gold); }
        .btn-link { background: none; border: none; color: var(--ink-soft); font-size: .85rem; cursor: pointer; text-decoration: underline; }
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
            <span class="mark">“</span>Une dernière étape avant d'accéder à votre caisse.
        </div>
        <div class="footnote">Accès réservé aux utilisateurs autorisés.</div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <i class="bi bi-envelope-check icon"></i>
            <h2>Vérifiez votre email</h2>
            <p class="sub">Merci de votre inscription ! Avant de commencer, cliquez sur le lien que nous venons de vous envoyer par email pour confirmer votre adresse.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="status-msg">Un nouveau lien de vérification a été envoyé à l'adresse fournie lors de l'inscription.</div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">Renvoyer l'email de vérification</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-link">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>