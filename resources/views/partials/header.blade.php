<header class="header">

    <div>
        <h2>@yield('titre')</h2>
        <small>{{ now()->format('d F Y') }}</small>
    </div>

    <div class="header-right">

        <button class="notification">
            🔔
        </button>

        <div class="profile">

            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>

            <div>
                <strong>{{ Auth::user()->name }}</strong>
                <small>Administrateur</small>
            </div>

        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout-btn">
                Déconnexion
            </button>
        </form>

    </div>

</header>