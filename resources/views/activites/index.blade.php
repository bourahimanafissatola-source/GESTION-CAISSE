@extends('layouts.app-caisse')
@section('titre', 'Journal d\'activité')

@section('contenu')
<style>
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.3rem; font-weight: 600; margin: 0 0 .25rem; }
    .page-head p { color: var(--ink-soft); font-size: .85rem; margin: 0; }

    .filter-bar { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: flex-end; }
    .filter-bar label { display: block; font-size: .75rem; color: var(--ink-soft); margin-bottom: .3rem; }
    .filter-bar select { border: 1px solid var(--sage); border-radius: 4px; padding: .5rem .7rem; font-size: .85rem; }
    .btn-primary { background: var(--forest); color: #fff; border: none; padding: .5rem 1.1rem; border-radius: 4px; font-size: .85rem; cursor: pointer; }

    .cs-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; overflow-x: auto; }
    .timeline { list-style: none; margin: 0; padding: 0; }
    .timeline li {
        display: flex; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid var(--sage);
    }
    .timeline li:last-child { border-bottom: none; }
    .t-icon {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .t-icon.creee { background: #E7F1EC; color: var(--pos); }
    .t-icon.modifiee { background: #EEF3F0; color: var(--forest); }
    .t-icon.supprimee, .t-icon.rejetee { background: #F5E9E7; color: var(--neg); }
    .t-icon.validee { background: #FBF3E2; color: #93701C; }
    .t-body { flex: 1; min-width: 0; }
    .t-desc { font-size: .88rem; }
    .t-meta { font-size: .75rem; color: var(--ink-soft); margin-top: .2rem; }
    .cs-empty { padding: 3rem 1.5rem; text-align: center; color: var(--ink-soft); }
    .online-panel { background: #fff; border: 1px solid var(--sage); border-radius: 6px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
.online-panel h2 { font-size: .85rem; font-weight: 600; margin: 0 0 .8rem; color: var(--ink-soft); text-transform: uppercase; letter-spacing: .03em; }
.online-list { display: flex; flex-wrap: wrap; gap: .6rem; }
.online-chip { display: flex; align-items: center; gap: .5rem; background: var(--cream); border-radius: 20px; padding: .4rem .9rem .4rem .6rem; font-size: .82rem; }
.online-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--pos); flex-shrink: 0; box-shadow: 0 0 0 3px rgba(46,125,80,.15); }
.online-empty { color: var(--ink-soft); font-size: .82rem; }
</style>

<div class="page-head">
    <h1>Journal d'activité</h1>
    <p>Historique des actions effectuées par les utilisateurs.</p>
</div>
<div class="online-panel">
    <h2>Connectés actuellement</h2>
    @if($enLigne->isEmpty())
        <div class="online-empty">Aucun utilisateur en ligne pour le moment.</div>
    @else
        <div class="online-list">
            @foreach($enLigne as $u)
                <div class="online-chip">
                    <span class="online-dot"></span>
                    {{ $u->name }}
                </div>
            @endforeach
        </div>
    @endif
</div>

<form method="GET" class="filter-bar">
    <div>
        <label>Filtrer par utilisateur</label>
        <select name="utilisateur" onchange="this.form.submit()">
            <option value="">Tous les utilisateurs</option>
            @foreach($utilisateurs as $u)
                <option value="{{ $u->id }}" {{ request('utilisateur') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
</form>

<div class="cs-panel">
    @if($logs->isEmpty())
        <div class="cs-empty">Aucune activité enregistrée.</div>
    @else
        <ul class="timeline">
            @foreach($logs as $log)
            @php
                $categorie = str_contains($log->action, 'creee') ? 'creee'
                    : (str_contains($log->action, 'modifiee') ? 'modifiee'
                    : (str_contains($log->action, 'supprimee') ? 'supprimee'
                    : (str_contains($log->action, 'validee') ? 'validee'
                    : (str_contains($log->action, 'rejetee') ? 'rejetee' : 'modifiee'))));
                $icone = match($categorie) {
                    'creee' => 'bi-plus-circle',
                    'modifiee' => 'bi-pencil',
                    'supprimee' => 'bi-trash',
                    'validee' => 'bi-check-circle',
                    'rejetee' => 'bi-x-circle',
                    default => 'bi-dot',
                };
            @endphp
            <li>
                <div class="t-icon {{ $categorie }}"><i class="bi {{ $icone }}"></i></div>
                <div class="t-body">
                    <div class="t-desc">{{ $log->description }}</div>
                    <div class="t-meta">{{ $log->user->name ?? 'Utilisateur supprimé' }} · {{ $log->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </li>
            @endforeach
        </ul>
        <div style="padding: 1rem 1.5rem;">{{ $logs->links() }}</div>
    @endif
</div>
@endsection