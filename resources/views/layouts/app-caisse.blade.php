<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titre') — Gestion de caisse</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
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

        /* Sidebar */
          .sidebar {
    width: 240px; flex-shrink: 0; background: var(--forest); color: #EDEFE9;
    transition: transform .25s ease;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
}
        .logo { padding: 1.5rem 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .logo h2 { font-weight: 500; font-size: 1.15rem; margin: 0; }
        .logo p { font-size: .7rem; letter-spacing: .03em; color: rgba(237,239,233,.55); margin: .2rem 0 0; }
        .menu { display: flex; flex-direction: column; padding-top: .5rem; }
        .menu a {
            color: rgba(237,239,233,.75); text-decoration: none; font-size: .92rem;
            padding: .65rem 1.25rem; border-left: 3px solid transparent;
            display: flex; align-items: center; gap: .65rem;
        }
        .menu a:hover { background: rgba(255,255,255,.05); color: #fff; }
        .menu a.active { background: rgba(184,146,61,.14); border-left-color: var(--gold); color: #fff; }

        /* Main */
        .main { flex: 1; min-width: 0; }
        .topbar {
            background: #fff; border-bottom: 1px solid var(--sage); padding: 1.1rem 2rem;
            display: flex; justify-content: space-between; align-items: center; gap: 1rem;
        }
        .topbar-left { display: flex; align-items: center; gap: 1rem; min-width: 0; }
        .topbar h2 { font-weight: 500; font-size: 1.4rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .menu-toggle {
            display: none; background: none; border: none; font-size: 1.4rem; color: var(--forest);
            cursor: pointer; padding: 0; line-height: 1;
        }

        .user-menu { position: relative; flex-shrink: 0; }
        .user-pill {
            font-weight: 500; font-size: .85rem; color: var(--ink-soft);
            border: 1px solid var(--sage); border-radius: 999px; padding: .35rem .9rem;
            display: flex; align-items: center; gap: .5rem; cursor: pointer; background: #fff;
        }
        .user-pill:hover { border-color: var(--forest); }
        .user-dropdown {
            display: none; position: absolute; right: 0; top: calc(100% + .5rem);
            background: #fff; border: 1px solid var(--sage); border-radius: 6px;
            box-shadow: 0 4px 14px rgba(0,0,0,.08); min-width: 180px; z-index: 50; overflow: hidden;
        }
        .user-dropdown.open { display: block; }
        .user-dropdown a, .user-dropdown button {
            display: flex; align-items: center; gap: .6rem; width: 100%;
            padding: .7rem 1rem; font-size: .85rem; color: var(--ink); text-decoration: none;
            background: none; border: none; text-align: left; cursor: pointer;
        }
        .user-dropdown a:hover, .user-dropdown button:hover { background: var(--cream); }
        .user-dropdown .logout-btn { color: var(--neg); border-top: 1px solid var(--sage); }

        .content { padding: 2rem; overflow-x: auto; }

        .sidebar-overlay { display: none; }

        /* ---- Responsive ---- */
        @media (max-width: 900px) {
            .content { padding: 1.25rem; }
            .topbar { padding: .9rem 1.25rem; }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed; top: 0; left: 0; height: 100vh; z-index: 100;
                transform: translateX(-100%);
            }
            .sidebar.open { transform: translateX(0); }
            .menu-toggle { display: block; }
            .topbar h2 { font-size: 1.15rem; }
            .sidebar-overlay {
                display: none; position: fixed; inset: 0; background: rgba(15,61,46,.35); z-index: 90;
            }
            .sidebar-overlay.open { display: block; }
        }

        @media (max-width: 560px) {
            .content { padding: 1rem; }
            .topbar { flex-wrap: wrap; }
        }
        /* ---- Tableaux responsives ---- */
.cs-panel {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.cs-table {
    min-width: 640px;
}

/* ---- En-tête de page qui s'adapte ---- */
.page-head {
    flex-wrap: wrap;
    gap: .8rem;
}

/* ---- Barre de recherche + compteur qui s'adaptent ---- */
.panel-toolbar {
    flex-wrap: wrap;
    gap: .8rem;
}

@media (max-width: 768px) {
    .page-head {
        flex-direction: column;
        align-items: flex-start;
    }
    .btn-new {
        width: 100%;
        justify-content: center;
    }
    .panel-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .search-box {
        width: 100%;
    }
    .form-card {
        max-width: 100%;
        padding: 1.4rem;
    }
    .form-grid-2 {
        grid-template-columns: 1fr;
    }
    .cs-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .cs-ledger {
        flex-direction: column;
    }
    .cs-ledger .stat {
        border-right: none;
        border-bottom: 1px solid var(--sage);
    }
    .cs-ledger .stat:last-child {
        border-bottom: none;
    }
    .cs-ledger .stat .value {
        font-size: 1.3rem;
        word-break: break-word;
    }
}
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>

<body>

<div class="layout">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="logo">
            <h2>Gestion de caisse</h2>
            <p>Version PRO 2026</p>
        </div>

        <div class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Tableau de bord
            </a>
            <a href="{{ route('entrees.index') }}" class="{{ request()->routeIs('entrees.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-down-circle"></i> Entrées
            </a>
            <a href="{{ route('sorties.index') }}" class="{{ request()->routeIs('sorties.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-up-circle"></i> Sorties
            </a>
            <a href="{{ route('categories-entrees.index') }}" class="{{ request()->routeIs('categories-entrees.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Catégories entrées
            </a>
            <a href="{{ route('categories-sorties.index') }}" class="{{ request()->routeIs('categories-sorties.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Catégories sorties
            </a>
         
                    @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
        </a>

        <a href="{{ route('activites.index') }}" class="{{ request()->routeIs('activites.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Journal d'activité
        </a>

        <a href="{{ route('rapports.index') }}" class="{{ request()->routeIs('rapports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Rapports
        </a>
    @endif
@endauth
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Mon profil
            </a>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                    <i class="bi bi-list"></i>
                </button>
                <h2>@yield('titre')</h2>
            </div>

            <div class="user-menu">
                <div class="user-pill" id="userPillToggle">
                    <i class="bi bi-person"></i> {{ Auth::user()->name }}
                    <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <a href="{{ route('profile.edit') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Se déconnecter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="content">
            @yield('contenu')
        </div>
    </div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuToggle = document.getElementById('menuToggle');

    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }
    menuToggle?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    const userPillToggle = document.getElementById('userPillToggle');
    const userDropdown = document.getElementById('userDropdown');
    userPillToggle?.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('open');
    });
    document.addEventListener('click', function () {
        userDropdown?.classList.remove('open');
    });
</script>

</body>
</html>