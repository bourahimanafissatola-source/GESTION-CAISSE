<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titre') — Gestion de caisse</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --forest: #0F3D2E;
            --gold: #B8923D;
            --cream: #F7F5F0;
            --ink: #1C1E1B;
            --ink-soft: #5B6660;
            --sage: #E4E9E1;
            --pos: #2F6B4F;
            --neg: #A6453D;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--cream); color: var(--ink); font-family: 'Segoe UI', sans-serif; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar { width: 240px; flex-shrink: 0; background: var(--forest); color: #EDEFE9; }
        .logo { padding: 1.5rem 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .logo h2 { font-weight: 500; font-size: 1.15rem; margin: 0; }
        .logo p { font-size: .7rem; letter-spacing: .03em; color: rgba(237,239,233,.55); margin: .2rem 0 0; }
        .menu { display: flex; flex-direction: column; padding-top: .5rem; }
        .menu a { color: rgba(237,239,233,.75); text-decoration: none; font-size: .92rem; padding: .65rem 1.25rem; border-left: 3px solid transparent; display: flex; align-items: center; gap: .65rem; }
        .menu a:hover { background: rgba(255,255,255,.05); color: #fff; }
        .menu a.active { background: rgba(184,146,61,.14); border-left-color: var(--gold); color: #fff; }
        .main { flex: 1; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid var(--sage); padding: 1.1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .topbar h2 { font-weight: 500; font-size: 1.4rem; margin: 0; }
        .topbar strong { font-weight: 500; font-size: .85rem; color: var(--ink-soft); border: 1px solid var(--sage); border-radius: 999px; padding: .35rem .9rem; }
        .content { padding: 2rem; }
    </style>
</head>
<body>
<div class="layout">
    <div class="sidebar">
        <div class="logo">
            <h2>Gestion de caisse</h2>
            <p>Version PRO 2026</p>
        </div>
        <div class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid"></i> Tableau de bord</a>
            <a href="{{ route('entrees.index') }}" class="{{ request()->routeIs('entrees.*') ? 'active' : '' }}"><i class="bi bi-arrow-down-circle"></i> Entrées</a>
            <a href="{{ route('sorties.index') }}" class="{{ request()->routeIs('sorties.*') ? 'active' : '' }}"><i class="bi bi-arrow-up-circle"></i> Sorties</a>
            <a href="{{ route('categories-entrees.index') }}" class="{{ request()->routeIs('categories-entrees.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Catégories entrées</a>
            <a href="{{ route('categories-sorties.index') }}" class="{{ request()->routeIs('categories-sorties.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Catégories sorties</a>
            {{-- UTILISATEURS --}}
             <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}"> <i class="bi bi-people"></i> Utilisateurs </a> 
             {{-- RAPPORTS --}}
              <a href="{{ route('rapports.index') }}" class="{{ request()->routeIs('rapports.*') ? 'active' : '' }}"> <i class="bi bi-file-earmark-text"></i> Rapports </a>
             {{-- PROFIL --}} 
             <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"> <i class="bi bi-person-circle"></i> Mon profil </a>
        </div>
    </div>
    <div class="main">
        <div class="topbar">
            <h2>@yield('titre')</h2>
            <strong><i class="bi bi-person"></i> {{ Auth::user()->name }}</strong>
        </div>
        <div class="content">
            @yield('contenu')
        </div>
    </div>
</div>
</body>
</html>