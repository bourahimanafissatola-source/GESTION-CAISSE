@extends('layouts.app-caisse')
@section('titre', 'Utilisateurs')

@section('contenu')
<style>
    .page-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }
    .btn-new { background: var(--forest); color: #fff; text-decoration: none; padding: .55rem 1.1rem; border-radius: 4px; font-size: .85rem; font-weight: 500; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-new:hover { background: var(--gold); color: #fff; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; }
    .panel-toolbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .search-box { position: relative; width: 260px; }
    .search-box input { width: 100%; border: 1px solid var(--sage); border-radius: 4px; padding: .5rem .8rem .5rem 2.1rem; font-size: .85rem; }
    .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--ink-soft); }

    .cs-table { width: 100%; border-collapse: collapse; }
    .cs-table th { font-size: .75rem; color: var(--ink-soft); font-weight: 500; text-align: left; padding: .75rem 1.5rem; border-bottom: 1px solid var(--sage); }
    .cs-table td { padding: .8rem 1.5rem; border-bottom: 1px solid var(--sage); font-size: .88rem; vertical-align: middle; }
    .cs-table tr:last-child td { border-bottom: none; }
    .cs-table tbody tr:hover { background: var(--cream); }

    .user-cell { display: flex; align-items: center; gap: .7rem; }
    .avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--forest); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 600; flex-shrink: 0;
    }
    .user-name { font-weight: 500; }
    .user-email { color: var(--ink-soft); font-size: .8rem; }

    .role-badge { font-size: .72rem; padding: .25rem .65rem; border-radius: 4px; font-weight: 500; }
    .role-badge.administrateur { background: #EDE5F5; color: #5B3A9B; }
    .role-badge.superviseur { background: #FBF3E2; color: #93701C; }
    .role-badge.agent_confirme { background: #E7F1EC; color: var(--pos); }
    .role-badge.operateur { background: #E9EDF4; color: #3B5A8A; }

    .actions a, .actions button { color: var(--ink-soft); text-decoration: none; margin-right: .6rem; background: none; border: none; padding: 0; cursor: pointer; font-size: .95rem; }
    .actions a.edit:hover { color: var(--forest); }
    .actions .delete:hover { color: var(--neg); }
    .cs-empty { padding: 3rem 1.5rem; text-align: center; color: var(--ink-soft); }
</style>

<div class="page-head">
    <div>
        <h1>Utilisateurs</h1>
        <p>Gérer les comptes et les profils d'accès à l'application.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-new">
        <i class="bi bi-plus-lg"></i> Nouvel utilisateur
    </a>
</div>

<div class="cs-panel">
    <div class="panel-toolbar">
        <span style="font-size:.8rem; color: var(--ink-soft);">{{ ($users ?? collect())->count() }} utilisateur(s)</span>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="rechercheUsers" placeholder="Rechercher...">
        </div>
    </div>

    @if(($users ?? collect())->isEmpty())
        <div class="cs-empty">Aucun utilisateur enregistré.</div>
    @else
        <table class="cs-table" id="tableUsers">
            <thead>
                <tr><th>Utilisateur</th><th>Profil</th><th>Créé le</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <div>
                                <div class="user-name">{{ $u->name }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="role-badge {{ $u->role }}">{{ ucfirst(str_replace('_',' ', $u->role)) }}</span></td>
                    <td>{{ $u->created_at->format('d/m/Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('users.edit', $u->id) }}" class="edit"><i class="bi bi-pencil"></i></a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="delete"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
document.getElementById('rechercheUsers')?.addEventListener('input', function () {
    const filtre = this.value.toLowerCase();
    document.querySelectorAll('#tableUsers tbody tr').forEach(function (ligne) {
        ligne.style.display = ligne.textContent.toLowerCase().includes(filtre) ? '' : 'none';
    });
});
</script>
@endsection