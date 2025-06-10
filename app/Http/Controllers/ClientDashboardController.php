<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Récupère l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les paramètres de recherche
        $search = $request->input('search');
        $status = $request->input('status');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Construire la requête pour les événements auxquels le client est inscrit
        $query = $user->participatedEvents();

        // Appliquer les filtres de recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateStart) {
            $query->whereDate('date', '>=', $dateStart);
        }

        if ($dateEnd) {
            $query->whereDate('date', '<=', $dateEnd);
        }

        // Récupérer les événements avec pagination
        $events = $query->orderBy('date', 'asc')->paginate(10);

        // Retourne la vue en passant les événements et les paramètres de recherche
        return view('dashboard.client', compact('events', 'search', 'status', 'dateStart', 'dateEnd'));
    }
}
