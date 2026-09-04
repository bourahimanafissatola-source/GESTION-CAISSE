<aside class="sidebar">

    <div class="logo">
        <div class="logo-icon">💰</div>

        <div>
            <h1>Gestion Caisse</h1>
            <p>Version Entreprise 2026</p>
        </div>
    </div>

    <nav class="menu">

        <a href="{{ route('dashboard') }}" class="active">
            <span>🏠</span>
            Tableau de bord
        </a>

        <a href="{{ route('entrees.index') }}">
            <span>💰</span>
            Entrées
        </a>

        <a href="{{ route('sorties.index') }}">
            <span>💸</span>
            Sorties
        </a>

        <a href="{{ route('categories-entrees.index') }}">
            <span>📂</span>
            Catégories Entrées
        </a>

        <a href="{{ route('categories-sorties.index') }}">
            <span>📁</span>
            Catégories Sorties
        </a>

        <a href="#">
            <span>👥</span>
            Utilisateurs
        </a>

        <a href="#">
            <span>📊</span>
            Rapports
        </a>

        <a href="{{ route('profile.edit') }}">
            <span>⚙️</span>
            Mon profil
        </a>

    </nav>

</aside>