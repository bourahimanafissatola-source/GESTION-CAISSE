<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Gestion de caisse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --forest: #0F3D2E;
            --forest-dark: #0A2B20;
            --gold: #B8923D;
            --cream: #F7F5F0;
            --ink: #1C1E1B;
            --ink-soft: #5B6660;
            --sage: #E4E9E1;
            --neg: #A6453D;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; color: var(--ink); }

        .auth-split { display: flex; min-height: 100vh; }

        .auth-brand {
            width: 44%;
            background: linear-gradient(160deg, var(--forest) 0%, var(--forest-dark) 100%);
            color: #EDEFE9;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .auth-brand h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 .3rem;
        }
        .auth-brand p.tagline {
            color: rgba(237,239,233,.6);
            font-size: .85rem;
            margin: 0;
        }
        .auth-brand .quote {
            font-size: 1.05rem;
            line-height: 1.6;
            max-width: 380px;
            color: rgba(237,239,233,.9);
        }
        .auth-brand .quote .mark { color: var(--gold); font-size: 1.8rem; line-height: 0; vertical-align: -.4rem; }
        .auth-brand .footnote { font-size: .75rem; color: rgba(237,239,233,.45); }

        .auth-form-side {
            flex: 1;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-card { width: 100%; max-width: 380px; }
        .auth-card h2 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .3rem; }
        .auth-card p.sub { color: var(--ink-soft); font-size: .87rem; margin: 0 0 1.8rem; }

        .form-row { margin-bottom: 1.1rem; }
        .form-row label { display: block; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
        .form-row input {
            width: 100%; border: 1px solid var(--sage); border-radius: 4px;
            padding: .65rem .85rem; font-size: .9rem; font-family: inherit; background: #fff;
        }
        .form-row input:focus { outline: none; border-color: var(--forest); }

        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.4rem; font-size: .82rem; }
        .form-options label { display: flex; align-items: center; gap: .4rem; color: var(--ink-soft); cursor: pointer; }
        .form-options a { color: var(--forest); text-decoration: none; }
        .form-options a:hover { text-decoration: underline; }

        .btn-primary {
            width: 100%; background: var(--forest); color: #fff; border: none;
            padding: .75rem; border-radius: 4px; font-size: .92rem; font-weight: 500; cursor: pointer;
        }
        .btn-primary:hover { background: var(--gold); }

        .status-msg { background: #E7F1EC; color: #2F6B4F; padding: .7rem 1rem; border-radius: 4px; font-size: .82rem; margin-bottom: 1.2rem; }
        .error-box { background: #F5E9E7; color: var(--neg); padding: .8rem 1rem; border-radius: 4px; font-size: .8rem; margin-bottom: 1.2rem; }
        .error-box ul { margin: 0; padding-left: 1.1rem; }

        .switch-link { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: var(--ink-soft); }
        .switch-link a { color: var(--forest); font-weight: 500; text-decoration: none; }
        .switch-link a:hover { text-decoration: underline; }
/* ================================
   RESPONSIVE - CONNEXION
   ================================ */

@media (max-width: 768px) {

    .auth-split {
        flex-direction: column;
        min-height: 100vh;
    }

    .auth-brand {
        width: 100%;
        min-height: auto;
        padding: 1.5rem;
        gap: 1.5rem;
    }

    .auth-brand h1 {
        font-size: 1.3rem;
    }

    .auth-brand p.tagline {
        font-size: .8rem;
    }

    .auth-brand .quote {
        font-size: .9rem;
        line-height: 1.5;
        max-width: 100%;
    }

    .auth-brand .quote .mark {
        font-size: 1.5rem;
    }

    .auth-brand .footnote {
        font-size: .7rem;
    }

    .auth-form-side {
        width: 100%;
        padding: 2rem 1.25rem;
        align-items: flex-start;
    }

    .auth-card {
        width: 100%;
        max-width: 100%;
    }

    .auth-card h2 {
        font-size: 1.25rem;
    }

    .auth-card p.sub {
        font-size: .85rem;
        margin-bottom: 1.5rem;
    }

    .form-row input {
        padding: .75rem .85rem;
        font-size: 1rem;
    }

    .btn-primary {
        padding: .85rem;
        font-size: .95rem;
    }

    .form-options {
        flex-wrap: wrap;
        gap: .8rem;
        font-size: .8rem;
    }
}

@media (max-width: 400px) {

    .auth-brand {
        padding: 1.2rem;
    }

    .auth-form-side {
        padding: 1.5rem 1rem;
    }

    .form-options {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-options a {
        margin-top: .2rem;
    }
}


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
            <span class="mark">“</span>Chaque entrée notée, chaque sortie justifiée — une caisse suivie au franc près.
        </div>
        <div class="footnote">Accès réservé aux utilisateurs autorisés.</div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <h2>Connexion</h2>
            <p class="sub">Accédez à votre espace de gestion de caisse.</p>

            @session('status')
                <div class="status-msg">{{ $value }}</div>
            @endsession

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

<form method="POST" action="{{ route('login') }}" autocomplete="off">
    @csrf

    {{-- Champs leurres invisibles : absorbent l'auto-remplissage de Chrome --}}
    <input type="text" name="fake_email" style="position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
    <input type="password" name="fake_password" style="position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">

    <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
    </div>

    <div class="form-row">
        <label>Mot de passe</label>
        <input type="password" name="password" required
               autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');">
    </div>

    <div class="form-options">
        <label>
            <input type="checkbox" name="remember" style="width:auto;">
            Se souvenir de moi
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
        @endif
    </div>

    <button type="submit" class="btn-primary">Se connecter</button>
</form>
        </div>
    </div>
</div>

</body>
</html>