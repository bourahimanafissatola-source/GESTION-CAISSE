<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrateur', 'superviseur'])) {
            abort(403, 'Accès réservé aux administrateurs et superviseurs.');
        }

        $logs = ActivityLog::with('user')
            ->when($request->utilisateur, fn($q) => $q->where('user_id', $request->utilisateur))
            ->orderByDesc('created_at')
            ->paginate(20);

        $utilisateurs = \App\Models\User::orderBy('name')->get();

        $enLigne = \App\Models\User::where('last_seen_at', '>=', now()->subMinutes(5))
            ->orderBy('last_seen_at', 'desc')
            ->get();

        return view('activites.index', compact('logs', 'utilisateurs', 'enLigne'));
    }
}